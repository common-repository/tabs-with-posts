import $ from 'jquery';
import { hideUp, showUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

const firstOrderGroup = $( '#twrpb-order-setting__js-first-order-group' );
const secondOrderGroup = $( '#twrpb-order-setting__js-second-order-group' );
const thirdOrderGroup = $( '#twrpb-order-setting__js-third-order-group' );

const orderByClassName = 'twrpb-order-setting__js-orderby';

const orderGroups = [ firstOrderGroup, secondOrderGroup, thirdOrderGroup ];

$( hideOrShowUnnecessarySelectors );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowUnnecessarySelectors );

/**
 * Hide or show the next selectors for the order by and order type, making the
 * user experience better.
 */
function hideOrShowUnnecessarySelectors() {
	let hideNext = false;

	for ( let i = 0; i < orderGroups.length; i++ ) {
		if ( hideNext ) {
			hideUp( orderGroups[ i ] );
		} else {
			showUp( orderGroups[ i ] );
		}

		if ( orderGroups[ i ].find( `.${ orderByClassName }` ).val() === 'not_applied' ) {
			hideNext = true;
			hideUp( orderGroups[ i ].find( '.twrpb-order-setting__js-order-type' ) );
		} else {
			showUp( orderGroups[ i ].find( '.twrpb-order-setting__js-order-type' ) );
		}
	}
}

$( hideOrShowSelectedValues );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowSelectedValues );

function hideOrShowSelectedValues() {
	for ( let i = 0; i < orderGroups.length; i++ ) {
		orderGroups[ i ].find( 'option' ).removeAttr( 'disabled' );
	}

	for ( let i = 0; i < orderGroups.length; i++ ) {
		const selectedVal = orderGroups[ i ].find( `.${ orderByClassName }` ).val();
		if ( selectedVal !== 'not_applied' ) {
			for ( let j = i + 1; j < orderGroups.length; j++ ) {
				const orderBySelect = orderGroups[ j ].find( `.${ orderByClassName }` );
				const nextSelectedVal = orderBySelect.val();

				orderBySelect.find( `[value="${ selectedVal }"]` ).attr( 'disabled', 'disabled' );

				if ( nextSelectedVal === selectedVal ) {
					orderBySelect.val( 'not_applied' );
					orderBySelect.trigger( 'change' );
				}
			}
		}
	}
}

// #region -- Hide/Show Post ID warning notice if selected.

const postIdOrderWarning = $( '#twrpb-setting-note__ordering_by_post_id_warning' );

$( hideOrShowPostIdWarning );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowPostIdWarning );

function hideOrShowPostIdWarning() : void {
	hideOrShowNoteOnSelect( [ 'ID', 'parent' ], postIdOrderWarning );
}

// #endregion -- Hide/Show Post ID warning notice if selected.

// #region -- Hide/Show order by comments warning.

const commentsOrderWarning = $( '#twrpb-setting-note__order_by_comments_warning' );

$( hideOrShowCommentsWarning );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowCommentsWarning );

function hideOrShowCommentsWarning() : void {
	let showWarning = false;
	const values = [];

	for ( let i = 0; i < orderGroups.length; i++ ) {
		const orderByVal = String( $( orderGroups[ i ] ).find( `.${ orderByClassName }` ).val() );
		values.push( orderByVal );
	}

	if ( values.indexOf( 'comment_count' ) === 0 && values.indexOf( 'not_applied' ) === 1 ) {
		showWarning = true;
	}

	if ( showWarning ) {
		showUp( commentsOrderWarning );
	} else {
		hideUp( commentsOrderWarning );
	}
}

// #endregion -- Hide/Show order by comments warning.

// #region -- Hide/Show Search notice if selected.

const searchOrderNote = $( '#twrpb-setting-note__order_by_search_note' );

$( hideOrShowSearchNote );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowSearchNote );

function hideOrShowSearchNote() : void {
	hideOrShowNoteOnSelect( 'relevance', searchOrderNote );
}

// #endregion -- Hide/Show Search notice if selected.

// #region -- Hide/Show Meta notice if selected.

const metaNote = $( '#twrpb-setting-note__order_by_meta_note' );

$( hideOrShowMetaNote );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowMetaNote );

function hideOrShowMetaNote() : void {
	hideOrShowNoteOnSelect( [ 'meta_value', 'meta_value_num' ], metaNote );
}

// #endregion -- Hide/Show Meta notice if selected.

// #region -- Hide/Show Post In notice if selected.

const postsInNote = $( '#twrpb-setting-note__order_by_posts_in_note' );

$( hideOrShowPostInNote );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowPostInNote );

function hideOrShowPostInNote() : void {
	hideOrShowNoteOnSelect( 'post__in', postsInNote );
}

// #endregion -- Hide/Show Post In notice if selected.

// #region -- Hide/Show Views notice if selected.

const viewsNote = $( '#twrpb-setting-note__order_by_views_in_note' );

$( hideOrShowViewsNote );
$( document ).on( 'change', `.${ orderByClassName }`, hideOrShowViewsNote );

function hideOrShowViewsNote() : void {
	hideOrShowNoteOnSelect( [ 'twrp_post_views_order', 'twrp_post_rating_order', 'twrp_post_rating_count_order' ], viewsNote );
}

// #endregion -- Hide/Show Views notice if selected.

// #region -- Hide/Show Note if Order By option is selected.

function hideOrShowNoteOnSelect( $orderByValue: string|Array<string>, $note: JQuery ) {
	let showWarning = false;
	const values = [];

	for ( let i = 0; i < orderGroups.length; i++ ) {
		const orderByVal = String( $( orderGroups[ i ] ).find( `.${ orderByClassName }` ).val() );

		if ( orderByVal === 'not_applied' ) {
			break;
		}

		values.push( orderByVal );
	}

	if ( typeof $orderByValue === 'string' || $orderByValue instanceof String ) {
		if ( values.indexOf( $orderByValue ) !== -1 ) {
			showWarning = true;
		}
	} else {
		for ( let i = 0; i < $orderByValue.length; i++ ) {
			if ( values.indexOf( $orderByValue[ i ] ) !== -1 ) {
				showWarning = true;
			}
		}
	}

	if ( showWarning ) {
		showUp( $note );
	} else {
		hideUp( $note );
	}
}

// #endregion -- Hide/Show Note if OrderBy option is selected.

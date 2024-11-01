import $ from 'jquery';
import { showUp, hideUp } from '../admin-blocks/twrpb-hidden/twrpb-hidden';

declare const wp: any;
declare const ajaxurl: any;

// #region -- Hide or show custom date format depending on human readable date format.

const humanReadableEnabledCheckboxSelector = '#twrpb-general-settings__human_readable_date-setting-true';
const customDateWrapperSelector = '#twrpb-general-settings__date_format-wrapper';

function showOrHideCustomDateFormat() {
	const humanReadableEnabledCheckbox = $( humanReadableEnabledCheckboxSelector );
	const customDateWrapper = $( customDateWrapperSelector );

	if ( humanReadableEnabledCheckbox.is( ':checked' ) ) {
		hideUp( customDateWrapper );
	} else {
		showUp( customDateWrapper );
	}
}

$( showOrHideCustomDateFormat );
$( document ).on( 'click', showOrHideCustomDateFormat );

// #endregion -- Hide or show custom date format depending on human readable date format.

// #region -- Add an Icon preview on the right.

const iconWrapperClassName = 'twrpb-general-settings__icon-preview';
const iconAndSvgElem = '<span class="' + iconWrapperClassName + '"><svg><use/></svg></span>';
const selectNames = [ 'author_icon', 'date_icon', 'category_icon', 'comments_icon', 'comments_disabled_icon', 'views_icon' ];

$( updateAllIcons );
$( document ).on( 'change', getDocumentOnClickSelector(), onChangeHandler );

/**
 * Handler for the selection of the icon. Update the preview of changed icon.
 */
function onChangeHandler( this: JQuery ) {
	const selectName = String( $( this ).attr( 'name' ) );

	if ( ! selectName ) {
		return;
	}

	updateIcon( selectName );
}

/**
 * Update all icons on all selectors.
 */
function updateAllIcons() {
	for ( const selectName of selectNames ) {
		updateIcon( selectName );
	}
}

/**
 * Given a selector name, update the icon corresponding to it.
 */
function updateIcon( selectName: string ): void {
	const selectElem = $( 'select[name="' + selectName + '"' );

	if ( selectElem.length === 0 ) {
		return;
	}

	const selectWrapper = selectElem.parent();
	const iconId = String( selectElem.val() );

	selectWrapper.find( '.' + iconWrapperClassName ).remove();
	$( iconAndSvgElem ).insertBefore( selectElem );
	selectWrapper.find( 'use' ).attr( 'href', '#' + iconId );
}

/**
 * Get the jQuery selector to attach the event to the document.
 */
function getDocumentOnClickSelector(): string {
	let documentOnClickSelector = '';

	for ( const selectName of selectNames ) {
		if ( documentOnClickSelector ) {
			documentOnClickSelector = documentOnClickSelector + ', ';
		}
		documentOnClickSelector = documentOnClickSelector + 'select[name="' + selectName + '"]';
	}

	return documentOnClickSelector;
}

// #endregion -- Add an Icon preview on the right.

// #region -- Add icons preview on the right for rating packs.

const ratingIconsDataSetElementId = 'twrpb-general-settings__rating_pack_icons-wrapper';
const dataHolderName = 'data-twrpb-rating-packs';
const selectRatingName = 'rating_pack_icons';

const iconsPreviewWrapperClassName = 'twrpb-general-select__rating_icons_preview';
const iconsPreviewWrapper = '<span class="' + iconsPreviewWrapperClassName + '"></span>';
const ratingIconAndSvgElem = iconAndSvgElem;
let ratingIconsSet = null;

$( updateRatingIcons );
$( document ).on( 'change', 'select[name="' + selectRatingName + '"]', updateRatingIcons );

function setRatingIconsData() {
	const dataHolderElement = $( '#' + ratingIconsDataSetElementId );
	if ( dataHolderElement.length === 0 ) {
		return;
	}

	const toParse = dataHolderElement.attr( dataHolderName );

	if ( ( typeof toParse ) !== 'string' ) {
		return;
	}

	try {
		ratingIconsSet = JSON.parse( toParse );
	} catch ( e ) {
		// Do nothing.
	}
}

function updateRatingIcons() {
	if ( null === ratingIconsSet ) {
		setRatingIconsData();
	}

	const selectElem = $( 'select[name="' + selectRatingName + '"]' );

	if ( selectElem.length === 0 ) {
		return;
	}

	const selectWrapper = selectElem.parent();
	const selectedRatingPack = String( selectElem.val() );

	// Check if all property exists before accessing them.
	if ( ! ( selectedRatingPack in ratingIconsSet ) ||
		! ( 'empty' in ratingIconsSet[ selectedRatingPack ] ) ||
		! ( 'half' in ratingIconsSet[ selectedRatingPack ] ) ||
		! ( 'full' in ratingIconsSet[ selectedRatingPack ] )
	) {
		return;
	}

	const emptyIconId = ratingIconsSet[ selectedRatingPack ].empty;
	const halfFilledIconId = ratingIconsSet[ selectedRatingPack ].half;
	const filledIconId = ratingIconsSet[ selectedRatingPack ].full;

	selectWrapper.find( '.' + iconsPreviewWrapperClassName ).remove();
	selectWrapper.prepend( iconsPreviewWrapper );

	const iconsWrapperPreviewElement = selectWrapper.find( '.' + iconsPreviewWrapperClassName );
	$( ratingIconAndSvgElem ).appendTo( iconsWrapperPreviewElement ).find( 'use' ).attr( 'href', '#' + filledIconId );
	$( ratingIconAndSvgElem ).appendTo( iconsWrapperPreviewElement ).find( 'use' ).attr( 'href', '#' + halfFilledIconId );
	$( ratingIconAndSvgElem ).appendTo( iconsWrapperPreviewElement ).find( 'use' ).attr( 'href', '#' + emptyIconId );
}

// #endregion -- Add icons preview on the right for rating packs.

// #region -- Auto-Choose the compatible disabled icon.

const selectCommentIconSelector = '#twrpb-general-settings__comments_icon-setting';
const selectDisabledCommentIconSelector = '#twrpb-general-settings__comments_disabled_icon-setting';

const dataHolderElementSelector = '#twrpb-general-settings__comments_disabled_icon-wrapper';
const dataHolderAttrName = 'data-twrpb-related-comment-icons';
let compatibleDisabledComments = null;

const autoSelectIconSwitchName = 'comments_disabled_icon_auto_select';

$( enableOrDisableIconSelect );
$( document ).on( 'change', selectCommentIconSelector, onChangeAutoSelectHandler );
$( document ).on( 'change', 'input[name="' + autoSelectIconSwitchName + '"]', enableOrDisableIconSelect );
$( document ).on( 'submit', onSubmitSendDisable );

function setCommentsData() {
	const dataHolderElement = $( dataHolderElementSelector );
	if ( dataHolderElement.length === 0 ) {
		return;
	}

	const toParse = dataHolderElement.attr( dataHolderAttrName );

	if ( ( typeof toParse ) !== 'string' ) {
		return;
	}

	try {
		compatibleDisabledComments = JSON.parse( toParse );
	} catch ( e ) {
		// Do nothing.
	}
}

function onChangeAutoSelectHandler() {
	if ( isEnableAutoSelect() ) {
		doDisabledCommentIconAutoSelect();
	}
}

function doDisabledCommentIconAutoSelect() {
	if ( compatibleDisabledComments === null ) {
		setCommentsData();
	}
	if ( compatibleDisabledComments === null ) {
		return;
	}

	const commentId = String( $( selectCommentIconSelector ).val() );

	if ( ! compatibleDisabledComments[ commentId ] ) {
		return;
	}

	const compatibleDisabledCommentIconId = String( compatibleDisabledComments[ commentId ] );

	$( selectDisabledCommentIconSelector ).val( compatibleDisabledCommentIconId ).trigger( 'change' );
}

let firstTime = true;
function enableOrDisableIconSelect() {
	if ( isEnableAutoSelect() ) {
		disableIconSelector();
		if ( ! firstTime ) {
			doDisabledCommentIconAutoSelect();
		}
		firstTime = false;
	} else {
		enableIconSelector();
	}
}

function isEnableAutoSelect(): boolean {
	const val = String( $( 'input[name="' + autoSelectIconSwitchName + '"]:checked' ).val() );
	if ( parseInt( val ) === 1 || val === 'true' ) {
		return true;
	}
	return false;
}

function disableIconSelector() {
	$( selectDisabledCommentIconSelector ).prop( 'disabled', true );
}

function enableIconSelector() {
	$( selectDisabledCommentIconSelector ).prop( 'disabled', false );
}

function onSubmitSendDisable() {
	enableIconSelector();
}

// #endregion -- Auto-Choose the compatible disabled icon.

// #region -- Show WP Media selector.

const containerSelector = '.twrpb-general-image';
const buttonSelector = '.twrpb-general-image__btn';
const clearBtnSelector = '.twrpb-general-image__clear-btn';
const inputSelector = '.twrpb-general-image__img-src';
const imgSelector = '.twrpb-general-image__img-preview';
const defaultSrcAttrName = 'data-twrpb-default-image';
const mediaArgsAttrName = 'data-twrpb-media-args';

$( addButtonEvent );

function addButtonEvent() {
	$( buttonSelector ).on( 'click', function( e ) {
		e.preventDefault();
		let mediaWindow: any;
		const button = $( this );
		const input = button.closest( containerSelector ).find( inputSelector );
		const img = button.closest( containerSelector ).find( imgSelector );
		let mediaArgs = input.attr( mediaArgsAttrName );
		mediaArgs = JSON.parse( mediaArgs );

		if ( mediaWindow === undefined ) {
			mediaWindow = wp.media( mediaArgs );

			mediaWindow.on( 'select', () => {
				const attachment = mediaWindow.state().get( 'selection' ).first().toJSON();
				input.val( attachment.id );
				img.attr( 'src', attachment.url );
			} );
		}

		mediaWindow.open( button );

		return false;
	} );

	$( clearBtnSelector ).on( 'click', function( e ) {
		e.preventDefault();
		const button = $( this );
		const input = button.closest( containerSelector ).find( inputSelector );
		const img = button.closest( containerSelector ).find( imgSelector );
		const defaultSrc = input.attr( defaultSrcAttrName );
		img.attr( 'src', defaultSrc );
		input.val( '' );
	} );
}

// #endregion -- Show WP Media selector.

// #region -- Cache rebuild handler.

$( document ).on( 'click', '#twrpb-refresh-cache-button', handleRefreshCache );

function handleRefreshCache( e ) {
	e.preventDefault();
	const button = $( this );
	const runningPrefix = ' (' + button.attr( 'data-twrpb-refresh-cache-waiting' ) + ')';
	const failedPrefix = ' (' + button.attr( 'data-twrpb-refresh-cache-failed' ) + ')';
	const successPrefix = ' (' + button.attr( 'data-twrpb-refresh-cache-success' ) + ')';

	const nonce = button.attr( 'data-twrpb-refresh-cache-nonce' );
	const text = button.text();
	const cleanText = text.replace( runningPrefix, '' ).replace( failedPrefix, '' ).replace( successPrefix, '' );

	button.html( cleanText + runningPrefix );

	$.ajax( {
		type: 'post',
		dataType: 'json',
		cache: false,
		data: {
			action: 'twrp_refresh_widget_cache',
			nonce,
		},
		url: ajaxurl,
		complete( response ) {
			if ( response.status === 200 ) {
				button.html( cleanText + successPrefix );
			} else {
				button.html( cleanText + failedPrefix );
			}
		},
	} );
}

// #endregion -- Cache rebuild handler.

// #region -- Show/Hide Translations.

const btnHideTranslationsSelector = '#twrpb-general-settings__translations-show-btn';
const translationContainer = '#twrpb-general-settings__translations-hidden';

$( document ).on( 'click', btnHideTranslationsSelector, translationBtnHandler );

function translationBtnHandler( e ) {
	e.preventDefault();
	$( translationContainer ).slideToggle();
}

// #region -- Show/Hide Translations.

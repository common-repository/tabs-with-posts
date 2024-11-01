import $ from 'jquery';
import { hideUp, showUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

const selectCommentsComparator = $( '#twrpb-comments-settings__js-comparator' );

const numCommentsInput = $( '#twrpb-comments-settings__js-num_comments' );

$( hideOrShowCommentsNumberInput );
$( document ).on( 'change', '#twrpb-comments-settings__js-comparator', hideOrShowCommentsNumberInput );

function hideOrShowCommentsNumberInput() {
	const comparator = selectCommentsComparator.val();

	if ( 'NA' === comparator ) {
		hideUp( numCommentsInput );
	} else {
		showUp( numCommentsInput );
	}
}

import $ from 'jquery';
import { hideUp, showUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

const searchInput = $( '#twrpb-search-setting__js-search-input' );
const warningWrapper = $( '#twrpb-setting-note__search_query_too_short_warning' );

$( hiderOrShowSearchWarning );
$( document ).on( 'change', '#twrpb-search-setting__js-search-input', hiderOrShowSearchWarning );

function hiderOrShowSearchWarning(): void {
	const searchString = String( searchInput.val() );
	const searchStringLength = searchString.length;

	if ( ( searchStringLength !== 0 ) && ( searchStringLength < 4 ) ) {
		showUp( warningWrapper );
	} else {
		hideUp( warningWrapper );
	}
}

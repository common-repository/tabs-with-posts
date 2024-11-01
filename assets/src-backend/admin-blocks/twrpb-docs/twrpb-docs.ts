import $ from 'jquery';
import { toggleDisplay } from '../twrpb-hidden/twrpb-hidden';

const showButtonSelector = '.twrpb-docs__icon-spoiler-btn';

$( document ).on( 'click', showButtonSelector, hideOrShowIconsSpoiler );

function hideOrShowIconsSpoiler() {
	const spoilerWrapper = $( this ).parent( '.twrpb-docs__icon-spoiler-category' ).find( '.twrpb-docs__spoiler' );
	toggleDisplay( spoilerWrapper );
}

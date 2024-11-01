import $ from 'jquery';
import 'jqueryui';

$( enableCollapsibleSettings );

function enableCollapsibleSettings() {
	$( '.twrpb-collapsible' ).each( function() {
		const element = $( this );
		const activeTabIndex = ( element.attr( 'data-twrpb-is-collapsed' ) === '1' ) ? 0 : false;

		element.accordion( {
			active: activeTabIndex,
			heightStyle: 'content',
			collapsible: true,
			icons: false,
		} );
	} );
}

import $ from 'jquery';
import Pickr from '@simonwep/pickr';

declare const TwrpPickrTranslations: any;

$( createPicker );
$( document ).on( 'widget-updated widget-added twrpb-artblock-added twrpb-query-added', createPicker );

function createPicker() {
	const colorPickers = $( '.twrpb-color-picker' );

	let translations = [];
	if ( typeof TwrpPickrTranslations === 'object' ) {
		translations = TwrpPickrTranslations;
	}

	let pickrContainer = 'body';
	if ( $( '#widgets-right' ).length ) {
		pickrContainer = '#widgets-right';
	}

	colorPickers.each( function() {
		const element: any = this;
		const input = $( element ).parent().find( 'input' );
		let inputVal = String( input.val() );

		if ( ! inputVal ) {
			inputVal = null;
		}

		const pickr = Pickr.create( {
			el: element,
			theme: 'monolith',
			container: pickrContainer,
			default: inputVal,
			appClass: 'twrpb-pickr',
			// @ts-ignore
			i18n: translations,

			swatches: [
				'rgba(244, 67, 54, 1)',
				'rgba(233, 30, 99, 1)',
				'rgba(156, 39, 176, 1)',
				'rgba(103, 58, 183, 1)',
				'rgba(63, 81, 181, 1)',
				'rgba(33, 150, 243, 1)',
				'rgba(3, 169, 244, 1)',
				'rgba(0, 188, 212, 1)',
				'rgba(0, 150, 136, 1)',
				'rgba(76, 175, 80, 1)',
				'rgba(139, 195, 74, 1)',
				'rgba(205, 220, 57, 1)',
				'rgba(255, 235, 59, 1)',
				'rgba(255, 193, 7, 1)',
			],
			defaultRepresentation: 'RGBA',

			components: {
				// Main components
				preview: true,
				opacity: true,
				hue: true,

				// Input / output Options
				interaction: {
					input: true,
					cancel: false,
					clear: true,
					save: true,
				},
			},
		} ).on( 'save', function( color: any ) {
			if ( color ) {
				input.val( color.toRGBA().toString( 0 ) );
			} else {
				input.val( '' );
			}

			input.change();
			pickr.hide();
		} );

		input.data( 'pickr', pickr );

		pickr.setColorRepresentation( 'RGBA' );
	} );
}

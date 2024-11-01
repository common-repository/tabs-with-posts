import $ from 'jquery';
import 'jqueryui';

// #region -- Initialize the jQuery Ui Tooltip.

const tooltipBtnSelector = '.twrpb-widget-form__artblock-tooltip-btn';
const tooltipContentSelector = '.twrpb-widget-form__artblock-tooltip-content';

$( document ).on( 'click', tooltipBtnSelector, handleWidgetTooltip );

function handleWidgetTooltip( e ): void {
	e.preventDefault();
	const btn = $( this );
	const content = btn.parent().find( tooltipContentSelector );

	content.slideToggle();
}

// #endregion -- Initialize the jQuery Ui Tooltip.

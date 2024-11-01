import $ from 'jquery';

// #region -- Ask an alert question before deleting a query.

const deleteButtonSelector = '.twrpb-queries-table__delete-link';

const deleteConfirmationMessageHolder = '#twrpb-query-settings__before-deleting-confirmation';
const deleteConfirmationMessageData = 'data-twrpb-query-delete-confirm';

$( document ).on( 'click', deleteButtonSelector, handleDeleteButtonClicked );

function handleDeleteButtonClicked( event ) {
	const alertMessage = $( deleteConfirmationMessageHolder ).attr( deleteConfirmationMessageData );
	// eslint-disable-next-line no-alert
	const confirmation = confirm( alertMessage );

	if ( ! confirmation ) {
		event.preventDefault();
	}
}

// #endregion -- Ask an alert question before deleting a query.

// #region -- Make Query Arguments Debugger Accordion.

const displayQueryArgsSelector = '#twrpb-query-settings__query_generated_array_container';

$( makeQueryArgsCollapsible );

function makeQueryArgsCollapsible() {
	$( displayQueryArgsSelector ).accordion( {
		active: false,
		heightStyle: 'content',
		collapsible: true,
		icons: false,
	} );
}

// #region -- Make Query Arguments Debugger Accordion.

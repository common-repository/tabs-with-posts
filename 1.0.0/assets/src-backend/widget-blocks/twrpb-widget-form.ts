import $ from 'jquery';
import 'jqueryui';

declare const ajaxurl: string;

/**
 * Todo: Do not save widget if the query tabs are not displayed.
 * Todo: Block the select option while the article block is loading and waited
 * to be appended and display a nice animation.
 */

// #region -- Declaring all constants.

const dataWidgetId = 'data-twrpb-widget-id';
const dataQueryId = 'data-twrpb-query-id';
const dataArtblockId = 'data-twrpb-selected-artblock';

const queriesListSelector = '.twrpb-widget-form__selected-queries-list';
const queriesItemSelector = '.twrpb-widget-form__selected-query';

const queryAddSelector = '.twrpb-widget-form__select-query-to-add-btn';
const querySpinnerSelector = '.twrpb-widget-form__query-loading';
const queryFailedToAddSelector = '.twrpb-widget-form__failed-to-load-query-tab';
const queryRemoveSelector = '.twrpb-widget-form__remove-selected-query';

const queriesInputSelector = '.twrpb-widget-form__selected-queries';

const selectArticleBlockSelector = '.twrpb-widget-form__article-block-selector';

const articleBlockSettingsContainerSelector = '.twrpb-widget-form__article-block-settings-container';

// #endregion -- Declaring all constants.

// #region -- Add Queries.

$( document ).on( 'click', queryAddSelector, handleAddQueryButton );

let widgetIsAjaxLoadingQuery = false;

/**
 * Handles the click when a user wants to add a query(tab) to be displayed and
 * modify its settings.
 */
function handleAddQueryButton(): void {
	const widgetWrapper = $( this ).closest( '.twrpb-widget-form' );
	const widgetId = widgetWrapper.attr( dataWidgetId );
	const queryId = String( widgetWrapper.find( '.twrpb-widget-form__select-query-to-add' ).val() );

	addQueryToListIfDoNotExist( widgetId, queryId );
}

/**
 * Appends a query tab to the widget list of queries. This function also updates
 * the queries input after a display query have been added.
 *
 */
async function addQueryToListIfDoNotExist( widgetId: string, queryId: string|number ) {
	// If query already exist in the list, then return. This check is here for
	// only to prevent a useless request to the server.
	if ( queryExistInList( widgetId, queryId ) ) {
		return;
	}

	if ( widgetIsAjaxLoadingQuery ) {
		return;
	}

	const widgetElement = getWidgetWrapperById( widgetId );
	const failedMessageElement = widgetElement.find( queryFailedToAddSelector );
	failedMessageElement.hide();

	addQueryLoadingAnimation( widgetId );
	widgetIsAjaxLoadingQuery = true;

	const ajaxNonce = String( $( '#twrpb-plugin-widget-ajax-nonce' ).attr( 'data-twrpb-plugin-widget-ajax-nonce' ) );

	$.ajax( {
		url: ajaxurl,
		method: 'POST',
		data: {
			action: 'twrpb_widget_create_query_setting',
			widget_id: widgetId,
			query_id: queryId,
			nonce: ajaxNonce,
		},
	} )
		.done(
			function( data ) {
				// We need another check here because this function can be
				// called and executed multiple times, because it can be called
				// from multiple events.
				if ( data.length >= 25 && ! queryExistInList( widgetId, queryId ) ) {
					const widget = getWidgetWrapperById( widgetId );
					const queriesList = widget.find( '.twrpb-widget-form__selected-queries-list' );
					queriesList.append( data );
					updateQueriesInput( widgetId );
					makeAllQueriesCollapsible();
					queriesList.trigger( 'twrpb-query-added' );
				} else if ( data.length < 25 ) {
					const widget = getWidgetWrapperById( widgetId );
					const failedMessage = widget.find( queryFailedToAddSelector );
					failedMessage.show();
				}
			}
		)
		.fail(
			// eslint-disable-next-line no-unused-vars
			function( data ) {
				// eslint-disable-next-line no-console
				console.error( 'Something went wrong with the ajax callback in addQueryToListIfDoNotExist() function,' );
				const widget = getWidgetWrapperById( widgetId );
				const failedMessage = widget.find( queryFailedToAddSelector );
				failedMessage.show();
			}
		).always( function() {
			removeQueryLoadingAnimation( widgetId );
			widgetIsAjaxLoadingQuery = false;
		} );
}

function addQueryLoadingAnimation( widgetId: string ) {
	const widget = getWidgetWrapperById( widgetId );
	const addQueryButton = widget.find( queryAddSelector );
	const spinner = widget.find( querySpinnerSelector );

	addQueryButton.addClass( 'twrpb-button--disabled' );
	spinner.show();
}

function removeQueryLoadingAnimation( widgetId: string ) {
	const widget = getWidgetWrapperById( widgetId );
	const addQueryButton = widget.find( queryAddSelector );
	const spinner = widget.find( querySpinnerSelector );

	addQueryButton.removeClass( 'twrpb-button--disabled' );
	spinner.hide();
}

// #endregion -- Add Queries.

// #region -- Remove Queries.

$( document ).on( 'click', queryRemoveSelector, handleRemoveQuery );

/**
 * Removes the query from Display List and Input List.
 */
function handleRemoveQuery() {
	const widgetId = getClosestWidgetId( $( this ) );
	const queryId = getClosestQueryId( $( this ) );

	removeQueryFromList( widgetId, queryId );
	updateQueriesInput( widgetId );
}

/**
 * Remove a Query From the Display List.
 */
function removeQueryFromList( widgetId: string, queryId: string|number ) {
	const queryItem = getQueryItemById( widgetId, queryId );
	queryItem.remove();
}

// #endregion -- Remove Queries.

// #region -- Update Queries

$( updateAllWidgetsListOnLoad );
$( document ).on( 'widget-updated widget-added', updateAllWidgetsListOnLoad );

function updateAllWidgetsListOnLoad(): void {
	const widgets = getAllWidgets();
	widgets.each( function() {
		const widget = $( this );
		const widgetId = widget.attr( dataWidgetId );
		const queries = getQueriesInputValues( widget );

		for ( let i = 0; i < queries.length; i++ ) {
			addQueryToListIfDoNotExist( widgetId, queries[ i ] );
		}

		updateQueriesInput( widgetId );
	} );
}

// #endregion -- Update Queries

// #region -- Make Queries Sortable.

$( initializeAllQueriesSorting );
$( document ).on( 'widget-updated widget-added', initializeAllQueriesSorting );

function initializeAllQueriesSorting(): void {
	const widgets = getAllWidgets();
	widgets.each( function() {
		const queriesList = $( this ).find( queriesListSelector );
		queriesList.sortable( {
			update: handleSortingChange,
		} );

		function handleSortingChange(): void {
			const widgetId = getClosestWidgetId( this );
			updateQueriesInput( widgetId );
		}
	} );
}

// #endregion -- Make Queries Sortable.

// #region -- Add/Remove/Update Queries Functions.

/**
 * Check if a query settings are displayed in the widget.
 */
function queryExistInList( widgetId: string, queryId: string|number ): boolean {
	const queryItem = getQueryItemById( widgetId, queryId );
	if ( queryItem.length ) {
		return true;
	}

	return false;
}

/**
 * Updates the queries input, from the queries display list.
 *
 * This is the primary function that is used to update the queries input, based
 * on what is displayed.
 */
function updateQueriesInput( widgetId: string ): void {
	const widget = getWidgetWrapperById( widgetId );
	const queriesItems = widget.find( queriesListSelector ).find( queriesItemSelector );

	const queries = [];
	queriesItems.each( function() {
		const queryId = $( this ).attr( dataQueryId );
		if ( queryId ) {
			queries.push( queryId );
		}
	} );

	const input = widget.find( queriesInputSelector );
	const value = queries.join( ';' );
	input.val( value );
	input.trigger( 'change' );
}

/**
 * Get an array of all query ids used in a widget.
 */
function getQueriesInputValues( widget: JQuery<HTMLElement> ): Array<string> {
	const queryInput = widget.find( queriesInputSelector );
	let queries = String( queryInput.val() ).split( ';' );
	queries = queries.filter( isNonEmptyString );

	return queries;

	function isNonEmptyString( elem: string ): boolean {
		return Boolean( elem );
	}
}

// #endregion -- Add/Remove/Update Queries Functions.

// #region -- Make Queries Tab Collapsible.

$( makeAllQueriesCollapsible );
$( document ).on( 'widget-updated widget-added', makeAllQueriesCollapsible );

function makeAllQueriesCollapsible() {
	const widgets = getAllWidgets();
	widgets.each( function() {
		const queryItems = $( this ).find( `[${ dataQueryId }]` );

		queryItems.each( function() {
			const query = $( this );
			const accordionInstance = query.accordion( 'instance' );

			if ( accordionInstance ) {
				query.accordion( 'refresh' );
			} else {
				query.accordion( {
					collapsible: true,
					active: false,
					heightStyle: 'content',
				} );
			}
		} );
	} );
}

// #endregion -- Make Queries Tab Collapsible.

// #region -- Helper Functions.

function getClosestWidgetId( element: any ): string {
	return $( element ).closest( `[${ dataWidgetId }]` ).attr( dataWidgetId );
}

function getClosestQueryId( element: any ): string {
	return $( element ).closest( `[${ dataQueryId }]` ).attr( dataQueryId );
}

function getWidgetWrapperById( widgetId: string ): JQuery<HTMLElement> {
	return $( document ).find( `[${ dataWidgetId }="${ widgetId }"]` );
}

function getQueryItemById( widgetId: string, queryId: string|number ): JQuery<HTMLElement> {
	const widget = getWidgetWrapperById( widgetId );
	return $( widget ).find( `[${ dataQueryId }="${ queryId }"]` );
}

/**
 * Get all TWRP widgets.
 */
function getAllWidgets(): JQuery {
	return $( document ).find( `[${ dataWidgetId }]` ).not( '[id*="__i__"]' );
}

// #endregion -- Helper Functions.

// #region -- Manages The Changes Of Article Block Settings.

// #region -- Insert The Article Block Settings.

$( document ).on( 'change', selectArticleBlockSelector, handleArticleBlockChanged );

async function handleArticleBlockChanged() {
	const widgetId = getClosestWidgetId( $( this ) );
	const queryId = getClosestQueryId( $( this ) );
	const artblockId = String( $( this ).val() );

	// add previous container to cache:
	addArticleBlockToCache( widgetId, queryId );

	let artblockSettingsHTML;
	if ( hasArticleBlockInCache( widgetId, queryId, artblockId ) ) {
		artblockSettingsHTML = getArticleBlockFromCache( widgetId, queryId, artblockId );
	} else {
		displayLoadingArtblock( widgetId, queryId );
		artblockSettingsHTML = await getArticleBlockSettings( widgetId, queryId, artblockId );
		removeLoadingArtblock( widgetId, queryId );
	}

	insertArticleBlock( widgetId, queryId, artblockSettingsHTML );
}

function getArticleBlockSettings( widgetId: string, queryId: string|number, artblockId: string|number ) {
	const ajaxNonce = String( $( '#twrpb-plugin-widget-ajax-nonce' ).attr( 'data-twrpb-plugin-widget-ajax-nonce' ) );

	return $.ajax( {
		url: ajaxurl,
		method: 'POST',
		data: {
			action: 'twrpb_widget_create_artblock_settings',
			artblock_id: artblockId,
			widget_id: widgetId,
			query_id: queryId,
			nonce: ajaxNonce,
		},
	} );
}

function insertArticleBlock( widgetId: string, queryId: string|number, html: string|JQuery ) {
	if ( ( ( typeof html ) === 'string' ) && html.length < 25 ) {
		return;
	}

	const queryItem = getQueryItemById( widgetId, queryId );
	const artblockContainer = queryItem.find( articleBlockSettingsContainerSelector );
	artblockContainer.empty();
	artblockContainer.find( '[' + dataArtblockId + ']' ).detach();
	artblockContainer.append( html );
	artblockContainer.trigger( 'twrpb-artblock-added' );
}

function displayLoadingArtblock( widgetId, queryId ) {
	const queryContainer = getQueryItemById( widgetId, queryId );

	const settingsContainer = queryContainer.find( articleBlockSettingsContainerSelector );
	settingsContainer.addClass( 'twrpb-widget-form__article-block-loading' );

	const loadingSpinner = queryContainer.find( '.twrpb-widget-form__article-block-loading-spinner' );
	loadingSpinner.show();
}

function removeLoadingArtblock( widgetId, queryId ) {
	const queryContainer = getQueryItemById( widgetId, queryId );

	const settingsContainer = queryContainer.find( articleBlockSettingsContainerSelector );
	settingsContainer.removeClass( 'twrpb-widget-form__article-block-loading' );

	const loadingSpinner = queryContainer.find( '.twrpb-widget-form__article-block-loading-spinner' );
	loadingSpinner.hide();
}

// #endregion -- Insert The Article Block Settings.

// #region -- Cache Article Block Settings.

/**
 * An array that contains all the article blocks retrieved and detached.
 * When a user choose to reselect a previously selected and modified article
 * block, the settings will not be fetched again from the server and the
 * modifications will remain.
 */
const articleBlocksCache = Array();

/**
 * Add the article block to the cache array.
 */
function addArticleBlockToCache( widgetId: string, queryId: string|number ): void {
	if ( ( ! widgetId ) || ( ! queryId ) ) {
		return;
	}

	if ( ! articleBlocksCache[ widgetId ] ) {
		articleBlocksCache[ widgetId ] = [];
	}
	if ( ! articleBlocksCache[ widgetId ][ queryId ] ) {
		articleBlocksCache[ widgetId ][ queryId ] = [];
	}

	const query = getQueryItemById( widgetId, queryId );
	const artblockWrapper = query.find( '[' + dataArtblockId + ']' );
	const artblockId = artblockWrapper.attr( dataArtblockId );

	if ( ! artblockId ) {
		return;
	}

	articleBlocksCache[ widgetId ][ queryId ][ artblockId ] = artblockWrapper;
}

/**
 * Get an article block from cache. Or false if the article block is not in cache.
 */
function getArticleBlockFromCache( widgetId: string, queryId: string|number, $artblockId: string|number ): JQuery<HTMLElement>|false {
	if ( hasArticleBlockInCache( widgetId, queryId, $artblockId ) ) {
		return articleBlocksCache[ widgetId ][ queryId ][ $artblockId ];
	}
	return false;
}

/**
 * Whether or not an article block was already fetched and put in cache.
 */
function hasArticleBlockInCache( widgetId: string, queryId: string|number, $artblockId: string|number ): boolean {
	if ( ! articleBlocksCache[ widgetId ] || ! articleBlocksCache[ widgetId ][ queryId ] || ! articleBlocksCache[ widgetId ][ queryId ][ $artblockId ] ) {
		return false;
	}

	return true;
}

// #endregion -- Cache Article Block Settings.

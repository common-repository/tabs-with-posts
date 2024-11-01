import '../external/tabby/tabby_polyfill';
import Tabby from '../external/tabby/tabby';

const tabs: any = [];
declare const TWRPLocalizeObject: any;

if ( document.readyState === 'loading' ) {
	window.addEventListener( 'DOMContentLoaded', initializeAllTabs );
} else {
	initializeAllTabs();
}
document.addEventListener( 'twrp-widgets-loaded-via-ajax', initializeAllTabs );

function initializeAllTabs() {
	const allTabs = document.querySelectorAll( '[data-twrp-tabs-btns]' );

	if ( allTabs.length === 0 ) {
		return;
	}

	for ( let i = 0; i < allTabs.length; i++ ) {
		const element = allTabs[ i ];
		const tabsWrapper = element.closest( '.twrp-main' );
		if ( tabsWrapper === null ) {
			continue;
		}

		const tabId = tabsWrapper.getAttribute( 'id' );
		const tabSelectorId = '#' + tabId + ' [data-twrp-tabs-btns]';

		// @ts-ignore
		if ( ( typeof element.twrpTabbyObject === 'object' ) && ( element.twrpTabbyObject !== null ) ) {
			continue;
		}

		// Set timeout to make this execute later, to make the page feel faster.
		setTimeout( function() {
			// @ts-ignore -- No Tabby type declared yet.
			const tabby = new Tabby( tabSelectorId, {
				idPrefix: 'twrp-tabby__',
				default: '[data-twrp-default-tab]',
			} );
			const tabElement = document.querySelector( tabSelectorId );

			// @ts-ignore
			tabElement.twrpTabbyObject = tabby;
			tabs.push( tabby );
		}, 0 );
	}
}

// #region -- Make button not take focus on click.

// todo maybe remove:
// document.addEventListener( 'click', removeTabButtonFocus );

// function removeTabButtonFocus( event: Event ) {
// 	const tab = event.target;

// 	// @ts-ignore
// 	if ( tab.getAttribute( 'role' ) === 'tab' && ( tab.className.search( 'twrp-' ) !== -1 && tab.getAttribute( 'href' ) ).search( '#twrp-' ) === 0 ) {
// 		// @ts-ignore
// 		tab.blur();
// 	}
// }

//#endregion -- Make button not take focus on click.

// #region -- Add active item to button wrapper.

const activeButtonItemClass = 'twrp-tab-btn-item-active';

if ( document.readyState === 'loading' ) {
	window.addEventListener( 'DOMContentLoaded', addButtonWrapperInitial );
} else {
	addButtonWrapperInitial();
}
function addButtonWrapperInitial() {
	const buttons = document.querySelectorAll( '[data-twrp-default-tab]' );

	for ( let i = 0; i < buttons.length; i++ ) {
		const btnWrapper = buttons[ i ].parentElement;
		// @ts-ignore
		btnWrapper.classList.add( activeButtonItemClass );
	}
}

document.addEventListener( 'tabby', addButtonWrapperActiveClass );
function addButtonWrapperActiveClass( event: Event ) {
	// @ts-ignore
	const buttonWrapper: Element = event.target.parentElement;
	// @ts-ignore
	const prevButtonWrapper: Element = event.detail.previousTab.parentElement;

	if ( buttonWrapper.nodeName === 'LI' && buttonWrapper.className.search( 'twrp-' ) !== -1 ) {
		buttonWrapper.classList.add( activeButtonItemClass );
	}

	prevButtonWrapper.classList.remove( activeButtonItemClass );
}

// #endregion -- Add active item to button wrapper.

// #region -- Manage widget ajax.

const ajaxAction = 'twrp_load_widget';
const dataWidgetIdName = 'data-twrp-ajax-widget-id';
const dataPostIdName = 'data-twrp-ajax-post-id';
const dataFailedText = 'data-twrp-failed-text';

interface ajaxWidgetItem {
	widget_id: number; // eslint-disable-line camelcase
	post_id: number; // eslint-disable-line camelcase
	content: string;
  }

if ( document.readyState === 'loading' ) {
	window.addEventListener( 'DOMContentLoaded', loadWidgetsViaAjax );
} else {
	loadWidgetsViaAjax();
}

function loadWidgetsViaAjax() {
	const allWidgetsToLoad = document.querySelectorAll( '[' + dataWidgetIdName + ']' );
	const allWidgetsToLoadData = [];

	for ( let i = 0; i < allWidgetsToLoad.length; i++ ) {
		setTimeout( showWidgetNotLoadedAtTimeout.bind( null, allWidgetsToLoad[ i ] ), 60000 );

		const widgetItem = new Object;
		// @ts-ignore
		widgetItem.widget_id = allWidgetsToLoad[ i ].getAttribute( dataWidgetIdName );
		// @ts-ignore
		widgetItem.post_id = allWidgetsToLoad[ i ].getAttribute( dataPostIdName );

		// @ts-ignore
		if ( ! ( parseInt( widgetItem.widget_id ) > 0 ) || ! ( parseInt( widgetItem.post_id ) > 0 ) ) {
			continue;
		}

		allWidgetsToLoadData.push( widgetItem );
	}

	if ( allWidgetsToLoadData.length ) {
		createAjaxWidgetLoadCall( allWidgetsToLoadData );
	}
}

function createAjaxWidgetLoadCall( items: Array<Object> ) {
	const ajaxUrl = TWRPLocalizeObject.ajaxUrl;
	const ajax = new XMLHttpRequest();

	ajax.open( 'GET', ajaxUrl + '?action=' + ajaxAction + '&items=' + JSON.stringify( items ), true );
	ajax.onreadystatechange = function() {
		handleAjaxFinished( this, items );
	};
	ajax.send();
}

function handleAjaxFinished( ajaxObject: XMLHttpRequest, items: Array<Object> ) {
	if ( ajaxObject.readyState === 4 ) {
		if ( ajaxObject.status === 200 ) {
			const jsonText = ajaxObject.responseText;
			let parsedItems;

			try {
				parsedItems = JSON.parse( jsonText );
			} catch ( e ) {
				showWidgetsCannotBeLoaded( items );
			}

			if ( ! Array.isArray( parsedItems ) ) {
				showWidgetsCannotBeLoaded( items );
			}

			for ( let i = 0; i < parsedItems.length; i++ ) {
				updateWidgetViaAjax( parsedItems[ i ] );
			}
		} else {
			showWidgetsCannotBeLoaded( items );
		}

		// todo: make this IE 11 compatible.
		const event = new Event( 'twrp-widgets-loaded-via-ajax', {
			bubbles: true,
		} );
		document.dispatchEvent( event );
	}
}

function updateWidgetViaAjax( itemData: ajaxWidgetItem ) {
	if ( ! ajaxItemIsValid( itemData ) ) {
		return;
	}

	const widgetId = itemData.widget_id;
	const postId = itemData.post_id;

	const widgets = document.querySelectorAll( '[' + dataWidgetIdName + '="' + widgetId + '"][' + dataPostIdName + '="' + postId + '"]' );

	for ( let i = 0; i < widgets.length; i++ ) {
		const widgetParent = widgets[ i ].parentElement;
		if ( ! isElement( widgetParent ) ) {
			continue;
		}
		widgetParent.insertAdjacentHTML( 'beforeend', itemData.content );
		widgetParent.removeChild( widgets[ i ] );
	}
}

/**
 * Widgets is an object with the keys widget_id and post_id, or an array with
 * those objects.
 */
function showWidgetsCannotBeLoaded( widgets: unknown ) {
	if ( Array.isArray( widgets ) ) {
		for ( let i = 0; i < widgets.length; i++ ) {
			showWidgetsCannotBeLoaded( widgets[ i ] );
		}
		return;
	}

	if ( ( typeof widgets === 'object' ) && ( widgets !== null ) && ( 'widget_id' in widgets ) && ( 'post_id' in widgets ) ) {
		// @ts-ignore
		const widgetElements = document.querySelectorAll( '[' + dataWidgetIdName + '="' + widgets.widget_id + '"][' + dataPostIdName + '="' + widgets.post_id + '"]' );
		for ( let i = 0; i < widgetElements.length; i++ ) {
			showWidgetCannotBeLoaded( widgetElements[ i ] );
		}
	}
}

function showWidgetCannotBeLoaded( widget: Element ) {
	// If is not an HTML element, then we try to find the widgets.
	if ( ! isElement( widget ) ) {
		return;
	}

	const failedText = widget.getAttribute( dataFailedText );
	const widgetParent = widget.parentElement;
	if ( ! isElement( widgetParent ) ) {
		return;
	}

	const failedHtml = '<div class="twrp-widget-load-via-ajax-failed">' + failedText + '</div>';

	widgetParent.insertAdjacentHTML( 'beforeend', failedHtml );
	widgetParent.removeChild( widget );
}

function showWidgetNotLoadedAtTimeout( widget: Element ) {
	// If is detached from the document, then return.
	if ( ! isElement( widget.parentElement ) ) {
		return;
	}

	showWidgetCannotBeLoaded( widget );
}

function ajaxItemIsValid( item: unknown ): item is ajaxWidgetItem {
	if ( typeof item !== 'object' || item === null ) {
		return false;
	}

	if ( ! ( 'widget_id' in item ) || ! ( 'post_id' in item ) || ! ( 'content' in item ) ) {
		return false;
	}

	return true;
}

// #endregion -- Manage widget ajax.

// #region -- Helpers.

// eslint-disable-next-line no-unused-vars
function isElement( element: unknown ): element is Element {
	return element instanceof Element || element instanceof HTMLDocument;
}

// eslint-disable-next-line no-unused-vars
function isNodeList( nodes: any ): nodes is NodeList {
	const stringRepresentation = Object.prototype.toString.call( nodes );

	return typeof nodes === 'object' &&
        /^\[object (HTMLCollection|NodeList|Object)\]$/.test( stringRepresentation ) &&
        nodes.hasOwnProperty( 'length' ) &&
        ( nodes.length === 0 || ( typeof nodes[ 0 ] === 'object' && nodes[ 0 ].nodeType > 0 ) );
}

// #endregion -- Helpers.

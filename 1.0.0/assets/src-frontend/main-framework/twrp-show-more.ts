declare const TWRPLocalizeObject: any;

// #region -- Show more pages button

const showMoreWrapperClassName = 'twrp-show-more';
const showMoreButtonClassName = 'twrp-show-more__btn';

document.addEventListener( 'click', handleMorePostsBtnClick );

function handleMorePostsBtnClick( e: Event ) {
	const target = e.target;
	if ( ! ( target instanceof Element ) ) {
		return;
	}

	if ( target.classList.contains( showMoreWrapperClassName ) || target.classList.contains( showMoreButtonClassName ) ) {
		const toRemove = target.closest( '.' + showMoreWrapperClassName );
		if ( toRemove !== null ) {
			// @ts-ignore -- Parent Node is not null.
			toRemove.parentNode.removeChild( toRemove );
		}
	}
}

// #endregion -- Show more pages button

// #region -- Add a class to every tab wrapper that have multiple columns.

const tabContentClassName = 'twrp-main__tab-content';
const columnModeClassName = 'twrp-js-tab-have-columns';

// Run this function when page is loading.
if ( document.readyState === 'loading' ) {
	window.addEventListener( 'DOMContentLoaded', addOrRemoveColumnsClass );
} else {
	addOrRemoveColumnsClass();
}
window.addEventListener( 'resize', addOrRemoveColumnsClass );
document.addEventListener( 'twrp-widgets-loaded-via-ajax', addOrRemoveColumnsClass );

function addOrRemoveColumnsClass() {
	const tabsWrappers = document.getElementsByClassName( tabContentClassName );

	for ( let i = 0; i < tabsWrappers.length; i++ ) {
		const columns = countNumberOfColumns( tabsWrappers[ i ] );
		if ( columns !== false && columns > 1 ) {
			tabsWrappers[ i ].classList.add( columnModeClassName );
		} else {
			tabsWrappers[ i ].classList.remove( columnModeClassName );
		}
	}
}

// #endregion -- Add a class to every tab wrapper that have multiple columns.

// #region -- Equalize number of posts to columns.

const dataPostsPerPage = 'data-twrp-posts-per-page';
const dataCurrentPostsPerPage = 'data-twrp-current-posts-per-page';
const dataPagesDisplayed = 'data-twrp-pages-displayed';

// Run this function when page is loading.
if ( document.readyState === 'loading' ) {
	window.addEventListener( 'DOMContentLoaded', tryToEqualizeAllTabs );
} else {
	tryToEqualizeAllTabs();
}
window.addEventListener( 'resize', tryToEqualizeAllTabs );
document.addEventListener( 'tabby', tryToEqualizeAllTabs );
document.addEventListener( 'twrp-widgets-loaded-via-ajax', tryToEqualizeAllTabs );

function tryToEqualizeAllTabs(): void {
	if ( ( typeof TWRPLocalizeObject !== 'undefined' ) && ( TWRPLocalizeObject.disableGridPostsFill ) && ( TWRPLocalizeObject.disableGridPostsFill === 'true' ) ) {
		return;
	}

	const tabsWrappers = document.getElementsByClassName( tabContentClassName );
	for ( let i = 0; i < tabsWrappers.length; i++ ) {
		equalizePagesToNumberOfColumns( tabsWrappers[ i ] );
	}
}

function equalizePagesToNumberOfColumns( element: unknown ): void {
	if ( ! ( element instanceof HTMLElement ) && ! ( element instanceof Element ) ) {
		return;
	}

	// This value represents the widget posts per page, set in widget settings.
	const postsPerPage = Number( element.getAttribute( dataPostsPerPage ) );

	// Get the current posts per page displayed(if modified previously).
	let currentPostsPerPage = Number( element.getAttribute( dataCurrentPostsPerPage ) );
	if ( ! currentPostsPerPage ) {
		currentPostsPerPage = postsPerPage;
		element.setAttribute( dataCurrentPostsPerPage, String( currentPostsPerPage ) );
	}

	const columns = countNumberOfColumns( element );

	// If we don't have columns, no re-pagination needed.
	if ( columns === false ) {
		return;
	}

	// If we don't have columns, and posts per page are the same, no re-pagination is needed.
	if ( ( columns === 0 || columns === 1 ) && currentPostsPerPage === postsPerPage ) {
		return;
	}

	// This value represents what the new posts per page should be(can be same as previous).
	let newPostsPerPage = postsPerPage;
	if ( columns > 1 ) {
		if ( postsPerPage > columns ) {
			if ( postsPerPage % columns === 0 ) {
				newPostsPerPage = postsPerPage;
			} else {
				newPostsPerPage = columns * ( Math.floor( postsPerPage / columns ) + 1 ); // postsPerPage + ( postsPerPage % columns );
			}
		} else {
			newPostsPerPage = columns;
		}
	}

	if ( columns > 1 && ( ( currentPostsPerPage % columns ) === 0 ) && ( newPostsPerPage === currentPostsPerPage ) ) {
		return;
	}

	// From now on, almost a re-pagination needs to be done.

	// Get a show more button, and remove all buttons.
	const elementChildren = element.children;
	let showMoreButton = null;
	for ( let i = 0; i < elementChildren.length; i++ ) {
		if ( elementChildren[ i ].classList.contains( showMoreWrapperClassName ) ) {
			if ( showMoreButton === null ) {
				showMoreButton = elementChildren[ i ].cloneNode( true );
			}
			element.removeChild( elementChildren[ i ] );
		}
	}

	// all pages are displayed, so no need to continue.
	if ( showMoreButton === null ) {
		return;
	}

	let pagesDisplayed = Number( element.getAttribute( dataPagesDisplayed ) );
	if ( ! pagesDisplayed ) {
		pagesDisplayed = 1;
	}
	let page = 1;
	let blockNumber = 0;
	for ( let i = 0; i < elementChildren.length; i++ ) {
		if ( blockNumber !== 0 && newPostsPerPage !== 0 && ( blockNumber % newPostsPerPage === 0 ) ) {
			if ( page === pagesDisplayed ) {
				element.insertBefore( showMoreButton.cloneNode( true ), elementChildren[ i ] );
				i++;
			} else {
				page++;
			}
		}

		if ( elementChildren[ i ].classList.contains( 'twrp-block' ) ) {
			blockNumber++;
		}
	}

	element.setAttribute( dataCurrentPostsPerPage, String( newPostsPerPage ) );
}

// #endregion -- Equalize number of posts to columns.

// #region -- Helper Functions

function countNumberOfColumns( element: unknown ): false|number {
	if ( ! ( element instanceof HTMLElement ) && ! ( element instanceof Element ) ) {
		return false;
	}

	const compStyles = window.getComputedStyle( element );
	const gridColumnsProperty = compStyles.getPropertyValue( 'grid-template-columns' );

	// @ts-ignore -- Verify if is string.
	if ( ! ( typeof gridColumnsProperty === 'string' ) && ! ( gridColumnsProperty instanceof String ) ) {
		return false;
	}

	// Verify if computed styles have been calculated.
	if ( compStyles.display !== 'grid' ) {
		return false;
	}

	// Some people sat that there is a bug that the computed styles of columns have added some 0px columns.
	const columns = gridColumnsProperty.replace( / 0px/g, '' ).split( ' ' ).length;
	if ( Number.isInteger( columns ) && columns > 0 ) {
		return columns;
	}

	return false;
}

// #endregion -- Helper Functions

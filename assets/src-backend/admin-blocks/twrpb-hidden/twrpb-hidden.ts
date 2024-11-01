import $ from 'jquery';
import 'jqueryui';

// #region -- Run hideLeft/ showLeft only one at time.

let horizontalAnimationRunning = false;

const horizontalAnimationQueue = [];

function addQueueAnimation( functionName, args ) {
	horizontalAnimationRunning = true;
	const queueItem = { name: functionName, parameters: args };
	horizontalAnimationQueue.push( queueItem );
}

function nextQueueAnimation() {
	if ( horizontalAnimationQueue.length ) {
		const horizontalQueueItem = horizontalAnimationQueue.pop();

		if ( horizontalAnimationQueue.length === 0 ) {
			horizontalAnimationRunning = false;
		} else {
			horizontalAnimationRunning = true;
		}

		const functionName = horizontalQueueItem.name;
		functionName.apply( null, horizontalQueueItem.parameters );
	}

	if ( horizontalAnimationQueue.length === 0 ) {
		horizontalAnimationRunning = false;
	} else {
		horizontalAnimationRunning = true;
	}
}

function horizontalAnimationIsRunning() {
	return horizontalAnimationRunning;
}

function makeHorizontalRunning() {
	horizontalAnimationRunning = true;
}

// #endregion -- Run hideLeft/ showLeft only one at time.

const effectDuration = 500;
const effectDurationHorizontal = 300;

function toggleDisplay( element: JQuery ): void {
	if ( element.hasClass( 'twrpb-hidden' ) ) {
		showUp( element );
	} else {
		hideUp( element );
	}
}

// #region -- Hide/Show Vertically

function hideUp( element: JQuery ): void {
	$( element ).slideUp( {
		duration: effectDuration,
		complete() {
			addHideClass( element );
		},
	} );
}

function showUp( element: JQuery ): void {
	$( element ).slideDown( {
		duration: effectDuration,
		complete() {
			removeHideClass( element );
		},
	} );
}

// #endregion -- Hide/Show Vertically

// =============================================================================

/**
 * Hide a HTML element with a blind effect from the left.
 *
 * @param {HTMLElement} element A jquery element.
 * @param {'remove'} remove Add 'remove' to remove the element from the DOM.
 */
function hideLeft( element: JQuery, remove: string = '' ): void {
	// If something is being hidden/revealed, then wait for it to finish.
	if ( horizontalAnimationIsRunning() ) {
		addQueueAnimation( hideLeft, [ element, remove ] );
		return;
	}
	makeHorizontalRunning();

	let completeFunction = function() {
		addHideClass( element );
		nextQueueAnimation();
	};
	if ( remove === 'remove' ) {
		completeFunction = function() {
			removeElement( element );
			nextQueueAnimation();
		};
	}

	$( element ).hide( {
		effect: 'blind',
		duration: effectDurationHorizontal,
		direction: 'left',
		complete: completeFunction,
	} );
}

/**
 * Show a HTML element with a blind effect from the left.
 *
 * @param {HTMLElement} element A jquery element.
 * @param {'hide_first'} hideFirst Add 'hide_first' to first hide then show, if the
 * element already exists.
 */
function showLeft( element: JQuery, hideFirst: string = '' ): void {
	// If something is being hidden/revealed, then wait for it to finish.
	if ( horizontalAnimationIsRunning() ) {
		addQueueAnimation( showLeft, [ element, hideFirst ] );
		return;
	}
	makeHorizontalRunning();

	if ( hideFirst === 'hide_first' ) {
		element.addClass( 'twrpb-hidden' );
	}

	$( element ).show( {
		effect: 'blind',
		duration: effectDurationHorizontal,
		direction: 'left',
		complete() {
			removeHideClass( element );
			nextQueueAnimation();
		},
	} );
}

// =============================================================================

function addHideClass( element: JQuery ): void {
	$( element ).addClass( 'twrpb-hidden' );
}

function removeHideClass( element: JQuery ): void {
	$( element ).removeClass( 'twrpb-hidden' ).css( 'display', '' );
}

function removeElement( element: JQuery ): void {
	$( element ).remove();
}

// =============================================================================

export {
	toggleDisplay,
	showUp,
	hideUp,
	hideLeft,
	showLeft,
};

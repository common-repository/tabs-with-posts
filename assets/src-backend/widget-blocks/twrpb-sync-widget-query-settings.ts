/**
 * Works on: "type"="checkbox", type="number", type="hidden", pickr color, select.
 */

import $ from 'jquery';

const widgetFormSelector = '.twrpb-widget-form';
const queryTabSettingsSelector = '.twrpb-widget-form__selected-query';

// #region -- Update what widgets to sync.
// The widgets that are marked to sync have twrpb-widget-form--js-sync-settings class name.

$( updateAllWidgetSyncClasses );
$( document ).on( 'widget-updated widget-added', updateAllWidgetSyncClasses );
$( document ).on( 'change', '[type="checkbox"][name$="sync_query_settings]"]', updateAllWidgetSyncClasses );

function updateAllWidgetSyncClasses() {
	const syncSetting = $( document ).find( '[type="checkbox"][name$="sync_query_settings]"]' );
	syncSetting.each( function() {
		const syncSettingValue = $( this ).prop( 'checked' );
		const widget = $( this ).closest( widgetFormSelector );
		if ( syncSettingValue ) {
			addSyncClassToWidget( widget );
		} else {
			removeSyncClassFromWidget( widget );
		}
	} );
}

function addSyncClassToWidget( widget ) {
	widget.addClass( 'twrpb-widget-form--js-sync-settings' );
}

function removeSyncClassFromWidget( widget ) {
	widget.removeClass( 'twrpb-widget-form--js-sync-settings' );
}

// #endregion -- Update what widgets to sync.

// #region -- Sync the widget settings when the sync button is clicked.

$( document ).on( 'change', '[type="checkbox"][name$="sync_query_settings]"]', handleSyncButtonEnabled );

function handleSyncButtonEnabled() {
	const settingEnabled = $( this ).prop( 'checked' );

	if ( settingEnabled ) {
		syncAllWidgetSettings( $( this ).closest( widgetFormSelector ) );
	}
}

// #endregion -- Sync the widget settings when the sync button is clicked.

// #region -- Sync all settings for a widget

$( document ).on( 'twrpb-query-added twrpb-artblock-added', '.twrpb-widget-form--js-sync-settings', handleQueryOrArtblockAdded );

function handleQueryOrArtblockAdded() {
	const widget = $( this );
	syncAllWidgetSettings( widget );

	// Sometimes the pickr color won't sync because it gets update at the same time, so we force it.
	setTimeout( function() {
		syncAllWidgetSettings( widget );
	}, 100 );

	setTimeout( function() {
		syncAllWidgetSettings( widget );
	}, 1000 );
}

/**
 * Sync all query settings of the widget with the first query setting.
 */
function syncAllWidgetSettings( widget: JQuery ): void {
	const firstQuery = widget.find( queryTabSettingsSelector ).eq( 0 );

	const toSyncElements = firstQuery.find( 'input, select' );

	toSyncElements.each( function() {
		syncElement( $( this ) );
	} );
}

// #endregion -- Sync all settings for a widget

// #region -- Sync all equivalent query settings, if a setting change.

addDocumentEvents();
function addDocumentEvents() {
	$( document ).on( 'change', '.twrpb-widget-form--js-sync-settings' + ' ' + queryTabSettingsSelector, syncTheSetting );
}

function removeDocumentEvents() {
	$( document ).off( 'change', '.twrpb-widget-form--js-sync-settings' + ' ' + queryTabSettingsSelector, syncTheSetting );
}

function syncTheSetting( event ) {
	const elementChanged = $( event.target );
	syncElement( elementChanged );
}

// #endregion -- Sync all equivalent query settings, if a setting change.

/**
 * For a setting control passed, sync the other similar query settings with
 * same value.
 */
function syncElement( elementChanged: JQuery ) {
	const elementName = elementChanged.attr( 'name' );

	const shouldBeSynched = settingShouldBeSynched( elementChanged );
	if ( ! shouldBeSynched ) {
		return;
	}

	const allWidgetQueriesTabs = elementChanged.closest( widgetFormSelector ).find( queryTabSettingsSelector );
	const nameSuffixToSearch = elementName.replace( /widget-twrp_tabs_with_recommended_posts\[\d+\]\[\d+\]/, '' );

	let allSimilarQuerySettings = allWidgetQueriesTabs.find( '[name$="' + nameSuffixToSearch + '"]' );
	allSimilarQuerySettings = filterSimilarQuerySettings( elementChanged, allSimilarQuerySettings );

	const settingsChanged = changeAllSettings( elementChanged, allSimilarQuerySettings );

	triggerNecessaryChanges( settingsChanged );
	// We don't need yet to trigger all changes.
	// triggerAllChanges( elementChanged, allSimilarQuerySettings );
}

/**
 * Whether or not it should sync this setting control.
 */
function settingShouldBeSynched( settingControl ): boolean {
	if ( settingControl.is( '[name$="[display_title]"]' ) ) {
		return false;
	}

	return true;
}

/**
 * Filter the similar settings found.
 *
 * Usually used on settings that have custom controllers.
 */
function filterSimilarQuerySettings( settingElement: JQuery, allSimilarQuerySettings : JQuery ): JQuery {
	const elementType = getElementChangedType( settingElement );

	if ( elementType === 'checkbox' ) {
		allSimilarQuerySettings = allSimilarQuerySettings.not( '[type="hidden"]' );
	}

	return allSimilarQuerySettings;
}

/**
 * Based on a setting control, change all other settings controls passed. This
 * function will return a list with all the controls that were changed(value
 * different than the based setting control).
 */
function changeAllSettings( elementName: JQuery, allSimilarQuerySettings : JQuery ): JQuery {
	const elementType = getElementChangedType( elementName );
	const valueToChange = elementName.val();
	let elementsChanged = $();

	allSimilarQuerySettings.each( function() {
		const currentElement = $( this );

		if ( elementType === 'checkbox' ) {
			const propValue = elementName.prop( 'checked' );
			const currentSetting = currentElement.prop( 'checked' );
			if ( propValue !== currentSetting ) {
				elementsChanged = elementsChanged.add( currentElement );
				currentElement.prop( 'checked', propValue );
			}
		} else if ( elementType === 'select' || elementType === 'number' || elementType === 'hidden' ) {
			const currentValue = currentElement.val();
			if ( valueToChange !== currentValue ) {
				elementsChanged = elementsChanged.add( currentElement );
				currentElement.val( valueToChange );
			}
		} else if ( elementType === 'color' ) {
			const currentValue = currentElement.val();
			if ( valueToChange !== currentValue ) {
				elementsChanged = elementsChanged.add( currentElement );
				currentElement.val( valueToChange );
			}

			// This is left outside, because we can refresh the button display
			// in case the pickr initialize slower.
			if ( valueToChange && ( typeof valueToChange === 'string' ) ) {
				currentElement.next( '.pickr' ).children( 'button' ).css( 'color', valueToChange );
			} else {
				currentElement.next( '.pickr' ).children( 'button' ).css( 'color', 'rgba(0, 0, 0, 0.15)' );
			}
		}
	} );

	return elementsChanged;
}

/**
 * Trigger the changes only to elements that have other javascript calls to them.
 */
function triggerNecessaryChanges( settingControlsToTriggerChanges:JQuery ): void {
	let triggerChanges = false;
	const firstControl = settingControlsToTriggerChanges.eq( 0 );

	// See whether or not we should trigger changes.
	const elementName = String( firstControl.attr( 'name' ) );
	if ( elementName.includes( '[article_block]' ) ) {
		triggerChanges = true;
	}

	if ( triggerChanges ) {
		removeDocumentEvents();
		settingControlsToTriggerChanges.each( function() {
			$( this ).trigger( 'change' );
		} );
		addDocumentEvents();
	}
}

/**
 * Trigger all the changes done to all elements.
 */
function triggerAllChanges( elementName: JQuery, allSimilarQuerySettings : JQuery ): void {
	removeDocumentEvents();
	const elementType = getElementChangedType( elementName );
	allSimilarQuerySettings.each( function() {
		if ( elementType === 'checkbox' ) {
			$( this ).trigger( 'change' );
		} else if ( elementType === 'select' ) {
			$( this ).trigger( 'change' );
		} else if ( elementType === 'number' ) {
			$( this ).trigger( 'change' );
		} else if ( elementType === 'hidden' ) {
			$( this ).trigger( 'change' );
		} else if ( elementType === 'color' ) {
			$( this ).trigger( 'change' );
		}
	} );
	addDocumentEvents();
}

/**
 * Get the input type that is changed.
 *
 * The additional check on hidden type is because the checkboxes can have a
 * hidden input before, to submit something to the server.
 */
function getElementChangedType( settingElement ): string {
	if ( settingElement.attr( 'type' ) === 'hidden' && settingElement.next( '.pickr, .twrpb-color-picker' ).length ) {
		return 'color';
	} else if ( settingElement.attr( 'type' ) === 'hidden' && settingElement.next( '[type="checkbox"]' ).length === 0 ) {
		return 'hidden';
	} else if ( settingElement.attr( 'type' ) === 'checkbox' ) {
		return 'checkbox';
	} else if ( settingElement.is( 'select' ) ) {
		return 'select';
	} else if ( settingElement.attr( 'type' ) === 'number' ) {
		return 'number';
	}
}

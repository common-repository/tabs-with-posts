import $ from 'jquery';

const querySettingCollapsibleSelector = '.twrpb-query-settings__setting';

const dataSettingName = 'data-twrpb-setting-name';
const dataDefaultSettingsName = 'data-twrpb-default-settings';

const selectTemplateSelector = '#twrpb-query-settings__predefined_template_selector';
const applyTemplateBtnSelector = '#twrpb-query-settings__apply_template_btn';
const dataTemplatesName = 'data-twrpb-templates';
const dataConfirmMessage = 'data-twrpb-confirm-template-changes';

const collapsibleSelector = '.twrpb-collapsible';

$( document ).on( 'click', applyTemplateBtnSelector, applyTemplate );

function applyTemplate() {
	const selectTemplate = $( selectTemplateSelector );
	const template = String( selectTemplate.val() );
	let allTemplatesOptions: string;
	try {
		allTemplatesOptions = JSON.parse( selectTemplate.attr( dataTemplatesName ) );
	} catch ( e ) {
		return false;
	}
	const templateOptions = allTemplatesOptions[ template ];

	if ( templateOptions === undefined ) {
		return;
	}

	const confirmation = userConfirmedChanges();
	if ( ! confirmation ) {
		return;
	}

	// Reset all queries to default and put the template settings.
	resetAllQuerySettingsToDefault();
	for ( const settingName of Object.keys( templateOptions ) ) {
		const settingValue = templateOptions[ settingName ];
		if ( settingValue === undefined ) {
			continue;
		}

		const control = $( '[name="' + settingName + '"]' );
		// If we have no controls of this type, maybe we have a checkbox type of control, which can have another array element.
		if ( control.length === 0 ) {
			$( '[name^="' + settingName + '"]' );
		}

		setControlValue( control, settingValue );

		// Make sure that we open the control collapsible. We toggle this late to not show very much of inside how things are hidden/show.
		setTimeout( () => {
			openControlCollapsible( control );
		}, 300 );
	}
}

// #region -- Set all settings to default.

function resetAllQuerySettingsToDefault() {
	const querySettingsWrappers = $( querySettingCollapsibleSelector ).not( '[' + dataSettingName + '="query_name"]' );

	for ( let i = 0; i < querySettingsWrappers.length; i++ ) {
		setQuerySettingToDefault( querySettingsWrappers.eq( i ) );
	}
}

function setQuerySettingToDefault( querySettingWrapper: JQuery ) {
	const settingBaseName = querySettingWrapper.attr( dataSettingName );
	const defaultSettings = JSON.parse( querySettingWrapper.attr( dataDefaultSettingsName ) );

	for ( const settingName of Object.keys( defaultSettings ) ) {
		const defaultSettingValue = defaultSettings[ settingName ];
		const fullSettingName = settingBaseName + '[' + settingName + ']';

		const settingControl = $( '[name="' + fullSettingName + '"], [type="checkbox"][name^="' + fullSettingName + '"]' );
		if ( settingControl.length === 0 ) {
			continue;
		}

		setControlValue( settingControl, defaultSettingValue );
	}
}

// #endregion -- Set all settings to default.

// #region -- Set a value of a control.

function setControlValue( control: JQuery, setting: string|Array<string> ) {
	if ( getControlType( control ) === 'select' ) {
		control.val( setting );
		triggerControlChange( control );
		return;
	}

	if ( getControlType( control ) === 'checkbox' ) {
		if ( Array.isArray( setting ) ) {
			control.each( ( index, el ) => {
				const element = $( el );

				// If the element is checked and it shouldn't, then uncheck.
				if ( setting.indexOf( String( element.val() ) ) === -1 && element.prop( 'checked' ) === true ) {
					element.prop( 'checked', false );
					triggerControlChange( element );
				}

				// If the element is unchecked and it should, then check.
				if ( setting.indexOf( String( element.val() ) ) !== -1 && element.prop( 'checked' ) === false ) {
					element.prop( 'checked', true );
					triggerControlChange( element );
				}
			} );
		}
		return;
	}

	if ( getControlType( control ) === 'text' ) {
		control.val( setting );
		triggerControlChange( control );
		return;
	}

	if ( getControlType( control ) === 'number' ) {
		control.val( setting );
		triggerControlChange( control );
		return;
	}

	if ( getControlType( control ) === 'date' ) {
		control.val( setting );
		triggerControlChange( control );
		return;
	}

	if ( getControlType( control ) === 'radio' ) {
		if ( ! ( typeof setting === 'string' ) && ! ( setting instanceof String ) ) {
			setting = '';
		}

		control.each( ( index, el ) => {
			const element = $( el );
			if ( element.prop( 'checked' ) && ! element.is( '[value="' + setting + '"]' ) ) {
				element.prop( 'checked', false );
				triggerControlChange( element );
			}

			if ( element.is( '[value="' + setting + '"]' ) && ! element.prop( 'checked' ) ) {
				element.prop( 'checked', true );
				triggerControlChange( element );
			}
		} );
		return;
	}

	if ( getControlType( control ) === 'textarea' ) {
		control.val( setting );

		// @ts-ignore
		if ( control.next().is( '.CodeMirror' ) && control.next().get( 0 ).CodeMirror ) {
			// @ts-ignore
			control.next().get( 0 ).CodeMirror.setValue( '' );
		}

		triggerControlChange( control );
	}
}

function triggerControlChange( control: JQuery ): void {
	// Default trigger.
	control.trigger( 'change' );
}

// #endregion -- Set a value of a control.

// #region -- Get controls that are known to the code and can be safely managed.

/**
 * Get the type of control recognized.
 *
 * For the moment only select, checkboxes, textarea, CodeMirror textarea,
 * number, radio, and text are recognized.
 */
function getControlType( control: JQuery ): string {
	if ( control.is( 'select' ) ) {
		return 'select';
	}

	if ( control.is( 'input[type="checkbox"]' ) ) {
		return 'checkbox';
	}

	if ( control.is( 'input[type="text"]' ) ) {
		return 'text';
	}

	if ( control.is( 'input[type="number"]' ) ) {
		return 'number';
	}

	if ( control.is( 'input[type="date"]' ) ) {
		return 'date';
	}

	if ( control.is( 'input[type="radio"]' ) ) {
		return 'radio';
	}

	if ( control.is( 'textarea' ) ) {
		return 'textarea';
	}

	return null;
}

// #endregion -- Get controls that are known to the code and can be safely managed.

function userConfirmedChanges(): boolean {
	let message = $( selectTemplateSelector ).attr( dataConfirmMessage );
	if ( ! message ) {
		message = 'This will modify all the current settings to the specific template. Are you sure?';
	}

	// eslint-disable-next-line no-alert
	return confirm( message );
}

function openControlCollapsible( control: JQuery ) {
	const collapsible = control.closest( collapsibleSelector );
	const accordionInstance = collapsible.accordion( 'instance' );
	if ( accordionInstance === undefined ) {
		return;
	}

	collapsible.accordion( 'option', 'active', 0 );
}

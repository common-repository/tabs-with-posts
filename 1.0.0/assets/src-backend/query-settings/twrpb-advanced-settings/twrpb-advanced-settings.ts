import $ from 'jquery';
import { hideUp, showUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

declare let wp: any;
let codeMirrorInstance = null;

$( enableCodeMirror );

function enableCodeMirror(): void {
	const element = document.getElementById( 'twrpb-advanced-settings__textarea' );

	if ( element ) {
		codeMirrorInstance = wp.CodeMirror.fromTextArea( element, {
			mode: 'application/json',
			theme: 'material-darker',
			indentUnit: 4,
			indentWithTabs: true,
			lineNumbers: true,
			autoRefresh: true,
		} );
	}
}

// #region -- Show/Hide Editor

const applySettingSelector = '#twrpb-advanced-settings__is-applied-selector';
const settingWrapperSelector = '#twrpb-advanced-settings__textarea-wrapper';

$( hideOrShowSetting );
$( document ).on( 'change', applySettingSelector, hideOrShowSetting );

function hideOrShowSetting() {
	const settingIsAppliedVal = String( $( applySettingSelector ).val() );

	if ( settingIsAppliedVal === 'not_apply' ) {
		hideUp( $( settingWrapperSelector ) );
	} else {
		showUp( $( settingWrapperSelector ) );
	}
}

// #endregion -- Show/Hide Editor

// #region -- Invalid JSON

const textareaWrapperSelector = '#twrpb-advanced-settings__textarea-wrapper';
const warningSelector = '#twrpb-setting-note__invalid_json_warning';

$( showOrHideJSONInvalidWarning );
$( document ).on( 'change keyup paste', textareaWrapperSelector, showOrHideJSONInvalidWarning );

function showOrHideJSONInvalidWarning() {
	if ( codeMirrorInstance === null ) {
		return;
	}

	const textareaJSON = codeMirrorInstance.getValue();

	if ( isValidJSON( textareaJSON ) || '' === textareaJSON ) {
		hideUp( $( warningSelector ) );
	} else {
		showUp( $( warningSelector ) );
	}
}

function isValidJSON( jsonString ) {
	try {
		const o = JSON.parse( jsonString );

		// Handle non-exception-throwing cases:
		// Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
		// but... JSON.parse(null) returns null, and typeof null === "object",
		// so we must check for that, too. Thankfully, null is false, so this suffices:
		if ( o && typeof o === 'object' ) {
			return true;
		}
	} catch ( e ) { }

	return false;
}

// #endregion -- Invalid JSON

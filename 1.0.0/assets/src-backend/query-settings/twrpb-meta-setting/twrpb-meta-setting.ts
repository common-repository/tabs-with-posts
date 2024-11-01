import $ from 'jquery';
import { showUp, hideUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

const metaTypeSelector = '#twrpb-meta-setting__js-meta-type';
const metaValueSelector = '#twrpb-meta-setting__js-meta-value-group';

$( showOrHideValueInput );
$( document ).on( 'change', metaTypeSelector, showOrHideValueInput );

function showOrHideValueInput() {
	const metaTypeValue = $( metaTypeSelector ).val();

	if ( metaTypeValue === 'NOT EXISTS' || metaTypeValue === 'EXISTS' ) {
		hideUp( $( metaValueSelector ) );
	} else {
		showUp( $( metaValueSelector ) );
	}
}

// #region -- Hide or show the whole meta setting.

const settingIsAppliedSelector = '#twrpb-meta-setting__js-apply-meta-select';
const settingWrapperSelector = '#twrpb-meta-setting__js-setting-wrapper';

$( hideOrShowMetaSetting );
$( document ).on( 'change', settingIsAppliedSelector, hideOrShowMetaSetting );

function hideOrShowMetaSetting() {
	const isApplied = String( $( settingIsAppliedSelector ).val() );

	if ( 'NA' === isApplied ) {
		hideUp( $( settingWrapperSelector ) );
	} else {
		showUp( $( settingWrapperSelector ) );
	}
}

// #endregion -- Hide or show the whole meta setting.

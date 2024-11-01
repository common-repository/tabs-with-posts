import $ from 'jquery';
import { hideUp, showUp } from '../../admin-blocks/twrpb-hidden/twrpb-hidden';

const applySettingSelect = $( '#twrpb-statuses-settings__js-apply-select' );
const postStatuses = $( '#twrpb-statuses-settings__js-statuses-wrapper' );

$( document ).on( 'change', '#twrpb-statuses-settings__js-apply-select', hideOrShowPostStatuses );
$( hideOrShowPostStatuses );

function hideOrShowPostStatuses() {
	if ( applySettingSelect.val() === 'not_applied' ) {
		hideUp( postStatuses );
	} else {
		showUp( postStatuses );
	}
}

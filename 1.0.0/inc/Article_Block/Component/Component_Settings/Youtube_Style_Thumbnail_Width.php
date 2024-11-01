<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Number_Control;

/**
 * Class used to change the width of the thumbnail in the yt style.
 */
class Youtube_Style_Thumbnail_Width extends Component_Setting {

	public function get_key_name() {
		return 'yt_style_width';
	}

	public function display_setting( $prefix_id, $prefix_name, $value ) {
		$id   = $prefix_id . '-' . $this->get_key_name();
		$name = $prefix_name . '[' . $this->get_key_name() . ']';

		Number_Control::display_setting( $id, $name, $value, $this->get_control_setting_args() );
	}

	public function sanitize_setting( $value ) {
		return Number_Control::sanitize_setting( $value, $this->get_control_setting_args() );
	}

	protected function get_control_setting_args() {
		return array(
			'default' => '',
			'before'  => _x( 'Width:', 'backend', 'tabs-with-posts' ),
			'after'   => _x( 'px. (120px default).', 'backend, px is a CSS unit', 'tabs-with-posts' ),
			'max'     => '250',
			'min'     => '80',
			'step'    => '5',
		);
	}

	public function get_css( $value ) {
		if ( is_numeric( $value ) ) {
			return "--twrp-yt-thumbnail-width:${value}px;";
		}

		return '';
	}
}

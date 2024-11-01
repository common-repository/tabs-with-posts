<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Number_Control;

/**
 * Class used to change the line height of the specific component.
 */
class Line_Height_Setting extends Component_Setting {

	public function get_key_name() {
		return 'line_height';
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
			'before'  => _x( 'Line Height:', 'backend, font line', 'tabs-with-posts' ),
			'after'   => '',
			'max'     => '3',
			'min'     => '0.7',
			'step'    => '0.05',
		);
	}

	public function get_css( $value ) {
		if ( is_numeric( $value ) ) {
			return "line-height:${value};";
		}

		return '';
	}
}

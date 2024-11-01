<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Number_Control;

/**
 * Class used to change the border-radius variable of the specific component.
 */
class Border_Radius_Setting extends Component_Setting {

	public function get_key_name() {
		return 'border_radius';
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
			'before'  => _x( 'Border Radius:', 'backend', 'tabs-with-posts' ),
			'after'   => 'px',
			'max'     => '50',
			'min'     => '0',
			'step'    => '1',
		);
	}

	public function get_css( $value ) {
		if ( is_numeric( $value ) ) {
			return "--twrp-border-radius:${value}px;";
		}

		return '';
	}
}

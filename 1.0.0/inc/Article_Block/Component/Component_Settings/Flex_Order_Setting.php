<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Number_Control;

/**
 * Class used to change the flex order of the specific component.
 */
class Flex_Order_Setting extends Component_Setting {

	public function get_key_name() {
		return 'flex_order';
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
			'before'  => _x( 'Order:', 'backend, noun', 'tabs-with-posts' ),
			'max'     => '10',
			'min'     => '-10',
			'step'    => '1',
		);
	}

	public function get_css( $value ) {
		if ( is_numeric( $value ) ) {
			return "order:${value};";
		}

		return '';
	}
}

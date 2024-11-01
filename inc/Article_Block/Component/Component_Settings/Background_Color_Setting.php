<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Color_Control;

/**
 * Class used to change the background color of the specific component.
 */
class Background_Color_Setting extends Component_Setting {

	public function get_key_name() {
		return 'background_color';
	}

	public function display_setting( $prefix_id, $prefix_name, $value ) {
		$id   = $prefix_id . '-' . $this->get_key_name();
		$name = $prefix_name . '[' . $this->get_key_name() . ']';

		Color_Control::display_setting( $id, $name, $value, $this->get_control_setting_args() );
	}

	public function sanitize_setting( $value ) {
		return Color_Control::sanitize_setting( $value, $this->get_control_setting_args() );
	}

	protected function get_control_setting_args() {
		return array(
			'default' => '',
			'before'  => _x( 'Background color:', 'backend', 'tabs-with-posts' ),
		);
	}

	public function get_css( $value ) {
		if ( ! empty( $value ) ) {
			return "background-color:${value};";
		}

		return '';
	}
}

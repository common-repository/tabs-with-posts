<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Number_Control;

/**
 * Class used to change the meta font size of the specific component.
 */
class Meta_Font_Size_Setting extends Component_Setting {

	public function get_key_name() {
		return 'meta_font_size';
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
			'before'  => _x( 'Font size:', 'backend', 'tabs-with-posts' ),
			'after'   => 'rem',
			'max'     => '3',
			'min'     => '0.7',
			'step'    => '0.025',
		);
	}

	public function get_css( $value ) {
		if ( is_numeric( $value ) ) {
			return "--twrp-meta-font-size:${value}rem;";
		}

		return '';
	}
}

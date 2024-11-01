<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Select_Control;

/**
 * Class used to change the font weight of the specific component.
 */
class Font_Weight_Setting extends Component_Setting {

	public function get_key_name() {
		return 'font_weight';
	}

	public function display_setting( $prefix_id, $prefix_name, $value ) {
		$id   = $prefix_id . '-' . $this->get_key_name();
		$name = $prefix_name . '[' . $this->get_key_name() . ']';

		Select_Control::display_setting( $id, $name, $value, $this->get_control_setting_args() );
	}

	public function sanitize_setting( $value ) {
		return Select_Control::sanitize_setting( $value, $this->get_control_setting_args() );
	}

	protected function get_control_setting_args() {
		return array(
			'default' => '',
			'before'  => _x( 'Font weight:', 'backend', 'tabs-with-posts' ),
			'after'   => '',
			'options' => array(
				''        => _x( 'Not set', 'backend', 'tabs-with-posts' ),
				'inherit' => _x( 'inherit', 'backend', 'tabs-with-posts' ),
				'100'     => '100',
				'200'     => '200',
				'300'     => '300',
				'400'     => '400',
				'500'     => '500',
				'600'     => '600',
				'700'     => '700',
				'800'     => '800',
				'900'     => '900',
			),
		);
	}

	public function get_css( $font_weight ) {
		$control_settings = $this->get_control_setting_args();
		if ( isset( $control_settings['options'] ) && is_array( $control_settings['options'] ) ) {
			$possible_values = $control_settings['options'];
		} else {
			$possible_values = array();
		}

		if ( empty( $font_weight ) ) {
			return '';
		}

		if ( ! in_array( $font_weight, $possible_values, true ) ) {
			return '';
		}

		return "font-weight:${font_weight};";
	}
}

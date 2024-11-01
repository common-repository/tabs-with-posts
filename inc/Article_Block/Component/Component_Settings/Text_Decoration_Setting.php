<?php

namespace TWRP\Article_Block\Component;

use TWRP\Admin\Widget_Control\Select_Control;

/**
 * Class used to change the text decoration of the specific component.
 */
class Text_Decoration_Setting extends Component_Setting {

	public function get_key_name() {
		return 'text_decoration';
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
			'before'  => _x( 'Text Decoration:', 'backend', 'tabs-with-posts' ),
			'after'   => '',
			'options' => self::get_all_text_decoration_options(),
		);
	}

	public static function get_all_text_decoration_options() {
		return array(
			''                 => _x( 'Not set', 'backend, css - text property', 'tabs-with-posts' ),
			'underline'        => _x( 'underline', 'backend, css - text property', 'tabs-with-posts' ),
			'underline dashed' => _x( 'underline dashed', 'backend, css - text property', 'tabs-with-posts' ),
			'underline dotted' => _x( 'underline dotted', 'backend, css - text property', 'tabs-with-posts' ),
			'underline wavy'   => _x( 'underline wavy', 'backend, css - text property', 'tabs-with-posts' ),
			'overline'         => _x( 'overline', 'backend, css - text property', 'tabs-with-posts' ),
			'line-through'     => _x( 'line-through', 'backend, css - text property', 'tabs-with-posts' ),
		);
	}

	public function get_css( $text_decoration ) {
		$control_settings = $this->get_control_setting_args();
		if ( isset( $control_settings['options'] ) && is_array( $control_settings['options'] ) ) {
			$possible_values = $control_settings['options'];
		} else {
			$possible_values = array();
		}

		if ( empty( $text_decoration ) ) {
			return '';
		}

		if ( ! in_array( $text_decoration, $possible_values, true ) ) {
			return '';
		}

		return "text-decoration:${text_decoration};";
	}
}

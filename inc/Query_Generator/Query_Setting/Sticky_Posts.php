<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Class that will create the setting to include or not sticky posts.
 */
class Sticky_Posts extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 33;
	}

	/**
	 * The name of the setting which represents whether or not to include sticky
	 * posts.
	 */
	const INCLUSION__SETTING_NAME = 'inclusion';

	public function get_setting_name() {
		return 'sticky_posts';
	}

	public function get_default_setting() {
		return array(
			self::INCLUSION__SETTING_NAME => 'not_include',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::INCLUSION__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$inclusion_setting = $setting[ self::INCLUSION__SETTING_NAME ];
		$possible_values   = array( 'not_include', 'include' );
		if ( ! in_array( $inclusion_setting, $possible_values, true ) ) {
			return $this->get_default_setting();
		}

		$sanitized_setting = array(
			self::INCLUSION__SETTING_NAME => $setting[ self::INCLUSION__SETTING_NAME ],
		);

		return $sanitized_setting;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		$inclusion_setting = $query_settings[ $this->get_setting_name() ][ self::INCLUSION__SETTING_NAME ];

		if ( 'include' === $inclusion_setting ) {
			$previous_query_args['ignore_sticky_posts'] = false;
		} else {
			$previous_query_args['ignore_sticky_posts'] = true;
		}

		return $previous_query_args;
	}
}

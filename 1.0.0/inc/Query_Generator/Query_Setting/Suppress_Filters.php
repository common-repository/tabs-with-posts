<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Class that will make a setting that will decide whether or not to suppress
 * the query filters.
 *
 * The default of this setting is true(opposed from WP default false), to try
 * to not have unexpected behaviour because of a plugin/theme/custom filter.
 */
class Suppress_Filters extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 90;
	}

	/**
	 * The array key of the setting that remember whether or not to suppress the
	 * filters.
	 */
	const SUPPRESS_FILTERS__SETTING_NAME = 'suppress';

	public function get_setting_name() {
		return 'suppress_filters';
	}

	public function get_default_setting() {
		return array(
			self::SUPPRESS_FILTERS__SETTING_NAME => 'true',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! is_array( $setting ) ) {
			return $this->get_default_setting();
		}

		if ( ! isset( $setting[ self::SUPPRESS_FILTERS__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$suppress_possible_values = array( 'true', 'false' );
		if ( ! in_array( $setting[ self::SUPPRESS_FILTERS__SETTING_NAME ], $suppress_possible_values, true ) ) {
			return $this->get_default_setting();
		}
		$sanitized_settings = array();
		$sanitized_settings[ self::SUPPRESS_FILTERS__SETTING_NAME ] = $setting[ self::SUPPRESS_FILTERS__SETTING_NAME ];

		return $sanitized_settings;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! isset( $query_settings[ $this->get_setting_name() ][ self::SUPPRESS_FILTERS__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}

		if ( 'true' === $query_settings[ $this->get_setting_name() ][ self::SUPPRESS_FILTERS__SETTING_NAME ] ) {
			$previous_query_args['suppress_filters'] = true;
		}

		return $previous_query_args;
	}
}

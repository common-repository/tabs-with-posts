<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Class that will let administrators custom modifying the advanced arguments
 * via JSON parameters.
 */
class Advanced_Arguments extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 1000;
	}

	/**
	 * The setting name and the array key of the option that remembers whether
	 * or not the custom arguments are applied.
	 */
	const IS_APPLIED__SETTING_NAME = 'is_applied_setting';

	/**
	 * The setting name and the array key where the custom arguments JSON is found.
	 */
	const CUSTOM_ARGS__SETTING_NAME = 'custom_args_json';

	public function get_setting_name() {
		return 'advanced_args';
	}

	public function get_default_setting() {
		return array(
			self::IS_APPLIED__SETTING_NAME  => 'not_apply',
			self::CUSTOM_ARGS__SETTING_NAME => '',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::CUSTOM_ARGS__SETTING_NAME ], $setting[ self::IS_APPLIED__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$is_applied_possible_values = array( 'not_apply', 'apply' );
		if ( ! in_array( $setting[ self::IS_APPLIED__SETTING_NAME ], $is_applied_possible_values, true ) ) {
			return $this->get_default_setting();
		}

		if ( empty( trim( $setting[ self::CUSTOM_ARGS__SETTING_NAME ] ) ) ) {
			return $this->get_default_setting();
		}

		$sanitized_setting = array(
			self::IS_APPLIED__SETTING_NAME => $setting[ self::IS_APPLIED__SETTING_NAME ],
		);

		$sanitized_setting[ self::CUSTOM_ARGS__SETTING_NAME ] = $setting[ self::CUSTOM_ARGS__SETTING_NAME ];

		return $sanitized_setting;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! isset( $query_settings[ $this->get_setting_name() ][ self::IS_APPLIED__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}
		$settings = $query_settings[ $this->get_setting_name() ];

		if ( 'apply' !== $settings[ self::IS_APPLIED__SETTING_NAME ] ) {
			return $previous_query_args;
		}

		$json_settings = json_decode( $settings[ self::CUSTOM_ARGS__SETTING_NAME ], true );

		if ( ! is_array( $json_settings ) ) {
			return $previous_query_args;
		}

		return array_merge( $previous_query_args, $json_settings );
	}

}

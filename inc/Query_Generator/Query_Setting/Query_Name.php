<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Implements the query name setting.
 *
 * This setting is used for the administrators to give a query interrogation a
 * simple name to remember and describe what a query do. This name will be
 * visible only in the backend of the website.
 */
class Query_Name extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 0;
	}

	/**
	 * The name of the setting and array key which represents the query name.
	 */
	const QUERY_NAME__SETTING_NAME = 'name';

	public function get_setting_name() {
		return 'query_name';
	}

	public function get_default_setting() {
		return array(
			self::QUERY_NAME__SETTING_NAME => '',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::QUERY_NAME__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		if ( ! is_string( $setting[ self::QUERY_NAME__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$sanitized_setting                                   = array();
		$sanitized_setting[ self::QUERY_NAME__SETTING_NAME ] = $setting[ self::QUERY_NAME__SETTING_NAME ];

		return $sanitized_setting;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		return $previous_query_args;
	}

	/**
	 * Get the display name for a query.
	 *
	 * @param array $query_settings The full settings of the query, or just the query name settings.
	 * @param int|string $query_id_to_replace The query id to replace with, in case the query does not have a name.
	 * @return string Will return "Query-$query_id_to_replace" if a name doesn't exist.
	 */
	public static function get_query_display_name( $query_settings, $query_id_to_replace ) {
		$query_name_class = new Query_Name();
		if ( isset( $query_settings[ $query_name_class->get_setting_name() ][ self::QUERY_NAME__SETTING_NAME ] ) ) {
			$name = $query_settings[ $query_name_class->get_setting_name() ][ self::QUERY_NAME__SETTING_NAME ];
		} elseif ( isset( $query_settings[ self::QUERY_NAME__SETTING_NAME ] ) ) {
			$name = $query_settings[ self::QUERY_NAME__SETTING_NAME ];
		} else {
			$name = '';
		}

		if ( empty( $name ) ) {
			/* translators: %s: an unique id number, this translation will be displayed if somehow no query name is present. */
			$name_replacement = _x( 'Query-%s', 'backend', 'tabs-with-posts' );
			$name             = sprintf( $name_replacement, $query_id_to_replace );
		}

		return $name;
	}
}

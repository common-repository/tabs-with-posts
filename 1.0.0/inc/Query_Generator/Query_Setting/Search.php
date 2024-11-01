<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Creates the possibility to filter a query based on a search string.
 */
class Search extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 70;
	}

	/**
	 * The name of the setting and array key which represents the search string.
	 */
	const SEARCH_KEYWORDS__SETTING_NAME = 'search_keywords';

	public function get_setting_name() {
		return 'search';
	}

	public function get_default_setting() {
		return array(
			self::SEARCH_KEYWORDS__SETTING_NAME => '',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::SEARCH_KEYWORDS__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}
		$search_keywords = $setting[ self::SEARCH_KEYWORDS__SETTING_NAME ];

		if ( ! is_string( $search_keywords ) ) {
			return $this->get_default_setting();
		}

		return $setting;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! isset( $query_settings[ $this->get_setting_name() ][ self::SEARCH_KEYWORDS__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}

		$search_keywords = $query_settings[ $this->get_setting_name() ][ self::SEARCH_KEYWORDS__SETTING_NAME ];

		if ( empty( $search_keywords ) ) {
			return $previous_query_args;
		}

		$previous_query_args['s'] = $search_keywords;
		return $previous_query_args;
	}
}

<?php

namespace TWRP\Query_Generator\Query_Setting;

use TWRP\Utils\Simple_Utils;

/**
 * Class that manages the author filter for the query.
 */
class Author extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 40;
	}

	/**
	 * The setting attribute name, and the array key of the author filter type.
	 */
	const AUTHORS_TYPE__SETTING_NAME = 'setting_type';

	/**
	 * The setting attribute name, and the array key of the ids selected. The
	 * ids selected are a string separated by ';'.
	 */
	const AUTHORS_IDS__SETTING_NAME = 'authors';

	/**
	 * Do not filter by authors type.
	 */
	const AUTHORS_TYPE__DISABLED = 'DISABLED';

	/**
	 * Filter by including authors.
	 */
	const AUTHORS_TYPE__INCLUDE = 'IN';

	/**
	 * Filter by excluding authors.
	 */
	const AUTHORS_TYPE__EXCLUDE = 'OUT';

	public function get_setting_name() {
		return 'author_settings';
	}

	public function get_default_setting() {
		return array(
			self::AUTHORS_TYPE__SETTING_NAME => self::AUTHORS_TYPE__DISABLED,
			self::AUTHORS_IDS__SETTING_NAME  => '',
		);
	}

	public function sanitize_setting( $settings ) {
		if ( ! isset( $settings[ self::AUTHORS_TYPE__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$authors_type    = $settings[ self::AUTHORS_TYPE__SETTING_NAME ];
		$available_types = array( self::AUTHORS_TYPE__DISABLED, self::AUTHORS_TYPE__INCLUDE, self::AUTHORS_TYPE__EXCLUDE );
		if ( ! in_array( $authors_type, $available_types, true ) ) {
			return $this->get_default_setting();
		}

		if ( self::AUTHORS_TYPE__DISABLED === $authors_type ) {
			return $this->get_default_setting();
		}

		$sanitized_setting = array(
			self::AUTHORS_TYPE__SETTING_NAME => $settings[ self::AUTHORS_TYPE__SETTING_NAME ],
			self::AUTHORS_IDS__SETTING_NAME  => '',
		);

		$authors_ids = explode( ';', $settings[ self::AUTHORS_IDS__SETTING_NAME ] );
		$authors_ids = Simple_Utils::get_valid_wp_ids( $authors_ids );

		$sanitized_authors_ids = array();
		foreach ( $authors_ids as $author_id ) {
			$author = get_userdata( (int) $author_id );
			if ( false !== $author ) {
				array_push( $sanitized_authors_ids, $author_id );
			}
		}
		$sanitized_authors_ids = implode( ';', $sanitized_authors_ids );

		if ( empty( $sanitized_authors_ids ) ) {
			return $this->get_default_setting();
		}

		$sanitized_setting[ self::AUTHORS_IDS__SETTING_NAME ] = $sanitized_authors_ids;

		return $sanitized_setting;
	}

	/**
	 * Create and insert the new arguments for the WP_Query.
	 *
	 * The previous query arguments will be modified such that will also contain
	 * the new settings, and will return the new query arguments to be passed
	 * into WP_Query class.
	 *
	 * @param array $previous_query_args The query arguments before being modified.
	 * @param array $query_settings All query settings, these settings are sanitized.
	 * @return array The new arguments modified.
	 */
	public function add_query_arg( $previous_query_args, $query_settings ) {
		$settings     = $query_settings[ $this->get_setting_name() ];
		$authors_type = $settings[ self::AUTHORS_TYPE__SETTING_NAME ];

		if ( self::AUTHORS_TYPE__DISABLED === $authors_type ) {
			return $previous_query_args;
		}

		$authors_ids = explode( ';', $settings[ self::AUTHORS_IDS__SETTING_NAME ] );
		$authors_ids = Simple_Utils::get_valid_wp_ids( $authors_ids );

		if ( empty( $authors_ids ) ) {
			return $previous_query_args;
		}

		if ( self::AUTHORS_TYPE__INCLUDE === $authors_type ) {
			$previous_query_args['author__in'] = $authors_ids;
		} elseif ( self::AUTHORS_TYPE__EXCLUDE === $authors_type ) {
			$previous_query_args['author__not_in'] = $authors_ids;
		}

		return $previous_query_args;
	}
}

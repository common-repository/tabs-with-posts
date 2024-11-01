<?php

namespace TWRP\Database;

/**
 * A setting in the WP_Options table, that will hold options that are not so
 * important, or are not modified directly by the user via an interface, but
 * nonetheless needs someplace to be stored.
 */
class Aside_Options implements Clean_Database {

	const TABLE_OPTION_KEY = 'twrp__aside_options';

	const KEY__NEEDED_ICONS_GENERATION_TIME = 'needed_icons_generation_timestamp';

	const KEY__NEEDED_ICONS_SVG = 'needed_icons_svg';

	#region -- Needed Icons Generation Timestamp

	/**
	 * Set the needed icons generation timestamp, to current timestamp.
	 *
	 * @return bool True if succeeded, false otherwise.
	 */
	public static function set_icons_generation_timestamp_to_current_timestamp() {
		$options = get_option( static::TABLE_OPTION_KEY, array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$time = (string) time();
		$options[ static::KEY__NEEDED_ICONS_GENERATION_TIME ] = $time;

		return update_option( static::TABLE_OPTION_KEY, $options );
	}

	/**
	 * Get the needed icons generation timestamp.
	 *
	 * @return string Empty string if not set.
	 */
	public static function get_needed_icons_generation_timestamp() {
		$options = get_option( static::TABLE_OPTION_KEY, array() );
		if ( ! is_array( $options ) ) {
			return '';
		}

		if ( isset( $options[ static::KEY__NEEDED_ICONS_GENERATION_TIME ] ) ) {
			return (string) $options[ static::KEY__NEEDED_ICONS_GENERATION_TIME ];
		}

		return '';
	}

	#endregion -- Needed Icons Generation Timestamp

	#region -- Save Icons SVG to Database

	/**
	 * Set the inline icons definitions.
	 *
	 * @param string $content
	 * @return bool True if setting was updated, false otherwise.
	 */
	public static function set_inline_icons( $content ) {
		$options = get_option( static::TABLE_OPTION_KEY, array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$options[ self::KEY__NEEDED_ICONS_SVG ] = $content;

		return update_option( self::TABLE_OPTION_KEY, $options );
	}

	/**
	 * Get the inline icons definitions.
	 *
	 * @return string Return empty string if not set.
	 */
	public static function get_inline_icons() {
		$options = get_option( self::TABLE_OPTION_KEY );

		if ( ! is_array( $options ) || ! isset( $options[ self::KEY__NEEDED_ICONS_SVG ] ) ) {
			return '';
		}

		$content = $options[ self::KEY__NEEDED_ICONS_SVG ];

		if ( is_string( $content ) ) {
			return $content;
		}

		return '';
	}

	#endregion -- Save Icons SVG to Database

	#region -- Clean Database

	public static function clean_database() {
		delete_option( self::TABLE_OPTION_KEY );
	}

	#region -- Clean Database

}

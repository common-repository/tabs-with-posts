<?php

namespace TWRP\Utils;

use TWRP\TWRP_Widget;
use RuntimeException;
use TWRP\Database\Query_Options;

/**
 * Class that contain a list of static methods, that can be used everywhere.
 *
 * The methods implemented here are methods usually used in widget form or other
 * widget settings.
 */
class Widget_Utils {

	const TWRP_WIDGET__BASE_ID = 'twrp_tabs_with_recommended_posts';

	/**
	 * Try to get the tab style id and the variant id used for a specific query
	 * id.
	 *
	 * @param array $widget_instance_settings
	 * @return array{tab_style_id:string,tab_variant_id:string}
	 */
	public static function pluck_tab_style_and_variant_id( $widget_instance_settings ) {
		$tab_style_and_variant = array(
			'tab_style_id'   => '',
			'tab_variant_id' => '',
		);

		if ( isset( $widget_instance_settings[ TWRP_Widget::TAB_STYLE_AND_VARIANT__NAME ] ) ) {
			$setting = $widget_instance_settings[ TWRP_Widget::TAB_STYLE_AND_VARIANT__NAME ];
			$setting = explode( '___', $setting );
			if ( isset( $setting[0] ) ) {
				$tab_style_and_variant['tab_style_id'] = $setting[0];
			}

			if ( isset( $setting[1] ) ) {
				$tab_style_and_variant['tab_variant_id'] = $setting[1];
			}
		}

		return $tab_style_and_variant;
	}

	/**
	 * Try to get the tab style variant that should be used for a specific query_id.
	 *
	 * @param array $widget_instance_settings
	 * @param int $query_id
	 * @return string|'' The title or empty string if not set.
	 */
	public static function pluck_tab_button_title( $widget_instance_settings, $query_id ) {
		if ( ! empty( $widget_instance_settings[ $query_id ][ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ] ) ) {
			return $widget_instance_settings[ $query_id ][ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ];
		}

		return '';
	}

	/**
	 * Get a specific query settings from the whole instance settings.
	 *
	 * @param array $widget_instance_settings
	 * @param int $query_id
	 * @return array Empty array if settings do not exist or are not set.
	 */
	public static function pluck_query_settings( $widget_instance_settings, $query_id ) {
		if ( isset( $widget_instance_settings[ $query_id ] ) ) {
			if ( is_array( $widget_instance_settings[ $query_id ] ) ) {
				return $widget_instance_settings[ $query_id ];
			}
		}

		return array();
	}

	/**
	 * Get all the valid query ids(that exist) from the widget instance settings.
	 *
	 * @param array $widget_instance_settings
	 * @return array
	 */
	public static function pluck_valid_query_ids( $widget_instance_settings ) {
		$query_ids = array();

		if ( isset( $widget_instance_settings['queries'] ) ) {
			$query_ids = explode( ';', $widget_instance_settings['queries'] );
		}

		$sanitized_query_ids = array();

		foreach ( $query_ids as $query_id ) {
			if ( Query_Options::query_exists( $query_id ) ) {
				array_push( $sanitized_query_ids, $query_id );
			}
		}

		return $sanitized_query_ids;
	}

	/**
	 * Try to get the artblock id that should be used for a specific query_id.
	 *
	 * @param array $widget_instance_settings
	 * @param string|int $query_id
	 * @return string|'' Empty string if no id is selected or not isset.
	 */
	public static function pluck_artblock_id( $widget_instance_settings, $query_id ) {
		if ( ! isset( $widget_instance_settings[ $query_id ][ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] ) ) {
			return '';
		}
		$artblock_id = $widget_instance_settings[ $query_id ][ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ];

		if ( ! is_string( $artblock_id ) ) {
			return '';
		}

		return $artblock_id;
	}

	/**
	 * Get the id of a control for the Tabs with Recommended Posts Widget.
	 *
	 * This is meant to replace the WP widget get_field_id() in
	 * WP_Widget::form() function.
	 *
	 * @param int|string $widget_id
	 * @param int|string ...$other_name_keys
	 * @return string
	 */
	public static function get_field_id( $widget_id, ...$other_name_keys ) {
		$suffix = '-' . $widget_id;
		foreach ( $other_name_keys as $name_key ) {
			$suffix .= '-' . $name_key;
		}

		return 'widget-' . TWRP_Widget::TWRP_BASE_ID . $suffix;
	}

	/**
	 * Get the name of a control for the Tabs with Recommended Posts Widget.
	 *
	 * This is meant to replace the WP widget get_field_name() in
	 * WP_Widget::form() function.
	 *
	 * @param int|string $widget_id
	 * @param int|string ...$other_name_keys
	 * @return string
	 *
	 * @psalm-param array-key ...$other_name_keys
	 */
	public static function get_field_name( $widget_id, ...$other_name_keys ) {
		$suffix = '[' . $widget_id . ']';
		foreach ( $other_name_keys as $name_key ) {
			$name_key = sanitize_key( (string) $name_key );
			if ( empty( $name_key ) ) {
				continue;
			}

			$suffix .= '[' . $name_key . ']';
		}

		return 'widget-' . TWRP_Widget::TWRP_BASE_ID . $suffix;
	}

	/**
	 * Get the instance settings array for the widget id.
	 *
	 * @param string|int $widget_id Either the int or the whole widget Id.
	 * @return array Empty array if no settings are present.
	 */
	public static function get_instance_settings( $widget_id ) {
		try {
			$widget_id = self::get_widget_id_number( $widget_id );
		} catch ( RuntimeException $e ) {
			return array();
		}

		$instance_options = get_option( 'widget_' . self::TWRP_WIDGET__BASE_ID );
		if ( isset( $instance_options[ $widget_id ] ) ) {
			return $instance_options[ $widget_id ];
		}

		return array();
	}

	/**
	 * Get only the widget Id number for this type of widget.
	 *
	 * @throws RuntimeException If the widget Id cannot be retrieved.
	 *
	 * @param string|int $widget_id Either the int or the whole widget Id.
	 * @return int
	 */
	public static function get_widget_id_number( $widget_id ) {
		if ( is_numeric( $widget_id ) ) {
			return (int) $widget_id;
		}

		$widget_id_num = ltrim( str_replace( self::TWRP_WIDGET__BASE_ID, '', $widget_id ), '-' );

		if ( is_numeric( $widget_id_num ) ) {
			return (int) $widget_id_num;
		} else {
			throw new RuntimeException( 'Cannot retrieve a number corresponding to a widget Id.' );
		}
	}

	/**
	 * Get a widget Id by passing the instance settings of a widget.
	 *
	 * This function will search in database for the same settings, and get the
	 * id of those who match.
	 *
	 * @param array $instance_settings
	 * @return int
	 */
	public static function get_widget_id_by_instance_settings( $instance_settings ) {
		$all_widgets_settings = get_option( 'widget_' . self::TWRP_WIDGET__BASE_ID );

		foreach ( $all_widgets_settings as $key => $widget_setting ) {
			// Try to speed up by removing those who don't have the same number
			// of keys or same keys.
			if ( count( $widget_setting ) === count( $instance_settings ) && array() === array_diff_key( $widget_setting, $instance_settings ) ) {
				$sorted_widget_settings   = array_multisort( $widget_setting );
				$sorted_instance_settings = array_multisort( $instance_settings );
				if ( $sorted_widget_settings === $sorted_instance_settings ) {
					return (int) $key;
				}
			}
		}

		return 0;
	}

}

<?php

namespace TWRP\Query_Generator\Query_Setting;

use WP_Post_Type;

/**
 * Class that will filter the articles via selected posts types.
 */
class Post_Types extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 20;
	}

	const SELECTED_TYPES__SETTING_NAME = 'selected_post_types';

	public function get_setting_name() {
		return 'post_types';
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::SELECTED_TYPES__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}
		$selected_types = $setting[ self::SELECTED_TYPES__SETTING_NAME ];

		if ( ! is_array( $selected_types ) ) {
			return $this->get_default_setting();
		}

		$available_post_types = self::get_available_types( 'names' );

		$sanitized_post_types = array();
		foreach ( $selected_types as $post_type_name ) {
			if ( in_array( $post_type_name, $available_post_types, true ) ) {
				array_push( $sanitized_post_types, $post_type_name );
			}
		}

		return array( self::SELECTED_TYPES__SETTING_NAME => $sanitized_post_types );
	}

	public function get_default_setting() {
		if ( post_type_exists( 'post' ) ) {
			return array(
				self::SELECTED_TYPES__SETTING_NAME => array( 'post' ),
			);
		}

		return array(
			self::SELECTED_TYPES__SETTING_NAME => array(),
		);
	}

	/**
	 * Get the registered custom post types that are available as options to
	 * query posts from.
	 *
	 * @param string $return_type Can be 'objects', or names, @see get_post_types().
	 *
	 * @return WP_Post_Type[]|string[] Depends on return type parameter.
	 */
	public static function get_available_types( $return_type = 'objects' ) {
		$args = array(
			'public' => true,
		);

		if ( 'names' === $return_type ) {
			return get_post_types( $args, 'names' );
		}

		return get_post_types( $args, 'objects' );
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! empty( $query_settings[ $this->get_setting_name() ][ self::SELECTED_TYPES__SETTING_NAME ] ) ) {
			$post_types = $query_settings[ $this->get_setting_name() ][ self::SELECTED_TYPES__SETTING_NAME ];
		} else {
			return $previous_query_args;
		}

		$previous_query_args['post_type'] = $post_types;
		return $previous_query_args;
	}
}

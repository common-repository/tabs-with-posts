<?php

namespace TWRP\Query_Generator\Query_Setting;

use TWRP\Utils\Simple_Utils;
use WP_Post;

/**
 * Class that will filter the articles via selected post ids.
 * Can include/exclude for posts Ids.
 */
class Post_Settings extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 50;
	}

	const FILTER_TYPE__SETTING_NAME = 'posts_filter';

	const POSTS_INPUT__SETTING_NAME = 'posts_ids';

	public function get_setting_name() {
		return 'post_settings';
	}

	public function get_default_setting() {
		return array(
			self::FILTER_TYPE__SETTING_NAME => 'NA',
			self::POSTS_INPUT__SETTING_NAME => '',
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! isset( $setting[ self::FILTER_TYPE__SETTING_NAME ], $setting[ self::POSTS_INPUT__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}
		$filter_type = $setting[ self::FILTER_TYPE__SETTING_NAME ];
		$posts_ids   = $setting[ self::POSTS_INPUT__SETTING_NAME ];

		$possible_filters = array( 'NA', 'IP', 'EP' );

		if ( ( ! in_array( $filter_type, $possible_filters, true ) ) || 'NA' === $filter_type ) {
			return $this->get_default_setting();
		}

		$sanitized_settings = array( self::FILTER_TYPE__SETTING_NAME => $setting[ self::FILTER_TYPE__SETTING_NAME ] );

		$posts_ids = explode( ';', $posts_ids );
		$posts_ids = Simple_Utils::get_valid_wp_ids( $posts_ids );

		foreach ( $posts_ids as $key => $id ) {
			$post = get_post( (int) $id );
			if ( ! ( $post instanceof WP_Post ) ) {
				unset( $posts_ids[ $key ] );
			}
		}
		$posts_ids = array_values( $posts_ids );

		$sanitized_settings[ self::POSTS_INPUT__SETTING_NAME ] = implode( ';', $posts_ids );

		if ( empty( $sanitized_settings[ self::POSTS_INPUT__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		return $sanitized_settings;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! isset( $query_settings[ $this->get_setting_name() ] ) ) {
			return $previous_query_args;
		}
		$settings = $query_settings[ $this->get_setting_name() ];

		if ( ! isset( $settings[ self::FILTER_TYPE__SETTING_NAME ], $settings[ self::POSTS_INPUT__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}

		$filter_type = $settings[ self::FILTER_TYPE__SETTING_NAME ];
		if ( 'NA' === $filter_type ) {
			return $previous_query_args;
		}

		$posts_ids = explode( ';', $settings[ self::POSTS_INPUT__SETTING_NAME ] );
		$posts_ids = Simple_Utils::get_valid_wp_ids( $posts_ids );

		if ( empty( $posts_ids ) ) {
			return $previous_query_args;
		}

		$posts_query_attr = '';
		if ( 'IP' === $filter_type ) {
			$posts_query_attr = 'post__in';
		} elseif ( 'EP' === $filter_type ) {
			$posts_query_attr = 'post__not_in';
		} else {
			return $previous_query_args;
		}

		$previous_query_args[ $posts_query_attr ] = $posts_ids;

		return $previous_query_args;
	}
}

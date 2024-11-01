<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Creates the possibility to filter a query based on post statuses.
 */
class Post_Status extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 30;
	}

	const APPLY_STATUSES__SETTING_NAME = 'status_type';

	const POST_STATUSES__SETTING_NAME = 'selected_statuses';

	public function get_setting_name() {
		return 'post_status';
	}

	/**
	 * Get the post statuses that an user can choose to make a query.
	 *
	 * @return object[] An array with stdClass post statuses.
	 */
	public static function get_post_statuses() {
		$args = array(
			'public' => true,
		);
		return get_post_stati( $args, 'objects', 'or' );
	}

	public function get_default_setting() {
		return array(
			self::APPLY_STATUSES__SETTING_NAME => 'not_applied',
			self::POST_STATUSES__SETTING_NAME  => array(),
		);
	}

	public function sanitize_setting( $setting ) {
		if ( ! is_array( $setting ) ) {
			return $this->get_default_setting();
		}

		if ( ! isset( $setting[ self::APPLY_STATUSES__SETTING_NAME ] ) || 'apply' !== $setting[ self::APPLY_STATUSES__SETTING_NAME ] ) {
			return $this->get_default_setting();
		}

		if ( ! isset( $setting[ self::POST_STATUSES__SETTING_NAME ] ) || ! is_array( $setting[ self::POST_STATUSES__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		$post_statuses       = self::get_post_statuses();
		$post_statuses_names = wp_list_pluck( $post_statuses, 'name' );

		$sanitized_post_statuses = array(
			self::APPLY_STATUSES__SETTING_NAME => $setting[ self::APPLY_STATUSES__SETTING_NAME ],
			self::POST_STATUSES__SETTING_NAME  => array(),
		);
		foreach ( $setting[ self::POST_STATUSES__SETTING_NAME ] as $post_status ) {
			if ( in_array( $post_status, $post_statuses_names, true ) ) {
				array_push( $sanitized_post_statuses[ self::POST_STATUSES__SETTING_NAME ], $post_status );
			}
		}

		if ( empty( $sanitized_post_statuses[ self::POST_STATUSES__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}

		return $sanitized_post_statuses;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( ! isset( $query_settings[ $this->get_setting_name() ][ self::APPLY_STATUSES__SETTING_NAME ] )
			|| 'apply' !== $query_settings[ $this->get_setting_name() ][ self::APPLY_STATUSES__SETTING_NAME ] ) {
				return $previous_query_args;
		}

		if ( empty( $query_settings[ $this->get_setting_name() ][ self::POST_STATUSES__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}

		$previous_query_args['post_status'] = $query_settings[ $this->get_setting_name() ][ self::POST_STATUSES__SETTING_NAME ];
		$previous_query_args['perm']        = 'readable';

		return $previous_query_args;
	}
}

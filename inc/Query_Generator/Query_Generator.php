<?php

namespace TWRP\Query_Generator;

use TWRP\Database\Query_Options;

use TWRP\Utils\Class_Retriever_Utils;

use WP_Query;
use WP_Post;
use RuntimeException;

/**
 * Collection of static methods that are used to retrieve posts by a query id,
 * or to construct WP_Query arguments for a query_id.
 */
class Query_Generator {

	/**
	 * Get the WordPress posts for a specific defined query.
	 *
	 * @throws RuntimeException If the Query ID does not exist.
	 *
	 * @param int|string $query_id The Query ID to get posts from.
	 * @param array $additional_args Additional arguments to be merged.
	 * @return WP_Post[] The WordPress Posts.
	 */
	public static function get_posts_by_query_id( $query_id, $additional_args = array() ) {
		try {
			$query_args = self::get_wp_query_arguments( $query_id );
		} catch ( RuntimeException $exception ) {
			throw $exception;
		}
		$wp_query   = new WP_Query();
		$query_args = array_merge( $query_args, $additional_args );

		$query_args = apply_filters( 'twrp_before_getting_query', $query_args, $query_id );
		$posts      = $wp_query->query( $query_args );
		$posts      = apply_filters( 'twrp_after_getting_posts_filter', $posts );

		return $posts;
	}

	/**
	 * Construct the WP Query Arguments for a registered query, based on the
	 * setting classes registered.
	 *
	 * @see \TWRP\Query_Settings_Manager On how to add a setting class.
	 * @throws RuntimeException If the Query ID does not exist, or something went wrong.
	 *
	 * @param int|string $query_id The Id to construct query for.
	 * @param bool $suppress_twrp_filters Whether or not to suppress twrp filters.
	 * @return array
	 */
	public static function get_wp_query_arguments( $query_id, $suppress_twrp_filters = false ) {
		$registered_settings_classes = Class_Retriever_Utils::get_all_query_settings_objects();
		$query_args                  = self::get_starting_query_args();

		try {
			$query_options = Query_Options::get_all_query_settings( $query_id );
		} catch ( RuntimeException $exception ) {
			throw $exception;
		}

		if ( ! $suppress_twrp_filters ) {
			$query_args = apply_filters( 'twrp_starting_query_arguments', $query_args, $query_id, $query_options );
		}

		foreach ( $registered_settings_classes as $setting_class_to_apply ) {
			$query_args = $setting_class_to_apply->add_query_arg( $query_args, $query_options );
		}

		if ( ! $suppress_twrp_filters ) {
			return apply_filters( 'twrp_get_query_arguments_created', $query_args, $query_id, $query_options );
		} else {
			return $query_args;
		}

	}

	/**
	 * Get the WP Query arguments before even any other setting is applied.
	 *
	 * @return array
	 */
	protected static function get_starting_query_args() {
		return array(
			// Like in get_posts(), we set to get only published posts. By
			// default WP adds private posts if user is logged in.
			'post_status'   => 'publish',
			'no_found_rows' => true,
		);
	}

}

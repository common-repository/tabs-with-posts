<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Plugins\Post_Views;

/**
 * Adapter type of class that will manage and call the functions for the views
 * plugin written by GaMerZ.
 *
 * Internally this plugin use the post meta to store the post views values.
 *
 * The last tested version of this plugin is 1.66, but we assume 1.0.0 since
 * 1.66 version is from 2013, and is very old.
 */
class GamerZ_Views_Plugin extends Post_Views_Plugin {

	public static function get_class_order_among_siblings() {
		return 20;
	}

	#region -- Plugin Meta

	public function get_plugin_title() {
		return 'WP-PostViews';
	}

	public function get_plugin_author() {
		return "Lester 'GaMerZ' Chan";
	}

	public function get_tested_plugin_versions() {
		return '1.0.0 - 1.76.1';
	}

	public function get_plugin_file_relative_path() {
		return array(
			'wp-postviews-premium/wp-postviews.php',
			'wp-postviews-premium/wp-postviews-premium.php',
			'wp-postviews/wp-postviews.php',
		);
	}

	#endregion -- Plugin Meta

	#region -- Detect if is installed.

	public function is_installed_and_can_be_used() {
		if ( ! function_exists( 'the_views' ) ) {
			return false;
		}

		if ( ! function_exists( 'get_totalviews' ) ) {
			return false;
		}

		return true;
	}

	#endregion -- Detect if is installed.

	#region -- Get the views.

	public function get_views( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return false;
		}
		$post_id = (int) $post_id;

		if ( ! $this->is_installed_and_can_be_used() ) {
			return false;
		}

		$post_views = get_post_meta( $post_id, 'views', true );

		if ( is_numeric( $post_views ) && $post_views >= 0 ) {
			return (int) $post_views;
		}

		return 0;
	}

	#endregion -- Get the views.

	#region -- Modify the query argument to order posts.

	public function modify_query_arg_if_necessary( $query_args ) {
		$orderby_value = Post_Views::ORDERBY_VIEWS_OPTION_KEY;
		if ( ! isset( $query_args['orderby'][ $orderby_value ] ) ) {
			return $query_args;
		}

		$new_orderby = array();
		foreach ( $query_args['orderby'] as $orderby => $order ) {
			if ( $orderby_value === $orderby ) {
				$new_orderby['meta_value_num'] = $order;
				continue;
			}
			$new_orderby[ $orderby ] = $order;
		}

		$query_args['orderby']  = $new_orderby;
		$query_args['meta_key'] = 'views'; // phpcs:ignore -- using meta_key is making query slow.

		return $query_args;
	}

	#endregion -- Modify the query argument to order posts.

}

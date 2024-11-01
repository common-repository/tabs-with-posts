<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Plugins\Post_Views;

/**
 * Adapter type of class that will manage and call the functions for the views
 * plugin written by DFactory.
 *
 * Internally, this plugin use the table "wp_post_views" to store the views.
 */
class DFactory_Views_Plugin extends Post_Views_Plugin {

	public static function get_class_order_among_siblings() {
		return 10;
	}

	const ORDERBY_NAME = 'post_views';

	#region -- Plugin Meta

	public function get_plugin_title() {
		return 'Post Views Counter';
	}

	public function get_plugin_author() {
		return 'Digital Factory';
	}

	public function get_tested_plugin_versions() {
		return '1.0.0 - 1.3.3';
	}

	public function get_plugin_file_relative_path() {
		return array(
			'post-views-counter-premium/post-views-counter.php',
			'post-views-counter-premium/post-views-counter-premium.php',
			'post-views-counter/post-views-counter.php',
		);
	}

	#endregion -- Plugin Meta

	#region -- Detect if is installed.

	public function is_installed_and_can_be_used() {
		return function_exists( 'pvc_get_post_views' );
	}

	#endregion -- Detect if is installed.

	#region -- Get the views.

	public function get_views( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return false;
		}
		$post_id = (int) $post_id;

		$post_views = pvc_get_post_views( $post_id );

		if ( is_numeric( $post_views ) && $post_views >= 0 ) {
			return (int) $post_views;
		}

		return 0;
	}

	#endregion -- Get the views.

	#region -- Modify the query argument to order posts.

	/**
	 * Given an array with WP_Query args with 'orderby' of type array and a
	 * custom orderby key. Return the new WP_Query args that will have the
	 * parameters modified, to retrieve the posts in order of the views.
	 *
	 * @param array $query_args The normal WP_Query args, only that a new key
	 * will appear as a key in 'orderby' parameter.
	 * @return array
	 */
	public function modify_query_arg_if_necessary( $query_args ) {
		$orderby_value = Post_Views::ORDERBY_VIEWS_OPTION_KEY;
		if ( ! isset( $query_args['orderby'][ $orderby_value ] ) ) {
			return $query_args;
		}

		$query_args['order']            = $query_args['orderby'][ $orderby_value ];
		$query_args['orderby']          = 'post_views';
		$query_args['suppress_filters'] = false;

		return $query_args;
	}

	#endregion -- Modify the query argument to order posts.

}

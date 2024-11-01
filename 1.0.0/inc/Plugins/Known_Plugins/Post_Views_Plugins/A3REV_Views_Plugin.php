<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Plugins\Post_Views;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Simple_Utils;
use WP_Query;

/**
 * Adapter type of class that will manage and call the functions for the views
 * plugin written by A3REV.
 *
 * By default this plugin does not implement a way to query posts by order of
 * views, so a way to order them was added, via WP filters.
 */
class A3REV_Views_Plugin extends Post_Views_Plugin {

	public static function get_class_order_among_siblings() {
		return 30;
	}

	const QUERY_ADDITIONAL_FILTER_KEY = 'twrp_a3rev_views_order';

	#region -- Plugin Meta

	public function get_plugin_title() {
		return 'Page View Count';
	}

	public function get_plugin_author() {
		return 'a3rev Software';
	}

	public function get_tested_plugin_versions() {
		return '1.1.0 - 2.4.5';
	}

	public function get_plugin_file_relative_path() {
		return array(
			'page-views-count-premium/page-views-count.php',
			'page-views-count-premium/page-views-count-premium.php',
			'page-views-count/page-views-count.php',
		);
	}

	#endregion -- Plugin Meta

	#region -- Detect if is installed.

	public function is_installed_and_can_be_used() {
		$early_version_method_exist = Simple_Utils::method_exist_and_is_public( 'A3_PVC', 'pvc_fetch_post_total' );
		$method_exist               = Simple_Utils::method_exist_and_is_public( 'A3Rev\\PageViewsCount\\A3_PVC', 'pvc_fetch_post_total' );

		return $method_exist || $early_version_method_exist;
	}

	#endregion -- Detect if is installed.

	#region -- Get the views.

	public function get_views( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return 0;
		}
		$post_id = (int) $post_id;

		if ( class_exists( 'A3Rev\\PageViewsCount\\A3_PVC' ) ) {
			$post_views = \A3Rev\PageViewsCount\A3_PVC::pvc_fetch_post_total( $post_id );
		} elseif ( class_exists( 'A3_PVC' ) ) {
			$post_views = \A3_PVC::pvc_fetch_post_total( $post_id );
		} else {
			return 0;
		}

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

		$query_args[ self::QUERY_ADDITIONAL_FILTER_KEY ] = true;
		$query_args['suppress_filters']                  = false;

		return $query_args;
	}

	#endregion -- Modify the query argument to order posts.

	#region -- Creating custom filters for ordering posts.

	use After_Setup_Theme_Init_Trait;

	public static function after_setup_theme_init() {
		add_filter(
			'twrp_before_getting_query',
			/**
			 * Added some filters that can order posts by this plugin views.
			 *
			 * @param array $query_args
			 * @return array
			*/
			function( $query_args ) {
				add_filter( 'posts_join', array( self::class, 'add_posts_query_join_table' ), 10, 2 );
				add_filter( 'posts_orderby', array( self::class, 'add_query_posts_orderby_views' ), 10, 2 );
				return $query_args;
			}
		);

		add_filter(
			'twrp_after_getting_posts_filter',
			/**
			 * Remove the filters installed.
			 *
			 * @param array $query_args
			 * @return array
			*/
			function( $query_args ) {
				remove_filter( 'posts_join', array( self::class, 'add_posts_query_join_table' ) );
				remove_filter( 'posts_orderby', array( self::class, 'add_query_posts_orderby_views' ) );
				return $query_args;
			}
		);
	}

	/**
	 * Filter that joins 2 sql tables if needed to order by this plugin views.
	 *
	 * @param string $join
	 * @param WP_Query $wp_query
	 * @return string
	 *
	 * @psalm-suppress DocblockTypeContradiction
	 */
	public static function add_posts_query_join_table( $join, $wp_query ) {
		global $wpdb;

		$query_array = $wp_query->query;
		if ( ! is_array( $query_array ) || ! array_key_exists( self::QUERY_ADDITIONAL_FILTER_KEY, $query_array ) ) {
			return $join;
		}

		$pvc_total_table_name = $wpdb->prefix . 'pvc_total';
		$join                .= "INNER JOIN $pvc_total_table_name ON $wpdb->posts.ID = $pvc_total_table_name.postnum ";

		return $join;
	}

	/**
	 * Filter function that adds to the query how to order by views stored by
	 * this plugin, if necessary.
	 *
	 * @param string $orderby
	 * @param WP_Query $wp_query
	 * @return string
	 *
	 * @psalm-suppress DocblockTypeContradiction
	 */
	public static function add_query_posts_orderby_views( $orderby, $wp_query ) {
		global $wpdb;
		$orderby_value = Post_Views::ORDERBY_VIEWS_OPTION_KEY;

		$query_array = $wp_query->query;
		if ( ! is_array( $query_array ) || ! array_key_exists( self::QUERY_ADDITIONAL_FILTER_KEY, $query_array ) ) {
			return $orderby;
		}

		$order = 'DESC';
		if ( isset( $query_array['orderby'], $query_array['orderby'][ $orderby_value ] ) ) {
			$order = $query_array['orderby'][ $orderby_value ];
		}

		$pvc_total_table_name = $wpdb->prefix . 'pvc_total';

		if ( ! empty( $orderby ) ) {
			$orderby = ', ' . $orderby;
		}
		$orderby = $pvc_total_table_name . '.postcount ' . $order . $orderby;

		return $orderby;
	}

	#endregion -- Creating custom filters for ordering posts.

}

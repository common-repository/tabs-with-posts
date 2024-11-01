<?php

namespace TWRP\Plugins;

use TWRP\Plugins\Known_Plugins\Post_Rating_Plugin;

use TWRP\Utils\Class_Retriever_Utils;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Simple_Utils;

use WP_Post;

/**
 * Class that is used to get the rating of a post.
 *
 * This class can recognize if a known plugin that show rating is installed, and
 * use that plugin to get the rating.
 *
 * The plugin used will be the first encountered installed plugin, in a specific
 * order of importance or preference.
 */
class Post_Rating {

	const ORDERBY_RATING_OPTION_KEY = 'twrp_post_rating_order';

	const ORDERBY_RATING_COUNT_OPTION_KEY = 'twrp_post_rating_count_order';

	use After_Setup_Theme_Init_Trait;

	/**
	 * Holds an array with an object of each plugin class.
	 *
	 * @var array<string,Post_Rating_Plugin>
	 */
	protected static $plugin_classes = array();

	/**
	 * WP rating plugin class to use. False means no plugin is installed, null
	 * is the initial value, and the object is the object to use.
	 *
	 * @var Post_Rating_Plugin|false|null
	 */
	protected static $used_plugin_class = null;

	/**
	 * Get the class of the plugin to use.
	 *
	 * @return false|Post_Rating_Plugin False if no rating plugin is installed,
	 * so no class can be used, or the corresponding object to use.
	 */
	public static function get_plugin_to_use() {
		$plugins = self::get_plugin_classes();

		if ( null !== self::$used_plugin_class ) {
			return self::$used_plugin_class; // @phan-suppress-current-line PhanPossiblyNullTypeReturn
		}

		foreach ( $plugins as $plugin_class ) {
			if ( Simple_Utils::method_exist_and_is_public( $plugin_class, 'is_installed_and_can_be_used' ) && $plugin_class->is_installed_and_can_be_used() === true ) {
				self::$used_plugin_class = $plugin_class;
				return self::$used_plugin_class; // @phan-suppress-current-line PhanPossiblyNullTypeReturn
			}
		}

		self::$used_plugin_class = false;
		return false;
	}

	/**
	 * Returns an array with each plugin classes, in the usage preference order.
	 *
	 * @return array<string,Post_Rating_Plugin>
	 */
	public static function get_plugin_classes() {
		if ( ! empty( self::$plugin_classes ) ) {
			return self::$plugin_classes;
		}

		self::$plugin_classes = Class_Retriever_Utils::get_all_post_ratings_plugins_objects();
		return self::$plugin_classes;
	}

	/**
	 * Get the average rating for a post. Return false if the rating cannot be
	 * retrieved, like the plugin is not installed, post don't exist, or
	 * another error.
	 *
	 * @param WP_Post|int|null $post The post to use. Defaults to global $post.
	 * @return float|int|false
	 */
	public static function get_rating( $post = null ) {
		$post = get_post( $post );
		if ( ! ( $post instanceof WP_Post ) ) {
			return false;
		}

		$post_id      = $post->ID;
		$plugin_class = self::get_plugin_to_use();

		if ( ! $plugin_class ) {
			return false;
		}

		$post_rating = $plugin_class->get_rating( $post_id );
		return $post_rating;
	}

	/**
	 * Get how many people have give a review for this post.
	 *
	 * @param WP_Post|int|null $post The post to use. Defaults to global $post.
	 * @return int|false
	 */
	public static function get_rating_count( $post = null ) {
		$post = get_post( $post );
		if ( ! ( $post instanceof WP_Post ) ) {
			return false;
		}

		$post_id      = $post->ID;
		$plugin_class = self::get_plugin_to_use();

		if ( ! $plugin_class ) {
			return false;
		}

		$post_rating_num = $plugin_class->get_rating_count( $post_id );
		return $post_rating_num;
	}

	/**
	 * Add filters/actions necessary for this class to work. See
	 * After_Setup_Theme_Init_Trait for more info.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		add_filter( 'twrp_post_first_orderby_select_options', array( self::class, 'add_orderby_rating_option' ) );
		add_filter( 'twrp_get_query_arguments_created', array( self::class, 'modify_query_arg_if_necessary' ), 3 );
	}

	/**
	 * Given an array with WP_Query args return the new WP_Query args that will
	 * have the parameters added to order by the rating plugin selected.
	 *
	 * @param array $query_args The normal WP_Query args, only that
	 * ORDERBY_RATING_OPTION_KEY appears as a key in 'orderby' parameter.
	 * @return array
	 */
	public static function modify_query_arg_if_necessary( $query_args ) {
		$plugin_class = self::get_plugin_to_use();
		if ( false === $plugin_class ) {
			return $query_args;
		}

		$query_args = $plugin_class->modify_query_arg_if_necessary( $query_args );
		return $query_args;
	}

	/**
	 * Function used to register the rating as an orderby option in the query.
	 *
	 * @param array $orderby_options
	 * @return array
	 */
	public static function add_orderby_rating_option( $orderby_options ) {
		$plugin_installed = ( self::get_plugin_to_use() === false ? false : true );

		$not_installed_message = '';
		if ( ! $plugin_installed ) {
			$not_installed_message = _x( 'Plugin not installed', 'backend', 'tabs-with-posts' );
		}

		$text = _x( 'Order by rating', 'backend', 'tabs-with-posts' );
		if ( ! empty( $not_installed_message ) ) {
			$text = $text . '(' . $not_installed_message . ')';
		}

		$orderby_options[ self::ORDERBY_RATING_OPTION_KEY ] = $text;

		$text = _x( 'Order by rating count', 'backend', 'tabs-with-posts' );
		if ( ! empty( $not_installed_message ) ) {
			$text = $text . '(' . $not_installed_message . ')';
		}
		$orderby_options[ self::ORDERBY_RATING_COUNT_OPTION_KEY ] = $text;
		return $orderby_options;
	}

	/**
	 * Check if query settings depends on one of the views plugins to be
	 * installed.
	 *
	 * @param array $query_args
	 * @return bool
	 */
	public static function query_settings_depend_on_plugin( $query_args ) {
		if ( isset( $query_args['orderby'] ) && is_array( $query_args['orderby'] ) && isset( $query_args['orderby'][ self::ORDERBY_RATING_OPTION_KEY ] ) ) {
			return true;
		}

		if ( isset( $query_args['orderby'] ) && is_array( $query_args['orderby'] ) && isset( $query_args['orderby'][ self::ORDERBY_RATING_COUNT_OPTION_KEY ] ) ) {
			return true;
		}

		return false;
	}

}

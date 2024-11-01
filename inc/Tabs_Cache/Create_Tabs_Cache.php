<?php

namespace TWRP\Tabs_Cache;

use TWRP\Database\General_Options;
use TWRP\Database\Tabs_Cache_Table;

use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Widget_Utils;

/**
 * Class used to manage when to create the cache for the tabs, and fire the request.
 */
class Create_Tabs_Cache {

	use After_Setup_Theme_Init_Trait;

	/**
	 * Holds the Background Process class.
	 *
	 * @var Tabs_Cache_Async_Request
	 */
	protected static $async_request;

	/**
	 * Whether or not the async request have been displaced.
	 *
	 * @var bool
	 */
	protected static $async_request_dispatched = false;

	/**
	 * Function called at 'after_setup_theme' action.
	 *
	 * For more information see trait After_Setup_Theme_Init_Trait.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		// If cache is not enable, then return.
		if ( 'false' === General_Options::get_option( General_Options::ENABLE_CACHE ) ) {
			return;
		}

		// We need to initialize this class here, because after it's too late, and won't work.
		self::$async_request = new Tabs_Cache_Async_Request();

		// Cache when a post is added/updated/deleted.
		add_action( 'save_post', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'post_updated', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'after_delete_post', array( static::class, 'cache_all_widgets_and_tabs' ) );

		// When a term is updated/added/deleted.
		add_action( 'create_term', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'edited_terms', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'delete_term', array( static::class, 'cache_all_widgets_and_tabs' ) );

		// When the widget is updated/added/deleted.
		add_action( 'update_option_widget_twrp_tabs_with_recommended_posts', array( static::class, 'cache_all_widgets_and_tabs' ) );

		// When the settings are updated.
		add_action( 'twrp_general_after_settings_submitted', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'twrp_after_tab_query_deleted', array( static::class, 'cache_all_widgets_and_tabs' ) );
		add_action( 'twrp_after_update_tab_queries', array( static::class, 'cache_all_widgets_and_tabs' ) );

		// When a plugin is activated/deactivated.
		add_action( 'activated_plugin', array( static::class, 'any_plugin_activation_or_deactivation_refresh_cache' ) );
		add_action( 'deactivated_plugin', array( static::class, 'any_plugin_activation_or_deactivation_refresh_cache' ) );

		// When a plugin updated, or WordPress itself updated.
		add_action( 'upgrader_process_complete', array( static::class, 'cache_all_widgets_and_tabs' ) );

		// Every n minutes.
		if ( self::check_if_minimum_timestamp_to_refresh_has_passed() ) {
			Tabs_Cache_Table::refresh_cache_timestamp();
			self::cache_all_widgets_and_tabs();
		};

		// Refresh at ajax call.
		add_action( 'wp_ajax_twrp_refresh_widget_cache', array( static::class, 'ajax_refresh_cache' ) );
	}

	/**
	 * Refresh cache when this plugin is activated.
	 *
	 * @return void
	 */
	public static function plugin_activated_refresh_cache() {
		// We can't create cache here because is too late, but anyway delete the
		// cache timestamp to refresh next time a page load.
		Tabs_Cache_Table::delete_cache_timestamp();
	}

	/**
	 * Fire an async request that caches all widgets tabs.
	 *
	 * @return void
	 */
	public static function cache_all_widgets_and_tabs() {
		if ( self::$async_request_dispatched || self::are_two_batches_dispatched_already() ) {
			return;
		}

		$widgets = get_option( 'widget_' . Widget_Utils::TWRP_WIDGET__BASE_ID, array() );

		if ( ! is_array( $widgets ) ) {
			return;
		}

		$request     = self::$async_request;
		$item_pushed = false;

		$widget_ids = array();
		$items      = array();
		foreach ( $widgets as $id => $widget_instance_settings ) {
			if ( ! is_numeric( $id ) || ! is_array( $widget_instance_settings ) ) {
				continue;
			}
			array_push( $widget_ids, $id );

			$item = array( 'widget_id' => $id );

			array_push( $items, $item );
		}

		// Create the delete item and push it to the task.
		if ( ! empty( $widget_ids ) ) {
			$item = array( 'delete_all_except_widget_ids' => $widget_ids );
			$request->push_to_queue( $item );
			$item_pushed = true;
		}

		// Push cache items to the task.
		foreach ( $items as $item ) {
			$request->push_to_queue( $item );
		}

		// Dispatch the background process.
		if ( $item_pushed ) {
			$request                        = $request->save();
			self::$async_request_dispatched = true;
			$request->dispatch();
		}
	}

	/**
	 * Check if the widgets global cache needs to be refreshed at a specific
	 * interval of time.
	 *
	 * @return bool
	 */
	public static function check_if_minimum_timestamp_to_refresh_has_passed() {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['doing_wp_cron'] ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['action'] ) && 'twrp_tabs_cache_request' === $_GET['action'] ) {
			return false;
		}

		$minutes = General_Options::get_option( General_Options::CACHE_AUTOMATIC_REFRESH );

		if ( ! is_numeric( $minutes ) ) {
			return false;
		}
		$minutes = (int) $minutes;

		if ( $minutes < 1 ) {
			return false;
		}

		$current_timestamp   = time();
		$timestamp_refreshed = (int) Tabs_Cache_Table::get_cache_refreshed_timestamp();

		if ( $current_timestamp > ( $timestamp_refreshed + ( $minutes * 60 ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if there are already two batches dispatched, to not dispatch more
	 * that is necessary.
	 *
	 * This function have a somewhat performance impact because it queries db.
	 *
	 * @return bool
	 */
	public static function are_two_batches_dispatched_already() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'twrp_tabs_cache_request_batch%' LIMIT 2", \ARRAY_A );

		if ( count( $plugin_options ) > 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Refresh the cache when any plugin(except this plugin itself) is
	 * activated or deactivated.
	 *
	 * This does not count silent activated, like when updating.
	 *
	 * @param string $plugin
	 * @return void
	 */
	public static function any_plugin_activation_or_deactivation_refresh_cache( $plugin ) {
		if ( strpos( $plugin, 'tabs-with-posts' ) === false ) {
			self::cache_all_widgets_and_tabs();
		}
	}

	/**
	 * Refresh the widget cache via an ajax call.
	 *
	 * @return void
	 */
	public static function ajax_refresh_cache() {
		if ( ! isset( $_POST['nonce'] ) ) {
			die();
		}

		$nonce = wp_unslash( (string) $_POST['nonce'] ); // phpcs:ignore WordPress.Security
		if ( ! is_string( $nonce ) || ! wp_verify_nonce( $nonce, 'twrp_refresh_widget_cache_nonce' ) ) {
			die();
		}

		self::cache_all_widgets_and_tabs();

		die();
	}
}

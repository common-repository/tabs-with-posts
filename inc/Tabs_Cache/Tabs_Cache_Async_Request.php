<?php

namespace TWRP\Tabs_Cache;

use TWRP\Database\Tabs_Cache_Table;
use TWRP\Tabs_Creator\Tabs_Creator;

use TWRP\Utils\WP_Background_Process;

use RuntimeException;
use TWRP\Utils\Widget_Utils;

/**
 * This class manages how the async request that caches tabs works.
 */
class Tabs_Cache_Async_Request extends WP_Background_Process {

	/**
	 * The prefix of the action.
	 *
	 * @var string
	 */
	protected $prefix = 'twrp';

	/**
	 * TThe action name.
	 *
	 * @var string
	 */
	protected $action = 'tabs_cache_request';

	protected function task( $item ) {
		if ( isset( $item['delete_all_except_widget_ids'] ) ) {
			if ( ! is_array( $item['delete_all_except_widget_ids'] ) ) {
				return false;
			}

			Tabs_Cache_Table::delete_widgets_cache_not_in( $item['delete_all_except_widget_ids'] );
		}

		if ( ! isset( $item['widget_id'] ) ) {
			return false;
		}

		$widget_id = $item['widget_id'];

		if ( ! is_numeric( $widget_id ) ) {
			return false;
		}

		$instance_settings = Widget_Utils::get_instance_settings( (string) $widget_id );

		if ( empty( $instance_settings ) ) {
			return false;
		}

		$this->create_widget_cache( (int) $widget_id, $instance_settings );
		return false;
	}

	/**
	 * Given an widget id and it's instance settings, create the cache.
	 *
	 * @param int $widget_id
	 * @param array $instance_settings
	 * @return void
	 */
	protected function create_widget_cache( $widget_id, $instance_settings ) {
		try {
			$tabs_creator = new Tabs_Creator( $widget_id, $instance_settings );
		} catch ( RuntimeException $e ) {
			return;
		}

		$table_cache = new Tabs_Cache_Table( $widget_id );

		// Cache inline style.
		$inline_style = $tabs_creator->create_widget_inline_css();
		$table_cache->update_widget_html( 'style', $inline_style );

		// Cache query ids.
		$query_ids = $tabs_creator->get_query_ids();
		foreach ( $query_ids as $query_id ) {
			$tab_content_html = $tabs_creator->create_tab_articles( $query_id );
			$table_cache->update_widget_html( $query_id, $tab_content_html );
		}
	}

	protected function complete() {
		parent::complete();

		Tabs_Cache_Table::refresh_cache_timestamp();
	}
}

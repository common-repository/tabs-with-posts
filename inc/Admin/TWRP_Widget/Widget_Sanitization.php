<?php

namespace TWRP\Admin\TWRP_Widget;

use TWRP\TWRP_Widget;
use TWRP\Article_Block\Article_Block;

use TWRP\Database\Query_Options;
use TWRP\Utils\Simple_Utils;

use TWRP\Admin\Widget_Control\Checkbox_Control;
use TWRP\Admin\Widget_Control\Number_Control;
use TWRP\Admin\Widget_Control\Select_Control;
use TWRP\Admin\TWRP_Widget\Widget_Form;

use RuntimeException;

/**
 * Class used to sanitize the form inputs of the TWRP widget when is updated in
 * the admin area.
 */
class Widget_Sanitization {

	/**
	 * The widget Id number.
	 *
	 * @var int
	 */
	protected $widget_id;

	/**
	 * The new instance of settings, that needs to be sanitized.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * The old instance of settings.
	 *
	 * @var array
	 */
	protected $old_settings;

	/**
	 * Construct a new object.
	 *
	 * @param int $widget_id
	 * @param array $settings The settings to be sanitized.
	 * @param array $old_settings
	 */
	public function __construct( $widget_id, $settings, $old_settings ) {
		$this->widget_id    = $widget_id;
		$this->old_settings = $old_settings;
		$this->settings     = $settings;
	}

	/**
	 * Return an array with the new settings sanitized.
	 *
	 * @return array
	 */
	public function get_the_sanitized_settings() {
		$sanitized_non_query_settings = $this->sanitize_all_non_queries_widget_settings();
		$sanitized_query_settings     = $this->sanitize_all_queries_settings();

		return $sanitized_non_query_settings + $sanitized_query_settings;
	}

	/**
	 * Sanitize all the widgets settings that do not belong to queries.
	 *
	 * @return array
	 */
	protected function sanitize_all_non_queries_widget_settings() {
		$sanitized_settings = array();

		// Sanitize the number of posts.
		$current_setting = null;
		if ( isset( $this->settings [ TWRP_Widget::NUMBER_OF_POSTS__NAME ] ) ) {
			$current_setting = $this->settings [ TWRP_Widget::NUMBER_OF_POSTS__NAME ];
		}
		$sanitized_settings[ TWRP_Widget::NUMBER_OF_POSTS__NAME ] = Number_Control::sanitize_setting( $current_setting, Widget_Form::get_number_of_posts_args() );

		// Sanitize the number of posts per page.
		$current_setting = null;
		if ( isset( $this->settings [ TWRP_Widget::NUMBER_OF_POSTS_PER_PAGE__NAME ] ) ) {
			$current_setting = $this->settings [ TWRP_Widget::NUMBER_OF_POSTS_PER_PAGE__NAME ];
		}
		$sanitized_settings[ TWRP_Widget::NUMBER_OF_POSTS_PER_PAGE__NAME ] = Number_Control::sanitize_setting( $current_setting, Widget_Form::get_number_of_posts_per_page_args() );

		// Sanitize widget padding option.
		$current_setting = null;
		if ( isset( $this->settings [ TWRP_Widget::HORIZONTAL_PADDING_SETTING__NAME ] ) ) {
			$current_setting = $this->settings [ TWRP_Widget::HORIZONTAL_PADDING_SETTING__NAME ];
		}
		$sanitized_settings[ TWRP_Widget::HORIZONTAL_PADDING_SETTING__NAME ] = Checkbox_Control::sanitize_setting( $current_setting, Widget_Form::get_horizontal_padding_control_args() );

		// Sanitize sync queries option.
		$current_setting = null;
		if ( isset( $this->settings [ TWRP_Widget::SYNC_QUERY_SETTINGS__NAME ] ) ) {
			$current_setting = $this->settings [ TWRP_Widget::SYNC_QUERY_SETTINGS__NAME ];
		}
		$sanitized_settings[ TWRP_Widget::SYNC_QUERY_SETTINGS__NAME ] = Checkbox_Control::sanitize_setting( $current_setting, Widget_Form::get_query_sync_control_args() );

		// Sanitize tab style.
		$current_setting = null;
		if ( isset( $this->settings [ TWRP_Widget::TAB_STYLE_AND_VARIANT__NAME ] ) ) {
			$current_setting = $this->settings [ TWRP_Widget::TAB_STYLE_AND_VARIANT__NAME ];
		}
		$sanitized_settings[ TWRP_Widget::TAB_STYLE_AND_VARIANT__NAME ] = Select_Control::sanitize_setting( $current_setting, Widget_Form::get_tab_style_control_args() );

		return $sanitized_settings;
	}

	/**
	 * Sanitize all the widgets settings that belong to queries, including the
	 * setting that holds all the queries.
	 *
	 * @return array
	 */
	public function sanitize_all_queries_settings() {
		if ( ! isset( $this->settings['queries'] ) ) {
			$this->settings['queries'] = '';
		}
		$queries = explode( ';', $this->settings['queries'] );
		$queries = Simple_Utils::get_valid_wp_ids( $queries );

		$valid_queries_ids  = array();
		$sanitized_settings = array();

		foreach ( $queries as $query_id ) {
			if ( Query_Options::query_exists( $query_id ) ) {
				// @phan-suppress-next-line PhanTypeMismatchDimFetch
				if ( ! isset( $this->settings[ $query_id ] ) || ! is_array( $this->settings[ $query_id ] ) ) {
					$this->settings[ $query_id ] = array();
				}

				// @phan-suppress-next-line PhanPartialTypeMismatchArgument
				$sanitized_settings[ $query_id ] = $this->sanitize_query_settings( $query_id, $this->settings[ $query_id ] );
				array_push( $valid_queries_ids, $query_id );
			}
		}

		$sanitized_settings['queries'] = implode( ';', $valid_queries_ids );

		return $sanitized_settings;
	}

	/**
	 * Sanitize all the widgets settings that belong to queries, including the
	 * setting that holds all the queries.
	 *
	 * @param int $query_id
	 * @param array $query_settings Only the query settings to sanitize.
	 * @return array
	 */
	protected function sanitize_query_settings( $query_id, $query_settings ) {
		$sanitized_settings = array();

		if ( isset( $query_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] ) ) {
			if ( Article_Block::article_block_id_exist( $query_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] ) ) {
				$sanitized_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] = $query_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ];
			} else {
				$sanitized_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] = TWRP_Widget::DEFAULT_SELECTED_ARTBLOCK_ID;
			}
		} else {
			$sanitized_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ] = TWRP_Widget::DEFAULT_SELECTED_ARTBLOCK_ID;
		}

		if ( ! isset( $query_settings[ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ] ) ) {
			$sanitized_settings[ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ] = '';
		} else {
			$sanitized_settings[ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ] = $query_settings[ TWRP_Widget::QUERY_BUTTON_TITLE__NAME ];
		}

		try {
			$artblock = Article_Block::construct_class_by_name_or_id( $sanitized_settings[ TWRP_Widget::ARTBLOCK_SELECTOR__NAME ], $this->widget_id, $query_id, $query_settings );
		} catch ( RuntimeException $e ) {
			return $sanitized_settings;
		}

		$sanitized_article_block_setting = $artblock->sanitize_widget_settings();

		return $sanitized_settings + $sanitized_article_block_setting;
	}

}

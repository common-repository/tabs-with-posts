<?php

namespace TWRP;

use TWRP\Tabs_Creator\Tabs_Creator;
use TWRP\Admin\TWRP_Widget\Widget_Form;
use TWRP\Admin\TWRP_Widget\Widget_Sanitization;

use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Widget_Utils;

use RuntimeException;
use WP_Widget;

/**
 * The main widget of this plugin, named "Tabs with recommended posts".
 */
class TWRP_Widget extends WP_Widget {

	const TWRP_BASE_ID = 'twrp_tabs_with_recommended_posts';

	const TAB_STYLE_AND_VARIANT__NAME = 'tab_style_and_variant';

	const NUMBER_OF_POSTS__NAME = 'number_of_posts';

	const NUMBER_OF_POSTS_PER_PAGE__NAME = 'number_of_posts_per_page';

	const DEFAULT_POSTS_PER_PAGE = 6;

	const HORIZONTAL_PADDING_SETTING__NAME = 'horizontal_padding';

	const SYNC_QUERY_SETTINGS__NAME = 'sync_query_settings';

	const ARTBLOCK_SELECTOR__NAME = 'article_block';

	const QUERY_BUTTON_TITLE__NAME = 'display_title';

	const TAB_QUERIES__NAME = 'queries';

	const DEFAULT_SELECTED_ARTBLOCK_ID = 'simple_style';

	use After_Setup_Theme_Init_Trait;

	public function __construct() {
		$description = _x( 'Tabs with recommended posts. The settings are available at Settings->Tabs With Recommended Posts', 'backend', 'tabs-with-posts' );
		$widget_ops  = array(
			'classname'                   => 'twrp-widget',
			'description'                 => $description,
			'customize_selective_refresh' => true,
		);

		parent::__construct(
			self::TWRP_BASE_ID,
			_x( 'Tabs with Recommended Posts', 'backend', 'tabs-with-posts' ),
			$widget_ops
		);
	}

	/**
	 * Initialize this plugin.
	 *
	 * For more info about this method see After_Setup_Theme_Init_Trait trait.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		add_action(
			'widgets_init',
			function() {
				register_widget( self::class );
			}
		);
	}

	/**
	 * Display the front-end content.
	 *
	 * @param array $args              Display arguments including 'before_title',
	 *                                 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance_settings The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget( $args, $instance_settings ) {
		try {
			$tabs_creator = new Tabs_Creator( (int) $this->number, $instance_settings );
		} catch ( RuntimeException $e ) {
			// If the tabs cannot be created, or there are no tabs, then return.
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security -- No XSS.
		$queries_ids = explode( ';', $instance_settings['queries'] );
		if ( 1 === count( $queries_ids ) ) {
			$button_text = Widget_Utils::pluck_tab_button_title( $instance_settings, (int) $queries_ids[0] );
			echo $args['before_title'] . esc_html( $button_text ) . $args['after_title']; // phpcs:ignore WordPress.Security -- No XSS.
		}
		$tabs_creator->display_tabs();
		echo $args['after_widget']; // phpcs:ignore WordPress.Security -- No XSS.
	}

	/**
	 * Create the widget form settings for an instance.
	 *
	 * @param array $instance_settings
	 * @return ''
	 */
	public function form( $instance_settings ) {
		if ( is_bool( $this->number ) ) {
			return '';
		}

		// Here the number is either int or "__i__". Usually its "__i__".
		// See WP_Widget or Google for more info.
		$widget_form = new Widget_Form( $this->number, $instance_settings );
		$widget_form->display_form();
		return ''; // Because of AJAX.
	}

	public function update( $new_instance, $old_instance ) {
		$widget_id = $this->number;
		if ( ! is_int( $widget_id ) ) {
			$widget_id = Widget_Utils::get_widget_id_by_instance_settings( $old_instance );
		}

		$widget_sanitization = new Widget_Sanitization( $widget_id, $new_instance, $old_instance );
		return $widget_sanitization->get_the_sanitized_settings();
	}

}

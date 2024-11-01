<?php

namespace TWRP\Tabs_Creator;

use TWRP\TWRP_Widget;
use TWRP\Article_Block\Article_Block;
use TWRP\Query_Generator\Query_Generator;
use TWRP\Database\Tabs_Cache_Table;

use TWRP\Tabs_Creator\Tabs_Styles\Tab_Style;

use TWRP\Utils\Widget_Utils;
use TWRP\Utils\Class_Retriever_Utils;

use RuntimeException;
use TWRP\Database\General_Options;
use TWRP\Database\Query_Options;
use TWRP\Utils\Frontend_Translation;
use WP_Post;

/**
 * Construct the tabs widget.
 *
 * The only way to set the settings for the tabs are through the WordPress widget.
 * We can pass a set of custom settings to this constructor, or pass the ones
 * defined in widget.
 */
class Tabs_Creator {

	/**
	 * Holds the widget id of which the tabs must be generated for.
	 *
	 * @var int
	 */
	protected $widget_id = 0;

	/**
	 * Holds the widget instance settings.
	 *
	 * @var array
	 */
	protected $instance_settings = array();

	/**
	 * Holds the Tab_Style object.
	 *
	 * @var Tab_Style
	 */
	protected $tab_style;

	/**
	 * Holds all the query ids of the widget
	 *
	 * @var array
	 */
	protected $query_ids = array();

	/**
	 * For each query id holds the artblock that needs to generate the posts.
	 *
	 * @var array
	 */
	protected $query_artblocks = array();

	/**
	 * For each query id holds the posts to be displayed.
	 *
	 * @var array
	 */
	protected $query_array_of_posts = array();

	/**
	 * Variable used to overwrite the general setting to load via ajax.
	 *
	 * @var bool|null
	 */
	protected $load_via_ajax = null;

	/**
	 * Construct the object based on some widget settings.
	 *
	 * By default WordPress Widget classes are not intuitively very reasonable,
	 * we cannot pass a widget object, because the settings are stored in
	 * database and not in the object itself.
	 *
	 * @throws RuntimeException If widget id does not exist, or the instance
	 * settings might not be correct. Or there are no tabs that can be correctly
	 * displayed.
	 *
	 * @param int $widget_id
	 * @param array $widget_instance_settings Optionally. Will get the default
	 * settings by widget_id.
	 *
	 * @psalm-suppress DocblockTypeContradiction
	 * @psalm-suppress PropertyTypeCoercion
	 */
	public function __construct( $widget_id, $widget_instance_settings = array() ) {
		if ( ! is_int( $widget_id ) ) {
			throw new RuntimeException();
		}

		if ( empty( $widget_instance_settings ) || ! is_array( $widget_instance_settings ) ) {
			$widget_instance_settings = Widget_Utils::get_instance_settings( $widget_id );
		}

		if ( empty( $widget_instance_settings ) ) {
			throw new RuntimeException();
		}

		$this->widget_id         = $widget_id;
		$this->instance_settings = $widget_instance_settings;

		$tab_and_variant = Widget_Utils::pluck_tab_style_and_variant_id( $this->instance_settings );

		$tab_style_id         = $tab_and_variant['tab_style_id'];
		$tab_style_class_name = Class_Retriever_Utils::get_tab_style_class_name_by_id( $tab_style_id );
		$tab_variant          = $tab_and_variant['tab_variant_id'];

		if ( empty( $tab_style_class_name ) ) {
			throw new RuntimeException();
		}

		$this->tab_style = new $tab_style_class_name( $this->widget_id, $this->instance_settings, $tab_variant );

		$this->query_ids = Widget_Utils::pluck_valid_query_ids( $this->instance_settings );
		$this->query_ids = $this->get_filtered_valid_queries_to_display( $this->query_ids );

		foreach ( $this->query_ids as $key => $query_id ) {
			$artblock = $this->get_artblock( $query_id );
			if ( null === $artblock ) {
				unset( $this->query_ids[ $key ] );
			} else {
				$this->query_artblocks[ $query_id ] = $artblock;
			}
		}

		if ( empty( $this->query_ids ) ) {
			throw new RuntimeException();
		}
	}

	/**
	 * Create and display the tabs for the widget.
	 *
	 * @return void
	 */
	public function display_tabs() {
		$loaded_via_ajax    = $this->widget_is_loaded_via_ajax();
		$queries_to_display = $this->query_ids;

		do_action( 'twrp_display_tabs_before_ajax', $this->instance_settings, $this->widget_id, $queries_to_display, $this->tab_style, $this->query_artblocks, $loaded_via_ajax );

		if ( $loaded_via_ajax ) {
			$this->display_widget_to_load_via_ajax();
			return;
		}

		$this->widget_inline_css();
		$tab_style   = $this->tab_style;
		$default_tab = true;

		do_action( 'twrp_before_tabs', $this->instance_settings, $this->widget_id, $queries_to_display, $this->tab_style, $this->query_artblocks );

		$tab_style->start_tabs_wrapper();
		if ( count( $queries_to_display ) > 1 ) {
			$tab_style->start_tab_buttons_wrapper();
			foreach ( $queries_to_display as $query_id ) :
				$button_text = Widget_Utils::pluck_tab_button_title( $this->instance_settings, $query_id );
				$tab_style->tab_button( $button_text, $query_id, $default_tab );
				$default_tab = false;
			endforeach;
			$tab_style->end_tab_buttons_wrapper();
		}

		$tab_style->start_all_tabs_wrapper();
		foreach ( $queries_to_display as $query_id ) :
			$tab_style->start_tab_content_wrapper( $query_id );
			$this->display_query_posts( $query_id );
			$tab_style->end_tab_content_wrapper( $query_id );
		endforeach;
		$tab_style->end_all_tabs_wrapper();
		$tab_style->end_tabs_wrapper();

		do_action( 'twrp_after_tabs', $this->instance_settings, $this->widget_id, $queries_to_display, $this->tab_style, $this->query_artblocks );
	}

	/**
	 * Get an array with each query id.
	 *
	 * @return array
	 */
	public function get_query_ids() {
		return $this->query_ids;
	}

	/**
	 * Display all the posts from a query id.
	 *
	 * @param int $query_id
	 * @return void
	 */
	protected function display_query_posts( $query_id ) {
		$cache_is_enabled = General_Options::get_option( General_Options::ENABLE_CACHE );

		if ( 'false' === $cache_is_enabled ) {
			$this->create_tab_articles( $query_id, true );
			return;
		}

		$tabs_cache = new Tabs_Cache_Table( $this->widget_id );
		$widget     = $tabs_cache->get_widget_html( (string) $query_id );

		if ( ! empty( $widget ) ) {
			echo $widget; // phpcs:ignore
		}
	}

	/**
	 * Echo the inline css for the whole tabs, the css is from table cache..
	 *
	 * @return void
	 */
	protected function widget_inline_css() {
		$cache_is_enabled = General_Options::get_option( General_Options::ENABLE_CACHE );

		if ( 'false' === $cache_is_enabled ) {
			echo $this->create_widget_inline_css(); // phpcs:ignore -- No XSS.
			return;
		}

		$tabs_cache   = new Tabs_Cache_Table( $this->widget_id );
		$inline_style = $tabs_cache->get_widget_html( 'style' );

		if ( ! empty( $inline_style ) ) {
			echo $inline_style; //phpcs:ignore -- No XSS.
		}
	}

	/**
	 * Get the article block class for a query in the widget.
	 *
	 * @param int $query_id
	 * @return Article_Block|null
	 */
	protected function get_artblock( $query_id ) {
		$artblock_id = Widget_Utils::pluck_artblock_id( $this->instance_settings, $query_id );
		if ( empty( $artblock_id ) || ! is_array( $this->instance_settings[ $query_id ] ) ) {
			return null;
		}

		try {
			$artblock = Article_Block::construct_class_by_name_or_id( $artblock_id, $this->widget_id, $query_id, $this->instance_settings[ $query_id ] );
		} catch ( RuntimeException $e ) {
			return null;
		}

		return $artblock;
	}

	/**
	 * Get the setting of how many posts to display per page.
	 *
	 * @return int
	 */
	protected function get_posts_per_page_setting() {
		$posts_per_page = TWRP_Widget::DEFAULT_POSTS_PER_PAGE;
		if ( isset( $this->instance_settings[ TWRP_Widget::NUMBER_OF_POSTS_PER_PAGE__NAME ] ) ) {
			$posts_per_page = (int) $this->instance_settings[ TWRP_Widget::NUMBER_OF_POSTS_PER_PAGE__NAME ];
		}

		return $posts_per_page;
	}

	/**
	 * Create the inline css for a widget.
	 *
	 * @return string
	 */
	public function create_widget_inline_css() {
		$css = '';
		foreach ( $this->query_ids as $query_id ) {
			$artblock = $this->get_artblock( $query_id );
			if ( null === $artblock ) {
				continue;
			}

			$css .= $artblock->get_css();
		}

		if ( ! empty( $css ) ) {
			$css = '<style>' . $css . '</style>';
		}

		return $css;
	}

	/**
	 * Create the article blocks for a query id.
	 *
	 * @param int $query_id
	 * @param bool $echo Whether to echo the html or to return.
	 * @return null|string Return the html or null if echo is true.
	 */
	public function create_tab_articles( $query_id, $echo = false ) {
		if ( ! $echo ) {
			ob_start();
		}

		$number_of_posts = 12;
		if ( isset( $this->instance_settings[ TWRP_Widget::NUMBER_OF_POSTS__NAME ] ) ) {
			$num_posts_aux = $this->instance_settings[ TWRP_Widget::NUMBER_OF_POSTS__NAME ];
			if ( is_numeric( $num_posts_aux ) ) {
				$number_of_posts = (int) $num_posts_aux;
			}
		}

		$additional_args = array(
			'nopaging'       => false,
			'posts_per_page' => $number_of_posts,
			'page'           => 1,
		);

		try {
			$query_posts = Query_Generator::get_posts_by_query_id( $query_id, $additional_args );
		} catch ( RuntimeException $e ) {
			$result = ob_get_contents();
			ob_end_clean();
			if ( $echo ) {
				echo $this->get_display_no_posts_message(); //phpcs:ignore -- No XSS.
			} else {
				return $this->get_display_no_posts_message();
			}
		}

		$artblock       = $this->query_artblocks[ $query_id ];
		$posts_per_page = $this->get_posts_per_page_setting();
		if ( ! empty( $query_posts ) ) {
			$artblock->display_blocks( $query_posts, $posts_per_page );
		} else {
			echo $this->get_display_no_posts_message(); //phpcs:ignore -- No XSS.
		}

		if ( ! $echo ) {
			$result = ob_get_contents();
			ob_end_clean();

			if ( false === $result ) {
				$result = $this->get_display_no_posts_message();
			}

			return $result;
		}

		return null;
	}

	/**
	 * Set the Ajax loading manually, overwriting the global option for this
	 * widget.
	 *
	 * @param bool $load_via_ajax Whether to force the widget to load via ajax or not.
	 * @return void
	 */
	public function set_ajax_loading( $load_via_ajax ) {
		if ( is_bool( $load_via_ajax ) ) {
			$this->load_via_ajax = $load_via_ajax;
		}
	}

	/**
	 * Check if the widget needs to be loaded via Ajax or not.
	 *
	 * @return bool
	 */
	public function widget_is_loaded_via_ajax() {
		if ( null !== $this->load_via_ajax ) {
			return $this->load_via_ajax;
		}

		$option = General_Options::get_option( General_Options::LOAD_WIDGET_VIA_AJAX );

		if ( 'true' === $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Display the HTML that needs to be replaced with the widget after the
	 * ajax call.
	 *
	 * @return void
	 */
	protected function display_widget_to_load_via_ajax() {
		$widget_id = (string) $this->widget_id;
		$post_id   = '0';

		global $post;
		// todo: search how to check if global $post exist and is good better maybe is_single?.
		if ( ( $post instanceof WP_Post ) && $post->ID > 0 ) {
			$post_id = (string) $post->ID;
		}

		$failed_text = Frontend_Translation::get_translation( Frontend_Translation::WIDGET_FAIL_TO_LOAD );

		echo '<div id="twrp-widget-load-via-ajax--' . esc_attr( $widget_id ) . '" style="display:none;" data-twrp-ajax-widget-id="' . esc_attr( $widget_id ) . '" data-twrp-ajax-post-id="' . esc_attr( $post_id ) . '" data-twrp-failed-text="' . esc_attr( $failed_text ) . '">';
	}

	/**
	 * Get the query ids, that are valid to display.
	 *
	 * A query is valid to display if it has the needed plugins installed.
	 *
	 * @param array $query_ids
	 * @return array
	 */
	protected function get_filtered_valid_queries_to_display( $query_ids ) {
		$returned_query_ids = array();
		foreach ( $query_ids as $query_id ) {
			$dependencies_ok = false;
			if ( Query_Options::query_plugin_dependencies_installed( $query_id ) ) {
				$dependencies_ok = true;
			}

			$all_ok = $dependencies_ok;
			if ( $all_ok ) {
				array_push( $returned_query_ids, $query_id );
			}
		}

		return $returned_query_ids;
	}

	/**
	 * Display a text that says that there is no posts.
	 *
	 * @return string
	 */
	protected function get_display_no_posts_message() {
		$no_posts_text = Frontend_Translation::get_translation( Frontend_Translation::NO_POSTS_TEXT );

		return '<div class="twrp-main__no-posts-wrapper"><span class="twrp-main__no-posts-text">' . esc_html( $no_posts_text ) . '</span></div>';
	}
}

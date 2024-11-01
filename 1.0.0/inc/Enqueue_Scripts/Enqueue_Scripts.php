<?php

namespace TWRP\Enqueue_Scripts;

use TWRP\Admin\Settings_Menu;
use TWRP\Database\General_Options;

use TWRP\Utils\Color_Utils;
use TWRP\Utils\Directory_Utils;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;

use TWRP\Enqueue_Scripts\Icons_CSS;

/**
 * Class used to enqueue the CSS/JS files, and to generate some CSS.
 *
 * Some css/js files are included via 'wp_enqueue_scripts' action, and some
 * added in the head via 'wp_head' action. The icons svgs are a special way of
 * including, see Icons_CSS class.
 *
 * By standard, all CSS/JS inclusions should be defined here. If for example a
 * class needs a specific JS plugin, then we can see if the class exists, and
 * enqueue the script from this class. Is more easily to see an overview of all
 * files included when they all are in one place, rather than scattered through
 * code.
 *
 * The inline CSS is added in a Tabs_Creator method.
 */
class Enqueue_Scripts {

	use After_Setup_Theme_Init_Trait;

	/**
	 * Include the styles. See After_Setup_Theme_Init_Trait for more details.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		// Frontend.
		add_action( 'wp_enqueue_scripts', array( static::class, 'include_the_frontend_styles' ), 11 );
		add_action( 'wp_enqueue_scripts', array( static::class, 'include_the_frontend_scripts' ), 11 );
		add_action( 'wp_head', array( __CLASS__, 'generate_color_variables_inline_style' ) );

		// Include the needed icons.
		$include_inline = General_Options::get_option( General_Options::SVG_INCLUDE_INLINE );
		if ( 'true' === $include_inline ) {
			add_action(
				'twrp_display_tabs_before_ajax',
				/**
				 * Add the inline svg icons.
				 *
				 * @param array $instance_settings
				 * @return void
				 */
				function( $instance_settings ) {
					static $function_executed = false;
					if ( ! $function_executed && ! wp_doing_ajax() ) {
						Icons_CSS::include_needed_icons_inline();
						$function_executed = true;
					}
				}
			);
		} else {
			add_action( 'wp_head', array( Icons_CSS::class, 'include_needed_icons_file' ) );
		}

		// Admin.
		add_action( 'admin_enqueue_scripts', array( static::class, 'include_the_backend_styles' ), 11 );
		add_action( 'admin_enqueue_scripts', array( static::class, 'include_the_backend_scripts' ), 11 );
		// In admin, include all icons file.
		add_action( 'admin_head', array( Icons_CSS::class, 'include_all_icons_file' ) );
	}

	/**
	 * Include the frontend styles necessary for this plugin to work.
	 *
	 * @return void
	 */
	public static function include_the_frontend_styles() {
		$version = Directory_Utils::PLUGIN_VERSION;
		wp_enqueue_style( 'twrp-style', Directory_Utils::get_frontend_directory_url() . 'style.css', array(), $version, 'all' );
	}

	/**
	 * Include the frontend scripts necessary for this plugin to work.
	 *
	 * @return void
	 */
	public static function include_the_frontend_scripts() {
		$version         = Directory_Utils::PLUGIN_VERSION;
		$grid_posts_fill = General_Options::get_option( General_Options::FILL_GRID_WITH_POSTS );

		wp_enqueue_script( 'twrp-script', Directory_Utils::get_frontend_directory_url() . 'script.js', array(), $version, true );

		$localization_object = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		);

		// By default, the grid posts fill is enabled,  the below code will only disable it.
		if ( 'false' === $grid_posts_fill ) {
			$localization_object['disableGridPostsFill'] = 'true';
		}

		wp_localize_script( 'twrp-script', 'TWRPLocalizeObject', $localization_object );
	}

	/**
	 * Include the backend styles necessary for this plugin to work.
	 *
	 * @return void
	 */
	public static function include_the_backend_styles() {
		$version     = Directory_Utils::PLUGIN_VERSION;
		$backend_url = Directory_Utils::get_backend_directory_url();

		if ( is_admin() ) {
			wp_enqueue_style( 'twrpb-style', $backend_url . 'style.css', array(), $version, 'all' );
		}

		if ( self::is_query_tab_displayed() ) {
			// CodeMirror.
			wp_enqueue_style( 'wp-codemirror' );
		}
	}

	/**
	 * Include the backend scripts necessary for this plugin to work.
	 *
	 * @return void
	 */
	public static function include_the_backend_scripts() {
		$version     = Directory_Utils::PLUGIN_VERSION;
		$backend_url = Directory_Utils::get_backend_directory_url();

		if ( is_admin() ) {
			wp_enqueue_script( 'twrpb-script', $backend_url . 'script.js', array( 'jquery', 'wp-api' ), $version, true );
			// Include Pickr translations.
			wp_localize_script( 'twrpb-script', 'TwrpPickrTranslations', self::get_pickr_translations() );

			// Jquery UI.
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-effects-blind' );
		}

		if ( self::is_query_tab_displayed() ) {
			// CodeMirror.
			wp_enqueue_script( 'wp-codemirror' );

			// Jquery UI.
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
		}

		if ( self::is_plugin_settings_page_displayed() ) {
			wp_enqueue_media();
		}
	}

	/**
	 * Generate the inline twrp color variables.
	 *
	 * @return void
	 */
	public static function generate_color_variables_inline_style() {
		$background_color = General_Options::get_option( General_Options::BACKGROUND_COLOR );
		if ( ! Color_Utils::is_color( $background_color ) ) {
			$background_color = 'inherit';
		}

		$secondary_background_color = General_Options::get_option( General_Options::SECONDARY_BACKGROUND_COLOR );
		if ( ! Color_Utils::is_color( $secondary_background_color ) ) {
			$secondary_background_color = 'inherit';
		}

		$text_color = General_Options::get_option( General_Options::TEXT_COLOR );
		if ( ! Color_Utils::is_color( $text_color ) ) {
			$text_color = 'inherit';
		}

		$disabled_text_color = General_Options::get_option( General_Options::DISABLED_TEXT_COLOR );
		if ( ! Color_Utils::is_color( $disabled_text_color ) ) {
			$disabled_text_color = 'inherit';
		}

		$accent_color = General_Options::get_option( General_Options::ACCENT_COLOR );
		if ( ! Color_Utils::is_color( $accent_color ) ) {
			$accent_color = 'inherit';
		}

		$darker_accent_color = General_Options::get_option( General_Options::DARKER_ACCENT_COLOR );
		if ( ! Color_Utils::is_color( $darker_accent_color ) ) {
			$darker_accent_color = 'inherit';
		}

		$lighter_accent_color = General_Options::get_option( General_Options::LIGHTER_ACCENT_COLOR );
		if ( ! Color_Utils::is_color( $lighter_accent_color ) ) {
			$lighter_accent_color = 'inherit';
		}

		$border_color = General_Options::get_option( General_Options::BORDER_COLOR );
		if ( ! Color_Utils::is_color( $border_color ) ) {
			$border_color = 'inherit';
		}

		$secondary_border_color = General_Options::get_option( General_Options::SECONDARY_BORDER_COLOR );
		if ( ! Color_Utils::is_color( $secondary_border_color ) ) {
			$secondary_border_color = 'inherit';
		}

		$outline_accent_color = Color_Utils::set_opacity( $accent_color, 0.5 );
		if ( ! is_string( $outline_accent_color ) ) {
			$outline_accent_color = $accent_color;
		}

		$best_accent_text_color = 'rgba(256, 256, 256, 1)';
		if ( 0 === Color_Utils::white_or_black_text( $accent_color ) ) {
			$best_accent_text_color = 'rgba(0, 0, 0, 1)';
		}

		$border_radius = General_Options::get_option( General_Options::BORDER_RADIUS );
		if ( empty( $border_radius ) || ! is_string( $border_radius ) ) {
			$border_radius = '0';
		} else {
			$border_radius = $border_radius . 'px';
		}

		$tab_button_size = General_Options::get_option( General_Options::TAB_BUTTON_SIZE );
		if ( empty( $tab_button_size ) || ! is_string( $tab_button_size ) ) {
			$tab_button_size = '1rem';
		} else {
			$tab_button_size = $tab_button_size . 'rem';
		}

		echo '<style type="text/css">:root{' .
			'--twrp-background-color: ' . esc_html( $background_color ) . ';' .
			'--twrp-secondary-background-color: ' . esc_html( $secondary_background_color ) . ';' .

			'--twrp-text-color: ' . esc_html( $text_color ) . ';' .
			'--twrp-disabled-text-color: ' . esc_html( $disabled_text_color ) . ';' .

			'--twrp-accent-color: ' . esc_html( $accent_color ) . ';' .
			'--twrp-darker-accent-color: ' . esc_html( $darker_accent_color ) . ';' .
			'--twrp-lighter-accent-color: ' . esc_html( $lighter_accent_color ) . ';' .

			'--twrp-border-color: ' . esc_html( $border_color ) . ';' .
			'--twrp-secondary-border-color: ' . esc_html( $secondary_border_color ) . ';' .
			'--twrp-border-radius: ' . esc_html( $border_radius ) . ';' .

			'--twrp-outline-accent-color: ' . esc_html( $outline_accent_color ) . ';' .
			'--twrp-accent-best-text-color: ' . esc_html( $best_accent_text_color ) . ';' .

			'--twrp-tab-button-font-size: ' . esc_html( $tab_button_size ) . ';' .
		'}</style>';
	}

	/**
	 * Get an array with translations that is needed to be used in the pickr color
	 * picker.
	 *
	 * @return array
	 */
	private static function get_pickr_translations() {
		return array(
			// Strings visible in the UI.
			'ui:dialog'       => _x( 'color picker dialog', 'backend', 'tabs-with-posts' ),
			'btn:toggle'      => _x( 'toggle color picker dialog', 'backend', 'tabs-with-posts' ),
			'btn:swatch'      => _x( 'color swatch', 'backend', 'tabs-with-posts' ),
			'btn:last-color'  => _x( 'use previous color', 'backend', 'tabs-with-posts' ),
			'btn:save'        => _x( 'Save', 'backend', 'tabs-with-posts' ),
			'btn:cancel'      => _x( 'Cancel', 'backend', 'tabs-with-posts' ),
			'btn:clear'       => _x( 'Clear', 'backend', 'tabs-with-posts' ),

			// Strings used for aria-labels.
			'aria:btn:save'   => _x( 'save and close', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:btn:cancel' => _x( 'cancel and close', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:btn:clear'  => _x( 'clear and close', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:input'      => _x( 'color input field', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:palette'    => _x( 'color selection area', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:hue'        => _x( 'hue selection slider', 'backend, screen reader text', 'tabs-with-posts' ),
			'aria:opacity'    => _x( 'selection slider', 'backend, screen reader text', 'tabs-with-posts' ),
		);
	}

	/**
	 * Get if the current page is widgets page
	 *
	 * @return bool
	 */
	protected static function is_widgets_page() {
		global $pagenow;
		return Settings_Menu::is_active_screen() || 'widgets.php' === $pagenow;
	}

	/**
	 * Get if the current screen displayed is the "Query Settings" tab.
	 *
	 * @return bool
	 */
	protected static function is_query_tab_displayed() {
		return Settings_Menu::is_query_settings_tab_active();
	}

	/**
	 * Get if the current screen displayed is the Plugin Settings.
	 *
	 * @return bool
	 */
	protected static function is_plugin_settings_page_displayed() {
		return Settings_Menu::is_active_screen();
	}
}

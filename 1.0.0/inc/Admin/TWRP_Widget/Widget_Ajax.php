<?php

namespace TWRP\Admin\TWRP_Widget;

use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Widget_Utils;

/**
 * Class that encapsulate all widget functions that might be called by frontend
 * JavaScript programs.
 */
class Widget_Ajax {

	use After_Setup_Theme_Init_Trait;

	const NONCE_ACTION_NAME = 'twrpb-plugin-widget-ajax';

	/**
	 * Register the AJAX hooks and functions needed to be set.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		add_action( 'wp_ajax_twrpb_widget_create_query_setting', self::class . '::ajax_create_query_selected_item' );
		add_action( 'wp_ajax_twrpb_widget_create_artblock_settings', self::class . '::ajax_create_artblock_settings' );

		add_action( 'admin_print_footer_scripts', self::class . '::enqueue_nonce' );
	}

	/**
	 * Returns a query tab HTML to the ajax callback.
	 *
	 * @return void
	 */
	public static function ajax_create_query_selected_item() {
		if ( ! isset( $_POST, $_POST['nonce'], $_POST['widget_id'], $_POST['query_id'] ) ) {
			echo 'error';
			die();
		}

		$nonce = sanitize_key( (string) $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION_NAME ) ) {
			echo 'error';
			die();
		}

		$widget_id = intval( wp_unslash( $_POST['widget_id'] ) );
		$query_id  = intval( wp_unslash( $_POST['query_id'] ) );

		$widget_instance_settings = Widget_Utils::get_instance_settings( $widget_id );
		$widget_form              = new Widget_Form( $widget_id, $widget_instance_settings );

		$widget_form->display_query_settings( $query_id );
		die();
	}

	/**
	 * Returns the HTML for the artblock settings to the ajax callback.
	 *
	 * @return void
	 */
	public static function ajax_create_artblock_settings() {
		if ( ! isset( $_POST, $_POST['nonce'], $_POST['artblock_id'], $_POST['widget_id'], $_POST['query_id'] ) ) {
			echo 'error';
			die();
		}

		$nonce = sanitize_key( (string) $_POST['nonce'] );

		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION_NAME ) ) {
			echo 'error';
			die();
		}

		$artblock_id = sanitize_key( (string) $_POST['artblock_id'] );
		$widget_id   = intval( wp_unslash( $_POST['widget_id'] ) );
		$query_id    = intval( wp_unslash( $_POST['query_id'] ) );

		$widget_instance_settings = Widget_Utils::get_instance_settings( $widget_id );
		$widget_form              = new Widget_Form( $widget_id, $widget_instance_settings );

		$widget_form->display_artblock_settings( $query_id, $artblock_id );
		die();
	}

	/**
	 * Displays a div that have a data nonce.
	 *
	 * @return void
	 */
	public static function enqueue_nonce() {
		if ( ! is_admin() ) {
			return;
		}

		$nonce = wp_create_nonce( self::NONCE_ACTION_NAME );
		?>
			<div id="twrpb-plugin-widget-ajax-nonce" data-twrpb-plugin-widget-ajax-nonce="<?php echo esc_attr( $nonce ); ?>" style="display:none"></div>
		<?php
	}
}

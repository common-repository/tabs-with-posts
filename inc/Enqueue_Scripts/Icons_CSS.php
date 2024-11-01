<?php

namespace TWRP\Enqueue_Scripts;

use TWRP\Icons\Icon_Factory;
use TWRP\Database\General_Options;
use TWRP\Database\Aside_Options;

use TWRP\Utils\Directory_Utils;
use TWRP\Utils\Filesystem_Utils;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;

use RuntimeException;

/**
 * Class used to generate the methods to include the Icons SVG. The icons are
 * enqueue in Enqueue_Scripts file.
 *
 * The method in which the icons SVG are included is that in the HTML head, we
 * put a script tag, where we prepend a div with all the SVG in the <body>
 * (similar of how Facebook includes it's comment scripts).
 *
 * There are two methods to include the icons: inline and via a file.
 *
 * There are 2 types of icons that can be included:
 * 1. All icons(included in the admin area, via a file).
 * 2. Needed icons(included in frontend area, either inline or via a file).
 *
 * The needed icons are written in file and in database every time the general
 * settings are updated, and the icons settings are changed. There exist a
 * special timestamp that is generate every time the file is written, to add at
 * the end of the file link, to prevent use of the old file by browser caching.
 */
class Icons_CSS {

	use After_Setup_Theme_Init_Trait;

	/**
	 * See After_Setup_Theme_Init_Trait for more info.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		// When settings get updated, generate the needed icons file and inline svg.
		add_action( 'twrp_general_before_settings_submitted', array( __CLASS__, 'write_needed_icons_on_settings_submitted' ) );
	}

	/**
	 * When plugin is activated, we write the needed icons to the icons file,
	 * because an old version of the database with some other icons can be
	 * installed.
	 *
	 * This function should be registered with register_activation_hook() function.
	 *
	 * @return void
	 */
	public static function write_needed_icons_to_file_on_plugin_activation() {
		self::write_needed_icons_to_file();
		self::write_needed_icons_to_option_in_database();
	}

	#region -- Enqueue icons

	/**
	 * Include the icon definitions inline.
	 *
	 * This method should be called at wp_head action, because it creates an
	 * element at the opening of the body with the svgs.
	 *
	 * @return void
	 */
	public static function include_needed_icons_inline() {
		$icon_definitions = wp_json_encode( Aside_Options::get_inline_icons() );

		if ( false === $icon_definitions ) {
			return;
		}

		?>
		<script id="twrp-include-icons-inline-script" type="text/javascript">
		(function(){var div=document.createElement( 'div' );div.innerHTML=<?php echo $icon_definitions; // phpcs:ignore ?>;insertIntoDocument(div);
		function insertIntoDocument(elem){if(document.body && document.body.firstElementChild){document.body.insertBefore(elem,document.body.firstElementChild);}else{setTimeout(insertIntoDocument.bind(null,elem),200);}}})();
		</script>
		<?php
	}

	/**
	 * Enqueue all needed icons file.
	 *
	 * This function needs to be called at 'wp_head' or 'admin_head' action.
	 *
	 * @return void
	 */
	public static function include_needed_icons_file() {
		$file_url = Directory_Utils::get_needed_icons_url();
		$time     = Aside_Options::get_needed_icons_generation_timestamp();
		self::ajax_include_svg_file( $file_url . '?time="' . $time . '"' );
	}

	/**
	 * Enqueue a file with all icons.
	 *
	 * This function needs to be called at 'admin_head' action.
	 *
	 * @return void
	 */
	public static function include_all_icons_file() {
		$file_path = Directory_Utils::get_all_icons_url();
		self::ajax_include_svg_file( $file_path . '?version="' . Directory_Utils::PLUGIN_VERSION . '"' );
	}

	/**
	 * Include a svg file at the top of the document(after the body tag ends).
	 *
	 * The svg files cannot be included in the head, and inline svg is the only
	 * way to reference a SVG by id. Inline svgs are not good because they
	 * cannot be cached, so thus we include a file containing all svgs as inline.
	 *
	 * Careful at the included file, to not violate the CORS.
	 *
	 * @param string $file_path The path to the file to be included.
	 * @return void
	 */
	protected static function ajax_include_svg_file( $file_path ) {
		?>
		<script type="text/javascript">
		(function(){const ajax=new XMLHttpRequest();ajax.open('GET','<?php echo esc_url( $file_path ); ?>',true);ajax.send();
		ajax.onload=function(e){var div=document.createElement('div');div.innerHTML=ajax.responseText;insertIntoDocument(div);};
		function insertIntoDocument(elem){if(document.body && document.body.firstElementChild){document.body.insertBefore(elem,document.body.firstElementChild);}else{setTimeout(insertIntoDocument.bind(null,elem),200);}}})();
		</script>
		<?php
	}

	#endregion -- Enqueue icons

	#region -- Create needed icons, inline and in a file

	/**
	 * Write the needed icons to a file nd to database if the settings were
	 * submitted, and the icons were updated.
	 *
	 * This function should be called at 'twrp_general_before_settings_submitted' action.
	 *
	 * @param array $updated_settings All the new settings that were updated.
	 *
	 * @return void
	 */
	public static function write_needed_icons_on_settings_submitted( $updated_settings ) {
		foreach ( General_Options::ICON_KEYS as $icon_class_name ) {
			$icon_setting  = General_Options::get_option( $icon_class_name );
			$setting_class = General_Options::get_option_object( $icon_class_name );
			if ( null === $setting_class ) {
				continue;
			}
			$setting_key_name = $setting_class->get_key_name();

			// Will only update if the icons are changed.
			if ( isset( $updated_settings[ $setting_key_name ] ) && $icon_setting !== $updated_settings[ $setting_key_name ] ) {
				add_action(
					'twrp_general_after_settings_submitted',
					function() {
						self::write_needed_icons_to_file();
						self::write_needed_icons_to_option_in_database();
					}
				);
				break;
			}
		}
	}

	/**
	 * Write needed icons to a specific file in assets folder.
	 *
	 * @return bool Whether or not the file was written.
	 */
	protected static function write_needed_icons_to_file() {
		$file_path = Directory_Utils::get_needed_icons_path();
		$html      = self::get_defs_file_needed_icons();

		return Filesystem_Utils::set_file_contents( $file_path, $html );
	}

	/**
	 * Write needed icons to a option in database.
	 *
	 * @return bool Whether or not the option was updated.
	 */
	protected static function write_needed_icons_to_option_in_database() {
		$html = self::get_defs_inline_needed_icons();
		Aside_Options::set_icons_generation_timestamp_to_current_timestamp();
		return Aside_Options::set_inline_icons( $html );
	}

	/**
	 * Get the HTML for a file of all needed icons.
	 *
	 * @return string
	 */
	protected static function get_defs_file_needed_icons() {
		$html_header =
		'<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . "\n" .
		'<!-- This file is generated dynamically. Do NOT modify it. -->' . "\n" .
		'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display:none;">' . "\n";
		$html_footer = '</svg>';

		$html = '';
		foreach ( self::get_all_used_icons() as $icon_id ) {
			try {
				$icon = Icon_Factory::get_icon( $icon_id );
			} catch ( RuntimeException $e ) {
				continue;
			}

			$def = $icon->get_icon_svg_definition();
			if ( false !== $def ) {
				$html .= $def;
			}
		}
		$html = str_replace( '<svg', '<symbol', $html );
		$html = str_replace( 'svg>', 'symbol>', $html );

		$html = $html_header . $html . $html_footer;

		return $html;
	}

	/**
	 * Get the inline HTML of all needed icons.
	 *
	 * @return string
	 */
	public static function get_defs_inline_needed_icons() {
		$html_header = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display:none;">';
		$html_footer = '</svg>';

		$html = '';
		foreach ( self::get_all_used_icons() as $icon_id ) {
			try {
				$icon = Icon_Factory::get_icon( $icon_id );
			} catch ( RuntimeException $e ) {
				continue;
			}

			$def = $icon->get_icon_svg_definition();
			if ( false !== $def ) {
				$html .= $def;
			}
		}
		$html = str_replace( '<svg', '<symbol', $html );
		$html = str_replace( 'svg>', 'symbol>', $html );

		$html = $html_header . $html . $html_footer;

		return $html;
	}

	#endregion -- Create needed icons, inline and in a file

	#region -- Get all used website icons Ids

	/**
	 * Get an array with all used icons ids, for all widgets.
	 *
	 * @return array<string>
	 */
	public static function get_all_used_icons() {
		$icons = array();

		$options = General_Options::ICON_KEYS;
		foreach ( $options as $option_key ) {
			$option_value = General_Options::get_option( $option_key );

			if ( General_Options::RATING_ICON_PACK === $option_key ) {
				if ( is_string( $option_value ) ) {
					try {
						$rating_pack = Icon_Factory::get_rating_pack( $option_value );
					} catch ( RuntimeException $e ) {
						continue;
					}

					$icon = $rating_pack->get_filled_icon();
					array_push( $icons, $icon->get_id() );
					$icon = $rating_pack->get_half_filled_icon();
					array_push( $icons, $icon->get_id() );
					$icon = $rating_pack->get_empty_icon();
					array_push( $icons, $icon->get_id() );
				}

				continue;
			}

			if ( ! empty( $option_value ) && is_string( $option_value ) ) {
				array_push( $icons, $option_value );
			}
		}

		return $icons;
	}

	#endregion -- Get all used website icons Ids
}

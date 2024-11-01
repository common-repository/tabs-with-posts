<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Utils\Simple_Utils;
use TWRP\Utils\Directory_Utils;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;

/**
 * Every plugin known to this plugin should extend this class. It contains
 * methods to know the title, author, if plugin is installed, versions tested,
 * plugin avatar... etc.
 */
abstract class Known_Plugin {

	use After_Setup_Theme_Init_Trait;

	/**
	 * Get the title of the plugin.
	 *
	 * @return string
	 */
	abstract public function get_plugin_title();

	/**
	 * Get the author of the plugin.
	 *
	 * @return string
	 */
	abstract public function get_plugin_author();

	/**
	 * Get the current version of the plugin.
	 *
	 * @return string|false False in case the plugin is not installed.
	 */
	public function get_plugin_version() {
		$plugins = get_plugins();

		$plugin_files = $this->get_plugin_file_relative_path();
		if ( is_array( $plugin_files ) ) {
			foreach ( $plugin_files as $plugin_file ) {
				if ( isset( $plugins[ $plugin_file ]['Version'] ) ) {
					return $plugins[ $plugin_file ]['Version'];
				}
			}
		} elseif ( isset( $plugins[ $plugin_files ]['Version'] ) ) {
			return $plugins[ $plugin_files ]['Version'];
		}

		return false;
	}

	/**
	 * Get last manually tested version of the plugin.
	 *
	 * @return string
	 */
	abstract public function get_tested_plugin_versions();

	/**
	 * Get the plugin avatar src.
	 *
	 * @return string
	 */
	public function get_plugin_avatar_src() {
		$class_name = get_called_class();
		$class_name = str_replace( '_Locked', '', $class_name );

		return Directory_Utils::get_plugin_avatar_src( $class_name );
	}

	/**
	 * Get the plugin file path, relative to WP plugins directory.
	 *
	 * @return string|string[]
	 */
	abstract public function get_plugin_file_relative_path();

	/**
	 * Whether or not the plugin is installed.
	 *
	 * @return bool
	 */
	abstract public function is_installed_and_can_be_used();

}

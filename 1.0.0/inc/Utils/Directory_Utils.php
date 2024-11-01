<?php

namespace TWRP\Utils;

/**
 * Class that is a collection of static methods, that can be used everywhere,
 * when a function that perform something with a directory is needed. This class
 * knows about this plugin folder structure, and also of the important files, so
 * it's always good to create a method with a good name and place it here.
 *
 * This class also guarantees a DRY(Do not repeat yourself) principle. Having
 * just one place to change a file/folder name is better than searching the
 * whole project for where the file might be referenced.
 *
 * If you find itself needed to use a function, then add a string or a path
 * after, then most probably you will need to add a constant and create a method
 * here.
 */
class Directory_Utils {

	/**
	 * How many folders this class is indexed, this constant is usually needed
	 * for the get_dir_path() or get_dir_url() WP functions. For example if
	 * is nested inside inc/Utils/ folder, then the value is 2.
	 */
	const HOW_MANY_FOLDERS_THIS_FILE_IS_NESTED = 2;

	/**
	 * The current version of this plugin.
	 *
	 * Mainly used in CSS/JS version to prevent caching when something has
	 * changed.
	 */
	const PLUGIN_VERSION = '1.0.0';

	/**
	 * The main file name of this plugin, without .php extension.
	 */
	const PLUGIN_MAIN_FILE_NAME = 'tabs-with-posts';

	/**
	 * The folder where article blocks templates are to be found.
	 */
	const TEMPLATES_FOLDER = 'templates/';

	/**
	 * The folder where all assets, meaning CSS, JS, SVG, and images(and maybe
	 * others) are to be found.
	 */
	const ASSETS_FOLDER = 'assets/';

	/**
	 * The folder where frontend CSS/JS are to be found.
	 */
	const FRONTEND_FOLDER = 'assets/frontend/';

	/**
	 * The folder where backend CSS/JS are to be found.
	 */
	const BACKEND_FOLDER = 'assets/backend/';

	/**
	 * The folder where the plugin avatars are keep.
	 */
	const PLUGIN_AVATARS_FOLDER = 'assets/plugin-avatars/';

	/**
	 * The folder where no thumbnail images are keep.
	 */
	const NO_THUMBNAIL_IMAGES_FOLDER = 'assets/no-thumbnail-images/';

	/**
	 * The folder where no thumbnail images are keep.
	 */
	const ARTBLOCK_META_LOCATIONS_IMAGES_FOLDER = 'assets/artblock-meta-locations/';

	/**
	 * The folder where all svg assets are to be found.
	 */
	const SVG_FOLDER = 'assets/svgs/';

	/**
	 * The file that contains all icons of this plugin.
	 */
	const ALL_ICONS_FILE = 'assets/svgs/all-icons.svg';

	/**
	 * The file that contains only needed icons of this plugin.
	 */
	const NEEDED_ICONS_FILE = 'assets/svgs/needed-icons.svg';

	/**
	 * Get the path of the main plugin file.
	 *
	 * @return string
	 */
	public static function get_path_of_main_plugin_file() {
		return self::get_plugin_directory_path() . self::PLUGIN_MAIN_FILE_NAME . '.php';
	}

	/**
	 * Returns the path to this plugin directory.
	 *
	 * @return string The path is trail slashed.
	 */
	public static function get_plugin_directory_path() {
		$file = __FILE__;
		for ( $i = self::HOW_MANY_FOLDERS_THIS_FILE_IS_NESTED; $i > 0; $i-- ) {
			$file = dirname( $file );
		}

		return trailingslashit( plugin_dir_path( $file ) );
	}

	/**
	 * Get this plugin directory URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_plugin_directory_url() {
		$file = __FILE__;

		for ( $i = self::HOW_MANY_FOLDERS_THIS_FILE_IS_NESTED; $i > 0; $i-- ) {
			$file = dirname( $file );
		}

		return trailingslashit( plugin_dir_url( $file ) );
	}

	/**
	 * Get the plugin avatar src.
	 *
	 * @param string $fully_class_name The fully class name of the plugin.
	 * @return string
	 */
	public static function get_plugin_avatar_src( $fully_class_name ) {
		$plugin_avatars_url = self::get_plugin_avatars_directory_url();
		$class_name         = substr( $fully_class_name, (int) strrpos( $fully_class_name, '\\' ) + 1 );

		$image_file = trailingslashit( $plugin_avatars_url ) . $class_name . '.png';

		return $image_file;
	}

	/**
	 * Get plugin avatars folder path.
	 *
	 * @return string The path is has an ending slash.
	 */
	public static function get_plugin_avatars_directory_path() {
		$directory = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::PLUGIN_AVATARS_FOLDER, '/' );
		return trailingslashit( $directory );
	}

	/**
	 * Get plugin avatars folder URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_plugin_avatars_directory_url() {
		$plugins_url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::PLUGIN_AVATARS_FOLDER, '/' );
		return trailingslashit( $plugins_url );
	}

	/**
	 * Get folder path with images that should display when no thumbnail is available.
	 *
	 * @return string The path is has an ending slash.
	 */
	public static function get_no_thumbnail_images_directory_path() {
		$directory = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::NO_THUMBNAIL_IMAGES_FOLDER, '/' );
		return trailingslashit( $directory );
	}

	/**
	 * Get folder URL with images that should display when no thumbnail is available.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_no_thumbnail_images_directory_url() {
		$url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::NO_THUMBNAIL_IMAGES_FOLDER, '/' );
		return trailingslashit( $url );
	}

	/**
	 * Get folder path with images of artblocks meta locations.
	 *
	 * @return string The path is has an ending slash.
	 */
	public static function get_artblock_meta_locations_images_directory_path() {
		$directory = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::ARTBLOCK_META_LOCATIONS_IMAGES_FOLDER, '/' );
		return trailingslashit( $directory );
	}

	/**
	 * Get folder URL with images of artblocks meta locations.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_artblock_meta_locations_images_directory_url() {
		$url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::ARTBLOCK_META_LOCATIONS_IMAGES_FOLDER, '/' );
		return trailingslashit( $url );
	}

	/**
	 * Get the path to plugin "templates" folder.
	 *
	 * @return string The path is has an ending slash.
	 */
	public static function get_template_directory_path() {
		$directory = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::TEMPLATES_FOLDER, '/' );
		return trailingslashit( $directory );
	}

	/**
	 * Get the url to plugin "templates" folder.
	 *
	 * @return string The path is has an ending slash.
	 */
	public static function get_template_directory_url() {
		$url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::TEMPLATES_FOLDER, '/' );
		return trailingslashit( $url );
	}

	/**
	 * Get plugin assets directory path.
	 *
	 * @return string Path has a trailing slash.
	 */
	public static function get_assets_directory_path() {
		$assets_dir = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::ASSETS_FOLDER, '/' );
		return trailingslashit( $assets_dir );
	}

	/**
	 * Get plugin assets URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_assets_directory_url() {
		$assets_url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::ASSETS_FOLDER, '/' );
		return trailingslashit( $assets_url );
	}

	/**
	 * Get plugin assets frontend directory path.
	 *
	 * @return string Path has a trailing slash.
	 */
	public static function get_frontend_directory_path() {
		$assets_dir = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::FRONTEND_FOLDER, '/' );
		return trailingslashit( $assets_dir );
	}

	/**
	 * Get plugin assets frontend URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_frontend_directory_url() {
		$assets_url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::FRONTEND_FOLDER, '/' );
		return trailingslashit( $assets_url );
	}

	/**
	 * Get plugin assets backend directory path.
	 *
	 * @return string Path has a trailing slash.
	 */
	public static function get_backend_directory_path() {
		$assets_dir = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::BACKEND_FOLDER, '/' );
		return trailingslashit( $assets_dir );
	}

	/**
	 * Get plugin assets backend URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_backend_directory_url() {
		$assets_url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::BACKEND_FOLDER, '/' );
		return trailingslashit( $assets_url );
	}

	/**
	 * Get plugin assets svgs directory path.
	 *
	 * @return string Path has a trailing slash.
	 */
	public static function get_assets_svgs_directory_path() {
		$assets_svgs_dir = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::SVG_FOLDER, '/' );
		return trailingslashit( $assets_svgs_dir );
	}

	/**
	 * Get plugin assets svgs URL.
	 *
	 * @return string Url has a trailing slash.
	 */
	public static function get_assets_svgs_directory_url() {
		$assets_svgs_url = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::SVG_FOLDER, '/' );
		return trailingslashit( $assets_svgs_url );
	}

	/**
	 * Get path for the all icons file.
	 *
	 * @return string
	 */
	public static function get_all_icons_path() {
		$file = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::ALL_ICONS_FILE, '/' );
		return $file;
	}

	/**
	 * Get url for the all icons file.
	 *
	 * @return string
	 */
	public static function get_all_icons_url() {
		$file = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::ALL_ICONS_FILE, '/' );
		return $file;
	}

	/**
	 * Get path for the needed icons file.
	 *
	 * @return string
	 */
	public static function get_needed_icons_path() {
		$file = trailingslashit( self::get_plugin_directory_path() ) . ltrim( self::NEEDED_ICONS_FILE, '/' );
		return $file;
	}

	/**
	 * Get url for the needed icons file.
	 *
	 * @return string
	 */
	public static function get_needed_icons_url() {
		$file = trailingslashit( self::get_plugin_directory_url() ) . ltrim( self::NEEDED_ICONS_FILE, '/' );
		return $file;
	}

}

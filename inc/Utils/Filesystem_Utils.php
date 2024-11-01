<?php

namespace TWRP\Utils;

use WP_Filesystem_Direct;

/**
 * Class that is a collection of static methods, that can be used everywhere,
 * to write/read something from a file. The functions in this package works only
 * when writing/reading from a file that exist. It will not try to create the
 * file, as this can cause security problems. To create a file use
 * WP_Filesystem API.
 */
class Filesystem_Utils {

	/**
	 * Get the WP_Filesystem_Direct class. This class should be used only to
	 * get contents, to write anything.
	 *
	 * @psalm-suppress UnresolvableInclude
	 * @phan-suppress PhanTypeMismatchArgumentNullable
	 *
	 * @return null|WP_Filesystem_Direct
	 */
	public static function get_direct_filesystem() {
		if ( ! defined( 'ABSPATH' ) ) {
			return null;
		}

		if ( ! class_exists( 'WP_Filesystem_Base' ) || ! class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-base.php'; // @codeCoverageIgnore
			require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-filesystem-direct.php'; // @codeCoverageIgnore
		}

		return new WP_Filesystem_Direct( null );
	}

	/**
	 * Get the contents of a file.
	 *
	 * This function use WP_Filesystem_Direct class, to check if file exist and
	 * to get the contents.
	 *
	 * @param string $file_path
	 * @return string|false
	 */
	public static function get_file_contents( $file_path ) {
		$direct_filesystem = self::get_direct_filesystem();
		if ( is_null( $direct_filesystem ) || ! $direct_filesystem->exists( $file_path ) ) {
			return false;
		}

		$content = $direct_filesystem->get_contents( $file_path );
		if ( is_string( $content ) ) {
			return $content;
		}

		return false;
	}

	/**
	 * Set the contents of a file, will work only if the file exist.
	 *
	 * This function use WP_Filesystem_Direct class, to check if file exist and
	 * to set the contents.
	 *
	 * @param string $file_path
	 * @param string $content
	 * @return bool True on success, false on failure.
	 */
	public static function set_file_contents( $file_path, $content ) {
		$direct_filesystem = self::get_direct_filesystem();
		if ( is_null( $direct_filesystem ) || ! $direct_filesystem->exists( $file_path ) ) {
			return false;
		}

		if ( $direct_filesystem->put_contents( $file_path, $content, 0664 ) ) {
			return true;
		}

		return false;
	}

}

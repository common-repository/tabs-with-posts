<?php

namespace TWRP\Utils;

use DateTimeZone;
use DateTimeImmutable;
use WP_Post;

/**
 * Class that is a collection of static methods, that can be used everywhere
 * when a work with date needs to be done. Some date function were introduced
 * in WP 5.3, so if we want to support lower versions, we need to use some
 * polyfills.
 */
class Date_Utils {

	/**
	 * Get the website timezone.
	 *
	 * This will try to use WP function wp_timezone() available from WP 5.3, or
	 * else, will fallback to a polyfill.
	 *
	 * @return DateTimeZone
	 */
	public static function wp_timezone() {
		if ( function_exists( 'wp_timezone' ) ) {
			return wp_timezone(); // @phan-suppress-current-line PhanUndeclaredFunction
		}

		return new DateTimeZone( self::wp_timezone_polyfill() );
	}

	/**
	 * Get the timezone string. Used as polyfill if wp_timezone is not available.
	 *
	 * @return string
	 */
	protected static function wp_timezone_polyfill() {
		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset  = (float) get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}


	/**
	 * Retrieve post published or modified time as a Unix timestamp. This function
	 * will either use the native WP function with the same name(WP 5.3), or
	 * will fallback to the polyfill version.
	 *
	 * @param WP_Post|int|null $post WP_Post object or ID. Default is global $post object.
	 * @param string $field Published or modified time to use from database. Accepts 'date' or 'modified'.
	 * @return int|false Unix timestamp on success, false on failure.
	 */
	public static function get_post_timestamp( $post = null, $field = 'date' ) {
		if ( function_exists( 'get_post_timestamp' ) ) {
			return get_post_timestamp( $post, $field ); // @phan-suppress-current-line PhanUndeclaredFunction
		}

		return self::get_post_timestamp_polyfill( $post, $field );
	}

	/**
	 * Polyfill version of the get_post_timestamp() function introduced in WP 5.3.
	 *
	 * @param WP_Post|int|null $post WP_Post object or ID. Default is global $post object.
	 * @param string $field Published or modified time to use from database. Accepts 'date' or 'modified'.
	 * @return int|false Unix timestamp on success, false on failure.
	 */
	protected static function get_post_timestamp_polyfill( $post = null, $field = 'date' ) {
		$datetime = self::get_post_datetime( $post, $field );

		if ( false === $datetime ) {
			return false;
		}

		return $datetime->getTimestamp();
	}


	/**
	 * Retrieve post published or modified time as a DateTimeImmutable object
	 * instance. This will either use the wp function if available(WP 5.3), or
	 * will fallback to the polyfill version.
	 *
	 * For legacy reasons, this function allows to choose to instantiate from
	 * local or UTC time in database. Normally this should make no difference to
	 * the result. However, the values might get out of sync in database,
	 * typically because of timezone setting changes. The parameter ensures the
	 * ability to reproduce backwards compatible behaviors in such cases.
	 *
	 * @param WP_Post|int|null $post WP_Post object or ID. Default is global $post object.
	 * @param string $field Published or modified time to use from database. Accepts 'date' or 'modified'.
	 * @param string $source Local or UTC time to use from database. Accepts 'local' or 'gmt'.
	 * @return DateTimeImmutable|false Time object on success, false on failure.
	 */
	public static function get_post_datetime( $post = null, $field = 'date', $source = 'local' ) {
		if ( function_exists( 'get_post_datetime' ) ) {
			return get_post_datetime( $post, $field, $source ); // @phan-suppress-current-line PhanUndeclaredFunction
		}

		return self::get_post_datetime_polyfill( $post, $field, $source );
	}

	/**
	 * Retrieve post published or modified time as a DateTimeImmutable object
	 * instance. This is a polyfill of the wp function get_post_datetime() (WP 5.3).
	 *
	 * For legacy reasons, this function allows to choose to instantiate from
	 * local or UTC time in database. Normally this should make no difference to
	 * the result. However, the values might get out of sync in database,
	 * typically because of timezone setting changes. The parameter ensures the
	 * ability to reproduce backwards compatible behaviors in such cases.
	 *
	 * @param WP_Post|int|null $post WP_Post object or ID. Default is global $post object.
	 * @param string $field Published or modified time to use from database. Accepts 'date' or 'modified'.
	 * @param string $source Local or UTC time to use from database. Accepts 'local' or 'gmt'.
	 * @return DateTimeImmutable|false Time object on success, false on failure.
	 */
	protected static function get_post_datetime_polyfill( $post = null, $field = 'date', $source = 'local' ) {
		$post = get_post( $post );

		if ( ( ! $post ) || is_array( $post ) ) {
			return false;
		}

		$wp_timezone = self::wp_timezone();

		if ( 'gmt' === $source ) {
			$time     = ( 'modified' === $field ) ? $post->post_modified_gmt : $post->post_date_gmt;
			$timezone = new DateTimeZone( 'UTC' );
		} else {
			$time     = ( 'modified' === $field ) ? $post->post_modified : $post->post_date;
			$timezone = $wp_timezone;
		}

		if ( empty( $time ) || '0000-00-00 00:00:00' === $time ) {
			return false;
		}

		$datetime = date_create_immutable_from_format( 'Y-m-d H:i:s', $time, $timezone );

		if ( false === $datetime ) {
			return false;
		}

		return $datetime->setTimezone( $wp_timezone );
	}


	/**
	 * Retrieves the current time as an object with the timezone from settings.
	 * This function will either call the WP function current_datetime() if
	 * available(> WP 5.3) or fallback to a polyfill.
	 *
	 * @return DateTimeImmutable Date and time object.
	 */
	public static function current_datetime() {
		if ( function_exists( 'current_datetime' ) ) {
			return current_datetime(); // @phan-suppress-current-line PhanUndeclaredFunction
		}

		return self::current_datetime_polyfill();
	}

	/**
	 * Retrieves the current time as an object with the timezone from settings.
	 * This function is a polyfill for the WP function introduced in WP 5.3.
	 *
	 * @return DateTimeImmutable Date and time object.
	 */
	protected static function current_datetime_polyfill() {
		return new DateTimeImmutable( 'now', self::wp_timezone() );
	}

}

<?php

namespace TWRP\Utils;

use WPSEO_Primary_Term;

use WP_Term;
use ReflectionMethod;
use ReflectionException;
use RecursiveIteratorIterator;
use RecursiveArrayIterator;

/**
 * Class that contain a list of static methods, that can be used everywhere.
 * The methods here are simple methods that cannot be categorized in one of the
 * other *_Utils classes.
 */
class Simple_Utils {

	/**
	 * Verify that a value is a number bigger than 0.
	 *
	 * This will work everywhere where WP use an id, like posts, users,
	 * categories, ...etc.
	 *
	 * @param mixed $post_id
	 * @return int|false False if not valid.
	 */
	public static function get_valid_wp_id( $post_id ) {
		if ( ! is_numeric( $post_id ) ) {
			return false;
		}

		$post_id = (int) $post_id;

		if ( ! ( $post_id > 0 ) ) {
			return false;
		}

		return $post_id;
	}

	/**
	 * Verify that each value in an array is a number bigger than 0.
	 *
	 * This will work everywhere where WP use an id, like posts, users,
	 * categories, ...etc.
	 *
	 * @param array $post_ids
	 * @return array<int>
	 */
	public static function get_valid_wp_ids( $post_ids ) {
		foreach ( $post_ids as $key => $post_id ) {
			$sanitized_id = self::get_valid_wp_id( $post_id );

			if ( $sanitized_id ) {
				$post_ids[ $key ] = $sanitized_id;
			} else {
				unset( $post_ids[ $key ] );
			}
		}

		return $post_ids;
	}

	/**
	 * Flatten multi-dimensional array.
	 *
	 * @param array $array
	 * @return array
	 */
	public static function flatten_array( array $array ) {
		$ret_array = array();
		foreach ( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ) as $value ) {
			$ret_array[] = $value;
		}
		return $ret_array;
	}

	/**
	 * Check if the object contains a method that is public.
	 *
	 * @param string|object $class_name
	 * @param string $method_name
	 * @return bool
	 *
	 * @psalm-suppress ArgumentTypeCoercion ReflectionMethod takes class-string.
	 */
	public static function method_exist_and_is_public( $class_name, $method_name ) {
		if ( is_string( $class_name ) && ! class_exists( $class_name ) ) {
			return false;
		}

		try {
			$reflection_method = new ReflectionMethod( $class_name, $method_name );
		} catch ( ReflectionException $e ) {
			return false;
		}

		return $reflection_method->isPublic();
	}

	/**
	 * Check if the object contains a method.
	 *
	 * The method can be of any type, any visibility, static or non-static.
	 *
	 * @param string|object $class_name
	 * @param string $method_name
	 * @return bool
	 *
	 * @psalm-suppress ArgumentTypeCoercion ReflectionMethod takes class-string.
	 */
	public static function method_exist_in_class( $class_name, $method_name ) {
		try {
			$reflection_method = new ReflectionMethod( $class_name, $method_name );
		} catch ( ReflectionException $e ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the primary category, and additionally, all the categories.
	 *
	 * @param int $post_id
	 * @param bool $return_all_categories
	 * @param string $term Defaults to "category".
	 * @return array{primary_category:null|WP_Term,all_categories:array}
	 *
	 * @psalm-suppress LessSpecificReturnStatement
	 * @psalm-suppress MoreSpecificReturnType
	 */
	public static function get_post_primary_category( $post_id, $return_all_categories = false, $term = 'category' ) {
		$return                     = array();
		$return['primary_category'] = null;
		$return['all_categories']   = array();

		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			// Show Primary category by Yoast if it is enabled & set.
			$wpseo_primary_term = new WPSEO_Primary_Term( $term, $post_id );
			$primary_term       = get_term( $wpseo_primary_term->get_primary_term() );

			if ( ! is_wp_error( $primary_term ) ) {
				$return['primary_category'] = $primary_term;
			}
		}

		if ( empty( $return['primary_category'] ) || $return_all_categories ) {
			$categories_list = get_the_terms( $post_id, $term );

			if ( empty( $return['primary_category'] ) && is_array( $categories_list ) && ! empty( $categories_list ) ) {
				$return['primary_category'] = $categories_list[0];
			}

			if ( $return_all_categories && ! empty( $categories_list ) && is_array( $categories_list ) ) {
					$return['all_categories'] = $categories_list;
			}
		}

		return $return;
	}

	/**
	 * For a given number, create a number abbreviation.
	 * It works only for positive numbers. If the number is negative, return 0.
	 *
	 * @param int|string $number
	 * @param bool $extra_shortener Show a decimal only if one number is shortener, default true.
	 * @return string
	 */
	public static function get_number_abbreviation( $number, $extra_shortener = true ) {
		$abbrevs = array(
			1000000000 => Frontend_Translation::get_translation( Frontend_Translation::ABBREVIATION_FOR_BILLIONS ),
			1000000    => Frontend_Translation::get_translation( Frontend_Translation::ABBREVIATION_FOR_MILLIONS ),
			1000       => Frontend_Translation::get_translation( Frontend_Translation::ABBREVIATION_FOR_THOUSANDS ),
			1          => '',
		);

		if ( 0 === $number || $number < 0 ) {
			return '0';
		}

		$number_decimal_threshold = 100;
		if ( $extra_shortener ) {
			$number_decimal_threshold = 10;
		}

		$number = (int) $number;

		foreach ( $abbrevs as $exponent => $abbrev ) {
			if ( $number >= $exponent ) {
				$display_num = $number / $exponent;
				$decimals    = ( $exponent >= 1000 && round( $display_num ) < $number_decimal_threshold ) ? 1 : 0;
				return number_format_i18n( $display_num, $decimals ) . $abbrev;
			}
		}

		return (string) $number;
	}

	/**
	 * Get a css selector of the body, to increase specificity of the CSS. By
	 * default this selector is used in all CSS.
	 *
	 * @return string
	 */
	public static function get_body_css_increase_specificity_selector() {
		return 'body:not(#twrpS)';
	}

	/**
	 * Cut a string intelligently at the closest word of length if possible.
	 *
	 * The length should be bigger than 20. The string returned can be bigger
	 * than the length by 4, or less than the length.
	 *
	 * @param string $string The string to be cut.
	 * @param int $length
	 * @param string $ellipses
	 * @return string
	 */
	public static function cut_string_at_closest_length( $string, $length, $ellipses ) {
		$string_length = strlen( $string );
		if ( $string_length <= $length + 4 ) {
			return $string;
		}

		$string = substr( $string, 0, $length + 3 );

		$last_word = strpos( $string, ' ', $length - 3 );

		if ( is_int( $last_word ) && $last_word > $length - 4 ) {
			$string = substr( $string, 0, $last_word );
		} else {
			$string = substr( $string, 0, $length );
		}

		$last_char = substr( $string, -1 );

		if ( '.' === $last_char || ',' === $last_char ) {
			$string = substr( $string, 0, -1 );
		}

		if ( strpos( $string, '&hellip;', strlen( $string ) - 13 ) || strpos( $string, '...', strlen( $string ) - 6 ) ) {
			return $string;
		}

		// remove chunks of &hellip;.
		$possible_hellip_start = strpos( $string, '&', strlen( $string ) - 7 );
		if ( $possible_hellip_start ) {
			$string = substr( $string, 0, $possible_hellip_start );
		}

		return $string . $ellipses;
	}

	/**
	 * Return an array with safe allowed html, to pass to kses functions.
	 *
	 * Usually used with translations, where is a need for html.
	 *
	 * @return array
	 */
	public static function get_plugin_allowed_kses_html() {
		return array(
			'a'      => array(
				'href'   => array(),
				'target' => array(),
				'title'  => array(),
			),
			'b'      => array(),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'span'   => array(
				'class' => array(),
			),
		);
	}
}

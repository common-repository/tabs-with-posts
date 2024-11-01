<?php

namespace TWRP\Utils;

/**
 * Class that is a collection of static methods, that can be used everywhere
 * when a work with color needs to be done.
 */
class Color_Utils {

	/**
	 * Verify if the value is a hex/rgba/rgb string color.
	 *
	 * @param mixed $value
	 * @return bool
	 *
	 * @phan-assert string $value
	 * @psalm-assert-if-true string $value
	 */
	public static function is_color( $value ) {
		if ( ! is_string( $value ) ) {
			return false;
		}

		return static::is_rgba_color( $value ) || static::is_hex_color( $value ) || static::is_rgb_color( $value );
	}

	/**
	 * Verify if the value is a hex color.
	 *
	 * @param string $value
	 * @return bool
	 */
	public static function is_hex_color( $value ) {
		return (bool) preg_match( '/^#([0-9a-f]{3}){1,2}$/i', $value );
	}

	/**
	 * Verify if the value is a rgb color.
	 *
	 * @param string $value
	 * @return bool
	 */
	public static function is_rgb_color( $value ) {
		return (bool) preg_match( '/rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)/i', $value );
	}

	/**
	 * Verify if the value is a rgba color.
	 *
	 * @param string $value
	 * @return bool
	 */
	public static function is_rgba_color( $value ) {
		return (bool) preg_match( '/rgba\((\s*\d+\s*,){3}\s*(0|1)(\.?\d+)?\)/i', $value );
	}

	/**
	 * Increase or decrease the brightness of a color.
	 *
	 * @param string $rgba Color in rgba format to lighten/darken.
	 * @param int $steps To darken use positive integers, to lighten use negative.
	 * @return string|null
	 */
	public static function adjust_brightness( $rgba, $steps ) {
		// Steps should be between -255 and 255. Negative = darker, positive = lighter.
		$steps = max( -255, min( 255, $steps ) );

		$color_values = array();
		preg_match_all( '/(\d+\.*\d*)/', $rgba, $color_values );

		if ( ! isset( $color_values[1] ) ) {
			return null;
		}
		$color_values = $color_values[1];

		if ( count( $color_values ) < 4 ) {
			return null;
		}

		for ( $i = 0; $i < 3; $i++ ) {
			$color_values[ $i ] = intval( $color_values[ $i ] );
			$color_values[ $i ] = max( 0, min( 255, $color_values[ $i ] + $steps ) );
		}

		return 'rgba(' . $color_values[0] . ', ' . $color_values[1] . ', ' . $color_values[2] . ', ' . $color_values[3] . ')';
	}

	/**
	 * Set the opacity of a rgba color.
	 *
	 * @param string $rgba Color in rgba format.
	 * @param int|float $opacity
	 * @return string|null
	 */
	public static function set_opacity( $rgba, $opacity ) {
		if ( $opacity < 0 || $opacity > 1 ) {
			return null;
		}

		$color_values = array();
		preg_match_all( '/(\d+\.*\d*)/', $rgba, $color_values );

		if ( ! isset( $color_values[1] ) ) {
			return null;
		}
		$color_values = $color_values[1];

		if ( count( $color_values ) < 4 ) {
			return null;
		}

		return 'rgba(' . $color_values[0] . ', ' . $color_values[1] . ', ' . $color_values[2] . ', ' . $opacity . ')';
	}

	/**
	 * Get whether the text color should be white or black.
	 *
	 * @param string $rgba_color A rgba color to calculate the text color.
	 * @param string $white_color_rgba The white color to calculate, defaults to 'rgba(256, 256, 256, 1)'.
	 * @param string $black_color_rgba The white color to calculate, defaults to 'rgba(52, 58, 64, 1)' (As in Bootstrap).
	 * @return int|null 0 if black should be used, 1 if white should be used, or
	 * null if cannot be determined, or something is wrong.
	 */
	public static function white_or_black_text( $rgba_color, $white_color_rgba = 'rgba(256, 256, 256, 1)', $black_color_rgba = 'rgba(52, 58, 64, 1)' ) {
		$white_contrast_ratio = self::get_contrast_ratio( $rgba_color, $white_color_rgba );
		$black_contrast_ratio = self::get_contrast_ratio( $rgba_color, $black_color_rgba );

		if ( null === $white_contrast_ratio || null === $black_contrast_ratio ) {
			return null;
		}

		// We add a 0.1 because we prefer white more, since the text white a background color usually goes white.
		if ( $white_contrast_ratio + 0.1 > $black_contrast_ratio ) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Get the contrast ratio between 2 colors.
	 *
	 * @param string $first_color Rgba color string format.
	 * @param string $second_color Rgba color string format.
	 * @return float|null A number indicating the contrast ratio, or null on failure.
	 */
	public static function get_contrast_ratio( $first_color, $second_color ) {
		$first_color_values  = self::get_rgba_values( $first_color );
		$second_color_values = self::get_rgba_values( $second_color );

		if ( null === $first_color_values || null === $second_color_values ) {
			return null;
		}

		// Calc contrast ratio.
		$first_color_contrast = 0.2126 * pow( (int) $first_color_values[0] / 255, 2.2 ) +
		0.7152 * pow( (int) $first_color_values[1] / 255, 2.2 ) +
		0.0722 * pow( (int) $first_color_values[2] / 255, 2.2 );

		$second_color_contrast = 0.2126 * pow( (int) $second_color_values[0] / 255, 2.2 ) +
		0.7152 * pow( (int) $second_color_values[1] / 255, 2.2 ) +
		0.0722 * pow( (int) $second_color_values[2] / 255, 2.2 );

		if ( $first_color_contrast > $second_color_contrast ) {
			$contrast_ratio = ( ( $first_color_contrast + 0.05 ) / ( $second_color_contrast + 0.05 ) );
		} else {
			$contrast_ratio = ( ( $second_color_contrast + 0.05 ) / ( $first_color_contrast + 0.05 ) );
		}

		return $contrast_ratio;
	}

	/**
	 * Convert a rgba color string into an array where each value is the color,
	 * the last(4th) being the opacity.
	 *
	 * @param string $rgba_color A rgba color string.
	 * @return null|array An array with 4 values, or null on failure.
	 */
	protected static function get_rgba_values( $rgba_color ) {
		$color_values = array();
		preg_match_all( '/(\d+\.*\d*)/', $rgba_color, $color_values );

		if ( ! isset( $color_values[1] ) ) {
			return null;
		}
		$color_values = $color_values[1];

		if ( count( $color_values ) < 4 ) {
			return null;
		}

		return $color_values;
	}
}

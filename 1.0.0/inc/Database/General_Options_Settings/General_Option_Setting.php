<?php

namespace TWRP\Database\Settings;

/**
 * Abstract class, that each general setting must extend.
 *
 * Each setting is represented as as a string. There is not a setting
 * represented as boolean, they are represented as strings 'true' and 'false'.
 */
abstract class General_Option_Setting {

	/**
	 * Sanitize the setting.
	 *
	 * By default, the setting will be sanitized to have an option retrieved
	 * from get_possible_options() function. Else you need to implement this
	 * method in a child class.
	 *
	 * @param mixed $value
	 * @return string
	 */
	public function sanitize( $value ) {
		return $this->sanitize_string_choice( $value, $this->get_possible_options(), $this->get_default_value() );
	}

	/**
	 * Get the key name of the setting. The key name will always be the name of
	 * the class, with lower characters.
	 *
	 * @return string
	 */
	final public function get_key_name() {
		$full_class_name    = get_class( $this );
		$separator_position = strrpos( $full_class_name, '\\' );
		if ( false !== $separator_position ) {
			// @phan-suppress-next-line PhanPossiblyFalseTypeArgumentInternal
			return strtolower( substr( $full_class_name, $separator_position + 1 ) );
		}

		return strtolower( $full_class_name );
	}

	/**
	 * Get the default value of a setting.
	 *
	 * @return string
	 */
	abstract public function get_default_value();

	/**
	 * Return an array with each possible choice.
	 *
	 * This is used only on settings that have a limited number of possibilities,
	 * these settings usually need a select control or a radio/checkbox.
	 *
	 * @return array
	 */
	public function get_possible_options() {
		return array();
	}

	/**
	 * Sanitize choices the strings.
	 *
	 * @param mixed $value
	 * @param array $options
	 * @param string $default
	 * @return string
	 */
	protected function sanitize_string_choice( $value, $options, $default ) {
		if ( in_array( $value, $options, true ) ) {
			return $value;
		}

		return $default;
	}
}

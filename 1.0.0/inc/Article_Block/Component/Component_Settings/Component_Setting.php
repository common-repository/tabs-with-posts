<?php

namespace TWRP\Article_Block\Component;

/**
 * Each component setting should implement this interface.
 */
abstract class Component_Setting {

	/**
	 * The name of the setting. Will be used as an array key for storage.
	 *
	 * @return string
	 */
	abstract public function get_key_name();

	/**
	 * Display the component setting.
	 *
	 * @param string $prefix_id The prefix id of the control. Will be merged with
	 *                          the class key name.
	 * @param string $prefix_name The prefix name of the control. Will be merged with
	 *                            the class key name.
	 * @param mixed $value The value of the control.
	 * @return void
	 */
	abstract public function display_setting( $prefix_id, $prefix_name, $value );

	/**
	 * Get the arguments for the control.
	 *
	 * @return array
	 */
	abstract protected function get_control_setting_args();

	/**
	 * Sanitize the setting.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	abstract public function sanitize_setting( $value );

	/**
	 * Create the CSS for a given value.
	 *
	 * @param string|int|float $value
	 * @return string The CSS.
	 */
	abstract public function get_css( $value );

}

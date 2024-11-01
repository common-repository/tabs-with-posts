<?php

namespace TWRP\Query_Generator\Query_Setting;

use TWRP\Utils\Helper_Interfaces\Class_Children_Order;

use RuntimeException;

/**
 * Implements the functions needed to be calling in displaying the settings in
 * the backend, and to create a query setting in general. As the name says, this
 * interface is needed in the backend area of the WordPress website.
 *
 * Each one of this setting will be displayed with a title taken from
 * get_title() function, and display below the HTML in display_setting() function.
 *
 * How to add an option to a specific setting:
 * 1. Each individual setting must have a const in that setting class.
 * 2. Each individual setting must have a default value, else array key undefined notices might be throw.
 * 3. Each setting usually must be sanitized, and also maybe displayed or have a control.
 *
 * @psalm-consistent-constructor The constructor should not have any parameter.
 */
abstract class Query_Setting implements Class_Children_Order {

	/**
	 * The name of the HTML form input and of the array key that stores the option of the query.
	 *
	 * @return string
	 */
	abstract public function get_setting_name();

	/**
	 * The default setting to be retrieved, if user didn't set anything.
	 *
	 * @return array
	 */
	abstract public function get_default_setting();

	/**
	 * Sanitize a variable, to be safe for processing.
	 *
	 * @param mixed $setting
	 * @return array The sanitized variable.
	 */
	abstract public function sanitize_setting( $setting );

	/**
	 * Create and insert the new arguments for the WP_Query.
	 *
	 * The previous query arguments will be modified such that will also contain
	 * the new settings, and will return the new query arguments to be passed
	 * into WP_Query class.
	 *
	 * @throws RuntimeException If a setting cannot implement something.
	 *
	 * @param array $previous_query_args The query arguments before being modified.
	 * @param array $query_settings All query settings, these settings are sanitized.
	 * @return array The new arguments modified.
	 */
	abstract public function add_query_arg( $previous_query_args, $query_settings );
}

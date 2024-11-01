<?php

namespace TWRP\Icons;

/**
 * Each set of icon definitions class must implement this interface.
 *
 * Provides a simple method to retrieve all icons for a specific category.
 */
interface Icon_Definitions {

	/**
	 * Get all registered icons definitions for a specific category.
	 *
	 * @return array<string,array>
	 */
	public static function get_definitions();
}

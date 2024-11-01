<?php

namespace TWRP\Article_Block;

/**
 * Declare static methods that should be implemented in each article block.
 *
 * This interface is used because in PHP 5.6 Strict Standard warning is
 * triggered if same method is abstract and strict.
 */
interface Article_Block_Info {

	/**
	 * Get the Id of the article block.
	 *
	 * This should be unique across all article blocks and should be implemented
	 * in all children, even that is not abstract.
	 *
	 * @return string
	 */
	public static function get_id();

	/**
	 * Get the name of the Article Block. The name should have spaces instead
	 * of "_" and should be something representative.
	 *
	 * This method should be implemented in all children, even that is not
	 * abstract.
	 *
	 * @return string
	 */
	public static function get_name();

	/**
	 * Get the file name. The name must have appended ".php" suffix.
	 *
	 * @return string
	 */
	public static function get_file_name();
}

<?php

namespace TWRP\Database;

/**
 * Interface that a class can implement, to show how the class can clean
 * the database, when is needed.
 */
interface Clean_Database {

	/**
	 * Delete the database entries that were created by this class.
	 *
	 * @return void
	 */
	public static function clean_database();
}

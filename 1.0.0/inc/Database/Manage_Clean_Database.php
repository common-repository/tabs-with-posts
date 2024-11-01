<?php

namespace TWRP\Database;

use TWRP\Utils\Class_Retriever_Utils;
use TWRP\Utils\Directory_Utils;

/**
 * Class used to manage the cleaning of database when needed.
 */
class Manage_Clean_Database {
	public static function delete_all_plugin_database_entries() {
		$plugins          = get_plugins();
		$plugins_filename = array_keys( $plugins );

		$plugins_count  = 0;
		$main_file_name = Directory_Utils::PLUGIN_MAIN_FILE_NAME . '.php';

		foreach ( $plugins_filename as $folder_and_file ) {
			if ( strstr( $folder_and_file, $main_file_name ) !== false ) {
				$plugins_count++;
			}
		}

		if ( $plugins_count > 1 ) {
			return;
		}

		$all_class_names = Class_Retriever_Utils::get_all_clean_database_class_names();
		foreach ( $all_class_names as $class_name ) {
			$class_name::clean_database();
		}
	}
}

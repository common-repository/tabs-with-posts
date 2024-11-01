<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Utils\Helper_Interfaces\Class_Children_Order;

/**
 * Class used to mark external plugins that are available only in premium mode.
 */
abstract class Post_Rating_Plugin_Locked extends Known_Plugin implements Class_Children_Order {

	#region -- Detect if is installed.

	public function is_installed_and_can_be_used() {
		return false;
	}

	#endregion -- Detect if is installed.
}

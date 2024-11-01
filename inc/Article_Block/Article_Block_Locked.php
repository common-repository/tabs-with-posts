<?php

namespace TWRP\Article_Block;

use TWRP\Utils\Helper_Interfaces\Class_Children_Order;

/**
 * Class used by the Article Blocks that needs to show to the user that the
 * style is available only by premium version.
 */
abstract class Article_Block_Locked implements Class_Children_Order, Article_Block_Info {
	final public function __construct() {}
}

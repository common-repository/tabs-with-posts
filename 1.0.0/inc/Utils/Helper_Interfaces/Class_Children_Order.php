<?php

namespace TWRP\Utils\Helper_Interfaces;

/**
 * Interface that can be used in an abstract class, to make the child classes
 * order by a number when retrieving dynamically(via class parent).
 *
 * For example, without this kind of interface, all query settings will be in
 * different order each time a page is visited.
 */
interface Class_Children_Order {

	/**
	 * Get the order in which the class should be retrieved among it's children.
	 *
	 * @return int
	 */
	public static function get_class_order_among_siblings();
}

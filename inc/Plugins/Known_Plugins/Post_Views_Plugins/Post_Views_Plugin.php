<?php

namespace TWRP\Plugins\Known_Plugins;

use TWRP\Utils\Helper_Interfaces\Class_Children_Order;

/**
 * Interface that will tell what methods the plugin wrapper classes should
 * implement.
 *
 * @psalm-consistent-constructor The constructor should not have any parameter.
 */
abstract class Post_Views_Plugin extends Known_Plugin implements Class_Children_Order {

	/**
	 * Get the views for a post. Return false if cannot be retrieved.
	 *
	 * @param int|string $post_id The post Id.
	 * @return int|false
	 */
	abstract public function get_views( $post_id );

	/**
	 * Given an array with WP_Query args with 'orderby' of type array and a
	 * custom orderby key. Return the new WP_Query args that will have the
	 * parameters modified, to retrieve the posts in order of the views.
	 *
	 * @param array $query_args The normal WP_Query args, only that a new key
	 * will appear as a key in 'orderby' parameter.
	 * @return array
	 */
	abstract public function modify_query_arg_if_necessary( $query_args );
}

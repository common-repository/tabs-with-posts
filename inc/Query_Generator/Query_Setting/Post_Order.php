<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Creates the possibility to select the order in which the posts will be
 * displayed.
 *
 * Plugins can implement their order using 'twrp_post_orderby_select_options'
 * and 'twrp_post_order_after_add_query_args' filter.
 */
class Post_Order extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 40;
	}

	/**
	 * The name of the first select to set orderby.
	 */
	const FIRST_ORDERBY_SELECT_NAME = 'first_orderby';

	/**
	 * The name of the second select to set orderby.
	 */
	const SECOND_ORDERBY_SELECT_NAME = 'second_orderby';

	/**
	 * The name of the third select to set orderby.
	 */
	const THIRD_ORDERBY_SELECT_NAME = 'third_orderby';

	/**
	 * The name of the first select to set the ascending or descending order.
	 */
	const FIRST_ORDER_TYPE_SELECT_NAME = 'first_order_type';

	/**
	 * The name of the second select to set the ascending or descending order.
	 */
	const SECOND_ORDER_TYPE_SELECT_NAME = 'second_order_type';

	/**
	 * The name of the third select to set the ascending or descending order.
	 */
	const THIRD_ORDER_TYPE_SELECT_NAME = 'third_order_type';

	public function get_setting_name() {
		return 'post_order';
	}

	/**
	 * Get an array with all orderby select values.
	 *
	 * @return array The array key is the value that should go into WP_Query
	 * arguments parameters and into HTML option value, while the array value
	 * is a text that will be displayed in the front-end select statement.
	 */
	public static function get_orderby_select_options() {
		$select_options = array(
			'not_applied'    => _x( 'Not applied', 'backend', 'tabs-with-posts' ),
			'ID'             => _x( 'Order by post id', 'backend', 'tabs-with-posts' ),
			'author'         => _x( 'Order by author', 'backend', 'tabs-with-posts' ),
			'title'          => _x( 'Order by title', 'backend', 'tabs-with-posts' ),
			'name'           => _x( 'Order by post slug', 'backend', 'tabs-with-posts' ),
			'type'           => _x( 'Order by post type', 'backend', 'tabs-with-posts' ),
			'date'           => _x( 'Order by date', 'backend', 'tabs-with-posts' ),
			'modified'       => _x( 'Order by last modified date', 'backend', 'tabs-with-posts' ),
			'parent'         => _x( 'Order by post/page parent id', 'backend', 'tabs-with-posts' ),
			'rand'           => _x( 'Random order', 'backend', 'tabs-with-posts' ),
			'comment_count'  => _x( 'Order by number of comments', 'backend', 'tabs-with-posts' ),
			'relevance'      => _x( 'Order by searched terms', 'backend', 'tabs-with-posts' ),
			'meta_value'     => _x( 'Order by meta value(alphabetically)', 'backend', 'tabs-with-posts' ), // phpcs:ignore WordPress.DB -- No slow query.
			'meta_value_num' => _x( 'Order by meta value(numerically)', 'backend', 'tabs-with-posts' ),
			'post__in'       => _x( 'Order by included posts(sort them below)', 'backend', 'tabs-with-posts' ),
		);

		/**
		 * Add or remove orderby options in the query settings page.
		 */
		$select_options = apply_filters( 'twrp_post_orderby_select_options', $select_options );

		return $select_options;
	}

	/**
	 * Get some orderby options, that will only show in the first orderby select,
	 * because they aren't implemented by plugins to work in an array(multiple
	 * orderby options).
	 *
	 * @return array
	 */
	public static function get_additional_first_orderby_select_options() {
		$select_options = array();

		/**
		 * Add or remove orderby options that will appear only on the first
		 * orderby selector(meaning that the posts can be ordered only by these
		 * values, and not the others), in the query settings page.
		 */
		$select_options = apply_filters( 'twrp_post_first_orderby_select_options', $select_options );

		return $select_options;
	}

	public function get_default_setting() {
		return array(
			self::FIRST_ORDERBY_SELECT_NAME     => 'not_applied',
			self::SECOND_ORDERBY_SELECT_NAME    => 'not_applied',
			self::THIRD_ORDERBY_SELECT_NAME     => 'not_applied',

			self::FIRST_ORDER_TYPE_SELECT_NAME  => 'DESC',
			self::SECOND_ORDER_TYPE_SELECT_NAME => 'DESC',
			self::THIRD_ORDER_TYPE_SELECT_NAME  => 'DESC',
		);
	}

	public function sanitize_setting( $setting ) {
		$sanitized_setting = $this->get_default_setting();
		$setting           = wp_parse_args( $setting, $this->get_default_setting() );

		$orderby_options       = array_keys( self::get_orderby_select_options() );
		$first_orderby_options = array_keys( self::get_additional_first_orderby_select_options() );

		$order_type_options = array( 'DESC', 'ASC' );

		$orderby_settings_keys = array(
			self::FIRST_ORDERBY_SELECT_NAME,
			self::SECOND_ORDERBY_SELECT_NAME,
			self::THIRD_ORDERBY_SELECT_NAME,
		);

		$order_type_settings_keys = array(
			self::FIRST_ORDER_TYPE_SELECT_NAME,
			self::SECOND_ORDER_TYPE_SELECT_NAME,
			self::THIRD_ORDER_TYPE_SELECT_NAME,
		);

		// Sanitize orderby Settings, check if elements are valid and if one of
		// the element in the array setting equals 'not_applied', then all
		// elements after will also equal 'not_applied'.
		$do_not_apply_orderby = false;
		$foreach_iteration    = 0;
		foreach ( $orderby_settings_keys as $orderby_key ) {
			$foreach_iteration++;
			$is_valid_option = in_array( $setting[ $orderby_key ], $orderby_options, true );

			if ( 1 === $foreach_iteration ) {
				$is_valid_option = $is_valid_option || in_array( $setting[ $orderby_key ], $first_orderby_options, true );
			}

			if ( ! $is_valid_option || $do_not_apply_orderby ) {
				$setting[ $orderby_key ] = 'not_applied';
			}

			if ( ( 'not_applied' === $setting[ $orderby_key ] ) ) {
				$do_not_apply_orderby = true;
			}

			$sanitized_setting[ $orderby_key ] = $setting[ $orderby_key ];
		}

		foreach ( $order_type_settings_keys as $order_type_key ) {
			$is_valid_setting = in_array( $setting[ $order_type_key ], $order_type_options, true );
			if ( ! $is_valid_setting ) {
				$setting[ $order_type_key ] = 'DESC';
			}

			$sanitized_setting[ $order_type_key ] = $setting[ $order_type_key ];
		}

		return $sanitized_setting;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		$orderby  = array();
		$settings = $query_settings[ $this->get_setting_name() ];

		$setting_names = array(
			self::FIRST_ORDERBY_SELECT_NAME  => self::FIRST_ORDER_TYPE_SELECT_NAME,
			self::SECOND_ORDERBY_SELECT_NAME => self::SECOND_ORDER_TYPE_SELECT_NAME,
			self::THIRD_ORDERBY_SELECT_NAME  => self::THIRD_ORDER_TYPE_SELECT_NAME,
		);

		foreach ( $setting_names as $orderby_setting_name => $order_type_name ) {
			if ( 'not_applied' === $settings[ $orderby_setting_name ] ) {
				break;
			}

			$orderby[ $settings[ $orderby_setting_name ] ] = $settings[ $order_type_name ];
		}

		if ( empty( $orderby ) ) {
			return $previous_query_args;
		}

		$previous_query_args['orderby'] = $orderby;

		/**
		 * Filter the query args generated of the post order.
		 */
		$previous_query_args = apply_filters( 'twrp_post_order_after_add_query_args', $previous_query_args, $query_settings );

		return $previous_query_args;
	}
}

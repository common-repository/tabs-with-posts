<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Plugins\Post_Rating;
use TWRP\Plugins\Post_Views;
use TWRP\Query_Generator\Query_Setting\Post_Order;
use TWRP\Query_Generator\Query_Setting\Post_Comments;
use TWRP\Query_Generator\Query_Setting\Post_Date;
use TWRP\Query_Generator\Query_Setting\Query_Name;

/**
 * Class that is used to declare the starting templates for query settings.
 *
 * There are 2 types of functions, one which will return an array with the
 * key as a string representing the array setting name, used in HTML name attr,
 * and one which will return the actual array. First will be used with javascript
 * to automate the selecting of them, and one will be used to insert them directly
 * in the database when plugin is activated and have no query settings displayed.
 *
 * To add a template:
 * 1. Add a const with the name id that you want.
 * 2. Add the option name in get_all_query_posts_templates_options() function.
 * 3. Add a function that sets the template settings.
 *
 * To add a starter template(that will be set as default when plugin is activated).
 * 1. Add the option name in get_all_query_posts_templates_array() function.
 * 2. Add a function that sets the template settings.
 */
class Query_Templates {

	const LAST_POSTS__TEMPLATE_NAME = 'last_posts_template';

	const MOST_COMMENTED__TEMPLATE_NAME = 'most_commented_template';

	const RANDOM_POSTS__TEMPLATE_NAME = 'random_posts_template';

	const THIS_WEEK__TEMPLATE_NAME = 'this_week_template';

	const MOST_VIEWED__TEMPLATE_NAME = 'most_viewed_template';

	const BEST_RATED__TEMPLATE_NAME = 'best_rated_template';

	const MOST_RATED__TEMPLATE_NAME = 'most_rated_template';

	/**
	 * Get an array with the template id as key and the template display name as
	 * value.
	 *
	 * @return array
	 */
	public function get_all_query_posts_templates_options() {
		return array(
			self::LAST_POSTS__TEMPLATE_NAME     => _x( 'Last Posts Template', 'backend', 'tabs-with-posts' ),
			self::MOST_COMMENTED__TEMPLATE_NAME => _x( 'Most Commented Template', 'backend', 'tabs-with-posts' ),
			self::RANDOM_POSTS__TEMPLATE_NAME   => _x( 'Random Posts Template', 'backend', 'tabs-with-posts' ),
			self::THIS_WEEK__TEMPLATE_NAME      => _x( 'This Week Template', 'backend', 'tabs-with-posts' ),
			self::MOST_VIEWED__TEMPLATE_NAME    => _x( 'Most Viewed Template', 'backend', 'tabs-with-posts' ),
			self::BEST_RATED__TEMPLATE_NAME     => _x( 'Best Rated Template', 'backend', 'tabs-with-posts' ),
			self::MOST_RATED__TEMPLATE_NAME     => _x( 'Most Rated Template', 'backend', 'tabs-with-posts' ),
		);
	}

	/**
	 * Get an array with the template id as key and the template settings as a
	 * value.
	 *
	 * @return array
	 */
	public function get_all_query_posts_templates() {
		return array(
			self::LAST_POSTS__TEMPLATE_NAME     => $this->get_last_posts_template_settings(),
			self::MOST_COMMENTED__TEMPLATE_NAME => $this->get_most_commented_template_settings(),
			self::RANDOM_POSTS__TEMPLATE_NAME   => $this->get_random_posts_template_settings(),
			self::THIS_WEEK__TEMPLATE_NAME      => $this->get_this_week_template_settings(),
			self::MOST_VIEWED__TEMPLATE_NAME    => $this->get_most_viewed_template_settings(),
			self::BEST_RATED__TEMPLATE_NAME     => $this->get_best_rated_template_settings(),
			self::MOST_RATED__TEMPLATE_NAME     => $this->get_most_rated_template_settings(),
		);
	}

	public function get_all_query_posts_templates_array() {
		return array(
			$this->get_last_posts_template_settings_array(),
			$this->get_most_commented_template_settings_array(),
			$this->get_random_posts_template_settings_array(),
			$this->get_this_week_template_settings_array(),
			$this->get_most_viewed_template_settings_array(),
			$this->get_best_rated_template_settings_array(),
			$this->get_most_rated_template_settings_array(),
		);
	}

	/**
	 * Get the settings for last posts template.
	 *
	 * @return array
	 */
	public function get_last_posts_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Last Posts', 'backend', 'tabs-with-posts' ),
			$setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => 'date',
			$setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
		);
	}

	/**
	 * Get the settings for last posts template.
	 *
	 * @return array
	 */
	public function get_last_posts_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Last Posts', 'backend', 'tabs-with-posts' ) ),
			$setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
		);
	}

	/**
	 * Get the settings for most commented posts template.
	 *
	 * @return array
	 */
	public function get_most_commented_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class     = new Post_Order();
		$order_setting_prefix = $post_order_class->get_setting_name();

		$post_comments_class     = new Post_Comments();
		$comments_setting_prefix = $post_comments_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Most Commented', 'backend', 'tabs-with-posts' ),
			$order_setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => 'comment_count',
			$order_setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$order_setting_prefix . '[' . $post_order_class::SECOND_ORDERBY_SELECT_NAME . ']' => 'date',
			$order_setting_prefix . '[' . $post_order_class::SECOND_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$comments_setting_prefix . '[' . $post_comments_class::COMMENTS_COMPARATOR_NAME . ']' => 'BE',
			$comments_setting_prefix . '[' . $post_comments_class::COMMENTS_VALUE_NAME . ']' => '1',
		);
	}

	/**
	 * Get the settings for most commented posts template.
	 *
	 * @return array
	 */
	public function get_most_commented_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class     = new Post_Order();
		$order_setting_prefix = $post_order_class->get_setting_name();

		$post_comments_class     = new Post_Comments();
		$comments_setting_prefix = $post_comments_class->get_setting_name();

		return array(
			$query_name_prefix       => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Most Commented', 'backend', 'tabs-with-posts' ) ),
			$order_setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => 'comment_count',
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
				$post_order_class::SECOND_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::SECOND_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
			$comments_setting_prefix => array(
				$post_comments_class::COMMENTS_COMPARATOR_NAME => 'BE',
				$post_comments_class::COMMENTS_VALUE_NAME => '1',
			),
		);
	}

	/**
	 * Get the settings for random posts template.
	 *
	 * @return array
	 */
	public function get_random_posts_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Random Posts', 'backend', 'tabs-with-posts' ),
			$setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => 'rand',
			$setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
		);
	}

	/**
	 * Get the settings for random posts template.
	 *
	 * @return array
	 */
	public function get_random_posts_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Random Posts', 'backend', 'tabs-with-posts' ) ),
			$setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => 'rand',
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
		);
	}

	/**
	 * Get this week posts template.
	 *
	 * @return array
	 */
	public function get_this_week_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class     = new Post_Order();
		$order_setting_prefix = $post_order_class->get_setting_name();

		$post_date_class     = new Post_Date();
		$date_setting_prefix = $post_date_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'This Week', 'backend', 'tabs-with-posts' ),
			$order_setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => 'date',
			$order_setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$date_setting_prefix . '[' . $post_date_class::DATE_TYPE_NAME . ']' => 'LT',
			$date_setting_prefix . '[' . $post_date_class::DATE_LAST_PERIOD_NAME . ']' => 'L7D',
		);
	}

	/**
	 * Get this week posts template.
	 *
	 * @return array
	 */
	public function get_this_week_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class     = new Post_Order();
		$order_setting_prefix = $post_order_class->get_setting_name();

		$post_date_class     = new Post_Date();
		$date_setting_prefix = $post_date_class->get_setting_name();

		return array(
			$query_name_prefix    => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'This Week', 'backend', 'tabs-with-posts' ) ),
			$order_setting_prefix => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
			$date_setting_prefix  => array(
				$post_date_class::DATE_TYPE_NAME        => 'LT',
				$post_date_class::DATE_LAST_PERIOD_NAME => 'L7D',
			),
		);
	}

	/**
	 * Get this most viewed posts template.
	 *
	 * @return array
	 */
	public function get_most_viewed_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Most Viewed', 'backend', 'tabs-with-posts' ),
			$setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => Post_Views::ORDERBY_VIEWS_OPTION_KEY,
			$setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDERBY_SELECT_NAME . ']' => 'date',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
		);
	}

	/**
	 * Get this most viewed posts template.
	 *
	 * @return array
	 */
	public function get_most_viewed_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Most Viewed', 'backend', 'tabs-with-posts' ) ),
			$setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => Post_Views::ORDERBY_VIEWS_OPTION_KEY,
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
				$post_order_class::SECOND_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::SECOND_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
		);
	}

	/**
	 * Get best rated posts template.
	 *
	 * @return array
	 */
	public function get_best_rated_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Best Rated', 'backend', 'tabs-with-posts' ),
			$setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => Post_Rating::ORDERBY_RATING_OPTION_KEY,
			$setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDERBY_SELECT_NAME . ']' => 'date',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
		);
	}

	/**
	 * Get best rated posts template.
	 *
	 * @return array
	 */
	public function get_best_rated_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Best Rated', 'backend', 'tabs-with-posts' ) ),
			$setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => Post_Rating::ORDERBY_RATING_OPTION_KEY,
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
				$post_order_class::SECOND_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::SECOND_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
		);
	}

	/**
	 * Get most rated posts template.
	 *
	 * @return array
	 */
	public function get_most_rated_template_settings() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix . '[' . $query_name_class::QUERY_NAME__SETTING_NAME . ']' => _x( 'Most Rated', 'backend', 'tabs-with-posts' ),
			$setting_prefix . '[' . $post_order_class::FIRST_ORDERBY_SELECT_NAME . ']' => Post_Rating::ORDERBY_RATING_COUNT_OPTION_KEY,
			$setting_prefix . '[' . $post_order_class::FIRST_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDERBY_SELECT_NAME . ']' => 'date',
			$setting_prefix . '[' . $post_order_class::SECOND_ORDER_TYPE_SELECT_NAME . ']' => 'DESC',
		);
	}

	/**
	 * Get most rated posts template.
	 *
	 * @return array
	 */
	public function get_most_rated_template_settings_array() {
		$query_name_class  = new Query_Name();
		$query_name_prefix = $query_name_class->get_setting_name();

		$post_order_class = new Post_Order();
		$setting_prefix   = $post_order_class->get_setting_name();

		return array(
			$query_name_prefix => array( $query_name_class::QUERY_NAME__SETTING_NAME => _x( 'Most Rated', 'backend', 'tabs-with-posts' ) ),
			$setting_prefix    => array(
				$post_order_class::FIRST_ORDERBY_SELECT_NAME => Post_Rating::ORDERBY_RATING_COUNT_OPTION_KEY,
				$post_order_class::FIRST_ORDER_TYPE_SELECT_NAME => 'DESC',
				$post_order_class::SECOND_ORDERBY_SELECT_NAME => 'date',
				$post_order_class::SECOND_ORDER_TYPE_SELECT_NAME => 'DESC',
			),
		);
	}

}

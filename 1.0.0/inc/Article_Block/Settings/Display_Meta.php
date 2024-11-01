<?php

namespace TWRP\Article_Block\Settings;

use TWRP\Admin\Widget_Control\Select_Control;
use TWRP\Plugins\Post_Rating;
use TWRP\Plugins\Post_Views;
use TWRP\Utils\Widget_Utils;

/**
 * Class used to create a setting that will manage to display a meta of choice.
 */
class Display_Meta extends Artblock_Setting {

	/**
	 * Redefine the current setting as a string variable for static analyzers.
	 *
	 * @var string
	 */
	protected $current_setting;

	/**
	 * A number representing the meta number, usually in which order is
	 * displayed.
	 *
	 * @var int
	 */
	protected $setting_instance;

	/**
	 * All possible options that this setting can have.
	 *
	 * @var array
	 */
	protected $possible_options;

	const DISPLAY_AUTHOR_VALUE = 'author';

	const DISPLAY_DATE_VALUE = 'date';

	const DISPLAY_VIEWS_VALUE = 'views';

	const DISPLAY_RATING_VALUE = 'rating';

	const DISPLAY_RATING_AND_COUNT_VALUE = 'rating_and_count';

	const DISPLAY_COMMENTS_VALUE = 'comments';

	const DISPLAY_CATEGORY_VALUE = 'category';

	/**
	 * Get the values for all the available meta.
	 *
	 * @return array
	 */
	public function get_all_meta() {
		return array(
			self::DISPLAY_AUTHOR_VALUE,
			self::DISPLAY_DATE_VALUE,
			self::DISPLAY_VIEWS_VALUE,
			self::DISPLAY_RATING_VALUE,
			self::DISPLAY_RATING_AND_COUNT_VALUE,
			self::DISPLAY_COMMENTS_VALUE,
			self::DISPLAY_CATEGORY_VALUE,
		);
	}

	/**
	 * Get the values for all the short meta(that take a small space).
	 *
	 * @return array
	 */
	public function get_short_meta() {
		return array(
			self::DISPLAY_VIEWS_VALUE,
			self::DISPLAY_RATING_VALUE,
			self::DISPLAY_RATING_AND_COUNT_VALUE,
			self::DISPLAY_COMMENTS_VALUE,
		);
	}

	/**
	 * Get the values for all the short meta(that take a long space).
	 *
	 * @return array
	 */
	public function get_long_meta() {
		return array(
			self::DISPLAY_AUTHOR_VALUE,
			self::DISPLAY_DATE_VALUE,
			self::DISPLAY_CATEGORY_VALUE,
		);
	}

	/**
	 * Construct the settings class.
	 *
	 * @param int $widget_id
	 * @param int $query_id
	 * @param array $settings
	 * @param array $additional_attrs
	 */
	public function __construct( $widget_id, $query_id, $settings, $additional_attrs ) {
		// The setting_instance needs to be set before calling parent constructor.
		if ( is_int( $additional_attrs['instance'] ) ) {
			$this->setting_instance = $additional_attrs['instance'];
		} else {
			$this->setting_instance = 1;
		}

		$possible_values = array();
		if ( empty( $additional_attrs['meta'] ) || 'all' === $additional_attrs['meta'] ) {
			$possible_values = $this->get_all_meta();
		} elseif ( 'short' === $additional_attrs['meta'] ) {
			$possible_values = $this->get_short_meta();
		} elseif ( 'long' === $additional_attrs['meta'] ) {
			$possible_values = $this->get_long_meta();
		} elseif ( is_array( $additional_attrs['meta'] ) ) {
			$possible_values = $additional_attrs['meta'];
		}
		$possible_values        = array_merge( array( 'none' ), $possible_values );
		$possible_values        = $this->get_possible_values_display_options( $possible_values );
		$this->possible_options = $possible_values;

		parent::__construct( $widget_id, $query_id, $settings, $additional_attrs );
	}

	public function display_setting() {
		$id   = Widget_Utils::get_field_id( $this->widget_id, $this->query_id, $this->get_setting_name() );
		$name = Widget_Utils::get_field_name( $this->widget_id, $this->query_id, $this->get_setting_name() );

		Select_Control::display_setting( $id, $name, $this->current_setting, $this->get_setting_args() );
	}

	public function sanitize_setting() {
		return Select_Control::sanitize_setting( $this->current_setting, $this->get_setting_args() );
	}

	public function get_setting_name() {
		return 'display_meta_' . $this->setting_instance;
	}

	/**
	 * Get the arguments for the Widget Setting Control.
	 *
	 * @return array
	 */
	public function get_setting_args() {
		$options = $this->possible_options;
		$number  = '';
		if ( $this->setting_instance > 0 ) {
			$number = '(' . $this->setting_instance . ') ';
		}

		$not_installed_text = _x( '(Not Installed)', 'backend', 'tabs-with-posts' );

		if ( isset( $options['views'] ) && false === Post_Views::get_plugin_to_use() ) {
			$options['views'] = $options['views'] . ' ' . $not_installed_text;
		}

		if ( isset( $options['rating'] ) && false === Post_Rating::get_plugin_to_use() ) {
			$options['rating'] = $options['rating'] . ' ' . $not_installed_text;
		}

		if ( isset( $options['rating_and_count'] ) && false === Post_Rating::get_plugin_to_use() ) {
			$options['rating_and_count'] = $options['rating_and_count'] . ' ' . $not_installed_text;
		}

		return array(
			'default'                => $this->get_default_value(),
			'options'                => $options,
			'before'                 => $number . _x( 'Meta to display:', 'backend', 'tabs-with-posts' ),
			'control_class_modifier' => 'display-meta',
		);
	}

	public function get_default_value() {
		return 'none';
	}

	/**
	 * Create the text for selecting the option in the backend.
	 *
	 * @param array $possible_values
	 * @return array
	 */
	public function get_possible_values_display_options( $possible_values ) {
		$new_possible_values = array();

		foreach ( $possible_values as $value ) {
			$display_value = '';
			if ( 'none' === $value ) {
				$display_value = _x( 'None', 'backend', 'tabs-with-posts' );
			} elseif ( 'author' === $value ) {
				$display_value = _x( 'Author', 'backend', 'tabs-with-posts' );
			} elseif ( 'date' === $value ) {
				$display_value = _x( 'Date', 'backend', 'tabs-with-posts' );
			} elseif ( 'views' === $value ) {
				$display_value = _x( 'Views', 'backend', 'tabs-with-posts' );
			} elseif ( 'rating' === $value ) {
				$display_value = _x( 'Rating', 'backend', 'tabs-with-posts' );
			} elseif ( 'rating_and_count' === $value ) {
				$display_value = _x( 'Rating and Count', 'backend', 'tabs-with-posts' );
			} elseif ( 'comments' === $value ) {
				$display_value = _x( 'Number of Comments', 'backend', 'tabs-with-posts' );
			} elseif ( 'category' === $value ) {
				$display_value = _x( 'Main Category', 'backend', 'tabs-with-posts' );
			}

			$new_possible_values[ $value ] = $display_value;
		}

		return $new_possible_values;
	}

}

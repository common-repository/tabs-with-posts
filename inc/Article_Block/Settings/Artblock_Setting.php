<?php

namespace TWRP\Article_Block\Settings;

/**
 * Each Article Block setting must extend this class.
 *
 * An article block setting is a simple setting that can be more common or
 * unique. Various article blocks can include same settings.
 *
 * Example of settings:
 * - Display or not display the post thumbnail.
 * - Display or not display the post comments.
 */
abstract class Artblock_Setting {

	/**
	 * The widget Id of this setting.
	 *
	 * @var int
	 */
	protected $widget_id;

	/**
	 * The query Id of this setting.
	 *
	 * @var int
	 */
	protected $query_id;

	/**
	 * The current setting.
	 *
	 * @var array|string
	 */
	protected $current_setting;

	/**
	 * Additional attributes for the setting.
	 *
	 * @var array
	 */
	protected $additional_attrs;

	/**
	 * Construct the settings class.
	 *
	 * @param int $widget_id
	 * @param int $query_id
	 * @param array $settings
	 * @param array $additional_attrs
	 */
	public function __construct( $widget_id, $query_id, $settings, $additional_attrs = array() ) {
		$this->widget_id        = $widget_id;
		$this->query_id         = $query_id;
		$this->additional_attrs = $additional_attrs;

		if ( isset( $settings[ $this->get_setting_name() ] ) ) {
			$this->current_setting = $settings[ $this->get_setting_name() ];
		} else {
			$this->current_setting = $this->get_default_value();
		}
	}

	/**
	 * Display the widget control for the setting.
	 *
	 * @return void
	 */
	abstract public function display_setting();

	/**
	 * Get the sanitized setting.
	 *
	 * @return string|array
	 */
	abstract public function sanitize_setting();

	/**
	 * Get the setting name. An unique name among the other settings. Used to
	 * generate the name attr in HTML setting control.
	 *
	 * @return string
	 */
	abstract public function get_setting_name();

	/**
	 * Get the default value of the setting.
	 *
	 * @return string|array
	 */
	abstract public function get_default_value();

}

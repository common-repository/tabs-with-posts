<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Admin\Tabs\Queries_Tab;
use TWRP\Query_Generator\Query_Setting\Query_Setting;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;
use TWRP\Utils\Helper_Interfaces\Class_Children_Order;

/**
 * Used to display a control for a query setting.
 */
abstract class Query_Setting_Display implements Class_Children_Order {

	use BEM_Class_Naming_Trait;

	/**
	 * Initialize the class. The constructor must not take any parameter.
	 *
	 * @phan-suppress PhanEmptyPublicMethod
	 */
	final public function __construct() {
		// Do nothing.
	}

	/**
	 * Return the query setting class corresponding to this controller.
	 *
	 * @return Query_Setting
	 */
	abstract protected function get_setting_class();

	/**
	 * Get the setting name(used as key to store in database) for this setting.
	 *
	 * @return string
	 */
	public function get_setting_name() {
		$setting_class = $this->get_setting_class();
		return $setting_class->get_setting_name();
	}

	/**
	 * Get the default setting.
	 *
	 * @return array
	 */
	public function get_default_setting() {
		$setting_class = $this->get_setting_class();
		return $setting_class->get_default_setting();
	}

	/**
	 * The title of the setting accordion.
	 *
	 * @return string
	 */
	abstract public function get_title();

	/**
	 * Whether or not when displaying the setting in the backend only the title
	 * is shown and the setting HTML is hidden(return false), or both are
	 * shown(return true).
	 *
	 * @return bool|'auto'
	 */
	public function setting_is_collapsed() {
		return 'auto';
	}

	/**
	 * Get the setting submitted from the form. The setting is sanitized and
	 * ready to use.
	 *
	 * @return array
	 */
	public function get_submitted_sanitized_setting() {
		$setting_class = $this->get_setting_class();
		if ( isset( $_POST[ $setting_class->get_setting_name() ] ) ) { // phpcs:ignore -- Nonce verified
			// phpcs:ignore -- Nonce verified and the setting is sanitized.
			return $setting_class->sanitize_setting( wp_unslash( $_POST[ $setting_class->get_setting_name() ] ) );
		}

		return $setting_class->get_default_setting();
	}

	/**
	 * Display the backend HTML for the setting.
	 *
	 * @param mixed $current_setting An array filled with only the settings that
	 * this class work with. The settings are sanitized.
	 *
	 * @return void
	 */
	abstract public function display_setting( $current_setting );

	/**
	 * Echo the query setting class for paragraphs.
	 *
	 * @return void
	 */
	protected function query_setting_paragraph_class() {
		echo esc_attr( $this->get_query_setting_paragraph_class() );
	}

	/**
	 * Get the query setting class for paragraphs.
	 *
	 * @return string
	 */
	protected function get_query_setting_paragraph_class() {
		$query_tab = new Queries_Tab();
		return $query_tab->get_query_setting_paragraph_class();
	}

	/**
	 * Echo the query setting class for line checkboxes.
	 *
	 * @return void
	 */
	protected function query_setting_checkbox_line_class() {
		$query_tab = new Queries_Tab();
		echo esc_attr( $query_tab->query_setting_checkbox_line_class() );
	}

}

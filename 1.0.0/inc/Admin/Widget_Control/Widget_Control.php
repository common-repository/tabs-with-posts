<?php

namespace TWRP\Admin\Widget_Control;

/**
 * Interface that each control implemented should extend.
 */
interface Widget_Control {

	/**
	 * Display a widget control field.
	 *
	 * @param string $id
	 * @param string $name
	 * @param mixed $value
	 * @param array $args
	 * @return void
	 */
	public static function display_setting( $id, $name, $value, $args );

	/**
	 * Sanitize the number input field.
	 *
	 * @param mixed $setting
	 * @param array $args
	 * @return mixed
	 */
	public static function sanitize_setting( $setting, $args );
}

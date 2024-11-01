<?php

namespace TWRP\Admin\Widget_Control;

/**
 * Class that implements the control to display the setting for a color.
 */
class Color_Control implements Widget_Control {

	/**
	 * Display a widget control field.
	 *
	 * @param string $id
	 * @param string $name
	 * @param mixed $value
	 * @param array $args
	 * @return void
	 */
	public static function display_setting( $id, $name, $value, $args ) {
		$default_args = array(
			'default' => '',
			'before'  => '',
			'after'   => '',
		);
		$args         = wp_parse_args( $args, $default_args );

		$value = ( isset( $value ) && is_string( $value ) && self::is_color( $value ) ) ? $value : $args['default'];
		?>
		<div class="twrpb-widget-form__paragraph twrpb-widget-form__paragraph-color-control">
			<?php if ( $args['before'] ) : ?>
				<span class="twrpb-widget-form__color-label-before" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['before']; // phpcs:ignore -- No XSS ?>
				</span>
			<?php endif; ?>

			<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<div class="twrpb-color-picker"></div>

			<?php if ( $args['after'] ) : ?>
				<span class="twrpb-widget-form__color-label-after" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['after']; // phpcs:ignore -- No XSS ?>
				</span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Sanitize the number input field.
	 *
	 * @param mixed $setting
	 * @param array $args
	 * @return mixed
	 */
	public static function sanitize_setting( $setting, $args ) {
		if ( ! is_string( $setting ) ) {
			return '';
		}

		if ( self::is_color( $setting ) ) {
			return $setting;
		}

		return '';
	}

	/**
	 * Checks whether or not the given string is a CSS valid color.
	 *
	 * @param string $setting
	 * @return bool
	 */
	public static function is_color( $setting ) {
		$hex_pattern  = '/^#([a-f0-9]{6}|[a-f0-9]{3})\b$/i';
		$rgb_pattern  = '/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/i';
		$rgba_pattern = '/^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d*(?:\.\d+)?)\)$/i';

		if ( preg_match( $rgba_pattern, $setting ) || preg_match( $rgb_pattern, $setting ) || preg_match( $hex_pattern, $setting ) ) {
			return true;
		}

		return false;
	}
}

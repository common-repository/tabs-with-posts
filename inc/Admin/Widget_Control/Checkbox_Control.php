<?php

namespace TWRP\Admin\Widget_Control;

/**
 * Class that implements the control to display the widget setting for a
 * checkbox.
 */
class Checkbox_Control implements Widget_Control {

	/**
	 * Display a checkbox field.
	 *
	 * @param string $id
	 * @param string $name
	 * @param string|null $value
	 * @param array{default?:string,value?:string,before?:string,after?:string} $args
	 * @return void
	 */
	public static function display_setting( $id, $name, $value, $args ) {
		$default_args = array(
			'default'  => '',
			'value'    => '1',
			'before'   => '',
			'after'    => '',
			'disabled' => false,
		);
		$args         = wp_parse_args( $args, $default_args );

		$disabled = '';
		if ( $args['disabled'] ) {
			$disabled = 'disabled';
		}

		$value = ( isset( $value ) && is_string( $value ) ) ? $value : $args['default'];
		?>
		<div class="twrpb-widget-form__paragraph twrpb-widget-form__paragraph-checkbox-control">
			<?php if ( $args['before'] ) : ?>
				<label class="twrpb-widget-form__checkbox-label-before" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['before']; // phpcs:ignore -- No XSS. ?>
				</label>
			<?php endif; ?>

			<input type='hidden' value='' name="<?php echo esc_attr( $name ); ?>">
			<input
				id="<?php echo esc_attr( $id ); ?>"
				type="checkbox"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $args['value'] ); ?>"
				<?php checked( $value, $args['value'] ); ?>
				<?php echo esc_attr( $disabled ); ?>
			>

			<?php if ( $args['after'] ) : ?>
				<label class="twrpb-widget-form__checkbox-label-after" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['after']; // phpcs:ignore -- No XSS. ?>
				</label>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Sanitize the checkbox input field.
	 *
	 * @param mixed $setting
	 * @param array{default?:string,value?:string} $args
	 * @return string
	 */
	public static function sanitize_setting( $setting, $args ) {
		$default_args = array(
			'default' => '',
			'value'   => '1',
		);
		$args         = wp_parse_args( $args, $default_args );

		if ( $setting === $args['value'] || '' === $setting ) {
			return $setting;
		}

		return $args['default'];
	}
}

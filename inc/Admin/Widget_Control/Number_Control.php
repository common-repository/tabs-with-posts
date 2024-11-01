<?php

namespace TWRP\Admin\Widget_Control;

/**
 * Class that implements the control to display the setting for a number.
 */
class Number_Control implements Widget_Control {

	/**
	 * Display a number input field.
	 *
	 * @param string $id
	 * @param string $name
	 * @param int|float|string|null $value
	 * @param array{default?:''|int|float,min?:''|int|float,max?:''|int|float,step?:''|int|float,before?:string,after?:string,string:string} $args
	 * @return void
	 */
	public static function display_setting( $id, $name, $value, $args ) {
		$default_args = array(
			'default' => '',
			'min'     => '',
			'max'     => '',
			'step'    => '',
			'before'  => '',
			'after'   => '',
		);
		$args         = wp_parse_args( $args, $default_args );

		$value = isset( $value ) && is_numeric( $value ) ? $value : $args['default'];
		?>
		<div class="twrpb-widget-form__paragraph twrpb-widget-form__paragraph-number-control">
			<?php if ( $args['before'] ) : ?>
				<span class="twrpb-widget-form__number-label-before" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['before']; // phpcs:ignore -- No XSS. ?>
				</span>
			<?php endif; ?>

			<input id="<?php echo esc_attr( $id ); ?>"
				class="twrpb-widget-form__number-control"
				name="<?php echo esc_attr( $name ); ?>"
				type="number"
				step="<?php echo esc_attr( $args['step'] ); ?>"
				max="<?php echo esc_attr( $args['max'] ); ?>"
				min="<?php echo esc_attr( $args['min'] ); ?>"
				value="<?php echo esc_attr( (string) $value ); ?>"
			/>

			<?php if ( $args['after'] ) : ?>
				<span class="twrpb-widget-form__number-label-after" for="<?php echo esc_attr( $id ); ?>">
					<?php echo $args['after']; // phpcs:ignore -- No XSS. ?>
				</span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Sanitize the number input field.
	 *
	 * @param mixed $setting
	 * @param array{default?:''|int|float,min?:''|int|float,max?:''|int|float} $args
	 * @return float|int|''
	 */
	public static function sanitize_setting( $setting, $args ) {
		$default_args = array(
			'default' => '',
			'min'     => '',
			'max'     => '',
		);
		$args         = wp_parse_args( $args, $default_args );

		if ( ! is_numeric( $setting ) ) {
			return $args['default'];
		}

		if ( '' !== $args['min'] && $setting < $args['min'] ) {
			return $args['default'];
		}

		if ( '' !== $args['max'] && $setting > $args['max'] ) {
			return $args['default'];
		}

		return $setting;
	}
}

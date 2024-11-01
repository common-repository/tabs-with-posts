<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Meta_Setting;

/**
 * Used to display the advanced arguments query setting control.
 */
class Meta_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 130;
	}

	/**
	 * Get the main setting class.
	 *
	 * @return Meta_Setting
	 */
	protected function get_setting_class() {
		return new Meta_Setting();
	}

	public function get_title() {
		return _x( 'Meta key', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$meta_class           = $this->get_setting_class();
		$comparators          = $meta_class->get_meta_key_comparators();
		$meta_key_name        = $meta_class->get_setting_name() . '[' . Meta_Setting::META_KEY_NAME__SETTING_NAME . ']';
		$meta_is_applied_name = $meta_class->get_setting_name() . '[' . Meta_Setting::META_IS_APPLIED__SETTING_NAME . ']';

		$meta_apply_value             = $current_setting[ Meta_Setting::META_IS_APPLIED__SETTING_NAME ];
		$additional_meta_hidden_class = ' twrpb-hidden';
		if ( 'NA' !== $meta_apply_value ) {
			$additional_meta_hidden_class = '';
		}

		$meta_key_value = $current_setting[ Meta_Setting::META_KEY_NAME__SETTING_NAME ];

		$meta_compare_name  = $meta_class->get_setting_name() . '[' . Meta_Setting::META_KEY_COMPARATOR__SETTING_NAME . ']';
		$meta_compare_value = $current_setting[ Meta_Setting::META_KEY_COMPARATOR__SETTING_NAME ];

		$meta_value_name  = $meta_class->get_setting_name() . '[' . Meta_Setting::META_KEY_VALUE__SETTING_NAME . ']';
		$meta_value_value = $current_setting[ Meta_Setting::META_KEY_VALUE__SETTING_NAME ];

		$additional_value_hidden_class = '';
		if ( 'EXISTS' === $meta_compare_value || 'NOT EXISTS' === $meta_compare_value ) {
			$additional_value_hidden_class = ' twrpb-hidden';
		}

		$name_placeholder  = _x( 'Meta Name', 'backend', 'tabs-with-posts' );
		$value_placeholder = _x( 'Meta Value', 'backend', 'tabs-with-posts' );
		?>
		<div class="<?php $this->bem_class(); ?>">
			<select id="<?php $this->bem_class( 'js-apply-meta-select' ); ?>" class="<?php $this->bem_class( 'apply-meta-select' ); ?> <?php $this->query_setting_paragraph_class(); ?>" name="<?php echo esc_attr( $meta_is_applied_name ); ?>">
				<option value="NA" <?php selected( $meta_apply_value, 'NA' ); ?>><?php echo esc_html_x( 'Not Applied', 'backend', 'tabs-with-posts' ); ?></option>
				<option value="A" <?php selected( $meta_apply_value, 'A' ); ?>><?php echo esc_html_x( 'Apply Meta', 'backend', 'tabs-with-posts' ); ?></option>
			</select>

			<div id="<?php $this->bem_class( 'js-setting-wrapper' ); ?>" class="<?php $this->bem_class( 'setting-wrapper' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $additional_meta_hidden_class ); ?>">
				<div class="<?php $this->bem_class( 'input-group' ); ?>">
					<label for="<?php $this->bem_class( 'meta-key' ); ?>" class="<?php $this->bem_class( 'input-label' ); ?>">
						<?php echo esc_html_x( 'Meta Name:', 'backend', 'tabs-with-posts' ); ?>
					</label>
					<input id="<?php $this->bem_class( 'meta-key' ); ?>" type="text" placeholder="<?php echo esc_attr( $name_placeholder ); ?>" name="<?php echo esc_attr( $meta_key_name ); ?>" value="<?php echo esc_attr( $meta_key_value ); ?>"/>
				</div>

				<div class="<?php $this->bem_class( 'input-group' ); ?>">
					<label for="<?php $this->bem_class( 'js-meta-type' ); ?>" class="<?php $this->bem_class( 'input-label' ); ?>">
						<?php echo esc_html_x( 'Meta Comparator:', 'backend', 'tabs-with-posts' ); ?>
					</label>
					<select id="<?php $this->bem_class( 'js-meta-type' ); ?>" name="<?php echo esc_attr( $meta_compare_name ); ?>">
						<?php foreach ( $comparators as $value => $display ) : ?>
							<option
								value="<?php echo esc_attr( $value ); ?>"
								<?php selected( $value, $meta_compare_value ); ?>
							>
								<?php echo esc_html( $display ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div id="<?php $this->bem_class( 'js-meta-value-group' ); ?>" class="<?php $this->bem_class( 'input-group' ); ?> <?php echo esc_attr( $additional_value_hidden_class ); ?>">
					<label for="<?php $this->bem_class( 'js-meta-value' ); ?>" class="<?php $this->bem_class( 'input-label' ); ?>">
						<?php echo esc_html_x( 'Meta Value:', 'backend', 'tabs-with-posts' ); ?>
					</label>
					<input id="<?php $this->bem_class( 'js-meta-value' ); ?>" class="<?php $this->bem_class( 'meta-value' ); ?>" placeholder="<?php echo esc_attr( $value_placeholder ); ?>" type="text" name="<?php echo esc_attr( $meta_value_name ); ?>" value="<?php echo esc_attr( $meta_value_value ); ?>"/>
				</div>
			</div>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-meta-setting';
	}

}

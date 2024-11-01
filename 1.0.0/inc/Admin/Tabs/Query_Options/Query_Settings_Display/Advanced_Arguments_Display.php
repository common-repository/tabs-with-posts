<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Admin\Helpers\Remember_Note;
use TWRP\Query_Generator\Query_Setting\Advanced_Arguments;

/**
 * Used to display the advanced arguments query setting control.
 */
class Advanced_Arguments_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 1000;
	}

	protected function get_setting_class() {
		return new Advanced_Arguments();
	}

	public function get_title() {
		return _x( 'Advanced query settings', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$arguments_settings_class = $this->get_setting_class();
		$selector_name            = $arguments_settings_class->get_setting_name() . '[' . Advanced_Arguments::IS_APPLIED__SETTING_NAME . ']';
		$textarea_name            = $arguments_settings_class->get_setting_name() . '[' . Advanced_Arguments::CUSTOM_ARGS__SETTING_NAME . ']';

		$advanced_args  = $current_setting[ Advanced_Arguments::CUSTOM_ARGS__SETTING_NAME ];
		$selector_value = $current_setting[ Advanced_Arguments::IS_APPLIED__SETTING_NAME ];

		$additional_setting_hidden_class = ' twrpb-hidden';
		if ( 'not_apply' !== $selector_value ) {
			$additional_setting_hidden_class = '';
		}

		?>
		<div class="<?php $this->bem_class(); ?>">
			<div class="<?php $this->query_setting_paragraph_class(); ?>">
				<select
					id="<?php $this->bem_class( 'is-applied-selector' ); ?>"
					class="<?php $this->bem_class( 'is-applied-selector' ); ?>"
					name="<?php echo esc_attr( $selector_name ); ?>"  rows="10"
				>
					<option value="not_apply" <?php selected( $selector_value, 'not_apply' ); ?>>
						<?php echo esc_html_x( 'Not applied', 'backend', 'tabs-with-posts' ); ?>
					</option>

					<option value="apply" <?php selected( $selector_value, 'apply' ); ?>>
						<?php echo esc_html_x( 'Apply settings', 'backend', 'tabs-with-posts' ); ?>
					</option>
				</select>
			</div>

			<div id="<?php $this->bem_class( 'textarea-wrapper' ); ?>" class="<?php $this->query_setting_paragraph_class(); ?> twrpb-codemirror-material-dark<?php echo esc_attr( $additional_setting_hidden_class ); ?>">
				<?php
				$remember_note = new Remember_Note( Remember_Note::NOTE__ADVANCED_ARGS_NOTE );
				$remember_note->display_note( $this->get_query_setting_paragraph_class() );
				?>
				<textarea
					id="<?php $this->bem_class( 'textarea' ); ?>"
					class="<?php $this->bem_class( 'textarea' ); ?>"
					name="<?php echo esc_attr( $textarea_name ); ?>"  rows="10"
				><?php echo esc_html( $advanced_args ); ?></textarea>
				<?php
				$warning_json_invalid = new Remember_Note( Remember_Note::NOTE__INVALID_JSON_WARNING, 'warning' );
				$warning_json_invalid->display_note( $this->get_query_setting_paragraph_class() );
				?>
			</div>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-advanced-settings';
	}

}

<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Password_Protected;

/**
 * Used to display the password protected query setting control.
 */
class Password_Protected_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 120;
	}

	protected function get_setting_class() {
		return new Password_Protected();
	}

	public function get_title() {
		return _x( 'Filter by password', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$setting_class   = $this->get_setting_class();
		$select_name     = $setting_class->get_setting_name() . '[' . Password_Protected::PASSWORD_PROTECTED__SETTING_NAME . ']';
		$current_setting = $current_setting[ Password_Protected::PASSWORD_PROTECTED__SETTING_NAME ];

		$not_applied_text  = _x( 'Not Applied (Posts with and without password)', 'backend', 'tabs-with-posts' );
		$has_password_text = _x( 'Only posts with password', 'backend', 'tabs-with-posts' );
		$no_password_text  = _x( 'Only posts without password', 'backend', 'tabs-with-posts' );

		?>
		<div class="<?php $this->bem_class(); ?>">
			<div class="<?php $this->query_setting_paragraph_class(); ?>">
				<select name="<?php echo esc_attr( $select_name ); ?>" class="<?php $this->bem_class( 'selector' ); ?>">
					<option value="not_applied" <?php selected( 'not_applied', $current_setting ); ?>>
						<?php echo esc_html( $not_applied_text ); ?>
					</option>
					<option value="has_password" <?php selected( 'has_password', $current_setting ); ?>>
						<?php echo esc_html( $has_password_text ); ?>
					</option>
					<option value="no_password" <?php selected( 'no_password', $current_setting ); ?>>
						<?php echo esc_html( $no_password_text ); ?>
					</option>
				</select>
			</div>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-password-settings';
	}
}

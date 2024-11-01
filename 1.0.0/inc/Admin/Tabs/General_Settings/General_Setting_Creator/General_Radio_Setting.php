<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create a radio setting, in the general settings tab.
 *
 * The "General" term used here does NOT mean as the "default" or in general, it
 * means that is primary used for the "General Settings" interface(so contrary
 * to what is to be expected), where a user could change the main settings.
 */
class General_Radio_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		?>
		<div class="<?php $this->bem_class( 'checkboxes' ); ?>">
			<?php foreach ( $this->options as $option_value => $text ) : ?>
				<?php
					$radio_id = $this->get_settings_attr_id( $option_value );
					$checked  = ( $option_value === $this->value ? ' checked' : '' );
				?>
				<span class="<?php $this->bem_class( 'selection' ); ?>">
					<input
						id="<?php echo esc_attr( $radio_id ); ?>"
						type="radio"
						name="<?php echo esc_attr( $this->name ); ?>"
						value="<?php echo esc_attr( $option_value ); ?>"
						<?php echo esc_attr( $checked ); ?>
					>

					<label for="<?php echo esc_attr( $radio_id ); ?>">
						<?php echo $text; // phpcs:ignore -- No XSS ?>
					</label>
				</span>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-radio';
	}

}

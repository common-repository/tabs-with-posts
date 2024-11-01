<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create a number setting, in the general settings tab.
 *
 * The "General" term used here does NOT mean as the "default" or in general, it
 * means that is primary used for the "General Settings" interface(so contrary
 * to what is to be expected), where a user could change the main settings.
 */
class General_Number_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		?>
		<div class="<?php $this->bem_class( 'wrapper' ); ?>">
			<?php if ( ! empty( $this->all_args['before'] ) && is_string( $this->all_args['before'] ) ) : ?>
				<label for="<?php echo esc_attr( $this->get_settings_attr_id() ); ?>" class="<?php $this->bem_class( 'before-input' ); ?>">
					<?php echo esc_html( $this->all_args['before'] ); ?>
				</label>
			<?php endif; ?>

			<input
				id="<?php echo esc_attr( $this->get_settings_attr_id() ); ?>"
				type="number"
				name="<?php echo esc_attr( $this->name ); ?>"
				value="<?php echo esc_attr( $this->value ); ?>"
				<?php $this->display_input_attributes(); ?>
			/>

			<?php if ( ! empty( $this->all_args['after'] ) && is_string( $this->all_args['after'] ) ) : ?>
				<label for="<?php echo esc_attr( $this->get_settings_attr_id() ); ?>" class="<?php $this->bem_class( 'after-input' ); ?>">
					<?php echo esc_html( $this->all_args['after'] ); ?>
				</label>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-number';
	}
}

<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create a text setting, in the general settings tab.
 *
 * The "General" term used here does NOT mean as the "default" or in general, it
 * means that is primary used for the "General Settings" interface(so contrary
 * to what is to be expected), where a user could change the main settings.
 */
class General_Text_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		?>
		<div class="<?php $this->bem_class( 'wrapper' ); ?>">
			<input
				id="<?php echo esc_attr( $this->get_settings_attr_id() ); ?>"
				type="text"
				name="<?php echo esc_attr( $this->name ); ?>"
				value="<?php echo esc_attr( $this->value ); ?>"
				<?php $this->display_input_attributes(); ?>
			/>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-text';
	}
}

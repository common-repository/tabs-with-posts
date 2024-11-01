<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create a color setting, in the general settings tab.
 */
class General_Color_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		$before = '';

		?>
		<div class="<?php $this->bem_class( 'color-wrapper' ); ?>">
			<input type="hidden" name="<?php echo esc_attr( $this->name ); ?>" value="<?php echo esc_attr( $this->value ); ?>"></input>
			<?php if ( isset( $this->all_args['before'] ) ) : ?>
				<span class="<?php $this->bem_class( 'before-color' ); ?>"><?php echo esc_html( (string) $this->all_args['before'] ); ?></span>
			<?php endif; ?>
			<span class="twrpb-color-picker"></span>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-color';
	}

}

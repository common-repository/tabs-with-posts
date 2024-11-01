<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create a select setting, in the general settings tab.
 *
 * The "General" term used here does NOT mean as the "default" or in general, it
 * means that is primary used for the "General Settings" interface(so contrary
 * to what is to be expected), where a user could change the main settings.
 */
class General_Select_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		?>
		<div class="<?php $this->bem_class( 'select-wrapper' ); ?>">
			<select
				id="<?php echo esc_attr( $this->get_settings_attr_id() ); ?>"
				name="<?php echo esc_attr( $this->name ); ?>"
				class="<?php $this->bem_class( 'select' ); ?>"
				<?php $this->display_input_attributes(); ?>
			>
				<?php
				if ( $this->select_has_optgroup() ) :
					$this->display_select_opt_groups();
				else :
					$this->display_select_options( $this->options );
				endif;
				?>
			</select>
		</div>
		<?php
	}

	/**
	 * Display the select option groups.
	 *
	 * @return void
	 */
	protected function display_select_opt_groups() {
		foreach ( $this->options as $label => $brand_options ) :
			?>
			<optgroup label="<?php echo esc_attr( (string) $label ); ?>">
				<?php $this->display_select_options( $brand_options ); ?>
			</optgroup>
			<?php
		endforeach;
	}

	/**
	 * Display the options for the select input.
	 *
	 * @param array $options
	 * @return void
	 */
	protected function display_select_options( $options ) {
		foreach ( $options as $option_value => $option_label ) :
			$selected = ( (string) $option_value === (string) $this->value ? ' selected' : '' );
			?>
			<option value="<?php echo esc_attr( $option_value ); ?>"<?php echo esc_attr( $selected ); ?>>
				<?php echo esc_html( $option_label ); ?>
			</option>
			<?php
		endforeach;
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-select';
	}

	/**
	 * Detect if the options passed to create_select_option() function are for
	 * creating a select with optgroup.
	 *
	 * @return bool
	 */
	protected function select_has_optgroup() {
		foreach ( $this->options as $value ) {
			if ( is_array( $value ) ) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

}

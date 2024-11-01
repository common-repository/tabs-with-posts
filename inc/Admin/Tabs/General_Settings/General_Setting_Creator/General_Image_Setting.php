<?php

namespace TWRP\Admin\Tabs\General_Settings;

/**
 * Class used to create an image setting, in the general settings tab.
 */
class General_Image_Setting extends General_Setting_Creator {

	protected function display_internal_setting() {
		$image_id = $this->value;

		$default_src = '';
		if ( isset( $this->all_args['default_src'] ) ) {
			$default_src = $this->all_args['default_src'];
			if ( ! is_string( $default_src ) ) {
				$default_src = '';
			}
		}

		$src = $default_src;
		if ( is_numeric( $image_id ) ) {
			$image_data = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			if ( false !== $image_data ) {
				$src = $image_data[0];
			}
		}

		?>
		<div class="<?php $this->bem_class( 'btns-wrapper' ); ?>">
			<button class="<?php $this->bem_class( 'btn' ); ?> twrpb-button" type="button"><?php echo esc_html_x( 'Add image', 'backend', 'tabs-with-posts' ); ?></button>
			<button class="<?php $this->bem_class( 'clear-btn' ); ?> twrpb-button twrpb-button--delete twrpb-button--small" type="button"><?php echo esc_html_x( 'Clear Image', 'backend', 'tabs-with-posts' ); ?></button>
		</div>

		<input class="<?php $this->bem_class( 'img-src' ); ?>" name="<?php echo esc_attr( $this->name ); ?>" value="<?php echo esc_attr( $image_id ); ?>" type="hidden"<?php $this->display_input_attributes(); ?>/>

		<div class="<?php $this->bem_class( 'img-preview-wrapper' ); ?>">
			<img class="<?php $this->bem_class( 'img-preview' ); ?>" src="<?php echo esc_url( $src ); ?>"></img>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-general-image';
	}

}

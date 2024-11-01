<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Post_Types;

/**
 * Used to display the post types query setting control.
 */
class Post_Types_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 20;
	}

	protected function get_setting_class() {
		return new Post_Types();
	}

	public function get_title() {
		return _x( 'Post types to include', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$selected_post_types = $current_setting[ Post_Types::SELECTED_TYPES__SETTING_NAME ];
		?>
		<div class="<?php $this->bem_class(); ?>">
			<div class="<?php $this->query_setting_paragraph_class(); ?>">
				<?php
				$available_post_types = Post_Types::get_available_types();
				foreach ( $available_post_types as $post_type ) :
					if ( ( ! is_string( $post_type ) ) && isset( $post_type->name, $post_type->label ) ) :
						$is_checked = in_array( $post_type->name, $selected_post_types, true );
						$this->display_post_type_setting_checkbox( $post_type->name, $post_type->label, $is_checked );
					endif;
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display the checkbox for a single custom post type item.
	 *
	 * @param string $name
	 * @param string $label
	 * @param bool $is_checked
	 * @return void
	 */
	protected function display_post_type_setting_checkbox( $name, $label, $is_checked ) {
		$setting_class = $this->get_setting_class();
		$checked_attr  = $is_checked ? 'checked="checked"' : '';
		$checkbox_id   = $this->get_bem_class( 'checkbox-' . $name );
		$checkbox_name = $setting_class->get_setting_name() . '[' . Post_Types::SELECTED_TYPES__SETTING_NAME . '][' . $name . ']';

		?>
		<div class="<?php $this->bem_class( 'checkbox' ); ?> <?php $this->query_setting_checkbox_line_class(); ?>">
			<input
				id="<?php echo esc_attr( $checkbox_id ); ?>"
				name="<?php echo esc_attr( $checkbox_name ); ?>"
				type="checkbox"
				value="<?php echo esc_attr( $name ); ?>"
				<?php echo $checked_attr //phpcs:ignore -- No XSS. ?>
			/>
			<label for="<?php echo esc_attr( $checkbox_id ); ?>">
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-types-setting';
	}

}

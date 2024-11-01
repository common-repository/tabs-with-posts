<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Post_Status;
use TWRP\Admin\Helpers\Remember_Note;

/**
 * Used to display the post status filter setting control.
 */
class Post_Status_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 30;
	}

	protected function get_setting_class() {
		return new Post_Status();
	}

	public function get_title() {
		return _x( 'Filter by post statuses', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$setting_class          = $this->get_setting_class();
		$apply_statuses_name    = $setting_class->get_setting_name() . '[' . Post_Status::APPLY_STATUSES__SETTING_NAME . ']';
		$current_apply_statuses = $current_setting[ Post_Status::APPLY_STATUSES__SETTING_NAME ];

		$additional_hide_class = 'not_applied' === $current_apply_statuses ? ' twrpb-hidden' : '';

		$remember_note = new Remember_Note( Remember_Note::NOTE__POST_STATUS_INFO );
		$remember_note->display_note( $this->get_query_setting_paragraph_class() );
		?>

		<div class="<?php $this->query_setting_paragraph_class(); ?>">
			<select id="<?php $this->bem_class( 'js-apply-select' ); ?>" name="<?php echo esc_attr( $apply_statuses_name ); ?>">
				<option value="not_applied" <?php echo selected( $current_apply_statuses, 'not_applied' ); ?>>
					<?php echo esc_html_x( 'Not applied', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="apply" <?php echo selected( $current_apply_statuses, 'apply' ); ?>>
					<?php echo esc_html_x( 'Filter by post statuses', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>

		<div id="<?php $this->bem_class( 'js-statuses-wrapper' ); ?>" class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'statuses-wrap' ); ?><?php echo esc_attr( $additional_hide_class ); ?>">
			<?php
			$post_stats = Post_Status::get_post_statuses();
			foreach ( $post_stats as $status ) :
				if ( isset( $status->name, $status->label ) ) :
					$id = $this->get_bem_class( $status->name );

					$checked = '';
					if ( in_array( $status->name, $current_setting[ Post_Status::POST_STATUSES__SETTING_NAME ], true ) ) {
						$checked = 'checked';
					}
					?>
					<div class="<?php $this->query_setting_checkbox_line_class(); ?>">
						<input
							id="<?php echo esc_attr( $id ); ?>"
							class="<?php $this->bem_class( 'input' ); ?>"
							name="<?php echo esc_attr( $setting_class->get_setting_name() . '[' . Post_Status::POST_STATUSES__SETTING_NAME . '][' . $status->name . ']' ); ?>"
							type="checkbox"
							value="<?php echo esc_attr( $status->name ); ?>"
							<?php echo esc_attr( $checked ); ?>
						/>
						<label class="<?php $this->bem_class( 'label' ); ?>" for="<?php echo esc_attr( $id ); ?>">
							<?php echo esc_html( $status->label ); ?>
						</label>
					</div>
					<?php
				endif;
			endforeach;
			?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-statuses-settings';
	}

}

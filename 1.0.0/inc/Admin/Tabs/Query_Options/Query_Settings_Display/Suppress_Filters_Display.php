<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Suppress_Filters;
use TWRP\Admin\Helpers\Remember_Note;

/**
 * Used to display the suppress filter query setting control.
 */
class Suppress_Filters_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 140;
	}

	protected function get_setting_class() {
		return new Suppress_Filters();
	}

	public function get_title() {
		return _x( 'Suppress other plugins/theme query filters', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$setting_class        = $this->get_setting_class();
		$suppress_the_filters = $current_setting[ Suppress_Filters::SUPPRESS_FILTERS__SETTING_NAME ];
		$name                 = $setting_class->get_setting_name() . '[' . Suppress_Filters::SUPPRESS_FILTERS__SETTING_NAME . ']';
		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			$remember_note = new Remember_Note( Remember_Note::NOTE__SUPPRESS_FILTERS_INFO );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() );
			?>

			<div class="<?php $this->query_setting_paragraph_class(); ?>">
				<select id="<?php $this->bem_class( 'suppress-filters' ); ?>" name="<?php echo esc_attr( $name ); ?>">
					<option value="true" <?php echo selected( $suppress_the_filters, 'true' ); ?>>
						<?php echo esc_html_x( 'Suppress the filters', 'backend', 'tabs-with-posts' ); ?>
					</option>
					<option value="false" <?php echo selected( $suppress_the_filters, 'false' ); ?>>
						<?php echo esc_html_x( 'Do not suppress the filters', 'backend', 'tabs-with-posts' ); ?>
					</option>
				</select>
			</div>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-filters-setting';
	}

}

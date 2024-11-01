<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Query_Name;
use TWRP\Admin\Helpers\Remember_Note;

/**
 * Used to display the query name setting control.
 */
class Query_Name_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 0;
	}

	protected function get_setting_class() {
		return new Query_Name();
	}

	public function get_title() {
		return _x( 'Name of the query', 'backend', 'tabs-with-posts' );
	}

	public function setting_is_collapsed() {
		return true;
	}

	public function display_setting( $current_setting ) {
		$setting_class = $this->get_setting_class();
		$name          = $setting_class->get_setting_name() . '[' . Query_Name::QUERY_NAME__SETTING_NAME . ']';
		$value         = $current_setting[ Query_Name::QUERY_NAME__SETTING_NAME ];
		$placeholder   = _x( 'Ex: Related Posts', 'backend', 'tabs-with-posts' );

		?>
		<div class="<?php $this->bem_class(); ?>">
			<div class="<?php $this->query_setting_paragraph_class(); ?>">
			<input
				id="<?php $this->bem_class( 'name' ); ?>"
				class="<?php $this->bem_class( 'name' ); ?>" type="text"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
			/>
			</div>

			<?php
			$remember_note = new Remember_Note( Remember_Note::NOTE__QUERY_NAME_INFO );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() );
			?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-name-setting';
	}
}

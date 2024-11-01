<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Admin\Tabs\Queries_Tab;
use TWRP\Database\Query_Options;
use TWRP\Query_Generator\Query_Setting\Query_Name;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;
use TWRP\Utils\Simple_Utils;

/**
 * Display a table with the existing queries under "Queries Tab".
 */
class Query_Existing_Table {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the existing queries table.
	 *
	 * @return void
	 */
	public function display() {
		$existing_queries = Query_Options::get_all_queries();
		?>
		<table class="<?php $this->bem_class(); ?>">
			<thead>
				<tr>
					<th class="<?php $this->bem_class( 'title-head' ); ?>"><?php echo esc_html_x( 'Query Name', 'backend', 'tabs-with-posts' ); ?></th>
					<th class="<?php $this->bem_class( 'edit-head' ); ?>">
						<?php echo esc_html_x( 'Actions', 'backend', 'tabs-with-posts' ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( ! empty( $existing_queries ) ) : ?>
					<?php foreach ( $existing_queries as $query_id => $query ) : ?>
						<?php $this->display_existing_queries_row( $query_id, $query ); ?>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td class="<?php $this->bem_class( 'no-queries-col' ); ?>" colspan="2">
						<?php echo esc_html_x( 'No queries yet', 'backend', 'tabs-with-posts' ); ?>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Display a row with the corresponding query names and their actions.
	 *
	 * @param int|string $query_id
	 * @param array $query_settings
	 *
	 * @return void
	 */
	protected function display_existing_queries_row( $query_id, $query_settings ) {
		$queries_tab = new Queries_Tab();

		$edit_icon   = '<span class="' . $this->get_bem_class( 'edit-icon' ) . ' dashicons dashicons-edit"></span>';
		$delete_icon = '<span class="' . $this->get_bem_class( 'delete-icon' ) . ' dashicons dashicons-trash"></span>';

		/* translators: %s: edit dashicon html. */
		$edit_text = sprintf( _x( '%sEdit', 'backend', 'tabs-with-posts' ), $edit_icon );
		/* translators: %s: delete dashicon html. */
		$delete_text = sprintf( _x( '%sDelete', 'backend', 'tabs-with-posts' ), $delete_icon );

		?>
		<tr>
			<td class="<?php $this->bem_class( 'title-col' ); ?>">
				<?php echo esc_html( Query_Name::get_query_display_name( $query_settings, $query_id ) ); ?>
			</td>
			<td class="<?php $this->bem_class( 'edit-col' ); ?>">
				<a class="<?php $this->bem_class( 'edit-link' ); ?>" href="<?php echo esc_url( $queries_tab->get_query_edit_link( $query_id ) ); ?>"><?php echo wp_kses( $edit_text, Simple_Utils::get_plugin_allowed_kses_html() ); ?></a>
				<span class="<?php $this->bem_class( 'edit-delete-separator' ); ?>">|</span>
				<a class="<?php $this->bem_class( 'delete-link' ); ?>" href="<?php echo esc_url( $queries_tab->get_query_delete_link( $query_id ) ); ?>"><?php echo wp_kses( $delete_text, Simple_Utils::get_plugin_allowed_kses_html() ); ?></a>
			</td>
		</tr>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-queries-table';
	}
}

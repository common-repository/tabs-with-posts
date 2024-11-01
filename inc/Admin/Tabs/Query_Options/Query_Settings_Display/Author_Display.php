<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Author;
use TWRP\Utils\Simple_Utils;

/**
 * Used to display the author query setting control.
 */
class Author_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 80;
	}

	protected function get_setting_class() {
		return new Author();
	}

	public function get_title() {
		return _x( 'Filter by author', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$authors_type = $current_setting[ Author::AUTHORS_TYPE__SETTING_NAME ];
		$is_showing   = false;

		if ( Author::AUTHORS_TYPE__INCLUDE === $authors_type
		|| Author::AUTHORS_TYPE__EXCLUDE === $authors_type ) {
			$is_showing = true;
		}

		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			$this->display_authors_select_type( $current_setting );
			$this->display_selected_authors_list( $current_setting, $is_showing );
			$this->display_add_authors_to_list( $current_setting, $is_showing );
			?>
		</div>
		<?php
	}

	/**
	 * Display a select field which will tell what to do with the authors selected.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_authors_select_type( $current_setting ) {
		$selected_option = $current_setting[ Author::AUTHORS_TYPE__SETTING_NAME ];
		$author_class    = $this->get_setting_class();

		?>
		<div class="<?php $this->query_setting_paragraph_class(); ?>">
			<select
				id="<?php $this->bem_class( 'select_type' ); ?>"
				class="<?php $this->bem_class( 'select_type' ); ?>"
				name="<?php echo esc_attr( $author_class->get_setting_name() . '[' . Author::AUTHORS_TYPE__SETTING_NAME . ']' ); ?>"
			>
				<option value="<?php echo esc_attr( Author::AUTHORS_TYPE__DISABLED ); ?>" <?php selected( Author::AUTHORS_TYPE__DISABLED, $selected_option ); ?>>
					<?php echo esc_html_x( 'Not applied', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="<?php echo esc_attr( Author::AUTHORS_TYPE__INCLUDE ); ?>" <?php selected( Author::AUTHORS_TYPE__INCLUDE, $selected_option ); ?>>
					<?php echo esc_html_x( 'Include only posts with selected authors', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="<?php echo esc_attr( Author::AUTHORS_TYPE__EXCLUDE ); ?>" <?php selected( Author::AUTHORS_TYPE__EXCLUDE, $selected_option ); ?>>
					<?php echo esc_html_x( 'Exclude posts with selected authors', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>
		<?php
	}

	/**
	 * Display the list with the selected authors.
	 *
	 * @param array $current_setting
	 * @param bool $is_showing
	 * @return void
	 */
	protected function display_selected_authors_list( $current_setting, $is_showing ) {
		$authors = array();
		if ( isset( $current_setting[ Author::AUTHORS_IDS__SETTING_NAME ] ) ) {
			$authors_ids = explode( ';', $current_setting[ Author::AUTHORS_IDS__SETTING_NAME ] );
			$authors_ids = Simple_Utils::get_valid_wp_ids( $authors_ids );

			if ( ! empty( $authors_ids ) ) {
				$authors_args = array(
					'include' => $authors_ids,
					'fields'  => array( 'ID', 'display_name' ),
					'orderby' => 'include',
				);
				$authors      = get_users( $authors_args );
			}
		}

		$additional_list_class = '';
		if ( ! $is_showing ) {
			$additional_list_class = ' twrpb-hidden';
		}

		$additional_no_authors_class = '';
		if ( ! empty( $authors ) ) {
			$additional_no_authors_class = ' twrpb-hidden';
		}

		/* translators: %s -> display name of the author. */
		$remove_aria_label = _x( 'remove author %s', 'backend, accessibility text', 'tabs-with-posts' );

		?>
		<div
			id="<?php $this->bem_class( 'js-authors-list' ); ?>"
			class="twrpb-display-list <?php $this->bem_class( 'display-list' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $additional_list_class ); ?>"
			data-twrpb-aria-remove-label="<?php echo esc_attr( $remove_aria_label ); ?>"
		>
			<div id="<?php $this->bem_class( 'js-no-authors-selected' ); ?>" class="twrpb-display-list__empty-msg<?php echo esc_attr( $additional_no_authors_class ); ?>">
				<?php echo esc_html_x( 'No authors selected. You can search for an author and click the button to add.', 'backend', 'tabs-with-posts' ); ?>
			</div>
			<?php foreach ( $authors as $author ) : ?>
				<?php
				$author_display_name = $author->display_name;

				// The following HTML can also be generated in JS, so it will
				// be need to be changed there as well.
				?>
				<div class="<?php $this->bem_class( 'author-item' ); ?> twrpb-display-list__item" data-author-id="<?php echo esc_attr( (string) $author->ID ); ?>">
					<div class="<?php $this->bem_class( 'author-item-name' ); ?>">
						<?php echo esc_html( $author_display_name ); ?>
					</div>
					<button
						class="twrpb-display-list__item-remove-btn <?php $this->bem_class( 'js-author-remove-btn' ); ?>"
						type="button"
						aria-label="<?php echo esc_attr( sprintf( $remove_aria_label, $author_display_name ) ); ?>"
					>
						<span class="dashicons dashicons-no"></span>
					</button>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Display the search for authors, the add button, and a hidden input field
	 * that remembers the authors.
	 *
	 * @param array $current_setting
	 * @param bool $is_showing
	 * @return void
	 */
	protected function display_add_authors_to_list( $current_setting, $is_showing ) {
		$additional_class = '';
		if ( ! $is_showing ) {
			$additional_class = ' twrpb-hidden';
		}
		$author_class = $this->get_setting_class();

		?>
		<div id="<?php $this->bem_class( 'author-search-wrap' ); ?>" class="<?php $this->bem_class( 'author-search-wrap' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $additional_class ); ?>">
			<input
				id="<?php $this->bem_class( 'js-author-search' ); ?>" type="text"
				class="<?php $this->bem_class( 'author-search' ); ?>"
				placeholder="<?php echo esc_attr_x( 'Search for Author', 'backend', 'tabs-with-posts' ); ?>"
			/>

			<button
				id="<?php $this->bem_class( 'js-author-add-btn' ); ?>" type="button"
				class="<?php $this->bem_class( 'js-author-add-btn' ); ?> twrpb-button"
			>
				<?php echo esc_html_x( 'Add Author', 'backend', 'tabs-with-posts' ); ?>
			</button>

			<input
				id="<?php $this->bem_class( 'js-author-ids' ); ?>" type="hidden"
				name="<?php echo esc_attr( $author_class->get_setting_name() . '[' . Author::AUTHORS_IDS__SETTING_NAME . ']' ); ?>"
				value="<?php echo esc_attr( $current_setting[ Author::AUTHORS_IDS__SETTING_NAME ] ); ?>"
			/>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-author-settings';
	}
}

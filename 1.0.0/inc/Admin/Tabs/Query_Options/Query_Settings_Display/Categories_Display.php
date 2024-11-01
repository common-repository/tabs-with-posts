<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Categories;
use TWRP\Utils\Simple_Utils;
use WP_Term;

/**
 * Used to display the categories query setting control.
 */
class Categories_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 60;
	}

	protected function get_setting_class() {
		return new Categories();
	}

	public function get_title() {
		return _x( 'Include/Exclude posts by categories', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$cat_type_setting = $current_setting[ Categories::CATEGORIES_TYPE__SETTING_KEY ];

		$additional_class = '';
		if ( 'NA' === $cat_type_setting ) {
			$additional_class = ' twrpb-hidden';
		}

		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			$this->display_category_select_type( $current_setting );

			?>
			<div id="<?php $this->bem_class( 'js-settings-wrapper' ); ?>" class="<?php $this->bem_class( 'settings-wrapper' ); ?><?php echo esc_attr( $additional_class ); ?>">
				<?php
				$this->display_category_include_children( $current_setting );
				$this->display_categories_relation_setting( $current_setting );

				echo '<hr class="' . esc_attr( $this->get_bem_class( 'separator' ) ) . '"/>';

				$this->display_categories_list( $current_setting );
				$this->display_category_dropdown_selector( $current_setting );
				$this->display_hidden_input_with_cat_ids( $current_setting );
				?>
			</div>
		</div>
		<?php
	}

	#region -- Display settings

	/**
	 * Display a select field where the administrator will select how to filter
	 * the posts in the query based on categories.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_category_select_type( $current_setting ) {
		$cat_type_setting = $current_setting[ Categories::CATEGORIES_TYPE__SETTING_KEY ];
		$categories_class = $this->get_setting_class();
		?>
		<div class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'select-type-wrap' ); ?>">
			<select
				id="<?php $this->bem_class( 'type' ); ?>"
				class="<?php $this->bem_class( 'type' ); ?>"
				name="<?php echo esc_attr( $categories_class->get_setting_name() . '[' . Categories::CATEGORIES_TYPE__SETTING_KEY . ']' ); ?>"
			>
				<option value="NA" <?php selected( $cat_type_setting, 'NA' ); ?>>
					<?php echo esc_html_x( 'Not applied', 'backend', 'tabs-with-posts' ); ?>
				</option>

				<option value="IN" <?php selected( $cat_type_setting, 'IN' ); ?>>
					<?php echo esc_html_x( 'Include categories', 'backend', 'tabs-with-posts' ); ?>
				</option>

				<option value="OUT" <?php selected( $cat_type_setting, 'OUT' ); ?>>
					<?php echo esc_html_x( 'Exclude categories', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>
		<?php
	}

	/**
	 * Display a checkbox setting that will remember whether or not to also
	 * include categories children.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_category_include_children( $current_setting ) {
		$categories_class = $this->get_setting_class();
		?>
		<div class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'include-children-wrap' ); ?>">
			<input
				id="<?php $this->bem_class( 'include-children' ); ?>"
				type="checkbox"
				value="1"
				name="<?php echo esc_attr( $categories_class->get_setting_name() . '[' . Categories::INCLUDE_CHILDREN__SETTING_KEY . ']' ); ?>"
				<?php checked( '1', $current_setting[ Categories::INCLUDE_CHILDREN__SETTING_KEY ] ); ?>
			/>
			<label for="<?php $this->bem_class( 'include-children' ); ?>" class="<?php $this->bem_class( 'include-children-label' ); ?>">
				<?php echo esc_html_x( 'For each category selected include also all children categories.', 'backend', 'tabs-with-posts' ); ?>
			</label>
		</div>
		<?php
	}

	/**
	 * Display a select field to choose the relation between categories.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_categories_relation_setting( $current_setting ) {
		$categories_class = $this->get_setting_class();
		$select_name      = $categories_class->get_setting_name() . '[' . Categories::RELATION__SETTING_KEY . ']';
		$cat_relation     = $current_setting[ Categories::RELATION__SETTING_KEY ];

		$additional_class = '';
		if ( 'IN' !== $current_setting[ Categories::CATEGORIES_TYPE__SETTING_KEY ] ) {
			$additional_class = ' twrpb-hidden';
		}

		?>
		<div
			id="<?php $this->bem_class( 'js-select-relation-wrap' ); ?>"
			class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'select-relation-wrap' ); ?><?php echo esc_attr( $additional_class ); ?>"
		>
			<p class="<?php $this->bem_class( 'select-relation-text' ); ?>">
				<?php echo esc_html_x( 'An article should have:', 'backend', 'tabs-with-posts' ); ?>
			</p>

			<select
				id="<?php $this->bem_class( 'relation' ); ?>"
				class="<?php $this->bem_class( 'relation' ); ?>"
				name="<?php echo esc_attr( $select_name ); ?>"
			>
				<option value="OR" <?php selected( $cat_relation, 'OR' ); ?>>
					<?php echo esc_html_x( 'Minimum one selected category', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="AND" <?php selected( $cat_relation, 'AND' ); ?>>
					<?php echo esc_html_x( 'All selected categories', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>
		<?php
	}

	/**
	 * Display a list with the categories selected.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_categories_list( $current_setting ) {
		/* translators: %s -> category name. */
		$remove_aria_label = _x( 'remove category %s', 'backend, accessibility text', 'tabs-with-posts' );

		$categories_ids = $current_setting[ Categories::CATEGORIES_IDS__SETTING_KEY ];
		$categories_ids = explode( ';', $categories_ids );
		$categories_ids = Simple_Utils::get_valid_wp_ids( $categories_ids );

		$categories_are_displayed = array();
		if ( ! empty( $categories_ids ) ) {
			$categories_are_displayed = get_categories( array( 'include' => $categories_ids ) );
		}

		$additional_empty_msg_class = '';
		if ( count( $categories_are_displayed ) > 0 ) {
			$additional_empty_msg_class = ' twrpb-hidden';
		}

		?>
		<h4 class="<?php $this->bem_class( 'section-title' ); ?>">
			<?php echo esc_html_x( 'Selected categories:', 'backend', 'tabs-with-posts' ); ?>
		</h4>
		<div class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'cat-list-section' ); ?>">
			<div
				id="<?php $this->bem_class( 'cat-list-wrap' ); ?>"
				class="twrpb-display-list <?php $this->bem_class( 'cat-list-wrap' ); ?>"
				data-twrpb-aria-remove-label="<?php echo esc_attr( $remove_aria_label ); ?>"
			>
				<div class="twrpb-display-list__empty-msg<?php echo esc_attr( $additional_empty_msg_class ); ?>">
					<span>
						<?php echo esc_html_x( 'No categories added. Select a category and click the button to add.', 'backend', 'tabs-with-posts' ); ?>
					</span>
				</div>
				<?php foreach ( $categories_ids as $category_id ) : ?>
					<?php
					$category = get_category( (int) $category_id );

					if ( ! ( $category instanceof WP_Term ) ) {
						continue;
					}

					$remove_button_aria_label = sprintf( $remove_aria_label, $category->name );
					?>
					<div class="twrpb-display-list__item <?php $this->bem_class( 'cat-list-item' ); ?>" data-cat-id="<?php echo esc_attr( (string) $category->term_id ); ?>">
						<div class="twrpb-display-list__item-name <?php $this->bem_class( 'cat-item-name' ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</div>
						<button
							class="twrpb-display-list__item-remove-btn <?php $this->bem_class( 'cat-remove-btn' ); ?>"
							type="button"
							aria-label=<?php echo esc_html( $remove_button_aria_label ); ?>
						>
							<span class="dashicons dashicons-no"></span>
						</button>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display a dropdown with all categories and an "Add Button" to add them
	 * to the categories list.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_category_dropdown_selector( $current_setting ) {
		$categories_dropdown = wp_dropdown_categories(
			array(
				'echo'         => false,
				'name'         => 'cat_dropdown',
				'id'           => $this->get_bem_class( 'js-cat-dropdown' ),
				'class'        => $this->get_bem_class( 'cat-dropdown' ),
				'show_count'   => '1',
				'hide_empty'   => Categories::CATEGORIES_HIDE_EMPTY,
				'hierarchical' => true,
			)
		);

		?>
		<div class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'add-cat-wrapper' ); ?>">
			<?php echo $categories_dropdown; // phpcs:ignore -- No need to escape. ?>
			<button id="<?php $this->bem_class( 'add-cat-btn' ); ?>" class="<?php $this->bem_class( 'add-cat-btn' ); ?> twrpb-button" type="button">
				<?php echo esc_html_x( 'Add Category To List', 'backend', 'tabs-with-posts' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Display a hidden input where the categories selected will be remembered.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_hidden_input_with_cat_ids( $current_setting ) {
		$categories_class = $this->get_setting_class();
		?>
			<input
				id="<?php $this->bem_class( 'cat-ids' ); ?>"
				class="<?php $this->bem_class( 'cat-ids' ); ?>"
				type="hidden"
				name="<?php echo esc_attr( $categories_class->get_setting_name() . '[' . Categories::CATEGORIES_IDS__SETTING_KEY . ']' ); ?>"
				value="<?php echo esc_attr( $current_setting[ Categories::CATEGORIES_IDS__SETTING_KEY ] ); ?>"
			/>
		<?php
	}

	#endregion -- Display settings

	protected function get_bem_base_class() {
		return 'twrpb-cat-settings';
	}

}

<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Admin\Helpers\Remember_Note;
use TWRP\Query_Generator\Query_Setting\Post_Settings;
use TWRP\Utils\Simple_Utils;
use WP_Query;

/**
 * Used to display the post settings query setting control.
 */
class Post_Settings_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 50;
	}

	protected function get_setting_class() {
		return new Post_Settings();
	}

	public function get_title() {
		return _x( 'Only Include/Exclude specific posts', 'backend', 'tabs-with-posts' );
	}

	#region -- Display settings

	public function display_setting( $current_setting ) {
		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			$this->display_select_posts_inclusion_type( $current_setting );
			$this->display_selected_posts_list( $current_setting );
			$this->display_search_and_add_posts_to_list( $current_setting );
			?>
		</div>
		<?php
	}

	/**
	 * Display an option that will decide what to do with the selected posts.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_select_posts_inclusion_type( $current_setting ) {
		$setting_class = $this->get_setting_class();
		$select_name   = $setting_class->get_setting_name() . '[' . Post_Settings::FILTER_TYPE__SETTING_NAME . ']';

		$option_selected = '';
		if ( isset( $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ] ) ) {
			$option_selected = $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ];
		}

		?>
		<div class="<?php $this->query_setting_paragraph_class(); ?>">
			<select id="<?php $this->bem_class( 'js-filter-type' ); ?>" name="<?php echo esc_attr( $select_name ); ?>">
				<option value="NA" <?php selected( 'NA', $option_selected ); ?>>
					<?php echo esc_html_x( 'Not Applied', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="IP" <?php selected( 'IP', $option_selected ); ?>>
					<?php echo esc_html_x( 'Only these posts', 'backend', 'tabs-with-posts' ); ?>
				</option>
				<option value="EP" <?php selected( 'EP', $option_selected ); ?>>
					<?php echo esc_html_x( 'Exclude these posts', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>
		<?php
		$addition_note_hide_class = ' twrpb-hidden';
		if ( 'IP' === $option_selected ) {
			$addition_note_hide_class = '';
		}

		$remember_note = new Remember_Note( Remember_Note::NOTE__POST_SETTINGS_NOTE );
		$remember_note->display_note( $this->get_query_setting_paragraph_class() . $addition_note_hide_class );
	}

	/**
	 * Creates and display the selected posts list.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_selected_posts_list( $current_setting ) {
		$ids   = array();
		$posts = array();
		// We make the hidden input be the same as the displayed posts. This is
		// better in preventing posts that cannot be retrieve to be hidden int
		// the input.
		$hidden_input_value = array();

		if ( isset( $current_setting[ Post_Settings::POSTS_INPUT__SETTING_NAME ] ) ) {
			$ids      = $current_setting[ Post_Settings::POSTS_INPUT__SETTING_NAME ];
			$ids      = explode( ';', $ids );
			$ids      = Simple_Utils::get_valid_wp_ids( $ids );
			$wp_query = new WP_Query();
			if ( ! empty( $ids ) ) {
				$posts = $wp_query->query(
					array(
						'post_type'     => 'any',
						'post__in'      => $ids,
						'orderby'       => 'post__in',
						'no_found_rows' => true,
					)
				);
			}
		}

		/* translators: %s -> post name. */
		$remove_aria_label = _x( 'remove post %s', 'backend, accessibility text', 'tabs-with-posts' );

		$list_is_hidden_class = '';
		if ( isset( $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ] ) && 'NA' === $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ] ) {
			$list_is_hidden_class = ' twrpb-hidden';
		}

		$text_is_hidden_class = '';
		if ( ! empty( $posts ) ) {
			$text_is_hidden_class = ' twrpb-hidden';
		}

		?>
		<div
			id="<?php $this->bem_class( 'js-posts-list' ); ?>"
			class="twrpb-display-list <?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'posts-list' ); ?><?php echo esc_attr( $list_is_hidden_class ); ?>"
			data-twrpb-aria-remove-label="<?php echo esc_attr( $remove_aria_label ); ?>"
		>
			<div
				id="<?php $this->bem_class( 'js-no-posts-selected' ); ?>"
				class="twrpb-display-list__empty-msg<?php echo esc_attr( $text_is_hidden_class ); ?>"
			>
				<?php echo esc_html_x( 'No posts selected. You can search for a post and click the button to add.', 'backend', 'tabs-with-posts' ); ?>
			</div>
			<?php foreach ( $posts as $post ) : ?>
				<?php
				$title = get_the_title( $post );
				array_push( $hidden_input_value, $post->ID );

				if ( empty( $title ) ) {
					$title = esc_html_x( 'Post with no title', 'backend', 'tabs-with-posts' );
				}

				$remove_btn_label = sprintf( $remove_aria_label, $title );
				?>

				<div class="twrpb-display-list__item <?php $this->bem_class( 'post-item' ); ?>" data-post-id="<?php echo esc_attr( (string) $post->ID ); ?>">
					<div class="<?php $this->bem_class( 'post-item-title' ); ?>"><?php echo $title // phpcs:ignore -- No XSS. ?></div>
					<button class="twrpb-display-list__item-remove-btn <?php $this->bem_class( 'js-post-remove-btn' ); ?>" type="button" aria-label="<?php echo esc_attr( $remove_btn_label ); ?>">
						<span class="dashicons dashicons-no"></span>
					</button>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$this->display_posts_ids_hidden_input( $hidden_input_value );
	}

	/**
	 * Display a search box and an add button to add posts.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_search_and_add_posts_to_list( $current_setting ) {
		$list_is_hidden_class = '';
		if ( isset( $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ] ) && 'NA' === $current_setting[ Post_Settings::FILTER_TYPE__SETTING_NAME ] ) {
			$list_is_hidden_class = ' twrpb-hidden';
		}

		?>
		<div id="<?php $this->bem_class( 'js-posts-search-wrap' ); ?>" class="<?php $this->bem_class( 'posts-search-wrap' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $list_is_hidden_class ); ?>">
			<input
				id="<?php $this->bem_class( 'js-posts-search' ); ?>" type="text"
				class="<?php $this->bem_class( 'posts-search' ); ?>"
				placeholder="<?php echo esc_attr_x( 'Search for a post', 'backend', 'tabs-with-posts' ); ?>"
			/>

			<button
				id="<?php $this->bem_class( 'js-posts-add-btn' ); ?>" type="button"
				class="<?php $this->bem_class( 'js-posts-add-btn' ); ?> twrpb-button"
			>
				<?php echo esc_html_x( 'Add Post', 'backend', 'tabs-with-posts' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Display a hidden input that will remember what posts the administrator
	 * has chosen.
	 *
	 * @param array $post_ids
	 * @return void
	 */
	protected function display_posts_ids_hidden_input( $post_ids ) {
		$setting_class = $this->get_setting_class();
		$input_name    = $setting_class->get_setting_name() . '[' . Post_Settings::POSTS_INPUT__SETTING_NAME . ']';

		$value = implode( ';', $post_ids );

		?>
		<input id="<?php $this->bem_class( 'js-posts-ids' ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>" type="hidden"
			value="<?php echo esc_attr( $value ); ?>"
		>
		<?php
	}

	#endregion -- Display settings

	protected function get_bem_base_class() {
		return 'twrpb-posts-settings';
	}

}

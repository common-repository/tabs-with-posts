<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Admin\Helpers\Remember_Note;
use TWRP\Query_Generator\Query_Setting\Post_Order;

/**
 * Used to display the post order query setting control.
 */
class Post_Order_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 40;
	}

	protected function get_setting_class() {
		return new Post_Order();
	}

	public function get_title() {
		return esc_html_x( 'Order of posts', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$setting_class = $this->get_setting_class();

		$first_select_orderby_name  = $setting_class->get_setting_name() . '[' . Post_Order::FIRST_ORDERBY_SELECT_NAME . ']';
		$second_select_orderby_name = $setting_class->get_setting_name() . '[' . Post_Order::SECOND_ORDERBY_SELECT_NAME . ']';
		$third_select_orderby_name  = $setting_class->get_setting_name() . '[' . Post_Order::THIRD_ORDERBY_SELECT_NAME . ']';

		$first_select_order_type_name  = $setting_class->get_setting_name() . '[' . Post_Order::FIRST_ORDER_TYPE_SELECT_NAME . ']';
		$second_select_order_type_name = $setting_class->get_setting_name() . '[' . Post_Order::SECOND_ORDER_TYPE_SELECT_NAME . ']';
		$third_select_order_type_name  = $setting_class->get_setting_name() . '[' . Post_Order::THIRD_ORDER_TYPE_SELECT_NAME . ']';

		$first_orderby_setting  = isset( $current_setting[ Post_Order::FIRST_ORDERBY_SELECT_NAME ] ) ? $current_setting[ Post_Order::FIRST_ORDERBY_SELECT_NAME ] : null;
		$second_orderby_setting = isset( $current_setting[ Post_Order::SECOND_ORDERBY_SELECT_NAME ] ) ? $current_setting[ Post_Order::SECOND_ORDERBY_SELECT_NAME ] : null;
		$third_orderby_setting  = isset( $current_setting[ Post_Order::THIRD_ORDERBY_SELECT_NAME ] ) ? $current_setting[ Post_Order::THIRD_ORDERBY_SELECT_NAME ] : null;

		$first_order_type_setting  = isset( $current_setting[ Post_Order::FIRST_ORDER_TYPE_SELECT_NAME ] ) ? $current_setting[ Post_Order::FIRST_ORDER_TYPE_SELECT_NAME ] : null;
		$second_order_type_setting = isset( $current_setting[ Post_Order::SECOND_ORDER_TYPE_SELECT_NAME ] ) ? $current_setting[ Post_Order::SECOND_ORDER_TYPE_SELECT_NAME ] : null;
		$third_order_type_setting  = isset( $current_setting[ Post_Order::THIRD_ORDER_TYPE_SELECT_NAME ] ) ? $current_setting[ Post_Order::THIRD_ORDER_TYPE_SELECT_NAME ] : null;

		$additional_second_order_class     = '';
		$additional_first_order_type_class = '';
		if ( 'not_applied' === $first_orderby_setting ) {
			$additional_first_order_type_class = ' twrpb-hidden';
			$additional_second_order_class     = ' twrpb-hidden';
		}

		$additional_third_order_class       = '';
		$additional_second_order_type_class = '';
		if ( 'not_applied' === $second_orderby_setting ) {
			$additional_second_order_type_class = ' twrpb-hidden';
			$additional_third_order_class       = ' twrpb-hidden';
		}

		$additional_third_order_type_class = '';
		if ( 'not_applied' === $third_orderby_setting ) {
			$additional_third_order_type_class = ' twrpb-hidden';
		}

		?>
		<div class="<?php $this->bem_class(); ?>">
			<p id="<?php $this->bem_class( 'js-first-order-group' ); ?>" class="<?php $this->bem_class( 'order-group' ); ?> <?php $this->query_setting_paragraph_class(); ?>">
				<select class="<?php $this->bem_class( 'js-orderby' ); ?>" name=<?php echo esc_attr( $first_select_orderby_name ); ?>>
					<?php $this->display_order_by_select_options( $first_orderby_setting, Post_Order::get_orderby_select_options() ); ?>
					<?php $this->display_order_by_select_options( $first_orderby_setting, Post_Order::get_additional_first_orderby_select_options() ); ?>
				</select>

				<select class="<?php $this->bem_class( 'js-order-type' ); ?><?php echo esc_html( $additional_first_order_type_class ); ?>" name=<?php echo esc_attr( $first_select_order_type_name ); ?>>
					<?php $this->display_order_type_select_options( $first_order_type_setting ); ?>
				</select>
			</p>

			<p id="<?php $this->bem_class( 'js-second-order-group' ); ?>" class="<?php $this->bem_class( 'order-group' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_html( $additional_second_order_class ); ?>">
				<select class="<?php $this->bem_class( 'js-orderby' ); ?>" name=<?php echo esc_attr( $second_select_orderby_name ); ?>>
					<?php $this->display_order_by_select_options( $second_orderby_setting, Post_Order::get_orderby_select_options() ); ?>
				</select>

				<select class="<?php $this->bem_class( 'js-order-type' ); ?><?php echo esc_html( $additional_second_order_type_class ); ?>" name=<?php echo esc_attr( $second_select_order_type_name ); ?>>
					<?php $this->display_order_type_select_options( $second_order_type_setting ); ?>
				</select>
			</p>

			<p id="<?php $this->bem_class( 'js-third-order-group' ); ?>" class="<?php $this->bem_class( 'order-group' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_html( $additional_third_order_class ); ?>">
				<select class="<?php $this->bem_class( 'js-orderby' ); ?>" name=<?php echo esc_attr( $third_select_orderby_name ); ?>>
					<?php $this->display_order_by_select_options( $third_orderby_setting, Post_Order::get_orderby_select_options() ); ?>
				</select>

				<select class="<?php $this->bem_class( 'js-order-type' ); ?><?php echo esc_html( $additional_third_order_type_class ); ?>" name=<?php echo esc_attr( $third_select_order_type_name ); ?>>
					<?php $this->display_order_type_select_options( $third_order_type_setting ); ?>
				</select>
			</p>
			<?php

			$orderby_settings = array( $first_orderby_setting, $second_orderby_setting, $third_orderby_setting );

			// Post Id Ordering Warning.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( in_array( 'ID', $orderby_settings, true ) || in_array( 'parent', $orderby_settings, true ) ) {
				$note_is_hidden_class = '';
			}
			$warning_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_POST_ID_WARNING, 'warning' );
			$warning_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );

			// Comments order warning.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( 'comment_count' === $first_orderby_setting && 'not_applied' === $second_orderby_setting ) {
				$note_is_hidden_class = '';
			}
			$remember_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_COMMENTS_WARNING, 'warning' );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );

			// Search note.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( in_array( 'relevance', $orderby_settings, true ) ) {
				$note_is_hidden_class = '';
			}
			$remember_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_SEARCH_NOTE );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );

			// Meta note.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( in_array( 'meta_value', $orderby_settings, true ) || in_array( 'meta_value_num', $orderby_settings, true ) ) {
				$note_is_hidden_class = '';
			}
			$remember_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_META_NOTE );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );

			// Post In note.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( in_array( 'post__in', $orderby_settings, true ) ) {
				$note_is_hidden_class = '';
			}
			$remember_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_POSTS_IN_NOTE );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );

			// Views note.
			$note_is_hidden_class = ' twrpb-hidden';
			if ( in_array( 'twrp_post_views_order', $orderby_settings, true ) ) {
				$note_is_hidden_class = '';
			}
			$remember_note = new Remember_Note( Remember_Note::NOTE__ORDERING_BY_VIEWS_NOTE );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $note_is_hidden_class );
			?>
		</div>
		<?php
	}

	/**
	 * Display all possible orderby options to select.
	 *
	 * @param string|null $current_setting Current selected option.
	 * @param array $options
	 * @return void
	 */
	protected function display_order_by_select_options( $current_setting, $options ) {
		if ( ! isset( $current_setting ) ) {
			$current_setting = 'not_applied';
		}

		foreach ( $options as $value => $description ) {
			?>
			<option value=<?php echo esc_attr( $value ); ?> <?php selected( $value, $current_setting ); ?>>
				<?php echo esc_html( $description ); ?>
			</option>
			<?php
		}
	}

	/**
	 * Display all possible order type options to select.
	 *
	 * @param string|null $current_setting Current selected option.
	 * @return void
	 */
	protected function display_order_type_select_options( $current_setting ) {
		$options = array(
			'DESC' => 'Descending order',
			'ASC'  => 'Ascending order',
		);

		foreach ( $options as $value => $description ) {
			?>
				<option value=<?php echo esc_attr( $value ); ?> <?php selected( $value, $current_setting ); ?>>
					<?php echo esc_html( $description ); ?>
				</option>
			<?php
		}
	}

	protected function get_bem_base_class() {
		return 'twrpb-order-setting';
	}

}

<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Search;
use TWRP\Admin\Helpers\Remember_Note;

/**
 * Used to display the search query setting control.
 */
class Search_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 100;
	}

	protected function get_setting_class() {
		return new Search();
	}

	public function get_title() {
		return _x( 'Filter by search keywords', 'backend', 'tabs-with-posts' );
	}

	public function display_setting( $current_setting ) {
		$search_keywords = $current_setting[ Search::SEARCH_KEYWORDS__SETTING_NAME ];

		$warning_text_is_shown = ( strlen( $search_keywords ) < 4 ) && ( strlen( $search_keywords ) !== -1 );

		$warning_hidden_class = ' twrpb-hidden';
		if ( $warning_text_is_shown ) {
			$warning_hidden_class = '';
		}

		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			$setting_class = $this->get_setting_class();
			$remember_note = new Remember_Note( Remember_Note::NOTE__SEARCH_QUERY_INFO );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() );
			?>

			<div class="<?php $this->query_setting_paragraph_class(); ?> <?php $this->bem_class( 'paragraph' ); ?>">
				<input
					id="<?php $this->bem_class( 'js-search-input' ); ?>"
					class="<?php $this->bem_class( 'input' ); ?>"
					type="text"
					name="<?php echo esc_attr( $setting_class->get_setting_name() . '[' . Search::SEARCH_KEYWORDS__SETTING_NAME . ']' ); ?>"
					value="<?php echo esc_attr( $search_keywords ); ?>"
					placeholder="<?php echo esc_attr_x( 'Search keywords...', 'backend', 'tabs-with-posts' ); ?>"
				/>
			</div>
			<?php
			$remember_note = new Remember_Note( Remember_Note::NOTE__SEARCH_QUERY_TOO_SHORT_WARNING, 'warning' );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . $warning_hidden_class );
			?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrpb-search-setting';
	}

}

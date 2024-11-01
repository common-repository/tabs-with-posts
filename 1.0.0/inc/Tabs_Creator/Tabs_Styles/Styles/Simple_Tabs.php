<?php

namespace TWRP\Tabs_Creator\Tabs_Styles;

/**
 * Used to display the tabs a normal tabs style.
 */
class Simple_Tabs extends Tab_Style {

	const TAB_ID = 'tabs_style';

	public static function get_tab_style_name() {
		return _x( 'Tabs Style', 'backend', 'tabs-with-posts' );
	}

	public static function get_all_variants() {
		return array(
			'accent'       => _x( 'Accent', 'backend, name of style', 'tabs-with-posts' ),
			'accent_light' => _x( 'Accent Light', 'backend, name of style', 'tabs-with-posts' ),
			'inverse'      => _x( 'Inversed', 'backend, name of style', 'tabs-with-posts' ),
		);
	}

	public function start_tabs_wrapper() {
		$additional_tab_class = '';
		if ( 'accent' === $this->variant ) {
			$additional_tab_class = ' ' . $this->get_bem_class( '', 'accent' );
		}

		if ( 'accent_light' === $this->variant ) {
			$additional_tab_class = ' ' . $this->get_bem_class( '', 'accent-light' );
		}

		if ( 'inverse' === $this->variant ) {
			$additional_tab_class = ' ' . $this->get_bem_class( '', 'inverse' );
		}

		?>
		<div id="<?php $this->tabs_wrapper_id(); ?>" class="<?php $this->tab_class(); ?><?php echo esc_attr( $additional_tab_class ); ?>">
		<?php
	}

	public function end_tabs_wrapper() {
		?>
		</div>
		<?php
	}

	public function start_tab_buttons_wrapper() {
		?>
		<ul class="<?php $this->tab_btns_class(); ?>" <?php $this->tabby_btns_data_attr(); ?>>
		<?php
	}

	public function end_tab_buttons_wrapper() {
		?>
		</ul>
		<?php
	}

	public function tab_button( $button_text, $query_id, $default_tab = false ) {
		$default_tab_attr = '';
		if ( $default_tab ) {
			$default_tab_attr = ' ' . $this->get_tabby_default_tab_data_attr();
		}
		?>
		<li class="<?php $this->tab_btn_item_class(); ?>"><a class="<?php $this->tab_btn_class(); ?>" href="#<?php $this->tab_id( $query_id ); ?>"<?php echo esc_attr( $default_tab_attr ); ?>><?php echo esc_html( $button_text ); ?></a></li>
		<?php
	}

	public function start_all_tabs_wrapper() {
		?>
		<div class="<?php $this->tab_contents_wrapper_class(); ?>">
		<?php
	}

	public function end_all_tabs_wrapper() {
		?>
		</div>
		<?php
	}

	public function start_tab_content_wrapper( $query_id ) {
		?>
		<div id="<?php $this->tab_id( $query_id ); ?>" class="<?php $this->tab_content_class( $query_id ); ?>"<?php $this->display_tab__paging_attributes(); ?>>
		<?php
	}

	public function end_tab_content_wrapper( $query_id ) {
		?>
		</div>
		<?php
	}

	protected function get_bem_base_class() {
		return 'twrp-tab-ts';
	}

}

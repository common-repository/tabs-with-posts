<?php

namespace TWRP\Article_Block\Blocks;

use TWRP\Article_Block\Article_Block;

use TWRP\Article_Block\Component\Artblock_Component;

use TWRP\Article_Block\Settings\Display_Post_Thumbnail_Setting;
use TWRP\Article_Block\Settings\Display_Meta;

/**
 * The class that display a post in a style, that is named "Simple", because
 * nothing here is complicated.
 *
 * Consists of a title, a thumbnail, and the meta of the post displayed in order.
 */
class Simple_Article extends Article_Block {

	/**
	 * Redeclare to make Phan not trigger an error.
	 *
	 * @var array
	 */
	protected $settings;

	public static function get_class_order_among_siblings() {
		return 30;
	}

	public static function get_id() {
		return 'simple_style';
	}

	public static function get_name() {
		return _x( 'Simple Style', 'backend', 'tabs-with-posts' );
	}

	public static function get_file_name() {
		return 'simple-style.php';
	}

	public function get_components() {
		$components = array();
		$css_prefix = $this->get_body_css_specificity_selector() . ' .' . $this->get_block_class();

		$title_component_settings = array(
			Artblock_Component::TITLE_FONT_SIZE_SETTING,
			Artblock_Component::LINE_HEIGHT_SETTING,
			Artblock_Component::FONT_WEIGHT_SETTING,
			Artblock_Component::TEXT_DECORATION_SETTING,
			Artblock_Component::COLOR_SETTING,
		);

		$meta_component_settings = array(
			Artblock_Component::META_FONT_SIZE_SETTING,
			Artblock_Component::FONT_WEIGHT_SETTING,
			Artblock_Component::TEXT_DECORATION_SETTING,
			Artblock_Component::COLOR_SETTING,
		);

		$component_settings_hover = array(
			Artblock_Component::HOVER_TEXT_DECORATION_SETTING,
			Artblock_Component::HOVER_COLOR_SETTING,
		);

		#region -- Title Component

		// Use 'header' instead of 'title' to prevent WP widget to mistake the name with an usual title setting.
		$hover_css_selector    = $css_prefix . ' .twrp-ss__link:hover .twrp-ss__title, ' . $css_prefix . ' .twrp-ss__link:focus .twrp-ss__title';
		$current_settings      = ( isset( $this->settings['header'] ) && is_array( $this->settings['header'] ) ) ? $this->settings['header'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__title' => $title_component_settings,
			$hover_css_selector              => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'header', _x( 'Title', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['header'] = $component;

		#endregion -- Title Component

		#region -- Meta 1

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--1, ' . $css_prefix . ':focus-within .twrp-ss__meta--1';
		$current_settings      = ( isset( $this->settings['meta_1'] ) && is_array( $this->settings['meta_1'] ) ) ? $this->settings['meta_1'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--1' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_1', _x( 'Meta 1', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_1'] = $component;

		#endregion -- Meta 1

		#region -- Meta 2

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--2, ' . $css_prefix . ':focus-within .twrp-ss__meta--2';
		$current_settings      = ( isset( $this->settings['meta_2'] ) && is_array( $this->settings['meta_2'] ) ) ? $this->settings['meta_2'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--2' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_2', _x( 'Meta 2', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_2'] = $component;

		#endregion -- Meta 2

		#region -- Meta 3

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--3, ' . $css_prefix . ':focus-within .twrp-ss__meta--3';
		$current_settings      = ( isset( $this->settings['meta_3'] ) && is_array( $this->settings['meta_3'] ) ) ? $this->settings['meta_3'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--3' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_3', _x( 'Meta 3', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_3'] = $component;

		#endregion -- Meta 3

		#region -- Meta 4

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--4, ' . $css_prefix . ':focus-within .twrp-ss__meta--4';
		$current_settings      = ( isset( $this->settings['meta_4'] ) && is_array( $this->settings['meta_4'] ) ) ? $this->settings['meta_4'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--4' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_4', _x( 'Meta 4', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_4'] = $component;

		#endregion -- Meta 4

		#region -- Meta 5

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--5, ' . $css_prefix . ':focus-within .twrp-ss__meta--5';
		$current_settings      = ( isset( $this->settings['meta_5'] ) && is_array( $this->settings['meta_5'] ) ) ? $this->settings['meta_5'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--5' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_5', _x( 'Meta 5', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_5'] = $component;

		#endregion -- Meta 5

		#region -- Meta 6

		$hover_css_selector    = $css_prefix . ':hover .twrp-ss__meta--6, ' . $css_prefix . ':focus-within .twrp-ss__meta--6';
		$current_settings      = ( isset( $this->settings['meta_6'] ) && is_array( $this->settings['meta_6'] ) ) ? $this->settings['meta_6'] : array();
		$css_components        = array(
			$css_prefix . ' .twrp-ss__meta--6' => $meta_component_settings,
			$hover_css_selector                => $component_settings_hover,
		);
		$component             = new Artblock_Component( 'meta_6', _x( 'Meta 6', 'backend', 'tabs-with-posts' ), $current_settings, $css_components );
		$components ['meta_6'] = $component;

		#endregion -- Meta 6

		return $components;
	}

	public function get_artblock_settings() {
		$query_settings = array();

		$setting           = new Display_Post_Thumbnail_Setting( $this->widget_id, $this->query_id, $this->settings );
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 1,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 2,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 3,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 4,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 5,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		$setting           = new Display_Meta(
			$this->widget_id,
			$this->query_id,
			$this->settings,
			array(
				'instance' => 6,
				'meta'     => 'all',
			)
		);
		$query_settings [] = $setting;

		return $query_settings;
	}
}

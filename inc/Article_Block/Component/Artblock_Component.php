<?php

namespace TWRP\Article_Block\Component;

use TWRP\Article_Block\Component\Component_Setting;
use TWRP\Utils\Simple_Utils;

/**
 * Class that represents an artblock component and its settings. A component is
 * an abstract thing that is used to style a part of an artblock independently
 * from other parts.
 *
 * For example we can have a "Title" component, which will style just the title
 * (font-color, font-size).. etc. and other components for meta like "Author",
 * "Views", "Post Thumbnail"... etc.
 */
class Artblock_Component {

	const COMPONENTS_NAMESPACE_PREFIX = __NAMESPACE__;

	const FLEX_ORDER_SETTING             = 'Flex_Order_Setting';
	const TITLE_FONT_SIZE_SETTING        = 'Title_Font_Size_Setting';
	const META_FONT_SIZE_SETTING         = 'Meta_Font_Size_Setting';
	const FONT_SIZE_SETTING              = 'Font_Size_Setting';
	const BORDER_RADIUS_SETTING          = 'Border_Radius_Setting';
	const LINE_HEIGHT_SETTING            = 'Line_Height_Setting';
	const FONT_WEIGHT_SETTING            = 'Font_Weight_Setting';
	const TEXT_DECORATION_SETTING        = 'Text_Decoration_Setting';
	const COLOR_SETTING                  = 'Color_Setting';
	const BACKGROUND_COLOR_SETTING       = 'Background_Color_Setting';
	const HOVER_TEXT_DECORATION_SETTING  = 'Hover_Text_Decoration_Setting';
	const HOVER_COLOR_SETTING            = 'Hover_Color_Setting';
	const HOVER_BACKGROUND_COLOR_SETTING = 'Hover_Background_Color_Setting';
	const MAXIMUM_EXCERPT_LINES_SETTING  = 'Maximum_Excerpt_Lines_Setting';

	const YOUTUBE_STYLE_THUMBNAIL_WIDTH = 'Youtube_Style_Thumbnail_Width';

	/**
	 * The name of the component. It should be as something unique, will appear
	 * when creating the names in HTML attribute.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The title of the component. Will appear as a tab title in the widget
	 * settings.
	 *
	 * @var string
	 */
	protected $component_title;

	/**
	 * The current settings of the component.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * A selector as the array key, and a component setting as the value. The
	 * value will apply to the selector.
	 *
	 * @var array
	 */
	protected $css_settings;

	/**
	 * Variable that holds all the component settings classes needed.
	 *
	 * @var array<Component_Setting>
	 */
	protected $setting_classes;

	/**
	 * Creates the class.
	 *
	 * @param string $name The name must be unique between other components.
	 * @param string $component_title
	 * @param array $settings
	 * @param array $css_settings
	 */
	public function __construct( $name, $component_title, $settings, $css_settings ) {
		$this->name            = $name;
		$this->component_title = $component_title;
		$this->settings        = $settings;
		$this->css_settings    = $css_settings;

		$this->setting_classes = $this->get_component_classes( $this->css_settings );
	}

	/**
	 * Get the component name.
	 *
	 * @return string
	 */
	public function get_component_name() {
		return $this->name;
	}

	/**
	 * Get the component title.
	 *
	 * @return string
	 */
	public function get_component_title() {
		return $this->component_title;
	}

	/**
	 * Get an array with all component settings classes.
	 *
	 * @return array<Component_Setting>
	 */
	public function get_component_setting_classes() {
		return $this->setting_classes;
	}

	/**
	 * Get an array with all component settings.
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	#region -- Sanitization

	/**
	 * Sanitize the internal state settings, and returns them.
	 *
	 * @return array
	 */
	public function sanitize_settings() {
		$component_setting_classes = $this->setting_classes;
		$settings                  = $this->settings;

		$sanitized_settings = array();
		foreach ( $component_setting_classes as $setting_class ) {
			$setting_key = $setting_class->get_key_name();

			if ( isset( $settings[ $setting_key ] ) ) {
				$sanitized_settings[ $setting_key ] = $setting_class->sanitize_setting( $settings[ $setting_key ] );
			} else {
				$sanitized_settings[ $setting_key ] = $setting_class->sanitize_setting( null );
			}
		}

		$this->settings = $sanitized_settings;
		return $sanitized_settings;
	}

	#endregion -- Sanitization

	/**
	 * Returns an array with all the setting classes objects.
	 *
	 * @param array $component_setting_classes Can be a multidimensional array.
	 * @return array<Component_Setting>
	 */
	public function get_component_classes( $component_setting_classes ) {
		$classes_names   = Simple_Utils::flatten_array( $component_setting_classes );
		$setting_classes = array();

		foreach ( $classes_names as $component_setting_class_name ) {
			$component_setting_class_name = self::COMPONENTS_NAMESPACE_PREFIX . '\\' . $component_setting_class_name;

			if ( ! class_exists( $component_setting_class_name ) ) {
				continue;
			}

			$component_setting_class = new $component_setting_class_name();

			if ( $component_setting_class instanceof Component_Setting ) {
				array_push( $setting_classes, $component_setting_class );
			}
		}

		return $setting_classes;
	}

	/**
	 * Create the CSS of the component and return it.
	 *
	 * @return string
	 */
	public function get_css() {
		$css = '';

		foreach ( $this->css_settings as $selector => $css_component ) {
			if ( ! is_array( $css_component ) ) {
				continue;
			}

			$components = $this->get_component_classes( $css_component );

			$component_css = '';
			foreach ( $components as $component ) {
				if ( isset( $this->settings[ $component->get_key_name() ] ) && '' !== $this->settings[ $component->get_key_name() ] ) {
					$value          = $this->settings[ $component->get_key_name() ];
					$component_css .= $component->get_css( $value );
				}
			}

			if ( ! empty( $component_css ) ) {
				$css .= $selector . '{' . $component_css . '}';
			}
		}

		return $css;
	}

}

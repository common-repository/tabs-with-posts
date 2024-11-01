<?php

namespace TWRP\Icons;

use RuntimeException;
use TWRP\Utils\Filesystem_Utils;
use TWRP\Utils\Directory_Utils;

/**
 * Represents a icon. Contains methods for displaying, and retrieving other
 * information.
 *
 * An Icon class needs to be retrieved through the Icon_Factory class.
 */
class Icon {

	/**
	 * The id of the icon.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The brand of the icon.
	 *
	 * @var string
	 */
	protected $brand;

	/**
	 * The icon description.
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * The type of the icon. Ex: Filled, Outlined, ..etc.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The filename of the icon.
	 *
	 * @var string
	 */
	protected $file_name;

	/**
	 * Classes to fix the icon, usually fix the icon vertical align.
	 *
	 * @var string
	 */
	protected $fix_classes = '';

	/**
	 * Holds the svg definition, to not retrieve multiple times.
	 *
	 * @var string
	 */
	protected $cache_definition = '';

	/**
	 * Construct the class. Either provide an icon id alongside with the needed
	 * arguments, or provide just the $icon_id, and let arguments be auto set.
	 *
	 * @param string $icon_id
	 * @param array $icon_args Defaults to the icon id arguments.
	 */
	public function __construct( $icon_id, $icon_args ) {
		$this->id          = $icon_id;
		$this->brand       = $icon_args['brand'];
		$this->description = $icon_args['description'];
		$this->type        = $icon_args['type'];
		$this->file_name   = $icon_args['file_name'];
		$this->fix_classes = ( isset( $icon_args['fix_classes'] ) ? $icon_args['fix_classes'] : '' );
	}

	#region -- Get basic info

	/**
	 * Get the icon id.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the icon brand.
	 *
	 * @return string
	 */
	public function get_brand() {
		return $this->brand;
	}

	/**
	 * Get the icon description.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the icon type.
	 *
	 * @return string
	 */
	public function get_icon_type() {
		return $this->type;
	}

	/**
	 * Returns the absolute path to a file that contains the icon.
	 *
	 * @return string|false False if filename cannot be retrieved.
	 */
	public function get_icon_file_path() {
		try {
			$relative_path = trailingslashit( Directory_Utils::get_assets_svgs_directory_path() ) . $this->get_folder_name_category() . '/' . $this->get_brand_folder() . '/' . $this->file_name;
		} catch ( RuntimeException $e ) {
			return false;
		}

		return $relative_path;
	}

	/**
	 * Get the fix classes.
	 *
	 * @return string
	 */
	public function get_fix_classes() {
		return $this->fix_classes;
	}

	/**
	 * Return the name of the folder, named after the icon brand.
	 *
	 * @return string
	 */
	protected function get_brand_folder() {
		$folder = str_replace( ' ', '-', strtolower( $this->get_brand() ) );
		$folder = str_replace( '\'', '-', $folder );
		$folder = str_replace( '--', '-', $folder );

		return $folder;
	}

	#endregion -- Get basic info

	/**
	 * Display the icon.
	 *
	 * @param string $additional_class Can be multiple classes separated by spaces.
	 * @return void
	 */
	public function display( $additional_class = '' ) {
		echo $this->get_html( $additional_class ); // phpcs:ignore -- No XSS.
	}

	/**
	 * Returns the HTML to include an icon.
	 *
	 * @param string $additional_class Can be multiple classes separated by spaces.
	 * @return string
	 */
	public function get_html( $additional_class = '' ) {
		if ( ! empty( $additional_class ) ) {
			$additional_class = ' ' . $additional_class;
		}

		$icon_category_class = ' ' . $this->get_icon_category_class();

		$fix_icon_class = '';
		if ( ! empty( $this->get_fix_classes() ) ) {
			$fix_icon_class = ' ' . $this->get_fix_classes();
		}

		$html =
		'<span class="twrp-i' . esc_attr( $fix_icon_class . $additional_class . $icon_category_class ) . '" role="icon" aria-label="' . esc_attr( $this->get_icon_aria_label() ) . '">' .
		'<svg><use xlink:href="#' . esc_attr( $this->get_id() ) . '"/></svg>' .
		'</span>';

		return $html;
	}

	/**
	 * Returns the HTML that define the icon.
	 *
	 * @return string|false False if icon cannot be retrieved.
	 */
	public function get_icon_svg_definition() {
		if ( ! empty( $this->cache_definition ) ) {
			return $this->cache_definition;
		}

		$icon_filename = $this->get_icon_file_path();

		if ( ! is_string( $icon_filename ) ) {
			return false;
		}

		$content = Filesystem_Utils::get_file_contents( $icon_filename );
		if ( is_string( $content ) ) {
			$this->cache_definition = $content;
			return $content;
		}

		return false; // @codeCoverageIgnore
	}

	/**
	 * Get the icon description to be displayed as an option.
	 *
	 * @param bool $with_brand Whether or not to include the brand in the name.
	 * @return string
	 */
	public function get_option_icon_description( $with_brand = false ) {
		$return_description = $this->get_description() . ' (' . $this->get_icon_type() . ')';

		if ( $with_brand ) {
			$return_description = '[' . $this->get_brand() . '] ' . $return_description;
		}

		return $return_description;
	}

	#region -- Helpers

	/**
	 * Get a numeric value, representing the icon category. The numeric value
	 * is a constant declared in this class. Return false otherwise.
	 *
	 * @throws RuntimeException In case the numeric value cannot be retrieved.
	 *
	 * @return int
	 */
	public function get_icon_category() {
		return Icon_Categories::get_icon_category( $this->get_id() );
	}

	/**
	 * Constructor helper, that will get the name of the folder category of the
	 * icons.
	 *
	 * @throws RuntimeException In case folder name cannot be retrieved.
	 *
	 * @return string
	 */
	public function get_folder_name_category() {
		$icon_category = $this->get_icon_category();
		$icon_folders  = Icon_Categories::ICON_CATEGORY_FOLDER;

		if ( isset( $icon_folders[ $icon_category ] ) ) {
			return $icon_folders[ $icon_category ];
		} else {
			throw new RuntimeException(); // @codeCoverageIgnore
		}
	}

	/**
	 * Get the icon class, corresponding to icon category.
	 *
	 * @return string
	 */
	public function get_icon_category_class() {
		try {
			$category = $this->get_icon_category();
		} catch ( RuntimeException $e ) {
			return '';
		}
		$classes = Icon_Categories::ICON_CATEGORY_CLASS;

		if ( isset( $classes[ $category ] ) ) {
			return $classes[ $category ];
		}

		return ''; // @codeCoverageIgnore
	}

	/**
	 * Get the aria label for the icon. If the icon does not have an aria-label,
	 * will return an empty string.
	 *
	 * @return string
	 */
	public function get_icon_aria_label() {
		$labels = Icon_Categories::get_category_aria_label();

		try {
			$icon_category = $this->get_icon_category();
		} catch ( RuntimeException $e ) {
			return '';
		}

		if ( isset( $labels[ $icon_category ] ) ) {
			return $labels[ $icon_category ];
		}

		return ''; // @codeCoverageIgnore
	}

	#endregion -- Helpers

	#region -- Static Helpers

	/**
	 * Make the icons of same brand be nested in an array, where the key is the
	 * brand name.
	 *
	 * @param array<Icon> $icons
	 * @return array
	 */
	public static function nest_icons_by_brands( $icons ) {
		$branded_icons = array();

		foreach ( $icons as $icon ) {
			$brand = $icon->get_brand();

			if ( ! isset( $branded_icons[ $brand ] ) ) {
				$branded_icons[ $brand ] = array();
			}

			$branded_icons[ $brand ][ $icon->get_id() ] = $icon;
		}

		return $branded_icons;
	}

	/**
	 * Create an array with the brand as a key, and the value containing an
	 * array of icons of the same brand.
	 *
	 * @param array<Icon> $icons
	 * @return array
	 */
	public static function get_description_options_by_brands( $icons ) {
		$options = self::nest_icons_by_brands( $icons );

		foreach ( $options as $brand => $brand_icons ) {
			foreach ( $brand_icons as $icon_id => $icon ) {
				$options[ $brand ][ $icon_id ] = $icon->get_option_icon_description();
			}
		}

		return $options;
	}

	#endregion -- Static Helpers

}

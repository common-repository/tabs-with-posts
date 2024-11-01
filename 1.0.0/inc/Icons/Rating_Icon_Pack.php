<?php

namespace TWRP\Icons;

use RuntimeException;

/**
 * Class that represents a rating icon pack. Usually 3 icons are in this class,
 * an empty one, a half-filled one, and a filled one.
 */
class Rating_Icon_Pack {

	/**
	 * Holds the id of the icon pack.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Holds the brand of the icon pack.
	 *
	 * @var string
	 */
	protected $brand;

	/**
	 * Holds the description of the icon pack.
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Hold the icon for an empty star.
	 *
	 * @var Icon
	 */
	protected $empty_icon;

	/**
	 * Hold the icon for a half-full star.
	 *
	 * @var Icon
	 */
	protected $half_filled_icon;

	/**
	 * Hold the icon for a full star.
	 *
	 * @var Icon
	 */
	protected $filled_icon;

	/**
	 * Construct the rating icon pack.
	 *
	 * @throws RuntimeException If the rating pack id does not exist, or an icon
	 *                          id does not exist.
	 *
	 * @param string $rating_pack_id
	 * @param array $pack_attr
	 */
	public function __construct( $rating_pack_id, $pack_attr ) {
		$this->id               = $rating_pack_id;
		$this->brand            = $pack_attr['brand'];
		$this->description      = $pack_attr['description'];
		$this->empty_icon       = Icon_Factory::get_icon( $pack_attr['empty'] );
		$this->half_filled_icon = Icon_Factory::get_icon( $pack_attr['half'] );
		$this->filled_icon      = Icon_Factory::get_icon( $pack_attr['full'] );
	}

	#region -- Get basic info

	/**
	 * Get the id of the icon pack.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the brand of the icon pack.
	 *
	 * @return string
	 */
	public function get_brand() {
		return $this->brand;
	}

	/**
	 * Get the description of the icon pack.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	#endregion -- Get basic info

	/**
	 * Get the empty icon object.
	 *
	 * @return Icon
	 */
	public function get_empty_icon() {
		return $this->empty_icon;
	}

	/**
	 * Get the half filled icon object.
	 *
	 * @return Icon
	 */
	public function get_half_filled_icon() {
		return $this->half_filled_icon;
	}

	/**
	 * Get the filled icon object.
	 *
	 * @return Icon
	 */
	public function get_filled_icon() {
		return $this->filled_icon;
	}

	/**
	 * Get the icon pack description to be displayed as an option.
	 *
	 * @param bool $with_brand Whether or not to include the brand in the name.
	 * @return string
	 */
	public function get_option_pack_description( $with_brand = false ) {
		$return_description = $this->get_description();

		if ( $with_brand ) {
			$return_description = '[' . $this->get_brand() . '] ' . $return_description;
		}

		return $return_description;
	}

	#region -- Static Helpers

	/**
	 * Make the rating packs of same brand be nested in an array, where the key is the
	 * brand name.
	 *
	 * @param array<Rating_Icon_Pack> $icon_packs
	 * @return array<string,array<string,Rating_Icon_Pack>>
	 */
	public static function nest_packs_by_brands( $icon_packs ) {
		$branded_icons = array();

		foreach ( $icon_packs as $icon_pack ) {
			$brand = $icon_pack->get_brand();

			if ( ! isset( $branded_icons[ $brand ] ) ) {
				$branded_icons[ $brand ] = array();
			}

			$branded_icons[ $brand ][ $icon_pack->get_id() ] = $icon_pack;
		}

		return $branded_icons;
	}

	#endregion -- Static Helpers

}

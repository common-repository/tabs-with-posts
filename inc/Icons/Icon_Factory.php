<?php

namespace TWRP\Icons;

use RuntimeException;

use TWRP\Icons\Date_Icons;
use TWRP\Icons\User_Icons;
use TWRP\Icons\Category_Icons;
use TWRP\Icons\Comments_Icons;
use TWRP\Icons\Comments_Disabled_Icons;
use TWRP\Icons\Views_Icons;
use TWRP\Icons\Rating_Icons;


/**
 * Create Icons or Rating_Icon_Packs. Can also get icons from specific
 * categories.
 *
 * By convention, to make the working more easily, every Id should be composed
 * as: twrp-{icon-meaning}-{brand/author}-[additional-icon-meaning]-{icon-type}.
 */
class Icon_Factory {

	/**
	 * Get an Icon object, based on the id.
	 *
	 * @throws RuntimeException If icon id does not exist.
	 *
	 * @param string $icon_id
	 * @return Icon
	 */
	public static function get_icon( $icon_id ) {
		// Search only in specific category.
		$icon_attr = self::get_icon_attr( $icon_id );

		if ( false === $icon_attr ) {
			throw new RuntimeException();
		}

		$icon = new Icon( $icon_id, $icon_attr );

		return $icon;
	}

	/**
	 * Get a rating icon pack by id.
	 *
	 * @throws RuntimeException If icon id does not exist.
	 *
	 * @param string $rating_pack_id
	 * @return Rating_Icon_Pack
	 */
	public static function get_rating_pack( $rating_pack_id ) {
		// Search only in specific category.
		$packs = self::get_rating_packs_attr();

		if ( ! isset( $packs[ $rating_pack_id ] ) ) {
			throw new RuntimeException();
		}

		$pack_attr = $packs[ $rating_pack_id ];

		$pack = new Rating_Icon_Pack( $rating_pack_id, $pack_attr );

		return $pack;
	}

	#region -- Get specific icon sets

	/**
	 * Get all registered icons that represents the user.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_user_icons() {
		$icons = User_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 * Get all registered icons that represents the date.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_date_icons() {
		$icons = Date_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 * Get all registered icons that represents the category.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_category_icons() {
		$icons = Category_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 * Get all registered icons that represents the comments.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_comment_icons() {
		$icons = Comments_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 * Get all registered icons that represents the disabled comments.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_comment_disabled_icons() {
		$icons = Comments_Disabled_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 *  Get all registered icons that represents the number of views.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_views_icons() {
		$icons = Views_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	/**
	 * Get all registered icons that represents the rating.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_rating_icons() {
		$icons = Rating_Icons::get_definitions();

		$icons_classes = array();
		foreach ( $icons as $icon_id => $icon ) {
			$new_icon                  = new Icon( $icon_id, $icon );
			$icons_classes[ $icon_id ] = $new_icon;
		}

		return $icons_classes;
	}

	#endregion -- Get specific icon sets

	#region -- Get Rating Packs

	/**
	 * Get all rating packs attributes.
	 *
	 * @return array<string,array>
	 */
	public static function get_rating_packs_attr() {
		return Rating_Icons::get_rating_packs();
	}

	/**
	 * Get all rating icons packs.
	 *
	 * @return array<string,Rating_Icon_Pack>
	 */
	public static function get_rating_packs() {
		$rating_packs         = Rating_Icons::get_rating_packs();
		$rating_packs_objects = array();

		foreach ( $rating_packs as $rating_pack_id => $rating_pack ) {
			try {
				$rating_pack = self::get_rating_pack( $rating_pack_id );
			} catch ( RuntimeException $e ) {
				continue;
			}
			$rating_packs_objects[ $rating_pack_id ] = $rating_pack;
		}

		return $rating_packs_objects;
	}

	#endregion -- Get Rating Packs

	#region -- Get all icons

	/**
	 * Get an array with all registered icons attributes.
	 *
	 * @return array<string,array>
	 */
	public static function get_all_icons_attr() {
		return User_Icons::get_definitions() +
			Date_Icons::get_definitions() +
			Category_Icons::get_definitions() +
			Comments_Icons::get_definitions() +
			Comments_Disabled_Icons::get_definitions() +
			Views_Icons::get_definitions() +
			Rating_Icons::get_definitions();
	}

	/**
	 * Get an array with all registered icons.
	 *
	 * @return array<string,Icon>
	 */
	public static function get_all_icons() {
		return self::get_user_icons() +
			self::get_date_icons() +
			self::get_category_icons() +
			self::get_comment_icons() +
			self::get_comment_disabled_icons() +
			self::get_views_icons() +
			self::get_rating_icons();
	}

	#endregion -- Get all icons

	#region -- Get compatible disabled comment icon

	/**
	 * For a comment icon, get the compatible disabled icon.
	 *
	 * @param Icon|string $icon Either an Icon object, or an icon id.
	 * @return Icon|null The compatible disabled comment icon, or null if not found.
	 */
	public static function get_compatible_disabled_comment_icon( $icon ) {
		$icon_id = self::get_compatible_disabled_comment_icon_id( $icon );

		if ( ! is_string( $icon_id ) ) {
			return null;
		}

		try {
			$disabled_icon = self::get_icon( $icon_id );
			return $disabled_icon;
		} catch ( RuntimeException $e ) { // @codeCoverageIgnore
			return null; // @codeCoverageIgnore
		}
	}

	/**
	 * For a comment icon, get the compatible disabled icon.
	 *
	 * @param Icon|string $icon Either an Icon object, or an icon id.
	 * @return string|null The compatible disabled comment icon, or null if not found.
	 */
	public static function get_compatible_disabled_comment_icon_id( $icon ) {
		if ( $icon instanceof Icon ) {
			$icon_id = $icon->get_id();
		} elseif ( is_string( $icon ) ) {
			$icon_id = $icon;
		} else {
			return null;
		}

		$compatible_icons = self::get_compatibles_disabled_comments_attr();

		if ( isset( $compatible_icons[ $icon_id ] ) ) {
			return $compatible_icons[ $icon_id ];
		}

		return null;
	}

	/**
	 * Get an array with the compatible disabled comment icons. The key is the
	 * comment icon, while the value is the disabled comment that corresponds.
	 *
	 * @return array<string,string>
	 */
	public static function get_compatibles_disabled_comments_attr() {
		return Comments_Disabled_Icons::get_comment_disabled_compatibles();
	}

	#endregion -- Get compatible disabled comment icon

	#region -- Get an icon attr fast

	/**
	 * Get the icon attributes array, or false if do not exist.
	 *
	 * This function is speeded by searching only in the needed category.
	 *
	 * @param string $icon_id
	 * @return array|false
	 */
	public static function get_icon_attr( $icon_id ) {
		try {
			$icon_category = Icon_Categories::get_icon_category( $icon_id );
		} catch ( RuntimeException $e ) {
			return false;
		}

		$definitions_class = Icon_Categories::get_definitions_class_by_category( $icon_category );
		if ( false === $definitions_class ) {
			return false;
		}
		$icons_attr = $definitions_class::get_definitions();

		if ( isset( $icons_attr[ $icon_id ] ) ) {
			return $icons_attr[ $icon_id ];
		}

		return false; // @codeCoverageIgnore
	}

	#endregion -- Get an icon attr fast

}

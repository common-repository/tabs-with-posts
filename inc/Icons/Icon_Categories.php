<?php

namespace TWRP\Icons;

use RuntimeException;
use TWRP\Utils\Frontend_Translation;

/**
 * Class used to hold/retrieve values that needs to be different between each
 * category of icons, and to get the category of an icon by looking at the id.
 */
class Icon_Categories {

	/**
	 * Numerical index, representing a user icon.
	 */
	const USER_ICON = 1;

	/**
	 * Numerical index, representing a date icon.
	 */
	const DATE_ICON = 2;

	/**
	 * Numerical index, representing a views icon.
	 */
	const VIEWS_ICON = 3;

	/**
	 * Numerical index, representing a rating icon.
	 */
	const RATING_ICON = 4;

	/**
	 * Numerical index, representing a rating icon.
	 */
	const CATEGORY_ICON = 5;

	/**
	 * Numerical index, representing a comment icon.
	 */
	const COMMENT_ICON = 6;

	/**
	 * Numerical index, representing a disabled comment icon.
	 */
	const DISABLED_COMMENT_ICON = 7;

	/**
	 * Each icon category has a folder where icons are found, these are declared
	 * here.
	 */
	const ICON_CATEGORY_FOLDER = array(
		self::USER_ICON             => 'user',
		self::DATE_ICON             => 'date',
		self::VIEWS_ICON            => 'views',
		self::RATING_ICON           => 'rating',
		self::CATEGORY_ICON         => 'taxonomy',
		self::COMMENT_ICON          => 'comments',
		self::DISABLED_COMMENT_ICON => 'disabled-comments',
	);

	/**
	 * Each icon category will be wrapped in a HTML element, with a class name
	 * of one of these.
	 */
	const ICON_CATEGORY_CLASS = array(
		self::USER_ICON             => 'twrp-i--user',
		self::DATE_ICON             => 'twrp-i--date',
		self::VIEWS_ICON            => 'twrp-i--views',
		self::RATING_ICON           => 'twrp-i--rating',
		self::CATEGORY_ICON         => 'twrp-i--category',
		self::COMMENT_ICON          => 'twrp-i--comment',
		self::DISABLED_COMMENT_ICON => 'twrp-i--disabled-comment',
	);

	/**
	 * Get an array, where the key is the icon category index, and the value
	 * is the aria label for these type of icons.
	 *
	 * @return array
	 */
	public static function get_category_aria_label() {
		$aria_labels = array(
			self::USER_ICON             => Frontend_Translation::get_translation( Frontend_Translation::ARIA_AUTHOR ),
			self::DATE_ICON             => Frontend_Translation::get_translation( Frontend_Translation::ARIA_DATE ),
			self::VIEWS_ICON            => Frontend_Translation::get_translation( Frontend_Translation::ARIA_VIEWS ),
			self::RATING_ICON           => Frontend_Translation::get_translation( Frontend_Translation::ARIA_RATING ),
			self::CATEGORY_ICON         => Frontend_Translation::get_translation( Frontend_Translation::ARIA_CATEGORY ),
			self::COMMENT_ICON          => Frontend_Translation::get_translation( Frontend_Translation::ARIA_COMMENTS ),
			self::DISABLED_COMMENT_ICON => Frontend_Translation::get_translation( Frontend_Translation::ARIA_COMMENTS_DISABLED ),
		);

		return $aria_labels;
	}

	/**
	 * Get a numeric value, representing the icon category. The numeric value
	 * is a constant declared in this class. Return false otherwise.
	 *
	 * @throws RuntimeException In case the numeric value cannot be retrieved.
	 *
	 * @param string $icon_id
	 * @return int
	 */
	public static function get_icon_category( $icon_id ) {
		if ( strstr( $icon_id, 'views' ) ) {
			return self::VIEWS_ICON;
		}

		if ( strstr( $icon_id, 'cal' ) ) {
			return self::DATE_ICON;
		}

		if ( strstr( $icon_id, 'dcom' ) ) {
			return self::DISABLED_COMMENT_ICON;
		}

		if ( strstr( $icon_id, 'com' ) && ! strstr( $icon_id, 'dcom' ) ) {
			return self::COMMENT_ICON;
		}

		if ( strstr( $icon_id, 'user' ) ) {
			return self::USER_ICON;
		}

		if ( strstr( $icon_id, 'tax' ) ) {
			return self::CATEGORY_ICON;
		}

		if ( strstr( $icon_id, 'rat' ) ) {
			return self::RATING_ICON;
		}

		throw new RuntimeException();
	}

	/**
	 * For a category id, get the definition class attached to it.
	 *
	 * @param int $category_id
	 * @return Icon_Definitions|false
	 */
	public static function get_definitions_class_by_category( $category_id ) {
		if ( self::USER_ICON === $category_id ) {
			return new User_Icons();
		}

		if ( self::DATE_ICON === $category_id ) {
			return new Date_Icons();
		}

		if ( self::CATEGORY_ICON === $category_id ) {
			return new Category_Icons();
		}

		if ( self::COMMENT_ICON === $category_id ) {
			return new Comments_Icons();
		}

		if ( self::DISABLED_COMMENT_ICON === $category_id ) {
			return new Comments_Disabled_Icons();
		}

		if ( self::VIEWS_ICON === $category_id ) {
			return new Views_Icons();
		}

		if ( self::RATING_ICON === $category_id ) {
			return new Rating_Icons();
		}

		return false;
	}
}

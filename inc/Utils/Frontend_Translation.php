<?php

namespace TWRP\Utils;

use TWRP\Utils\Directory_Utils;
use TWRP\Database\General_Options;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;

/**
 * This class is used to control the retrieving of all translated strings that
 * are displayed in the frontend.
 *
 * To add a translation:
 * 1. Add the translation to the function get_all_translations.
 * 2. Add the option to change the frontend translation from general settings.
 * (See General_Options class how to do it).
 * 3. Add the option name to get_all_translation_option_name() function.
 */
class Frontend_Translation {

	const SHOW_MORE_POSTS     = 'show_more_posts';
	const POST_NO_TITLE       = 'post_no_title';
	const POST_NO_THUMBNAIL   = 'post_no_thumbnail';
	const HUMAN_RELATIVE_DATE = 'human_relative_date';
	const WIDGET_FAIL_TO_LOAD = 'widget_failed_to_load';
	const NO_POSTS_TEXT       = 'no_posts_text';

	const ARIA_AUTHOR            = 'aria_author';
	const ARIA_DATE              = 'aria_date';
	const ARIA_VIEWS             = 'aria_views';
	const ARIA_RATING            = 'aria_rating';
	const ARIA_CATEGORY          = 'aria_category';
	const ARIA_COMMENTS          = 'aria_comments';
	const ARIA_COMMENTS_DISABLED = 'aria_comments_disabled';

	const ABBREVIATION_FOR_THOUSANDS = 'thousands_abbreviation';
	const ABBREVIATION_FOR_MILLIONS  = 'millions_abbreviation';
	const ABBREVIATION_FOR_BILLIONS  = 'billions_abbreviation';

	/**
	 * Holds the translations in a cache, to not retrieve them every time.
	 *
	 * @var array
	 */
	protected static $cached_translations = array();

	use After_Setup_Theme_Init_Trait;

	/**
	 * Load the plugin text domain.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		$plugin_folder      = trim( Directory_Utils::get_plugin_directory_path(), '/' );
		$plugin_folder_name = strrchr( $plugin_folder, '/' );

		if ( false === $plugin_folder_name ) {
			return;
		}

		$plugin_folder_name = trim( $plugin_folder_name, '/' );

		load_plugin_textdomain( 'tabs-with-posts', false, $plugin_folder_name . '/languages' );
	}

	/**
	 * Get a frontend translation.
	 *
	 * @param string $translation_name Use one of the defined constants from this class.
	 * @return string Empty string if the translation doesn't exist.
	 */
	public static function get_translation( $translation_name ) {
		$all_translation_options = self::get_all_translation_option_name();
		$option_name             = '';
		if ( isset( $all_translation_options[ $translation_name ] ) ) {
			$option_name = $all_translation_options[ $translation_name ];
		}
		$option = General_Options::get_option( $option_name );

		if ( is_string( $option ) && ! empty( trim( $option ) ) ) {
			$translation = $option;
		} else {
			$translations = self::get_all_localized_translations();

			if ( isset( $translations[ $translation_name ] ) ) {
				$translation = $translations[ $translation_name ];
			} else {
				$translation = '';
			}
		}

		return $translation;
	}

	/**
	 * Return an array, where the key is the translation name, and the value is
	 * the localized translation.
	 *
	 * @return array
	 */
	public static function get_all_localized_translations() {
		if ( ! empty( self::$cached_translations ) ) {
			return self::$cached_translations;
		}

		$localized_translations = array(
			self::SHOW_MORE_POSTS            => __( 'Show more posts', 'tabs-with-posts' ),
			/* translators: Text to display if a post has no title. */
			self::POST_NO_TITLE              => _x( '&laquo; No title &raquo;', 'title if the post has no title.', 'tabs-with-posts' ),
			self::POST_NO_THUMBNAIL          => __( 'Post with no thumbnail', 'tabs-with-posts' ),
			/* translators: %s: a date representation, in human language. Ex: 2 days ago, 3 weeks ago,  1 month ago... etc. the %s is expected to be already translated. */
			self::HUMAN_RELATIVE_DATE        => __( '%s ago', 'tabs-with-posts' ),
			self::WIDGET_FAIL_TO_LOAD        => __( 'Loading articles failed, please refresh the page to try again.', 'tabs-with-posts' ),
			self::NO_POSTS_TEXT              => __( 'Unfortunately, no posts are here.', 'tabs-with-posts' ),

			self::ARIA_AUTHOR                => _x( 'Author', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_DATE                  => _x( 'Date', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_VIEWS                 => _x( 'Views', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_RATING                => _x( 'Rating', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_CATEGORY              => _x( 'Category', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_COMMENTS              => _x( 'Comments', 'Noun, accessibility text', 'tabs-with-posts' ),
			self::ARIA_COMMENTS_DISABLED     => _x( 'Comments are disabled', 'Noun, accessibility text', 'tabs-with-posts' ),

			self::ABBREVIATION_FOR_THOUSANDS => _x( 'K', 'Abbreviation for Thousands', 'tabs-with-posts' ),
			self::ABBREVIATION_FOR_MILLIONS  => _x( 'M', 'Abbreviation for Millions', 'tabs-with-posts' ),
			self::ABBREVIATION_FOR_BILLIONS  => _x( 'B', 'Abbreviation for Billions', 'tabs-with-posts' ),
		);

		self::$cached_translations = $localized_translations;
		return $localized_translations;
	}

	/**
	 * Return an array with the translation name and the option name to change
	 * that translation.
	 *
	 * @return array
	 */
	protected static function get_all_translation_option_name() {
		return array(
			self::SHOW_MORE_POSTS            => General_Options::SHOW_MORE_POSTS_TEXT,
			self::POST_NO_TITLE              => General_Options::POST_NO_TITLE_TEXT,
			self::POST_NO_THUMBNAIL          => General_Options::POST_WITH_NO_THUMBNAIL_TEXT,
			self::HUMAN_RELATIVE_DATE        => General_Options::DATE_RELATIVE_TEXT,
			self::WIDGET_FAIL_TO_LOAD        => General_Options::FAIL_TO_LOAD_WIDGET_TEXT,
			self::NO_POSTS_TEXT              => General_Options::NO_POSTS_TEXT,

			self::ARIA_AUTHOR                => General_Options::ARIA_AUTHOR_TEXT,
			self::ARIA_DATE                  => General_Options::ARIA_DATE_TEXT,
			self::ARIA_VIEWS                 => General_Options::ARIA_VIEWS_TEXT,
			self::ARIA_RATING                => General_Options::ARIA_RATING_TEXT,
			self::ARIA_CATEGORY              => General_Options::ARIA_CATEGORY_TEXT,
			self::ARIA_COMMENTS              => General_Options::ARIA_COMMENTS_TEXT,
			self::ARIA_COMMENTS_DISABLED     => General_Options::ARIA_COMMENTS_ARE_DISABLED_TEXT,

			self::ABBREVIATION_FOR_THOUSANDS => General_Options::ABBREVIATION_FOR_THOUSANDS,
			self::ABBREVIATION_FOR_MILLIONS  => General_Options::ABBREVIATION_FOR_MILLIONS,
			self::ABBREVIATION_FOR_BILLIONS  => General_Options::ABBREVIATION_FOR_BILLIONS,
		);
	}

}

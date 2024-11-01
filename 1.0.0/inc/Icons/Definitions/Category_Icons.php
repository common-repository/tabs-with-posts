<?php

namespace TWRP\Icons;

/**
 * Class that holds all category icon definitions.
 */
class Category_Icons implements Icon_Definitions {

	/**
	 * Get all registered icons that represents the category.
	 *
	 * Keywords to search for: tag, bookmark, hashtag, taxonomy, category, folder, directory.
	 *
	 * @return array<string,array>
	 */
	public static function get_definitions() {
		$filled_type   = _x( 'Filled', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$outlined_type = _x( 'Outlined', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$duotone_type  = _x( 'DuoTone', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$thin_type     = _x( 'Thin', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$sharp_type    = _x( 'Sharp', 'backend, icon type(aspect)', 'tabs-with-posts' );

		$bookmark_description    = _x( 'Bookmark', 'backend, icon name(description)', 'tabs-with-posts' );
		$bookmark_2_description  = _x( 'Bookmark 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$bookmark_3_description  = _x( 'Bookmark 3', 'backend, icon name(description)', 'tabs-with-posts' );
		$bookmarks_description   = _x( 'Bookmarks', 'backend, icon name(description)', 'tabs-with-posts' );
		$bookmarks_2_description = _x( 'Bookmarks 2', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_bookmarks_description = _x( 'Ios Bookmarks', 'backend, icon name(description)', 'tabs-with-posts' );
		$folder_description        = _x( 'Folder', 'backend, icon name(description)', 'tabs-with-posts' );
		$folder_open_description   = _x( 'Folder Open', 'backend, icon name(description)', 'tabs-with-posts' );
		$folders_description       = _x( 'Folders', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_folder_description = _x( 'Ios Folder', 'backend, icon name(description)', 'tabs-with-posts' );
		$hashtag_description    = _x( 'Hashtag', 'backend, icon name(description)', 'tabs-with-posts' );
		$label_description      = _x( 'Label', 'backend, icon name(description)', 'tabs-with-posts' );
		$label_2_description    = _x( 'Label 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$tag_heart_description  = _x( 'Tag Heart', 'backend, icon name(description)', 'tabs-with-posts' );

		$tag_description    = _x( 'Tag', 'backend, icon name(description)', 'tabs-with-posts' );
		$tag_2_description  = _x( 'Tag 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$tags_description   = _x( 'Tags', 'backend, icon name(description)', 'tabs-with-posts' );
		$tags_2_description = _x( 'Tags 2', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_tag_description = _x( 'Ios Tag', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_tags_description = _x( 'Ios Tags', 'backend, icon name(description)', 'tabs-with-posts' );

		$registered_category_vectors = array(

			#region -- TWRP Icons

			// No Icons...

			#endregion -- TWRP Icons

			#region -- FontAwesome Icons

			'twrp-tax-fa-f'      => array(
				'brand'       => 'FontAwesome',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-fa-t-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $tags_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-fa-f-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-fa-f-ol'   => array(
				'brand'       => 'FontAwesome',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-fa-fo-f'   => array(
				'brand'       => 'FontAwesome',
				'description' => $folder_open_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-open-filled.svg',
			),

			'twrp-tax-fa-fo-ol'  => array(
				'brand'       => 'FontAwesome',
				'description' => $folder_open_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-open-outlined.svg',
			),

			'twrp-tax-fa-h-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $hashtag_description,
				'type'        => $filled_type,
				'file_name'   => 'hashtag-filled.svg',
			),

			'twrp-tax-fa-b-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-fa-b-ol'   => array(
				'brand'       => 'FontAwesome',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			#endregion -- FontAwesome Icons

			#region -- Google Icons

			'twrp-tax-goo-f'     => array(
				'brand'       => 'Google',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-ol'    => array(
				'brand'       => 'Google',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-dt'    => array(
				'brand'       => 'Google',
				'description' => $tag_description,
				'type'        => $duotone_type,
				'file_name'   => 'tag-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-sh'    => array(
				'brand'       => 'Google',
				'description' => $tag_description,
				'type'        => $sharp_type,
				'file_name'   => 'tag-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-f-f'   => array(
				'brand'       => 'Google',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-goo-f-ol'  => array(
				'brand'       => 'Google',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-goo-f-dt'  => array(
				'brand'       => 'Google',
				'description' => $folder_description,
				'type'        => $duotone_type,
				'file_name'   => 'folder-duotone.svg',
			),

			'twrp-tax-goo-f-sh'  => array(
				'brand'       => 'Google',
				'description' => $folder_description,
				'type'        => $sharp_type,
				'file_name'   => 'folder-sharp.svg',
			),

			'twrp-tax-goo-h-f'   => array(
				'brand'       => 'Google',
				'description' => $tag_heart_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-heart-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-h-ol'  => array(
				'brand'       => 'Google',
				'description' => $tag_heart_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-heart-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-h-dt'  => array(
				'brand'       => 'Google',
				'description' => $tag_heart_description,
				'type'        => $duotone_type,
				'file_name'   => 'tag-heart-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-h-sh'  => array(
				'brand'       => 'Google',
				'description' => $tag_heart_description,
				'type'        => $sharp_type,
				'file_name'   => 'tag-heart-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l-f'   => array(
				'brand'       => 'Google',
				'description' => $label_description,
				'type'        => $filled_type,
				'file_name'   => 'label-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l-ol'  => array(
				'brand'       => 'Google',
				'description' => $label_description,
				'type'        => $outlined_type,
				'file_name'   => 'label-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l-dt'  => array(
				'brand'       => 'Google',
				'description' => $label_description,
				'type'        => $duotone_type,
				'file_name'   => 'label-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l-sh'  => array(
				'brand'       => 'Google',
				'description' => $label_description,
				'type'        => $sharp_type,
				'file_name'   => 'label-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l2-f'  => array(
				'brand'       => 'Google',
				'description' => $label_2_description,
				'type'        => $filled_type,
				'file_name'   => 'label-2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-l2-dt' => array(
				'brand'       => 'Google',
				'description' => $label_2_description,
				'type'        => $duotone_type,
				'file_name'   => 'label-2-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-goo-b-f'   => array(
				'brand'       => 'Google',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-goo-b-ol'  => array(
				'brand'       => 'Google',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-goo-b-dt'  => array(
				'brand'       => 'Google',
				'description' => $bookmark_description,
				'type'        => $duotone_type,
				'file_name'   => 'bookmark-duotone.svg',
			),

			'twrp-tax-goo-b-sh'  => array(
				'brand'       => 'Google',
				'description' => $bookmark_description,
				'type'        => $sharp_type,
				'file_name'   => 'bookmark-sharp.svg',
			),

			'twrp-tax-goo-bs-f'  => array(
				'brand'       => 'Google',
				'description' => $bookmarks_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmarks-filled.svg',
			),

			'twrp-tax-goo-bs-ol' => array(
				'brand'       => 'Google',
				'description' => $bookmarks_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmarks-outlined.svg',
			),

			'twrp-tax-goo-bs-dt' => array(
				'brand'       => 'Google',
				'description' => $bookmarks_description,
				'type'        => $duotone_type,
				'file_name'   => 'bookmarks-duotone.svg',
			),

			'twrp-tax-goo-bs-sh' => array(
				'brand'       => 'Google',
				'description' => $bookmarks_description,
				'type'        => $sharp_type,
				'file_name'   => 'bookmarks-sharp.svg',
			),

			#endregion -- Google Icons

			#region -- Dashicons Icons

			'twrp-tax-di-f'      => array(
				'brand'       => 'Dashicons',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-di-c-f'    => array(
				'brand'       => 'Dashicons',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-di-c2-f'   => array(
				'brand'       => 'Dashicons',
				'description' => $folder_open_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-2-filled.svg',
			),

			#endregion -- Dashicons Icons

			#region -- Foundation Icons

			'twrp-tax-fi-f'      => array(
				'brand'       => 'Foundation',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-fi-t-f'    => array(
				'brand'       => 'Foundation',
				'description' => $tags_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-fi-f-f'    => array(
				'brand'       => 'Foundation',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			#endregion -- Foundation Icons

			#region -- Ionicons Icons

			'twrp-tax-ii-f'      => array(
				'brand'       => 'Ionicons',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-ol'     => array(
				'brand'       => 'Ionicons',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-sh'     => array(
				'brand'       => 'Ionicons',
				'description' => $tag_description,
				'type'        => $sharp_type,
				'file_name'   => 'tag-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-i-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $ios_tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-t-f'    => array(
				'brand'       => 'Ionicons',
				'description' => $tags_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-t-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-t-sh'   => array(
				'brand'       => 'Ionicons',
				'description' => $tags_description,
				'type'        => $sharp_type,
				'file_name'   => 'tags-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-it-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ii-f-f'    => array(
				'brand'       => 'Ionicons',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-ii-f-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-ii-fo-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $folder_open_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-open-filled.svg',
			),

			'twrp-tax-ii-fo-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $folder_open_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-open-outlined.svg',
			),

			'twrp-tax-ii-iof-f'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_folder_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-folder-filled.svg',
			),

			'twrp-tax-ii-iof-ol' => array(
				'brand'       => 'Ionicons',
				'description' => $ios_folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-folder-outlined.svg',
			),

			'twrp-tax-ii-b-f'    => array(
				'brand'       => 'Ionicons',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-ii-b-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-ii-b-sh'   => array(
				'brand'       => 'Ionicons',
				'description' => $bookmark_description,
				'type'        => $sharp_type,
				'file_name'   => 'bookmark-sharp.svg',
			),

			'twrp-tax-ii-bs-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $bookmarks_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmarks-filled.svg',
			),

			'twrp-tax-ii-bs-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $bookmarks_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmarks-outlined.svg',
			),

			'twrp-tax-ii-bs-sh'  => array(
				'brand'       => 'Ionicons',
				'description' => $bookmarks_description,
				'type'        => $sharp_type,
				'file_name'   => 'bookmarks-sharp.svg',
			),

			'twrp-tax-ii-ibs-ol' => array(
				'brand'       => 'Ionicons',
				'description' => $ios_bookmarks_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-bookmark-outlined.svg',
			),

			#endregion -- Ionicons Icons

			#region -- IconMonstr Icons

			'twrp-tax-im-f'      => array(
				'brand'       => 'IconMonstr',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-ol'     => array(
				'brand'       => 'IconMonstr',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t'      => array(
				'brand'       => 'IconMonstr',
				'description' => $tag_description,
				'type'        => $thin_type,
				'file_name'   => 'tag-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $tags_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t-t'    => array(
				'brand'       => 'IconMonstr',
				'description' => $tags_description,
				'type'        => $thin_type,
				'file_name'   => 'tags-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t2-f'   => array(
				'brand'       => 'IconMonstr',
				'description' => $tags_2_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-im-t2-ol'  => array(
				'brand'       => 'IconMonstr',
				'description' => $tags_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-2-outlined.svg',
			),

			'twrp-tax-im-f-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-im-f-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-im-f-t'    => array(
				'brand'       => 'IconMonstr',
				'description' => $folder_description,
				'type'        => $thin_type,
				'file_name'   => 'folder-thin.svg',
			),

			'twrp-tax-im-b-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-im-b-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-im-b-t'    => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_description,
				'type'        => $thin_type,
				'file_name'   => 'bookmark-thin.svg',
			),

			'twrp-tax-im-b2-f'   => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_2_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-2-filled.svg',
			),

			'twrp-tax-im-b2-ol'  => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-2-outlined.svg',
			),

			'twrp-tax-im-b3-f'   => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_3_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-3-filled.svg',
			),

			'twrp-tax-im-b3-ol'  => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmark_3_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-3-outlined.svg',
			),

			'twrp-tax-im-bs-f'   => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmarks_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmarks-filled.svg',
			),

			'twrp-tax-im-bs-ol'  => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmarks_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmarks-outlined.svg',
			),

			'twrp-tax-im-bs2-f'  => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmarks_2_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmarks-2-filled.svg',
			),

			'twrp-tax-im-bs2-ol' => array(
				'brand'       => 'IconMonstr',
				'description' => $bookmarks_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmarks-2-outlined.svg',
			),

			'twrp-tax-im-h-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $hashtag_description,
				'type'        => $filled_type,
				'file_name'   => 'hashtag-filled.svg',
			),

			#endregion -- IconMonstr Icons

			#region -- Captain Icons

			'twrp-tax-ci-f'      => array(
				'brand'       => 'Captain Icons',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ci-2-f'    => array(
				'brand'       => 'Captain Icons',
				'description' => $tag_2_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ci-f-f'    => array(
				'brand'       => 'Captain Icons',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-ci-b-f'    => array(
				'brand'       => 'Captain Icons',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			#endregion -- Captain Icons

			#region -- Feather Icons

			'twrp-tax-fe-ol'     => array(
				'brand'       => 'Feather',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-fe-f-ol'   => array(
				'brand'       => 'Feather',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-fe-b-ol'   => array(
				'brand'       => 'Feather',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-fe-h-f'    => array(
				'brand'       => 'Feather',
				'description' => $hashtag_description,
				'type'        => $filled_type,
				'file_name'   => 'hashtag-filled.svg',
			),

			#endregion -- Feather Icons

			#region -- Jam Icons

			'twrp-tax-ji-f'      => array(
				'brand'       => 'JamIcons',
				'description' => $tag_description,
				'type'        => $filled_type,
				'file_name'   => 'tag-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ji-ol'     => array(
				'brand'       => 'JamIcons',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ji-t-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $tags_description,
				'type'        => $filled_type,
				'file_name'   => 'tags-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ji-t-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ji-f-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-ji-f-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-ji-b-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-ji-b-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-ji-h-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $hashtag_description,
				'type'        => $filled_type,
				'file_name'   => 'hashtag-filled.svg',
			),

			#endregion -- Jam Icons

			#region -- Linea Icons

			'twrp-tax-li-ol'     => array(
				'brand'       => 'Linea',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-li-t-ol'   => array(
				'brand'       => 'Linea',
				'description' => $tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-li-f-ol'   => array(
				'brand'       => 'Linea',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-li-f2-ol'  => array(
				'brand'       => 'Linea',
				'description' => $folders_description,
				'type'        => $outlined_type,
				'file_name'   => 'folders-outlined.svg',
			),

			'twrp-tax-li-b-ol'   => array(
				'brand'       => 'Linea',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			#endregion -- Linea Icons

			#region -- Octicons Icons

			'twrp-tax-oi-ol'     => array(
				'brand'       => 'Octicons',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-oi-t'      => array(
				'brand'       => 'Octicons',
				'description' => $tag_description,
				'type'        => $thin_type,
				'file_name'   => 'tag-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-oi-f-f'    => array(
				'brand'       => 'Octicons',
				'description' => $folder_description,
				'type'        => $filled_type,
				'file_name'   => 'folder-filled.svg',
			),

			'twrp-tax-oi-f-ol'   => array(
				'brand'       => 'Octicons',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-oi-b-f'    => array(
				'brand'       => 'Octicons',
				'description' => $bookmark_description,
				'type'        => $filled_type,
				'file_name'   => 'bookmark-filled.svg',
			),

			'twrp-tax-oi-b-ol'   => array(
				'brand'       => 'Octicons',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			'twrp-tax-oi-b-t'    => array(
				'brand'       => 'Octicons',
				'description' => $bookmark_description,
				'type'        => $thin_type,
				'file_name'   => 'bookmark-thin.svg',
			),

			#endregion -- Octicons Icons

			#region -- Typicons Icons

			'twrp-tax-ti-ol'     => array(
				'brand'       => 'Typicons',
				'description' => $tag_description,
				'type'        => $outlined_type,
				'file_name'   => 'tag-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ti-t-ol'   => array(
				'brand'       => 'Typicons',
				'description' => $tags_description,
				'type'        => $outlined_type,
				'file_name'   => 'tags-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-tax-ti-f-ol'   => array(
				'brand'       => 'Typicons',
				'description' => $folder_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-outlined.svg',
			),

			'twrp-tax-ti-fo-ol'  => array(
				'brand'       => 'Typicons',
				'description' => $folder_open_description,
				'type'        => $outlined_type,
				'file_name'   => 'folder-open-outlined.svg',
			),

			'twrp-tax-ti-b-ol'   => array(
				'brand'       => 'Typicons',
				'description' => $bookmark_description,
				'type'        => $outlined_type,
				'file_name'   => 'bookmark-outlined.svg',
			),

			#endregion -- Typicons Icons

		);

		return $registered_category_vectors;
	}

}

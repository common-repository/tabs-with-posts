<?php

namespace TWRP\Icons;

/**
 * Class that holds all comments disabled icon definitions.
 */
class Comments_Disabled_Icons implements Icon_Definitions {

	/**
	 * Get all registered icons that represents the disabled comments.
	 *
	 * @return array<string,array>
	 */
	public static function get_definitions() {
		$filled_type   = _x( 'Filled', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$outlined_type = _x( 'Outlined', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$duotone_type  = _x( 'DuoTone', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$thin_type     = _x( 'Thin', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$sharp_type    = _x( 'Sharp', 'backend, icon type(aspect)', 'tabs-with-posts' );

		$comment_disable_description      = _x( 'Comment Disabled', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_disable_description     = _x( 'Comments Disabled', 'backend, icon name(description)', 'tabs-with-posts' );
		$comment_alt_disable_description  = _x( 'Comment Alt Disabled', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_alt_disable_description = _x( 'Comments Alt Disabled', 'backend, icon name(description)', 'tabs-with-posts' );

		$registered_disabled_comments_vectors = array(

			#region -- TWRP Icons

			'twrp-dcom-twrp-c-f'    => array(
				'brand'       => 'TWRP',
				'description' => $comment_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'comment-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c-ol'   => array(
				'brand'       => 'TWRP',
				'description' => $comment_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'comment-outlined.svg',
			),

			'twrp-dcom-twrp-c-dt'   => array(
				'brand'       => 'TWRP',
				'description' => $comment_disable_description,
				'type'        => $duotone_type,
				'file_name'   => 'comment-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c-sh'   => array(
				'brand'       => 'TWRP',
				'description' => $comment_disable_description,
				'type'        => $sharp_type,
				'file_name'   => 'comment-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c-t'    => array(
				'brand'       => 'TWRP',
				'description' => $comment_disable_description,
				'type'        => $thin_type,
				'file_name'   => 'comment-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c2-f'   => array(
				'brand'       => 'TWRP',
				'description' => $comment_alt_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'comment-alt-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c2-ol'  => array(
				'brand'       => 'TWRP',
				'description' => $comment_alt_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'comment-alt-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c2-dt'  => array(
				'brand'       => 'TWRP',
				'description' => $comment_alt_disable_description,
				'type'        => $duotone_type,
				'file_name'   => 'comment-alt-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-c2-t'   => array(
				'brand'       => 'TWRP',
				'description' => $comment_alt_disable_description,
				'type'        => $thin_type,
				'file_name'   => 'comment-alt-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-dcom-twrp-cs-f'   => array(
				'brand'       => 'TWRP',
				'description' => $comments_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
			),

			'twrp-dcom-twrp-cs-ol'  => array(
				'brand'       => 'TWRP',
				'description' => $comments_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
			),

			'twrp-dcom-twrp-cs-dt'  => array(
				'brand'       => 'TWRP',
				'description' => $comments_disable_description,
				'type'        => $duotone_type,
				'file_name'   => 'comments-duotone.svg',
			),

			'twrp-dcom-twrp-cs-sh'  => array(
				'brand'       => 'TWRP',
				'description' => $comments_disable_description,
				'type'        => $sharp_type,
				'file_name'   => 'comments-sharp.svg',
			),

			'twrp-dcom-twrp-cs-t'   => array(
				'brand'       => 'TWRP',
				'description' => $comments_disable_description,
				'type'        => $thin_type,
				'file_name'   => 'comments-thin.svg',
			),

			'twrp-dcom-twrp-cs2-f'  => array(
				'brand'       => 'TWRP',
				'description' => $comments_alt_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-alt-filled.svg',
			),

			'twrp-dcom-twrp-cs2-ol' => array(
				'brand'       => 'TWRP',
				'description' => $comments_alt_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-alt-outlined.svg',
			),

			'twrp-dcom-twrp-cs2-dt' => array(
				'brand'       => 'TWRP',
				'description' => $comments_alt_disable_description,
				'type'        => $duotone_type,
				'file_name'   => 'comments-alt-duotone.svg',
			),

			'twrp-dcom-twrp-cs2-t'  => array(
				'brand'       => 'TWRP',
				'description' => $comments_alt_disable_description,
				'type'        => $thin_type,
				'file_name'   => 'comments-alt-thin.svg',
			),

			#endregion -- TWRP Icons

			#region -- FontAwesome Icons

			'twrp-dcom-fa-f'        => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- FontAwesome Icons

			#region -- Google Icons

			// No icons...

			#endregion -- Google Icons

			#region -- Dashicons

			'twrp-dcom-di-f'        => array(
				'brand'       => 'Dashicons',
				'description' => $comment_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Dashicons

			#region -- Foundation Icons

			// No icons...

			#endregion -- Foundation Icons

			#region -- Ionicons Icons

			// No Icons...

			#endregion -- Ionicons Icons

			#region -- IconMonstr Icons

			'twrp-dcom-im-f'        => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_disable_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-dcom-im-ol'       => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			#endregion -- IconMonstr Icons

			#region -- Captain Icons

			// No Icons...

			#endregion -- Captain Icons

			#region -- Feather Icons

			// No Icons...

			#endregion -- Feather Icons

			#region -- Jam Icons

			// No Icons...

			#endregion -- Jam Icons

			#region -- Linea Icons

			'twrp-dcom-li-ol'       => array(
				'brand'       => 'Linea',
				'description' => $comment_disable_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Linea Icons

			#region -- Octicons Icons

			// No Icons...

			#endregion -- Octicons Icons

			#region -- Typicons Icons

			// No Icons...

			#endregion -- Typicons Icons
		);

		return $registered_disabled_comments_vectors;
	}

	/**
	 * Get an array where each key is a comment id, and the value is a disable
	 * comment id, that is compatible(best to look the same) to the comment.
	 *
	 * @return array
	 */
	public static function get_comment_disabled_compatibles() {
		$comment_disable_compatibles = array(

			// FontAwesome.
			'twrp-com-fa-f'       => 'twrp-dcom-twrp-c2-f',
			'twrp-com-fa-ol'      => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-fa-2-f'     => 'twrp-dcom-twrp-c-f',
			'twrp-com-fa-2-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-fa-dots-f'  => 'twrp-dcom-twrp-c2-f',
			'twrp-com-fa-dots-ol' => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-fa-comm-f'  => 'twrp-dcom-twrp-c2-f',
			'twrp-com-fa-comm-ol' => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-fa-do-f'    => 'twrp-dcom-twrp-c2-f',

			// Google.
			'twrp-com-goo-f'      => 'twrp-dcom-twrp-c-f',
			'twrp-com-goo-ol'     => 'twrp-dcom-twrp-c-ol',
			'twrp-com-goo-dt'     => 'twrp-dcom-twrp-c-dt',
			'twrp-com-goo-sh'     => 'twrp-dcom-twrp-c-sh',
			'twrp-com-goo-2-f'    => 'twrp-dcom-twrp-c-f',
			'twrp-com-goo-2-ol'   => 'twrp-dcom-twrp-c-ol',
			'twrp-com-goo-2-dt'   => 'twrp-dcom-twrp-c-dt',
			'twrp-com-goo-2-sh'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-goo-f-f'    => 'twrp-dcom-twrp-c-f',
			'twrp-com-goo-f-ol'   => 'twrp-dcom-twrp-c-ol',
			'twrp-com-goo-f-dt'   => 'twrp-dcom-twrp-c-dt',
			'twrp-com-goo-f-sh'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-goo-b-f'    => 'twrp-dcom-twrp-c-f',
			'twrp-com-goo-b-ol'   => 'twrp-dcom-twrp-c-ol',
			'twrp-com-goo-b-dt'   => 'twrp-dcom-twrp-c-dt',
			'twrp-com-goo-b-sh'   => 'twrp-dcom-twrp-c-sh',

			// Dashicons.
			'twrp-com-di-f'       => 'twrp-dcom-di-f',
			'twrp-com-di-c-f'     => 'twrp-dcom-di-f',
			'twrp-com-di-d-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-di-l-f'     => 'twrp-dcom-di-f',

			// Foundation.
			'twrp-com-fi-f'       => 'twrp-dcom-twrp-c-f',
			'twrp-com-fi-2-f'     => 'twrp-dcom-twrp-c-f',

			// Ionicons.
			'twrp-com-ii-f'       => 'twrp-dcom-twrp-c-f',
			'twrp-com-ii-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ii-sh'      => 'twrp-dcom-twrp-c-sh',
			'twrp-com-ii-2-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ii-2-ol'    => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-ii-ios-f'   => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ii-ios-ol'  => 'twrp-dcom-twrp-c2-t',
			'twrp-com-ii-t-f'     => 'twrp-dcom-twrp-c-f',
			'twrp-com-ii-t-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ii-t-sh'    => 'twrp-dcom-twrp-c-sh',
			'twrp-com-ii-t2-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ii-t2-ol'   => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-ii-c-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ii-c-ol'    => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-ii-ioc-f'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-ii-ioc-ol'  => 'twrp-dcom-twrp-c-t',

			// IconMonstr.
			'twrp-com-im-f'       => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-im-2-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-2-ol'    => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-im-t-f'     => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-t-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-im-t2-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-t2-ol'   => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-im-co-f'    => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-co-ol'   => 'twrp-dcom-twrp-c-ol',
			'twrp-com-im-co2-f'   => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-co2-ol'  => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-im-c-f'     => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-c-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-im-c2-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-c2-ol'   => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-im-c3-hf'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-c4-hf'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-c5-hf'   => 'twrp-dcom-twrp-c-sh',
			'twrp-com-im-c6-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-c6-ol'   => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-im-c6-t'    => 'twrp-dcom-twrp-c2-t',
			'twrp-com-im-c7-hf'   => 'twrp-dcom-twrp-c2-f',
			'twrp-com-im-c8-hf'   => 'twrp-dcom-twrp-c2-ol',

			// Captain Icons.
			'twrp-com-ci-f'       => 'twrp-dcom-twrp-c-f',
			'twrp-com-ci-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ci-2-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ci-3-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ci-4-f'     => 'twrp-dcom-twrp-c2-f',

			// Feather Icons.
			'twrp-com-fe-sq-ol'   => 'twrp-dcom-twrp-c-ol',
			'twrp-com-fe-ci-ol'   => 'twrp-dcom-twrp-c2-ol',

			// Jam Icons.
			'twrp-com-ji-f'       => 'twrp-dcom-twrp-c-f',
			'twrp-com-ji-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ji-2-f'     => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ji-2-ol'    => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-ji-c-f'     => 'twrp-dcom-twrp-c-f',
			'twrp-com-ji-c-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ji-c2-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ji-c2-ol'   => 'twrp-dcom-twrp-c2-ol',
			'twrp-com-ji-t-f'     => 'twrp-dcom-twrp-c-f',
			'twrp-com-ji-t-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ji-t2-f'    => 'twrp-dcom-twrp-c2-f',
			'twrp-com-ji-t2-ol'   => 'twrp-dcom-twrp-c2-ol',

			// Linea Icons.
			'twrp-com-li-ol'      => 'twrp-dcom-li-ol',
			'twrp-com-li-c-ol'    => 'twrp-dcom-li-ol',
			'twrp-com-li-l-ol'    => 'twrp-dcom-li-ol',
			'twrp-com-li-d-ol'    => 'twrp-dcom-li-ol',
			'twrp-com-li-he-ol'   => 'twrp-dcom-li-ol',
			'twrp-com-li-ha-ol'   => 'twrp-dcom-li-ol',

			// Octicons.
			'twrp-com-oi-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-oi-t'       => 'twrp-dcom-twrp-c-ol',
			'twrp-com-oi-c-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-oi-c-t'     => 'twrp-dcom-twrp-c-ol',

			// Typicons.
			'twrp-com-ti-ol'      => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ti-d-ol'    => 'twrp-dcom-twrp-c-ol',
			'twrp-com-ti-c-ol'    => 'twrp-dcom-twrp-c-ol',
		);

		return $comment_disable_compatibles;
	}

}

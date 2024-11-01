<?php

namespace TWRP\Icons;

/**
 * Class that holds all comments icon definitions.
 */
class Comments_Icons implements Icon_Definitions {

	/**
	 * Get all registered icons that represents the comments.
	 *
	 * @return array<string,array>
	 */
	public static function get_definitions() {
		$filled_type      = _x( 'Filled', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$outlined_type    = _x( 'Outlined', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$duotone_type     = _x( 'DuoTone', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$thin_type        = _x( 'Thin', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$sharp_type       = _x( 'Sharp', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$half_filled_type = _x( 'Half Filled', 'backend', 'tabs-with-posts' );

		$comment_description        = _x( 'Comment', 'backend, icon name(description)', 'tabs-with-posts' );
		$comment_2_description      = _x( 'Comment 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$comment_3_description      = _x( 'Comment 3', 'backend, icon name(description)', 'tabs-with-posts' );
		$comment_4_description      = _x( 'Comment 4', 'backend, icon name(description)', 'tabs-with-posts' );
		$comment_dollar_description = _x( 'Comment Dollar', 'backend, icon name(description)', 'tabs-with-posts' );

		$comments_description   = _x( 'Comments', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_2_description = _x( 'Comments 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_3_description = _x( 'Comments 3', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_4_description = _x( 'Comments 4', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_5_description = _x( 'Comments 5', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_6_description = _x( 'Comments 6', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_7_description = _x( 'Comments 7', 'backend, icon name(description)', 'tabs-with-posts' );
		$comments_8_description = _x( 'Comments 8', 'backend, icon name(description)', 'tabs-with-posts' );

		$dots_description      = _x( 'Dots', 'backend, icon name(description)', 'tabs-with-posts' );
		$dots_2_description    = _x( 'Dots 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$content_description   = _x( 'Content', 'backend, icon name(description)', 'tabs-with-posts' );
		$content_2_description = _x( 'Content 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$square_description    = _x( 'Square', 'backend, icon name(description)', 'tabs-with-posts' );
		$bank_description      = _x( 'Bank', 'backend, icon name(description)', 'tabs-with-posts' );
		$feedback_description  = _x( 'Feedback', 'backend, icon name(description)', 'tabs-with-posts' );
		$lines_description     = _x( 'Lines', 'backend, icon name(description)', 'tabs-with-posts' );
		$circle_description    = _x( 'Circle', 'backend, icon name(description)', 'tabs-with-posts' );
		$heart_description     = _x( 'Heart', 'backend, icon name(description)', 'tabs-with-posts' );
		$happy_description     = _x( 'Happy', 'backend, icon name(description)', 'tabs-with-posts' );

		$ios_comment_description  = _x( 'Ios Comment', 'backend, icon name(description)', 'tabs-with-posts' );
		$ios_comments_description = _x( 'Ios Comments', 'backend, icon name(description)', 'tabs-with-posts' );

		$registered_comment_vectors = array(

			#region -- TWRP Icons

			// No Icons...

			#endregion -- TWRP Icons

			#region -- FontAwesome Icons

			'twrp-com-fa-f'       => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-ol'      => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-2-f'     => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => 'alt-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-2-ol'    => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'alt-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-dots-f'  => array(
				'brand'       => 'FontAwesome',
				'description' => $dots_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-dots-ol' => array(
				'brand'       => 'FontAwesome',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fa-comm-f'  => array(
				'brand'       => 'FontAwesome',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
			),

			'twrp-com-fa-comm-ol' => array(
				'brand'       => 'FontAwesome',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
			),

			'twrp-com-fa-do-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $comment_dollar_description,
				'type'        => $filled_type,
				'file_name'   => 'dollar-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- FontAwesome Icons

			#region -- Google Icons

			'twrp-com-goo-f'      => array(
				'brand'       => 'Google',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-ol'     => array(
				'brand'       => 'Google',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-dt'     => array(
				'brand'       => 'Google',
				'description' => $comment_description,
				'type'        => $duotone_type,
				'file_name'   => 'duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-sh'     => array(
				'brand'       => 'Google',
				'description' => $comment_description,
				'type'        => $sharp_type,
				'file_name'   => 'sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-2-f'    => array(
				'brand'       => 'Google',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => 'alt-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-2-ol'   => array(
				'brand'       => 'Google',
				'description' => $comment_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'alt-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-2-dt'   => array(
				'brand'       => 'Google',
				'description' => $comment_2_description,
				'type'        => $duotone_type,
				'file_name'   => 'alt-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-2-sh'   => array(
				'brand'       => 'Google',
				'description' => $comment_2_description,
				'type'        => $sharp_type,
				'file_name'   => 'alt-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-f-f'    => array(
				'brand'       => 'Google',
				'description' => $feedback_description,
				'type'        => $filled_type,
				'file_name'   => 'feedback-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-f-ol'   => array(
				'brand'       => 'Google',
				'description' => $feedback_description,
				'type'        => $outlined_type,
				'file_name'   => 'feedback-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-f-dt'   => array(
				'brand'       => 'Google',
				'description' => $feedback_description,
				'type'        => $duotone_type,
				'file_name'   => 'feedback-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-f-sh'   => array(
				'brand'       => 'Google',
				'description' => $feedback_description,
				'type'        => $sharp_type,
				'file_name'   => 'feedback-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-b-f'    => array(
				'brand'       => 'Google',
				'description' => $bank_description,
				'type'        => $filled_type,
				'file_name'   => 'bank-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-b-ol'   => array(
				'brand'       => 'Google',
				'description' => $bank_description,
				'type'        => $outlined_type,
				'file_name'   => 'bank-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-b-dt'   => array(
				'brand'       => 'Google',
				'description' => $bank_description,
				'type'        => $duotone_type,
				'file_name'   => 'bank-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-goo-b-sh'   => array(
				'brand'       => 'Google',
				'description' => $bank_description,
				'type'        => $sharp_type,
				'file_name'   => 'bank-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Google Icons

			#region -- Dashicons

			'twrp-com-di-f'       => array(
				'brand'       => 'Dashicons',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-di-c-f'     => array(
				'brand'       => 'Dashicons',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-di-d-f'     => array(
				'brand'       => 'Dashicons',
				'description' => $dots_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-di-l-f'     => array(
				'brand'       => 'Dashicons',
				'description' => $lines_description,
				'type'        => $filled_type,
				'file_name'   => 'lines-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Dashicons

			#region -- Foundation Icons

			'twrp-com-fi-f'       => array(
				'brand'       => 'Foundation',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fi-2-f'     => array(
				'brand'       => 'Foundation',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
			),

			#endregion -- Foundation Icons

			#region -- Ionicons Icons

			'twrp-com-ii-f'       => array(
				'brand'       => 'Ionicons',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-ol'      => array(
				'brand'       => 'Ionicons',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-sh'      => array(
				'brand'       => 'Ionicons',
				'description' => $comment_description,
				'type'        => $sharp_type,
				'file_name'   => 'sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-2-f'     => array(
				'brand'       => 'Ionicons',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => '2-filled.svg',
			),

			'twrp-com-ii-2-ol'    => array(
				'brand'       => 'Ionicons',
				'description' => $comment_2_description,
				'type'        => $outlined_type,
				'file_name'   => '2-outlined.svg',
			),

			'twrp-com-ii-ios-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $ios_comment_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-ios-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-t-f'     => array(
				'brand'       => 'Ionicons',
				'description' => $dots_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-t-ol'    => array(
				'brand'       => 'Ionicons',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-t-sh'    => array(
				'brand'       => 'Ionicons',
				'description' => $dots_description,
				'type'        => $sharp_type,
				'file_name'   => 'dots-sharp.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-t2-f'    => array(
				'brand'       => 'Ionicons',
				'description' => $dots_2_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-2-filled.svg',
			),

			'twrp-com-ii-t2-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $dots_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-2-outlined.svg',
			),

			'twrp-com-ii-c-f'     => array(
				'brand'       => 'Ionicons',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
			),

			'twrp-com-ii-c-ol'    => array(
				'brand'       => 'Ionicons',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
			),

			'twrp-com-ii-ioc-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $ios_comments_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-comments-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ii-ioc-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-comments-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Ionicons Icons

			#region -- IconMonstr Icons

			'twrp-com-im-f'       => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-ol'      => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-2-f'     => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => '2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-2-ol'    => array(
				'brand'       => 'IconMonstr',
				'description' => $comment_2_description,
				'type'        => $outlined_type,
				'file_name'   => '2-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-t-f'     => array(
				'brand'       => 'IconMonstr',
				'description' => $dots_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-t-ol'    => array(
				'brand'       => 'IconMonstr',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-t2-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $dots_2_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-t2-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $dots_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-2-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-co-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $content_description,
				'type'        => $filled_type,
				'file_name'   => 'content-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-co-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $content_description,
				'type'        => $outlined_type,
				'file_name'   => 'content-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-co2-f'   => array(
				'brand'       => 'IconMonstr',
				'description' => $content_2_description,
				'type'        => $filled_type,
				'file_name'   => 'content-2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-co2-ol'  => array(
				'brand'       => 'IconMonstr',
				'description' => $content_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'content-2-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c-f'     => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c-ol'    => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c2-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_2_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-2-filled.svg',
			),

			'twrp-com-im-c2-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-2-outlined.svg',
			),

			'twrp-com-im-c3-hf'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_3_description,
				'type'        => $half_filled_type,
				'file_name'   => 'comments-3-half-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c4-hf'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_4_description,
				'type'        => $half_filled_type,
				'file_name'   => 'comments-4-half-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c5-hf'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_5_description,
				'type'        => $half_filled_type,
				'file_name'   => 'comments-5-half-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c6-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_6_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-6-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c6-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_6_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-6-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c6-t'    => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_6_description,
				'type'        => $thin_type,
				'file_name'   => 'comments-6-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c7-hf'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_7_description,
				'type'        => $half_filled_type,
				'file_name'   => 'comments-7-half-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-im-c8-hf'   => array(
				'brand'       => 'IconMonstr',
				'description' => $comments_8_description,
				'type'        => $half_filled_type,
				'file_name'   => 'comments-8-half-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- IconMonstr Icons

			#region -- Captain Icons

			'twrp-com-ci-f'       => array(
				'brand'       => 'Captain Icons',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ci-ol'      => array(
				'brand'       => 'Captain Icons',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ci-2-f'     => array(
				'brand'       => 'Captain Icons',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => '2-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ci-3-f'     => array(
				'brand'       => 'Captain Icons',
				'description' => $comment_3_description,
				'type'        => $filled_type,
				'file_name'   => '3-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ci-4-f'     => array(
				'brand'       => 'Captain Icons',
				'description' => $comment_4_description,
				'type'        => $filled_type,
				'file_name'   => '4-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Captain Icons

			#region -- Feather Icons

			'twrp-com-fe-sq-ol'   => array(
				'brand'       => 'Feather',
				'description' => $square_description,
				'type'        => $outlined_type,
				'file_name'   => 'square-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-fe-ci-ol'   => array(
				'brand'       => 'Feather',
				'description' => $circle_description,
				'type'        => $outlined_type,
				'file_name'   => 'circle-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Feather Icons

			#region -- Jam Icons

			'twrp-com-ji-f'       => array(
				'brand'       => 'JamIcons',
				'description' => $comment_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-ol'      => array(
				'brand'       => 'JamIcons',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-2-f'     => array(
				'brand'       => 'JamIcons',
				'description' => $comment_2_description,
				'type'        => $filled_type,
				'file_name'   => 'alt-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-2-ol'    => array(
				'brand'       => 'JamIcons',
				'description' => $comment_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'alt-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-c-f'     => array(
				'brand'       => 'JamIcons',
				'description' => $comments_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-c-ol'    => array(
				'brand'       => 'JamIcons',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-c2-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $comments_2_description,
				'type'        => $filled_type,
				'file_name'   => 'comments-alt-filled.svg',
			),

			'twrp-com-ji-c2-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $comments_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-alt-outlined.svg',
			),

			'twrp-com-ji-t-f'     => array(
				'brand'       => 'JamIcons',
				'description' => $dots_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-t-ol'    => array(
				'brand'       => 'JamIcons',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-t2-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $dots_2_description,
				'type'        => $filled_type,
				'file_name'   => 'dots-alt-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ji-t2-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $dots_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-alt-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Jam Icons

			#region -- Linea Icons

			'twrp-com-li-ol'      => array(
				'brand'       => 'Linea',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'comment-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-li-c-ol'    => array(
				'brand'       => 'Linea',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-li-l-ol'    => array(
				'brand'       => 'Linea',
				'description' => $lines_description,
				'type'        => $outlined_type,
				'file_name'   => 'lines-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-li-d-ol'    => array(
				'brand'       => 'Linea',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-li-he-ol'   => array(
				'brand'       => 'Linea',
				'description' => $heart_description,
				'type'        => $outlined_type,
				'file_name'   => 'heart-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-li-ha-ol'   => array(
				'brand'       => 'Linea',
				'description' => $happy_description,
				'type'        => $outlined_type,
				'file_name'   => 'happy-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Linea Icons

			#region -- Octicons Icons

			'twrp-com-oi-ol'      => array(
				'brand'       => 'Octicons',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'comment-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-oi-t'       => array(
				'brand'       => 'Octicons',
				'description' => $comment_description,
				'type'        => $thin_type,
				'file_name'   => 'comment-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-oi-c-ol'    => array(
				'brand'       => 'Octicons',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
			),

			'twrp-com-oi-c-t'     => array(
				'brand'       => 'Octicons',
				'description' => $comments_description,
				'type'        => $thin_type,
				'file_name'   => 'comments-thin.svg',
			),

			#endregion -- Octicons Icons

			#region -- Typicons Icons

			'twrp-com-ti-ol'      => array(
				'brand'       => 'Typicons',
				'description' => $comment_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ti-d-ol'    => array(
				'brand'       => 'Typicons',
				'description' => $dots_description,
				'type'        => $outlined_type,
				'file_name'   => 'dots-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-com-ti-c-ol'    => array(
				'brand'       => 'Typicons',
				'description' => $comments_description,
				'type'        => $outlined_type,
				'file_name'   => 'comments-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Typicons Icons

		);

		return $registered_comment_vectors;
	}


}

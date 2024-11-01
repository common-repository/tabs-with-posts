<?php

namespace TWRP\Icons;

/**
 * Class that holds all date icon definitions.
 *
 * Keywords to search: calendar, clock.
 */
class Date_Icons implements Icon_Definitions {

	/**
	 * Get all registered icons that represents the date.
	 *
	 * @return array<string,array>
	 */
	public static function get_definitions() {
		$filled_type   = _x( 'Filled', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$outlined_type = _x( 'Outlined', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$duotone_type  = _x( 'DuoTone', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$thin_type     = _x( 'Thin', 'backend, icon type(aspect)', 'tabs-with-posts' );
		$sharp_type    = _x( 'Sharp', 'backend, icon type(aspect)', 'tabs-with-posts' );

		$calendar_description       = _x( 'Calendar', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_2_description     = _x( 'Calendar 2', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_3_description     = _x( 'Calendar 3', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_4_description     = _x( 'Calendar 4', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_5_description     = _x( 'Calendar 5', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_today_description = _x( 'Calendar Today', 'backend, icon name(description)', 'tabs-with-posts' );
		$calendar_range_description = _x( 'Calendar Range', 'backend, icon name(description)', 'tabs-with-posts' );

		$clock_description   = _x( 'Clock', 'backend, icon name(description)', 'tabs-with-posts' );
		$clock_2_description = _x( 'Clock 2', 'backend, icon name(description)', 'tabs-with-posts' );

		/* translators: Ios is an Apple Phone operating system. */
		$ios_calendar_description = _x( 'Ios Calendar', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_clock_description = _x( 'Ios Clock', 'backend, icon name(description)', 'tabs-with-posts' );
		/* translators: Ios is an Apple Phone operating system. */
		$ios_time_description = _x( 'Ios Time', 'backend, icon name(description)', 'tabs-with-posts' );

		$registered_date_vectors = array(

			#region -- TWRP Icons

			// No Icons...

			#endregion -- TWRP Icons

			#region -- FontAwesome Icons

			'twrp-cal-fa-f'      => array(
				'brand'       => 'FontAwesome',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-fa-ol'     => array(
				'brand'       => 'FontAwesome',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-fa-2-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $calendar_2_description,
				'type'        => $filled_type,
				'file_name'   => 'alt-filled.svg',
			),

			'twrp-cal-fa-2-ol'   => array(
				'brand'       => 'FontAwesome',
				'description' => $calendar_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'alt-outlined.svg',
			),

			'twrp-cal-fa-c-f'    => array(
				'brand'       => 'FontAwesome',
				'description' => $clock_description,
				'type'        => $filled_type,
				'file_name'   => 'clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-fa-c-ol'   => array(
				'brand'       => 'FontAwesome',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- FontAwesome Icons

			#region -- Google Icons

			'twrp-cal-goo-f'     => array(
				'brand'       => 'Google',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-goo-ol'    => array(
				'brand'       => 'Google',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-goo-dt'    => array(
				'brand'       => 'Google',
				'description' => $calendar_description,
				'type'        => $duotone_type,
				'file_name'   => 'duotone.svg',
			),

			'twrp-cal-goo-sh'    => array(
				'brand'       => 'Google',
				'description' => $calendar_description,
				'type'        => $sharp_type,
				'file_name'   => 'sharp.svg',
			),

			'twrp-cal-goo-r-f'   => array(
				'brand'       => 'Google',
				'description' => $calendar_range_description,
				'type'        => $filled_type,
				'file_name'   => 'range-filled.svg',
			),

			'twrp-cal-goo-r-ol'  => array(
				'brand'       => 'Google',
				'description' => $calendar_range_description,
				'type'        => $outlined_type,
				'file_name'   => 'range-outlined.svg',
			),

			'twrp-cal-goo-r-dt'  => array(
				'brand'       => 'Google',
				'description' => $calendar_range_description,
				'type'        => $duotone_type,
				'file_name'   => 'range-duotone.svg',
			),

			'twrp-cal-goo-r-sh'  => array(
				'brand'       => 'Google',
				'description' => $calendar_range_description,
				'type'        => $sharp_type,
				'file_name'   => 'range-sharp.svg',
			),

			'twrp-cal-goo-d-f'   => array(
				'brand'       => 'Google',
				'description' => $calendar_today_description,
				'type'        => $filled_type,
				'file_name'   => 'today-filled.svg',
			),

			'twrp-cal-goo-d-ol'  => array(
				'brand'       => 'Google',
				'description' => $calendar_today_description,
				'type'        => $outlined_type,
				'file_name'   => 'today-outlined.svg',
			),

			'twrp-cal-goo-d-dt'  => array(
				'brand'       => 'Google',
				'description' => $calendar_today_description,
				'type'        => $duotone_type,
				'file_name'   => 'today-duotone.svg',
			),

			'twrp-cal-goo-d-sh'  => array(
				'brand'       => 'Google',
				'description' => $calendar_today_description,
				'type'        => $sharp_type,
				'file_name'   => 'today-sharp.svg',
			),

			'twrp-cal-goo-c-f'   => array(
				'brand'       => 'Google',
				'description' => $clock_description,
				'type'        => $filled_type,
				'file_name'   => 'clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-goo-c-ol'  => array(
				'brand'       => 'Google',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-goo-c-dt'  => array(
				'brand'       => 'Google',
				'description' => $clock_description,
				'type'        => $duotone_type,
				'file_name'   => 'clock-duotone.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Google Icons

			#region -- Dashicons

			'twrp-cal-di-f'      => array(
				'brand'       => 'Dashicons',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'calendar-filled.svg',
			),

			'twrp-cal-di-2-f'    => array(
				'brand'       => 'Dashicons',
				'description' => $calendar_2_description,
				'type'        => $filled_type,
				'file_name'   => 'calendar-2-filled.svg',
			),

			'twrp-cal-di-c-ol'   => array(
				'brand'       => 'Dashicons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Dashicons

			#region -- Foundation Icons

			'twrp-cal-fi-f'      => array(
				'brand'       => 'Foundation',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-fi-c-ol'   => array(
				'brand'       => 'Foundation',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Foundation Icons

			#region -- Ionicons Icons

			'twrp-cal-ii-f'      => array(
				'brand'       => 'Ionicons',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-ii-ol'     => array(
				'brand'       => 'Ionicons',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-ii-sh'     => array(
				'brand'       => 'Ionicons',
				'description' => $calendar_description,
				'type'        => $sharp_type,
				'file_name'   => 'sharp.svg',
			),

			'twrp-cal-ii-ios-f'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-filled.svg',
			),

			'twrp-cal-ii-ios-ol' => array(
				'brand'       => 'Ionicons',
				'description' => $ios_calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-outlined.svg',
			),

			'twrp-cal-ii-c-f'    => array(
				'brand'       => 'Ionicons',
				'description' => $clock_description,
				'type'        => $filled_type,
				'file_name'   => 'clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ii-c-ol'   => array(
				'brand'       => 'Ionicons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ii-ic-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $ios_clock_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ii-ic-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ii-it-f'   => array(
				'brand'       => 'Ionicons',
				'description' => $ios_time_description,
				'type'        => $filled_type,
				'file_name'   => 'ios-time-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ii-it-ol'  => array(
				'brand'       => 'Ionicons',
				'description' => $ios_time_description,
				'type'        => $outlined_type,
				'file_name'   => 'ios-time-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Ionicons Icons

			#region -- IconMonstr Icons

			'twrp-cal-im-f'      => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-im-t'      => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_description,
				'type'        => $thin_type,
				'file_name'   => 'thin.svg',
			),

			'twrp-cal-im-2-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_2_description,
				'type'        => $filled_type,
				'file_name'   => '2-filled.svg',
			),

			'twrp-cal-im-3-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_3_description,
				'type'        => $filled_type,
				'file_name'   => '3-filled.svg',
			),

			'twrp-cal-im-4-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_4_description,
				'type'        => $filled_type,
				'file_name'   => '4-filled.svg',
			),

			'twrp-cal-im-5-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $calendar_5_description,
				'type'        => $filled_type,
				'file_name'   => '5-filled.svg',
			),

			'twrp-cal-im-c-f'    => array(
				'brand'       => 'IconMonstr',
				'description' => $clock_description,
				'type'        => $filled_type,
				'file_name'   => 'clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-im-c-ol'   => array(
				'brand'       => 'IconMonstr',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-im-c-t'    => array(
				'brand'       => 'IconMonstr',
				'description' => $clock_description,
				'type'        => $thin_type,
				'file_name'   => 'clock-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- IconMonstr Icons

			#region -- Captain Icons

			'twrp-cal-ci-f'      => array(
				'brand'       => 'Captain Icons',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-ci-2-f'    => array(
				'brand'       => 'Captain Icons',
				'description' => $calendar_2_description,
				'type'        => $filled_type,
				'file_name'   => '2-filled.svg',
			),

			'twrp-cal-ci-c-ol'   => array(
				'brand'       => 'Captain Icons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Captain Icons

			#region -- Feather Icons

			'twrp-cal-fe-ol'     => array(
				'brand'       => 'Feather',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-fe-c-ol'   => array(
				'brand'       => 'Feather',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Feather Icons

			#region -- Jam Icons

			'twrp-cal-ji-f'      => array(
				'brand'       => 'JamIcons',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-ji-ol'     => array(
				'brand'       => 'JamIcons',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-ji-2-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $calendar_2_description,
				'type'        => $filled_type,
				'file_name'   => 'alt-filled.svg',
			),

			'twrp-cal-ji-2-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $calendar_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'alt-outlined.svg',
			),

			'twrp-cal-ji-c-f'    => array(
				'brand'       => 'JamIcons',
				'description' => $clock_description,
				'type'        => $filled_type,
				'file_name'   => 'clock-filled.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-ji-c-ol'   => array(
				'brand'       => 'JamIcons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Jam Icons

			#region -- Linea Icons

			'twrp-cal-li-ol'     => array(
				'brand'       => 'Linea',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'calendar-outlined.svg',
			),

			'twrp-cal-li-2-ol'   => array(
				'brand'       => 'Linea',
				'description' => $calendar_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'calendar-2-outlined.svg',
			),

			'twrp-cal-li-c-ol'   => array(
				'brand'       => 'Linea',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-li-c2-ol'  => array(
				'brand'       => 'Linea',
				'description' => $clock_2_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-2-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Linea Icons

			#region -- Octicons Icons

			'twrp-cal-oi-ol'     => array(
				'brand'       => 'Octicons',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'calendar-outlined.svg',
			),

			'twrp-cal-oi-t'      => array(
				'brand'       => 'Octicons',
				'description' => $calendar_description,
				'type'        => $thin_type,
				'file_name'   => 'calendar-thin.svg',
			),

			'twrp-cal-oi-c-ol'   => array(
				'brand'       => 'Octicons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			'twrp-cal-oi-c-t'    => array(
				'brand'       => 'Octicons',
				'description' => $clock_description,
				'type'        => $thin_type,
				'file_name'   => 'clock-thin.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Octicons Icons

			#region -- Typicons Icons

			'twrp-cal-ti-f'      => array(
				'brand'       => 'Typicons',
				'description' => $calendar_description,
				'type'        => $filled_type,
				'file_name'   => 'filled.svg',
			),

			'twrp-cal-ti-ol'     => array(
				'brand'       => 'Typicons',
				'description' => $calendar_description,
				'type'        => $outlined_type,
				'file_name'   => 'outlined.svg',
			),

			'twrp-cal-ti-c-ol'   => array(
				'brand'       => 'Typicons',
				'description' => $clock_description,
				'type'        => $outlined_type,
				'file_name'   => 'clock-outlined.svg',
				'fix_classes' => 'twrp-i--va-15',
			),

			#endregion -- Typicons Icons

		);

		return $registered_date_vectors;
	}

}

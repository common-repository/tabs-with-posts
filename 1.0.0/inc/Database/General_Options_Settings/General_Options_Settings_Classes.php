<?php
/**
 * This file contains all the option classes. Since the classes are very small
 * in size, all of them are put in a file, even that the standards says is good
 * to put them in a separate file.
 *
 * phpcs:disable Generic.Files.OneObjectStructurePerFile
 */

namespace TWRP\Database\Settings;

use TWRP\Icons\Icon_Factory;
use TWRP\Utils\Color_Utils;

/**
 * Class that manages the setting of the accent color.
 */
class Accent_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(0, 123, 255, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the Author Icon.
 */
class Author_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-user-fa-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_user_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of the background color.
 */
class Background_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(255, 255, 255, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the border color.
 */
class Border_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(50, 50, 50, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the Border Radius.
 */
class Border_Radius extends General_Option_Setting {

	public function get_default_value() {
		return '4';
	}

	public function sanitize( $value ) {
		if ( is_numeric( $value ) ) {
			return (string) $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages at what interval of time the global widget cache should
 * refresh, no matter what.
 */
class Cache_Automatic_Refresh extends General_Option_Setting {

	public function get_default_value() {
		return '10';
	}

	public function get_possible_options() {
		return array(
			'7',
			'10',
			'15',
			'20',
			'30',
			'45',
			'60',
		);
	}

}

/**
 * Class that manages the setting of the Category Icon.
 */
class Category_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-tax-fa-f-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_category_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of auto selecting the comments disabled icon.
 */
class Comments_Disabled_Icon_Auto_Select extends General_Option_Setting {

	public function get_default_value() {
		return 'true';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting of the comments disabled icon.
 */
class Comments_Disabled_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-dcom-twrp-c2-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_comment_disabled_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of the comments icon.
 */
class Comments_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-com-fa-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_comment_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of the darker accent color.
 */
class Darker_Accent_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(0, 94, 196, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the date format.
 */
class Date_Format extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the date icon.
 */
class Date_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-cal-fa-2-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_date_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of the disabled text color.
 */
class Disabled_Text_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(70, 70, 70, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting that will enable or disable the widget cache.
 */
class Enable_Cache extends General_Option_Setting {

	public function get_default_value() {
		return 'true';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting of filling additional grid spaces with posts.
 */
class Fill_Grid_With_Posts extends General_Option_Setting {

	public function get_default_value() {
		return 'true';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting that will show the date as a human readable date.
 */
class Human_Readable_Date extends General_Option_Setting {

	public function get_default_value() {
		return 'true';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting of the lighter accent color.
 */
class Lighter_Accent_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(70, 160, 255, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages whether or not the widget is loaded via ajax.
 */
class Load_Widget_Via_Ajax extends General_Option_Setting {

	public function get_default_value() {
		return 'false';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting of the image when there is no thumbnail.
 */
class No_Thumbnail_Image extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_numeric( $value ) && is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the rating pack icon.
 */
class Rating_Pack_Icons extends General_Option_Setting {

	public function get_default_value() {
		return 'fa-stars';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_rating_packs();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages the setting of the secondary background color.
 */
class Secondary_Background_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(247, 247, 247, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the secondary border color.
 */
class Secondary_Border_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(200, 200, 200, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the inclusion of all svgs inline.
 */
class Svg_Include_Inline extends General_Option_Setting {

	public function get_default_value() {
		return 'true';
	}

	public function get_possible_options() {
		return array( 'true', 'false' );
	}

}

/**
 * Class that manages the setting of the tab button size.
 */
class Tab_Button_Size extends General_Option_Setting {

	public function get_default_value() {
		return '1';
	}

	public function sanitize( $value ) {
		if ( is_numeric( $value ) ) {
			return (string) $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the text color.
 */
class Text_Color extends General_Option_Setting {

	public function get_default_value() {
		return 'rgba(10, 10, 10, 1)';
	}

	public function sanitize( $value ) {
		if ( '' === $value || Color_Utils::is_color( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the setting of the views icon.
 */
class Views_Icon extends General_Option_Setting {

	public function get_default_value() {
		return 'twrp-views-fa-f';
	}

	public function get_possible_options() {
		$icons = Icon_Factory::get_views_icons();
		$icons = array_keys( $icons );

		return $icons;
	}

}

/**
 * Class that manages what yasr rating to display.
 */
class YASR_Rating_Type extends General_Option_Setting {

	public function get_default_value() {
		return 'visitors';
	}

	public function get_possible_options() {
		return array(
			'overall',
			'visitors',
			'multi_set_overall',
			'multi_set_visitors',
		);
	}

}

#region -- Translation settings.

/**
 * Class that manages the setting of show more posts text.
 */
class Show_More_Posts_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting to display if post has no title.
 */
class Post_No_Title_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the post with no thumbnail alt text.
 */
class Post_With_No_Thumbnail_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the relative date text.
 */
class Date_Relative_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the text to display when the widget fails
 * to load.
 */
class Fail_To_Load_Widget_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the setting of the text to display when no posts are
 * displayed.
 */
class No_Posts_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the aria author translation.
 */
class Aria_Author_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria date translation.
 */
class Aria_Date_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria views translation.
 */
class Aria_Views_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria rating translation.
 */
class Aria_Rating_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria category translation.
 */
class Aria_Category_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria comments translation.
 */
class Aria_Comments_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the aria comments are disabled translation.
 */
class Aria_Comments_Are_Disabled_Text extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}


/**
 * Class that manages the abbreviation for billions.
 */
class Abbreviation_For_Billions extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}

}

/**
 * Class that manages the abbreviation for millions.
 */
class Abbreviation_For_Millions extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

/**
 * Class that manages the abbreviation for thousands.
 */
class Abbreviation_For_Thousands extends General_Option_Setting {

	public function get_default_value() {
		return '';
	}

	public function sanitize( $value ) {
		if ( is_string( $value ) ) {
			return $value;
		}

		return $this->get_default_value();
	}
}

#endregion -- Translation settings.

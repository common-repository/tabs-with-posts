<?php
/**
 * Require all files.
 */

namespace TWRP;

use TWRP\Database\Query_Options;
use TWRP\Enqueue_Scripts\Icons_CSS;
use TWRP\Database\Tabs_Cache_Table;
use TWRP\Tabs_Cache\Create_Tabs_Cache;
use TWRP\Utils\Class_Retriever_Utils;
use TWRP\Utils\Directory_Utils;

/**
 * Class used to require all files needed by this plugin.
 *
 * An autoloader is not used, because other plugins can use an autoloader that
 * is slow, making this plugin slow as well, also is not that hard to add a file
 * here every time one is created.
 */
class Plugin_Bootstrap {

	/**
	 * All files that needs to be required for the plugin.
	 *
	 * @var array<string>
	 */
	protected static $required_files = array(
		// Utils.
		// These traits should be included first.
		'Utils/Helper_Trait/BEM_Class_Naming_Trait',
		'Utils/Helper_Trait/After_Setup_Theme_Init_Trait',
		'Utils/Helper_Interfaces/Class_Children_Order',
		'Utils/Background_Processing/WP_Async_Request',
		'Utils/Background_Processing/WP_Background_Process',

		'Utils/Frontend_Translation',
		'Utils/Simple_Utils',
		'Utils/Class_Retriever_Utils',
		'Utils/Color_Utils',
		'Utils/Date_Utils',
		'Utils/Directory_Utils',
		'Utils/Filesystem_Utils',
		'Utils/Widget_Utils',

		// Admin.
		'Admin/Helpers/Remember_Note',

		'Admin/Tabs/Documentation/Tab_Queries_Docs',
		'Admin/Tabs/Documentation/License_Display',
		'Admin/Tabs/Documentation/Cache_Docs',
		'Admin/Tabs/Documentation/CSS_Docs',
		'Admin/Tabs/Documentation/Icons_Documentation',
		'Admin/Tabs/Documentation/Translations_Docs',
		'Admin/Tabs/Documentation/Additional_Plugins_Supported',
		'Admin/Tabs/Documentation/Plugin_Support_Docs',

		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Setting_Creator',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Select_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Radio_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Text_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Image_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Color_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Number_Setting',
		'Admin/Tabs/General_Settings/General_Setting_Creator/General_Select_With_Switch_Setting',
		'Admin/Tabs/General_Settings/General_Settings_Factory',

		'Admin/Tabs/Query_Options/Modify_Query_Settings',
		'Admin/Tabs/Query_Options/Existing_Table',
		'Admin/Tabs/Query_Options/Query_Templates',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Query_Setting_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Advanced_Arguments_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Author_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Categories_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Meta_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Password_Protected_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Comments_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Date_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Order_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Settings_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Status_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Post_Types_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Query_Name_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Search_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Sticky_Posts_Display',
		'Admin/Tabs/Query_Options/Query_Settings_Display/Suppress_Filters_Display',

		'Admin/Settings_Menu',
		'Admin/Tabs/Admin_Menu_Tab',
		'Admin/Tabs/Documentation_Tab',
		'Admin/Tabs/General_Settings_Tab',
		'Admin/Tabs/Queries_Tab',

		'Admin/TWRP_Widget/Widget_Form',
		'Admin/TWRP_Widget/Widget_Sanitization',
		'Admin/TWRP_Widget/Widget_Ajax',
		'Admin/TWRP_Widget/Widget_Form_Components_Display',

		'Admin/Widget_Control/Widget_Control',
		'Admin/Widget_Control/Checkbox_Control',
		'Admin/Widget_Control/Number_Control',
		'Admin/Widget_Control/Select_Control',
		'Admin/Widget_Control/Color_Control',


		// Article Blocks.
		'Article_Block/Article_Block_Info',
		'Article_Block/Article_Block',
		'Article_Block/Article_Block_Locked',

		'Article_Block/Component/Artblock_Component',
		'Article_Block/Component/Component_Settings/Component_Setting',

		'Article_Block/Component/Component_Settings/Meta_Font_Size_Setting',
		'Article_Block/Component/Component_Settings/Title_Font_Size_Setting',
		'Article_Block/Component/Component_Settings/Font_Size_Setting',
		'Article_Block/Component/Component_Settings/Border_Radius_Setting',
		'Article_Block/Component/Component_Settings/Line_Height_Setting',
		'Article_Block/Component/Component_Settings/Font_Weight_Setting',
		'Article_Block/Component/Component_Settings/Color_Setting',
		'Article_Block/Component/Component_Settings/Hover_Color_Setting',
		'Article_Block/Component/Component_Settings/Background_Color_Setting',
		'Article_Block/Component/Component_Settings/Hover_Background_Color_Setting',
		'Article_Block/Component/Component_Settings/Flex_Order_Setting',
		'Article_Block/Component/Component_Settings/Text_Decoration_Setting',
		'Article_Block/Component/Component_Settings/Maximum_Excerpt_Lines_Setting',
		'Article_Block/Component/Component_Settings/Hover_Text_Decoration_Setting',
		'Article_Block/Component/Component_Settings/Youtube_Style_Thumbnail_Width',

		'Article_Block/Settings/Artblock_Setting',
		'Article_Block/Settings/Display_Meta',
		'Article_Block/Settings/Display_Post_Thumbnail_Setting',
		'Article_Block/Settings/Display_Post_Excerpt_Setting',

		'Article_Block/Blocks/Simple_Article',
		'Article_Block/Blocks/Youtube_Article',
		'Article_Block/Blocks/Modern_Article',

		// CSS.
		'Enqueue_Scripts/Enqueue_Scripts',
		'Enqueue_Scripts/Icons_CSS',

		// Database.
		'Database/Interfaces/Clean_Database',
		'Database/General_Options_Settings/General_Option_Setting',
		'Database/General_Options_Settings/General_Options_Settings_Classes',
		'Database/Manage_Clean_Database',
		'Database/Query_Options',
		'Database/General_Options',
		'Database/Aside_Options',
		'Database/Tabs_Cache_Table',

		// Icons.
		'Icons/Icon',
		'Icons/Rating_Icon_Pack',
		'Icons/Icon_Factory',
		'Icons/Icon_Categories',

		'Icons/Definitions/Icon_Definitions',
		'Icons/Definitions/User_Icons',
		'Icons/Definitions/Date_Icons',
		'Icons/Definitions/Category_Icons',
		'Icons/Definitions/Comments_Icons',
		'Icons/Definitions/Comments_Disabled_Icons',
		'Icons/Definitions/Views_Icons',
		'Icons/Definitions/Rating_Icons',

		// Plugins.
		'Plugins/Post_Views',
		'Plugins/Post_Rating',

		'Plugins/Known_Plugins/Known_Plugin',

		'Plugins/Known_Plugins/Post_Views_Plugins/Post_Views_Plugin',
		'Plugins/Known_Plugins/Post_Views_Plugins/A3REV_Views_Plugin',
		'Plugins/Known_Plugins/Post_Views_Plugins/DFactory_Views_Plugin',
		'Plugins/Known_Plugins/Post_Views_Plugins/GamerZ_Views_Plugin',

		'Plugins/Known_Plugins/Post_Rating_Plugins/Post_Rating_Plugin',
		'Plugins/Known_Plugins/Post_Rating_Plugins/Post_Rating_Plugin_Locked',
		'Plugins/Known_Plugins/Post_Rating_Plugins/Blaz_Rating_Plugin',
		// 'Plugins/Known_Plugins/Post_Rating_Plugins/Site_Reviews_Rating_Plugin',
		'Plugins/Known_Plugins/Post_Rating_Plugins/Gamerz_Rating_Plugin',
		'Plugins/Known_Plugins/Post_Rating_Plugins/KK_Rating_Plugin',
		// 'Plugins/Known_Plugins/Post_Rating_Plugins/YASR_Rating_Plugin',

		// Query_Setting.
		'Query_Generator/Query_Generator',
		'Query_Generator/Query_Setting/Query_Setting',
		'Query_Generator/Query_Setting/Advanced_Arguments',
		'Query_Generator/Query_Setting/Author',
		'Query_Generator/Query_Setting/Categories',
		'Query_Generator/Query_Setting/Password_Protected',
		'Query_Generator/Query_Setting/Post_Comments',
		'Query_Generator/Query_Setting/Post_Date',
		'Query_Generator/Query_Setting/Post_Order',
		'Query_Generator/Query_Setting/Post_Settings',
		'Query_Generator/Query_Setting/Post_Status',
		'Query_Generator/Query_Setting/Post_Types',
		'Query_Generator/Query_Setting/Query_Name',
		'Query_Generator/Query_Setting/Search',
		'Query_Generator/Query_Setting/Meta_Setting',
		'Query_Generator/Query_Setting/Sticky_Posts',
		'Query_Generator/Query_Setting/Suppress_Filters',

		// Tabs_Creator.
		'Tabs_Creator/Tabs_Creator',
		'Tabs_Creator/Tabs_Creator_Ajax',

		'Tabs_Creator/Tabs_Styles/Tab_Style',
		'Tabs_Creator/Tabs_Styles/Styles/Simple_Tabs',
		'Tabs_Creator/Tabs_Styles/Styles/Button_Tabs',

		// Tabs_Cache.
		'Tabs_Cache/Create_Tabs_Cache',
		'Tabs_Cache/Tabs_Cache_Async_Request',

		// TWRP_Widget.
		'TWRP_Widget',
	);

	/**
	 * Require all the files needed for the plugin.
	 *
	 * @return void
	 *
	 * @psalm-suppress all
	 */
	public static function include_all_files() {
		foreach ( self::$required_files as $file ) {
			$filename = __DIR__ . '/' . $file . '.php';
			require $filename;
		}
	}

	/**
	 * Function that is executed after all this plugin files have been included.
	 * These are things that cannot wait until 'after_setup_theme' action, that
	 * this plugin uses to initialize classes.
	 *
	 * If you want to add basic actions, see After_Setup_Theme_Init_Trait.
	 *
	 * @return void
	 */
	public static function after_file_including_execute() {
		$main_file_path = Directory_Utils::get_path_of_main_plugin_file();

		// Regenerate the database and file icons, in case the plugin was previously installed.
		register_activation_hook( $main_file_path, array( Icons_CSS::class, 'write_needed_icons_to_file_on_plugin_activation' ) );

		// Create the tab cache table on plugin activation.
		register_activation_hook( $main_file_path, array( Tabs_Cache_Table::class, 'create_table_on_plugin_activation' ) );
		register_activation_hook( $main_file_path, array( Create_Tabs_Cache::class, 'plugin_activated_refresh_cache' ) );

		// Populate the database with some default queries.
		register_activation_hook( $main_file_path, array( Query_Options::class, 'populate_database_with_default_queries' ) );
	}

	/**
	 * Searches for all classes that have the After_Setup_Theme_Init_Trait
	 * trait, and call the trait functions.
	 *
	 * This method should be called immediately after include_all_files()
	 * method.
	 *
	 * @return void
	 */
	public static function initialize_after_setup_theme_hooks() {
		$trait_classes = Class_Retriever_Utils::get_all_classes_that_uses_after_init_theme_trait();

		foreach ( $trait_classes as $trait_class ) {
			$trait_class::after_setup_theme_init();
		}
	}

}

<?php

/**
 * The plugin bootstrap file.
 *
 * @wordpress-plugin
 * Plugin Name:       Tabs with Recommended Posts (Widget)
 * Description:       Widget to show posts(latest/most viewed/best rated/custom), with a very solid and nice design. Very user-friendly to customize and get started.
 * Version:           1.0.0
 * Requires at least: 4.9
 * Requires PHP:      5.6
 * Author:            Bradatan Dorin
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tabs-with-posts
 * Domain Path:       /translations
 */
use  TWRP\Database\Manage_Clean_Database ;
use  TWRP\Plugin_Bootstrap ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'twrp_fs' ) ) {
    twrp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.

    if ( !function_exists( 'twrp_fs' ) ) {
        #region -- Freemius integration snippet.
        // Create a helper function for easy SDK access.
        function twrp_fs()
        {
            global  $twrp_fs ;

            if ( !isset( $twrp_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $twrp_fs = fs_dynamic_init( array(
                    'id'             => '8395',
                    'slug'           => 'tabs-with-posts',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_3757934b04c3b1ee5bd8ed116f57c',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => false,
                ),
                    'menu'           => array(
                    'slug'           => 'tabs_with_recommended_posts',
                    'override_exact' => true,
                    'support'        => false,
                    'parent'         => array(
                    'slug' => 'options-general.php',
                ),
                ),
                    'is_live'        => true,
                ) );
            }

            return $twrp_fs;
        }

        // Init Freemius.
        twrp_fs();
        // Signal that SDK was initiated.
        do_action( 'twrp_fs_loaded' );
        // Not like register_uninstall_hook(), you do NOT have to use a static function.
        twrp_fs()->add_action( 'after_uninstall', array( Manage_Clean_Database::class, 'delete_all_plugin_database_entries' ) );
        function twrp_fs_settings_url()
        {
            return admin_url( 'options-general.php?page=tabs_with_recommended_posts&tab=general_settings' );
        }

        twrp_fs()->add_filter( 'connect_url', 'twrp_fs_settings_url' );
        twrp_fs()->add_filter( 'after_skip_url', 'twrp_fs_settings_url' );
        twrp_fs()->add_filter( 'after_connect_url', 'twrp_fs_settings_url' );
        twrp_fs()->add_filter( 'after_pending_connect_url', 'twrp_fs_settings_url' );
        #endregion -- Freemius integration snippet.
    }

    #region -- Plugin Bootstrap
    /**
     * Include all the files of this plugin.
     */
    require_once __DIR__ . '/inc/Plugin_Bootstrap.php';
    Plugin_Bootstrap::include_all_files();
    /**
     * Script to execute right now. Cannot wait until 'after_setup_theme' action.
     */
    Plugin_Bootstrap::after_file_including_execute();
    /**
     * Initialize all the WordPress Hooks and Actions that needs to be called.
     *
     * The function called search for all classes that implements a specific trait,
     * that suggest the class wants to use some event-driven WP hooks/actions.
     *
     * All hooks and actions used should be after the 'after_setup_theme' action.
     * If a hook or action that is earlier needs to be called, then it should be
     * added in the classic way. 'after_setup_theme' action is used because a class
     * that is not yet included might not get called.
     */
    add_action( 'after_setup_theme', array( Plugin_Bootstrap::class, 'initialize_after_setup_theme_hooks' ) );
    /**
     * For Development and Tests.
     */
    if ( file_exists( __DIR__ . '/tests/debug-and-development.php' ) ) {
        require_once __DIR__ . '/tests/debug-and-development.php';
    }
    if ( file_exists( __DIR__ . '/tests/random_things_testing.php' ) ) {
        require_once __DIR__ . '/tests/random_things_testing.php';
    }
    #endregion -- Plugin Bootstrap
}

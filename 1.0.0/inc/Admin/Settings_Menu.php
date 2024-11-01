<?php

namespace TWRP\Admin;

use TWRP\Admin\Tabs\Admin_Menu_Tab;
use TWRP\Admin\Tabs\Queries_Tab;
use TWRP\Utils\Helper_Trait\After_Setup_Theme_Init_Trait;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class for creating the Plugin Settings.
 *
 * These settings are added as a submenu page, to the "Settings" Admin Menu.
 *
 * This Page will have multiple tabs, to add a tab use the static function
 * TWRP\Admin\Settings_Menu::add_tab( 'tab_name' ), usually at "admin_menu"
 * action. The class that implements a tab must have the
 * \TWRP\Admin\Tabs\Admin_Menu_Tab interface.
 */
class Settings_Menu {

	use BEM_Class_Naming_Trait;
	use After_Setup_Theme_Init_Trait;

	/**
	 * Holds the ID of the WordPress submenu. The submenu is added to
	 * "Settings" menu.
	 */
	const MENU_SLUG = 'tabs_with_recommended_posts';

	/**
	 * Key of the URL parameter that holds each tab value.
	 */
	const TAB__URL_PARAMETER_KEY = 'tab';

	/**
	 * Holds all tabs.
	 *
	 * The tabs can be added via 'admin_menu' hook. The default plugin tabs are initialized at 5,6,7 priority.
	 *
	 * @var array<Admin_Menu_Tab>
	 */
	protected static $tabs = array();

	/**
	 * Add a new tab to be displayed in the settings.
	 *
	 * @param string $tab_class The name of a class that implements Admin_Menu_Tab interface.
	 * @return bool Whether or not the tab has been successfully added.
	 *
	 * @psalm_param class-string<Admin_Menu_Tab>
	 */
	public static function add_tab( $tab_class ) {
		if ( class_exists( $tab_class ) ) {
			if ( is_subclass_of( $tab_class, Admin_Menu_Tab::class ) ) {
				array_push( self::$tabs, new $tab_class() );
				return true;
			}
		}

		return false;
	}

	/**
	 * See the trait After_Setup_Theme_Init_Trait for more info.
	 *
	 * @return void
	 */
	public static function after_setup_theme_init() {
		add_action( 'admin_menu', array( self::class, 'initialize_admin_menu' ) );
	}

	/**
	 * Initialize the admin menu.
	 *
	 * Called at 'admin_menu' action, in after_setup_theme_init() function.
	 *
	 * @return void
	 */
	public static function initialize_admin_menu() {
		$page_title = _x( 'Tabs with Recommended Posts - Settings', 'backend', 'tabs-with-posts' );
		$menu_title = _x( 'Tabs with Recommended Posts', 'backend', 'tabs-with-posts' );
		$capability = 'manage_options';
		$slug       = 'tabs_with_recommended_posts';

		add_options_page( $page_title, $menu_title, $capability, $slug, array( self::class, 'display_admin_page_hook' ) );

		self::add_tab( 'TWRP\Admin\Tabs\Documentation_Tab' );
		self::add_tab( 'TWRP\Admin\Tabs\General_Settings_Tab' );
		self::add_tab( 'TWRP\Admin\Tabs\Queries_Tab' );
	}

	/**
	 * Main static function, displays the whole page.
	 *
	 * Static function to be used via add_options_page() or equivalent.
	 *
	 * @return void
	 */
	public static function display_admin_page_hook() {
		$class = new Settings_Menu();
		$class->display_admin_page();
	}

	/**
	 * Main function, displays the whole page.
	 *
	 * @return void
	 */
	public function display_admin_page() {
		?>
		<div class="<?php $this->bem_class(); ?> wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php $this->display_tab_buttons(); ?>
			<?php $this->display_active_tab(); ?>
		</div>
		<?php
	}

	/**
	 * Display the main tab buttons selection.
	 *
	 * @return void
	 */
	protected function display_tab_buttons() {
		?>
			<div class="<?php $this->bem_class( 'tabs' ); ?> nav-tab-wrapper">
				<?php foreach ( self::$tabs as $tab ) : ?>
					<a href="<?php echo esc_url( self::get_tab_url( $tab ) ); ?>" class="<?php echo esc_attr( $this->get_tab_class_attribute( $tab ) ); ?>">
						<?php echo $this->get_tab_title( $tab ); // phpcs:ignore -- "Feature" to add icons. ?>
					</a>
				<?php endforeach; ?>
				<a href="<?php echo esc_url( menu_page_url( self::MENU_SLUG, false ) . '-contact' ); ?>" class="<?php echo esc_attr( $this->get_tab_class_attribute( 'contact' ) ); ?>">
					<?php echo esc_html_x( 'Contact Us', 'backend', 'tabs-with-posts' ); ?>
				</a>
			</div>
		<?php
	}

	/**
	 * Display the content of the active tab.
	 *
	 * @return void
	 */
	protected function display_active_tab() {
		$active_tab = $this->get_active_tab();
		?>
		<div class="<?php $this->bem_class( 'tab-content' ); ?>">
			<?php $active_tab->display_tab(); ?>
		</div>
		<?php
	}


	/**
	 * Return the URL to the specific tab. The url is not sanitized.
	 *
	 * @param Admin_Menu_Tab $tab_class The tab to create the URL.
	 *
	 * @return string The URL, not sanitized.
	 */
	public static function get_tab_url( $tab_class ) {
		$submenu_url = menu_page_url( self::MENU_SLUG, false );

		// Do not att the query argument in url on the first tab, to make the
		// URL look nice.
		if ( get_class( $tab_class ) === get_class( self::$tabs[0] ) ) {
			return $submenu_url;
		}

		$url_arg = $tab_class->get_tab_url_arg();
		return add_query_arg( self::TAB__URL_PARAMETER_KEY, $url_arg, $submenu_url );
	}

	/**
	 * Get the HTML class attribute of a specific tab button.
	 *
	 * @param Admin_Menu_Tab|'contact' $tab_class The tab to create the attribute.
	 * @return string
	 */
	protected function get_tab_class_attribute( $tab_class ) {
		$default_class = $this->get_bem_class( 'tab-btn' ) . ' nav-tab';

		if ( ! is_string( $tab_class ) && self::is_tab_active( $tab_class ) ) {
			$default_class .= ' ' . $this->get_bem_class( 'tab-btn', 'active' ) . ' nav-tab-active';
		}

		return $default_class;
	}

	/**
	 * Return the tab title.
	 *
	 * @param Admin_Menu_Tab $tab_class The tab to retrieve the title.
	 *
	 * @return string
	 */
	protected function get_tab_title( $tab_class ) {
		return $tab_class->get_tab_title();
	}


	/**
	 * Whether or not the tab is currently displayed.
	 *
	 * @param Admin_Menu_Tab $tab_class The tab to verify.
	 *
	 * @return bool
	 */
	public static function is_tab_active( $tab_class ) {
		if ( self::is_active_screen() && self::get_active_tab_arg() === $tab_class->get_tab_url_arg() ) {
			return true;
		}

		return false;
	}

	/**
	 * Whether or not the query settings tab is active.
	 *
	 * @return bool
	 */
	public static function is_query_settings_tab_active() {
		$tab_class = new Queries_Tab();
		if ( self::is_active_screen() && self::get_active_tab_arg() === $tab_class->get_tab_url_arg() ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the url argument value, of the active tab.
	 *
	 * @return string
	 */
	public static function get_active_tab_arg() {
		$active_tab = self::get_active_tab();
		return $active_tab->get_tab_url_arg();
	}

	/**
	 * Get the active tab class.
	 *
	 * Usually you might need to call is_active_screen() first, to check if the
	 * admin submenu is selected.
	 *
	 * @return Admin_Menu_Tab The active tab class.
	 */
	public static function get_active_tab() {
		if ( ! self::is_active_screen() ) {
			return self::$tabs[0];
		}

		$active_tab = '';
		if ( isset( $_GET[ self::TAB__URL_PARAMETER_KEY ] ) ) { // phpcs:ignore WordPress.Security
			$active_tab = sanitize_key( (string) $_GET[ self::TAB__URL_PARAMETER_KEY ] ); // phpcs:ignore WordPress.Security
		}

		foreach ( self::$tabs as $tab ) {
			if ( $tab->get_tab_url_arg() === $active_tab ) {
				return $tab;
			}
		}

		$default_tab = self::$tabs[0];
		return $default_tab;
	}

	/**
	 * Whether or not the option page for plugin is displayed.
	 *
	 * @return bool
	 */
	public static function is_active_screen() {
		$screen = get_current_screen();
		if ( isset( $screen, $screen->id ) && ( strpos( $screen->id, self::MENU_SLUG ) !== false ) ) {
			return true;
		}

		return false;
	}

	/**
	 * The base CSS class. See trait BEM_Class_Naming_Trait for more.
	 *
	 * @return string
	 */
	protected function get_bem_base_class() {
		return 'twrpb-admin';
	}

}

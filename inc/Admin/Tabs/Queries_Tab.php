<?php

namespace TWRP\Admin\Tabs;

use TWRP\Admin\Settings_Menu;
use TWRP\Database\Query_Options;
use TWRP\Admin\Tabs\Query_Options\Modify_Query_Settings;
use TWRP\Admin\Tabs\Query_Options\Query_Existing_Table;
use TWRP\Query_Generator\Query_Generator;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;
use RuntimeException;
use TWRP\Utils\Simple_Utils;

/**
 * Implements a tab in the Settings Menu called "Queries Tab". The implemented
 * focus on creating queries that will remember by which properties the posts
 * should be retrieved, in which order, how to filter them, ..etc. In short,
 * it's a easy UI for the user to create parameters for WP_Query function.
 *
 * For a given query, this class shows an UI with each filter implemented. Each
 * filter has it's own class, and implement Query_Setting interface.
 */
class Queries_Tab extends Admin_Menu_Tab {

	use BEM_Class_Naming_Trait;

	/**
	 * The value that represents the tab in the query URL parameter.
	 *
	 * Ex: .../wp-admin/options-general.php?page=tabs_with_recommended_posts&tab=query_posts
	 */
	const TAB_URL_ARG = 'query_posts';

	/**
	 * The URL parameter key which say what query ID should be edited. If this
	 * parameter has no value, then a new query is added.
	 */
	const EDIT_QUERY__URL_PARAM_KEY = 'query_edit_id';

	/**
	 * The URL parameter key which say which query should be deleted.
	 */
	const DELETE_QUERY__URL_PARAM_KEY = 'query_delete_id';

	/**
	 * Name of the nonce from the edit form.
	 */
	const NONCE_EDIT_NAME = 'twrp_query_nonce';

	/**
	 * Action of the nonce from the edit form.
	 */
	const NONCE_EDIT_ACTION = 'twrp_edit_query';

	/**
	 * Name of the nonce to delete a query.
	 */
	const NONCE_DELETE_NAME = 'twrp_query_delete_nonce';

	/**
	 * Action of the nonce to delete a query.
	 */
	const NONCE_DELETE_ACTION = 'twrp_delete_query';

	/**
	 * The name of the button that submit the Add/Edit form. The constant
	 * is also used as the value attribute of the button.
	 */
	const SUBMIT_BTN_NAME = 'twrp_query_submitted';

	/**
	 * Get the value that represents the tab in the query URL parameter.
	 *
	 * Ex: .../wp-admin/options-general.php?page=tabs_with_recommended_posts&tab=query_posts
	 *
	 * @return string
	 */
	public function get_tab_url_arg() {
		return self::TAB_URL_ARG;
	}

	/**
	 * Get the tab title, it will be displayed on the tab button.
	 *
	 * @return string
	 */
	public function get_tab_title() {
		return _x( 'Tab Queries', 'backend', 'tabs-with-posts' );
	}

	/**
	 * Display the tab contents.
	 *
	 * @return void
	 */
	public function display_tab() {
		if ( $this->form_has_been_submitted() ) {
			if ( $this->edit_nonce_is_valid() ) {
				$modify_query_settings = new Modify_Query_Settings();
				$modify_query_settings->update_form_submitted_settings();

				$this->display_existing_queries_page( 'add-query-saved-notification' );
				return;
			} else {
				wp_nonce_ays( self::NONCE_EDIT_ACTION );
			}
		}

		if ( $this->delete_button_clicked() ) {
			if ( $this->verify_delete_nonce() ) {
				$this->execute_delete_query_action();

				$this->display_existing_queries_page( 'add-query-deleted-notification' );
				return;
			} else {
				wp_nonce_ays( self::NONCE_DELETE_ACTION );
			}
		}

		if ( $this->edit_query_screen_is_displayed() ) {
			$this->display_query_form();
		} else {
			$this->display_existing_queries_page();
		}
	}

	#region -- Existing Queries Table Methods

	/**
	 * Creates a table that display the existing queries.
	 *
	 * @param 'add-query-saved-notification'|'add-query-deleted-notification'|'' $additional_notification
	 * An additional notification box to add before displaying the table.
	 * @return void
	 */
	public function display_existing_queries_page( $additional_notification = '' ) {
		$delete_query_confirmation_message = _x( 'Are you sure that do you want to delete this query?', 'backend', 'tabs-with-posts' );
		$existing_queries_table            = new Query_Existing_Table();
		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php
			if ( 'add-query-saved-notification' === $additional_notification ) {
				$this->display_successfully_query_saved_message();
			} elseif ( 'add-query-deleted-notification' === $additional_notification ) {
				$this->display_successfully_query_deleted_message();
			}
			?>

			<h3 class="<?php $this->bem_class( 'title' ); ?>"><?php echo esc_html_x( 'Existing Queries:', 'backend', 'tabs-with-posts' ); ?></h3>
			<?php $this->display_queries_that_depends_on_non_installed_plugins(); ?>
			<div id="<?php $this->bem_class( 'before-deleting-confirmation' ); ?>" style="display:none" data-twrpb-query-delete-confirm="<?php echo esc_attr( $delete_query_confirmation_message ); ?>"></div>
			<?php
			do_action( 'twrp_before_displaying_existing_queries_table' );
			$existing_queries_table->display();
			$this->display_existing_queries_add_new_btn();
			do_action( 'twrp_after_displaying_existing_queries_table' );
			?>
		</div>
		<?php
	}

	/**
	 * Return the url to edit a query. If query doesn't exist it will
	 * return the tab link.
	 *
	 * @param int|string $query_id The ID.
	 *
	 * @return string
	 */
	public function get_query_edit_link( $query_id ) {
		$tab_url = Settings_Menu::get_tab_url( $this );

		if ( Query_Options::query_exists( $query_id ) ) {
			return add_query_arg( self::EDIT_QUERY__URL_PARAM_KEY, (string) $query_id, $tab_url );
		}

		return $tab_url;
	}

	/**
	 * Return the url to delete a query. If query doesn't exist it will
	 * return the tab link.
	 *
	 * @param int|string $query_id The ID.
	 *
	 * @return string
	 */
	public function get_query_delete_link( $query_id ) {
		$tab_url = Settings_Menu::get_tab_url( $this );

		if ( Query_Options::query_exists( $query_id ) ) {
			$url = add_query_arg( self::DELETE_QUERY__URL_PARAM_KEY, (string) $query_id, $tab_url );
			$url = add_query_arg( self::NONCE_DELETE_NAME, wp_create_nonce( self::NONCE_DELETE_ACTION ), $url );
			return $url;
		}

		return $tab_url;
	}

	/**
	 * Display a button to add a new query. Used on the existed queries page.
	 *
	 * @return void
	 */
	protected function display_existing_queries_add_new_btn() {
		$add_btn_icon = '<span class="' . $this->get_bem_class( 'add-query-btn-icon' ) . ' dashicons dashicons-plus"></span>';
		?>
		<a class="<?php $this->bem_class( 'add-query-btn' ); ?> twrpb-button twrpb-button--save twrpb-button--large" href=<?php echo esc_url( $this->get_new_query_link() ); ?>>
			<?php
				/* translators: %s: plus dashicon html. */
				echo wp_kses( sprintf( _x( '%s Add New Query', 'backend', 'tabs-with-posts' ), $add_btn_icon ), Simple_Utils::get_plugin_allowed_kses_html() );
			?>
		</a>
		<?php
	}

	/**
	 * Display the queries that cannot be displayed correctly because a plugin
	 * is not installed.
	 *
	 * @return void
	 */
	protected function display_queries_that_depends_on_non_installed_plugins() {
		$existing_queries    = Query_Options::get_all_queries();
		$unsupported_queries = array();

		foreach ( $existing_queries as $id => $settings ) {
			if ( ! Query_Options::query_plugin_dependencies_installed( $id ) ) {
				array_push( $unsupported_queries, $id );
			}
		}

		if ( empty( $unsupported_queries ) ) {
			return;
		}

		$queries_names = '';
		foreach ( $unsupported_queries as $id ) {
			$query_name = Query_Options::get_query_display_name( $id );
			if ( empty( $queries_names ) ) {
				$queries_names = '"' . $query_name . '"';
			} else {
				$queries_names = $queries_names . ', "' . $query_name . '"';
			}
		}
		/* translators: %s will be replaced with a name, or a list of names. */
		$message = sprintf( _x( 'The query/queries: %s cannot be displayed because an external plugin that is needed is not installed.', 'backend', 'tabs-with-posts' ), $queries_names );

		$documentation_url = Settings_Menu::get_tab_url( new Documentation_Tab() );
		/* translators: both %1$s and %2$s will be replaced with an anchor HTML tag, so the "documentation" word must be between them.  */
		$message_2 = sprintf( _x( 'See the %1$s documentation%2$s for supported rating/views plugins.', 'backend', 'tabs-with-posts' ), '<a href="' . esc_url( $documentation_url ) . '" target="_blank">', '</a>' );
		?>
			<p>
				<?php echo esc_html( $message ) . ' ' . wp_kses( $message_2, Simple_Utils::get_plugin_allowed_kses_html() ); ?>
			</p>
		<?php
	}

	/**
	 * Return the url to add a new query.
	 *
	 * @return string
	 */
	protected function get_new_query_link() {
		$add_new_link = Settings_Menu::get_tab_url( $this );
		$add_new_link = add_query_arg( self::EDIT_QUERY__URL_PARAM_KEY, '', $add_new_link );

		return $add_new_link;
	}

	/**
	 * Check to see if a deleted button of a query has been clicked.
	 *
	 * @return bool True if has been clicked, false otherwise.
	 */
	protected function delete_button_clicked() {
		if ( isset( $_GET[ self::DELETE_QUERY__URL_PARAM_KEY ] ) ) { // phpcs:ignore -- Nonce verified.
			return true;
		}

		return false;
	}

	/**
	 * Verify if the nonce of the deleted query is valid.
	 *
	 * @return bool
	 */
	protected function verify_delete_nonce() {
		if ( isset( $_GET[ self::NONCE_DELETE_NAME ] ) ) {
			$nonce_value = sanitize_key( (string) $_GET[ self::NONCE_DELETE_NAME ] );
			$nonce_check = wp_verify_nonce( $nonce_value, self::NONCE_DELETE_ACTION );
			if ( 1 === $nonce_check ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Delete the query when the button is clicked.
	 *
	 * @return void
	 */
	protected function execute_delete_query_action() {
		if ( isset( $_GET[ self::DELETE_QUERY__URL_PARAM_KEY ] ) ) { // phpcs:ignore -- Nonce verified and input sanitized.
			$key = wp_unslash( $_GET[ self::DELETE_QUERY__URL_PARAM_KEY ] ); // phpcs:ignore -- Nonce verified and input sanitized.
			if ( ! is_string( $key ) ) {
				return;
			}
			$key = sanitize_key( $key ); // phpcs:ignore -- Nonce verified and input sanitized.

			if ( Query_Options::query_exists( $key ) ) {
				Query_Options::delete_query( $key );
			}
		}
	}

	/**
	 * Display the message that the query settings were saved successfully.
	 *
	 * @return void
	 */
	protected function display_successfully_query_saved_message() {
		?>
		<div class="<?php $this->bem_class( 'notification' ); ?> twrpb-notification twrpb-notification--success">
			<?php echo esc_html_x( 'Settings saved successfully.', 'backend', 'tabs-with-posts' ); ?>
		</div>
		<?php
	}

	/**
	 * Display the message that the query settings were deleted successfully.
	 *
	 * @return void
	 */
	protected function display_successfully_query_deleted_message() {
		?>
		<div class="<?php $this->bem_class( 'notification' ); ?> twrpb-notification twrpb-notification--success">
			<?php echo esc_html_x( 'Query deleted successfully.', 'backend', 'tabs-with-posts' ); ?>
		</div>
		<?php
	}

	#endregion -- Existing Queries Table Methods

	#region -- Edit Queries Settings

	/**
	 * Display the form to add a new query or to modify a pre-existed query.
	 *
	 * @return void
	 */
	protected function display_query_form() {
		$query_settings_display = new Modify_Query_Settings()
		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php $this->display_back_to_existing_tables_btn(); ?>
			<form action="<?php echo esc_url( $this->get_edit_query_form_action() ); ?>" method="post">
				<?php $query_settings_display->display(); ?>
				<?php wp_nonce_field( self::NONCE_EDIT_ACTION, self::NONCE_EDIT_NAME ); ?>

				<button
					class="<?php $this->bem_class( 'save-query-btn' ); ?> twrpb-button twrpb-button--save twrpb-button--large"
					name="<?php echo esc_attr( self::SUBMIT_BTN_NAME ); ?>"
					value="<?php echo esc_attr( self::SUBMIT_BTN_NAME ); ?>"
					type="submit"
				>
					<?php echo esc_html_x( 'Save Settings', 'backend', 'tabs-with-posts' ); ?>
				</button>
			</form>
			<?php $this->display_query_debug(); ?>
		</div>
		<?php
	}

	/**
	 * After the form, display a nice output of the query created.
	 *
	 * @return void
	 */
	protected function display_query_debug() {
		$no_query_id = false;
		$query_id    = $this->get_sanitized_id_of_query_being_modified();

		if ( empty( $query_id ) ) {
			$no_query_id = true;
		}

		$query_arguments = array();
		try {
			$query_arguments = Query_Generator::get_wp_query_arguments( $query_id );
		} catch ( RuntimeException $e ) {
			$no_query_id = true;
		}

		$result_json = wp_json_encode( $query_arguments );
		$result_json = ( false === $result_json ? '' : $result_json );

		if ( $no_query_id ) {
			$result_to_display = _x( 'You must first save the query, then return here to see the arguments generated.', 'backend', 'tabs-with-posts' );
		} else {
			$result_to_display = print_r( $query_arguments, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			// Regex, remove lines that are empty or have only one character, Usually they have open or close parenthesis.
			$result_to_display = preg_replace( '/^\s*.?\n/m', '', $result_to_display );
			$result_to_display = preg_replace( '/^(?:Array\n|    )/m', '', $result_to_display );
		}

		?>
		<div id="<?php $this->bem_class( 'query_generated_array_container' ); ?>" class="<?php $this->bem_class( 'query_generated_array_container' ); ?>" data-twrpb-query-generated-array="<?php echo esc_attr( $result_json ); ?>">
			<button id="<?php $this->bem_class( 'query_generated_array_btn' ); ?>" class="button <?php $this->bem_class( 'query_generated_array_btn' ); ?>" type="button">&#9660;&nbsp;&nbsp;<?php echo esc_html_x( 'See WP_Query arguments generated', 'backend', 'tabs-with-posts' ); ?></button>

			<div class="<?php $this->bem_class( 'query_args_collapsible' ); ?>">
				<div class="<?php $this->bem_class( 'query_generated_note' ); ?>">
					<?php echo esc_html_x( 'These arguments are generated when this page is loaded, and does not live-change when a setting here is modified.', 'backend', 'tabs-with-posts' ); ?>
				</div>

				<div class="<?php $this->bem_class( 'query_generated_array' ); ?>"><?php echo esc_html( $result_to_display ); ?></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Display a button to get back to the existing queries table.
	 *
	 * @return void
	 */
	protected function display_back_to_existing_tables_btn() {
		$link        = Settings_Menu::get_tab_url( $this );
		$button_text = _x( '< Back to the Queries Overview', 'backend', 'tabs-with-posts' );
		?>
		<a href="<?php echo esc_url( $link ); ?>" class="<?php $this->bem_class( 'back-btn' ); ?> twrpb-button twrpb-button--large">
			<?php echo esc_html( $button_text ); ?>
		</a>
		<?php
	}


	/**
	 * Check to see if the user is currently editing a query.
	 *
	 * @return bool
	 */
	protected function edit_query_screen_is_displayed() {
		// phpcs:ignore -- No need for sanitization
		if ( isset( $_GET[ self::EDIT_QUERY__URL_PARAM_KEY ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the ID of the query being modified. Returns an empty string if
	 * a new query is added.
	 *
	 * @return string The ID of the query being modified, or an empty string.
	 */
	public function get_sanitized_id_of_query_being_modified() {
		if ( isset( $_GET[ self::EDIT_QUERY__URL_PARAM_KEY ] ) ) { // phpcs:ignore -- Nonce verified.
			// phpcs:ignore WordPress.Security -- The setting is sanitized below.
			$key = wp_unslash( $_GET[ self::EDIT_QUERY__URL_PARAM_KEY ] );

			if ( is_numeric( $key ) && Query_Options::query_exists( $key ) ) {
				return $key;
			}
		}

		return '';
	}

	/**
	 * Get the HTML form action attribute(URL).
	 *
	 * @return string
	 */
	protected function get_edit_query_form_action() {
		return Settings_Menu::get_tab_url( $this );
	}

	/**
	 * Whether or not the user have submitted the form.
	 *
	 * @return bool
	 */
	protected function form_has_been_submitted() {
		// phpcs:ignore -- Nonce verified.
		if ( isset( $_POST[ self::SUBMIT_BTN_NAME ] ) && self::SUBMIT_BTN_NAME === $_POST[ self::SUBMIT_BTN_NAME ] ) {
			return true;
		}

		return false;
	}

	/**
	 * Verify if the nonce from the edit query screen is valid.
	 *
	 * @return bool True if is valid, false otherwise.
	 */
	protected function edit_nonce_is_valid() {
		if ( isset( $_POST[ self::NONCE_EDIT_NAME ] ) ) {
			$nonce_value = sanitize_key( (string) $_POST[ self::NONCE_EDIT_NAME ] );
			$nonce_check = wp_verify_nonce( $nonce_value, self::NONCE_EDIT_ACTION );
			if ( 1 === $nonce_check ) {
				return true;
			}
		}

		return false;
	}

	#endregion -- Edit Queries Settings

	protected function get_bem_base_class() {
		return 'twrpb-query-settings';
	}

	/**
	 * Get the query setting class that all collapsible settings should have.
	 *
	 * @return string
	 */
	public function get_query_setting_wrapper_class() {
		return $this->get_bem_class( 'setting' );
	}

	/**
	 * Get the query setting class for paragraphs.
	 *
	 * @return string
	 */
	public function get_query_setting_paragraph_class() {
		return $this->get_bem_class( 'paragraph' );
	}

	/**
	 * Get the query setting class for line checkboxes.
	 *
	 * @return string
	 */
	public function query_setting_checkbox_line_class() {
		return $this->get_bem_class( 'checkbox-line' );
	}

}

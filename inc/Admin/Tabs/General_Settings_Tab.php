<?php

namespace TWRP\Admin\Tabs;

use TWRP\Database\General_Options;
use TWRP\Admin\Settings_Menu;
use TWRP\Admin\Tabs\General_Settings\General_Settings_Factory;
use TWRP\Utils\Simple_Utils;

/**
 * Display the general settings tab in the admin area. From this tab, the
 * administrator can change the settings of the plugin.
 */
class General_Settings_Tab extends Admin_Menu_Tab {

	public function get_tab_url_arg() {
		return 'general_settings';
	}

	public function get_tab_title() {
		return _x( 'General Settings', 'backend', 'tabs-with-posts' );
	}

	#region -- Display Settings

	public function display_tab() {
		?>
		<div class="twrpb-general-settings">
			<?php
			if ( self::are_settings_submitted() ) {
				if ( self::is_nonce_correct() ) {
					self::settings_submitted_success_message();
					self::save_settings_submitted();
				} else {
					wp_nonce_ays( 'twrp_general_submit_nonce' );
				}
			}
			?>

			<form class="twrpb-general-settings__form" method="post" action="<?php echo esc_url( self::get_form_action() ); ?>">

				<p class="twrpb-general-settings__pre-form-note">
					<?php echo esc_html_x( 'If you are using a plugin that cache pages, you need to purge(delete) the page data after changing these settings.', 'backend', 'tabs-with-posts' ); ?>
				</p>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Color Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php
					General_Settings_Factory::display_setting( General_Options::BACKGROUND_COLOR );
					General_Settings_Factory::display_setting( General_Options::SECONDARY_BACKGROUND_COLOR );
					General_Settings_Factory::display_setting( General_Options::TEXT_COLOR );
					General_Settings_Factory::display_setting( General_Options::DISABLED_TEXT_COLOR );
					General_Settings_Factory::display_setting( General_Options::ACCENT_COLOR );
					General_Settings_Factory::display_setting( General_Options::DARKER_ACCENT_COLOR );
					General_Settings_Factory::display_setting( General_Options::LIGHTER_ACCENT_COLOR );
					General_Settings_Factory::display_setting( General_Options::BORDER_COLOR );
					General_Settings_Factory::display_setting( General_Options::SECONDARY_BORDER_COLOR );
					?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Style Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php
					General_Settings_Factory::display_setting( General_Options::BORDER_RADIUS );
					General_Settings_Factory::display_setting( General_Options::TAB_BUTTON_SIZE );
					?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Date Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php
					General_Settings_Factory::display_setting( General_Options::HUMAN_READABLE_DATE );
					General_Settings_Factory::display_setting( General_Options::DATE_FORMAT );
					?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Thumbnail Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php
					General_Settings_Factory::display_setting( General_Options::NO_THUMBNAIL_IMAGE );
					?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Icons Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<p>
						<?php
						$icon_reference_link = Settings_Menu::get_tab_url( new Documentation_Tab() );
						$icon_reference_link = $icon_reference_link . '#twrpb-docs__all-icons-reference';
						echo wp_kses(
							sprintf(
							/* translators: %1$s and %2$s are placeholder where HTML code will be inserted, as a link wrapper(going to another page). No translation words are inserted there. Just translate the whole sentence, and be sure the corresponding words are between %$1s and %2$s tags. */
								_x( 'Note: To see a long list with all the icons, please look at %1$s documentation icons reference %2$s.', 'backend', 'tabs-with-posts' ),
								'<a href="' . esc_url( $icon_reference_link ) . '" target="_blank">',
								'</a>'
							),
							Simple_Utils::get_plugin_allowed_kses_html()
						);
						?>
					</p>
					<?php
					General_Settings_Factory::display_setting( General_Options::AUTHOR_ICON );
					General_Settings_Factory::display_setting( General_Options::DATE_ICON );
					General_Settings_Factory::display_setting( General_Options::CATEGORY_ICON );
					General_Settings_Factory::display_setting( General_Options::COMMENTS_ICON );
					General_Settings_Factory::display_setting( General_Options::COMMENTS_DISABLED_ICON );
					General_Settings_Factory::display_setting( General_Options::VIEWS_ICON );
					General_Settings_Factory::display_setting( General_Options::RATING_ICON_PACK );
					General_Settings_Factory::display_setting( General_Options::SVG_INCLUDE_INLINE );
					?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Cache Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php General_Settings_Factory::display_setting( General_Options::ENABLE_CACHE ); ?>
					<?php General_Settings_Factory::display_setting( General_Options::CACHE_AUTOMATIC_REFRESH ); ?>

					<?php $nonce = wp_create_nonce( 'twrp_refresh_widget_cache_nonce' ); ?>
					<button
						id="twrpb-refresh-cache-button"
						class="twrpb-button twrpb-general-settings__refresh-cache-btn"
						data-twrpb-refresh-cache-nonce="<?php echo esc_attr( $nonce ); ?>"
						data-twrpb-refresh-cache-waiting="<?php echo esc_attr_x( 'Waiting', 'backend', 'tabs-with-posts' ); ?>"
						data-twrpb-refresh-cache-success="<?php echo esc_attr_x( 'Success', 'backend', 'tabs-with-posts' ); ?>"
						data-twrpb-refresh-cache-failed="<?php echo esc_attr_x( 'Failed', 'backend', 'tabs-with-posts' ); ?>"
						type="button">
						<?php echo esc_html_x( 'Refresh widget cache', 'backend', 'tabs-with-posts' ); ?>
					</button>

					<?php General_Settings_Factory::display_setting( General_Options::LOAD_WIDGET_VIA_AJAX ); ?>
				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Translation', 'backend', 'tabs-with-posts' ); ?></legend>

					<button id="twrpb-general-settings__translations-show-btn" class="twrpb-button twrpb-general-settings__btn-show-translations"><?php echo esc_html_x( 'Click to show/hide translations', 'backend', 'tabs-with-posts' ); ?></button>

					<div id="twrpb-general-settings__translations-hidden" class="twrpb-general-settings__translations-container">
						<p>
							<?php echo esc_html_x( 'The string below will override the translations. If you want the default translation(or you use another plugin), then leave these empty. If you have a multi language website, then use a plugin for string localization, like "Loco Translate"(Free).', 'backend', 'tabs-with-posts' ); ?>
						</p>

						<?php General_Settings_Factory::display_setting( General_Options::SHOW_MORE_POSTS_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::POST_NO_TITLE_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::POST_WITH_NO_THUMBNAIL_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::DATE_RELATIVE_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::FAIL_TO_LOAD_WIDGET_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::NO_POSTS_TEXT ); ?>

						<?php General_Settings_Factory::display_setting( General_Options::ABBREVIATION_FOR_THOUSANDS ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ABBREVIATION_FOR_MILLIONS ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ABBREVIATION_FOR_BILLIONS ); ?>

						<?php General_Settings_Factory::display_setting( General_Options::ARIA_AUTHOR_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_DATE_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_VIEWS_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_RATING_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_CATEGORY_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_COMMENTS_TEXT ); ?>
						<?php General_Settings_Factory::display_setting( General_Options::ARIA_COMMENTS_ARE_DISABLED_TEXT ); ?>
					</div>

				</fieldset>

				<fieldset class="twrpb-general-settings__fieldset">
					<legend class="twrpb-general-settings__legend"><?php echo esc_html_x( 'Other Settings', 'backend', 'tabs-with-posts' ); ?></legend>
					<?php General_Settings_Factory::display_setting( General_Options::FILL_GRID_WITH_POSTS ); ?>
				</fieldset>

				<?php
				self::display_submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Display a message that show that the settings were submitted successfully.
	 *
	 * @return void
	 */
	protected static function settings_submitted_success_message() {
		?>
		<div class="twrpb-general-settings__success-submitted-wrapper twrpb-notification twrpb-notification--success">
			<?php echo esc_html_x( 'Settings saved successfully.', 'backend', 'tabs-with-posts' ); ?>
		</div>
		<?php
	}

	/**
	 * Check if the form has been submitted.
	 *
	 * @return bool
	 */
	protected static function are_settings_submitted() {
		if ( isset( $_POST['submit'] ) && 'submit' === wp_unslash( $_POST['submit'] ) ) { // phpcs:ignore -- Nonce verified.
			return true;
		}

		return false;
	}

	/**
	 * Get the action of the general settings form.
	 *
	 * @return string The url is not sanitized.
	 */
	protected static function get_form_action() {
		return Settings_Menu::get_tab_url( new self() );
	}

	/**
	 * Display the submit button of the form.
	 *
	 * @return void
	 */
	protected static function display_submit_button() {
		?>
		<div class="twrpb-general-settings__submit-btn-wrapper">
			<?php wp_nonce_field( 'twrp_general_submit_nonce', 'twrp_general_nonce', true, true ); ?>
			<button id="twrpb-general-settings__submit-btn" class="twrpb-general-settings__submit-btn twrpb-button twrpb-button--save twrpb-button--large" type="submit" name="submit" value="submit">
				<?php echo esc_html_x( 'Save Settings', 'backend', 'tabs-with-posts' ); ?>
			</button>
		</div>
		<?php
	}

	#endregion -- Display Settings

	#region -- Update Settings

	/**
	 * Verify if the nonce submitted is correct.
	 *
	 * @return bool
	 */
	protected static function is_nonce_correct() {
		if ( ! isset( $_POST['twrp_general_nonce'] ) || ! is_string( $_POST['twrp_general_nonce'] ) ) {
			return false;
		}

		$nonce = wp_unslash( $_POST['twrp_general_nonce'] ); // phpcs:ignore -- variable is sanitized.

		if ( ! is_string( $nonce ) ) {
			return false;
		}

		if ( 1 === wp_verify_nonce( $nonce, 'twrp_general_submit_nonce' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Save the submitted settings into database.
	 *
	 * @return void
	 */
	protected static function save_settings_submitted() {
		$settings = $_POST; // phpcs:ignore -- No need to check for nonce here.

		do_action( 'twrp_general_before_settings_submitted', $settings );

		General_Options::set_options( $settings );

		do_action( 'twrp_general_after_settings_submitted', $settings );
	}

	#endregion -- Update Settings
}

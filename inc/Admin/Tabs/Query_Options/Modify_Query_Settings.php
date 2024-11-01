<?php

namespace TWRP\Admin\Tabs\Query_Options;

use RuntimeException;
use TWRP\Utils\Class_Retriever_Utils;
use TWRP\Admin\Tabs\Query_Options\Query_Setting_Display;
use TWRP\Admin\Tabs\Queries_Tab;
use TWRP\Database\Query_Options;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class that displays the query settings, where an administrator can add/edit
 * a query setting.
 */
class Modify_Query_Settings {

	use BEM_Class_Naming_Trait;

	const NAME__QUERY_ID_HIDDEN_INPUT = 'query_id_being_modified';

	/**
	 * Display all the query settings.
	 *
	 * @return void
	 */
	public function display() {
		$settings_classes = Class_Retriever_Utils::get_all_display_query_settings_objects();
		$queries_tab      = new Queries_Tab();
		$query_id         = $queries_tab->get_sanitized_id_of_query_being_modified();

		?>
			<input type="hidden" name="<?php echo esc_attr( self::NAME__QUERY_ID_HIDDEN_INPUT ); ?>" value="<?php echo esc_attr( $query_id ); ?>">
		<?php
		$this->display_template_selector();

		foreach ( $settings_classes as $setting_class ) :
			$this->display_query_setting( $setting_class );
		endforeach;
	}

	/**
	 * Display the section that lets an administrator to apply a common template
	 * to all the settings.
	 *
	 * @return void
	 */
	protected function display_template_selector() {
		$query_templates_class          = new Query_Templates();
		$query_templates_settings       = wp_json_encode( $query_templates_class->get_all_query_posts_templates() );
		$query_templates_options        = $query_templates_class->get_all_query_posts_templates_options();
		$templates_confirmation_message = _x( 'This will modify all the current settings to the specific template. Are you sure?', 'backend', 'tabs-with-posts' );

		if ( false === $query_templates_settings ) {
			$query_templates_settings = '';
		}

		?>
		<div class="<?php $this->bem_class( 'apply_templates_container' ); ?>">
			<div class="<?php $this->bem_class( 'apply_templates_label' ); ?>">
				<?php echo esc_html_x( 'Start with a template:', 'backend', 'tabs-with-posts' ); ?>
			</div>

			<select
				id="<?php $this->bem_class( 'predefined_template_selector' ); ?>"
				class="<?php $this->bem_class( 'predefined_template_selector' ); ?>"
				data-twrpb-templates="<?php echo esc_attr( $query_templates_settings ); ?>"
				data-twrpb-confirm-template-changes="<?php echo esc_attr( $templates_confirmation_message ); ?>"
			>
				<?php foreach ( $query_templates_options as $value => $display_option ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $display_option ); ?></option>
				<?php endforeach; ?>
			</select>

			<button id="<?php $this->bem_class( 'apply_template_btn' ); ?>" class="twrpb-button <?php $this->bem_class( 'apply_template_btn' ); ?>" type="button">
				<?php echo esc_html_x( 'Apply template', 'backend', 'tabs-with-posts' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Display a specific setting control in query settings.
	 *
	 * @param Query_Setting_Display $setting_display_class
	 * @return void
	 */
	protected function display_query_setting( $setting_display_class ) {
		$current_setting       = $this->get_query_input_setting( $setting_display_class );
		$collapsed             = $this->get_if_setting_collapsed( $setting_display_class, $current_setting ) ? '1' : '0';
		$queries_tab           = new Queries_Tab();
		$default_settings_json = wp_json_encode( $setting_display_class->get_default_setting() );
		if ( false === $default_settings_json ) {
			$default_settings_json = '';
		}

		?>
		<div class="<?php echo esc_attr( $queries_tab->get_query_setting_wrapper_class() ); ?> twrpb-collapsible"
			data-twrpb-is-collapsed="<?php echo esc_attr( $collapsed ); ?>"
			data-twrpb-default-settings="<?php echo esc_attr( $default_settings_json ); ?>"
			data-twrpb-setting-name="<?php echo esc_attr( $setting_display_class->get_setting_name() ); ?>"
		>
			<h2 class="twrpb-collapsible__title">
				<span class="twrpb-collapsible__indicator"></span>
				<?php echo $setting_display_class->get_title(); // phpcs:ignore -- No need to escape title. ?>
			</h2>
			<div class="twrpb-collapsible__content">
				<?php $setting_display_class->display_setting( $current_setting ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get the current setting of the query.
	 *
	 * @param Query_Setting_Display $setting_class
	 * @return mixed The specific settings of the query, sanitized.
	 */
	protected function get_query_input_setting( $setting_class ) {
		$queries_tab_class = new Queries_Tab();
		try {
			$query_id           = $queries_tab_class->get_sanitized_id_of_query_being_modified();
			$all_query_settings = Query_Options::get_all_query_settings( $query_id );
		} catch ( RuntimeException $e ) { // phpcs:ignore -- Empty catch.
			// Do nothing.
		}

		if ( isset( $all_query_settings[ $setting_class->get_setting_name() ] ) ) {
			return $all_query_settings[ $setting_class->get_setting_name() ];
		}

		return $setting_class->get_default_setting();
	}

	/**
	 * Get whether or not the setting should be collapsed
	 *
	 * @param Query_Setting_Display $display_setting_class
	 * @param array $current_settings
	 * @return bool
	 */
	protected function get_if_setting_collapsed( $display_setting_class, $current_settings ) {
		$setting_is_collapsed = $display_setting_class->setting_is_collapsed();
		if ( is_bool( $setting_is_collapsed ) ) {
			return $setting_is_collapsed;
		}

		$default_settings = $display_setting_class->get_default_setting();

		array_multisort( $default_settings );
		array_multisort( $current_settings );

		if ( $current_settings === $default_settings ) {
			return false;
		}

		return true;
	}

	/**
	 * Add a new query settings, or updates a query when the form is submitted.
	 *
	 * @return void
	 */
	public function update_form_submitted_settings() {
		$settings_classes_name = Class_Retriever_Utils::get_all_display_query_settings_objects();
		$query_settings        = array();

		foreach ( $settings_classes_name as $setting_class ) {
			$query_settings[ $setting_class->get_setting_name() ] = $setting_class->get_submitted_sanitized_setting();
		}

		$query_key = $this->get_sanitized_submitted_query_id_being_modified();
		if ( ! $query_key ) {
			Query_Options::add_new_query( $query_settings );
		} elseif ( Query_Options::query_exists( $query_key ) ) {
			Query_Options::update_query( $query_key, $query_settings );
		}
	}

	/**
	 * Get the submitted query id that the settings are modified.
	 *
	 * @return int|false False if the query id does not exist, or the id is not valid.
	 */
	protected function get_sanitized_submitted_query_id_being_modified() {
		if ( ! isset( $_POST, $_POST[ self::NAME__QUERY_ID_HIDDEN_INPUT ] ) ) { // phpcs:ignore -- Nonce verified.
			return false;
		}

		$query_id = intval( wp_unslash( $_POST[ self::NAME__QUERY_ID_HIDDEN_INPUT ] ) ); // phpcs:ignore -- Nonce verified.

		if ( Query_Options::query_exists( $query_id ) ) {
			return $query_id;
		}

		return false;
	}

	protected function get_bem_base_class() {
		return 'twrpb-query-settings';
	}
}

<?php

namespace TWRP\Admin\Tabs\Query_Options;

use TWRP\Query_Generator\Query_Setting\Post_Date;
use TWRP\Admin\Helpers\Remember_Note;

/**
 * Used to display the post date query setting control.
 */
class Post_Date_Display extends Query_Setting_Display {

	public static function get_class_order_among_siblings() {
		return 70;
	}

	protected function get_setting_class() {
		return new Post_Date();
	}

	public function get_title() {
		return _x( 'Filter by date', 'backend', 'tabs-with-posts' );
	}

	#region -- Display Settings

	public function display_setting( $current_setting ) {
		?>
		<div class="<?php $this->bem_class(); ?>">
			<?php $this->display_date_filter_type( $current_setting[ Post_Date::DATE_TYPE_NAME ] ); ?>
			<?php $this->display_last_period_of_time_settings( $current_setting ); ?>
			<?php $this->display_between_fixed_point_in_time_settings( $current_setting ); ?>
		</div>
		<?php
	}

	/**
	 * Display the setting to select the type of date that the articles will be
	 * filter on.
	 *
	 * @param string $date_type
	 * @return void
	 */
	protected function display_date_filter_type( $date_type ) {
		$setting_class = $this->get_setting_class();
		?>
		<div class="<?php $this->bem_class( 'type-selector-wrapper' ); ?> <?php $this->query_setting_paragraph_class(); ?>">
			<select id="<?php $this->bem_class( 'js-date-type' ); ?>" name="<?php echo esc_attr( $setting_class->get_setting_name() . '[' . Post_Date::DATE_TYPE_NAME . ']' ); ?>">
				<option value="NA" <?php selected( 'NA', $date_type ); ?>>
					<?php echo esc_html_x( 'Not Applied', 'backend', 'tabs-with-posts' ); ?>
				</option>

				<option value="LT" <?php selected( 'LT', $date_type ); ?>>
					<?php echo esc_html_x( 'Last period of time', 'backend', 'tabs-with-posts' ); ?>
				</option>

				<option value="FT" <?php selected( 'FT', $date_type ); ?>>
					<?php echo esc_html_x( 'Between points in time', 'backend', 'tabs-with-posts' ); ?>
				</option>
			</select>
		</div>
		<?php
	}

	/**
	 * Display the radio buttons to choose to display posts from last periods of
	 * time, like last week/month/year... etc, or a custom number of days.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_last_period_of_time_settings( $current_setting ) {
		$setting_class  = $this->get_setting_class();
		$name           = $setting_class->get_setting_name() . '[' . Post_Date::DATE_LAST_PERIOD_NAME . ']';
		$last_days_name = $setting_class->get_setting_name() . '[' . Post_Date::DATE_LAST_DAYS_NAME . ']';

		$last_period = '';
		if ( isset( $current_setting[ Post_Date::DATE_LAST_PERIOD_NAME ] ) ) {
			$last_period = $current_setting[ Post_Date::DATE_LAST_PERIOD_NAME ];
		}

		$last_days_num = '';
		if ( isset( $current_setting[ Post_Date::DATE_LAST_DAYS_NAME ] ) && is_numeric( $current_setting[ Post_Date::DATE_LAST_DAYS_NAME ] ) ) {
			$last_days_num = $current_setting[ Post_Date::DATE_LAST_DAYS_NAME ];
		}

		$additional_settings_hidden_class = '';
		if ( ( ! isset( $current_setting[ Post_Date::DATE_TYPE_NAME ] ) ) || ( 'LT' !== $current_setting[ Post_Date::DATE_TYPE_NAME ] ) ) {
			$additional_settings_hidden_class = ' twrpb-hidden';
		}

		?>
		<div id="<?php $this->bem_class( 'js-last-period-wrapper' ); ?>" class="<?php $this->bem_class( 'last-period-wrapper' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $additional_settings_hidden_class ); ?>">
			<?php
			$remember_note = new Remember_Note( Remember_Note::NOTE__POST_DATE_SETTING_REMEMBER, 'warning' );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() . ' twrpb-hidden' );
			?>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?>">
				<input id="<?php $this->bem_class( 'last-7-days' ); ?>" type="radio" value="L7D"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'L7D', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'last-7-days' ); ?>">
					<?php echo esc_html_x( 'Last 7 days', 'backend', 'tabs-with-posts' ); ?>
				</label>
			</p>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?>">
				<input id="<?php $this->bem_class( 'last-30-days' ); ?>" type="radio" value="L30D"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'L30D', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'last-30-days' ); ?>">
					<?php echo esc_html_x( 'Last 30 days', 'backend', 'tabs-with-posts' ); ?>
				</label>
			</p>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?>">
				<input id="<?php $this->bem_class( 'last-week' ); ?>" type="radio" value="LW"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'LW', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'last-week' ); ?>">
					<?php echo esc_html_x( 'This and last week (max 7-14 days)', 'backend', 'tabs-with-posts' ); ?>
				</label>
			</p>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?>">
				<input id="<?php $this->bem_class( 'last-month' ); ?>" type="radio" value="LM"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'LM', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'last-month' ); ?>">
					<?php echo esc_html_x( 'This and last month (max 30-60 days)', 'backend', 'tabs-with-posts' ); ?>
				</label>
			</p>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?>">
				<input id="<?php $this->bem_class( 'this-year' ); ?>" type="radio" value="TY"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'TY', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'this-year' ); ?>">
					<?php echo esc_html_x( 'This year', 'backend', 'tabs-with-posts' ); ?>
				</label>
			</p>

			<p class="<?php $this->query_setting_checkbox_line_class(); ?> <?php $this->bem_class( 'custom-last-days-wrapper' ); ?>">
				<input id="<?php $this->bem_class( 'js-custom' ); ?>" type="radio" value="C"
					name="<?php echo esc_attr( $name ); ?>"
					<?php checked( 'C', $last_period ); ?>
				/>
				<label for="<?php $this->bem_class( 'js-custom' ); ?>">
					<?php echo esc_html_x( 'Last n days:', 'backend', 'tabs-with-posts' ); ?>
				</label>

				<input
					id="<?php $this->bem_class( 'js-last-days-input' ); ?>"
					class="<?php $this->bem_class( 'last-days-input' ); ?>"
					min="0" step="1" type="number"
					name="<?php echo esc_attr( $last_days_name ); ?>"
					placeholder="<?php echo esc_attr_x( 'Last number of days...', 'backend', 'tabs-with-posts' ); ?>"
					value="<?php echo esc_attr( (string) $last_days_num ); ?>"
				/>
			</p>
		</div>
		<?php
	}

	/**
	 * Display the radio buttons to choose to display posts from last periods of
	 * time, like last week/month/year... etc, or a custom number of days.
	 *
	 * @param array $current_setting
	 * @return void
	 */
	protected function display_between_fixed_point_in_time_settings( $current_setting ) {
		$after_value = '';
		if ( isset( $current_setting[ Post_Date::AFTER_DATE_NAME ] ) ) {
			$after_value = $current_setting[ Post_Date::AFTER_DATE_NAME ];
		}

		$before_value = '';
		if ( isset( $current_setting[ Post_Date::BEFORE_DATE_NAME ] ) ) {
			$before_value = $current_setting[ Post_Date::BEFORE_DATE_NAME ];
		}

		$is_hidden_class = '';
		if ( 'FT' !== $current_setting[ Post_Date::DATE_TYPE_NAME ] ) {
			$is_hidden_class = ' twrpb-hidden';
		}

		?>
		<div id="<?php $this->bem_class( 'js-between-wrapper' ); ?>" class="<?php $this->bem_class( 'between-wrapper' ); ?> <?php $this->query_setting_paragraph_class(); ?><?php echo esc_attr( $is_hidden_class ); ?>">
			<?php
			$setting_class = $this->get_setting_class();
			$remember_note = new Remember_Note( Remember_Note::NOTE__POST_DATE_AFTER_BEFORE_SETTING_EXAMPLE );
			$remember_note->display_note( $this->get_query_setting_paragraph_class() );
			?>

			<span class="<?php $this->bem_class( 'after-wrapper' ); ?>">
				<label for="<?php $this->bem_class( 'after' ); ?>" class="<?php $this->bem_class( 'after-label' ); ?>">
					<?php echo esc_html_x( 'After:', 'backend', 'tabs-with-posts' ); ?>
				</label>
				<br />
				<input id="<?php $this->bem_class( 'after' ); ?>" class="<?php $this->bem_class( 'after' ); ?>" type="date" autocomplete="off"
					name="<?php echo esc_attr( $setting_class->get_setting_name() . '[' . Post_Date::AFTER_DATE_NAME . ']' ); ?>"
					value="<?php echo esc_attr( $after_value ); ?>"
				/>
			</span>

			<span class="<?php $this->bem_class( 'after-before-separator' ); ?>"> &nbsp;&ndash;&nbsp; </span>

			<span class="<?php $this->bem_class( 'before-wrapper' ); ?>">
				<label for="<?php $this->bem_class( 'before' ); ?>" class="<?php $this->bem_class( 'before-label' ); ?>">
					<?php echo esc_html_x( 'Before:', 'backend', 'tabs-with-posts' ); ?>
				</label>
				<br />
				<input id="<?php $this->bem_class( 'before' ); ?>" class="<?php $this->bem_class( 'before' ); ?>" type="date" autocomplete="off"
					name="<?php echo esc_attr( $setting_class->get_setting_name() . '[' . Post_Date::BEFORE_DATE_NAME . ']' ); ?>"
					value="<?php echo esc_attr( $before_value ); ?>"
				/>
			</span>
		</div>
		<?php
	}

	#endregion -- Display Settings

	protected function get_bem_base_class() {
		return 'twrpb-date-settings';
	}

}

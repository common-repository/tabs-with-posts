<?php

namespace TWRP\Admin\Tabs\General_Settings;

use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;
use TWRP\Utils\Simple_Utils;

/**
 * Abstract class used to create the settings in "General Settings" tab.
 *
 * The "General" term used here does NOT mean as the "default" or in general, it
 * means that is primary used for the "General Settings" interface(so contrary
 * to what is to be expected), where a user could change the main settings.
 */
abstract class General_Setting_Creator {

	use BEM_Class_Naming_Trait;

	/**
	 * HTML class name for all settings.
	 */
	const ADDITIONAL_BLOCK_CLASS_NAME = 'twrpb-general-settings__setting-group';

	/**
	 * HTML class name to add if a setting is hidden.
	 */
	const SETTING_IS_HIDDEN_CLASS_NAME = 'twrpb-hidden';

	/**
	 * Holds the name of the setting. Used in HTML "name" attribute, so these
	 * must be unique across setting elements.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Holds the current value of the setting.
	 *
	 * @var string
	 */
	protected $value;

	/**
	 * Holds the title of the setting.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * A detailed description about a setting, if necessary.
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Holds the additional HTML attributes of the element. Usually used to
	 * insert additional data-* attributes into HTML to use in JavaScript. This
	 * value is escaped. These attributes are added at the outermost DIV block,
	 * aka called in display() function, so no need to call it in
	 * display_internal_setting(), this is why is marked as private.
	 *
	 * @var string
	 */
	private $additional_attr;

	/**
	 * Hold the additional options for a setting. Not used in all settings.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Holds all raw arguments passed in the constructor as the third argument.
	 *
	 * @var array
	 */
	protected $all_args;

	/**
	 * Hold the additional input attributes. Used only if the setting is a
	 * single input HTML element. Marked as private because it should be not
	 * used in concrete classes, use display_input_attributes() instead.
	 *
	 * @var array
	 */
	private $input_attr;

	/**
	 * Whether or not the whole setting is hidden initially.
	 *
	 * @var bool
	 */
	private $is_hidden;

	/**
	 * Construct the Object that will display a setting in the General Settings
	 * tab.
	 *
	 * @param string $name The name of the input.
	 * @param string|null $value The current value of the input, null for default.
	 * @param array{title:string,default:string,options:?array,additional_attr:?array,input_attr:?array} $args
	 */
	public function __construct( $name, $value, $args ) {
		$this->name = $name;

		if ( null === $value ) {
			if ( isset( $args['default'] ) ) {
				$this->value = $args['default'];
			} else {
				$this->value = '';
			}
		} else {
			$this->value = $value;
		}

		$this->options = array();
		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			$this->options = $args['options'];
		}

		$this->title = '';
		if ( isset( $args['title'] ) && is_string( $args['title'] ) ) {
			$this->title = $args['title'];
		}

		$this->additional_attr = '';
		if ( isset( $args['additional_attr'] ) && is_array( $args['additional_attr'] ) ) {
			$this->additional_attr = $this->create_additional_attributes( $args['additional_attr'] );
		}

		$this->input_attr = array();
		if ( isset( $args['input_attr'] ) && is_array( $args['input_attr'] ) ) {
			$this->input_attr = $args['input_attr'];
		}

		$this->is_hidden = false;
		if ( isset( $args['is_hidden'] ) && true === $args['is_hidden'] ) {
			$this->is_hidden = true;
		}

		$this->description = '';
		if ( isset( $args['description'] ) && is_string( $args['description'] ) ) {
			$this->description = $args['description'];
		}

		$this->all_args = $args;
	}

	/**
	 * Display the whole setting, with the title and the controller.
	 *
	 * @return void
	 */
	public function display() {
		?>
		<div
			id="<?php echo esc_attr( $this->get_setting_wrapper_attr_id() ); ?>"
			class="<?php echo esc_html( $this->get_main_html_element_class_name() ); ?>"
			<?php echo $this->additional_attr // phpcs:ignore -- Pre-escaped. ?>
		>

			<div class="<?php $this->bem_class( 'title' ); ?>">
				<?php echo $this->title; // phpcs:ignore -- No XSS ?>
			</div>

			<?php if ( ! empty( $this->description ) ) : ?>
				<div class="<?php $this->bem_class( 'description' ); ?>">
					<?php echo wp_kses( $this->description, Simple_Utils::get_plugin_allowed_kses_html() ); ?>
				</div>
			<?php endif; ?>

			<?php $this->display_internal_setting(); ?>

		</div>
		<?php
	}

	/**
	 * Display the main setting controller.
	 *
	 * @return void
	 */
	abstract protected function display_internal_setting();

	/**
	 * Get the HTML classes for the main element.
	 *
	 * This is marked as private because it should be not used on child classes.
	 *
	 * @return string The returned classes are unescaped.
	 */
	private function get_main_html_element_class_name() {
		$class_name  = '';
		$class_name .= static::ADDITIONAL_BLOCK_CLASS_NAME;

		$additional_element_class_name = $this->get_bem_class();
		if ( ! empty( $additional_element_class_name ) ) {
			$class_name .= ' ' . $additional_element_class_name;
		}

		if ( $this->is_hidden ) {
			$class_name .= ' ' . static::SETTING_IS_HIDDEN_CLASS_NAME;
		}

		return $class_name;
	}

	/**
	 * Display the HTML input attributes. It will have a space before if
	 * attributes are present.
	 *
	 * @return void
	 */
	protected function display_input_attributes() {
		$output_string = $this->create_additional_attributes( $this->input_attr );
		echo $output_string; // phpcs:ignore
	}

	/**
	 * Creates the HTML element additional attributes inserted.
	 *
	 * It will have a space before if necessary. The returned string is escaped.
	 *
	 * @param array $attrs
	 * @return string The returned string is escaped.
	 */
	protected function create_additional_attributes( $attrs ) {
		$output_string = '';

		foreach ( $attrs as $name => $value ) {
			$output_string .= ' ' . $name . '="' . esc_attr( $value ) . '"';
		}

		return $output_string;
	}

	/**
	 * Return the HTML wrapper id of a setting.
	 *
	 * Marked as private because it is used on abstract class function, and
	 * should not be used on concrete classes.
	 *
	 * @return string
	 */
	private function get_setting_wrapper_attr_id() {
		return 'twrpb-general-settings__' . $this->name . '-wrapper';
	}

	/**
	 * Return the HTML id of a setting.
	 *
	 * @param string $option_value If there are multiple id's separate them by an additional value.
	 * @return string The string is unescaped.
	 */
	protected function get_settings_attr_id( $option_value = '' ) {
		$id = 'twrpb-general-settings__' . $this->name . '-setting';

		if ( '' !== $option_value ) {
			$id = $id . '-' . $option_value;
		}

		return $id;
	}
}

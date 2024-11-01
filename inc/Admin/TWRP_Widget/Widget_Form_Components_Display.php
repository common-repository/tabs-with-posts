<?php

namespace TWRP\Admin\TWRP_Widget;

use TWRP\Article_Block\Component\Artblock_Component;
use TWRP\Utils\Widget_Utils;

/**
 * Class used to display the tabs for each component.
 */
class Widget_Form_Components_Display {

	/**
	 * The widget id to of the form.
	 *
	 * @var int
	 */
	protected $widget_id;

	/**
	 * The query id to of the form.
	 *
	 * @var int
	 */
	protected $query_id;

	/**
	 * Holds the array of components needed to create settings for.
	 *
	 * @var array<Artblock_Component> $components
	 */
	protected $components;

	/**
	 * Class constructor.
	 *
	 * @param int $widget_id
	 * @param int $query_id
	 * @param array<Artblock_Component> $components
	 */
	public function __construct( $widget_id, $query_id, $components ) {
		$this->widget_id  = $widget_id;
		$this->query_id   = $query_id;
		$this->components = $components;
	}

	/**
	 * Display the components settings in the widget.
	 *
	 * @return void
	 */
	public function display_components() {
		?>
		<div class="twrpb-widget-components">
			<ul class="twrpb-widget-components__tab-buttons">
				<?php foreach ( $this->components as $component ) : ?>
					<li class="twrpb-widget-components__btn-wrapper">
						<a class="twrpb-widget-components__btn" href="<?php echo esc_attr( '#' . $this->get_html_id_attr( $component ) ); ?>">
						<?php echo esc_html( $component->get_component_title() ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="twrpb-widget-components__components">
				<?php foreach ( $this->components as $component ) : ?>
					<div id="<?php echo esc_attr( $this->get_html_id_attr( $component ) ); ?>" class="twrpb-widget-components__component-wrapper">
						<?php $this->display_component_settings( $component ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display the component setting controls, in the widget settings manager.
	 *
	 * @param Artblock_Component $component
	 * @return void
	 */
	public function display_component_settings( $component ) {
		$component_setting_classes = $component->get_component_setting_classes();
		$settings                  = $component->get_settings();

		$prefix_name = $this->get_component_prefix_name( $component );
		$prefix_id   = $this->get_component_prefix_id( $component );

		foreach ( $component_setting_classes as $component_setting_class ) {
			$value = null;
			if ( isset( $settings[ $component_setting_class->get_key_name() ] ) ) {
				$value = $settings[ $component_setting_class->get_key_name() ];
			}

			$component_setting_class->display_setting( $prefix_id, $prefix_name, $value );
		}
	}

	/**
	 * Create and return the id for an input of the component.
	 *
	 * @param Artblock_Component $component
	 * @return string
	 */
	protected function get_html_id_attr( $component ) {
		return 'twrpb-widget-components__' . $this->widget_id . '-' . $this->query_id . '-' . $component->get_component_name();
	}

	/**
	 * Get the component prefix name for a setting.
	 *
	 * @param Artblock_Component $component
	 * @return string
	 */
	protected function get_component_prefix_name( $component ) {
		return Widget_Utils::get_field_name( $this->widget_id, $this->query_id, $component->get_component_name() );
	}

	/**
	 * Get the component prefix id of a setting.
	 *
	 * @param Artblock_Component $component
	 * @return string
	 */
	protected function get_component_prefix_id( $component ) {
		return Widget_Utils::get_field_id( $this->widget_id, $this->query_id, $component->get_component_name() );
	}

}

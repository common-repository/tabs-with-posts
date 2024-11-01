<?php

namespace TWRP\Utils\Helper_Trait;

/**
 * This trait can be used in a class that outputs HTML to easily make all html
 * "class" attributes to have uniform class naming.
 *
 * Usage:
 * 1. Use the class in a parent class and override the abstract method to return
 * the base class.
 * 2. Use function bem_class to return the base class for the outmost parent,
 * and with parameters to return for child elements.
 * 3. These methods are protected, if you want to get a HTML class name outside
 * the class, create a new public method that will call these functions.
 *
 * Ex:
 * bem_class() -> Outputs: base_class.
 * bem_class( 'child_elem' ) -> Outputs: base_class__child_elem.
 * bem_class( '', 'modifier' ) -> Outputs: base_class--modifier.
 * bem_class( 'child_elem', 'theme' ) -> Out: base_class__child_elem--theme.
 */
trait BEM_Class_Naming_Trait {

	/**
	 * Echo the HTML class name for an element. The class is a bem class, with
	 * no parameters given will return the BEM block element. Add additional
	 * element/modifier class.
	 *
	 * For more info about BEM, search BEM on a search engine.
	 *
	 * @param string $bem_element The element part of the class. Optional.
	 * @param string $bem_modifier The modifier part of the class. Optional.
	 * @return void
	 */
	protected function bem_class( $bem_element = '', $bem_modifier = '' ) {
		echo esc_attr( $this->get_bem_class( $bem_element, $bem_modifier ) );
	}

	/**
	 * Get a HTML class name for an element. The class is a bem class, with no
	 * parameters given will return the BEM block element. Add additional
	 * element/modifier class.
	 *
	 * For more info about BEM, search BEM on a search engine.
	 *
	 * @param string $bem_element The element part of the class. Optional.
	 * @param string $bem_modifier The modifier part of the class. Optional.
	 * @return string The HTML element class, unescaped.
	 */
	protected function get_bem_class( $bem_element = '', $bem_modifier = '' ) {
		$class = $this->get_bem_base_class();

		if ( ! empty( $bem_element ) ) {
			$class = $class . '__' . $bem_element;
		}

		if ( ! empty( $bem_modifier ) ) {
			$class = $class . '--' . $bem_modifier;
		}

		return $class;
	}

	/**
	 * Get an additional HTML element class, for this specific setting.
	 *
	 * @return string
	 */
	abstract protected function get_bem_base_class();
}

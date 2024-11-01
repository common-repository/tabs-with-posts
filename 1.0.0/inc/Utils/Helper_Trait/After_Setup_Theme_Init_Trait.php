<?php

namespace TWRP\Utils\Helper_Trait;

/**
 * Trait used to mark a class that will self initialize.
 *
 * The class that use this trait can override the method to call another hooks.
 * The method will get called at "after_setup_theme" action, where you can add
 * other WP hooks(that must execute after). This hook usually executes very
 * early.
 */
trait After_Setup_Theme_Init_Trait {

	/**
	 * Called before anything else, to initialize actions and filters.
	 *
	 * Always called at 'after_setup_theme' action. Other things added here should be
	 * additionally checked, for example by admin hooks, or whether or not to be
	 * included in special pages, ...etc. You can call any other hooks that run
	 * after the 'after_setup_theme' action.
	 *
	 * This function should not depend on whether or not other init functions
	 * from other classes have been executed.
	 *
	 * @return void
	 *
	 * @phan-suppress PhanEmptyPublicMethod
	 */
	public static function after_setup_theme_init() {
		// Do nothing.
	}
}

<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class used to display the translation documentation.
 */
class Translations_Docs {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the translation documentation.
	 *
	 * @return void
	 */
	public function display() {
		?>
			<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'Translations', 'backend documentation', 'tabs-with-posts' ); ?></h2>
			<p>
				<?php echo esc_html_x( 'You can either translate this plugin by changing directly the localization strings, via a plugin like "Loco Translate", or use the options to translate the strings. By translating the localization strings(and not changing the options), you can have multiple languages on a website.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
			<p>
				<?php echo esc_html_x( 'You should never translate the files in the plugin directory because these will be deleted when the plugin is updated.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

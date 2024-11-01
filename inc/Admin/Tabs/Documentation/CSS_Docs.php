<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class used to display the CSS documentation.
 */
class CSS_Docs {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the CSS documentation.
	 *
	 * @return void
	 */
	public function display() {
		?>
			<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'CSS', 'backend documentation', 'tabs-with-posts' ); ?></h2>
			<p>
				<?php echo esc_html_x( 'The one thing that I want to mention about the CSS is that all the CSS is enclosed into body:not(#twrpS) selectors, usually following by the class that the style apply. The meaning of this selector is to overwrite theme CSS like ".widget .item a" in a simple manner(an id tag overrides all classes tags), while not using !important tags.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
			<p>
				<?php echo esc_html_x( 'When changing the CSS, try to change variables first. CSS variables from this plugin begin with "--twrp...".', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

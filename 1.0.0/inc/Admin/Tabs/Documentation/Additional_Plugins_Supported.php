<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class used to display the additional plugins supported documentation.
 */
class Additional_Plugins_Supported {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the additional plugins supported documentation.
	 *
	 * @return void
	 */
	public function display() {
		?>
			<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'Additional Plugins Support & Info', 'backend documentation', 'tabs-with-posts' ); ?></h2>

			<p><?php echo esc_html_x( 'This plugin also supports the "Yoast SEO" plugin to retrieve the main category and to display it.', 'backend documentation', 'tabs-with-posts' ); ?></p>

			<p>
				<?php echo esc_html_x( 'This widget supports all page cache plugins.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_html_x( 'This plugin supports all multi-language plugins that can translate each widget individually.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_html_x( 'This plugin does NOT support Likes/Dislikes of "WP-PostRatings" Rating plugin.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

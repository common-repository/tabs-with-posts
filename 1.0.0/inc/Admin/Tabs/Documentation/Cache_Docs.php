<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class used to display the cache documentation.
 */
class Cache_Docs {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the cache documentation.
	 *
	 * @return void
	 */
	public function display() {
		?>
		<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'Cache', 'backend, documentation', 'tabs-with-posts' ); ?></h2>

		<p>
			<?php echo esc_html_x( 'To make the widgets faster, this plugin uses a database cache to not generate the widget every time a page is loaded.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The cache implementation is almost a must since the interrogation of the database is expensive, and also the additional plugins for views/ratings interrogate the database again for every post.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'A widget with 4 tabs, will be generated in 500 milliseconds average if the cache is off, if the setting is on it will take only 3ms(depending on database connection) average. According to Google, 53% of mobile users leave the page if it takes more than 3 seconds to load.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The plugin cache is highly recommended to be ON all the time, even if you use another external plugin to cache pages. If you use another plugin to cache pages, you must set the option to load the widget via AJAX.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The cache is generated in a background async process, so no user will feel any page load impact, since the widget is not generated when the page is loaded.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The cache will be automatically refreshed at an interval of time(to refresh views and rating), a setting that the user can change.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'If you don\'t see views/ratings change immediately, don\'t panic and be patient, the widget will be refreshed when the next visitor passes the minutes mark.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The widget will also force a refresh at some specific events, these include: any post added/updated/removed, any term added/updated/removed, any plugin activated/deactivated, WordPress Updates, ... etc.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<p>
			<?php echo esc_html_x( 'The database table with the cache will be automatically removed when the plugin is deleted.', 'backend, documentation', 'tabs-with-posts' ); ?>
		</p>

		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

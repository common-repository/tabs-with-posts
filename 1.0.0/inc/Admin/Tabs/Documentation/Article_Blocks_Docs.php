<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class used to display the article blocks documentation.
 */
class Article_Blocks_Docs {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the article blocks documentation.
	 *
	 * @return void
	 */
	public function display() {
		?>
			<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'Article Blocks(Styles)', 'backend documentation', 'tabs-with-posts' ); ?></h2>

			<p>
				<?php echo esc_attr_x( 'The article blocks, or styles, are a way to display a post.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'Each tab can have a different article block, but for consistency, it\'s good to make them all look the same.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'Each article block has a number of spots where a post meta could be inserted, and a meta could be inserted in any of these spots. Using this, you can create a unique way to display a post, with the most important information first.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'Just be careful to not overdo it, by putting a lot of meta and style them too hard.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'Another tip which I suggest is this: There are 2 types of meta, long ones: author, date, category. and short ones: views, comments, rating. Try to not use 3 or 2(depending on styles) long meta one after another, when you might not have enough space. By combining one or two long styles, you make the long ones not shrink when they don\'t have space.', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'All the thumbnails of the styles are loading lazy(only if in view).', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>

			<p>
				<?php echo esc_attr_x( 'You can overwrite an article block(style) template in your theme/child-theme by creating a file "twrp-{style-name}.php" or a file in a folder "twrp-templates/style-name.php" where the style-name is the name of the style(look on this plugin folder for example).', 'backend, documentation', 'tabs-with-posts' ); ?>
			</p>
		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

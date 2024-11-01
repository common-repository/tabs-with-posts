<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Icons\Icon_Factory;
use TWRP\Icons\Icon;
use TWRP\Icons\Rating_Icon_Pack;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;

/**
 * Class that is used to display Icons Documentation. Mostly used as a class for
 * the separation of the content between files.
 */
class Icons_Documentation {

	use BEM_Class_Naming_Trait;

	/**
	 * Display the icons documentation. In this documentation there is also a
	 * list with all available icons.
	 *
	 * @return void
	 */
	public function display_icon_documentation() {
		?>
		<h2 class="<?php $this->bem_class( 'title-section' ); ?>"><?php echo esc_html_x( 'Icons', 'backend documentation', 'tabs-with-posts' ); ?></h2>

		<p>
		<?php
		echo esc_html_x(
			'This plugin comes with a lot of icons possibilities. When selecting a plugin, you will see the brand of the plugin, the icon name and the style(filled, outlined, ... etc). If there is a brand that is not included in this plugin, you can send a request to add that icon pack.',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p>
		<?php
		echo esc_html_x(
			'The first thing when selecting icons you must consider(In my opinion) is to look exactly(or at least similar) with other theme/plugins icons. You can select icons from different brands, the icons are made to be aligned perfectly with each other.',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p>
		<?php
		echo esc_html_x(
			'In the frontend of the website, only the selected icons are included, making all the icons code very small(1-2kb GZipped).',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p class="<?php $this->bem_class( 'developer-text' ); ?>">
		<?php
		echo esc_html_x(
			'Because the code is in svg format, to use the icons with an ID reference, the svg icons are included in a div, prepended to the "body" HTML element.',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p>
		<?php
		echo esc_html_x(
			'The icons can be included either inline, or via a file. The recommended way(and default) is to include them inline, because the page will load faster, and also they are included only if the tabs are displayed. The option to include them via a file is to let the browser cache them, to not be downloaded every time, but is not worth, since they are very small in size, and an additional request will rather slow the page down(trust me).',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p class="<?php $this->bem_class( 'problem-text' ); ?>">
		<?php
		echo esc_html_x(
			'If an icon is not displayed, go to "General Settings" tab, change the setting of an icon, and save the settings. The icons are generated when the save button is pressed and at least an icon setting is changed(you can revert back to the icon after), or when the plugin is activated.',
			'backend documentation',
			'tabs-with-posts'
		);
		?>
		</p>

		<p>
			<?php echo esc_html_x( 'Check the spoilers bellow to see all available icons:', 'backend documentation', 'tabs-with-posts' ); ?>
		</p>
		<?php
		$this->display_all_icons_in_a_spoiler();
	}

	/**
	 * For each icon category, display a button to show/hide the spoiler, and a
	 * spoiler will all icons in that category.
	 *
	 * @return void
	 */
	protected function display_all_icons_in_a_spoiler() {
		$title_and_icons = $this->get_title_and_icons();

		?>
		<div id="<?php $this->bem_class( 'all-icons-reference' ); ?>" class="<?php $this->bem_class( 'icons-spoiler-wrapper' ); ?>">
			<h3 class="<?php $this->bem_class( 'title-sub-section' ); ?>"><?php echo esc_html_x( 'All icons reference', 'backend', 'tabs-with-posts' ); ?></h3>
			<?php foreach ( $title_and_icons as $category => $title_and_icon ) : ?>
			<div class="<?php $this->bem_class( 'icon-spoiler-category' ); ?>">
				<button class="<?php $this->bem_class( 'icon-spoiler-btn' ); ?> button">
					<?php /* translators: %s: icon category. Ex: "Author Icons" or "Date Icons"  */ ?>
					<?php echo esc_html( sprintf( _x( 'Toggle "%s" Spoiler', 'backend', 'tabs-with-posts' ), $title_and_icon['title'] ) ); ?>
				</button>

				<?php $this->display_icon_category_spoiler( $title_and_icon['icons'], $category ); ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Display the spoiler with the icons.
	 *
	 * @param array $icons
	 * @param string $additional_class_modifier
	 * @return void
	 */
	protected function display_icon_category_spoiler( $icons, $additional_class_modifier ) {
		$icons = Icon::nest_icons_by_brands( $icons );
		?>

		<div class="<?php $this->bem_class( 'spoiler' ); ?> <?php $this->bem_class( 'spoiler', $additional_class_modifier ); ?> twrpb-hidden">
		<?php foreach ( $icons as $brand => $brand_icons ) : ?>
			<div class="<?php $this->bem_class( 'spoiler-icon-group' ); ?>">
				<h4 class="<?php $this->bem_class( 'icons-brand-title' ); ?>">
					<?php echo esc_html( $brand ); ?>
				</h4>

				<?php foreach ( $brand_icons as $icon ) : ?>
					<div class="<?php $this->bem_class( 'icon-presentation' ); ?>">
						<span class="<?php $this->bem_class( 'icon-wrapper' ); ?>">
							<?php
							if ( $icon instanceof Rating_Icon_Pack ) {
								$rating_icon = $icon->get_filled_icon();
								$rating_icon->display();
								$rating_icon = $icon->get_half_filled_icon();
								$rating_icon->display();
								$rating_icon = $icon->get_empty_icon();
								$rating_icon->display();
							} else {
								$icon->display();
							}
							?>
						</span>
						<span class="<?php $this->bem_class( 'icon-description' ); ?>">
							<?php
							if ( $icon instanceof Rating_Icon_Pack ) {
								echo esc_html( $icon->get_option_pack_description() );
							} else {
								echo esc_html( $icon->get_option_icon_description() );
							}
							?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Returns an array containing the title and the icons to be displayed with
	 * that title.
	 *
	 * @return array<string,array{title:string,icons:array}>
	 */
	protected function get_title_and_icons() {
		$title_and_icons = array(
			'author'           => array(
				'title' => _x( 'Author Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_user_icons(),
			),

			'date'             => array(
				'title' => _x( 'Date Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_date_icons(),
			),

			'category'         => array(
				'title' => _x( 'Category Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_category_icons(),
			),

			'comment'          => array(
				'title' => _x( 'Comment Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_comment_icons(),
			),

			'comment_disabled' => array(
				'title' => _x( 'Comment Disabled Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_comment_disabled_icons(),
			),

			'views'            => array(
				'title' => _x( 'Views Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_views_icons(),
			),

			'rating_packs'     => array(
				'title' => _x( 'Rating Icons', 'backend documentation', 'tabs-with-posts' ),
				'icons' => Icon_Factory::get_rating_packs(),
			),
		);

		return $title_and_icons;
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}

}

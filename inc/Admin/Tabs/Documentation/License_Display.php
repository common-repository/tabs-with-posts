<?php

namespace TWRP\Admin\Tabs\Documentation;

use TWRP\Admin\Tabs\Documentation_Tab;
use TWRP\Utils\Helper_Trait\BEM_Class_Naming_Trait;
use TWRP\Utils\Simple_Utils;

/**
 * Class that is used to display licenses of the external works, and the license
 * of this plugin.
 */
class License_Display {

	use BEM_Class_Naming_Trait;

	/**
	 * Get an array, where each value is an array with the license attributions.
	 *
	 * @return array<string,array{brand:string,title:string,license_url:string,license_link_description:string,description:string}>
	 */
	protected function get_external_licenses_settings() {

		$mit_license_url        = 'https://opensource.org/licenses/MIT';
		$apache_v2_license_url  = 'https://www.apache.org/licenses/LICENSE-2.0';
		$gnu_v3_license_url     = 'https://www.gnu.org/licenses/gpl-3.0.en.html';
		$iconmonstr_license_url = 'https://iconmonstr.com/license/';
		$cc_by_sa_license_url   = 'https://creativecommons.org/licenses/by-sa/2.0/';
		$cc_zero_license_url    = '';

		$mit_license_text        = _x( 'MIT License', 'backend, documentation', 'tabs-with-posts' );
		$apache_v2_license_text  = _x( 'Apache License Version 2.0', 'backend, documentation', 'tabs-with-posts' );
		$gnu_v3_license_text     = _x( 'GNU General Public License Version 3', 'backend, documentation', 'tabs-with-posts' );
		$iconmonstr_license_text = _x( 'IconMonstr License', 'backend, documentation', 'tabs-with-posts' );
		$cc_by_sa_license_text   = _x( 'Attribution-ShareAlike 2.0 Generic (CC BY-SA 2.0) License', 'backend, documentation', 'tabs-with-posts' );
		$cc_zero_license_text    = _x( 'Creative Commons Zero v1.0 Universal', 'backend, documentation', 'tabs-with-posts' );

		/* translators: %1$s: icons brand name, %2$s: license name. */
		$icons_license_description = _x( '%1$s Icons are published under "%2$s", which grant the permission to be included in this plugin. Some icons may be modified in scale(increased to the margin of svg view box) and alignment(centered in svg view box), to be uniform displayed with other icons.', 'backend, documentation', 'tabs-with-posts' );

		$external_licenses = array(
			'fontawesome'  => array(
				'brand'                    => 'FontAwesome',
				'title'                    => _x( 'FontAwesome Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'FontAwesome', $mit_license_text ),
			),

			'google'       => array(
				'brand'                    => 'Google',
				'title'                    => _x( 'Google Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $apache_v2_license_url,
				'license_link_description' => $apache_v2_license_text,
				'description'              => sprintf( $icons_license_description, 'Google', $apache_v2_license_text ),
			),

			'dashicons'    => array(
				'brand'                    => 'Dashicons',
				'title'                    => 'Dashicons',
				'license_url'              => $gnu_v3_license_url,
				'license_link_description' => $gnu_v3_license_text,
				'description'              => sprintf( $icons_license_description, 'Dashicons', $gnu_v3_license_text ),
			),

			'foundation'   => array(
				'brand'                    => 'Foundation',
				'title'                    => _x( 'Foundation Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'Foundation', $mit_license_text ),
			),

			'ionicons'     => array(
				'brand'                    => 'Ionicons',
				'title'                    => 'Ionicons',
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'Ionicons', $mit_license_text ),
			),

			'iconmonstr'   => array(
				'brand'                    => 'IconMonstr',
				'title'                    => 'IconMonstr',
				'license_url'              => $iconmonstr_license_url,
				'license_link_description' => $iconmonstr_license_text,
				'description'              => sprintf( $icons_license_description, 'IconMonstr', $iconmonstr_license_text ),
			),

			'captain-icon' => array(
				'brand'                    => 'Captain Icons',
				'title'                    => _x( 'Captain Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $cc_by_sa_license_url,
				'license_link_description' => $cc_by_sa_license_text,
				'description'              => sprintf( $icons_license_description, 'Captain', $cc_by_sa_license_text ),
			),

			'feather'      => array(
				'brand'                    => 'Feather',
				'title'                    => _x( 'Feather Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'Feather', $mit_license_text ),
			),

			'jamicons'     => array(
				'brand'                    => 'Jam Icons',
				'title'                    => _x( 'Jam Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'Jam', $mit_license_text ),
			),

			'linea'        => array(
				'brand'                    => 'Linea Icons',
				'title'                    => _x( 'Linea Icons', 'backend, documentation', 'tabs-with-posts' ),
				'license_url'              => $cc_zero_license_url,
				'license_link_description' => $cc_zero_license_text,
				'description'              => sprintf( $icons_license_description, 'Linea', $cc_zero_license_text ),
			),

			'octicons'     => array(
				'brand'                    => 'Octicons',
				'title'                    => 'Octicons',
				'license_url'              => $mit_license_url,
				'license_link_description' => $mit_license_text,
				'description'              => sprintf( $icons_license_description, 'Octicons', $mit_license_text ),
			),

			'typicons'     => array(
				'brand'                    => 'Typicons',
				'title'                    => 'Typicons',
				'license_url'              => $cc_by_sa_license_url,
				'license_link_description' => $cc_by_sa_license_text,
				'description'              => sprintf( $icons_license_description, 'Typicons', $cc_by_sa_license_text ),
			),

		);

		return $external_licenses;
	}

	/**
	 * Display a list with all external programs used, and their licenses.
	 *
	 * @return void
	 */
	public function display_external_licenses() {
		$licenses = $this->get_external_licenses_settings();

		?>
		<div class="<?php $this->bem_class( 'licenses' ); ?>">
			<div class="<?php $this->bem_class( 'licenses-title-wrapper' ); ?>">
				<h2 class="<?php $this->bem_class( 'licenses-title' ); ?>"><?php echo esc_html_x( 'Licenses and external programs used', 'backend, documentation', 'tabs-with-posts' ); ?></h2>
			</div>

			<div class="<?php $this->bem_class( 'licenses-list' ); ?>">
				<?php
				foreach ( $licenses as $license ) {
					$this->display_external_license( $license );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display an external license info.
	 *
	 * @param array{brand:string,title:string,license_url:string,license_link_description:string,description:string} $license
	 * @return void
	 */
	protected function display_external_license( $license ) {
		if ( ! isset( $license['brand'], $license['title'], $license['license_url'], $license['license_link_description'], $license['description'] ) ) {
			return;
		}

		?>
			<div class="<?php $this->bem_class( 'license' ); ?>">
				<h3 class="<?php $this->bem_class( 'license-title' ); ?>">
					<?php echo esc_html( $license['title'] ); ?>
				</h3>

				<div class="<?php $this->bem_class( 'license-description' ); ?>">
					<?php echo wp_kses( $license['description'], Simple_Utils::get_plugin_allowed_kses_html() ); ?>
				</div>

				<div class="<?php $this->bem_class( 'license-link-wrapper' ); ?>">
					<?php echo esc_html_x( 'License Link:', 'backend, documentation', 'tabs-with-posts' ); ?>
					<a href="<?php echo esc_url( $license['license_link_description'] ); ?>" class="<?php $this->bem_class( 'license-link' ); ?>">
						<?php echo esc_html( $license['license_link_description'] ); ?>
					</a>
				</div>
			</div>
		<?php
	}

	protected function get_bem_base_class() {
		$documentation_tab = new Documentation_Tab();
		return $documentation_tab->get_bem_base_class();
	}
}

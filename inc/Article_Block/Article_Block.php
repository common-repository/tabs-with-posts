<?php

namespace TWRP\Article_Block;

use TWRP\Admin\TWRP_Widget\Widget_Form_Components_Display;
use TWRP\Database\General_Options;
use TWRP\Plugins\Post_Views;
use TWRP\Plugins\Post_Rating;
use TWRP\Icons\Icon_Factory;
use TWRP\Icons\Icon;

use TWRP\Utils\Class_Retriever_Utils;
use TWRP\Utils\Directory_Utils;
use TWRP\Utils\Date_Utils;
use TWRP\Utils\Simple_Utils;
use TWRP\Utils\Helper_Interfaces\Class_Children_Order;
use TWRP\Utils\Frontend_Translation;

use TWRP\Article_Block\Component\Artblock_Component;
use TWRP\Article_Block\Settings\Artblock_Setting;

use WP_Post;
use RuntimeException;
use WP_Term;

/**
 * The abstract for an article block. By extending this class, a class can
 * be declared an article block.
 *
 * Definition: An article block is how a post can be displayed in the widget.
 * It can be displayed as a simple title, where a user can click on it(it can
 * also have some metadata like the author or the date), or it can be displayed
 * maybe as a title and alongside a thumbnail, like YouTube do. Or maybe we want
 * to display a custom WooCommerce product. The possibilities are very large.
 *
 * If you want to sanitize the widgets settings(usually you don't need because
 * they are sanitized before adding in database), call
 * sanitize_widget_settings() function externally.
 */
abstract class Article_Block implements Class_Children_Order, Article_Block_Info {

	/**
	 * Holds the widget id of these article blocks.
	 *
	 * @var int
	 */
	protected $widget_id;

	/**
	 * Holds the query id of these article blocks.
	 *
	 * @var int
	 */
	protected $query_id;

	/**
	 * Holds the query settings.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Hold into a variable the path of the file, to not calculate every time.
	 *
	 * @var string|false
	 */
	protected $cache_include_file = false;

	/**
	 * Get the widget Id this artblock is build for.
	 *
	 * @return int
	 */
	public function get_widget_id() {
		return $this->widget_id;
	}

	/**
	 * Get the query Id this artblock is build for.
	 *
	 * @return int
	 */
	public function get_query_id() {
		return $this->query_id;
	}

	/**
	 * Get the settings of this artblock.
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Construct the object instance.
	 *
	 * @param int $widget_id
	 * @param int $query_id
	 * @param array $settings
	 */
	final public function __construct( $widget_id, $query_id, $settings ) {
		$this->widget_id = $widget_id;
		$this->query_id  = $query_id;
		$this->settings  = $settings;
	}

	/**
	 * Echo the block class that each article block in the query should have.
	 *
	 * The string is escaped when echoed.
	 *
	 * @return void
	 */
	public function the_block_class() {
		echo esc_attr( $this->get_block_class() );
	}

	/**
	 * Get the block class that each article block in the query should have.
	 *
	 * @return string
	 */
	public function get_block_class() {
		return 'twrp-block--' . $this->widget_id . '-' . $this->query_id;
	}

	/**
	 * Create the block for each post inside of the array.
	 *
	 * @param array<WP_Post> $query_posts
	 * @param int $posts_per_page
	 * @return void
	 */
	public function display_blocks( $query_posts, $posts_per_page ) {
		global $post;
		$post_number = 0;

		foreach ( $query_posts as $query_post ) {
			if ( 0 !== $posts_per_page && ( 0 === ( $post_number % $posts_per_page ) ) && 0 !== $post_number ) {
				$this->display_show_more_button();
			}

			$post = $query_post; // phpcs:ignore -- We reset it.
			setup_postdata( $query_post );
			$this->include_template();

			$post_number++;
		}
	}

	/**
	 * Include the template that should be displayed in the frontend.
	 *
	 * @return void
	 * @psalm-suppress UnresolvableInclude
	 */
	public function include_template() {
		// Artblock is assigned to $this to be used in the template style.
		$artblock  = $this;
		$file_path = $this->get_template_path_found();

		if ( ! empty( $file_path ) ) {
			include $file_path;
		}
	}

	/**
	 * Display a button that will show more posts if they are displayed.
	 *
	 * @return void
	 */
	public function display_show_more_button() {
		?>
			<div class="twrp-show-more">
				<button class="twrp-show-more__btn" type="button"><?php echo esc_html( Frontend_Translation::get_translation( Frontend_Translation::SHOW_MORE_POSTS ) ); ?></button>
			</div>
		<?php
	}

	/**
	 * Get the full path to the template file of the style.
	 *
	 * @return string Empty string if not found
	 */
	protected function get_template_path_found() {
		if ( false !== $this->cache_include_file ) {
			return $this->cache_include_file;
		}

		$file_name         = static::get_file_name();
		$has_php_extension = strpos( $file_name, '.php', strlen( $file_name ) - 4 );
		if ( false === $has_php_extension ) {
			$file_name .= '.php';
		}

		// Either the child theme directory or only the parent theme.
		$stylesheet_directory = trailingslashit( get_stylesheet_directory() );

		// Search in child theme if enabled, else in parent theme.
		$file_path = $stylesheet_directory . 'twrp-templates/' . $file_name;
		if ( file_exists( $file_path ) ) {
			$this->cache_include_file = $file_path;
			return $file_path;
		}

		$file_path = $stylesheet_directory . 'twrp-' . $file_name;
		if ( file_exists( $file_path ) ) {
			$this->cache_include_file = $file_path;
			return $file_path;
		}

		// Search in parent theme.
		$parent_theme_directory = trailingslashit( get_template_directory() );

		$file_path = $parent_theme_directory . 'twrp-templates/' . $file_name;
		if ( file_exists( $file_path ) ) {
			$this->cache_include_file = $file_path;
			return $file_path;
		}

		$file_path = $parent_theme_directory . 'twrp-' . $file_name;
		if ( file_exists( $file_path ) ) {
			$this->cache_include_file = $file_path;
			return $file_path;
		}

		// Else, get the template from plugin directory.
		$plugin_path = Directory_Utils::get_template_directory_path() . $file_name;
		if ( file_exists( $plugin_path ) ) {
			$this->cache_include_file = $plugin_path;
			return $plugin_path;
		}

		$this->cache_include_file = '';
		return '';
	}

	/**
	 * Get the components that can be edited for this artblock.
	 *
	 * @return array<Artblock_Component>
	 */
	abstract public function get_components();

	/**
	 * Get an array of artblock class settings names.
	 *
	 * @return array<Artblock_Setting>
	 */
	abstract public function get_artblock_settings();

	/**
	 * Display the article block settings in the Widgets::form().
	 *
	 * @return void
	 */
	public function display_form_settings() {
		$this->display_artblock_meta_places();

		$settings = $this->get_artblock_settings();
		foreach ( $settings as $query_artblock_setting ) {
			$query_artblock_setting->display_setting();
		}

		// Display the components settings.
		$components = $this->get_components();

		if ( empty( $components ) ) {
			return;
		}

		?>
		<h5 class="twrpb-widget-form__query-description-title">
			<?php echo esc_html_x( 'Change component CSS:', 'backend', 'tabs-with-posts' ); ?>
		</h5>
		<?php

		$components_display = new Widget_Form_Components_Display( $this->widget_id, $this->query_id, $components );
		$components_display->display_components();
	}

	/**
	 * Display a tooltip an image with the artblock and where the meta are
	 * placed.
	 *
	 * @return void
	 */
	protected function display_artblock_meta_places() {
		$image_src = $this->get_widget_meta_locations_image_src();
		if ( empty( $image_src ) ) {
			return;
		}

		?>
		<div class="twrpb-widget-form__artblock-tooltip-info">
			<button class="twrpb-widget-form__artblock-tooltip-btn" type="button">
				<?php echo esc_html_x( 'Click to show/hide the meta locations', 'backend', 'tabs-with-posts' ); ?>
			</button>

			<div class="twrpb-widget-form__artblock-tooltip-content">
				<img class="twrpb-widget-form__artblock-tooltip-img" src="<?php echo esc_attr( $image_src ); ?>" alt="<?php echo esc_attr_x( 'Image showing where the meta are placed.', 'backend', 'tabs-with-posts' ); ?>">
			</div>
		</div>
		<?php
	}

	/**
	 * Get the image url for the meta location, to enhance user experience.
	 *
	 * @return string
	 */
	protected function get_widget_meta_locations_image_src() {
		$folder_path = Directory_Utils::get_artblock_meta_locations_images_directory_url();
		$class_names = explode( '\\', get_class( $this ) );
		$class_name  = array_pop( $class_names );

		return $folder_path . $class_name . '.jpg';
	}

	/**
	 * Sanitize the widget settings of this specific article block.
	 *
	 * @param bool $set_internal Whether or not to set the object settings to
	 * the one sanitized. Default to true.
	 * @return array The new array of settings.
	 */
	public function sanitize_widget_settings( $set_internal = true ) {
		$components         = $this->get_components();
		$sanitized_settings = array();

		foreach ( $components as $component ) {
			$sanitized_settings[ $component->get_component_name() ] = $component->sanitize_settings();
		}

		$query_settings = $this->get_artblock_settings();

		foreach ( $query_settings as $query_artblock_setting ) {
			$sanitized_settings[ $query_artblock_setting->get_setting_name() ] = $query_artblock_setting->sanitize_setting();
		}

		if ( $set_internal ) {
			$this->settings = $sanitized_settings;
		}

		return $sanitized_settings;
	}


	/**
	 * Create and return the css of the component.
	 *
	 * @return string
	 */
	public function get_css() {
		$components = $this->get_components();

		$css = '';
		foreach ( $components as $component ) {
			$css .= $component->get_css();
		}

		return $css;
	}

	#region -- Verify if meta post information is displayed

	/**
	 * Whether or not the thumbnail must be displayed in the article.
	 *
	 * @return bool
	 */
	public function thumbnail_is_displayed() {
		return isset( $this->settings['display_post_thumbnail'] ) && $this->settings['display_post_thumbnail'];
	}

	/**
	 * Whether or not the thumbnail exist and must be displayed in the article.
	 *
	 * @return bool
	 */
	public function thumbnail_exist_and_displayed() {
		$display_thumbnail = isset( $this->settings['display_post_thumbnail'] ) && $this->settings['display_post_thumbnail'];
		if ( false === $display_thumbnail ) {
			return false;
		}

		return has_post_thumbnail();
	}

	/**
	 * Whether or not the excerpt must be displayed in the article.
	 *
	 * @return bool
	 */
	public function excerpt_is_displayed() {
		return isset( $this->settings['display_post_excerpt'] ) && $this->settings['display_post_excerpt'];
	}

	/**
	 * Whether or not the excerpt is displayed in the article and also is not
	 * empty.
	 *
	 * @return bool
	 */
	public function excerpt_exist_and_is_displayed() {
		$is_displayed = $this->excerpt_is_displayed();
		if ( false === $is_displayed ) {
			return false;
		}

		return ( ! empty( trim( $this->get_the_excerpt() ) ) );
	}

	/**
	 * Whether or not the author must be shown in the article.
	 *
	 * @return bool
	 */
	public function is_author_displayed() {
		return isset( $this->settings['display_author'] ) && $this->settings['display_author'];
	}

	/**
	 * Whether or not the date must be shown in the article.
	 *
	 * @return bool
	 */
	public function is_date_displayed() {
		return isset( $this->settings['display_date'] ) && $this->settings['display_date'];
	}

	/**
	 * Whether or not the views must be shown in the article.
	 *
	 * @return bool
	 */
	public function are_views_displayed() {
		$plugin_is_installed = Post_Views::get_plugin_to_use();
		if ( false !== $plugin_is_installed ) {
			$plugin_is_installed = true;
		}

		return isset( $this->settings['display_views'] ) && $this->settings['display_views'] && $plugin_is_installed;
	}

	/**
	 * Whether or not the rating must be shown in the article.
	 *
	 * @return bool
	 */
	public function is_rating_displayed() {
		$plugin_is_installed = Post_Rating::get_plugin_to_use();
		if ( false !== $plugin_is_installed ) {
			$plugin_is_installed = true;
		}

		return isset( $this->settings['display_rating'] ) && $this->settings['display_rating'] && $plugin_is_installed;
	}

	/**
	 * Whether or not the rating must be shown in the article.
	 *
	 * Before using this method, is_rating_displayed() should be used first.
	 *
	 * @return bool
	 */
	public function is_rating_count_displayed() {
		return isset( $this->settings['display_rating_count'] ) && $this->settings['display_rating_count'];
	}

	/**
	 * Whether or not the comments must be shown in the article.
	 *
	 * @return bool
	 */
	public function are_comments_displayed() {
		return isset( $this->settings['display_comments'] ) && $this->settings['display_comments'];
	}

	/**
	 * Whether or not the main category must be shown in the article.
	 *
	 * @return bool
	 */
	public function is_main_category_displayed() {
		return isset( $this->settings['display_main_category'] ) && $this->settings['display_main_category'];
	}

	#endregion -- Verify if meta post information is displayed

	#region -- Display Post Meta

	/**
	 * Display the permalink of the post, if the post can be viewed by the current
	 * user.
	 *
	 * @param WP_Post|int|null $post Defaults to global post.
	 * @return void
	 */
	public function the_permalink( $post = null ) {
		echo esc_url( $this->get_the_permalink( $post ) );
	}

	/**
	 * Get the permalink of the post, if the post can be viewed by the current
	 * user.
	 *
	 * @param WP_Post|int|null $post Defaults to global post.
	 * @return string
	 */
	public function get_the_permalink( $post = null ) {
		$post_status = get_post_status( $post );

		if ( 'publish' === $post_status ) {
			$permalink = get_the_permalink( $post );

			if ( false === $permalink ) {
				$permalink = '#';
			}
			return $permalink;
		}

		return '#';
	}

	/**
	 * Display the title of the post.
	 *
	 * @param string $before HTML to display before the title.
	 * @param string $after HTML to display after the title.
	 * @param bool $echo
	 * @param WP_Post|int|null $post Defaults to global post.
	 * @return string
	 */
	public function the_title( $before = '', $after = '', $echo = true, $post = null ) {
		$title  = $before;
		$title .= $this->get_the_title( $post );
		$title .= $after;

		if ( $echo ) {
			echo $title; // phpcs:ignore WordPress.Security -- This is a feature.
		}

		return $title;
	}

	/**
	 * Get the title of the post.
	 *
	 * @param WP_Post|int|null $post Defaults to global post.
	 * @return string
	 */
	public function get_the_title( $post = null ) {
		$title = get_the_title( $post );

		if ( empty( trim( $title ) ) ) {
			$title = Frontend_Translation::get_translation( Frontend_Translation::POST_NO_TITLE );
		}

		return $title;
	}

	/**
	 * Display the post thumbnail, or an image that say there is no thumbnail.
	 *
	 * @param string $size The WordPress image size, defaults to 'medium'.
	 * @param array $attr A list of attributes to add to the image.
	 * @return void
	 */
	public function display_post_thumbnail( $size = 'medium', $attr = array() ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $size, $attr );
		} else {
			$this->display_no_thumbnail_image( $size, $attr );
		}
	}

	/**
	 * Display an image in case that the post has no thumbnail.
	 *
	 * @param string $size The WordPress image size, defaults to 'medium'.
	 * @param array $attr A list of attributes to add to the image.
	 * @return void
	 */
	public function display_no_thumbnail_image( $size = 'medium', $attr = array() ) {
		$thumbnail_image_src = self::get_custom_no_thumbnail_image_url( $size );
		$src_set             = self::get_custom_no_thumbnail_image_srcset( $size );

		if ( false === $thumbnail_image_src ) {
			$thumbnail_image_src = self::get_default_no_thumbnail_image_url( $size );
			$src_set             = '';
		} elseif ( false === $src_set ) {
			$src_set = '';
		}

		if ( ! empty( $src_set ) ) {
			$src_set = ' srcset="' . $src_set . '"';
		}

		$lazy_loading = ' loading="lazy"';
		if ( isset( $attr['loading'] ) && ! $attr['loading'] ) {
			$lazy_loading = '';
		}

		if ( ! isset( $attr['alt'] ) ) {
			$alt = Frontend_Translation::get_translation( Frontend_Translation::POST_NO_THUMBNAIL );
		} else {
			$alt = $attr['alt'];
		}

		unset( $attr['loading'] );
		unset( $attr['alt'] );

		$attr_html = '';
		foreach ( $attr as $name => $value ) {
			$attr_html .= " $name=" . '"' . esc_attr( $value ) . '"';
		}

		// phpcs:ignore WordPress.Security -- No XSS
		echo '<img src="' . esc_url( $thumbnail_image_src ) . '" alt="' . esc_attr( $alt ) . '"' . $lazy_loading . $attr_html . $src_set . '>';
	}

	/**
	 * Display the meta that needs to be displayed in that place defined by a number.
	 *
	 * @param int $number
	 * @return void
	 */
	public function display_meta( $number ) {
		$meta_displayed = $this->get_meta_displayed_name( $number );
		if ( false === $meta_displayed ) {
			return;
		}

		if ( 'author' === $meta_displayed ) {
			$this->display_author_icon();
			$this->display_the_author();
		} elseif ( 'date' === $meta_displayed ) {
			$this->display_date_icon();
			$this->display_the_date();
		} elseif ( 'views' === $meta_displayed ) {
			$this->display_views_icon();
			$this->display_the_views();
		} elseif ( 'rating' === $meta_displayed || 'rating_and_count' === $meta_displayed ) {
			$this->display_rating_icon();
			if ( 'rating_and_count' === $meta_displayed ) {
				$this->display_rating( null, true );
			} else {
				$this->display_rating( null, false );
			}
		} elseif ( 'comments' === $meta_displayed ) {
			$this->display_comments_icon();
			$this->display_comments_number();
		} elseif ( 'category' === $meta_displayed ) {
			$this->display_category_icon();
			$this->display_the_main_category();
		}
	}

	/**
	 * Get what meta should be displayed in the space defined by the number.
	 *
	 * @param int $number
	 * @return string|false
	 */
	public function get_meta_displayed_name( $number ) {
		if ( ! ( $number > 0 ) ) {
			return false;
		}

		$key_name = 'display_meta_' . $number;

		if ( isset( $this->settings[ $key_name ] ) && 'none' !== $this->settings[ $key_name ] ) {
			$return_value = $this->settings[ $key_name ];
			if ( 'views' === $return_value && false === Post_Views::get_plugin_to_use() ) {
				$return_value = false;
			}

			if ( ( 'rating' === $return_value || 'rating_and_count' === $return_value ) && false === Post_Rating::get_plugin_to_use() ) {
				$return_value = false;
			}

			if ( is_string( $return_value ) ) {
				return $return_value;
			}
		}

		return false;
	}

	/**
	 * Whether or not one(or more) of the meta instances is displayed.
	 *
	 * @param array<int> $array_of_numbers
	 * @return bool
	 */
	public function one_or_more_meta_is_displayed( $array_of_numbers ) {
		foreach ( $array_of_numbers as $number ) {
			$return_value = $this->get_meta_displayed_name( $number );
			if ( false !== $return_value ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Display the author of the current post.
	 *
	 * @return void
	 */
	public function display_the_author() {
		$author = $this->get_the_author();
		if ( is_string( $author ) ) {
			echo esc_html( $author );
		}
	}

	/**
	 * Get the author of the current $post.
	 *
	 * @return string|null
	 */
	public function get_the_author() {
		$author = get_the_author();
		return $author;
	}


	/**
	 * Display the date of the current post.
	 *
	 * @param WP_Post|int|null $post The post, defaults to global post.
	 * @return void
	 */
	public function display_the_date( $post = null ) {
		$date = $this->get_the_date( $post );
		if ( is_string( $date ) ) {
			echo esc_html( $date );
		}
	}

	/**
	 * Get the date of the current post. The date retrieved will be formatted
	 * how it should be.
	 *
	 * @param WP_Post|int|null $post The post, defaults to global post.
	 * @return string|false False in case something was wrong.
	 */
	public function get_the_date( $post = null ) {
		$date_format = $this->get_date_format();
		if ( 'HUMAN_READABLE' === $date_format ) {
			$from = Date_Utils::get_post_timestamp( $post );
			$to   = date_timestamp_get( Date_Utils::current_datetime() );

			if ( false === $from || 0 === $to ) {
				$date_text = false;
			} else {
				// The default English translation is "%s ago".
				$date_text = sprintf( Frontend_Translation::get_translation( Frontend_Translation::HUMAN_RELATIVE_DATE ), human_time_diff( $from, $to ) );
			}
		} else {
			$date_text = get_the_time( $date_format, $post );
		}

		if ( is_int( $date_text ) ) {
			$date_text = (string) $date_text;
		}

		return $date_text;
	}


	/**
	 * Display the comments number. Will not display if comments are not open
	 * and there are no comments.
	 *
	 * @param WP_Post|int|null $post The post, defaults to global post.
	 * @return void
	 */
	public function display_comments_number( $post = null ) {
		$post = get_post( $post );

		if ( is_array( $post ) || is_null( $post ) ) { // Needed for static analysis checks.
			return;
		}

		if ( ( ! comments_open( $post ) ) && ( ! $this->get_the_comments_number() ) ) {
			return;
		}

		$num_comments = (int) $this->get_the_comments_number();
		echo esc_html( Simple_Utils::get_number_abbreviation( $num_comments ) );
	}

	/**
	 * Get the comments number for a post.
	 *
	 * @param WP_Post|int|null $post The post, defaults to global post.
	 * @return int|string A numeric string representing the number of posts, or 0.
	 */
	public function get_the_comments_number( $post = null ) {
		$post = get_post( $post );

		if ( is_array( $post ) || is_null( $post ) ) { // Needed for static analysis checks.
			return 0;
		}

		return get_comments_number( $post );
	}


	/**
	 * Display the views of the post.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return void
	 */
	public function display_the_views( $post = null ) {
		$views = $this->get_the_views( $post );
		if ( is_int( $views ) ) {
			echo esc_html( Simple_Utils::get_number_abbreviation( $views ) );
		} else {
			echo '0';
		}
	}

	/**
	 * Get the views of the post.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return int|false False if something went wrong and the views are not available.
	 */
	public function get_the_views( $post = null ) {
		return Post_Views::get_views( $post );
	}

	/**
	 * Display the average rating for a post and the rating count.
	 * If there is no rating, it displays 0.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @param bool $display_count WHether or not to also display the count.
	 * @return void
	 */
	public function display_rating( $post = null, $display_count = true ) {
		$avg_rating   = $this->get_the_average_rating( $post );
		$rating_count = null;

		if ( false === $avg_rating ) {
			$avg_rating   = 0;
			$rating_count = 0;
		}

		echo esc_html( number_format_i18n( $avg_rating, 1 ) );
		if ( 0 !== $avg_rating && 0 !== $rating_count && $display_count ) {
			$rating_count = $this->get_rating_count( $post );

			if ( false === $rating_count ) {
				$rating_count = 0;
			}

			$rating_count = (int) $rating_count;
			if ( 0 !== $rating_count ) {
				echo '<span class="twrp-rating-count">(';
				echo esc_html( Simple_Utils::get_number_abbreviation( $rating_count ) );
				echo ')</span>';
			}
		}
	}

	/**
	 * Get the average rating for a post.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return false|float|int
	 */
	public function get_the_average_rating( $post = null ) {
		return Post_Rating::get_rating( $post );
	}

	/**
	 * Get the rating count for a post.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return false|float|int
	 */
	public function get_rating_count( $post = null ) {
		return Post_Rating::get_rating_count( $post );
	}

	/**
	 * Display the main category name.
	 *
	 * @param WP_Post|int|null $post_or_post_id Defaults to global post.
	 * @return void
	 */
	public function display_the_main_category( $post_or_post_id = null ) {
		$name = $this->get_the_main_category_name( $post_or_post_id );
		if ( ! empty( $name ) ) {
			echo esc_html( $name );
		}
	}

	/**
	 * Get the main category name.
	 *
	 * @param WP_Post|int|null $post_or_post_id Defaults to global post.
	 * @return string Name of the category or empty string.
	 */
	public function get_the_main_category_name( $post_or_post_id = null ) {
		$object = $this->get_the_main_category_class( $post_or_post_id );
		if ( $object instanceof WP_Term ) {
			return $object->name;
		}

		return '';
	}

	/**
	 * Get the main category object.
	 *
	 * @param WP_Post|int|null $post_or_post_id Defaults to global post.
	 * @return null|WP_Term
	 */
	public function get_the_main_category_class( $post_or_post_id = null ) {
		global $post;

		if ( is_numeric( $post_or_post_id ) ) {
			$post_id = (int) $post_or_post_id;
		} elseif ( $post_or_post_id instanceof WP_Post ) {
			$post_id = $post_or_post_id->ID;
		} elseif ( $post instanceof WP_Post ) {
			$post_id = $post->ID;
		} else {
			return null;
		}

		$categories = Simple_Utils::get_post_primary_category( $post_id );
		if ( $categories['primary_category'] instanceof WP_Term ) {
			return $categories['primary_category'];
		}

		return null;
	}

	/**
	 * Display the excerpt of the post, with HTML tags stripped, and cut to desired
	 * length. The excerpt is also escaped before echoing.
	 *
	 * @param int $length
	 * @param WP_Post|int|null $post_or_post_id Defaults to global post.
	 * @return void
	 */
	public function display_the_excerpt( $length = 320, $post_or_post_id = null ) {
		$excerpt = $this->get_the_excerpt( $length, $post_or_post_id );
		echo esc_html( $excerpt );
	}

	/**
	 * Get the excerpt of the post, with HTML tags stripped, and cut to desired
	 * length.
	 *
	 * @param int $length
	 * @param WP_Post|int|null $post_or_post_id Defaults to global post.
	 * @return string
	 */
	public function get_the_excerpt( $length = 320, $post_or_post_id = null ) {
		$excerpt = get_the_excerpt( $post_or_post_id );
		$excerpt = wp_strip_all_tags( str_replace( '&nbsp;', ' ', $excerpt ) );

		$excerpt = apply_filters( 'twrp_get_excerpt_before_cut', $excerpt );
		$excerpt = Simple_Utils::cut_string_at_closest_length( $excerpt, $length, '&hellip;' );

		$excerpt = apply_filters( 'twrp_get_excerpt', $excerpt );
		return $excerpt;
	}

	#endregion -- Display Post Meta

	#region -- Display Icons Functions

	/**
	 * Include the HTML svg for the author icon.
	 *
	 * @return void
	 */
	public function display_author_icon() {
		echo $this->get_author_icon_html(); // phpcs:ignore -- No XSS.
	}

	/**
	 * Return the svg for the author icon.
	 *
	 * @return string The HTML is safe for output.
	 */
	public function get_author_icon_html() {
		try {
			$icon = Icon_Factory::get_icon( $this->get_selected_author_icon() );
			return $icon->get_html();
		} catch ( RuntimeException $e ) {
			return '';
		}
	}

	/**
	 * Get the Id of the selected author icon. Empty if nothing is set(usually
	 * should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_author_icon() {
		$option = General_Options::get_option( General_Options::AUTHOR_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}

		return $option;
	}

	/**
	 * Include the HTML svg for the date icon.
	 *
	 * @return void
	 */
	public function display_date_icon() {
		echo $this->get_date_icon_html(); // phpcs:ignore -- No XSS.
	}

	/**
	 * Return the svg for the date icon.
	 *
	 * @return string The HTML is safe for output.
	 */
	public function get_date_icon_html() {
		try {
			$icon = Icon_Factory::get_icon( $this->get_selected_date_icon() );
			return $icon->get_html();
		} catch ( RuntimeException $e ) {
			return '';
		}
	}

	/**
	 * Get the Id of the selected date icon. Empty if nothing is set(usually
	 * should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_date_icon() {
		$option = General_Options::get_option( General_Options::DATE_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}
		return $option;
	}

	/**
	 * Include the HTML svg for the views icon.
	 *
	 * @return void
	 */
	public function display_views_icon() {
		echo $this->get_views_icon_html(); // phpcs:ignore -- No XSS.
	}

	/**
	 * Return the svg for the views icon.
	 *
	 * @return string The HTML is safe for output.
	 */
	public function get_views_icon_html() {
		try {
			$icon = Icon_Factory::get_icon( $this->get_selected_views_icon() );
			return $icon->get_html();
		} catch ( RuntimeException $e ) {
			return '';
		}
	}

	/**
	 * Get the Id of the selected views icon. Empty if nothing is set(usually
	 * should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_views_icon() {
		$option = General_Options::get_option( General_Options::VIEWS_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}
		return $option;
	}

	/**
	 * Include the HTML svg for the rating icon.
	 *
	 * @param float|int|false $rating Calculate the icon to use by the rating. False means to use default half-filled.
	 * @param int|float|false $min The minimum of rating. False means that is determined if necessary.
	 * @param int|float|false $max The maximum of rating. False means that is determined if necessary.
	 * @return void
	 */
	public function display_rating_icon( $rating = false, $min = false, $max = false ) {
		echo $this->get_rating_icon_html( $rating, $min, $max ); // phpcs:ignore -- No XSS.
	}

	/**
	 * Return the svg for the rating icon.
	 *
	 * @param float|int|false $rating Calculate the icon to use by the rating. False means to use default half-filled.
	 * @param int|float|false $min The minimum of rating. False means that is determined if necessary.
	 * @param int|float|false $max The maximum of rating. False means that is determined if necessary.
	 * @return string
	 */
	public function get_rating_icon_html( $rating = false, $min = false, $max = false ) {
		$icon = $this->get_selected_rating_icon( $rating, $min, $max );
		if ( false === $icon ) {
			return '';
		}

		return $icon->get_html();
	}

	/**
	 * Get the Id of the selected views icon. Empty if nothing is set(usually
	 * should not be encounter).
	 *
	 * @param float|int|false $rating Calculate the icon to use by the rating. False means to use default half-filled.
	 * @param int|float|false $min The minimum of rating. False means that is determined if necessary.
	 * @param int|float|false $max The maximum of rating. False means that is determined if necessary.
	 * @return Icon|false
	 */
	protected function get_selected_rating_icon( $rating = false, $min = false, $max = false ) {
		$rating_pack_id = General_Options::get_option( General_Options::RATING_ICON_PACK );
		if ( ! is_string( $rating_pack_id ) ) {
			return false;
		}

		try {
			$rating_pack = Icon_Factory::get_rating_pack( $rating_pack_id );
		} catch ( RuntimeException $e ) {
			return false;
		}

		// todo: get different icon by rating?
		return $rating_pack->get_half_filled_icon();
	}

	/**
	 * Display the HTML svg icon
	 *
	 * If comments are disabled and the post has no comments, then the comments
	 * disable icon will be used. If the post has at least one comment or
	 * comments are open, the normal comments icon will be given.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return void
	 */
	public function display_comments_icon( $post = null ) {
		echo $this->get_comments_icon_html( $post ); // phpcs:ignore -- No XSS.
	}

	/**
	 * Get the HTML to display the svg icon
	 *
	 * If comments are disabled and the post has no comments, then the comments
	 * disable icon will be used. If the post has at least one comment or
	 * comments are open, the normal comments icon will be given.
	 *
	 * @param WP_Post|int|null $post Defaults to global $post.
	 * @return string
	 */
	public function get_comments_icon_html( $post = null ) {
		$post = get_post( $post );

		if ( null === $post || is_array( $post ) ) { // This is for static type checkers.
			return '';
		}

		$number_of_comments = (int) get_comments_number( $post );
		$comments_open      = comments_open( $post );
		$comments_icon      = '';

		if ( 0 === $number_of_comments && ( ! $comments_open ) ) {
			$comments_icon = $this->get_selected_disabled_comments_icon();
		} else {
			$comments_icon = $this->get_selected_comments_icon();
		}

		try {
			$icon = Icon_Factory::get_icon( $comments_icon );
			return $icon->get_html();
		} catch ( RuntimeException $e ) {
			return '';
		}
	}

	/**
	 * Get the Id of the selected comments icon. Empty if nothing is
	 * set(usually should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_comments_icon() {
		$option = General_Options::get_option( General_Options::COMMENTS_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}
		return $option;
	}

	/**
	 * Get the Id of the selected comments disabled icon. Empty if nothing is
	 * set(usually should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_disabled_comments_icon() {
		$option = General_Options::get_option( General_Options::COMMENTS_DISABLED_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}
		return $option;
	}

	/**
	 * Include the HTML svg for the category icon.
	 *
	 * @return void
	 */
	public function display_category_icon() {
		echo $this->get_category_icon_html(); // phpcs:ignore -- No XSS.
	}

	/**
	 * Return the svg for the category icon.
	 *
	 * @return string The HTML is safe for output.
	 */
	public function get_category_icon_html() {
		try {
			$icon = Icon_Factory::get_icon( $this->get_selected_category_icon() );
			return $icon->get_html();
		} catch ( RuntimeException $e ) {
			return '';
		}
	}

	/**
	 * Get the Id of the selected category icon. Empty if nothing is set(usually
	 * should not be encounter).
	 *
	 * @return string
	 */
	protected function get_selected_category_icon() {
		$option = General_Options::get_option( General_Options::CATEGORY_ICON );

		if ( ! is_string( $option ) ) {
			return '';
		}
		return $option;
	}

	#endregion -- Icons

	#region -- Helper methods

	/**
	 * Get the date format to display, or to display in the human readable form.
	 *
	 * @return string Either the date format, or HUMAN_READABLE to signal that
	 * the relative human readable string should be used. An empty string means
	 * to use the default WP date format.
	 */
	public function get_date_format() {
		if ( 'true' === General_Options::get_option( General_Options::HUMAN_READABLE_DATE ) ) {
			return 'HUMAN_READABLE';

		} else {
			$option = General_Options::get_option( General_Options::DATE_FORMAT );
			if ( ! is_string( $option ) ) {
				return '';
			}
		}

		return $option;
	}

	/**
	 * Display a class suffix, which consist of the meta names, separated by '-'.
	 * This function should be used only on DIRECT the parent of the meta
	 * elements.
	 *
	 * @param array $numbers
	 * @return void
	 */
	public function meta_suffix_classes( $numbers ) {
		$class_suffix = '';
		foreach ( $numbers as $number ) {
			$meta_class = $this->get_meta_suffix_class( $number );

			if ( ! empty( $meta_class ) ) {
				if ( ! empty( $class_suffix ) ) {
					$class_suffix = $class_suffix . '-' . $meta_class;
				} else {
					$class_suffix = $meta_class;
				}
			}
		}

		echo $class_suffix; // phpcs:ignore
	}

	/**
	 * Display the class suffix for the wrapper.
	 *
	 * @param int $number The number of the meta.
	 * @return void
	 */
	public function meta_suffix_class( $number ) {
		echo $this->get_meta_suffix_class( $number ); // phpcs:ignore
	}

	/**
	 * Get the class name suffix for the wrapper.
	 *
	 * @param int $number The number of the meta.
	 * @return string
	 */
	public function get_meta_suffix_class( $number ) {
		$meta = $this->get_meta_displayed_name( $number );

		if ( false === $meta ) {
			return '';
		}

		if ( 'rating_and_count' === $meta ) {
			$meta = 'rating';
		}

		return $meta;
	}

	/**
	 * Factory function to construct if a name exist.
	 *
	 * @throws RuntimeException If class is not found.
	 *
	 * @param string $name_or_id For the name of the class, can be either just
	 *                           the class name, or fully qualified name.
	 * @param int|string $widget_id A numerical parameter.
	 * @param int|string $query_id A numerical parameter.
	 * @param array $settings
	 * @return Article_Block
	 */
	public static function construct_class_by_name_or_id( $name_or_id, $widget_id, $query_id, $settings ) {
		$artblock_class_names  = Class_Retriever_Utils::get_all_article_block_names();
		$founded_artblock_name = '';

		foreach ( $artblock_class_names as $artblock_name ) {
			// find the class by id.
			if ( $artblock_name::get_id() === $name_or_id ) {
				$founded_artblock_name = $artblock_name;
				break;
			}

			// Find the class by class name. Verify if not empty first because otherwise PHP throw warning.
			if ( ! empty( $name_or_id ) && strpos( $artblock_name, $name_or_id ) !== false ) {
				$founded_artblock_name = $artblock_name;
				break;
			}
		}

		if ( ! class_exists( $founded_artblock_name ) ) {
			throw new RuntimeException( 'Could not find class ' . $founded_artblock_name );
		} else {
			return new $founded_artblock_name( $widget_id, $query_id, $settings );
		}

	}

	/**
	 * Return whether or not the article block is registered.
	 *
	 * @param string $artblock_id
	 * @return bool
	 */
	public static function article_block_id_exist( $artblock_id ) {
		$article_block_names = Class_Retriever_Utils::get_all_article_block_names();

		foreach ( $article_block_names as $article_block_name ) {
			if ( $article_block_name::get_id() === $artblock_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get a css selector of the body, to increase specificity of the CSS. By
	 * default this selector is used in all CSS.
	 *
	 * @return string
	 */
	protected function get_body_css_specificity_selector() {
		return Simple_Utils::get_body_css_increase_specificity_selector();
	}

	/**
	 * Return the default image src to display if a post has no thumbnail.
	 *
	 * @param string $size The WordPress size of the thumbnail to retrieve. Defaults to 'medium'.
	 * @return string
	 */
	public static function get_default_no_thumbnail_image_url( $size = 'medium' ) {
		return Directory_Utils::get_no_thumbnail_images_directory_url() . 'no-thumbnail.jpg';
	}

	/**
	 * Return the custom image src to display if a post has no thumbnail.
	 *
	 * @param string $size The WordPress size of the thumbnail to retrieve. Defaults to 'medium'.
	 * @return string|false
	 */
	public static function get_custom_no_thumbnail_image_url( $size = 'medium' ) {
		$image_id = General_Options::get_option( General_Options::NO_THUMBNAIL_IMAGE );

		$src = false;
		if ( is_numeric( $image_id ) ) {
			$image_data = wp_get_attachment_image_src( (int) $image_id, $size );
			if ( false !== $image_data ) {
				$src = $image_data[0];
			}
		}

		return $src;
	}

	/**
	 * Return the custom image srcset to display if a post has no thumbnail.
	 *
	 * @param string $size The WordPress size of the thumbnail to retrieve. Defaults to 'thumbnail'.
	 * @return string|false
	 */
	public static function get_custom_no_thumbnail_image_srcset( $size = 'medium' ) {
		$image_id = General_Options::get_option( General_Options::NO_THUMBNAIL_IMAGE );

		$src = false;
		if ( is_numeric( $image_id ) ) {
			$src = wp_get_attachment_image_srcset( (int) $image_id, $size );
		}

		// Suppress code analyzer.
		if ( is_bool( $src ) ) {
			return false;
		}

		return $src;
	}

	#endregion -- Helper methods

}

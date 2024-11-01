<?php

namespace TWRP\Database;

/**
 * Manages the database table where the html of a widget will be cached.
 *
 * The query_id is not mandatory to be an id of a query, and can also be a
 * string like "style", used to cache the inline style of a widget. This is why
 * query_id is of type string, but can also represent an id in that string.
 *
 * phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
 */
class Tabs_Cache_Table implements Clean_Database {

	const TABLE_NAME = 'twrp_widget_cache';

	const CACHE_REFRESHED_TIMESTAMP_OPTION_NAME = 'twrp__cache_refreshed_timestamp';

	/**
	 * Holds the table cache, to not be needed to query again the table.
	 *
	 * The first array contain a key with the widget id, and the value is an
	 * array of keys representing the post ids, and the value is an array with
	 * the keys of query ids, where the value is an array containing query_id
	 * html cache.
	 * array {
	 *    (int): widget_id.
	 *    1 => array {
	 *        (int): post_id (0 means a query that can be displayed anywhere).
	 *        0   => array {
	 *            (string): query_id
	 *            1 => array {
	 *                widget_id: "1"
	 *                query_id: "1"
	 *                html: "..."
	 *            }
	 *            ...
	 *        }
	 *    }
	 * }
	 *
	 * @var array
	 */
	private static $table_cache = array();

	/**
	 * Holds the widget id.
	 *
	 * @var int
	 */
	private $widget_id;

	/**
	 * Holds the post id.
	 *
	 * @var int
	 */
	private $post_id;

	/**
	 * Construct the class.
	 *
	 * @param int $widget_id
	 * @param int $post_id
	 */
	public function __construct( $widget_id, $post_id = 0 ) {
		$this->widget_id = $widget_id;
		$this->post_id   = $post_id;
	}

	/**
	 * Get the HTML for a widget query id.
	 *
	 * @param string $query_id
	 * @return string
	 */
	public function get_widget_html( $query_id ) {
		$this->update_internal_widget_cache();

		$widget_id = $this->widget_id;
		$post_id   = $this->post_id;

		if ( isset( self::$table_cache[ $widget_id ][ $post_id ][ $query_id ]['html'] ) ) {
			return self::$table_cache[ $widget_id ][ $post_id ][ $query_id ]['html'];
		}

		if ( isset( self::$table_cache[ $widget_id ][0][ $query_id ]['html'] ) ) {
			return self::$table_cache[ $widget_id ][0][ $query_id ]['html'];
		}

		return '';
	}

	#region -- Update static variable from database.

	/**
	 * Update the internal class static variable to the results from database,
	 * if necessary.
	 *
	 * @return void
	 */
	private function update_internal_widget_cache() {
		if ( $this->widget_is_cached() ) {
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		$widget_id  = $this->widget_id;
		$post_id    = $this->post_id;

		if ( 0 === $post_id ) {
			$sql_query = $wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				'SELECT html, post_id, query_id, cache_generation_time FROM ' . $table_name . ' WHERE widget_id=%d AND post_id=0',
				$widget_id
			);
		} else {
			$sql_query = $wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				'SELECT query_id, html, post_id, cache_generation_time FROM ' . $table_name . ' WHERE widget_id=%d AND ( post_id=0 OR post_id=%d )',
				$widget_id,
				$post_id
			);
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results( $sql_query, ARRAY_A );

		if ( ! empty( $results ) ) {
			$results = $this->format_database_results( $results, $widget_id );
			$this->update_cache( $results );
			$this->mark_post_id_cache_updated();
		}
	}

	/**
	 * Format the database results in a consistent way, with better accessibility.
	 *
	 * @param array $results
	 * @param int $widget_id
	 *
	 * @return array<array{widget_id:int,query_id:string,post_id:int,html:string,cache_generation_time:string}>
	 */
	private function format_database_results( $results, $widget_id ) {
		$pretty_results = array();

		foreach ( $results as $result ) {
			$query_id_result = array();

			$query_id_result['widget_id'] = $widget_id;

			if ( isset( $result['query_id'] ) ) {
				$query_id_result['query_id'] = (string) $result['query_id'];
			} else {
				continue;
			}

			if ( isset( $result['post_id'] ) ) {
				$query_id_result['post_id'] = (int) $result['post_id'];
			} else {
				continue;
			}

			if ( isset( $result['html'] ) ) {
				$query_id_result['html'] = (string) $result['html'];
			} else {
				$query_id_result['html'] = '';
			}

			if ( isset( $result['cache_generation_time'] ) ) {
				$query_id_result['cache_generation_time'] = (string) $result['cache_generation_time'];
			} else {
				$query_id_result['cache_generation_time'] = '';
			}

			array_push( $pretty_results, $query_id_result );
		}

		return $pretty_results;
	}

	#endregion -- Update static variable from database.

	#region -- Manage static cache.

	/**
	 * Check if the widget html was previously retrieved from database.
	 *
	 * @return bool
	 */
	private function widget_is_cached() {
		if ( ! isset( self::$table_cache[ $this->widget_id ][ $this->post_id ] ) ) {
			return false;
		}

		if ( ! isset( self::$table_cache[ $this->widget_id ][0] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Update this class static cache variable with the results from database.
	 *
	 * @param array $results
	 * @return void
	 */
	private function update_cache( $results ) {
		foreach ( $results as $result ) {

			// Make sure that in cache we have the widget_id as an array.
			if ( isset( $result['widget_id'] ) ) {
				$widget_id = $result['widget_id'];
				if ( ! isset( self::$table_cache[ $widget_id ] ) || ! is_array( self::$table_cache[ $widget_id ] ) ) {
					self::$table_cache[ $widget_id ] = array();
				}
			} else {
				continue;
			}

			if ( isset( $result['post_id'] ) ) {
				$post_id = $result['post_id'];
				if ( ! isset( self::$table_cache[ $widget_id ][ $post_id ] ) || ! is_array( self::$table_cache[ $widget_id ][ $post_id ] ) ) {
					self::$table_cache[ $widget_id ][ $post_id ] = array();
				}
			} else {
				continue;
			}

			if ( isset( $result['query_id'] ) ) {
				$query_id = $result['query_id'];
			} else {
				continue;
			}

			self::$table_cache[ $widget_id ][ $post_id ][ $query_id ] = $result;
		}
	}

	/**
	 * Make sure that the static variable that holds the cache updates it's key
	 * to be detected as previously retrieved from database, when verified by
	 * widget_is_cached() function, to not make widget_is_cached() return false
	 * when in fact should return true.
	 *
	 * @return void
	 */
	private function mark_post_id_cache_updated() {
		if ( ! isset( self::$table_cache[ $this->widget_id ][ $this->post_id ] ) ) {
			self::$table_cache[ $this->widget_id ][ $this->post_id ] = array();
		}

		if ( ! isset( self::$table_cache[ $this->widget_id ][0] ) ) {
			self::$table_cache[ $this->widget_id ][0] = array();
		}
	}

	#endregion -- Manage static cache.

	#region -- Update table with widget html

	/**
	 * Update the database table with new tab content.
	 *
	 * @param string|int $query_id
	 * @param string $html
	 * @return void
	 */
	public function update_widget_html( $query_id, $html ) {
		global $wpdb;

		$table_name   = $wpdb->prefix . self::TABLE_NAME;
		$widget_id    = $this->widget_id;
		$query_id     = (string) $query_id;
		$post_id      = $this->post_id;
		$current_time = time();
		$post_id      = 0;

		// No need to cache here to static variable, since the database cache will anyway occur from an async request.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"INSERT INTO {$table_name} (widget_id,query_id,post_id,html,cache_generation_time) VALUES (%d,%s,%s,%s,%d) ON DUPLICATE KEY UPDATE html=%s, cache_generation_time=%d",
				$widget_id,
				$query_id,
				$post_id,
				$html,
				$current_time,
				$html,
				$current_time
			)
		);

	}

	#endregion -- Update table with widget html

	#region -- Delete widget cache

	/**
	 * Delete all the rows in the cache that DOES NOT exist in the widget_ids array.
	 *
	 * @param array $widget_ids
	 * @return void
	 */
	public static function delete_widgets_cache_not_in( $widget_ids ) {
		global $wpdb;
		$table_name         = $wpdb->prefix . self::TABLE_NAME;
		$escaped_widget_ids = array();

		foreach ( $widget_ids as $widget_id ) {
			if ( is_numeric( $widget_id ) ) {
				$widget_id = $wpdb->prepare( '%d', $widget_id );
				if ( is_array( $widget_id ) ) {
					continue;
				}

				array_push( $escaped_widget_ids, $widget_id );
			}
		}

		if ( empty( $escaped_widget_ids ) ) {
			return;
		}
		$not_in = implode( ', ', $escaped_widget_ids );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$wpdb->query( "DELETE FROM {$table_name} WHERE widget_id NOT IN ({$not_in})" );
	}

	#endregion -- Delete widget cache

	#region -- Manages global query timestamp

	/**
	 * Get the timestamp when global cache was generated.
	 *
	 * @return string Represents a number as a string.
	 */
	public static function get_cache_refreshed_timestamp() {
		$timestamp = get_option( self::CACHE_REFRESHED_TIMESTAMP_OPTION_NAME, '0' );

		if ( is_numeric( $timestamp ) ) {
			return (string) $timestamp;
		}

		return '0';
	}

	/**
	 * Set the global cache generated at the current timestamp.
	 *
	 * @return void
	 */
	public static function refresh_cache_timestamp() {
		$timestamp = time();
		update_option( self::CACHE_REFRESHED_TIMESTAMP_OPTION_NAME, $timestamp );
	}

	/**
	 * Delete the global cache timestamp.
	 *
	 * @return void
	 */
	public static function delete_cache_timestamp() {
		delete_option( self::CACHE_REFRESHED_TIMESTAMP_OPTION_NAME );
	}

	#endregion -- Manages global query timestamp

	#region -- Create cache table.

	/**
	 * Create the table on plugin activation.
	 *
	 * @return void
	 */
	public static function create_table_on_plugin_activation() {
		static::create_table();
	}

	/**
	 * Create a table to hold the tabs HTML.
	 *
	 * @return void
	 * @psalm-suppress UnresolvableInclude
	 * @psalm-suppress UndefinedConstant
	 * @psalm-suppress MissingFile
	 */
	public static function create_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . self::TABLE_NAME;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id int NOT NULL AUTO_INCREMENT,
			widget_id int NOT NULL,
			query_id varchar(16) NOT NULL,
			post_id int NOT NULL,
			html text NOT NULL,
			cache_generation_time varchar(16) NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY widget_id (widget_id,query_id,post_id)
		) $charset_collate;";

		require_once \ABSPATH . 'wp-admin/includes/upgrade.php'; // @phan-suppress-current-line PhanMissingRequireFile
		dbDelta( $sql );
	}

	#endregion -- Create cache table.

	#region -- Clean Database and delete table.

	public static function clean_database() {
		delete_option( self::CACHE_REFRESHED_TIMESTAMP_OPTION_NAME );
		self::delete_table();
	}

	/**
	 * Delete the cache table.
	 *
	 * @return void
	 */
	public static function delete_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;

		$sql = "DROP TABLE IF EXISTS $table_name";
		// phpcs:ignore WordPress.DB.PreparedSQL, WordPress.DB.DirectDatabaseQuery
		$wpdb->query( $sql );
	}

	#region -- Clean Database

}

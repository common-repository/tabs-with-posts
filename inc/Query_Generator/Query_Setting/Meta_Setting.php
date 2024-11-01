<?php

namespace TWRP\Query_Generator\Query_Setting;

/**
 * Filter the posts based on if the posts contain a meta key or not.
 */
class Meta_Setting extends Query_Setting {

	public static function get_class_order_among_siblings() {
		return 33;
	}

	const META_IS_APPLIED__SETTING_NAME = 'meta_applied';

	const META_KEY_NAME__SETTING_NAME       = 'meta_name';
	const META_KEY_VALUE__SETTING_NAME      = 'meta_value';
	const META_KEY_COMPARATOR__SETTING_NAME = 'meta_comparator';

	public function get_setting_name() {
		return 'meta_settings';
	}

	/**
	 * Get an array where the key is meta comparator and the value is a
	 * description of the comparator.
	 *
	 * @return array
	 */
	public function get_meta_key_comparators() {
		return array(
			'EXISTS'     => _x( 'Exists', 'backend', 'tabs-with-posts' ),
			'NOT EXISTS' => _x( 'Not Exists', 'backend', 'tabs-with-posts' ),
			'!='         => _x( 'Not equal (!=)', 'backend', 'tabs-with-posts' ),
			'='          => _x( 'Equal (=)', 'backend', 'tabs-with-posts' ),
			'>='         => _x( 'Bigger or equal than (>=)', 'backend', 'tabs-with-posts' ),
			'<='         => _x( 'Less or equal than (<=)', 'backend', 'tabs-with-posts' ),
			'>'          => _x( 'Bigger than (>)', 'backend', 'tabs-with-posts' ),
			'<'          => _x( 'Less than (<)', 'backend', 'tabs-with-posts' ),
		);
	}

	public function get_default_setting() {
		return array(
			self::META_IS_APPLIED__SETTING_NAME     => 'NA',
			self::META_KEY_NAME__SETTING_NAME       => '',
			self::META_KEY_VALUE__SETTING_NAME      => '',
			self::META_KEY_COMPARATOR__SETTING_NAME => 'EXISTS',
		);
	}

	public function sanitize_setting( $settings ) {
		if ( empty( $settings[ self::META_KEY_NAME__SETTING_NAME ] ) || empty( $settings[ self::META_IS_APPLIED__SETTING_NAME ] ) || 'NA' === $settings[ self::META_IS_APPLIED__SETTING_NAME ] ) {
			return $this->get_default_setting();
		}

		$key_name        = sanitize_key( $settings[ self::META_KEY_NAME__SETTING_NAME ] );
		$is_empty_string = preg_match( '/^\s+$/', $key_name );
		if ( $is_empty_string ) {
			return $this->get_default_setting();
		}
		$sanitize_settings                                        = array( self::META_KEY_NAME__SETTING_NAME => $key_name );
		$sanitize_settings[ self::META_IS_APPLIED__SETTING_NAME ] = $settings[ self::META_IS_APPLIED__SETTING_NAME ];

		// Comparator verification.
		if ( empty( $settings[ self::META_KEY_COMPARATOR__SETTING_NAME ] ) ) {
			return $this->get_default_setting();
		}
		$possible_values = array_keys( $this->get_meta_key_comparators() );
		if ( ! in_array( $settings[ self::META_KEY_COMPARATOR__SETTING_NAME ], $possible_values, true ) ) {
			return $this->get_default_setting();
		}
		$sanitize_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ] = $settings[ self::META_KEY_COMPARATOR__SETTING_NAME ];

		// Meta value verification.
		$meta_comparator = $sanitize_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ];
		if ( empty( $settings[ self::META_KEY_VALUE__SETTING_NAME ] )
		|| ( 'EXISTS' === $meta_comparator )
		|| ( 'NOT EXISTS' === $meta_comparator )
		) {
			$sanitize_settings[ self::META_KEY_VALUE__SETTING_NAME ] = '';
		} else {
			$sanitize_settings[ self::META_KEY_VALUE__SETTING_NAME ] = $settings[ self::META_KEY_VALUE__SETTING_NAME ];
		}

		return $sanitize_settings;
	}

	public function add_query_arg( $previous_query_args, $query_settings ) {
		if ( empty( $query_settings[ $this->get_setting_name() ][ self::META_KEY_NAME__SETTING_NAME ] ) ) {
			return $previous_query_args;
		}

		$meta_settings = $query_settings[ $this->get_setting_name() ];

		if ( empty( $meta_settings[ self::META_IS_APPLIED__SETTING_NAME ] ) || 'NA' === $meta_settings[ self::META_IS_APPLIED__SETTING_NAME ] ) {
			return $previous_query_args;
		}

		// phpcs:ignore -- Slow query.
		$previous_query_args['meta_key'] = $meta_settings[ self::META_KEY_NAME__SETTING_NAME ];

		if ( 'EXISTS' === $meta_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ] ||
		'NOT EXISTS' === $meta_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ] ) {
			$previous_query_args['meta_compare'] = $meta_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ];
			return $previous_query_args;
		}

		$previous_query_args['meta_compare'] = $meta_settings[ self::META_KEY_COMPARATOR__SETTING_NAME ];

		if ( isset( $meta_settings[ self::META_KEY_VALUE__SETTING_NAME ] ) ) {
			if ( is_numeric( $meta_settings[ self::META_KEY_VALUE__SETTING_NAME ] ) ) {
				$previous_query_args['meta_value_num'] = $meta_settings[ self::META_KEY_VALUE__SETTING_NAME ];
			} else {
				$previous_query_args['meta_value'] = $meta_settings[ self::META_KEY_VALUE__SETTING_NAME ]; // phpcs:ignore -- Slow query.
			}
		} else {
			$previous_query_args['meta_value'] = ''; // phpcs:ignore -- Slow query.
		}

		return $previous_query_args;
	}
}

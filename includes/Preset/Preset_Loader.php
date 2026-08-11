<?php

namespace HexaGrid\Preset;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Preset_Loader
 *
 * Loads, validates, and caches preset configurations,
 * then merges them with shortcode overrides.
 */
class Preset_Loader {

	/**
	 * Cache group name.
	 *
	 * @var string
	 */
	private const CACHE_GROUP = 'hexagrid';

	/**
	 * Load preset configuration merged with shortcode attributes.
	 *
	 * @param int   $preset_id      Preset post ID.
	 * @param array $shortcode_atts Attributes passed directly to the shortcode.
	 * @return array Merged configuration.
	 */
	public function load( $preset_id, $shortcode_atts = [] ) {
		$preset_id = absint( $preset_id );

		if ( ! $preset_id ) {
			return $this->get_defaults_with_overrides( $shortcode_atts );
		}

		$preset = $this->get_preset_post( $preset_id );

		if ( is_wp_error( $preset ) ) {
			return $this->get_defaults_with_overrides( $shortcode_atts );
		}

		$preset_config = $this->parse_preset_meta( $preset_id );

		return $this->merge_config( $preset_config, $shortcode_atts );
	}

	/**
	 * Validate and retrieve the preset post.
	 *
	 * @param int $preset_id Preset post ID.
	 * @return \WP_Post|\WP_Error Post object or error.
	 */
	private function get_preset_post( $preset_id ) {
		$post = get_post( $preset_id );

		if ( ! $post || is_wp_error( $post ) ) {
			return new \WP_Error(
				'preset_not_found',
				__( 'Preset not found.', 'hexa-grid-product-showcase' )
			);
		}

		if ( 'hexagrid_show_preset' !== $post->post_type ) {
			return new \WP_Error(
				'invalid_preset_type',
				__( 'Invalid preset type.', 'hexa-grid-product-showcase' )
			);
		}

		if ( 'publish' !== $post->post_status && ! current_user_can( 'edit_post', $preset_id ) ) {
			return new \WP_Error(
				'preset_not_published',
				__( 'Preset is not published.', 'hexa-grid-product-showcase' )
			);
		}

		return $post;
	}

	/**
	 * Get default values with shortcode overrides only.
	 *
	 * @param array $shortcode_atts Shortcode attributes.
	 * @return array
	 */
	private function get_defaults_with_overrides( $shortcode_atts ) {
		$defaults = Preset_Config::get_shortcode_defaults();

		return $this->apply_shortcode_overrides( $defaults, $shortcode_atts );
	}

	/**
	 * Parse preset meta values into a shortcode-attribute keyed array.
	 *
	 * @param int $preset_id Preset post ID.
	 * @return array
	 */
	private function parse_preset_meta( $preset_id ) {
		$cache_key = 'hexagrid_preset_' . $preset_id;
		$config    = wp_cache_get( $cache_key, self::CACHE_GROUP );

		if ( false !== $config ) {
			return $config;
		}

		$config = [];

		foreach ( Preset_Config::get_fields() as $field_key => $field_config ) {
			$value = get_post_meta( $preset_id, $field_config['meta_key'], true );

			if ( '' === $value || false === $value || null === $value ) {
				$value = $field_config['default'];
			}

			$config[ $field_config['shortcode_attr'] ] = $this->sanitize_value( $value, $field_config );
		}

		wp_cache_set( $cache_key, $config, self::CACHE_GROUP );

		return $config;
	}

	/**
	 * Merge preset config with shortcode overrides.
	 *
	 * Precedence: explicit shortcode > preset > defaults.
	 *
	 * @param array $preset_config  Parsed preset configuration.
	 * @param array $shortcode_atts Shortcode attributes.
	 * @return array
	 */
	private function merge_config( $preset_config, $shortcode_atts ) {
		$defaults = Preset_Config::get_shortcode_defaults();

		// Start with defaults.
		$merged = $defaults;

		// Apply preset values.
		foreach ( $preset_config as $key => $value ) {
			if ( $this->is_valid_value( $value ) ) {
				$merged[ $key ] = $value;
			}
		}

		// Apply explicit shortcode overrides.
		$merged = $this->apply_shortcode_overrides( $merged, $shortcode_atts );

		// Run through shortcode_atts filter for third-party compatibility.
		return shortcode_atts( $defaults, $merged, 'hexagrid_product_showcase' );
	}

	/**
	 * Apply non-empty shortcode overrides to a config array.
	 *
	 * @param array $base     Base configuration.
	 * @param array $overrides Shortcode attributes.
	 * @return array
	 */
	private function apply_shortcode_overrides( $base, $overrides ) {
		foreach ( $overrides as $key => $value ) {
			if ( 'preset_id' === $key ) {
				continue;
			}

			if ( $this->is_valid_value( $value ) ) {
				$base[ $key ] = $value;
			}
		}

		return $base;
	}

	/**
	 * Check if a value is considered valid/non-empty.
	 *
	 * @param mixed $value Value to check.
	 * @return bool
	 */
	private function is_valid_value( $value ) {
		return '' !== $value && null !== $value && false !== $value;
	}

	/**
	 * Sanitize a value using the field's configured sanitizer.
	 *
	 * @param mixed $value  Raw value.
	 * @param array $config Field config.
	 * @return mixed
	 */
	private function sanitize_value( $value, $config ) {
		$sanitize = isset( $config['sanitize'] ) ? $config['sanitize'] : 'sanitize_text_field';

		if ( is_callable( $sanitize ) ) {
			return call_user_func( $sanitize, $value );
		}

		if ( function_exists( $sanitize ) ) {
			return $sanitize( $value );
		}

		return sanitize_text_field( $value );
	}

	/**
	 * Clear cached configuration for a preset.
	 *
	 * @param int $preset_id Preset post ID.
	 * @return void
	 */
	public static function clear_cache( $preset_id ) {
		$preset_id = absint( $preset_id );

		if ( $preset_id ) {
			wp_cache_delete( 'hexagrid_preset_' . $preset_id, self::CACHE_GROUP );
		}
	}
}

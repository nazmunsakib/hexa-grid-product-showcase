<?php

namespace HexaGrid\Preset;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Preset_Config
 *
 * Centralized configuration for Hexa Grid presets.
 */
class Preset_Config {

	/**
	 * Get the full field configuration.
	 *
	 * @return array Field definitions.
	 */
	public static function get_fields() {
		return [
			'layout'          => [
				'meta_key'       => '_hexagrid_layout_type',
				'post_key'       => 'hexagrid_layout_type',
				'shortcode_attr' => 'layout',
				'default'        => 'grid',
				'sanitize'       => 'sanitize_text_field',
			],
			'style'           => [
				'meta_key'       => '_hexagrid_layout_style',
				'post_key'       => 'hexagrid_layout_style',
				'shortcode_attr' => 'style',
				'default'        => 'product-grid-1',
				'sanitize'       => 'sanitize_text_field',
			],
			'columns'         => [
				'meta_key'       => '_hexagrid_columns',
				'post_key'       => 'hexagrid_columns',
				'shortcode_attr' => 'columns',
				'default'        => 3,
				'sanitize'       => 'intval',
			],
			'limit'           => [
				'meta_key'       => '_hexagrid_query_limit',
				'post_key'       => 'hexagrid_query_limit',
				'shortcode_attr' => 'limit',
				'default'        => 12,
				'sanitize'       => 'intval',
			],
			'ids'             => [
				'meta_key'       => '_hexagrid_include_ids',
				'post_key'       => 'hexagrid_include_ids',
				'shortcode_attr' => 'ids',
				'default'        => '',
				'sanitize'       => 'sanitize_text_field',
			],
			'exclude_ids'     => [
				'meta_key'       => '_hexagrid_exclude_ids',
				'post_key'       => 'hexagrid_exclude_ids',
				'shortcode_attr' => 'exclude_ids',
				'default'        => '',
				'sanitize'       => 'sanitize_text_field',
			],
			'orderby'         => [
				'meta_key'       => '_hexagrid_orderby',
				'post_key'       => 'hexagrid_orderby',
				'shortcode_attr' => 'orderby',
				'default'        => 'date',
				'sanitize'       => 'sanitize_text_field',
			],
			'order'           => [
				'meta_key'       => '_hexagrid_order',
				'post_key'       => 'hexagrid_order',
				'shortcode_attr' => 'order',
				'default'        => 'DESC',
				'sanitize'       => 'sanitize_text_field',
			],
			'theme_color'     => [
				'meta_key'       => '_hexagrid_theme_color',
				'post_key'       => 'hexagrid_theme_color',
				'shortcode_attr' => 'theme_color',
				'default'        => '#3291b6',
				'sanitize'       => 'sanitize_hex_color',
			],
			'slider_nav'      => [
				'meta_key'       => '_hexagrid_slider_nav',
				'post_key'       => 'hexagrid_slider_nav',
				'shortcode_attr' => 'slider_nav',
				'default'        => 'yes',
				'sanitize'       => [ __CLASS__, 'sanitize_yes_no' ],
				'type'           => 'checkbox',
			],
			'slider_dots'     => [
				'meta_key'       => '_hexagrid_slider_dots',
				'post_key'       => 'hexagrid_slider_dots',
				'shortcode_attr' => 'slider_dots',
				'default'        => 'yes',
				'sanitize'       => [ __CLASS__, 'sanitize_yes_no' ],
				'type'           => 'checkbox',
			],
			'slider_autoplay' => [
				'meta_key'       => '_hexagrid_slider_autoplay',
				'post_key'       => 'hexagrid_slider_autoplay',
				'shortcode_attr' => 'slider_autoplay',
				'default'        => 'no',
				'sanitize'       => [ __CLASS__, 'sanitize_yes_no' ],
				'type'           => 'checkbox',
			],
			'slider_nav_position' => [
				'meta_key'       => '_hexagrid_slider_nav_position',
				'post_key'       => 'hexagrid_slider_nav_position',
				'shortcode_attr' => 'slider_nav_position',
				'default'        => 'middle',
				'sanitize'       => 'sanitize_text_field',
				'type'           => 'text',
			],
		];
	}

	/**
	 * Sanitize yes/no values.
	 *
	 * @param mixed $value Raw value.
	 * @return string 'yes' or 'no'.
	 */
	public static function sanitize_yes_no( $value ) {
		return in_array( $value, [ 'yes', 'no' ], true ) ? $value : 'no';
	}

	/**
	 * Get default values keyed by shortcode attribute name.
	 *
	 * @return array
	 */
	public static function get_shortcode_defaults() {
		$defaults = [];

		foreach ( self::get_fields() as $config ) {
			$defaults[ $config['shortcode_attr'] ] = $config['default'];
		}

		return $defaults;
	}

	/**
	 * Get meta key to shortcode attribute map.
	 *
	 * @return array
	 */
	public static function get_meta_map() {
		$map = [];

		foreach ( self::get_fields() as $config ) {
			$map[ $config['shortcode_attr'] ] = $config['meta_key'];
		}

		return $map;
	}

	/**
	 * Get post key to meta key map.
	 *
	 * @return array
	 */
	public static function get_post_meta_map() {
		$map = [];

		foreach ( self::get_fields() as $config ) {
			$map[ $config['post_key'] ] = $config['meta_key'];
		}

		return $map;
	}

	/**
	 * Get field configuration by field key.
	 *
	 * @param string $field Field key.
	 * @return array|null
	 */
	public static function get_field( $field ) {
		$fields = self::get_fields();

		return isset( $fields[ $field ] ) ? $fields[ $field ] : null;
	}

	/**
	 * Get default value for a field.
	 *
	 * @param string $field Field key.
	 * @return mixed
	 */
	public static function get_default( $field ) {
		$config = self::get_field( $field );

		return $config ? $config['default'] : null;
	}

	/**
	 * Get meta key for a field.
	 *
	 * @param string $field Field key.
	 * @return string|null
	 */
	public static function get_meta_key( $field ) {
		$config = self::get_field( $field );

		return $config ? $config['meta_key'] : null;
	}
}

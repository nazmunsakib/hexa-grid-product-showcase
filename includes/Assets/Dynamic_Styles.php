<?php

namespace HexaGrid\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Dynamic_Styles
 *
 * Generates scoped CSS custom properties for theme colors.
 *
 * Instead of outputting many !important rules per instance, we set a single
 * CSS variable on the shortcode wrapper. Static CSS then references that
 * variable for all theme-colored elements. This keeps styling consistent,
 * maintainable, and easy to override.
 */
class Dynamic_Styles {

	/**
	 * Generate scoped CSS custom properties.
	 *
	 * @param array  $atts      Shortcode attributes.
	 * @param string $unique_id Unique wrapper ID for scoping.
	 * @return string CSS string.
	 */
	public static function generate( $atts, $unique_id = '' ) {
		$theme_color = isset( $atts['theme_color'] ) ? sanitize_hex_color( $atts['theme_color'] ) : '';

		if ( empty( $theme_color ) || empty( $unique_id ) ) {
			return '';
		}

		$hover_color = self::adjust_brightness( $theme_color, -15 );

		$css  = '<style>';
		$css .= '#' . esc_attr( $unique_id ) . ' {';
		$css .= ' --hexagrid-color-primary: ' . esc_attr( $theme_color ) . ';';
		$css .= ' --hexagrid-color-primary-hover: ' . esc_attr( $hover_color ) . ';';
		$css .= ' }';
		$css .= '</style>';

		return $css;
	}

	/**
	 * Lighten or darken a hex color.
	 *
	 * @param string $hex    Hex color (with or without #).
	 * @param int    $percent Positive to lighten, negative to darken.
	 * @return string Hex color.
	 */
	public static function adjust_brightness( $hex, $percent ) {
		$hex = ltrim( $hex, '#' );

		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		$r = max( 0, min( 255, hexdec( substr( $hex, 0, 2 ) ) + ( $percent * 2.55 ) ) );
		$g = max( 0, min( 255, hexdec( substr( $hex, 2, 2 ) ) + ( $percent * 2.55 ) ) );
		$b = max( 0, min( 255, hexdec( substr( $hex, 4, 2 ) ) + ( $percent * 2.55 ) ) );

		return '#' . sprintf( '%02x%02x%02x', $r, $g, $b );
	}
}

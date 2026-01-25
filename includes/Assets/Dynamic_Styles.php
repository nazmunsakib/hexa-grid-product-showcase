<?php

namespace ProductShowcase\Assets;

/**
 * Class Dynamic_Styles
 *
 * Generates inline CSS based on shortcode attributes.
 */
class Dynamic_Styles {

    /**
     * Generate CSS.
     *
     * @param array $atts Attributes.
     * @param string $unique_id Unique ID for the wrapper to scope styles (optional, if we add IDs to wrappers).
     * @return string CSS string.
     */
    public static function generate( $atts, $unique_id = '' ) {
        $theme_color = isset( $atts['theme_color'] ) ? $atts['theme_color'] : '';

        if ( empty( $theme_color ) ) {
            return '';
        }

        // Ensure ID selector format
        $scope = $unique_id ? "#{$unique_id} " : '';

        $css = '<style>';
        
        // Backgrounds (Badges, Buttons)
        $css .= "{$scope}.psw-sale-badge { background-color: {$theme_color} !important; }";
        $css .= "{$scope}.psw-add-btn>a { background-color: {$theme_color} !important; }";
        $css .= "{$scope}.psw-product-price ins .amount { color: {$theme_color} !important; }";
        $css .= "{$scope}.psw-product:hover .psw-product-cart-btn a { background-color: {$theme_color} !important; border-color: {$theme_color} !important; }";
        
        // Text Hover (Links) - NOW USING THEME COLOR
        $css .= "{$scope}.psw-product-title a:hover, {$scope}.psw-product-category a:hover { color: {$theme_color} !important; }";
        
        // Pagination/Action buttons if needed
        $css .= "{$scope}.swiper-button-next, {$scope}.swiper-button-prev { color: {$theme_color} !important; }";

        $css .= '</style>';

        return $css;
    }
}

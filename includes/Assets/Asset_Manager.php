<?php

namespace HexaGrid\Assets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Asset_Manager
 *
 * Handles enqueuing of scripts and styles.
 */
class Asset_Manager {

    /**
     * Initialize hooks.
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Enqueue frontend assets.
     */
    public function enqueue_assets() {
        $plugin_root_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );

        wp_enqueue_style(
            'hexa-grid-product-showcase-style',
            $plugin_root_url . 'assets/css/style.css',
            [],
            '1.0.0'
        );

        // Enqueue Swiper JS (Local)
        wp_enqueue_script( 'swiper-js', $plugin_root_url . 'assets/vendor/swiper/swiper-bundle.min.js', [], '11.0.0', true );
        wp_enqueue_style( 'swiper-css', $plugin_root_url . 'assets/vendor/swiper/swiper-bundle.min.css', [], '11.0.0' );

        wp_enqueue_script(
            'hexa-grid-product-showcase-script',
            $plugin_root_url . 'assets/js/main.js',
            [ 'jquery', 'swiper-js' ],
            '1.0.0',
            true
        );
    }
}

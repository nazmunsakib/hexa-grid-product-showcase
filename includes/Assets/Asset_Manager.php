<?php

namespace HexaGrid\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Asset_Manager
 *
 * Handles registration and enqueueing of scripts and styles.
 */
class Asset_Manager {

	/**
	 * Plugin version used for cache busting.
	 *
	 * @var string
	 */
	private const ASSET_VERSION = '1.1.3';

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
	}

	/**
	 * Register frontend assets.
	 *
	 * Assets are registered early but only enqueued when the shortcode is rendered.
	 */
	public function register_assets() {
		$plugin_root_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );

		wp_register_style(
			'hexa-grid-product-showcase-style',
			$plugin_root_url . 'assets/css/style.css',
			[],
			self::ASSET_VERSION
		);

		wp_register_style(
			'swiper-css',
			$plugin_root_url . 'assets/vendor/swiper/swiper-bundle.min.css',
			[],
			'11.0.0'
		);

		wp_register_script(
			'swiper-js',
			$plugin_root_url . 'assets/vendor/swiper/swiper-bundle.min.js',
			[],
			'11.0.0',
			true
		);

		wp_register_script(
			'hexa-grid-product-showcase-script',
			$plugin_root_url . 'assets/js/main.js',
			[ 'jquery', 'swiper-js' ],
			self::ASSET_VERSION,
			true
		);
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * Called from the shortcode handler so assets load only when needed.
	 */
	public function enqueue() {
		// Ensure assets are registered before enqueueing, even if this is
		// called before the wp_enqueue_scripts hook has fired.
		if ( ! wp_style_is( 'hexa-grid-product-showcase-style', 'registered' ) ) {
			$this->register_assets();
		}

		wp_enqueue_style( 'hexa-grid-product-showcase-style' );
		wp_enqueue_style( 'swiper-css' );
		wp_enqueue_script( 'swiper-js' );
		wp_enqueue_script( 'hexa-grid-product-showcase-script' );

		// Localize data required by the wishlist feature.
		$wishlist_handler = new \HexaGrid\Wishlist\Wishlist_Handler();
		wp_localize_script( 'hexa-grid-product-showcase-script', 'hexagridWishlist', [
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( \HexaGrid\Wishlist\Wishlist_Handler::NONCE_ACTION ),
			'wishlist'  => $wishlist_handler->get_wishlist(),
			'count'     => $wishlist_handler->get_count(),
			'i18n'      => [
				'added'   => __( 'Added to wishlist', 'hexa-grid-product-showcase' ),
				'removed' => __( 'Removed from wishlist', 'hexa-grid-product-showcase' ),
				'error'   => __( 'Something went wrong. Please try again.', 'hexa-grid-product-showcase' ),
			],
		] );

		// Localize data for table layout AJAX pagination.
		wp_localize_script( 'hexa-grid-product-showcase-script', 'hexagridTable', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'action'  => \HexaGrid\Layout\Table_Layout::AJAX_ACTION,
			'nonce'   => wp_create_nonce( \HexaGrid\Layout\Table_Layout::AJAX_ACTION ),
			'i18n'    => [
				'error' => __( 'Unable to load the requested page. Please try again.', 'hexa-grid-product-showcase' ),
			],
		] );
	}
}

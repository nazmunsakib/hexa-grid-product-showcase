<?php
/**
 * Plugin Name: Product Showcase Woo
 * Plugin URI: https://addonskit.com
 * Description: Professional Product Showcase with Unlimited Grid, List, Slider and Table Layout for WooCommerce.
 * Version: 1.0.0
 * Author: Nazmun Sakib
 * Author URI: https://nazmunsakib.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: product-showcase-woo
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.9
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 5.0
 * WC tested up to: 8.5
 *
 * @package ProductShowcase
 * @author Nazmun Sakib
 * @since 1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autoload dependencies.
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Initialize the plugin.
 */
function product_showcase_init() {
	if ( class_exists( 'ProductShowcase\\Product_Showcase' ) ) {
		\ProductShowcase\Product_Showcase::get_instance();
	}
}
add_action( 'plugins_loaded', 'product_showcase_init' );

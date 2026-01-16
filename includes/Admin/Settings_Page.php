<?php

namespace ProductShowcase\Admin;

/**
 * Class Settings_Page
 *
 * Registers the settings page submenu.
 */
class Settings_Page {

    /**
     * Initialize hooks.
     */
    public function init() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'in_admin_header', [ $this, 'remove_notices' ] );
    }

    /**
     * Remove generic admin notices on plugin pages.
     */
    public function remove_notices() {
        $screen = get_current_screen();
        if ( ! $screen ) return;

        // Check if we are on our settings page or custom post type
        if ( strpos( $screen->id, 'product_show_preset' ) !== false || strpos( $screen->id, 'psw-product-showcase-settings' ) !== false ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }

    /**
     * Register the admin menu.
     */
    public function register_menu() {
        // Add submenu under the Custom Post Type
        add_submenu_page(
            'edit.php?post_type=product_show_preset',
            __( 'Settings & Docs', 'product-showcase-woo' ),
            __( 'Settings', 'product-showcase-woo' ),
            'manage_options',
            'psw-product-showcase-settings',
            [ $this, 'render_page' ]
        );
    }

    /**
     * Render the settings page content.
     */
    public function render_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Product Showcase - General Settings', 'product-showcase-woo' ); ?></h1>
            
            <div class="ps-admin-content" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
                <h2><?php esc_html_e( 'Documentation', 'product-showcase-woo' ); ?></h2>
                <p><?php esc_html_e( 'Welcome to Professional Product Showcase! Use this plugin to display your WooCommerce products in beautiful layouts.', 'product-showcase-woo' ); ?></p>

                <hr>

                <h3><?php esc_html_e( '1. Using the Builder (Presets)', 'product-showcase-woo' ); ?></h3>
                <p><?php esc_html_e( 'The easiest way to use the plugin is by creating a Preset.', 'product-showcase-woo' ); ?></p>
                <ol>
                    <li><?php echo wp_kses_post( __( 'Go to <strong>Product Showcase > Showcase Presets</strong>.', 'product-showcase-woo' ) ); ?></li>
                    <li><?php echo wp_kses_post( __( 'Click on <strong>Add New</strong>.', 'product-showcase-woo' ) ); ?></li>
                    <li><?php echo wp_kses_post( __( 'Configure your layout (Grid, List, Slider), columns, and product limits in the <strong>Showcase Settings</strong> box.', 'product-showcase-woo' ) ); ?></li>
                    <li><?php echo wp_kses_post( __( 'Copy the generated shortcode, e.g., <code>[product_showcase preset_id="123"]</code>.', 'product-showcase-woo' ) ); ?></li>
                    <li><?php esc_html_e( 'Paste it into any Post or Page.', 'product-showcase-woo' ); ?></li>
                </ol>

                <hr>

                <h3><?php esc_html_e( '2. Using Shortcodes Manually', 'product-showcase-woo' ); ?></h3>
                <p><?php esc_html_e( 'You can also use the shortcode directly with attributes:', 'product-showcase-woo' ); ?></p>
                <code>[product_showcase layout="grid" limit="8" columns="4"]</code>

                <h4><?php esc_html_e( 'Available Attributes:', 'product-showcase-woo' ); ?></h4>
                <ul>
                    <li><code><?php esc_html_e( 'layout', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'grid, list, slider (default: grid)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'limit', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'Number of products to show (default: 12)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'columns', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'Number of columns for grid layout (default: 3)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'category', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'Product category slug (comma separated)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'ids', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'Specific product IDs (comma separated)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'orderby', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'date, price, rand, title (default: date)', 'product-showcase-woo' ); ?></li>
                    <li><code><?php esc_html_e( 'order', 'product-showcase-woo' ); ?></code>: <?php esc_html_e( 'DESC, ASC (default: DESC)', 'product-showcase-woo' ); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
}

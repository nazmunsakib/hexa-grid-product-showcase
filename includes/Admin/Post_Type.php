<?php

namespace ProductShowcase\Admin;

/**
 * Class Post_Type
 *
 * Registers the Custom Post Type for Presets.
 */
class Post_Type {

    /**
     * Initialize hooks.
     */
    public function init() {
        add_action( 'init', [ $this, 'register_cpt' ] );
    }

    /**
     * Register the post type.
     */
    public function register_cpt() {
        $labels = [
            'name'               => _x( 'Showcase Presets', 'post type general name', 'product-showcase-woo' ),
            'singular_name'      => _x( 'Showcase Preset', 'post type singular name', 'product-showcase-woo' ),
            'menu_name'          => _x( 'Product Showcase', 'admin menu', 'product-showcase-woo' ),
            'name_admin_bar'     => _x( 'Showcase Preset', 'add new on admin bar', 'product-showcase-woo' ),
            'add_new'            => _x( 'Add New', 'Showcase Preset', 'product-showcase-woo' ),
            'add_new_item'       => __( 'Add New Showcase Preset', 'product-showcase-woo' ),
            'new_item'           => __( 'New Showcase Preset', 'product-showcase-woo' ),
            'edit_item'          => __( 'Edit Showcase Preset', 'product-showcase-woo' ),
            'view_item'          => __( 'View Showcase Preset', 'product-showcase-woo' ),
            'all_items'          => __( 'Showcase Presets', 'product-showcase-woo' ), // Renamed for clarity in submenu
            'search_items'       => __( 'Search Presets', 'product-showcase-woo' ),
            'parent_item_colon'  => __( 'Parent Presets:', 'product-showcase-woo' ),
            'not_found'          => __( 'No presets found.', 'product-showcase-woo' ),
            'not_found_in_trash' => __( 'No presets found in Trash.', 'product-showcase-woo' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [ 'slug' => 'product-showcase-preset' ],
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 58, // Moved down to avoid conflicts
            'menu_icon'          => 'dashicons-grid-view',
            'supports'           => [ 'title' ],
            'map_meta_cap'       => true, // Explicitly map capabilities
        ];

        register_post_type( 'product_show_preset', $args );
    }
}

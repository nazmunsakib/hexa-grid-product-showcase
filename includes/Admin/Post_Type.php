<?php

namespace HexaGrid\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
            'name'               => _x( 'Hexa Grid Presets', 'post type general name', 'hexa-grid-product-showcase' ),
            'singular_name'      => _x( 'Hexa Grid Preset', 'post type singular name', 'hexa-grid-product-showcase' ),
            'menu_name'          => _x( 'Hexa Grid', 'admin menu', 'hexa-grid-product-showcase' ),
            'name_admin_bar'     => _x( 'Hexa Grid Preset', 'add new on admin bar', 'hexa-grid-product-showcase' ),
            'add_new'            => _x( 'Add New', 'Hexa Grid Preset', 'hexa-grid-product-showcase' ),
            'add_new_item'       => __( 'Add New Preset', 'hexa-grid-product-showcase' ),
            'new_item'           => __( 'New Preset', 'hexa-grid-product-showcase' ),
            'edit_item'          => __( 'Edit Preset', 'hexa-grid-product-showcase' ),
            'view_item'          => __( 'View Preset', 'hexa-grid-product-showcase' ),
            'all_items'          => __( 'Hexa Grid Presets', 'hexa-grid-product-showcase' ), // Renamed for clarity in submenu
            'search_items'       => __( 'Search Presets', 'hexa-grid-product-showcase' ),
            'parent_item_colon'  => __( 'Parent Presets:', 'hexa-grid-product-showcase' ),
            'not_found'          => __( 'No presets found.', 'hexa-grid-product-showcase' ),
            'not_found_in_trash' => __( 'No presets found in Trash.', 'hexa-grid-product-showcase' ),
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

        register_post_type( 'hexagrid_show_preset', $args );
    }
}

<?php

namespace ProductShowcase\Shortcode;

use ProductShowcase\Query\Query_Builder;
use ProductShowcase\Layout\Grid_Layout;
use ProductShowcase\Layout\List_Layout;
use ProductShowcase\Layout\Slider_Layout;

/**
 * Class Shortcode_Handler
 *
 * Registers and processes the [product_showcase] shortcode.
 */
class Shortcode_Handler {

    /**
     * Initialize hooks.
     */
    public function init() {
        add_shortcode( 'product_showcase', [ $this, 'render_shortcode' ] );
    }

    /**
     * Render the shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Rendered HTML.
     */
    public function render_shortcode( $atts ) {
        // 0. Handle Preset ID
        if ( isset( $atts['preset_id'] ) && ! empty( $atts['preset_id'] ) ) {
            $preset_id = intval( $atts['preset_id'] );
            
            // Map meta keys to shortcode attributes
            $meta_map = [
                'layout'      => '_psw_layout_type',
                'style'       => '_psw_layout_style',
                'limit'       => '_psw_query_limit',
                'columns'     => '_psw_columns',
                'category'    => '_psw_categories', // This is an array
                'ids'         => '_psw_include_ids',
                'exclude_ids' => '_psw_exclude_ids',
                'orderby'     => '_psw_orderby',
                'order'       => '_psw_order',
                'theme_color' => '_psw_theme_color',
            ];

            foreach ( $meta_map as $att_key => $meta_key ) {
                $value = get_post_meta( $preset_id, $meta_key, true );
                if ( ! empty( $value ) ) {
                    // Handle array for category logic if passed to shortcode
                    // If shortcode handles category as string (commas), we might need to implode if it's an array
                    if ( is_array( $value ) && 'category' === $att_key ) {
                         // Query Builder handles array directly, but shortcode_atts expects all to be scaler ideally. 
                         // But we can pass array if QueryBuilder handles it.
                         // For simplicity and compatibility, let's keep it as is, but be mindful of shortcode_atts
                    }
                    $atts[ $att_key ] = $value;
                }
            }
        }

        $atts = shortcode_atts( [
            'layout'      => 'grid',
            'style'       => 'layout-1',
            'limit'       => 12,
            'columns'     => 3,
            'category'    => '',
            'ids'         => '',
            'exclude_ids' => '',
            'orderby'     => 'date',
            'order'       => 'DESC',
            'theme_color' => '',
            'preset_id'   => '',
        ], $atts, 'product_showcase' );

        // 1. Build Query
        $query_builder = new Query_Builder();
        $query_builder->set_limit( $atts['limit'] )
                      ->set_order( $atts['orderby'], $atts['order'] );

        if ( ! empty( $atts['category'] ) ) {
            $query_builder->set_category( $atts['category'] );
        }

        if ( ! empty( $atts['ids'] ) ) {
             // If array, it's from meta; if string, it's from shortcode param
            $query_builder->set_ids( $atts['ids'] );
        }
        
        if ( ! empty( $atts['exclude_ids'] ) ) {
            $query_builder->set_exclude_ids( $atts['exclude_ids'] );
        }

        if ( ! empty( $atts['category'] ) ) {
            $query_builder->set_category( $atts['category'] );
        }


        $query = $query_builder->get_query();

        // 2. Generate Unique ID for Scoping
        $unique_id = 'psw-preset-' . $preset_id . '-' . uniqid();
        $atts['wrapper_id'] = $unique_id;

        // 3. Render Layout
        $renderer = null;
        switch ( $atts['layout'] ) {
            case 'list':
                $renderer = new List_Layout();
                break;
            case 'slider':
                $renderer = new Slider_Layout();
                break;
            case 'table':
                $renderer = new \ProductShowcase\Layout\Table_Layout();
                break;
            case 'grid':
            default:
                $renderer = new Grid_Layout();
                break;
        }

        // 3. Render
        if ( $renderer ) {
            return $renderer->render( $query, $atts );
        }

        return '';
    }
}

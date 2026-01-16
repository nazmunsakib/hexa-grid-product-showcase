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
            $layout    = get_post_meta( $preset_id, '_psw_layout_type', true );
            $limit     = get_post_meta( $preset_id, '_psw_query_limit', true );
            $columns   = get_post_meta( $preset_id, '_psw_columns', true );
            $category  = get_post_meta( $preset_id, '_psw_query_category', true );

            // Merge preset settings into attributes if they exist
            if ( $layout ) $atts['layout'] = $layout;
            if ( $limit ) $atts['limit'] = $limit;
            if ( $columns ) $atts['columns'] = $columns;
            if ( $category ) $atts['category'] = $category;
        }

        $atts = shortcode_atts( [
            'layout'    => 'grid', // grid, list, slider
            'limit'     => 12,
            'columns'   => 3, // For grid
            'category'  => '',
            'ids'       => '',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'preset_id' => '',
        ], $atts, 'product_showcase' );

        // 1. Build Query
        $query_builder = new Query_Builder();
        $query_builder->set_limit( $atts['limit'] )
                      ->set_order( $atts['orderby'], $atts['order'] );

        if ( ! empty( $atts['category'] ) ) {
            $query_builder->set_category( $atts['category'] );
        }

        if ( ! empty( $atts['ids'] ) ) {
            $ids = array_map( 'trim', explode( ',', $atts['ids'] ) );
            $query_builder->set_ids( $ids );
        }

        $query = $query_builder->get_query();

        // 2. Select Layout
        $layout_renderer = null;
        switch ( $atts['layout'] ) {
            case 'list':
                $layout_renderer = new List_Layout();
                break;
            case 'slider':
                $layout_renderer = new Slider_Layout();
                break;
            case 'grid':
            default:
                $layout_renderer = new Grid_Layout();
                break;
        }

        // 3. Render
        if ( $layout_renderer ) {
            return $layout_renderer->render( $query, $atts );
        }

        return '';
    }
}

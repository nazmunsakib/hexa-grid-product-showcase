<?php

namespace HexaGrid\Layout;

use HexaGrid\Query\Query_Builder;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Table_Layout
 */
class Table_Layout implements Layout_Interface {

    /**
     * Nonce action for AJAX pagination requests.
     *
     * @var string
     */
    public const AJAX_ACTION = 'hexagrid_table_pagination';

    /**
     * Initialize hooks.
     */
    public function init() {
        add_action( 'wp_ajax_hexagrid_table_pagination', [ $this, 'ajax_render_page' ] );
        add_action( 'wp_ajax_nopriv_hexagrid_table_pagination', [ $this, 'ajax_render_page' ] );
    }

    /**
     * Render the table layout.
     *
     * @param \WP_Query $query The product query.
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render( $query, $atts ) {
        if ( ! $query->have_posts() ) {
            return '<p class="hexagrid-no-products">' . esc_html__( 'No products found.', 'hexa-grid-product-showcase' ) . '</p>';
        }

        $style = isset( $atts['style'] ) ? sanitize_file_name( $atts['style'] ) : 'product-table-1';
        $columns = isset( $atts['columns'] ) ? intval( $atts['columns'] ) : 3;

        $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/table/' . $style . '.php';

        if ( ! file_exists( $template_path ) ) {
              $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/table/product-table-1.php';
        }

        ob_start();
        echo wp_kses( \HexaGrid\Assets\Dynamic_Styles::generate( $atts, $atts['wrapper_id'] ), array( 'style' => array() ) );
        $container_classes = sprintf(
            'hexagrid-layout-container hexagrid-layout-table hexagrid-product-grid-container hexagrid-%s',
            esc_attr( $style )
        );
        echo '<div id="' . esc_attr( $atts['wrapper_id'] ) . '" class="' . $container_classes . '" data-table-atts="' . esc_attr( wp_json_encode( $atts ) ) . '">';
        include $template_path;
        echo '</div>';
        return ob_get_clean();
    }

    /**
     * AJAX handler: render a single page of the table (body + footer).
     *
     * @return void
     */
    public function ajax_render_page() {
        check_ajax_referer( self::AJAX_ACTION, 'nonce' );

        $atts_raw = isset( $_POST['atts'] ) ? wp_unslash( $_POST['atts'] ) : '';
        $atts     = json_decode( $atts_raw, true );

        if ( ! is_array( $atts ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid request.', 'hexa-grid-product-showcase' ) ] );
        }

        $paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

        $style = isset( $atts['style'] ) ? sanitize_file_name( $atts['style'] ) : 'product-table-1';
        $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/table/' . $style . '.php';

        if ( ! file_exists( $template_path ) ) {
            $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/table/product-table-1.php';
        }

        $query_builder = new Query_Builder();
        $query_builder->set_limit( isset( $atts['limit'] ) ? $atts['limit'] : 12 )
                     ->set_order( isset( $atts['orderby'] ) ? $atts['orderby'] : '', isset( $atts['order'] ) ? $atts['order'] : '' )
                     ->set_paged( $paged );

        if ( ! empty( $atts['ids'] ) ) {
            $query_builder->set_ids( $atts['ids'] );
        }

        if ( ! empty( $atts['exclude_ids'] ) ) {
            $query_builder->set_exclude_ids( $atts['exclude_ids'] );
        }

        $query   = $query_builder->get_query();
        $partial = true;

        ob_start();
        include $template_path;
        $html = ob_get_clean();

        wp_send_json_success( [ 'html' => $html ] );
    }
}
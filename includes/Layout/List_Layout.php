<?php

namespace ProductShowcase\Layout;

/**
 * Class List_Layout
 */
class List_Layout implements Layout_Interface {

    /**
     * Render the list layout.
     *
     * @param \WP_Query $query The product query.
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render( $query, $atts ) {
        if ( ! $query->have_posts() ) {
            return '<p class="ps-no-products">No products found.</p>';
        }

        $style = isset( $atts['style'] ) ? sanitize_file_name( $atts['style'] ) : 'layout-1';
        $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/list/' . $style . '.php';

        if ( ! file_exists( $template_path ) ) {
             $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/list/layout-1.php';
        }
        
        ob_start();
        include $template_path;
        return ob_get_clean();
    }
}

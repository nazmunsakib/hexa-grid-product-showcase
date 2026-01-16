<?php

namespace ProductShowcase\Layout;

/**
 * Class Slider_Layout
 */
class Slider_Layout implements Layout_Interface {

    /**
     * Render the slider layout.
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
        $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/slider/' . $style . '.php';

        if ( ! file_exists( $template_path ) ) {
             $template_path = dirname( dirname( dirname( __FILE__ ) ) ) . '/templates/slider/layout-1.php';
        }
        
        ob_start();
        echo \ProductShowcase\Assets\Dynamic_Styles::generate( $atts, $atts['wrapper_id'] );
        echo '<div id="' . esc_attr( $atts['wrapper_id'] ) . '" class="psw-layout-container">';
        include $template_path;
        echo '</div>';
        return ob_get_clean();
    }
}

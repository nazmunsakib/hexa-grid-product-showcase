<?php

namespace HexaGrid\Layout;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Interface Layout_Interface
 *
 * Contract for product layouts.
 */
interface Layout_Interface {

    /**
     * Render the layout.
     *
     * @param \WP_Query $query The product query.
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render( $query, $atts );
}

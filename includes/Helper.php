<?php
namespace HexaGrid;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Helper Class
 */
class Helper {

    public static function get_plugin_path() {
        return HEXAGRID_PATH;
    }

    public static function get_plugin_url() {
        return HEXAGRID_URL;
    }

    /**
     * Allowed HTML for responsive product images
     */
    public static function allowed_image_html() {
        return array(
            'img' => array(
                'src'       => true,
                'srcset'    => true,
                'sizes'     => true,
                'alt'       => true,
                'class'     => true,
                'width'     => true,
                'height'    => true,
                'loading'   => true,
                'decoding'  => true,
                'style'     => true,
            ),
            'picture' => array(),
            'source'  => array(
                'srcset' => true,
                'sizes'  => true,
                'type'   => true,
                'media'  => true,
            ),
        );
    }

    /**
     * Allowed HTML for SVG icons inside buttons.
     *
     * Merges the default post allowed tags with SVG tags so icons
     * survive wp_kses() sanitization.
     */
    public static function allowed_svg_html() {
        $svg = array(
            'svg'    => array(
                'xmlns'           => true,
                'viewbox'         => true,
                'viewBox'         => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
                'width'           => true,
                'height'          => true,
                'role'            => true,
                'aria-hidden'     => true,
                'class'           => true,
            ),
            'path'   => array(
                'd'               => true,
                'fill'            => true,
                'stroke'          => true,
                'stroke-width'    => true,
                'stroke-linecap'  => true,
                'stroke-linejoin' => true,
                'class'           => true,
            ),
            'circle' => array(
                'cx' => true,
                'cy' => true,
                'r'  => true,
            ),
            'rect'   => array(
                'x'      => true,
                'y'      => true,
                'width'  => true,
                'height' => true,
                'rx'     => true,
            ),
            'g'      => array(
                'class' => true,
            ),
        );

        return array_merge( wp_kses_allowed_html( 'post' ), $svg );
    }

    /**
     * Product Image with link
     */
    public static function get_product_image( $product, $size = 'woocommerce_thumbnail', $attr = array() ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $image = $product->get_image( $size, $attr );

        if ( ! $image ) {
            return '';
        }

        $html  = '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">';
        $html .= wp_kses( $image, self::allowed_image_html() );
        $html .= '</a>';

        return $html;
    }

    /**
     * Product Title
     */
    public static function get_product_title( $product, $length = 0, $trim_type = 'words' ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $title = $product->get_name();

        if ( $length > 0 ) {
            if ( 'chars' === $trim_type ) {
                $title = mb_strimwidth( $title, 0, $length, '...' );
            } else {
                $title = wp_trim_words( $title, $length, '...' );
            }
        }

        $title = '<h3 class="hexagrid-product-title">' .
            '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . wp_kses_post( $title ) . '</a>' .
            '</h3>';

        return $title;
    }

    /**
     * Product Price
     */
    public static function get_product_price( $product ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $price = '<div class="hexagrid-product-price">' . wp_kses_post( $product->get_price_html() ) . '</div>';

        return $price;
    }

    /**
     * Product Excerpt
     */
    public static function get_product_excerpt( $product, $length = 10, $trim_type = 'words' ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $excerpt = wp_strip_all_tags( $product->get_short_description() );

        if ( $length > 0 ) {
            if ( 'chars' === $trim_type ) {
                $excerpt = mb_strimwidth( $excerpt, 0, $length, '...' );
            } else {
                $excerpt = wp_trim_words( $excerpt, $length, '...' );
            }
        }

        $excerpt = '<div class="hexagrid-product-short-desc">' .
            '<p>' . wp_kses_post( $excerpt ) . '</p>' .
            '</div>';

        return $excerpt;
    }

    /**
     * Get Product Rating HTML
     *
     * @param \WC_Product $product
     * @param array $args Optional parameters:
     *                    - 'show_average' (bool) Display average number (default: true)
     *                    - 'show_count' (bool) Display review count (default: true)
     *
     * @return string HTML output
     */
    public static function get_product_rating( $product, $args = array() ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $defaults = array(
            'show_average' => true,
            'show_count'   => true,
        );

        $args = wp_parse_args( $args, $defaults );

        $average = (float) $product->get_average_rating();
        $review_count = (int) $product->get_review_count();

        if ( $average <= 0 && ! $args['show_count'] ) {
            return ''; // Nothing to show
        }

        $html  = '<div class="hexagrid-product-rating">';

        if ( $average > 0 ) {
            if ( function_exists( 'wc_get_rating_html' ) ) {
                $html .= wp_kses_post( wc_get_rating_html( $average ) );
            } else {
                $html .= '<div class="star-rating" role="img" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $average ) ) . '"><span style="width:' . ( ( $average / 5 ) * 100 ) . '%">' . sprintf( __( 'Rated %s out of 5', 'woocommerce' ), '<strong>' . esc_html( $average ) . '</strong>' ) . '</span></div>';
            }

            if ( $args['show_average'] ) {
                $html .= '<span class="hexagrid-rating-average">' . esc_html( number_format( $average, 1 ) ) . '</span>';
            }

            if ( $args['show_count'] && $review_count > 0 ) {
                $html .= '<span class="hexagrid-rating-separator">&bull;</span>';
                $html .= '<span class="hexagrid-review-count">' . esc_html( sprintf( _n( '%d review', '%d reviews', $review_count, 'hexa-grid-product-showcase' ), $review_count ) ) . '</span>';
            }
        } elseif ( $args['show_count'] && $review_count > 0 ) {
            $html .= '<span class="hexagrid-review-count">' . esc_html( sprintf( _n( '%d review', '%d reviews', $review_count, 'hexa-grid-product-showcase' ), $review_count ) ) . '</span>';
        }

        $html .= '</div>';

        return $html;
    }


    /**
     * Add to Cart Button (WooCommerce Compatible)
     */
    public static function get_add_to_cart_button( $product, $style = 'icon' ) {

        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $text = $product->add_to_cart_text();

        // Choose the right icon for the product type.
        $icon_name = 'cart';
        if ( in_array( $product->get_type(), [ 'variable', 'grouped', 'external' ], true ) ) {
            $icon_name = 'arrow-right';
        }

        // Icon wrapper with primary icon + success checkmark icon.
        $icon = '<span class="hexagrid-btn-inner" aria-hidden="true">' .
            '<span class="hexagrid-btn-icon hexagrid-btn-icon-default">' . self::get_svg_icon( $icon_name ) . '</span>' .
            '<span class="hexagrid-btn-icon hexagrid-btn-icon-success">' . self::get_svg_icon( 'check' ) . '</span>' .
        '</span>';

        // Decide button content
        switch ( $style ) {
            case 'text':
                $content = esc_html( $text );
                break;

            case 'both':
                $content = $icon . ' ' . esc_html( $text );
                break;

            case 'icon':
            default:
                $content = $icon;
                break;
        }

        $classes = array(
            'button',
            'hexagrid-btn',
            'hexagrid-btn-' . esc_attr( $style ),
            'hexagrid-add-to-cart',
            'product_type_' . $product->get_type(),
        );

        if ( $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ) {
            $classes[] = 'add_to_cart_button';
            $classes[] = 'ajax_add_to_cart';
        }

        $defaults = array(
            'quantity'   => 1,
            'class'      => implode( ' ', $classes ),
            'attributes' => array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => wp_strip_all_tags( $product->add_to_cart_description() ),
                'rel'              => 'nofollow',
            ),
        );

        $args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

        $button = sprintf(
            '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $args['quantity'] ),
            esc_attr( $args['class'] ),
            function_exists( 'wc_implode_html_attributes' ) ? \wc_implode_html_attributes( array_map( 'esc_attr', $args['attributes'] ) ) : '',
            $content
        );

        $button = apply_filters( 'woocommerce_loop_add_to_cart_link', $button, $product, $args );

        return '<div class="hexagrid-product-cart-btn hexagrid-cart-btn-type-' . esc_attr($style).'">' . $button . '</div>';
    }


    public static function get_product_badge( $product ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        // Out of stock badge (priority over sale)
        if ( ! $product->is_in_stock() ) {
            return '<span class="hexagrid-badge hexagrid-outofstock-badge">' . esc_html__( 'Out of Stock', 'hexa-grid-product-showcase' ) . '</span>';
        }

        // Sale badge
        if ( $product->is_on_sale() ) {
            return '<span class="hexagrid-badge hexagrid-sale-badge">' . esc_html__( 'Sale!', 'hexa-grid-product-showcase' ) . '</span>';
        }

        return '';
    }


    /**
     * Product Categories
     */
    public static function get_product_categories( $product ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        if ( function_exists( 'wc_get_product_category_list' ) ) {
            $categories = wc_get_product_category_list( $product->get_id(), ', ' );
        } else {
            $categories = get_the_term_list( $product->get_id(), 'product_cat', '', ', ' );
        }
            if ( $categories ) {
            return '<div class="hexagrid-product-category">' . wp_kses_post( $categories ) . '</div>';
        }
    }

    /**
     * Product Tags
     */
    public static function get_product_tags( $product ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $tags = $product->get_tag_ids();
        if ( empty( $tags ) ) return '';

        $html = '<div class="hg-product-tags">';

        foreach ( $tags as $tag_id ) {
            $term = get_term( $tag_id );
            if ( $term && ! is_wp_error( $term ) ) {
                $html .= '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a> ';
            }
        }

        $html .= '</div>';
        return $html;
    }

    public static function get_product_sku( $product ) {
        return ( $product instanceof \WC_Product ) ? esc_html( $product->get_sku() ) : '';
    }

    public static function get_product_stock_status( $product ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }
        
        $status = $product->get_stock_status();
        
        if ( function_exists( 'wc_get_stock_status_label' ) ) {
            return esc_html( wc_get_stock_status_label( $status ) );
        }
        
        // Fallback for common stock statuses if function is missing
        $statuses = array(
            'instock'     => __( 'In stock', 'woocommerce' ),
            'outofstock'  => __( 'Out of stock', 'woocommerce' ),
            'onbackorder' => __( 'On backorder', 'woocommerce' ),
        );
        
        $label = isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
        return esc_html( $label );
    }

    public static function get_product_stock_html( $product ) {
        if ( ! $product instanceof \WC_Product ) return '';

        return '<div class="hg-product-stock">' .
               esc_html( $product->is_in_stock() ? __( 'In Stock', 'hexa-grid-product-showcase' ) : __( 'Out of Stock', 'hexa-grid-product-showcase' ) ) .
               '</div>';
    }

    /**
     * Safe Product Meta
     */
    public static function get_product_meta_html( $product, $meta_key ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $meta = $product->get_meta( $meta_key );

        if ( empty( $meta ) ) return '';

        return '<div class="hg-product-meta">' . esc_html( $meta ) . '</div>';
    }

    /**
     * Get SVG Icon
     */
    public static function get_svg_icon( $name ) {
        $path = HEXAGRID_PATH . 'assets/icons/' . $name . '.svg';
        if ( file_exists( $path ) ) {
            return '<span class="hexagrid-btn-icon" aria-hidden="true">' . file_get_contents( $path ) . '</span>';
        }
        return '';
    }

    /**
     * Get Wishlist Button HTML
     *
     * @param \WC_Product $product Product object.
     * @param array       $args   Optional arguments:
     *                            - button_class (string) Wrapper class. Default 'hexagrid-wishlist-btn'.
     *                            - active_class (string) Class added when product is in wishlist. Default 'is-active'.
     *                            - loading_class (string) Class added during AJAX. Default 'is-loading'.
     * @return string HTML button.
     */
    public static function get_wishlist_button( $product, $args = array() ) {
        if ( ! $product instanceof \WC_Product ) {
            return '';
        }

        $defaults = array(
            'button_class'  => 'hexagrid-wishlist-btn',
            'active_class'  => 'is-active',
            'loading_class' => 'is-loading',
        );
        $args = wp_parse_args( $args, $defaults );

        $product_id = $product->get_id();

        $wishlist_handler = new \HexaGrid\Wishlist\Wishlist_Handler();
        $is_active        = $wishlist_handler->is_in_wishlist( $product_id );

        $classes = array_filter( array(
            $args['button_class'],
            $is_active ? $args['active_class'] : '',
        ) );

        $aria_label = $is_active
            ? __( 'Remove from wishlist', 'hexa-grid-product-showcase' )
            : __( 'Add to wishlist', 'hexa-grid-product-showcase' );

        $html  = '<button type="button" class="' . esc_attr( implode( ' ', $classes ) ) . '" ';
        $html .= 'data-product-id="' . esc_attr( $product_id ) . '" ';
        $html .= 'aria-label="' . esc_attr( $aria_label ) . '">';
        $html .= self::get_wishlist_icon();
        $html .= '</button>';

        return $html;
    }

    /**
     * Get Wishlist Heart Icon SVG
     *
     * @return string SVG markup.
     */
    public static function get_wishlist_icon() {
        return self::get_svg_icon( 'heart' );
    }

    /**
     * Check if a product is in the current wishlist.
     *
     * @param int $product_id Product ID.
     * @return bool
     */
    public static function is_product_in_wishlist( $product_id ) {
        $wishlist_handler = new \HexaGrid\Wishlist\Wishlist_Handler();
        return $wishlist_handler->is_in_wishlist( $product_id );
    }
}

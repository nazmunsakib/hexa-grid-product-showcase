<?php
/**
 * List Layout Template - Style 3 (Two Column)
 *
 * Identical row design to the Premium catalog list (style 2) but arranged
 * in a two-column grid on desktop, collapsing to a single column on
 * tablet and mobile.
 *
 * @var \WP_Query $query
 * @var string    $style
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hexagrid-layout-list hexagrid-list-2 hexagrid-list-3" role="list">
    <?php if ( $query->have_posts() ) : update_post_thumbnail_cache( $query ); ?>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>

            <?php
                if ( ! function_exists( 'wc_get_product' ) ) {
                    continue;
                }
                $product = wc_get_product( get_the_ID() );
                if ( ! $product ) {
                    continue;
                }

                $product_image   = \HexaGrid\Helper::get_product_image( $product, 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) );
                $product_title   = \HexaGrid\Helper::get_product_title( $product );
                $product_rating  = \HexaGrid\Helper::get_product_rating( $product, array( 'show_average' => false ) );
                $product_excerpt = \HexaGrid\Helper::get_product_excerpt( $product, 0 );
                $product_price   = \HexaGrid\Helper::get_product_price( $product );
                $product_button  = \HexaGrid\Helper::get_add_to_cart_button( $product, 'text' );

                $in_stock     = $product->is_in_stock();
                $stock_status = $product->get_stock_status();

                // Metadata: stock status · SKU · first category (only what exists).
                $meta_parts = array();

                if ( $stock_status ) {
                    $meta_parts[] = '<span class="hexagrid-list2-stock hexagrid-list2-stock-' . esc_attr( $stock_status ) . '">' . esc_html( \HexaGrid\Helper::get_product_stock_status( $product ) ) . '</span>';
                }

                $sku = $product->get_sku();
                if ( $sku ) {
                    $meta_parts[] = '<span class="hexagrid-list2-sku">' . esc_html( sprintf( /* translators: %s: SKU value */ __( 'SKU: %s', 'hexa-grid-product-showcase' ), $sku ) ) . '</span>';
                }

                $category_link = '';
                $terms         = get_the_terms( $product->get_id(), 'product_cat' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $primary_term  = reset( $terms );
                    $category_link = '<a href="' . esc_url( get_term_link( $primary_term ) ) . '">' . esc_html( $primary_term->name ) . '</a>';
                }
                if ( $category_link ) {
                    $meta_parts[] = '<span class="hexagrid-list2-category">' . $category_link . '</span>';
                }

                $product_meta = '';
                if ( $meta_parts ) {
                    $meta_sep     = '<span class="hexagrid-list2-meta-sep" aria-hidden="true">&middot;</span>';
                    $product_meta = '<div class="hexagrid-list2-meta">' . implode( $meta_sep, $meta_parts ) . '</div>';
                }

                // Sale badge: percentage for simple products, "Sale!" fallback otherwise.
                $sale_badge = '';
                if ( $product->is_on_sale() ) {
                    if ( $product->is_type( 'simple' ) ) {
                        $regular_price = (float) $product->get_regular_price();
                        $sale_price    = (float) $product->get_sale_price();
                        if ( $regular_price > 0 && $sale_price > 0 ) {
                            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                            if ( $percentage > 0 ) {
                                $sale_badge = '-' . absint( $percentage ) . '%';
                            }
                        }
                    }
                    if ( empty( $sale_badge ) ) {
                        $sale_badge = __( 'Sale!', 'hexa-grid-product-showcase' );
                    }
                }

                $image_placeholder = '<span class="hexagrid-list2-image-placeholder" aria-hidden="true">' .
                    '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' .
                        '<rect x="3" y="3" width="18" height="18" rx="2"></rect>' .
                        '<circle cx="8.5" cy="8.5" r="1.5"></circle>' .
                        '<path d="m21 15-5-5L5 21"></path>' .
                    '</svg>' .
                '</span>';
            ?>

            <article <?php post_class( 'hexagrid-product' . ( $in_stock ? '' : ' hexagrid-product-outofstock' ) ); ?> role="listitem">
                <div class="hexagrid-list2-row">

                    <div class="hexagrid-list2-image">
                        <?php
                            if ( $product_image ) {
                                echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- image is escaped inside Helper::get_product_image().
                            } else {
                                echo wp_kses( $image_placeholder, \HexaGrid\Helper::allowed_svg_html() );
                            }
                            if ( $sale_badge ) {
                                echo '<span class="hexagrid-badge hexagrid-sale-badge">' . esc_html( $sale_badge ) . '</span>';
                            }
                        ?>
                    </div>

                    <div class="hexagrid-list2-main">
                        <?php
                            if ( $product_title ) {
                                echo wp_kses_post( $product_title );
                            }
                            if ( $product_rating ) {
                                echo wp_kses_post( $product_rating );
                            }
                            if ( $product_excerpt ) {
                                echo wp_kses_post( $product_excerpt );
                            }
                            if ( $product_meta ) {
                                echo wp_kses_post( $product_meta );
                            }
                        ?>
                    </div>

                    <?php if ( $product_price || $product_button ) : ?>
                        <div class="hexagrid-list2-aside">
                            <?php
                                if ( $product_price ) {
                                    echo wp_kses_post( $product_price );
                                }
                                if ( $product_button ) {
                                    echo wp_kses( $product_button, \HexaGrid\Helper::allowed_svg_html() );
                                }
                            ?>
                        </div>
                    <?php endif; ?>

                </div>
            </article>

        <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
        <p class="hexagrid-no-products"><?php esc_html_e( 'No products found.', 'hexa-grid-product-showcase' ); ?></p>
    <?php endif; ?>
</div>
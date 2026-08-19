<?php
/**
 * List Layout Template - Style 1 (Minimal)
 *
 * @var \WP_Query $query
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hexagrid-layout-list" role="list">
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
                $product_badge   = \HexaGrid\Helper::get_product_badge( $product );
                $product_cats    = \HexaGrid\Helper::get_product_categories( $product );
                $product_title   = \HexaGrid\Helper::get_product_title( $product, 0 );
                $product_rating  = \HexaGrid\Helper::get_product_rating( $product );
                $product_price   = \HexaGrid\Helper::get_product_price( $product );
                $product_excerpt = \HexaGrid\Helper::get_product_excerpt( $product, 20, 'words' );
                $product_button  = \HexaGrid\Helper::get_add_to_cart_button( $product, 'text' );
            ?>

            <article <?php post_class( 'hexagrid-product' ); ?> role="listitem">
                <div class="hexagrid-product-wrapper">

                    <?php if ( $product_image || $product_badge ) : ?>
                        <div class="hexagrid-product-image-area">
                            <?php 
                                if ( $product_image ) {
                                    echo wp_kses_post( $product_image );
                                }
                                if ( $product_badge ) {
                                    echo wp_kses_post( $product_badge );
                                }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $product_cats || $product_title || $product_rating || $product_price || $product_excerpt || $product_button ) : ?>
                        <div class="hexagrid-product-content-area">
                            <?php if ( $product_cats || $product_title ) : ?>
                                <div class="hexagrid-product-content-header">
                                    <?php 
                                        if ( $product_cats ) {
                                            echo wp_kses_post( $product_cats );
                                        }
                                        if ( $product_title ) {
                                            echo wp_kses_post( $product_title );
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php 
                                if ( $product_rating ) {
                                    echo wp_kses_post( $product_rating );
                                }
                                if ( $product_price ) {
                                    echo wp_kses_post( $product_price );
                                }
                                if ( $product_excerpt ) {
                                    echo wp_kses_post( $product_excerpt );
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

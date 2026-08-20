<?php
/**
 * Slider Layout Template - Style 2 (Classic)
 *
 * Mirrors the Grid Layout Style 2 design inside a Swiper carousel:
 * - Product image with badge on a soft background.
 * - Centered title, category, price and add-to-cart button below.
 *
 * @var \WP_Query $query
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hexagrid-layout-slider hexagrid-<?php echo esc_attr( $style ); ?> hexagrid-product-grid-2 swiper" role="list">
    <div class="swiper-wrapper">
        <?php if ( $query->have_posts() ) : update_post_thumbnail_cache( $query ); ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                <?php
                    global $product;
                    if ( ! is_object( $product ) ) {
                        $product = \wc_get_product( get_the_ID() );
                    }
                    if ( ! $product ) {
                        continue;
                    }
                ?>

                <article <?php post_class( 'swiper-slide hexagrid-product' ); ?> role="listitem">
                    <div class="hexagrid-product-wrapper">

                        <div class="hexagrid-product-image-area">
                            <?php echo \HexaGrid\Helper::get_product_image( $product, 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>
                            <?php echo wp_kses_post( \HexaGrid\Helper::get_product_badge( $product ) ); ?>
                        </div>

                        <div class="hexagrid-product-content-area">
                            <h3 class="hexagrid-product-title">
                                <a href="<?php the_permalink(); ?>"><?php echo wp_kses_post( get_the_title() ); ?></a>
                            </h3>

                            <div class="hexagrid-product-category">
                                <?php echo wp_kses_post( \wc_get_product_category_list( $product->get_id(), ', ' ) ); ?>
                            </div>

                            <?php $product_price = $product->get_price_html(); ?>
                            <?php if ( $product_price ) : ?>
                                <div class="hexagrid-product-price">
                                    <?php echo wp_kses_post( $product_price ); ?>
                                </div>
                            <?php endif; ?>

                            <div class="hexagrid-add-btn">
                                <?php \woocommerce_template_loop_add_to_cart(); ?>
                            </div>
                        </div>

                    </div>
                </article>

            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p class="hexagrid-no-products"><?php esc_html_e( 'No products found.', 'hexa-grid-product-showcase' ); ?></p>
        <?php endif; ?>
    </div>

    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
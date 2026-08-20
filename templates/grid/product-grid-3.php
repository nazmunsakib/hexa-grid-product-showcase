<?php
/**
 * Grid Layout Template - Style 3 (Minimal Cosmetic Card)
 *
 * Features:
 * - Large product image on a soft gray background.
 * - Wishlist (heart) button in the top-right corner.
 * - Percentage sale badge in the top-left corner.
 * - Title + category on the left and price on the right below the image.
 *
 * @var \WP_Query $query
 * @var int $columns
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hexagrid-layout-grid hexagrid-<?php echo esc_attr( $style ); ?> hexagrid-columns-<?php echo esc_attr( $columns ); ?>" role="list">
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

                // Calculate sale percentage for simple products.
                $sale_badge = '';
                if ( $product->is_on_sale() ) {
                    if ( $product->is_type( 'simple' ) ) {
                        $regular_price = (float) $product->get_regular_price();
                        $sale_price    = (float) $product->get_sale_price();
                        if ( $regular_price > 0 ) {
                            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                            $sale_badge = '-' . absint( $percentage ) . '%';
                        }
                    }

                    if ( empty( $sale_badge ) ) {
                        $sale_badge = __( 'Sale!', 'hexa-grid-product-showcase' );
                    }
                }
            ?>

            <article <?php post_class( 'hexagrid-product' ); ?> role="listitem">
                <div class="hexagrid-product-wrapper">

                    <div class="hexagrid-product-image-area">
                        <?php echo \HexaGrid\Helper::get_product_image( $product, 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ); ?>

                        <?php if ( $sale_badge ) : ?>
                            <span class="hexagrid-badge hexagrid-sale-badge"><?php echo esc_html( $sale_badge ); ?></span>
                        <?php elseif ( ! $product->is_in_stock() ) : ?>
                            <span class="hexagrid-badge hexagrid-outofstock-badge"><?php esc_html_e( 'Out of Stock', 'hexa-grid-product-showcase' ); ?></span>
                        <?php endif; ?>

                        <?php echo wp_kses( \HexaGrid\Helper::get_wishlist_button( $product ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                    </div>

                    <div class="hexagrid-product-content-area">
                        <div class="hexagrid-product-info-main">
                            <?php
                                echo wp_kses_post( \HexaGrid\Helper::get_product_title( $product, 10, 'words' ) );
                                echo wp_kses_post( \HexaGrid\Helper::get_product_categories( $product ) );
                            ?>
                        </div>

                        <div class="hexagrid-product-info-actions">
                            <?php $product_price = \HexaGrid\Helper::get_product_price( $product ); ?>
                            <?php if ( $product_price ) : ?>
                                <div class="hexagrid-product-info-price">
                                    <?php echo wp_kses_post( $product_price ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="hexagrid-product-info-cart">
                                <?php echo wp_kses( \HexaGrid\Helper::get_add_to_cart_button( $product, 'icon' ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </article>

        <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
        <p class="hexagrid-no-products"><?php esc_html_e( 'No products found.', 'hexa-grid-product-showcase' ); ?></p>
    <?php endif; ?>
</div>

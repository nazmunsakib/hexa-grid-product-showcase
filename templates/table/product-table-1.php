<?php
/**
 * Table Layout Template - Style 1
 *
 * @var \WP_Query $query
 * @var int $columns
 * @var string $style
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="hexagrid-layout-table hexagrid-<?php echo esc_attr( $style ); ?>" role="table">
    <div class="hexagrid-table-responsive">
        <table class="hexagrid-product-table">
            <thead role="rowgroup">
                <tr role="row">
                    <th class="hexagrid-th-product" role="columnheader"><?php esc_html_e( 'Product', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-category" role="columnheader"><?php esc_html_e( 'Category', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-price" role="columnheader"><?php esc_html_e( 'Price', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-rating" role="columnheader"><?php esc_html_e( 'Rating', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-action" role="columnheader"><?php esc_html_e( 'Actions', 'hexa-grid-product-showcase' ); ?></th>
                </tr>
            </thead>
            <tbody role="rowgroup">
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
                            $product_excerpt = \HexaGrid\Helper::get_product_excerpt( $product, 10 );
                            $product_cats    = \HexaGrid\Helper::get_product_categories( $product );
                            $product_price   = \HexaGrid\Helper::get_product_price( $product );
                            $product_rating  = \HexaGrid\Helper::get_product_rating( $product, array( 'show_average' => false, 'show_count' => false ) );

                            $stock_status = $product->get_stock_status();
                            $stock_label  = \HexaGrid\Helper::get_product_stock_status( $product );

                            // Check for low stock
                            if ( $product->is_in_stock() && $product->managing_stock() ) {
                                $stock_qty = $product->get_stock_quantity();
                                $low_stock_amount = (int) get_option( 'woocommerce_notify_low_stock_amount', 2 );
                                if ( null !== $stock_qty && $stock_qty <= $low_stock_amount ) {
                                    $stock_status = 'lowstock';
                                    $stock_label  = __( 'Low Stock', 'hexa-grid-product-showcase' );
                                }
                            }
                        ?>
                        <tr class="hexagrid-product-row hexagrid-product" role="row">
                            <td class="hexagrid-td-product" data-label="<?php esc_attr_e( 'Product', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-product-info-wrapper">
                                    <div class="hexagrid-product-image">
                                        <?php echo wp_kses( $product_image, \HexaGrid\Helper::allowed_image_html() ); ?>
                                        <span class="hexagrid-status-pill status-<?php echo esc_attr( $stock_status ); ?>">
                                            <?php echo esc_html( $stock_label ); ?>
                                        </span>
                                    </div>
                                    <div class="hexagrid-product-details">
                                        <?php echo wp_kses_post( $product_title ); ?>
                                        <?php echo wp_kses_post( $product_excerpt ); ?>
                                    </div>
                                </div>
                            </td>
                            <td class="hexagrid-td-category" data-label="<?php esc_attr_e( 'Category', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php echo wp_kses_post( $product_cats ); ?>
                            </td>
                            <td class="hexagrid-td-price" data-label="<?php esc_attr_e( 'Price', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php if ( $product_price ) : ?>
                                    <?php echo wp_kses_post( $product_price ); ?>
                                <?php endif; ?>
                            </td>
                            <td class="hexagrid-td-rating" data-label="<?php esc_attr_e( 'Rating', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php echo wp_kses_post( $product_rating ); ?>
                            </td>
                            <td class="hexagrid-td-action" data-label="<?php esc_attr_e( 'Actions', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-action-buttons">
                                    <div class="hexagrid-quantity-stepper">
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-minus">-</button>
                                        <input type="number" class="hexagrid-qty-input" value="1" min="1">
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-plus">+</button>
                                    </div>
                                    <?php echo wp_kses( \HexaGrid\Helper::get_add_to_cart_button( $product, 'icon' ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <tr role="row">
                        <td colspan="5" role="cell">
                            <p class="hexagrid-no-products"><?php esc_html_e( 'No products found.', 'hexa-grid-product-showcase' ); ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

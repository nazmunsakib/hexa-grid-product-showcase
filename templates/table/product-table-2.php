<?php
/**
 * Table Layout Template - Style 2 (Modern)
 *
 * Columns: Product, Category, Price, Stock Status, Actions.
 *
 * @var \WP_Query $query
 * @var string $style
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$palette_classes = array( 'blue', 'yellow', 'green', 'purple', 'pink', 'orange', 'cyan', 'red' );
?>
<div class="hexagrid-layout-table hexagrid-<?php echo esc_attr( $style ); ?>" role="table">
    <div class="hexagrid-table-responsive">
        <table class="hexagrid-product-table">
            <thead role="rowgroup">
                <tr role="row">
                    <th class="hexagrid-th-product" role="columnheader"><?php esc_html_e( 'Product', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-category" role="columnheader"><?php esc_html_e( 'Category', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-price" role="columnheader"><?php esc_html_e( 'Price', 'hexa-grid-product-showcase' ); ?></th>
                    <th class="hexagrid-th-stock" role="columnheader"><?php esc_html_e( 'Stock Status', 'hexa-grid-product-showcase' ); ?></th>
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

                            $product_image = \HexaGrid\Helper::get_product_image( $product, 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) );
                            $product_title = \HexaGrid\Helper::get_product_title( $product );
                            $product_sku   = $product->get_sku();
                            $product_price = \HexaGrid\Helper::get_product_price( $product );

                            // Stock status.
                            $stock_status = $product->get_stock_status();
                            $stock_label  = \HexaGrid\Helper::get_product_stock_status( $product );
                            if ( $product->is_in_stock() && $product->managing_stock() ) {
                                $stock_qty = $product->get_stock_quantity();
                                $low_stock_amount = (int) get_option( 'woocommerce_notify_low_stock_amount', 2 );
                                if ( null !== $stock_qty && $stock_qty <= $low_stock_amount ) {
                                    $stock_status = 'lowstock';
                                    $stock_label  = __( 'Low Stock', 'hexa-grid-product-showcase' );
                                }
                            }

                            // Discount badge.
                            $discount_badge = '';
                            if ( $product->is_on_sale() && $product->is_type( 'simple' ) ) {
                                $regular = (float) $product->get_regular_price();
                                $sale    = (float) $product->get_sale_price();
                                if ( $regular > 0 ) {
                                    $discount_badge = '-' . absint( round( ( ( $regular - $sale ) / $regular ) * 100 ) ) . '%';
                                }
                            }

                            // Image bottom badge.
                            $image_badge      = '';
                            $image_badge_type = '';
                            if ( $product->is_on_sale() ) {
                                $image_badge      = __( 'SALE', 'hexa-grid-product-showcase' );
                                $image_badge_type = 'sale';
                            } elseif ( $product->is_featured() ) {
                                $image_badge      = __( 'FEATURED', 'hexa-grid-product-showcase' );
                                $image_badge_type = 'featured';
                            }
                        ?>
                        <tr class="hexagrid-product-row hexagrid-product" role="row">
                            <td class="hexagrid-td-product" data-label="<?php esc_attr_e( 'Product', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-product-info-wrapper">
                                    <div class="hexagrid-product-image">
                                        <?php echo wp_kses( $product_image, \HexaGrid\Helper::allowed_image_html() ); ?>
                                        <?php if ( $image_badge ) : ?>
                                            <span class="hexagrid-image-badge hexagrid-image-badge-<?php echo esc_attr( $image_badge_type ); ?>">
                                                <?php echo esc_html( $image_badge ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hexagrid-product-details">
                                        <?php echo wp_kses_post( $product_title ); ?>
                                        <?php if ( $product_sku ) : ?>
                                            <span class="hexagrid-product-sku"><?php echo esc_html( $product_sku ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="hexagrid-td-category" data-label="<?php esc_attr_e( 'Category', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php
                                    $terms = get_the_terms( $product->get_id(), 'product_cat' );
                                    if ( $terms && ! is_wp_error( $terms ) ) {
                                        foreach ( $terms as $term ) {
                                            $color_class = $palette_classes[ abs( (int) $term->term_id ) % count( $palette_classes ) ];
                                            echo '<span class="hexagrid-category-pill hexagrid-category-pill-' . esc_attr( $color_class ) . '">' . esc_html( $term->name ) . '</span>';
                                        }
                                    }
                                ?>
                            </td>
                            <td class="hexagrid-td-price" data-label="<?php esc_attr_e( 'Price', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-price-wrap">
                                    <?php if ( $product_price ) : ?>
                                        <?php echo wp_kses_post( $product_price ); ?>
                                    <?php endif; ?>
                                    <?php if ( $discount_badge ) : ?>
                                        <span class="hexagrid-discount-badge"><?php echo esc_html( $discount_badge ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="hexagrid-td-stock" data-label="<?php esc_attr_e( 'Stock Status', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <span class="hexagrid-stock-pill hexagrid-stock-<?php echo esc_attr( $stock_status ); ?>">
                                    <span class="hexagrid-stock-dot"></span>
                                    <?php echo esc_html( $stock_label ); ?>
                                </span>
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
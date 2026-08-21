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
                            $product_rating = \HexaGrid\Helper::get_product_rating( $product, array( 'show_average' => false, 'show_count' => false ) );

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

                            // Price: table layout 3 style (two-line on sale, discount badge right of offer price).
                            $regular_price = $product->get_regular_price();
                            $sale_price    = $product->get_sale_price();
                            $current_price = $product->get_price();

                            $discount_badge = '';
                            $has_sale       = false;
                            if ( $product->is_on_sale() && $product->is_type( 'simple' ) ) {
                                $regular = (float) $regular_price;
                                $sale    = (float) $sale_price;
                                if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
                                    $has_sale       = true;
                                    $discount_badge = '-' . absint( round( ( ( $regular - $sale ) / $regular ) * 100 ) ) . '%';
                                }
                            }

                            $price_html = '';
                            if ( $has_sale && function_exists( 'wc_price' ) ) {
                                $price_html = '<div class="hexagrid-table-price has-sale">' .
                                    '<div class="hexagrid-offer-line">' .
                                        '<span class="hexagrid-offer-price">' . wp_kses_post( wc_price( $sale_price ) ) . '</span>' .
                                        '<span class="hexagrid-discount-badge">' . esc_html( $discount_badge ) . '</span>' .
                                    '</div>' .
                                    '<div class="hexagrid-regular-line"><del>' . wp_kses_post( wc_price( $regular_price ) ) . '</del></div>' .
                                    '</div>';
                            } elseif ( '' !== $current_price && function_exists( 'wc_price' ) ) {
                                $price_html = '<div class="hexagrid-table-price">' .
                                    '<span class="hexagrid-offer-price">' . wp_kses_post( wc_price( $current_price ) ) . '</span>' .
                                    '</div>';
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

                            // Quantity controls only for simple, in-stock, purchasable products.
                            $in_stock       = $product->is_in_stock();
                            $is_purchasable = $product->is_purchasable();
                            $is_simple      = 'simple' === $product->get_type();
                            $enable_qty     = $in_stock && $is_purchasable && $is_simple;
                            $min_qty        = $product->get_min_purchase_quantity();
                            $max_qty        = $product->get_max_purchase_quantity();
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
                                            <span class="hexagrid-product-sku"><?php echo esc_html( sprintf( /* translators: %s: SKU value */ __( 'SKU: %s', 'hexa-grid-product-showcase' ), $product_sku ) ); ?></span>
                                        <?php endif; ?>
                                        <?php echo wp_kses_post( $product_rating ); ?>
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
                                    <?php if ( $price_html ) : ?>
                                        <?php echo wp_kses_post( $price_html ); ?>
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
                                    <div class="hexagrid-quantity-stepper"<?php echo $enable_qty ? '' : ' aria-hidden="true"'; ?>>
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-minus"<?php echo $enable_qty ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Decrease quantity', 'hexa-grid-product-showcase' ); ?>">-</button>
                                        <input type="number" class="hexagrid-qty-input" value="<?php echo esc_attr( $min_qty ); ?>" min="<?php echo esc_attr( $min_qty ); ?>"<?php echo ( $max_qty > 0 ) ? ' max="' . esc_attr( $max_qty ) . '"' : ''; ?> step="1"<?php echo $enable_qty ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Quantity', 'hexa-grid-product-showcase' ); ?>">
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-plus"<?php echo $enable_qty ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Increase quantity', 'hexa-grid-product-showcase' ); ?>">+</button>
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
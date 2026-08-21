<?php
/**
 * Table Layout Template - Style 3 (Premium)
 *
 * Columns: Product, Category, Price, Stock Status, Actions.
 *
 * Supports partial rendering (only tbody + footer) when `$partial` is true,
 * which is used by the AJAX pagination handler.
 *
 * @var \WP_Query $query
 * @var string $style
 * @var array  $atts
 * @var bool   $partial When true, render only the table body + footer.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$partial = ! empty( $partial );

$active_orderby = isset( $atts['orderby'] ) ? sanitize_key( $atts['orderby'] ) : 'date';
$active_order   = isset( $atts['order'] ) ? strtoupper( sanitize_key( $atts['order'] ) ) : 'DESC';
$active_order   = in_array( $active_order, array( 'ASC', 'DESC' ), true ) ? $active_order : 'DESC';

$sortable_columns = array(
    'product' => 'title',
    'price'   => 'price',
    'stock'   => 'menu_order',
);

$sort_arrow = '<span class="hexagrid-sort-indicator" aria-hidden="true">' .
    '<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M8.5 10 12 6.5 15.5 10H8.5zM15.5 14 12 17.5 8.5 14h7z"/></svg>' .
    '</span>';

$active_sort_for = function ( $column_key, $orderby ) use ( $sort_arrow, $sortable_columns ) {
    if ( isset( $sortable_columns[ $column_key ] ) && $orderby === $sortable_columns[ $column_key ] ) {
        return $sort_arrow;
    }
    return '';
};

$total      = (int) $query->found_posts;
$per_page   = (int) $query->query_vars['posts_per_page'];
$paged      = max( 1, (int) $query->get( 'paged' ) );
$start_item = ( 0 === $total ) ? 0 : ( ( $paged - 1 ) * $per_page ) + 1;
$end_item   = min( $total, $paged * $per_page );
?>
<?php if ( ! $partial ) : ?>
<div class="hexagrid-layout-table hexagrid-<?php echo esc_attr( $style ); ?>">
    <div class="hexagrid-table-card">
    <div class="hexagrid-table-responsive">
        <table class="hexagrid-product-table">
            <thead role="rowgroup">
                <tr role="row">
                    <th class="hexagrid-th-product" role="columnheader" scope="col">
                        <span class="hexagrid-th-label"><?php esc_html_e( 'Product', 'hexa-grid-product-showcase' ); ?></span>
                        <?php echo wp_kses( $active_sort_for( 'product', $active_orderby ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                    </th>
                    <th class="hexagrid-th-category" role="columnheader" scope="col">
                        <span class="hexagrid-th-label"><?php esc_html_e( 'Category', 'hexa-grid-product-showcase' ); ?></span>
                    </th>
                    <th class="hexagrid-th-price" role="columnheader" scope="col">
                        <span class="hexagrid-th-label"><?php esc_html_e( 'Price', 'hexa-grid-product-showcase' ); ?></span>
                        <?php echo wp_kses( $active_sort_for( 'price', $active_orderby ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                    </th>
                    <th class="hexagrid-th-stock" role="columnheader" scope="col">
                        <span class="hexagrid-th-label"><?php esc_html_e( 'Stock Status', 'hexa-grid-product-showcase' ); ?></span>
                        <?php echo wp_kses( $active_sort_for( 'stock', $active_orderby ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                    </th>
                    <th class="hexagrid-th-action" role="columnheader" scope="col">
                        <span class="hexagrid-th-label"><?php esc_html_e( 'Actions', 'hexa-grid-product-showcase' ); ?></span>
                    </th>
                </tr>
            </thead>
            <tbody role="rowgroup" class="hexagrid-table-body">
<?php endif; ?>
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

                            // Category pills (single neutral style).
                            $category_pills = '';
                            $terms = get_the_terms( $product->get_id(), 'product_cat' );
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    $category_pills .= '<span class="hexagrid-category-pill">' . esc_html( $term->name ) . '</span>';
                                }
                            }

                            // Price: two-line layout on sale, discount badge right of offer price.
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

                            // Stock status.
                            $stock_status = $product->get_stock_status();
                            $stock_label  = \HexaGrid\Helper::get_product_stock_status( $product );
                            $stock_qty    = null;
                            if ( $product->is_in_stock() && $product->managing_stock() ) {
                                $stock_qty = $product->get_stock_quantity();
                                $low_stock_amount = (int) get_option( 'woocommerce_notify_low_stock_amount', 2 );
                                if ( null !== $stock_qty && $stock_qty <= $low_stock_amount ) {
                                    $stock_status = 'lowstock';
                                    $stock_label  = __( 'Low Stock', 'hexa-grid-product-showcase' );
                                }
                            }
                            if ( 'instock' === $stock_status && null !== $stock_qty ) {
                                $stock_label = sprintf(
                                    /* translators: %s: stock quantity */
                                    __( 'In Stock (%s)', 'hexa-grid-product-showcase' ),
                                    $stock_qty
                                );
                            } elseif ( 'lowstock' === $stock_status && null !== $stock_qty ) {
                                $stock_label = sprintf(
                                    /* translators: %s: stock quantity */
                                    __( 'Low Stock (%s)', 'hexa-grid-product-showcase' ),
                                    $stock_qty
                                );
                            }

                            // Purchaseability for disabled states.
                            $in_stock       = $product->is_in_stock();
                            $is_purchasable = $product->is_purchasable();

                            // Quantity limits.
                            $min_qty = $product->get_min_purchase_quantity();
                            $max_qty = $product->get_max_purchase_quantity();
                        ?>
                        <tr class="hexagrid-product-row hexagrid-product<?php echo $in_stock ? '' : ' hexagrid-product-outofstock'; ?>" role="row">
                            <td class="hexagrid-td-product" data-label="<?php esc_attr_e( 'Product', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-product-info-wrapper">
                                    <div class="hexagrid-product-image">
                                        <?php echo wp_kses( $product_image, \HexaGrid\Helper::allowed_image_html() ); ?>
                                    </div>
                                    <div class="hexagrid-product-details">
                                        <?php echo wp_kses_post( $product_title ); ?>
                                        <?php if ( $product_sku ) : ?>
                                            <span class="hexagrid-product-sku"><?php echo esc_html( sprintf( /* translators: %s: SKU value */ __( 'SKU: %s', 'hexa-grid-product-showcase' ), $product_sku ) ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="hexagrid-td-category" data-label="<?php esc_attr_e( 'Category', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php if ( $category_pills ) : ?>
                                    <?php echo wp_kses_post( $category_pills ); ?>
                                <?php endif; ?>
                            </td>
                            <td class="hexagrid-td-price" data-label="<?php esc_attr_e( 'Price', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <?php if ( $price_html ) : ?>
                                    <?php echo wp_kses_post( $price_html ); ?>
                                <?php endif; ?>
                            </td>
                            <td class="hexagrid-td-stock" data-label="<?php esc_attr_e( 'Stock Status', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <span class="hexagrid-stock-pill hexagrid-stock-<?php echo esc_attr( $stock_status ); ?>">
                                    <span class="hexagrid-stock-dot" aria-hidden="true"></span>
                                    <span class="hexagrid-stock-text"><?php echo esc_html( $stock_label ); ?></span>
                                </span>
                            </td>
                            <td class="hexagrid-td-action" data-label="<?php esc_attr_e( 'Actions', 'hexa-grid-product-showcase' ); ?>" role="cell">
                                <div class="hexagrid-action-buttons">
                                    <div class="hexagrid-quantity-stepper"<?php echo $in_stock && $is_purchasable ? '' : ' aria-hidden="true"'; ?>>
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-minus"<?php echo $in_stock && $is_purchasable ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Decrease quantity', 'hexa-grid-product-showcase' ); ?>">-</button>
                                        <input type="number" class="hexagrid-qty-input" value="<?php echo esc_attr( $min_qty ); ?>" min="<?php echo esc_attr( $min_qty ); ?>"<?php echo ( $max_qty > 0 ) ? ' max="' . esc_attr( $max_qty ) . '"' : ''; ?> step="1"<?php echo $in_stock && $is_purchasable ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Quantity', 'hexa-grid-product-showcase' ); ?>">
                                        <button type="button" class="hexagrid-qty-btn hexagrid-qty-plus"<?php echo $in_stock && $is_purchasable ? '' : ' disabled'; ?> aria-label="<?php esc_attr_e( 'Increase quantity', 'hexa-grid-product-showcase' ); ?>">+</button>
                                    </div>
                                    <?php echo wp_kses( \HexaGrid\Helper::get_add_to_cart_button( $product, 'icon' ), \HexaGrid\Helper::allowed_svg_html() ); ?>
                                    <a class="hexagrid-more-btn" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product name */ __( 'View more about %s', 'hexa-grid-product-showcase' ), wp_strip_all_tags( $product->get_name() ) ) ); ?>">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                                            <circle cx="12" cy="5" r="1.6"></circle>
                                            <circle cx="12" cy="12" r="1.6"></circle>
                                            <circle cx="12" cy="19" r="1.6"></circle>
                                        </svg>
                                    </a>
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
<?php if ( ! $partial ) : ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

    <div class="hexagrid-table-footer">
        <div class="hexagrid-table-footer-info">
            <?php
            printf(
                /* translators: 1: start item, 2: end item, 3: total */
                esc_html__( 'Showing %1$d to %2$d of %3$d products', 'hexa-grid-product-showcase' ),
                absint( $start_item ),
                absint( $end_item ),
                absint( $total )
            );
            ?>
        </div>
        <?php if ( $total > $per_page && $query->max_num_pages > 1 ) : ?>
            <nav class="hexagrid-table-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Product table pagination', 'hexa-grid-product-showcase' ); ?>">
                <ul>
                    <li class="hexagrid-pagination-prev">
                        <?php if ( $paged > 1 ) : ?>
                            <a class="page-numbers prev" href="#" data-page="<?php echo esc_attr( $paged - 1 ); ?>" aria-label="<?php esc_attr_e( 'Previous page', 'hexa-grid-product-showcase' ); ?>">&lsaquo;</a>
                        <?php else : ?>
                            <span class="page-numbers prev disabled" aria-hidden="true">&lsaquo;</span>
                        <?php endif; ?>
                    </li>
                    <li class="hexagrid-pagination-current">
                        <span class="page-numbers current" aria-current="page"><?php echo esc_html( $paged ); ?></span>
                    </li>
                    <li class="hexagrid-pagination-next">
                        <?php if ( $paged < $query->max_num_pages ) : ?>
                            <a class="page-numbers next" href="#" data-page="<?php echo esc_attr( $paged + 1 ); ?>" aria-label="<?php esc_attr_e( 'Next page', 'hexa-grid-product-showcase' ); ?>">&rsaquo;</a>
                        <?php else : ?>
                            <span class="page-numbers next disabled" aria-hidden="true">&rsaquo;</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    </div>
<?php if ( ! $partial ) : ?>
</div>
<?php endif; ?>
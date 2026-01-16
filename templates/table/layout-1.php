<?php
/**
 * Table Layout Template - Style 1
 *
 * @var \WP_Query $query
 * @var int $columns
 */
?>
<div class="psw-layout-table psw-table-style-1">
    <div class="psw-table-responsive">
        <table class="psw-product-table">
            <thead>
                <tr>
                    <th class="psw-th-image"><?php esc_html_e( 'Image', 'product-showcase-woo' ); ?></th>
                    <th class="psw-th-name"><?php esc_html_e( 'Product Name', 'product-showcase-woo' ); ?></th>
                    <th class="psw-th-price"><?php esc_html_e( 'Price', 'product-showcase-woo' ); ?></th>
                    <th class="psw-th-rating"><?php esc_html_e( 'Rating', 'product-showcase-woo' ); ?></th>
                    <th class="psw-th-action"><?php esc_html_e( 'Action', 'product-showcase-woo' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php 
                        global $product;
                        if ( ! is_object( $product ) ) {
                            $product = wc_get_product( get_the_ID() );
                        }
                    ?>
                    <tr>
                        <td class="psw-td-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                            </a>
                        </td>
                        <td class="psw-td-name">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo wp_kses_post( get_the_title() ); ?>
                            </a>
                        </td>
                        <td class="psw-td-price">
                             <?php echo wp_kses_post( $product->get_price_html() ); ?>
                        </td>
                        <td class="psw-td-rating">
                            <?php 
                            if ( $average = $product->get_average_rating() ) :
                                echo wp_kses_post( wc_get_rating_html( $average ) );
                            endif;
                            ?>
                        </td>
                        <td class="psw-td-action">
                            <?php 
                                echo sprintf( '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                                    esc_url( $product->add_to_cart_url() ),
                                    esc_attr( 'button product_type_' . $product->get_type() . ' add_to_cart_button ajax_add_to_cart' ),
                                    'aria-label="' . esc_attr( $product->add_to_cart_description() ) . '" rel="nofollow"',
                                    esc_html( $product->add_to_cart_description() )
                                );
                            ?>
                        </td>
                    </tr>
                <?php endwhile; wp_reset_postdata(); ?>
            </tbody>
        </table>
    </div>
</div>

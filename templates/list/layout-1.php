<?php
/**
 * List Layout Template
 *
 * @var \WP_Query $query
 */
?>
<div class="psw-layout-list">
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <?php 
            global $product;
            if ( ! is_object( $product ) ) {
                $product = wc_get_product( get_the_ID() );
            }
        ?>
        <div class="psw-product-list-item">
            <div class="psw-list-image">
                <a href="<?php the_permalink(); ?>" class="psw-product-image-link">
                    <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                </a>
                <?php if ( $product->is_on_sale() ) : ?>
                    <span class="psw-badge psw-sale-badge"><?php esc_html_e( 'Sale!', 'product-showcase-woo' ); ?></span>
                <?php endif; ?>
            </div>
            <div class="psw-list-content">
                <?php 
                $categories = wc_get_product_category_list( $product->get_id(), ', ' );
                if ( $categories ) : ?>
                    <div class="psw-product-category">
                        <?php echo wp_kses_post( $categories ); ?>
                    </div>
                <?php endif; ?>

                <h3 class="psw-product-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : ''; ?></a></h3>
                <div class="psw-product-rating">
                    <?php 
                     $average = $product->get_average_rating();
                   if ( $average = $product->get_average_rating() ) :
                        echo wp_kses_post( wc_get_rating_html( $average ) );
                        ?>
                        <span class="psw-rating-average">
                            <?php echo esc_html( number_format( $average, 1 ) ); ?>
                        </span>
                        <?php
                        $review_count = $product->get_review_count();
                        if ( $review_count > 0 ) : ?>
                            <span class="psw-rating-separator">&bull;</span>
                            <span class="psw-review-count"><?php printf( esc_html__( '%d reviews', 'product-showcase-woo' ), $review_count ); ?></span>
                        <?php endif;
                    endif;
                    ?>
                </div>
                <div class="psw-product-excerpt">
                    <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
                </div>
                <div class="psw-product-content-footer">
                    <div class="psw-product-price">
                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                    </div>
                    <div class="psw-list-actions">
                        <div class="psw-action-buttons">
                            <?php woocommerce_template_loop_add_to_cart(); ?>
                            <a href="<?php the_permalink(); ?>" class="button psw-view-details">
                                <?php esc_html_e( 'View Details', 'product-showcase-woo' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>

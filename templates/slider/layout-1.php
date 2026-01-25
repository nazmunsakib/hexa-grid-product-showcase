<?php
/**
 * Slider Layout Template
 *
 * @var \WP_Query $query
 */
?>
<div class="psw-layout-slider swiper">
    <div class="swiper-wrapper">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <?php 
                global $product;
                if ( ! is_object( $product ) ) {
                    $product = wc_get_product( get_the_ID() );
                }
            ?>
            <div class="swiper-slide">
                <div class="psw-product">
                    <div class="psw-product-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                        </a>
                        <?php if ( $product->is_on_sale() ) : ?>
                            <span class="psw-badge psw-sale-badge"><?php esc_html_e( 'Sale!', 'product-showcase-woo' ); ?></span>
                        <?php endif; ?>
                         <div class="psw-product-actions">
                             <?php woocommerce_template_loop_add_to_cart(); ?>
                        </div>
                    </div>
                    <div class="psw-product-details">
                        <h3 class="psw-product-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : ''; ?></a></h3>
                        <div class="psw-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                        <div class="psw-product-rating">
                            <?php 
                            if ( $average = $product->get_average_rating() ) :
                                echo wp_kses_post( wc_get_rating_html( $average ) );
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
    <!-- Add Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

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
                <a href="<?php the_permalink(); ?>">
                    <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
                </a>
            </div>
            <div class="psw-list-content">
                <h3 class="psw-product-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : ''; ?></a></h3>
                <div class="psw-product-rating">
                    <?php 
                    if ( $average = $product->get_average_rating() ) :
                        echo wp_kses_post( wc_get_rating_html( $average ) );
                    endif;
                    ?>
                </div>
                <div class="psw-product-excerpt">
                    <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
                </div>
            </div>
            <div class="psw-list-actions">
                 <div class="psw-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
                 <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>

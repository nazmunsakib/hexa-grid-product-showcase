<?php

namespace ProductShowcase\Admin;

/**
 * Class Meta_Box
 *
 * Handles configuration meta boxes for Presets.
 */
class Meta_Box {

    /**
     * Initialize hooks.
     */
    public function init() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );
    }

    /**
     * Add meta box.
     */
    public function add_meta_boxes() {
        add_meta_box(
            'psw_showcase_settings',
            __( 'Showcase Settings', 'product-showcase-woo' ),
            [ $this, 'render_meta_box' ],
            'product_show_preset',
            'normal',
            'high'
        );
    }

    /**
     * Render the meta box.
     *
     * @param \WP_Post $post Post object.
     */
    public function render_meta_box( $post ) {
        // Add nonces for security
        wp_nonce_field( 'psw_save_showcase_settings', 'psw_showcase_settings_nonce' );

        $layout   = get_post_meta( $post->ID, '_psw_layout_type', true ) ?: 'grid';
        $limit    = get_post_meta( $post->ID, '_psw_query_limit', true ) ?: 12;
        $columns  = get_post_meta( $post->ID, '_psw_columns', true ) ?: 3;
        $category = get_post_meta( $post->ID, '_psw_query_category', true );
        ?>
        <div class="ps-meta-box-content">
            <p>
                <label for="psw_layout_type"><strong><?php esc_html_e( 'Layout Type', 'product-showcase-woo' ); ?></strong></label>
                <br>
                    <option value="grid" <?php selected( $layout, 'grid' ); ?>><?php esc_html_e( 'Grid', 'product-showcase-woo' ); ?></option>
                    <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'product-showcase-woo' ); ?></option>
                    <option value="slider" <?php selected( $layout, 'slider' ); ?>><?php esc_html_e( 'Slider', 'product-showcase-woo' ); ?></option>
                    <option value="table" <?php selected( $layout, 'table' ); ?>><?php esc_html_e( 'Table', 'product-showcase-woo' ); ?></option>
                </select>
            </p>

            <p class="ps-columns-field" style="<?php echo $layout !== 'grid' ? 'display:none;' : ''; ?>">
                <label for="psw_columns"><strong><?php esc_html_e( 'Columns (Grid Only)', 'product-showcase-woo' ); ?></strong></label>
                <br>
                <input type="number" name="psw_columns" id="psw_columns" value="<?php echo esc_attr( $columns ); ?>" class="widefat" min="1" max="6">
            </p>

            <p>
                <label for="psw_query_limit"><strong><?php esc_html_e( 'Product Limit', 'product-showcase-woo' ); ?></strong></label>
                <br>
                <input type="number" name="psw_query_limit" id="psw_query_limit" value="<?php echo esc_attr( $limit ); ?>" class="widefat" min="1">
            </p>

            <p>
                <label for="psw_query_category"><strong><?php esc_html_e( 'Category Variable (Slug)', 'product-showcase-woo' ); ?></strong></label>
                <br>
                <input type="text" name="psw_query_category" id="psw_query_category" value="<?php echo esc_attr( $category ); ?>" class="widefat">
                <small><?php esc_html_e( 'Enter category slug. Leave empty for all.', 'product-showcase-woo' ); ?></small>
            </p>

            <?php if ( $post->ID ) : ?>
                <div class="ps-shortcode-preview" style="background: #f0f0f1; padding: 10px; margin-top: 15px; border-radius: 4px;">
                    <strong><?php esc_html_e( 'Shortcode:', 'product-showcase-woo' ); ?></strong>
                    <code>[product_showcase preset_id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Save meta box data.
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['psw_showcase_settings_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['psw_showcase_settings_nonce'] ) ), 'psw_save_showcase_settings' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save fields
        if ( isset( $_POST['psw_layout_type'] ) ) {
            update_post_meta( $post_id, '_psw_layout_type', sanitize_text_field( wp_unslash( $_POST['psw_layout_type'] ) ) );
        }
        if ( isset( $_POST['psw_columns'] ) ) {
            update_post_meta( $post_id, '_psw_columns', intval( wp_unslash( $_POST['psw_columns'] ) ) );
        }
        if ( isset( $_POST['psw_query_limit'] ) ) {
            update_post_meta( $post_id, '_psw_query_limit', intval( wp_unslash( $_POST['psw_query_limit'] ) ) );
        }
        if ( isset( $_POST['psw_query_category'] ) ) {
            update_post_meta( $post_id, '_psw_query_category', sanitize_text_field( wp_unslash( $_POST['psw_query_category'] ) ) );
        }
    }
}

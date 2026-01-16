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
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        

    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets() {
        global $post_type;
        if ( 'product_show_preset' === $post_type ) {
            // WP Color Picker
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );



            $plugin_root_url = plugin_dir_url( dirname( dirname( __FILE__ ) ) );
            wp_enqueue_style( 'psw-admin-style', $plugin_root_url . 'assets/admin/css/admin.css', [], '1.0.0' );
            wp_enqueue_script( 'psw-admin-script', $plugin_root_url . 'assets/admin/js/admin.js', [ 'jquery', 'wp-color-picker' ], '1.0.0', true );
            
            wp_localize_script( 'psw-admin-script', 'pswAdmin', [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'psw_admin_nonce' ),
            ]);
        }
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

        // Retrieve existing values
        $layout       = get_post_meta( $post->ID, '_psw_layout_type', true ) ?: 'grid';
        $style        = get_post_meta( $post->ID, '_psw_layout_style', true ) ?: 'layout-1';
        $columns      = get_post_meta( $post->ID, '_psw_columns', true ) ?: 3;
        $limit        = get_post_meta( $post->ID, '_psw_query_limit', true ) ?: 12;
        
        $include_ids  = get_post_meta( $post->ID, '_psw_include_ids', true );
        $exclude_ids  = get_post_meta( $post->ID, '_psw_exclude_ids', true );

        $orderby      = get_post_meta( $post->ID, '_psw_orderby', true ) ?: 'date';
        $order        = get_post_meta( $post->ID, '_psw_order', true ) ?: 'DESC';

        $theme_color  = get_post_meta( $post->ID, '_psw_theme_color', true ) ?: '#0984e3';

        

        ?>
        <div class="ps-meta-box-wrapper">
            <div class="ps-meta-header">
                <span class="dashicons dashicons-sliders"></span>
                <h2><?php esc_html_e( 'Showcase Settings', 'product-showcase-woo' ); ?></h2>
            </div>
            
            <div class="ps-meta-box-content">
                
                <!-- Section 1: Layout Settings -->
                <div class="psw-section">
                    <h3 class="psw-section-title">
                        <span class="psw-step-number">1</span> <?php esc_html_e( 'Layout Settings', 'product-showcase-woo' ); ?>
                    </h3>
                    
                    <p class="psw-form-group">
                        <label for="psw_layout_type"><?php esc_html_e( 'Layout Type', 'product-showcase-woo' ); ?></label>
                        <select name="psw_layout_type" id="psw_layout_type" class="widefat">
                            <option value="grid" <?php selected( $layout, 'grid' ); ?>><?php esc_html_e( 'Grid', 'product-showcase-woo' ); ?></option>
                            <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'product-showcase-woo' ); ?></option>
                            <option value="slider" <?php selected( $layout, 'slider' ); ?>><?php esc_html_e( 'Carousel (Slider)', 'product-showcase-woo' ); ?></option>
                            <option value="table" <?php selected( $layout, 'table' ); ?>><?php esc_html_e( 'Table', 'product-showcase-woo' ); ?></option>
                        </select>
                    </p>

                     <p class="psw-form-group">
                        <label for="psw_layout_style"><?php esc_html_e( 'Layout Variation', 'product-showcase-woo' ); ?></label>
                        <select name="psw_layout_style" id="psw_layout_style" class="widefat">
                            <option value="layout-1" <?php selected( $style, 'layout-1' ); ?>><?php esc_html_e( 'Style 1 (Modern)', 'product-showcase-woo' ); ?></option>
                            <option value="layout-2" <?php selected( $style, 'layout-2' ); ?>><?php esc_html_e( 'Style 2 (Classic)', 'product-showcase-woo' ); ?></option>
                        </select>
                    </p>

                    <p class="psw-form-group">
                        <label for="psw_columns"><?php esc_html_e( 'Columns', 'product-showcase-woo' ); ?></label>
                        <select name="psw_columns" id="psw_columns" class="widefat">
                            <option value="1" <?php selected( $columns, 1 ); ?>>1 <?php esc_html_e( 'Column', 'product-showcase-woo' ); ?></option>
                            <option value="2" <?php selected( $columns, 2 ); ?>>2 <?php esc_html_e( 'Columns', 'product-showcase-woo' ); ?></option>
                            <option value="3" <?php selected( $columns, 3 ); ?>>3 <?php esc_html_e( 'Columns', 'product-showcase-woo' ); ?></option>
                            <option value="4" <?php selected( $columns, 4 ); ?>>4 <?php esc_html_e( 'Columns', 'product-showcase-woo' ); ?></option>
                        </select>
                    </p>

                    <p class="psw-form-group">
                        <label for="psw_query_limit"><?php esc_html_e( 'Product Limit', 'product-showcase-woo' ); ?></label>
                        <input type="number" name="psw_query_limit" id="psw_query_limit" value="<?php echo esc_attr( $limit ); ?>" class="widefat" min="1">
                    </p>
                </div>

                <!-- Section 2: Query Settings -->
                <div class="psw-section">
                    <h3 class="psw-section-title">
                        <span class="psw-step-number">2</span> <?php esc_html_e( 'Query Settings', 'product-showcase-woo' ); ?>
                    </h3>

                    <p class="psw-form-group">
                        <label for="psw_include_ids"><?php esc_html_e( 'Include Products (IDs)', 'product-showcase-woo' ); ?></label>
                        <input type="text" name="psw_include_ids" id="psw_include_ids" value="<?php echo esc_attr( $include_ids ); ?>" class="widefat" placeholder="e.g. 101, 105, 200">
                    </p>

                     <p class="psw-form-group">
                        <label for="psw_exclude_ids"><?php esc_html_e( 'Exclude Products (IDs)', 'product-showcase-woo' ); ?></label>
                        <input type="text" name="psw_exclude_ids" id="psw_exclude_ids" value="<?php echo esc_attr( $exclude_ids ); ?>" class="widefat" placeholder="e.g. 101, 105, 200">
                    </p>

                    <div class="psw-row">
                        <p class="psw-form-group psw-col-6">
                            <label for="psw_orderby"><?php esc_html_e( 'Order By', 'product-showcase-woo' ); ?></label>
                            <select name="psw_orderby" id="psw_orderby" class="widefat">
                                <option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Date', 'product-showcase-woo' ); ?></option>
                                <option value="price" <?php selected( $orderby, 'price' ); ?>><?php esc_html_e( 'Price', 'product-showcase-woo' ); ?></option>
                                <option value="ID" <?php selected( $orderby, 'ID' ); ?>><?php esc_html_e( 'ID', 'product-showcase-woo' ); ?></option>
                                <option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Title', 'product-showcase-woo' ); ?></option>
                                <option value="popularity" <?php selected( $orderby, 'popularity' ); ?>><?php esc_html_e( 'Popularity (Sales)', 'product-showcase-woo' ); ?></option>
                            </select>
                        </p>

                         <p class="psw-form-group psw-col-6">
                            <label for="psw_order"><?php esc_html_e( 'Order', 'product-showcase-woo' ); ?></label>
                            <select name="psw_order" id="psw_order" class="widefat">
                                <option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending (Z-A, Newest)', 'product-showcase-woo' ); ?></option>
                                <option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending (A-Z, Oldest)', 'product-showcase-woo' ); ?></option>
                            </select>
                        </p>
                    </div>
                </div>

                <!-- Section 3: Style Settings -->
                <div class="psw-section">
                    <h3 class="psw-section-title">
                        <span class="psw-step-number">3</span> <?php esc_html_e( 'Style Settings', 'product-showcase-woo' ); ?>
                    </h3>

                    <p class="psw-form-group">
                        <label for="psw_theme_color"><?php esc_html_e( 'Theme Color', 'product-showcase-woo' ); ?></label>
                        <input type="text" name="psw_theme_color" id="psw_theme_color" value="<?php echo esc_attr( $theme_color ); ?>" class="psw-color-picker">
                    </p>
                </div>

                <?php if ( $post->ID ) : ?>
                    <div class="ps-shortcode-preview-wrapper">
                        <label><?php esc_html_e( 'Shortcode', 'product-showcase-woo' ); ?></label>
                        <div class="ps-shortcode-container">
                            <code id="psw-shortcode-text">[product_showcase preset_id="<?php echo esc_attr( $post->ID ); ?>"]</code>
                            <button type="button" class="button psw-copy-btn" data-clipboard-target="#psw-shortcode-text">
                                <span class="dashicons dashicons-admin-page"></span> <?php esc_html_e( 'Copy', 'product-showcase-woo' ); ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
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

        // Layout Tab
        if ( isset( $_POST['psw_layout_type'] ) ) {
            update_post_meta( $post_id, '_psw_layout_type', sanitize_text_field( wp_unslash( $_POST['psw_layout_type'] ) ) );
        }
        if ( isset( $_POST['psw_layout_style'] ) ) {
            update_post_meta( $post_id, '_psw_layout_style', sanitize_text_field( wp_unslash( $_POST['psw_layout_style'] ) ) );
        }
        if ( isset( $_POST['psw_columns'] ) ) {
            update_post_meta( $post_id, '_psw_columns', intval( wp_unslash( $_POST['psw_columns'] ) ) );
        }
        if ( isset( $_POST['psw_query_limit'] ) ) {
            update_post_meta( $post_id, '_psw_query_limit', intval( wp_unslash( $_POST['psw_query_limit'] ) ) );
        }

        // Query Tab
        if ( isset( $_POST['psw_include_ids'] ) ) {
            update_post_meta( $post_id, '_psw_include_ids', sanitize_text_field( wp_unslash( $_POST['psw_include_ids'] ) ) );
        }
        if ( isset( $_POST['psw_exclude_ids'] ) ) {
            update_post_meta( $post_id, '_psw_exclude_ids', sanitize_text_field( wp_unslash( $_POST['psw_exclude_ids'] ) ) );
        }
        


        if ( isset( $_POST['psw_orderby'] ) ) {
            update_post_meta( $post_id, '_psw_orderby', sanitize_text_field( wp_unslash( $_POST['psw_orderby'] ) ) );
        }
        if ( isset( $_POST['psw_order'] ) ) {
            update_post_meta( $post_id, '_psw_order', sanitize_text_field( wp_unslash( $_POST['psw_order'] ) ) );
        }

        // Style Tab
        if ( isset( $_POST['psw_theme_color'] ) ) {
            update_post_meta( $post_id, '_psw_theme_color', sanitize_hex_color( wp_unslash( $_POST['psw_theme_color'] ) ) );
        }

    }
}

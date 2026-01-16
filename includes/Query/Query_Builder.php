<?php

namespace ProductShowcase\Query;

/**
 * Class Query_Builder
 *
 * Handles fetching products based on various criteria.
 */
class Query_Builder {

    /**
     * Arguments for WP_Query.
     *
     * @var array
     */
    protected $args = [];

    /**
     * Constructor.
     */
    public function __construct() {
        $this->args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 12, // Default limit
        ];
    }

    /**
     * Set the number of products to retrieve.
     *
     * @param int $limit Number of products.
     * @return self
     */
    public function set_limit( $limit ) {
        $this->args['posts_per_page'] = intval( $limit );
        return $this;
    }

    /**
     * Set specific product IDs to include.
     *
     * @param array $ids Array of product IDs.
     * @return self
     */
    public function set_ids( $ids ) {
        if ( ! empty( $ids ) ) {
            $this->args['post__in'] = $ids;
        }
        return $this;
    }

    /**
     * Filter by category slugs.
     *
     * @param string|array $categories Category slug or array of slugs.
     * @return self
     */
    public function set_category( $categories ) {
        if ( ! empty( $categories ) ) {
            if ( ! is_array( $categories ) ) {
                $categories = array_map( 'trim', explode( ',', $categories ) );
            }

            $this->args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $categories,
                ],
            ];
        }
        return $this;
    }

    /**
     * Set ordering arguments.
     *
     * @param string $orderby Order by parameter.
     * @param string $order Order direction (ASC/DESC).
     * @return self
     */
    public function set_order( $orderby = 'date', $order = 'DESC' ) {
        $this->args['orderby'] = $orderby;
        $this->args['order']   = $order;
        return $this;
    }

    /**
     * Execute the query and return the results.
     *
     * @return \WP_Query
     */
    public function get_query() {
        return new \WP_Query( $this->args );
    }
}

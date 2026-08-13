<?php

namespace HexaGrid\Wishlist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Wishlist_Handler
 *
 * Handles wishlist storage and AJAX operations.
 *
 * Storage strategy:
 * - Logged-in users: user meta `_hexagrid_wishlist`.
 * - Guest users: WooCommerce session `hexagrid_wishlist`.
 *
 * @package HexaGrid\Wishlist
 */
class Wishlist_Handler {

	/**
	 * User meta key for logged-in users.
	 *
	 * @var string
	 */
	private const USER_META_KEY = '_hexagrid_wishlist';

	/**
	 * Session key for guest users.
	 *
	 * @var string
	 */
	private const SESSION_KEY = 'hexagrid_wishlist';

	/**
	 * Nonce action for AJAX requests.
	 *
	 * @var string
	 */
	public const NONCE_ACTION = 'hexagrid_wishlist_nonce';

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'wp_ajax_hexagrid_toggle_wishlist', [ $this, 'ajax_toggle_wishlist' ] );
		add_action( 'wp_ajax_nopriv_hexagrid_toggle_wishlist', [ $this, 'ajax_toggle_wishlist' ] );
		add_action( 'wp_loaded', [ $this, 'maybe_start_guest_session' ] );
	}

	/**
	 * Ensure WooCommerce session is available for guests.
	 *
	 * @return void
	 */
	public function maybe_start_guest_session() {
		if ( is_user_logged_in() ) {
			return;
		}

		if ( function_exists( 'WC' ) && WC()->session && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}

	/**
	 * AJAX handler to toggle a product in the wishlist.
	 *
	 * @return void
	 */
	public function ajax_toggle_wishlist() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( [
				'message' => __( 'Invalid product ID.', 'hexa-grid-product-showcase' ),
			] );
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			wp_send_json_error( [
				'message' => __( 'Product not found.', 'hexa-grid-product-showcase' ),
			] );
		}

		$in_wishlist = $this->is_in_wishlist( $product_id );

		if ( $in_wishlist ) {
			$this->remove( $product_id );
			$in_wishlist = false;
			$message     = __( 'Removed from wishlist.', 'hexa-grid-product-showcase' );
		} else {
			$this->add( $product_id );
			$in_wishlist = true;
			$message     = __( 'Added to wishlist.', 'hexa-grid-product-showcase' );
		}

		wp_send_json_success( [
			'product_id'  => $product_id,
			'in_wishlist' => $in_wishlist,
			'count'       => $this->get_count(),
			'message'     => $message,
		] );
	}

	/**
	 * Check if a product is in the current user's/guest's wishlist.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function is_in_wishlist( $product_id ) {
		$wishlist = $this->get_wishlist();
		$product_id = absint( $product_id );

		return in_array( $product_id, $wishlist, true );
	}

	/**
	 * Get the current wishlist array.
	 *
	 * @return array Array of product IDs.
	 */
	public function get_wishlist() {
		if ( is_user_logged_in() ) {
			$wishlist = get_user_meta( get_current_user_id(), self::USER_META_KEY, true );
			return $this->normalize_wishlist( $wishlist );
		}

		if ( function_exists( 'WC' ) && WC()->session ) {
			$wishlist = WC()->session->get( self::SESSION_KEY, [] );
			return $this->normalize_wishlist( $wishlist );
		}

		return [];
	}

	/**
	 * Add a product to the wishlist.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function add( $product_id ) {
		$product_id = absint( $product_id );

		if ( ! $product_id ) {
			return false;
		}

		$wishlist = $this->get_wishlist();

		if ( in_array( $product_id, $wishlist, true ) ) {
			return true;
		}

		$wishlist[] = $product_id;

		return $this->save_wishlist( $wishlist );
	}

	/**
	 * Remove a product from the wishlist.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function remove( $product_id ) {
		$product_id = absint( $product_id );

		if ( ! $product_id ) {
			return false;
		}

		$wishlist = $this->get_wishlist();
		$wishlist = array_values( array_filter( $wishlist, function ( $id ) use ( $product_id ) {
			return absint( $id ) !== $product_id;
		} ) );

		return $this->save_wishlist( $wishlist );
	}

	/**
	 * Get total number of wishlist items.
	 *
	 * @return int
	 */
	public function get_count() {
		return count( $this->get_wishlist() );
	}

	/**
	 * Save the wishlist array.
	 *
	 * @param array $wishlist Array of product IDs.
	 * @return bool
	 */
	private function save_wishlist( array $wishlist ) {
		$wishlist = $this->normalize_wishlist( $wishlist );

		if ( is_user_logged_in() ) {
			return (bool) update_user_meta( get_current_user_id(), self::USER_META_KEY, $wishlist );
		}

		if ( function_exists( 'WC' ) && WC()->session ) {
			WC()->session->set( self::SESSION_KEY, $wishlist );
			return true;
		}

		return false;
	}

	/**
	 * Normalize wishlist data into an array of unique integer IDs.
	 *
	 * @param mixed $wishlist Raw wishlist data.
	 * @return array
	 */
	private function normalize_wishlist( $wishlist ) {
		if ( ! is_array( $wishlist ) ) {
			$wishlist = [];
		}

		$wishlist = array_map( 'intval', $wishlist );
		$wishlist = array_filter( $wishlist );
		$wishlist = array_unique( $wishlist );

		return array_values( $wishlist );
	}
}

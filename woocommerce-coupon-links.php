<?php
/**
 * WooCommerce Coupon Links
 *
 * @package   WooCommerceCouponLinks
 * @author    Luke McDonald
 * @author    Brady Vercher
 * @link      http://www.cedaro.com/
 * @copyright Copyright (c) 2015 Cedaro, Inc.
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WooCommerce Coupon Links
 * Plugin URI: https://github.com/cedaro/woocommerce-coupon-links
 * Description: Automatically apply a WooCommerce coupon code to the cart with a url.
 * Version: 2.0.2
 * Author: Cedaro
 * Author URI: http://www.cedaro.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: cedaro/woocommerce-coupon-links
 */

/**
 * Automatically apply a coupon passed via URL to the cart.
 *
 * @since 1.0.0
 */
function cedaro_woocommerce_coupon_links() {
	// Bail if WooCommerce or sessions aren't available.
	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return;
	}

	/**
	 * Filter the coupon code query variable name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query_var Query variable name.
	 */
	$query_var = apply_filters( 'woocommerce_coupon_links_query_var', 'coupon_code' );

	// Bail if a coupon code isn't in the query string.
	if ( empty( $_GET[ $query_var ] ) ) {
		return;
	}

	// Set a session cookie to persist the coupon in case the cart is empty.
	WC()->session->set_customer_session_cookie( true );

	// Apply the coupon to the cart if necessary.
	if ( ! WC()->cart->has_discount( $_GET[ $query_var ] ) ) {
		// WC_Cart::add_discount() sanitizes the coupon code.
		WC()->cart->add_discount( $_GET[ $query_var ] );
	}
}
add_action( 'wp_loaded', 'cedaro_woocommerce_coupon_links', 30 );
add_action( 'woocommerce_add_to_cart', 'cedaro_woocommerce_coupon_links' );

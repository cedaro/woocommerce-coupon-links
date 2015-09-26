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
 * Version: 2.0.1
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
	global $woocommerce;

	// Bail early if $woocommerce is empty or coupons are not enabled in WooCommerce.
	if ( empty( $woocommerce ) ) {
		return;
	}

	// Get coupon query variable name.
	$query_var = apply_filters( 'woocommerce_coupon_links_query_var', 'coupon_code' );

	// Get the coupon code.
	$coupon_code = isset( $_GET[ $query_var ] ) ? $_GET[ $query_var ] : '';

	// Bail early if coupon code has not been set or has already been applied.
	if ( empty( $coupon_code ) || in_array( $coupon_code, $woocommerce->cart->applied_coupons ) ) {
		return;
	}
        $woocommerce->session->set_customer_session_cookie(true);
	// Apply the coupon code to the cart.
	$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ) );
}
add_action( 'wp_loaded', 'cedaro_woocommerce_coupon_links', 30 );
add_action( 'woocommerce_add_to_cart', 'cedaro_woocommerce_coupon_links' );

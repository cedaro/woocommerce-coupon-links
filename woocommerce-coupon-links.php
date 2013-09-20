<?php
/**
 * Plugin Name: WooCommerce Coupon Links
 * Plugin URI: http://audiotheme.com/view/woocommerce-coupon-links/
 * Description: Automatically apply a WooCommerce coupon code to the cart with a url.
 * Version: 1.0.0
 * Author: AudioTheme
 * Author URI: http://audiotheme.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

function woocommerce_coupon_links() {
	global $woocommerce;

	// Bail early if $woocommerce is empty or coupons are not enabled in WooCommerce
	if ( empty( $woocommerce ) )
		return;

	// Get coupon query variable
	$coupon_var = apply_filters( 'woocommerce_coupon_links_query_var', 'coupon_code' );
	
	// Set coupon code
	$coupon_code = isset( $_GET[ $coupon_var ] ) ? $_GET[ $coupon_var ] : '';

	// Bail early if coupon code has not been set or has already been applied
	if ( empty( $coupon_code ) || in_array( $coupon_code, $woocommerce->cart->applied_coupons ) )
		return;

	// Apply coupon code to cart
	$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ) );
}

add_action( 'init', 'woocommerce_coupon_links' );
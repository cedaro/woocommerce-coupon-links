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

/*
 * Display the URL on the coupon page.
 *
 * @since 2.0.3
 */
function cedaro_show_coupon_url() {
	// Get the coupon code query variable.
	$query_var = apply_filters( 'woocommerce_coupon_links_query_var', 'coupon_code' );

	?>
	<p class="form-field coupon_url_field">
		<span id="coupon-url-label"><?php esc_html_e( 'Coupon URL', 'cedaro-coupon-links' ); ?></span>
		<span id="coupon-url" data-template="<?php echo esc_attr( get_home_url() . "?$query_var={coupon}" ); ?>"><?php echo esc_html( get_home_url() . "?$query_var=" . get_the_title() ); ?></span>
		<span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'This field displays the URL that can be used to directly add this coupon. The URL will work in conjunction with other query string parameters. An example of this would be adding a product to the cart while at the same time applying the coupon.', 'cedaro-coupon-links' ); ?>"></span>
	</p>
	<?php
}

/*
 * Enqueue style and JavaScript needed for proper display of the URL on
 * the coupon page and also to make it easier to copy the URL.
 *
 * @since 2.0.3
 */
function cedaro_enqueue_coupon_url_styles() {
	$screen = get_current_screen();

	if ( ! empty( $screen ) && 'shop_coupon' === $screen->id ) {
		wp_enqueue_script( 'woocommerce-coupon-links', plugin_dir_url( __FILE__ ) . 'woocommerce-coupon-links-admin.js', array( 'jquery' ), '2.0.3', false );
		wp_enqueue_style( 'woocommerce-coupon-links', plugin_dir_url( __FILE__ ) . 'woocommerce-coupon-links-admin.css', array(), '2.0.3', 'all' );
	}
}
add_action( 'wp_loaded', 'cedaro_woocommerce_coupon_links', 30 );
add_action( 'woocommerce_add_to_cart', 'cedaro_woocommerce_coupon_links' );
add_action( 'woocommerce_coupon_options', 'cedaro_show_coupon_url', 20 );
add_action( 'admin_enqueue_scripts', 'cedaro_enqueue_coupon_url_styles' );

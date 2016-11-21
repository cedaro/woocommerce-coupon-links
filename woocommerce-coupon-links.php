<?php
/**
 * WooCommerce Coupon Links
 *
 * @package   WooCommerceCouponLinks
 * @author    Luke McDonald
 * @author    Brady Vercher
 * @link      https://www.cedaro.com/
 * @copyright Copyright (c) 2015 Cedaro, Inc.
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Coupon Links
 * Plugin URI:        https://github.com/cedaro/woocommerce-coupon-links
 * Description:       Automatically apply a WooCommerce coupon code to the cart from the URL.
 * Version:           2.1.0
 * Author:            Cedaro
 * Author URI:        https://www.cedaro.com/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
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

	// Don't attempt to apply coupon in AJAX requests.
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
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

/**
 * Remove the coupon code query string parameter in the URL.
 *
 * @since 2.1.0
 */
function cedaro_woocommerce_coupon_links_update_url() {
	$query_var = apply_filters( 'woocommerce_coupon_links_query_var', 'coupon_code' );

	if ( ! isset( $_GET[ $query_var ] ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var queryVar = '<?php echo esc_js( $query_var ); ?>',
			removePattern = new RegExp( '([?&])' + queryVar + '=[^&#]*' );

		if ( window.history.replaceState ) {
			window.history.replaceState(
				null,
				null,
				window.location.href.replace( removePattern, '$1' ).replace( /[?&](#|$)/, '$1' )
			);
		}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'cedaro_woocommerce_coupon_links_update_url' );

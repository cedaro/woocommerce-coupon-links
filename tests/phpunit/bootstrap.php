<?php
use Cedaro\WooCommerce\CouponLinks\Test\Framework\Bootstrap;

/**
 * Load the Composer autoloader.
 */
if ( file_exists( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';
}

function locate_woocommerce() {
	$files = [
		dirname( dirname( dirname( __DIR__ ) ) ) . '/woocommerce/woocommerce.php',
		dirname( dirname( __DIR__ ) ) . '/vendor/woocommerce/woocommerce/woocommerce.php',
		'/tmp/woocommerce/woocommerce.php',
	];

	foreach ( $files as $file ) {
		if ( file_exists( $file ) ) {
			return $file;
		}
	}

	return '';
}

$bootstrap = new Bootstrap(
	dirname( dirname( __DIR__ ) ),
	'woocommerce-coupon-links/woocommerce-coupon-links.php'
);

/**
 * Load the WordPress tests.
 *
 * Checks to see if a test case in the unit test suite or the unit test suite
 * itself was specified. If not, loads the WordPress tests.
 */
if ( 'unit' !== $bootstrap->get_test_suite() ) {
	$GLOBALS['wp_tests_options'] = [
		'active_plugins'  => [
			'woocommerce/woocommerce.php',
			'woocommerce-coupon-links/woocommerce-coupon-links.php'
		],
		'timezone_string' => 'America/Los_Angeles',
	];

	$bootstrap->load_tests( function() {
		require locate_woocommerce();
		require dirname( dirname( __DIR__ ) ) . '/woocommerce-coupon-links.php';
	} );
}

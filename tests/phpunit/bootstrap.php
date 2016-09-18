<?php
/**
 * Load the Composer autoloader.
 */
if ( file_exists( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' );
}

/**
 * Load the WordPress tests.
 *
 * Checks to see if a test case in the unit test suite or the unit test suite
 * itself was specified. If not, loads the WordPress tests.
 */
if ( 'unit' !== _get_test_suite() ) {
	$_tests_directory = _locate_wordpress_tests_directory();
	_load_wordpress_tests( $_tests_directory );
}

function _get_test_suite() {
	$suite = '';

	$opts = PHPUnit_Util_Getopt::getopt(
		$GLOBALS['argv'],
		'd:c:hv',
		array( 'filter=', 'testsuite=' )
	);

	foreach ( $opts[0] as $opt ) {
		if ( '--testsuite' === $opt[0] ) {
			$suite = $opt[1];
			break;
		}

		if ( '--filter' === $opt[0] && false !== stripos( $opt[1], 'unit' ) ) {
			$suite = 'unit';
			break;
		}
	}

	return strtolower( $suite );
}

function _load_wordpress_tests( $tests_directory ) {
	$GLOBALS['wp_tests_options'] = array(
		'active_plugins'  => array(
			'woocommerce/woocommerce.php',
			'woocmmerce-coupon-links/woocommerce-coupon-links.php',
		),
		'timezone_string' => 'America/Los_Angeles',
	);

	require_once $tests_directory . '/includes/functions.php';

	tests_add_filter( 'muplugins_loaded', function() {
		require( dirname( dirname( dirname( __DIR__ ) ) ) . '/woocommerce/woocommerce.php' );
		require( dirname( dirname( __DIR__ ) ) . '/woocommerce-coupon-links.php' );
	} );

	require $tests_directory . '/includes/bootstrap.php';
}

function _locate_wordpress_tests_directory() {
	$directory = getenv( 'WP_TESTS_DIR' );

	if ( ! $directory ) {
		if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
			$directory = getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit';
		} elseif ( file_exists( '../../../../../tests/phpunit/includes/bootstrap.php' ) ) {
			$directory = '../../../../../tests/phpunit';
		} elseif ( file_exists( '/tmp/wordpress-tests-lib/includes/bootstrap.php' ) ) {
			$directory = '/tmp/wordpress-tests-lib';
		}
	}

	return $directory;
}

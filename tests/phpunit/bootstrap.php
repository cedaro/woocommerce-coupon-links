<?php
/**
 * Load the Composer autoloader.
 */
if ( file_exists( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' );
}

class Cedaro_WP_Plugin_Tests_Bootstrap {
	public function load_tests( $tests_directory ) {
		$GLOBALS['wp_tests_options'] = [
			'active_plugins'  => [
				'woocommerce/woocommerce.php',
				'woocmmerce-coupon-links/woocommerce-coupon-links.php',
			],
			'timezone_string' => 'America/Los_Angeles',
		];

		// @link https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/functions.php
		require_once $tests_directory . '/includes/functions.php';

		tests_add_filter( 'muplugins_loaded', function() {
			require( $this->locate_woocommerce() );
			require( dirname( dirname( __DIR__ ) ) . '/woocommerce-coupon-links.php' );
		} );

		require $tests_directory . '/includes/bootstrap.php';
	}

	public function locate_wordpress_tests() {
		$directories = [ getenv( 'WP_TESTS_DIR' ) ];

		if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
			$directories[] = getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit';
		}

		$directories[] = '../../../../../tests/phpunit';
		$directories[] = '/tmp/wordpress-tests-lib';

		foreach ( $directories as $directory ) {
			if ( $directory && file_exists( $directory ) ) {
				return $directory;
			}
		}

		return '';
	}

	public function locate_woocommerce() {
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

	public function get_test_suite() {
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
}

$bootstrap = new Cedaro_WP_Plugin_Tests_Bootstrap();

/**
 * Load the WordPress tests.
 *
 * Checks to see if a test case in the unit test suite or the unit test suite
 * itself was specified. If not, loads the WordPress tests.
 */
if ( 'unit' !== $bootstrap->get_test_suite() ) {
	$directory = $bootstrap->locate_wordpress_tests();
	$bootstrap->load_tests( $directory );
}

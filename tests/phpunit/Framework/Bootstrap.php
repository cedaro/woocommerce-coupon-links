<?php
namespace Cedaro\WooCommerce\CouponLinks\Test\Framework;

use PHPUnit_Util_Getopt;

class Bootstrap {
	protected $plugin_directory;
	protected $plugin_file;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_directory Absolute path to the plugin directory.
	 * @param string $plugin_file      Relative path to the main plugin file from the plugins directory.
	 */
	public function __construct( $plugin_directory, $plugin_file ) {
		$this->plugin_directory = rtrim( $plugin_directory, '/' );
		$this->plugin_file = ltrim( $plugin_file, '/' );
	}

	/**
	 * Load WordPress tests.
	 *
	 * @param callable $callback        Optional. Callback to load the plugin and any dependencies before the tests.
	 *                                  Defaults to loading the plugin being tested.
	 * @param string   $tests_directory Optional. Absolute path to the WordPress tests.
	 */
	public function load_tests( $callback = null, $tests_directory = '' ) {
		if ( empty( $callback ) ) {
			$callback = [ $this, 'load_plugin' ];
		}

		if ( empty( $tests_directory ) ) {
			$tests_directory = $this->locate_wordpress_tests();
		}

		// @link https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/functions.php
		require_once( $tests_directory . '/includes/functions.php' );

		tests_add_filter( 'muplugins_loaded', $callback );

		require( $tests_directory . '/includes/bootstrap.php' );
	}

	/**
	 * Load the plugin.
	 *
	 * Used as the default callback when loading WordPress' tests.
	 */
	public function load_plugin() {
		require( $this->plugin_directory . '/' . basename( $this->plugin_file ) );
	}

	/**
	 * Locate the WordPress tests.
	 *
	 * - WP_TESTS_DIR environment variable
	 * - WP_DEVELOP_DIR environment variable
	 * - Default location when developing WordPress
	 * - Location WP CLI installs the tests
	 *
	 * @link https://core.trac.wordpress.org/browser/trunk?order=name
	 * @link https://github.com/wp-cli/scaffold-command/blob/master/templates/install-wp-tests.sh
	 *
	 * @return string Absolute path to the tests.
	 */
	public function locate_wordpress_tests() {
		$directories = [ getenv( 'WP_TESTS_DIR' ) ];

		if ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
			$directories[] = getenv( 'WP_DEVELOP_DIR' ) . 'tests/phpunit';
		}

		$directories[] = dirname( dirname( dirname( $this->plugin_directory ) ) ) . '/tests/phpunit';
		$directories[] = '/tmp/wordpress-tests-lib';

		foreach ( array_filter( $directories ) as $directory ) {
			if ( file_exists( $directory ) ) {
				return $directory;
			}
		}

		return '';
	}

	/**
	 * Determine the test suite.
	 *
	 * @return string
	 */
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

<?php

namespace Cedaro\WooCommerce\CouponLinks\Test\Integration;

use Cedaro\WooCommerce\CouponLinks\Test\Framework\MockSession;
use WC_Helper_Coupon;
use WC_Helper_Product;

class CouponTest extends \WP_UnitTestCase {
	protected $coupon;

	public function setUp() {
		parent::setUp();

		$this->coupon = WC_Helper_Coupon::create_coupon();

		WC()->session = new MockSession();
	}

	public function test_add_coupon() {
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		cedaro_woocommerce_coupon_links();

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}

	public function test_add_coupon_after_product() {
		$product = WC_Helper_Product::create_simple_product();
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		WC()->cart->add_to_cart( $product->id, 1 );
		cedaro_woocommerce_coupon_links();

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}

	public function test_add_coupon_before_product() {
		$product = WC_Helper_Product::create_simple_product();
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		cedaro_woocommerce_coupon_links();
		WC()->cart->add_to_cart( $product->id, 1 );

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}
}

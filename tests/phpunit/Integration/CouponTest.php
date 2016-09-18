<?php

namespace Cedaro\WooCommerce\CouponLinks\Test\Integration;

use Cedaro\WooCommerce\CouponLinks\Test\Framework\Helper\CouponHelper;
use Cedaro\WooCommerce\CouponLinks\Test\Framework\Helper\ProductHelper;

class CouponTest extends \WP_UnitTestCase {
	protected $coupon;
	protected $session_class = '\Cedaro\WooCommerce\CouponLinks\Test\Framework\MockSession';

	public function setUp() {
		parent::setUp();

		$this->coupon = CouponHelper::create_coupon();

		WC()->session = new $this->session_class;
	}

	public function test_add_coupon() {
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		cedaro_woocommerce_coupon_links();

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}

	public function test_add_coupon_after_product() {
		$product = ProductHelper::create_simple_product();
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		WC()->cart->add_to_cart( $product->id, 1 );
		cedaro_woocommerce_coupon_links();

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}

	public function test_add_coupon_before_product() {
		$product = ProductHelper::create_simple_product();
		$this->go_to( home_url( '/?coupon_code=dummycoupon' ) );

		cedaro_woocommerce_coupon_links();
		WC()->cart->add_to_cart( $product->id, 1 );

		$this->assertTrue( WC()->cart->has_discount( 'dummycoupon' ) );
	}
}

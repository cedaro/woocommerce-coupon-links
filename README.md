# WooCommerce Coupon Links

Automatically apply WooCommerce coupon codes to the cart via a url.

__Contributors:__ [Luke McDonald](https://github.com/thelukemcdonald)  
__Requires:__ 3.5  
__Tested up to:__ 3.6.1  
__License:__ [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)  

## Usage

First, you will need to [setup a coupon code](http://docs.woothemes.com/document/coupon-management/) in WooCommerce. The coupon code can then be applied by adding a `coupon_code` query argument to the cart URL.

`http://audiotheme.com/cart/?coupon_code=couponcode`

If the cart is empty, the coupon code will still be applied and visible once the user adds a product. 

Probably more useful than just applying a coupon code to the URL, is appending the coupon code argument to the end of a product add-to-cart url.

`http://audiotheme.com/view/americanaura/?add-to-cart=192&coupon_code=couponcode`

In the above example, a product with the id of `192` will be added to the cart with the `couponcode` coupon applied.

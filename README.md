# WooCommerce Coupon Links

Automatically apply WooCommerce coupon codes to the cart via a URL.

__Contributors:__ [Luke McDonald](https://github.com/thelukemcdonald), [Brady Vercher](https://twitter.com/bradyvercher)  
__Requires:__ 4.0  
__Tested up to:__ 4.3  
__License:__ [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)  

## Usage

First, you will need to [set up a coupon code](http://docs.woothemes.com/document/coupon-management/) in WooCommerce. The coupon code can then be applied by adding a `coupon_code` query argument to the cart URL.

`https://audiotheme.com/cart/?coupon_code=highfive`

If the cart is empty, the coupon code will still be applied and visible once the user adds a product. 

Probably more useful than just applying a coupon code to the URL, is appending the coupon code argument to the end of a product's add-to-cart URL.

`https://audiotheme.com/view/promenade/?add-to-cart=2804&coupon_code=highfive`

In the above example, a product with the id of `2804` will be added to the cart with the `highfive` coupon applied.

## Installation ##

### Upload ###

1. Download the [latest release](https://github.com/cedaro/woocommerce-coupon-links/archive/master.zip) from GitHub.
2. Go to the _Plugins &rarr; Add New_ screen in your WordPress admin panel and click the __Upload__ button at the top next to the "Add Plugins" title.
3. Upload the zipped archive.
4. Click the __Activate Plugin__ link after installation completes.

### Manual ###

1. Download the [latest release](https://github.com/cedaro/woocommerce-coupon-links/archive/master.zip) from GitHub.
2. Unzip the archive.
3. Copy the folder to `/wp-content/plugins/`.
4. Go to the _Plugins &rarr; Installed Plugins_ screen in your WordPress admin panel and click the __Activate__ link under the _WooCommerce Coupon Links_ item.

Read the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git ###

Clone this repository in `/wp-content/plugins/`:

`git clone git@github.com:cedaro/woocommerce-coupon-links.git`

Then go to the _Plugins &rarr; Installed Plugins_ screen in your WordPress admin panel and click the __Activate__ link under the _WooCommerce Coupon Links_ item.

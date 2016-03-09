(function( $ ) {
	'use strict';

	$(function() {

		// 'pagenow' variable exists on admin pages
		if ( 'shop_coupon' === pagenow ) {

			// When URL or label for URL are clicked, select the URL for easy copying.
			$( '#coupon-url, #coupon-url-label' ).click( function() {
				var doc = document, range, selection,
					text = $( '#coupon-url' )[0];

				if ( doc.body.createTextRange ) {
					range = document.body.createTextRange();
					range.moveToElementText( text );
					range.select();
				} else if ( window.getSelection ) {
					selection = window.getSelection();
					range = document.createRange();
					range.selectNodeContents( text );
					selection.removeAllRanges();
					selection.addRange( range );
				}
			});

			// Update the URL as the coupon code is changed.
			$( '#title' ).keyup( function( e ) {
				var coupon_url = $( '#coupon-url' ),
					template = coupon_url.data( 'template' );

				coupon_url.text( template.replace( '{coupon}', e.target.value ) );
			});
		}
	});

})( jQuery );

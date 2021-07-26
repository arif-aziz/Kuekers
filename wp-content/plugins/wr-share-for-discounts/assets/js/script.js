/**
 * @version 1.0
 * @package WR_Share_For_Discounts
 * @author WooRockets Team <support@woorockets.com>
 * @copyright Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

( function( $ ) {
	$( document ).ready( function() {

		// Click to show modal sharing
		var modal = $( '.wr-share-modal' );
		$( '.wr-share-button a' ).click(function( e ) {
		  e.preventDefault();
			modal.addClass( 'show' );
			$( 'body' ).addClass( 'wr-share-mask' );
		});
		// Click overlay to close
		var close_share = function() {
			modal.removeClass('show');
			$( 'body' ).removeClass( 'wr-share-mask' );
		}
		modal.click( function( e ) {
			if ( $( e.target ).parents( '.wr-share-modal-inner' ).length === 0 ) {
				close_share();
			}
		});

		// Apply discount after sharing.
		function discount( type ) {
			if ( !type ) {
				return;
			}

			// Generate discount URL.
			var url = wr_share_for_discounts.url;

			url += '&nonce=' + wr_share_for_discounts.nonce;
			url += '&type=' + type;

			if ( type == 'product' ) {
				url += '&product=' + wr_share_for_discounts.product;
			}

			// Send discount request to server using the hidden iframe.
			$( 'iframe#wr-share-for-discounts' ).attr( 'src', url ).load( function() {
				if ( type == 'product' ) {
					// Get product details container.
					var elm = $( '#product-' + wr_share_for_discounts.product );

					if ( !elm.length ) {
						elm = $( '.type-product.post-' + wr_share_for_discounts.product );
					}

					if ( $( this ).contents().find( 'body' )[0].textContent != '' ) {
						var response = $( this ).contents().find( 'body' )[0].textContent.match( /^\{.+\}$/ );

						if ( response ) {
							response = JSON.parse( response[ 0 ] );

							if ( response.price ) {
								elm.find( '.price' ).eq( 0 ).html( response.price );
							}

							// Update price for variations.
							if ( response.variations ) {
								var variations = $( 'form.variations_form' ).data( 'product_variations' );

								if ( variations && variations.length ) {
									for ( var i = 0, n = variations.length; i < n; i++ ) {
										var id = variations[ i ].variation_id;

										if ( response.variations[ id ] ) {
											variations[ i ].display_price = response.variations[ id ].price;
											variations[ i ].display_regular_price = response.variations[ id ].price;
											variations[ i ].price_html = response.variations[ id ].price_html;
										}

										$( 'form.variations_form' ).data( 'product_variations', variations );
									}
								}
							}

							// Close modal after sharing
							close_share();

						} else {
							elm.find( '.price' ).eq( 0 ).html( $( this ).contents().find( 'body' ).html() );
						}
					}
				}
			} );
		}

		// Init Facebook sharing.
		if ( wr_share_for_discounts.enable_facebook_sharing ) {
			window.fbAsyncInit = function() {
				FB.init( {
					appId: wr_share_for_discounts.facebook_app_id,
					xfbml: true,
					version: 'v2.4'
				} );

				// Track Facebook like action.
				FB.Event.subscribe( 'edge.create', function( link, elm ) {
					discount( $( elm ).closest( '.wr-product-share' ).attr( 'data-type' ) );
				} );
			};

			// Setup share to Facebook button.
			$( 'a.wr-share-to-fb-button' ).click( function( event ) {
				FB.ui( {
					method: 'share',
					href: $( this ).attr( 'data-href' ),
				}, function( response ) {
					if ( response ) {
						discount( $( event.target ).closest( '.wr-product-share' ).attr( 'data-type' ) );
					}
				} );
			} );

			( function( d, s, id ) {
				var js, fjs = d.getElementsByTagName( s )[ 0 ];
				if ( d.getElementById( id ) ) {
					return;
				}
				js = d.createElement( s );
				js.id = id;
				js.src = '//connect.facebook.net/en_US/sdk.js';
				fjs.parentNode.insertBefore( js, fjs );
			}( document, 'script', 'facebook-jssdk' ) );
		}

		// Init Twitter sharing.
		if ( wr_share_for_discounts.enable_twitter_sharing ) {
			window.twttr = ( function( d, s, id ) {
				var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
				if ( d.getElementById( id ) ) {
					return t;
				}
				js = d.createElement( s );
				js.id = id;
				js.src = 'https://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore( js, fjs );
				t._e = [];
				t.ready = function( f ) {
					t._e.push( f );
				};
				return t;
			}( document, 'script', 'twitter-wjs' ) );

			twttr.ready( function( twttr ) {
				var tweetButton = $( '.twitter-sharing .twitter-share-button' );

				if ( ! tweetButton.length ) {
					return;
				}

				twttr.widgets.createShareButton(
					tweetButton.attr( 'data-url' ),
					tweetButton[0],
					{
						url: tweetButton.attr( 'data-url' ),
						via: tweetButton.attr( 'data-via' ),
						text: document.title,
					}
				);

				twttr.events.bind( 'tweet', function( event ) {
					if ( event ) {
						discount( $( event.target ).closest( '.wr-product-share' ).attr( 'data-type' ) );
					}
				} );
			} );
		}

		// Init Google+ sharing.
		if ( wr_share_for_discounts.enable_google_plus_sharing ) {
			var share_timer = null, counter = 0;

			// Callback for Google+ button.
			window.wr_google_plus_one = function( jsonParam ) {
				if ( jsonParam.state == 'on' ) {
					discount( $( wr_share_for_discounts.might_be_clicked ).closest( '.wr-product-share' ).attr( 'data-type' ) );
				}
			};

			// Callback for Google+ Share button.
			window.wr_google_plus_share = function( jsonParam ) {
				share_timer = setInterval( function() {
					counter++;

					if ( counter == 4 ) {
						clearInterval( share_timer );

						discount( $( wr_share_for_discounts.might_be_clicked ).closest( '.wr-product-share' ).attr( 'data-type' ) );
					}
				}, 1000 );
			};

			window.wr_google_plus_share_stop = function( jsonParam ) {
				if ( share_timer != null ) {
					counter = 0;
					clearInterval( share_timer );
				}
			}

			// Track mouse over event to determine which button is clicked.
			$( document ).on( 'mouseover', '.google-plus-sharing', function( event ) {
				wr_share_for_discounts.might_be_clicked = event.target;
			} );

			function initGooglePlus() {
				if ( window.gapi === undefined ) {
					return setTimeout( initGooglePlus, 200 );
				}

				gapi.plusone.go();
				gapi.plus.go();
			}

			initGooglePlus();
		}
	} );
} )( jQuery );

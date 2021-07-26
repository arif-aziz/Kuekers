/**
 * @version 1.0
 * @package WR_Custom_Attributes
 * @author WooRockets Team <support@woorockets.com>
 * @copyright Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

( function( $ ) {
	$.init_wr_custom_attributes = function() {
		// Replace default attribute select box with custom attribute selector.
		$('select[data-attribute_name]').each(function(i, e) {
			var selector = $('#' + $(e).attr('id') + '_custom_attribute_selector');

			if (selector.length) {
				$(e).before(selector.text());
				selector.remove();
			} else if ( ! $(e).prev('.wr-custom-attribute').length ) {
				$(e).addClass('active-attribute-selector');
			}
		});

		// Define function to switch between product image and variation image.
		window.wr_set_variation_attr = $.fn.wc_variations_image_update;

		// Override WooCommerce function to update variation image.
		$.fn.wc_variations_image_update = function( variation ) {
			if ( variation && variation.thumb_image_src == null ) {
				return;
			}

			// Store variation data.
			window.wr_custom_variation = variation;

			// Update variation image.
			$( '.variations_form' ).each( function() {
				window.wr_set_variation_attr.call( $(this), variation );
			} );
		};

		// Define function to switch product image gallery.
		function switch_image_gallery( elm, reset, preload ) {
			if ( $( elm ).hasClass( 'has-image-gallery' ) ) {
				// Get data from cache first.
				var
				cache = window.wr_custom_attribute_data || {},
				option = $( elm ).closest( '[data-attribute]' ).attr( 'data-attribute' ),
				key = option + '::' + ( reset ? 'default' : $( elm ).attr( 'data-value' ) );

				if ( cache[ key ] ) {
					// Switch image gallery.
					if ( preload ) {
						if ( $( elm ).parent().hasClass( 'selected' ) ) {
							$( elm ).trigger('click');
						}
					} else {
						var
						html = $( cache[ key ] ),
						orig = $( '.' + html.attr('class').replace( /\s+/g, '.' ) );

						if ( orig.length ) {
							var html_gallery = $( cache[ key ] );

							// Update gallery HTML.
							orig.parent().html( html_gallery.addClass('active-product-gallery') );

							// Update variation image.
							$( '.variations_form' ).each( function() {
								window.wr_set_variation_attr.call( $(this), window.wr_custom_variation );
							} );

							// Init product image gallery.
							if ( $.fn.wc_product_gallery ) {
								html_gallery.wc_product_gallery();
							}
						}
					}
				}

				// No cached data found, request server for data.
				else {
					var data = {};

					if ( ! reset ) {
						data[ 'attribute_' + option ] = $( elm ).attr( 'data-value' );
					}

					$.ajax( {
						url: $( elm ).attr( 'data-href' ),
						type: 'post',
						data: data,
						complete: function( response ) {
							// Update cache.
							var
							option = $( this ).closest( '[data-attribute]' ).attr( 'data-attribute' ),
							key = option + '::' + ( reset ? 'default' : $( this ).attr( 'data-value' ) );

							window.wr_custom_attribute_data = window.wr_custom_attribute_data || {};

							window.wr_custom_attribute_data[ key ] = response.responseText;

							// Switch image gallery.
							switch_image_gallery( this, reset, preload );
						},
						context: elm
					} );
				}
			}
		}

		// Pre-load data for variation image gallery.
		var
		colors = $( '.wr-custom-attribute .has-image-gallery[data-value]' ),
		selected = $( '.wr-custom-attribute .selected .has-image-gallery[data-value]' );

		if ( selected.length ) {
			// Load image gallery of default variation.
			switch_image_gallery( selected[0], false, true );
		}

		if ( colors.length ) {
			// Load default image gallery of the product.
			switch_image_gallery( colors[0], true, selected.length ? true : false );

			// Then, load all variation data.
			colors.each( function( i, e ) {
				if ( e != selected[0] ) {
					( function( color ) {
						setTimeout( function() {
							switch_image_gallery( color, false, true );
						}, (i + 1) * 200 );
					} )( e );
				}
			} );
		} else {
			// No variation image gallery available, show product image gallery.
			$( '.woocommerce-product-gallery' ).addClass( 'active-product-gallery' );
		}

		// Setup custom attribute selection.
		$( '.wr-custom-attribute' ).on( 'click', '[data-value]', function() {
			var
			option = $( this ).closest( '[data-attribute]' ).attr( 'data-attribute' ),
			select = $( 'select#' + option );

			$( this ).parent().addClass( 'selected' ).siblings().removeClass( 'selected' );

			// Update hidden option field.
			select.val( $( this ).attr( 'data-value' ) ).trigger( 'change' );

			// Switch image gallery if available.
			switch_image_gallery( this );
		} );

		// Setup variation form selection.
		$( '.variations_form' ).addClass( 'wr_variations_form' ).on( 'change', 'select[data-attribute_name]', function() {
			// Auto-select option that has only 1 choice available.
			setTimeout( function() {
				$( '.variations_form select[data-attribute_name]' ).each( function( i, e ) {
					if ( $( e ).val() == '' && $( e ).children( '[value!=""]' ).length == 1 ) {
						$( e ).val( $( e ).children( '[value!=""]' ).attr( 'value' ) ).trigger( 'change' );
					}
				} );
			}, 50 );

			// Refresh options available.
			$( '.wr-custom-attribute[data-attribute]' ).each( function( i, e ) {
				( function( e ) {
					setTimeout( function() {
						var
						option = $( e ).attr( 'data-attribute' ),
						select = $( 'select#' + option );

						$( e ).children().each( function( i2, e2 ) {
							if ( !select.children( '[value="' + $( e2 ).find( '[data-value]' ).attr( 'data-value' ) + '"]' ).length ) {
								$( e2 ).hide();
							} else {
								$( e2 ).show();
							}
						} );
					}, 50 );
				} )( e );
			} );
		} );

		// Handle reset action.
		$( document ).on( 'click', 'a.reset_variations', function() {
			// Reset product image.
			$.fn.wc_variations_image_update();

			// Reset product image gallery.
			switch_image_gallery( $( '.wr-custom-attribute .selected' ).children( '[data-value]' ), true );

			// Reset custom attribute selection.
			$( '.wr-custom-attribute .selected' ).removeClass( 'selected' );
			$( '.wr-custom-attribute .available-option' ).removeClass( 'available-option' );
		} );
	};

	if ( $.isReady ) {
		$.init_wr_custom_attributes();
	} else {
		$( document ).ready( $.init_wr_custom_attributes );
	}
} )( jQuery );

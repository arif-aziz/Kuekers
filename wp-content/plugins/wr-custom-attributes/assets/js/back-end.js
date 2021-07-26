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
	$(document).ready(function() {
		// Setup product variation gallery images.
		$('[id^="product-gallery-color-"], [id^="product-gallery-images-"]').each(function() {
			var image_gallery_ids = $(this).find('input.product_variation_image_gallery'),
				product_images = $(this).find('.product_variation_images_container ul.product_images');

			$(this).on('click', '.add_product_variation_images a', function(event) {
				event.preventDefault();

				// Get media frame.
				var $el = $(this),
					media_frame = $el.data('product_variation_gallery_frame');

				if ( ! media_frame ) {
					// Create the media frame.
					media_frame = wp.media({
						// Set the title of the modal.
						title: $el.data('choose'),
						button: {
							text: $el.data('update')
						},
						states: [
							new wp.media.controller.Library({
								title: $el.data('choose'),
								filterable: 'all',
								multiple: true
							})
						]
					});

					// When an image is selected, run a callback.
					media_frame.on('select', function() {
						var selection = media_frame.state().get('selection'),
							attachment_ids = image_gallery_ids.val();

						selection.map(function(attachment) {
							attachment = attachment.toJSON();

							if (attachment.id) {
								attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;

								var attachment_image = ( attachment.sizes && attachment.sizes.thumbnail )
									? attachment.sizes.thumbnail.url
									: attachment.url;

								product_images.append(
									'<li class="image" data-attachment_id="' + attachment.id + '">'
									+ '<img src="' + attachment_image + '" />'
									+ '<ul class="actions"><li>'
									+ '<a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a>'
									+ '</li></ul>'
									+ '</li>'
								);
							}
						});

						image_gallery_ids.val(attachment_ids);
					});

					$el.data('product_variation_gallery_frame', media_frame);
				}

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering.
			product_images.sortable({
				items: 'li.image',
				cursor: 'move',
				scrollSensitivity: 40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start: function( event, ui ) {
					ui.item.css('background-color', '#f6f6f6');
				},
				stop: function(event, ui) {
					ui.item.removeAttr('style');
				},
				update: function() {
					var attachment_ids = '';

					product_images.find('li.image').css('cursor', 'default').each(function() {
						var attachment_id = $(this).attr('data-attachment_id');
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					image_gallery_ids.val(attachment_ids);
				}
			});

			// Remove images.
			$(this).on('click', 'li.image a.delete', function() {
				$(this).closest('li.image').remove();

				var attachment_ids = '';

				product_images.find('li.image' ).css('cursor', 'default').each(function() {
					var attachment_id = $(this).attr('data-attachment_id');
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				image_gallery_ids.val( attachment_ids );

				// Remove any lingering tooltips.
				$('#tiptip_holder').removeAttr('style');
				$('#tiptip_arrow').removeAttr('style');

				return false;
			});
		});
	});

	$(document).ajaxSuccess(function(event, jqXHR, ajaxOptions, data) {
		// Check if this is an Ajax request to load product variations?
		if ( ajaxOptions.data && ajaxOptions.data.indexOf('&action=woocommerce_load_variations&') > 0 ) {
			// Send an Ajax request to check if product variations has Color Picker attribute?
			$.ajax({
				url: ajaxOptions.url,
				type: 'POST',
				data: ajaxOptions.data.replace('&action=woocommerce_load_variations&', '&action=wr-detect-color-picker-attribute&'),
				complete: function(response) {
					if ( response.responseJSON && response.responseJSON.success ) {
						// Detected Color Picker attribute used for product variations, create button to reload page.
						if ( ! $('.wr-reload-page').length ) {
							$('.variations-pagenav').before('<button type="button" class="button wr-reload-page">Refresh</button>');

							// Create a tool-tip to describe the button.
							$('.wr-reload-page').tipTip({
								content: wr_custom_attribute.refresh_tip
							});

							$('.wr-reload-page').click(function() {
								window.location.reload();
							});
						}
					}
				}
			});
		}
	});
} )( jQuery );

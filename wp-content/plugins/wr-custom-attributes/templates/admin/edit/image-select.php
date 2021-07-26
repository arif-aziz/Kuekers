<?php
/**
 * @version    1.0
 * @package    WR_Custom_Attributes
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

// Enqueue WordPress's media manager.
wp_enqueue_media();
?>
<tr class="form-field wr-image-wrap">
	<th scope="row"><label for="wr-image"><?php _e( 'Image', 'wr-custom-attributes' ); ?></label></th>
	<td>
		<input name="wr_image" id="wr-image" type="text" value="<?php
			echo esc_attr( get_woocommerce_term_meta( $tag->term_id, 'wr_image' ) );
		?>">
		<a href="javascript:void(0)" class="button" title="<?php
			_e( 'Click to select an existing image or upload a new one', 'wr-custom-attributes' );
		?>">
			<?php _e( 'Select', 'wr-custom-attributes' ); ?>
		</a>
		<p class="description"><?php _e( 'Please select an image.', 'wr-custom-attributes' ); ?></p>
	</td>
</tr>
<tr class="form-field wr-tooltip-wrap">
	<th scope="row"><label for="wr-tooltip"><?php _e( 'Tooltip', 'wr-custom-attributes' ); ?></label></th>
	<td>
		<input name="wr_tooltip" id="wr-tooltip" type="text" value="<?php
			echo esc_attr( get_woocommerce_term_meta( $tag->term_id, 'wr_tooltip' ) );
		?>">
		<p class="description"></p>
	</td>
</tr>
<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('.wr-image-wrap a.button').click(function(event) {
				event.preventDefault();

				// Store clicked element for later reference
				var $btn = $(this), $input = $btn.prev(), manager = $btn.data('wr_image_select');

				if (!manager) {
					// Create new media manager.
					manager = wp.media({
						button: {
							text: '<?php _e( 'Select',  'wr-custom-attributes' ); ?>',
						},
						states: [
							new wp.media.controller.Library({
								title: '<?php _e( 'Select an image',  'wr-custom-attributes' ); ?>',
								library: wp.media.query({type: 'image'}),
								multiple: false,
								date: false,
							})
						]
					});

					// When an image is selected, run a callback
					manager.on('select', function() {
						// Grab the selected attachment
						var attachment = manager.state().get('selection').first();

						// Update the field value
						$input.val(attachment.attributes.url).trigger('change');
					});

					// Store media manager object for later reference
					$btn.data('wr_image_select', manager);
				}

				manager.open();
			});
		});
	})(jQuery);
</script>

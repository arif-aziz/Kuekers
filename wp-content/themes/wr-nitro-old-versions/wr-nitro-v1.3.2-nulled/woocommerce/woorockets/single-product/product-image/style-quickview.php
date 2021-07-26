<?php
/**
 * @version    1.0
 * @package    WR_Theme
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$wr_nitro_options = WR_Nitro::get_options();
?>
<div id="p-preview" class="wr-nitro-carousel p-gallery" data-owl-options='{"items": "1", "nav": "true"<?php echo ( $wr_nitro_options['rtl'] ? ',"rtl": "true"' : '' ); ?>}'>
	<?php
		// Get post thumbnail
		echo '<div data-type="image-gallery-highlights">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', get_the_post_thumbnail( $product->id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ) ) . '</div>';

		// Get attachment file
		$loop = 0;

		$attachment_ids = $product->get_gallery_attachment_ids();

		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				echo '<div data-type="image-gallery-large">' . apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '%s', wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ), wp_get_attachment_url( $attachment_id ) ) ) . '</div>';
				$loop++;
			}
		}
	?>
</div>

<?php echo '<scr' . 'ipt>'; ?>
	(function($) {
		"use strict";
		$( document ).ready( function() {
			$.WR.Carousel();
		});
	})(jQuery);
<?php echo '</scr' . 'ipt>'; ?>
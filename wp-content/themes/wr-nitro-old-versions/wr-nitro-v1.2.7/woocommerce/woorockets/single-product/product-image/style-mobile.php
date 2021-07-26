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

global $post, $woocommerce, $product;

$wr_nitro_options = WR_Nitro::get_options();

// Right to left
$rtl = $wr_nitro_options['rtl'];

// Get offset width
$offset = $wr_nitro_options['wr_layout_offset'];

// Show wishlist
$show_wishlist = $wr_nitro_options['wc_general_wishlist'];

// Get sale price dates
$countdown = get_post_meta( get_the_ID(), '_show_countdown', true );
$start     = get_post_meta( get_the_ID(), '_sale_price_dates_from', true );
$end       = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
$now       = date( 'd-m-y' );

// Embed video to product thumbnail
$video_source = get_post_meta( $post->ID, 'wc_product_video', true );
$video_link   = get_post_meta( $post->ID, 'wc_product_video_url', true );
$video_file   = get_post_meta( $post->ID, 'wc_product_video_file', true );

?>
<div class="p-gallery">
	<div class="product-preview pr<?php if ( ! empty( $video_link ) || ! empty( $video_file ) ) echo ' has-video'; ?>">
		<?php if ( 'yes' == $countdown && $end && date( 'd-m-y', $start ) <= $now ) : ?>
			<div class="product__countdown pa bgw">
				<div class="wr-nitro-countdown fc jcsb tc aic" data-time='{"day": "<?php echo date( 'd', $end ); ?>", "month": "<?php echo date( 'm', $end ); ?>", "year": "<?php echo date( 'Y', $end ); ?>"}'></div>
			</div><!-- .product__countdown -->
		<?php endif; ?>
		<?php
			// Add Wishlist button
			if ( class_exists( 'YITH_WCWL' ) && $show_wishlist && wp_is_mobile() ) :
			echo '<div class="wishlist-btn">' . do_shortcode( '[yith_wcwl_add_to_wishlist]' ) . '</div>';
		endif; ?>

		<div id="p-large" class="wr-nitro-carousel images" data-owl-options='{"items": "1", "dots": "true"}'>
			<?php
				if ( has_post_thumbnail() ) {
					$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
					$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
					$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
					$args = array(
						'title'	          => $image_title,
						'alt'	          => $image_title,
						'url'             => $image_link,
					);

					$image       	= get_the_post_thumbnail(
						$post->ID,
						apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),
						$args
					);

					$attachment_count = count( $product->get_gallery_attachment_ids() );

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $post->ID );

				} else {
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'wr-nitro' ) ), $post->ID );
				}

				$attachment_ids = $product->get_gallery_attachment_ids();
				if ( $attachment_ids ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$image_link = wp_get_attachment_url( $attachment_id );
						if ( ! $image_link ) continue;

						$image_title 	= esc_attr( get_the_title( $attachment_id ) );
						$image_caption 	= get_post( $attachment_id )->post_excerpt;

						$args = array(
							'title'	          => $image_title,
							'alt'	          => $image_title,
							'url'             => $image_link
						);

						$image_title = esc_attr( get_the_title( $attachment_id ) );
						$image       = wp_get_attachment_image(
							$attachment_id,
							apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ), false,
							$args
						);

						echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" title="%s">%s</a>', $image_link, $image_caption, $image ), $post->ID );
					}
				}
			?>
		</div><!-- #p-large -->

		<?php
			if ( $video_source == 'url' && ! empty( $video_link ) ) {
				echo '<div class="p-video pa">';
					echo '<a class="p-video-link db" href="' . esc_url( $video_link ) . '"><i class="fa fa-play"></i></a>';
				echo '</div>';
			} elseif ( ! empty( $video_file ) ) {
				echo '<div class="p-video pa">';
					echo '<a class="p-video-file db" href="#wr-p-video"><i class="fa fa-play"></i></a>';
					echo '<div id="wr-p-video" class="mfp-hide">' . do_shortcode( '[video src="' . wp_get_attachment_url( $video_file ) . '" width="640" height="320"]' ) . '</div>';
				echo '</div>';
			}
		?>
	</div><!-- .product-preview -->
</div><!-- .p-gallery -->
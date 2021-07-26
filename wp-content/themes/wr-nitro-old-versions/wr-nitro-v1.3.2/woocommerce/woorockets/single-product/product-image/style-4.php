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

// Get offset width
$offset = $wr_nitro_options['wr_layout_offset'];
?>
<div class="p-gallery wr-nitro-carousel images" data-owl-options='{"items": "1", "autoplay": "true", "autoplayTimeout": "7000", "dots": "true"<?php echo ( $wr_nitro_options['rtl'] ? ',"rtl": "true"' : '' ); ?>}'>
	<?php

		$gallery = $lightbox = '';
		if ( is_customize_preview() ) {
			$lightbox = 'class="lightbox-disable"';
		}

		if ( has_post_thumbnail() ) {
			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail(
				$post->ID,
				apply_filters( 'single_product_large_thumbnail_size', 'full' ),
				array(
					'title' => $image_title,
					'alt'   => $image_title,
				)
			);

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = 'data-lightbox-gallery="' . esc_attr( $post->ID ) . '"';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="item fr"><a ' . $lightbox . ' href="%s" itemprop="image" title="%s" data-type="image-gallery-highlights" data-lightbox="nivo" ' . $gallery . '>%s</a></div>', $image_link, $image_caption, $image ), $post->ID );
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'wr-nitro' ) ), $post->ID );
		}

		$attachment_ids = $product->get_gallery_attachment_ids();
		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$image_link = wp_get_attachment_url( $attachment_id );
				if ( ! $image_link ) continue;

				$image_title = esc_attr( get_the_title( $attachment_id ) );
				$image_caption 	= get_post( $attachment_id )->post_excerpt;

				$image       = wp_get_attachment_image(
					$attachment_id,
					apply_filters( 'single_product_small_thumbnail_size', 'full' ), false,
					array(
						'title'	          => $image_title,
						'alt'	          => $image_title,
						'class'           => "attachment-shop_single",
					)
				);

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="item fr"><a ' . $lightbox . ' href="%s" itemprop="image" title="%s" data-lightbox="nivo" ' . $gallery . '>%s</a></div>', $image_link, $image_caption, $image ), $post->ID );
			}
		}
	?>
</div><!-- .p-gallery -->
<?php echo '<scr' . 'ipt>'; ?>
	(function($) {
		"use strict";

		$( document ).ready( function() {
			var adminbarHeight = $( '#wpadminbar' ).length ? $( '#wpadminbar' ).height() : 0,
			    offset   = <?php echo esc_js( $offset ); ?>,
			    content  = $( '.p-single-info' ).outerHeight();

			setTimeout( function() {
				var height = ( $( window ).height() - $( '.header-outer' ).height() - adminbarHeight );
				if ( height >= content + 20 ) {
					$( '.p-single-top, .p-gallery .item' ).css( 'height', ( height - offset * 2 ) );
				} else {
					$( '.p-single-top, .p-gallery .item' ).css( 'height', ( content + 30 ) );
				}
			}, 100);

			if ( ! $.WR.product_image_style_4_initialized ) {
				$( window ).on( 'resize', function() {
					var height   = ( $( window ).height() - $( '.header-outer' ).height() - adminbarHeight ),
						content  = $( '.p-single-info' ).outerHeight();
					if ( window.innerHeight >= 730 && height >= content + 20 ) {
						$( '.p-single-top, .p-gallery .item' ).css( 'height', height - offset * 2 );
					} else {
						$( '.p-single-top, .p-gallery .item' ).css( 'height', ( content + 30 ) );
					}
				});

				$.WR.product_image_style_4_initialized = true;
			}

			$.WR.Lightbox();
			$.WR.Carousel();
		} );
	})(jQuery);
<?php echo '</scr' . 'ipt>'; ?>
<?php
	// Embed video to product thumbnail
	$video_source = get_post_meta( $post->ID, 'wc_product_video', true );
	$video_link   = get_post_meta( $post->ID, 'wc_product_video_url', true );
	$video_file   = get_post_meta( $post->ID, 'wc_product_video_file', true );

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
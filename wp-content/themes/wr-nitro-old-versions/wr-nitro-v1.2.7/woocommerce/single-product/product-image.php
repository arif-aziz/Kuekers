<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

$wr_nitro_options = WR_Nitro::get_options();
$count = count( $product->get_gallery_attachment_ids() );

// Get single style
$single_style = get_post_meta( get_the_ID(), 'single_style', true );
if ( $single_style == 0 ) {
	$single_style = $wr_nitro_options['wc_single_style'];
} else {
	$single_style = get_post_meta( get_the_ID(), 'single_style', true );
}
if ( wp_is_mobile() ) {
	wc_get_template( 'woorockets/single-product/product-image/style-mobile.php' );
} else {
	wc_get_template( 'woorockets/single-product/product-image/style-' . $single_style . '.php' );
}
?>

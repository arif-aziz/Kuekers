<?php
/**
 * External product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Get theme option
$wr_nitro_options = WR_Nitro::get_options();

// Catalog mode
$wr_catalog_mode = $wr_nitro_options['wc_archive_catalog_mode'];
if ( $wr_catalog_mode ) return;

// Get single style
$single_style = get_post_meta( get_the_ID(), 'single_style', true );
if ( $single_style == 0 ) {
	$single_style = $wr_nitro_options['wc_single_style'];
} else {
	$single_style = get_post_meta( get_the_ID(), 'single_style', true );
}

// Icon Set
$icons = $wr_nitro_options['wc_icon_set'];
?>
<?php
	if ( $single_style != 1 ) {
		echo '<div class="p-single-action nitro-line btn-inline pdb20 fc aic aife">';
	}
?>
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<p class="cart mg0">
		<a href="<?php echo esc_url( $product_url ); ?>" rel="nofollow" class="single_add_to_cart_button button alt btr-50 db pdl20 pdr20 fl"><i class="nitro-icon-<?php echo esc_attr( $icons ); ?>-cart mgr10 mgt10"></i><?php echo esc_html( $button_text ); ?></a>
	</p>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
<?php
	if ( $single_style != 1 ) {
		echo '</div>';
	}
?>

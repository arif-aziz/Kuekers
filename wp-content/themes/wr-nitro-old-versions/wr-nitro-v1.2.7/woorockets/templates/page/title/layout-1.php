<?php
/**
 * @version    1.0
 * @package    Nitro
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Render page title.
 */

// Get options
$wr_nitro_options = WR_Nitro::get_options();

$wr_classes = $wr_attr = array();

if ( $wr_nitro_options['wr_page_title_fullscreen'] ) {
	$wr_classes[] = 'full';
}

// Get background color and image
if ( ! $wr_nitro_options['use_global'] && ! is_single() ) {
	$wr_image_id = $wr_nitro_options['wr_page_title_bg_image'];
	$wr_image    = isset( $wr_image_id ) ? wp_get_attachment_url( $wr_image_id ) : '';
} else {
	$wr_image = $wr_nitro_options['wr_page_title_bg_image'];
}

// Footer background image.
if ( ! empty( $wr_image ) ) {
	// Parallax
	if ( $wr_nitro_options['wr_page_title_parallax'] ) {
		$wr_attr[] = 'data-0="background-position:0px 0px" data-5000="background-position: 0px -2000px;"';
	}
}

// Get page description
$wr_page_desc = get_post_meta( get_the_ID(), 'wr_page_title_desc', true  );
?>
<div class="site-title style-1 pr <?php echo esc_attr( implode( ' ', $wr_classes ) ) . ' ' . ( is_customize_preview() ? 'customizable customize-section-page_title ' : '' );  ?>" <?php echo implode( '', $wr_attr ); ?>>
	<div class="container fc jcsb aic">
		<?php do_action( 'nitro_top_of_page_title' ); ?>
		<div class="title-desc">
			<h1 <?php WR_Nitro_Helper::schema_metadata( array( 'context' => 'entry_title' ) ); ?>>
				<?php WR_Nitro_Helper::page_title(); ?>
			</h1>
			<?php
				if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					do_action( 'woocommerce_archive_description' );
				}
			?>
			<?php
				if ( ! $wr_nitro_options['use_global'] && ! empty( $wr_page_desc ) ) {
					echo '<p class="desc mg0"> ' . $wr_nitro_options['wr_page_title_desc'] . '</p>';
				}
			?>
		</div>
		<?php
			if ( $wr_nitro_options['wr_page_title_breadcrumbs'] ) {
				if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					woocommerce_breadcrumb();
				} else {
					WR_Nitro_Render::get_template( 'common/breadcrumbs' );
				}
			}
		?>
		<?php do_action( 'nitro_bottom_of_page_title' ); ?>
	</div><!-- .container -->
	<div class="mask"></div>
</div><!-- .site-title -->

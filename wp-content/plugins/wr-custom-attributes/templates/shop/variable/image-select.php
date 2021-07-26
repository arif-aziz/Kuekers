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
?>
<ul class="wr-custom-attribute image-select" data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
	<?php
	if ( $options ):

	foreach ( $options as $term ) :

	// Get custom data.
	$image   = get_woocommerce_term_meta( $term->term_id, 'wr_image'   );
	$tooltip = get_woocommerce_term_meta( $term->term_id, 'wr_tooltip' );

	// Check if attribute value has own image gallery.
	$meta_key      = "_product_image_gallery_{$term->taxonomy}-{$term->slug}";
	$image_gallery = get_post_meta( $product->get_id(), $meta_key, true );
	?>
	<li class="<?php if ( $term->slug == $selected ) echo 'selected'; ?>">
		<a href="javascript:void(0)" class="wr-tooltip <?php
			if ( $image_gallery ) {
				echo 'has-image-gallery';
			}
		?>" 
		data-value="<?php echo esc_attr( $term->slug ); ?>" 
		data-href="<?php
			if ( $image_gallery ) {
				echo esc_url( add_query_arg( array(
					'action'  => 'wr-get-product-image-gallery',
					'product' => $product->get_id()
				), admin_url( 'admin-ajax.php' ) ) );
			}
		?>">
			<img src="<?php echo esc_url( $image ); ?>">
			<?php if ( $tooltip ) : ?>
			<span>
				<?php echo esc_attr( $tooltip ); ?>
			</span>
			<?php endif; ?>
		</a>
	</li>
	<?php
	endforeach;

	endif;
	?>
</ul>

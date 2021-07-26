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
<ul class="wr-custom-attribute image-select">
	<?php
	if ( $options ) :

	foreach ( $options as $term ) :

	// Get custom data.
	$image   = get_woocommerce_term_meta( $term->term_id, 'wr_image'   );
	$tooltip = get_woocommerce_term_meta( $term->term_id, 'wr_tooltip' );
	?>
	<li>
		<a href="javascript:void(0)" class="wr-tooltip">
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

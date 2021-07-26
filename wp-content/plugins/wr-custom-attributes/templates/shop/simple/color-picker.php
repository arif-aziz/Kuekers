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
<ul class="wr-custom-attribute color-picker">
	<?php
	if ( $options ):

	foreach ( $options as $term ) :

	// Get custom data.
	$color   = get_woocommerce_term_meta( $term->term_id, 'wr_color'   );
	$tooltip = get_woocommerce_term_meta( $term->term_id, 'wr_tooltip' );
	?>
	<li>
		<a href="javascript:void(0)" class="wr-tooltip" style="background-color: <?php echo esc_attr( $color ); ?>">
			&nbsp;
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

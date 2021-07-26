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
<ul class="wr-custom-attribute text-label" data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
	<?php
	if ( $options ):

	foreach ( $options as $term ):

	// Get custom data.
	$label   = get_woocommerce_term_meta( $term->term_id, 'wr_label'   );
	$tooltip = get_woocommerce_term_meta( $term->term_id, 'wr_tooltip' );

	if ( empty( $label ) ) {
		$label = $term->name;
	}
	?>
	<li class="<?php if ( $term->slug == $selected ) echo 'selected'; ?>">
		<a href="javascript:void(0)" class="wr-tooltip" data-value="<?php echo esc_attr( $term->slug ); ?>">
			<?php
			echo esc_html( $label );

			if ( $tooltip ) :
			?>
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

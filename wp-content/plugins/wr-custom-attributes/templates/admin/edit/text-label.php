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
<tr class="form-field wr-label-wrap">
	<th scope="row"><label for="wr-label"><?php _e( 'Label', 'wr-custom-attributes' ); ?></label></th>
	<td>
		<input name="wr_label" id="wr-label" type="text" value="<?php
			echo esc_attr( get_woocommerce_term_meta( $tag->term_id, 'wr_label' ) );
		?>">
		<p class="description"></p>
	</td>
</tr>
<tr class="form-field wr-tooltip-wrap">
	<th scope="row"><label for="wr-tooltip"><?php _e( 'Tooltip', 'wr-custom-attributes' ); ?></label></th>
	<td>
		<input name="wr_tooltip" id="wr-tooltip" type="text" value="<?php
			echo esc_attr( get_woocommerce_term_meta( $tag->term_id, 'wr_tooltip' ) );
		?>">
		<p class="description"></p>
	</td>
</tr>

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

// Enqueue Color Picker.
wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_style( 'wp-color-picker' );
?>
<tr class="form-field wr-color-wrap">
	<th scope="row"><label for="wr-color"><?php _e( 'Color', 'wr-custom-attributes' ); ?></label></th>
	<td>
		<input name="wr_color" id="wr-color" type="text" value="<?php
			echo esc_attr( get_woocommerce_term_meta( $tag->term_id, 'wr_color' ) );
		?>">
		<p class="description"><?php _e( 'Please pick a color.', 'wr-custom-attributes' ); ?></p>
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
<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('.wr-color-wrap input#wr-color').wpColorPicker();
		});
	})(jQuery);
</script>

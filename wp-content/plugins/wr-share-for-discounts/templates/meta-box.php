<?php
/**
 * @version    1.0
 * @package    WR_Share_For_Discounts
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2014 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 */
?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_share_for_discounts"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
			</th>
			<td>
				<input type="checkbox" value="1" id="enable_share_for_discounts" name="wr_share_for_discounts[enable]" <?php
					checked( $meta_data['enable'], 1 );
				?> autocomplete="off">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="share_for_discounts_type"><?php _e( 'Discount Type', 'wr-share-for-discounts' ); ?></label>
			</th>
			<td>
				<select id="share_for_discounts_type" name="wr_share_for_discounts[type]" aria-describedby="share_for_discounts_type-description" autocomplete="off">
					<option value="individual" <?php selected( $meta_data['type'], 'individual' ); ?>><?php
						_e( 'Individual', 'wr-share-for-discounts' );
					?></option>
					<option value="mass" <?php selected( $meta_data['type'], 'mass' ); ?>><?php
						_e( 'Mass', 'wr-share-for-discounts' );
					?></option>
				</select>
				<p id="share_for_discounts_type-description" class="description">
					<?php _e( '<b>Individual</b> applies discount for the user who like/share the product only.', 'wr-share-for-discounts' ); ?>
					<br>
					<?php _e( '<b>Mass</b> reduces the product price by the number of like/share received multiplying with the amount defined below.', 'wr-share-for-discounts' ); ?>
				</p>
				<script type="text/javascript">
					(function($) {
						$('select#share_for_discounts_type').change(function() {
							if ($(this).val() == 'individual') {
								$(this).closest('tr').next().addClass('hidden');
							} else {
								$(this).closest('tr').next().removeClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( 'individual' == $meta_data['type'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="share_for_discounts_min_price"><?php _e( 'Minimum Price', 'wr-share-for-discounts' ); ?></label>
			</th>
			<td>
				<input type="number" id="share_for_discounts_min_price" name="wr_share_for_discounts[min_price]" value="<?php
					echo ( isset( $meta_data['min_price'] ) ? esc_attr( $meta_data['min_price'] ) : NULL );
				?>" aria-describedby="share_for_discounts_min_price-description" autocomplete="off">
				<p id="share_for_discounts_min_price-description" class="description">
					<?php _e( 'The minimum product price at which discount will not be applied any more.', 'wr-share-for-discounts' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="share_for_discounts_amount"><?php _e( 'Discount Amount', 'wr-share-for-discounts' ); ?></label>
			</th>
			<td>
				<input type="number" id="share_for_discounts_amount" name="wr_share_for_discounts[amount]" value="<?php
					echo esc_attr( $meta_data['amount'] );
				?>" autocomplete="off">
				<select id="share_for_discounts_unit" name="wr_share_for_discounts[unit]" autocomplete="off">
					<option value="percent" <?php selected( $meta_data['unit'], 'percent' ); ?>><?php
						_e( '%', 'wr-share-for-discounts' );
					?></option>
					<option value="fixed" <?php selected( $meta_data['unit'], 'fixed' ); ?>><?php
						echo '' . get_woocommerce_currency_symbol();
					?></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

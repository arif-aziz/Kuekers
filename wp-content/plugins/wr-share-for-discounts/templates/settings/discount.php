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
<h3><?php _e( 'Product Discount', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_product_discount"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing to get product discount.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_product_discount" <?php
					checked( $settings['enable_product_discount'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_product_discount]" value="<?php
					echo esc_attr( $settings['enable_product_discount'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_product_discount').change(function() {
							if (this.checked) {
								var next = $(this).closest('tr').next().removeClass('hidden');

								if (next.find('#product_discount_type').val() == 'individual') {
									next = next.next().addClass('hidden');
								} else {
									next = next.next().removeClass('hidden');
								}

								next.next().removeClass('hidden').next().removeClass('hidden').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_product_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="product_discount_type"><?php _e( 'Discount Type', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Select type for product discount.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<select id="product_discount_type" name="wr_share_for_discounts[product_discount_type]" aria-describedby="product_discount_type-description" autocomplete="off">
					<option value="individual" <?php selected( $settings['product_discount_type'], 'individual' ); ?>><?php
						_e( 'Individual', 'wr-share-for-discounts' );
					?></option>
					<option value="mass" <?php selected( $settings['product_discount_type'], 'mass' ); ?>><?php
						_e( 'Mass', 'wr-share-for-discounts' );
					?></option>
				</select>
				<p id="product_discount_type-description" class="description">
					<?php _e( '<b>Individual</b> applies discount for the user who like/share the product only.', 'wr-share-for-discounts' ); ?>
					<br>
					<?php _e( '<b>Mass</b> reduces the product price by the number of like/share received multiplying with the amount defined below.', 'wr-share-for-discounts' ); ?>
				</p>
				<script type="text/javascript">
					(function($) {
						$('select#product_discount_type').change(function() {
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
		<tr class="<?php if ( ! $settings['enable_product_discount'] || 'individual' == $settings['product_discount_type'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="mass_discount_min_price"><?php _e( 'Minimum Price', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The minimum product price at which discount will not be applied any more.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="number" id="mass_discount_min_price" name="wr_share_for_discounts[mass_discount_min_price]" value="<?php
					echo esc_attr( $settings['mass_discount_min_price'] );
				?>" autocomplete="off">
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_product_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="product_discount_amount"><?php _e( 'Discount Amount', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The amount of discount to apply for the selected product.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="number" id="product_discount_amount" name="wr_share_for_discounts[product_discount_amount]" value="<?php
					echo esc_attr( $settings['product_discount_amount'] );
				?>" autocomplete="off">
				<select id="product_discount_unit" name="wr_share_for_discounts[product_discount_unit]" autocomplete="off">
					<option value="percent" <?php selected( $settings['product_discount_unit'], 'percent' ); ?>><?php
						_e( '%', 'wr-share-for-discounts' );
					?></option>
					<option value="fixed" <?php selected( $settings['product_discount_unit'], 'fixed' ); ?>><?php
						echo '' . get_woocommerce_currency_symbol();
					?></option>
				</select>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_product_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="discounted_products"><?php _e( 'Discounted Products', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Select products to apply discount for. You can also setting in each product.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="hidden" class="wc-product-search" id="discounted_products" name="wr_share_for_discounts[discounted_products]" data-placeholder="<?php
					_e( 'Search for a product&hellip;', 'wr-share-for-discounts' );
				?>" data-action="woocommerce_json_search_products" data-multiple="true" data-selected="<?php
					$product_ids = array_filter( array_map( 'absint', explode( ',', $settings['discounted_products'] ) ) );
					$json_ids    = array();

					foreach ( $product_ids as $product_id ) {
						if ( $product = wc_get_product( $product_id ) ) {
							$json_ids[ $product_id ] = wp_kses_post( html_entity_decode(
								$product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' )
							) );
						}
					}

					echo esc_attr( json_encode( $json_ids ) );
				?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>">
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_product_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="product_discount_button"><?php _e( 'Button Text', 'wr-share-for-discounts' ); ?></label>
			</th>
			<td>
				<input type="text" id="product_discount_button" name="wr_share_for_discounts[product_discount_button]" value="<?php
					echo esc_attr( $settings['product_discount_button'] );
				?>" autocomplete="off">
				<p id="product_discount_type-description" class="description">
					<?php _e( 'Use <b>%s</b> to showing amount value.', 'wr-share-for-discounts' ); ?>
				</p>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_product_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="product_discount_message"><?php _e( 'Message To Users', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'A public message to display above like/share buttons.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<?php
				// Prepare content for WYSIWYG editor.
				$content = isset( $settings['product_discount_message'] ) ? $settings['product_discount_message'] : '';

				if ( ! empty( $content ) ) {
					$content = str_replace( '\\', '', $content );
				}

				wp_editor( $content, 'product_discount_message' );
				?>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e( 'Cart Discount', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_cart_discount"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing to get cart discount.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_cart_discount" <?php
					checked( $settings['enable_cart_discount'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_cart_discount]" value="<?php
					echo esc_attr( $settings['enable_cart_discount'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_cart_discount').change(function() {
							if (this.checked) {
								$(this).closest('tr').next().removeClass('hidden').next().removeClass('hidden').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_cart_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="cart_discount_amount"><?php _e( 'Discount Amount', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The amount of discount to apply for cart total.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="number" id="cart_discount_amount" name="wr_share_for_discounts[cart_discount_amount]" value="<?php
					echo esc_attr( $settings['cart_discount_amount'] );
				?>" autocomplete="off">
				<select id="cart_discount_type" name="wr_share_for_discounts[cart_discount_type]" autocomplete="off">
					<option value="percent" <?php selected( $settings['cart_discount_type'], 'percent' ); ?>><?php
						_e( '%', 'wr-share-for-discounts' );
					?></option>
					<option value="fixed_cart" <?php selected( $settings['cart_discount_type'], 'fixed_cart' ); ?>><?php
						echo '' . get_woocommerce_currency_symbol();
					?></option>
				</select>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_cart_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="cart_discount_link"><?php _e( 'Link to Share', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The link to share.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="text" id="cart_discount_link" name="wr_share_for_discounts[cart_discount_link]" class="regular-text" value="<?php
					echo '' . $settings['cart_discount_link'];
				?>" autocomplete="off">
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_cart_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="cart_discount_message"><?php _e( 'Message To Users', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'A public message to display above like/share buttons.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<?php
				// Prepare content for WYSIWYG editor.
				$content = isset( $settings['cart_discount_message'] ) ? $settings['cart_discount_message'] : '';

				if ( ! empty( $content ) ) {
					$content = str_replace( '\\', '', $content );
				}

				wp_editor( $content, 'cart_discount_message' );
				?>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e( 'Discount Coupon', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_discount_coupon"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing to get discount coupon.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_discount_coupon" <?php
					checked( $settings['enable_discount_coupon'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_discount_coupon]" value="<?php
					echo esc_attr( $settings['enable_discount_coupon'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_discount_coupon').change(function() {
							if (this.checked) {
								$(this).closest('tr').next().removeClass('hidden').next().removeClass('hidden').next().removeClass('hidden').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_discount_coupon'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="discount_coupon_amount"><?php _e( 'Discount Amount', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The amount of discount to apply for coupon.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="number" id="discount_coupon_amount" name="wr_share_for_discounts[discount_coupon_amount]" value="<?php
					echo esc_attr( $settings['discount_coupon_amount'] );
				?>" autocomplete="off">
				<select id="discount_coupon_type" name="wr_share_for_discounts[discount_coupon_type]" autocomplete="off">
					<option value="fixed_cart" <?php selected( $settings['discount_coupon_type'], 'fixed_cart' ); ?>><?php
						_e( 'Cart Discount', 'wr-share-for-discounts' );
					?></option>
					<option value="percent" <?php selected( $settings['discount_coupon_type'], 'percent' ); ?>><?php
						_e( 'Cart % Discount', 'wr-share-for-discounts' );
					?></option>
					<option value="fixed_product" <?php selected( $settings['discount_coupon_type'], 'fixed_product' ); ?>><?php
						_e( 'Product Discount', 'wr-share-for-discounts' );
					?></option>
					<option value="percent_product" <?php selected( $settings['discount_coupon_type'], 'percent_product' ); ?>><?php
						_e( 'Product % Discount', 'wr-share-for-discounts' );
					?></option>
				</select>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_discount_coupon'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="discount_coupon_link"><?php _e( 'Link to Share', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The link to share.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="text" id="discount_coupon_link" name="wr_share_for_discounts[discount_coupon_link]" class="regular-text" value="<?php
					echo '' . $settings['discount_coupon_link'];
				?>" autocomplete="off">
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_cart_discount'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="discount_coupon_message"><?php _e( 'Message To Users', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'A public message to display above like/share buttons.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<?php
				// Prepare content for WYSIWYG editor.
				$content = isset( $settings['discount_coupon_message'] ) ? $settings['discount_coupon_message'] : '';

				if ( ! empty( $content ) ) {
					$content = str_replace( '\\', '', $content );
				}

				wp_editor( $content, 'discount_coupon_message' );
				?>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_discount_coupon'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label><?php _e( 'Shortcode', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'The shortcode to show share for discounts widget.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<?php _e( 'Copy and paste following shortcode to where you want to display <b>Share for Discounts</b>.' ); ?><br><br>
				[share_for_discounts]
			</td>
		</tr>
	</tbody>
</table>

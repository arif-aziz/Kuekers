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
<h3><?php _e( 'Facebook Sharing', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_facebook_sharing"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing via Facebook. Require a Facebook app to work properly.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_facebook_sharing" <?php
					checked( $settings['enable_facebook_sharing'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_facebook_sharing]" value="<?php
					echo esc_attr( $settings['enable_facebook_sharing'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_facebook_sharing').change(function() {
							if (this.checked) {
								$(this).closest('tr').next().removeClass('hidden').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_facebook_sharing'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="facebook_app_id"><?php _e( 'App ID', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Insert your Facebook app ID.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="number" id="facebook_app_id" name="wr_share_for_discounts[facebook_app_id]" value="<?php
					echo esc_attr( $settings['facebook_app_id'] );
				?>" autocomplete="off">
				<a target="_blank" rel="noopener noreferrer" href="https://developers.facebook.com/apps"><?php _e( 'Get Facebook App ID', 'wr-share-for-discounts' ); ?></a>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_facebook_sharing'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="facebook_button_type"><?php _e( 'Button Type', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Select the type of button you want to show for Facebook', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<select id="facebook_button_type" name="wr_share_for_discounts[facebook_button_type]" autocomplete="off">
					<option value="both" <?php selected( $settings['facebook_button_type'], 'both' ); ?>><?php
						_e( 'Like and Share Buttons', 'wr-share-for-discounts' );
					?></option>
					<option value="like" <?php selected( $settings['facebook_button_type'], 'like' ); ?>><?php
						_e( 'Like Button Only', 'wr-share-for-discounts' );
					?></option>
					<option value="share" <?php selected( $settings['facebook_button_type'], 'share' ); ?>><?php
						_e( 'Share Button Only', 'wr-share-for-discounts' );
					?></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e( 'Twitter Sharing', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_twitter_sharing"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing via Twitter.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_twitter_sharing" <?php
					checked( $settings['enable_twitter_sharing'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_twitter_sharing]" value="<?php
					echo esc_attr( $settings['enable_twitter_sharing'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_twitter_sharing').change(function() {
							if (this.checked) {
								$(this).closest('tr').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_twitter_sharing'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="twitter_username"><?php _e( 'Twitter Username', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Set this option if you want to include "via @YourUsername" to your tweets', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="text" class="regular-text" id="twitter_username" name="wr_share_for_discounts[twitter_username]" value="<?php
					echo esc_attr( $settings['twitter_username'] );
				?>" autocomplete="off">
			</td>
		</tr>
	</tbody>
</table>

<h3><?php _e( 'Google+ Sharing', 'wr-share-for-discounts' ); ?></h3>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable_google_plus_sharing"><?php _e( 'Enable?', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Enable sharing via Google+.', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<input type="checkbox" id="enable_google_plus_sharing" <?php
					checked( $settings['enable_google_plus_sharing'], 1 );
				?> autocomplete="off" onclick="jQuery(this).next().val(this.checked ? 1 : 0);">
				<input type="hidden" name="wr_share_for_discounts[enable_google_plus_sharing]" value="<?php
					echo esc_attr( $settings['enable_google_plus_sharing'] );
				?>">
				<script type="text/javascript">
					(function($) {
						$('input#enable_google_plus_sharing').change(function() {
							if (this.checked) {
								$(this).closest('tr').next().removeClass('hidden');
							} else {
								$(this).closest('tr').next().addClass('hidden');
							}
						});
					})(jQuery);
				</script>
			</td>
		</tr>
		<tr class="<?php if ( ! $settings['enable_google_plus_sharing'] ) echo 'hidden'; ?>">
			<th scope="row">
				<label for="google_plus_button_type"><?php _e( 'Button Type', 'wr-share-for-discounts' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php
					_e( 'Select the type of button you want to show for Google+.<br>', 'wr-share-for-discounts' );
				?>"></span>
			</th>
			<td>
				<select id="google_plus_button_type" name="wr_share_for_discounts[google_plus_button_type]" aria-describedby="google_plus_button_type-description" autocomplete="off">
					<option value="both" <?php selected( $settings['google_plus_button_type'], 'both' ); ?>><?php
						_e( '+1 and Share Buttons', 'wr-share-for-discounts' );
					?></option>
					<option value="like" <?php selected( $settings['google_plus_button_type'], 'like' ); ?>><?php
						_e( '+1 Button Only', 'wr-share-for-discounts' );
					?></option>
					<option value="share" <?php selected( $settings['google_plus_button_type'], 'share' ); ?>><?php
						_e( 'Share Button Only', 'wr-share-for-discounts' );
					?></option>
				</select>
				<p id="google_plus_button_type-description" class="description"><?php
					_e( 'Note: because of a bug unresolved by Google, the "Share" button could not generate the coupon correctly. For more information about the bug, please <a href="https://code.google.com/p/google-plus-platform/issues/detail?id=232" target="_blank" rel="noopener noreferrer">click here</a>.', 'wr-share-for-discounts' );
				?></p>
			</td>
		</tr>
	</tbody>
</table>

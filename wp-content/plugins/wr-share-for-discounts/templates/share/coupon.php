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

// Get current discount data.
$discount = isset( $_COOKIE[ WR_S4D ] ) ? maybe_unserialize( stripslashes( $_COOKIE[ WR_S4D ] ) ) : array();
?>
<div class="wr-product-share" data-type="<?php echo esc_attr( $type ); ?>">
	<?php if ( isset( $discount['discount_coupon'] ) && ! empty( $discount['discount_coupon'] ) ) : ?>
	<h3><?php _e( 'Thank you for sharing!', 'wr-share-for-discounts' ); ?></h3>
	<p>
		<?php _e( 'To express our thankful with you, we&#39;ve created the one-time coupon code below to apply to your cart.', 'wr-share-for-discounts' ); ?>
		<br>
		<strong><?php echo esc_html( $discount['discount_coupon'] ); ?></strong>
	</p>
	<?php else : ?>
	<h3><?php _e( 'Share for Discounts:', 'wr-share-for-discounts' ); ?></h3>
	<?php
	if ( isset( $settings['discount_coupon_message'] ) && ! empty( $settings['discount_coupon_message'] ) ) {
		echo '<div class="wr-share-message">' . wp_kses_post( str_replace( '\\', '', $settings['discount_coupon_message'] ) ) . '</div>';
	}
	?>
	<div class="share-boxs">
		<?php if ( $settings['enable_facebook_sharing'] ) : ?>
		<div id="fb-root"></div>
		<div class="facebook-sharing">
			<?php if ( 'share' != $settings['facebook_button_type'] ) : ?>
			<div class="fb-like"
				data-href="<?php echo '' . $settings['discount_coupon_link']; ?>"
				data-layout="button"
				data-action="like"
				data-show-faces="false"
				data-share="false"
			></div>
			<?php
			endif;

			if ( 'like' != $settings['facebook_button_type'] ) :
			?>
			<a class="wr-share-to-fb-button" href="javascript:void(0)" data-href="<?php echo '' . $settings['discount_coupon_link']; ?>">
				<span class="fb-image"></span><span class="fb-text"><?php _e( 'Share', 'wr-share-for-discounts' ); ?></span>
			</a>
			<?php endif; ?>
		</div>
		<?php
		endif;

		if ( $settings['enable_twitter_sharing'] ) :
		?>
		<div class="twitter-sharing">
			<a href="https://twitter.com/share" class="twitter-share-button"
				data-count="none"
				data-url="<?php echo '' . $settings['discount_coupon_link']; ?>"
				data-via="<?php echo esc_attr( $settings['twitter_username'] ); ?>"
			>
				<?php _e( 'Tweet', 'wr-share-for-discounts' ); ?>
			</a>
		</div>
		<?php
		endif;

		if ( $settings['enable_google_plus_sharing'] ) :
		?>
		<script>window.___gcfg = { lang: 'EN', parsetags: 'explicit' };</script>
		<script src="https://apis.google.com/js/platform.js" async defer></script>
		<div class="google-plus-sharing">
			<?php if ( 'share' != $settings['google_plus_button_type'] ) : ?>
			<div class="g-plusone"
				data-size="medium"
				data-annotation="none"
				data-callback="wr_google_plus_one"
				data-href="<?php echo '' . $settings['discount_coupon_link']; ?>"
			></div>
			<?php
			endif;

			if ( 'like' != $settings['google_plus_button_type'] ) :
			?>
			<div class="g-plus"
				data-action="share"
				data-size="medium"
				data-annotation="none"
				data-href="<?php echo '' . $settings['discount_coupon_link']; ?>"
				data-onstartinteraction="wr_google_plus_share"
				data-onendinteraction="wr_google_plus_share_stop"
			></div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<iframe id="wr-share-for-discounts" src="about:blank" style="display:none"></iframe>
	<?php endif; ?>
</div>

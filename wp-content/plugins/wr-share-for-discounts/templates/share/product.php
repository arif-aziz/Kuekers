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

global $product;
?>
<div class="wr-product-share" data-type="<?php echo esc_attr( $type ); ?>">
	<h3><?php _e( 'Share for Discounts', 'wr-share-for-discounts' ); ?></h3>
	<?php

		// Get share settings on each product
		$product_share = get_post_meta( $product->get_id(), 'wr_share_for_discounts', true );

		if ( isset( $product_share['enable'] ) && $product_share['enable'] == '1' ) {
			$output        = $product_share['amount'];
			$discount_unit = $product_share['unit'];
		} else {
			$output        = $settings['product_discount_amount'];
			$discount_unit = $settings['product_discount_unit'];
		}

		if ( 'percent' != $discount_unit ) {
			$output .= get_woocommerce_currency_symbol();
		} else {
			$output .= '%';
		}

		if ( isset( $settings['product_discount_button'] ) && ! empty( $settings['product_discount_button'] ) ) {
			$button = str_replace( '%s', $output, $settings['product_discount_button'] );
			echo '<div class="wr-share-button"><a href="#" class="wr-btn wr-btn-outline">' . esc_html( $button ) . '</a></div>';
		}
	?>
	<div class="wr-share-modal">
		<div class="wr-share-modal-content container">
			<div class="wr-share-modal-inner">
				<?php
					if ( isset( $settings['product_discount_message'] ) && ! empty( $settings['product_discount_message'] ) ) {
						echo '<div class="wr-share-message">' . wp_kses_post( str_replace( '\\', '', $settings['product_discount_message'] ) ) . '</div>';
					}
				?>
				<div class="share-boxs">
					<?php if ( $settings['enable_facebook_sharing'] ) : ?>
					<div id="fb-root"></div>
					<div class="facebook-sharing">
						<?php if ( 'share' != $settings['facebook_button_type'] ) : ?>
						<div class="fb-like"
							data-href="<?php echo get_permalink(); ?>"
							data-layout="button"
							data-action="like"
							data-show-faces="false"
							data-share="false"
						></div>
						<?php
						endif;

						if ( 'like' != $settings['facebook_button_type'] ) :
						?>
						<a class="wr-share-to-fb-button" href="javascript:void(0)" data-href="<?php echo get_permalink(); ?>">
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
							data-url="<?php echo get_permalink(); ?>"
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
							data-href="<?php echo get_permalink(); ?>"
							data-callback="wr_google_plus_one"
							data-onstartinteraction="wr_google_plus_share"
							data-onendinteraction="wr_google_plus_share_stop"
						></div>
						<?php
						endif;

						if ( 'like' != $settings['google_plus_button_type'] ) :
						?>
						<div class="g-plus"
							data-action="share"
							data-size="medium"
							data-annotation="none"
							data-href="<?php echo get_permalink(); ?>"
							data-onstartinteraction="wr_google_plus_share"
							data-onendinteraction="wr_google_plus_share_stop"
						></div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<iframe id="wr-share-for-discounts" src="about:blank" style="display:none"></iframe>
			</div>
		</div>
	</div>
</div>

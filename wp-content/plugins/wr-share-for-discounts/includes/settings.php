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

/**
 * Define settings class.
 */
class WR_Share_For_Discounts_Settings {
	/**
	 * Add Share for Discounts tab into WooCommerce settings screen.
	 *
	 * @param   array  $tabs  Current tabs for the settings page.
	 *
	 * @return  array
	 */
	public static function register( $tabs ) {
		// Add Share for Discounts tab.
		$tabs[ WR_S4D ] = __( 'Share for Discounts', 'wr-share-for-discounts' );

		// Load stylesheet for tab content.
		if ( isset( $_REQUEST['tab'] ) && WR_S4D == $_REQUEST['tab'] ) {
			wp_enqueue_style( WR_S4D, WR_S4D_URL . 'assets/css/admin.css' );
		}

		return $tabs;
	}

	/**
	 * Print HTML for the Share for Discounts configuration page.
	 *
	 * @return  void
	 */
	public static function show() {
		// Get current settings.
		$settings = WR_Share_For_Discounts::get_settings();

		// Get current section.
		$section = isset( $_REQUEST['section'] ) ? $_REQUEST['section'] : null;

		if ( empty( $section ) || ! in_array( $section, array( 'sharing', 'discount' ) ) ) {
			$section = 'sharing';
		}

		// Generate base settings URL.
		$url = admin_url( 'admin.php?page=wc-settings&tab=' . WR_S4D );

		// Load template file.
		include_once WR_S4D_PATH . 'templates/settings.php';
	}

	/**
	 * Method to validate and save Share for Discounts settings.
	 *
	 * @return  void
	 */
	public static function save() {
		// Verify request.
		if ( ! isset( $_POST['wr_share_for_discounts'] ) || ! count( $_POST['wr_share_for_discounts'] ) ) {
			return;
		}

		// Get current settings.
		$settings = self::get();

		foreach ( $settings as $option => $value ) {
			if ( isset( $_POST['wr_share_for_discounts'][ $option ] ) ) {
				if ( strpos( $option, 'enable_' ) !== false ) {
					$settings[ $option ] = intval( $_POST['wr_share_for_discounts'][ $option ] );
				} elseif ( strpos( $option, '_amount' ) !== false ) {
					$settings[ $option ] = floatval( $_POST['wr_share_for_discounts'][ $option ] );
				} else {
					$settings[ $option ] = sanitize_text_field( $_POST['wr_share_for_discounts'][ $option ] );
				}
			}
		}

		// Save content of WYSIWYG editor.
		foreach ( array( 'product_discount_message', 'cart_discount_message', 'discount_coupon_message' ) as $msg ) {
			if ( isset( $_POST[ $msg ] ) ) {
				$settings[ $msg ] = wp_kses_post( $_POST[ $msg ] );
			}
		}

		update_option( 'wr_share_for_discounts', $settings );
	}

	/**
	 * Method to get settings for Share for Discounts.
	 *
	 * @return  array
	 */
	public static function get() {
		// Get saved settings.
		$settings = get_option( 'wr_share_for_discounts' );

		// Apply default values.
		$settings = wp_parse_args(
			$settings,
			array(
				'enable_facebook_sharing' => 0,
				'facebook_app_id'         => null,
				'facebook_button_type'    => 'both',

				'enable_twitter_sharing' => 0,
				'twitter_username'       => null,

				'enable_google_plus_sharing' => 0,
				'google_plus_button_type'    => 'both',

				'enable_product_discount'  => 0,
				'product_discount_type'    => 'individual',
				'mass_discount_min_price'  => 0,
				'product_discount_amount'  => 0,
				'product_discount_unit'    => 'percent',
				'discounted_products'      => '',
				'product_discount_button'  => 'Share for discount %s',
				'product_discount_message' => '',

				'enable_cart_discount'  => 0,
				'cart_discount_amount'  => 0,
				'cart_discount_type'    => 'percent',
				'cart_discount_link'    => site_url(),
				'cart_discount_message' => '',

				'enable_discount_coupon'  => 0,
				'discount_coupon_amount'  => 0,
				'discount_coupon_type'    => 'percent',
				'discount_coupon_link'    => site_url(),
				'discount_coupon_message' => '',
			)
		);

		return $settings;
	}

	/**
	 * Add link setting in list plugin.
	 *
	 * @param   string  $links  List link current.
	 *
	 * @return  array
	 */
	public static function add_action_links( $links ) {
		$links['settings'] = '<a title="' . __( 'Open Share for Discounts Settings', 'wr-share-for-discounts' ) . '" href="' . admin_url( 'admin.php?page=wc-settings&tab=' . WR_S4D ) . '">' . __( 'Settings', 'wr-share-for-discounts' ) . '</a>';

		return $links;
	}
}

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
 * Define shortcode class.
 */
class WR_Share_For_Discounts_Shortcode {
	/**
	 * Method to generate and return HTML code for 'share_for_discounts' shortcode tag.
	 *
	 * @return  string
	 */
	public static function generate() {
		// Start output buffering.
		ob_start();

		// Init Share for Discounts.
		WR_Share_For_Discounts_Share::init( 'coupon' );

		// Print HTML.
		WR_Share_For_Discounts_Share::show( 'coupon' );

		// Get output.
		$html = ob_get_clean();

		return $html;
	}
}

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
 * Define meta box class.
 */
class WR_Share_For_Discounts_Meta_Box {
	/**
	 * Method to add meta box into the edit screen of the 'product' custom post type.
	 *
	 * @return  void
	 */
	public static function add() {
		// Add meta box into the edit screen of the 'product' custom post type.
		add_meta_box(
			'wr_share_for_discounts_meta_box',
			__( 'Share for Discounts', 'wr-share-for-discounts' ),
			array( __CLASS__, 'show' ),
			'product',
			'normal'
		);
	}

	/**
	 * Method to print HTML for the meta box in the edit screen of the 'product' custom post type.
	 *
	 * @param   object  $post  Post object being edited.
	 *
	 * @return  void
	 */
	public static function show( $post ) {
		// Get saved meta data.
		$meta_data = WR_Share_For_Discounts::get_meta_data();

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'wr_share_for_discounts_meta_box_data', 'wr_share_for_discounts_meta_box_nonce' );

		// Load template file.
		include_once WR_S4D_PATH . 'templates/meta-box.php';
	}

	/**
	 * Method to save meta box data.
	 *
	 * @param   int  $post_id  The ID of the post being saved.
	 *
	 * @return  void
	 */
	public static function save( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['wr_share_for_discounts_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wr_share_for_discounts_meta_box_nonce'], 'wr_share_for_discounts_meta_box_data' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! ( current_user_can( 'edit_post', $post_id ) || current_user_can( 'edit_posts' ) || current_user_can( 'manage_woocommerce' ) ) ) {
			return;
		}

		// Make sure that meta box has data.
		if ( ! isset( $_POST['wr_share_for_discounts'] ) ) {
			return;
		}

		// Sanitize user input.
		$meta_data = array();

		foreach ( $_POST['wr_share_for_discounts'] as $k => $v ) {
			$meta_data[ $k ] = sanitize_text_field( $v );
		}

		// Update the meta field in the database.
		update_post_meta( $post_id, 'wr_share_for_discounts', $meta_data );
	}

	/**
	 * Method to get Share For Discounts meta data for current product.
	 *
	 * @param   mixed  $product  The product to get meta data for.
	 *
	 * @return  array
	 */
	public static function get( $product = null ) {
		// Get product.
		if ( empty( $product ) ) {
			global $post;

			$product = wc_get_product( $post->ID );
		} else {
			$product = wc_get_product( $product );
		}

		// Get saved meta data.
		$meta_data = get_post_meta( $product->get_id(), 'wr_share_for_discounts', true );

		// Apply default values.
		$meta_data = wp_parse_args(
			$meta_data,
			array(
				'enable' => 0,
				'type'   => 'individual',
				'amount' => 0,
				'unit'   => 'percent',
			)
		);

		return $meta_data;
	}
}

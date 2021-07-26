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
class WR_Share_For_Discounts_Share {
	/**
	 * Method to do necessary initialization for Share for Discounts.
	 *
	 * @param   string  $type  Type of share for discounts.
	 *
	 * @return  void
	 */
	public static function init( $type = null ) {
		// Check if Share for Discounts is enabled?
		if ( empty( $type ) && ! ( $type = self::is_enabled() ) ) {
			return;
		}

		// Enqueue stylesheet for Share for Discounts.
		wp_enqueue_style( WR_S4D, WR_S4D_URL . 'assets/css/style.css' );

		// Enqueue script for Share for Discounts.
		wp_enqueue_script( WR_S4D, WR_S4D_URL . 'assets/js/script.js', array( 'jquery' ) );

		wp_localize_script(
			WR_S4D,
			'wr_share_for_discounts',
			array_merge(
				WR_Share_For_Discounts_Settings::get(),
				array(
					'url'     => admin_url( 'admin-ajax.php?action=' . WR_S4D ),
					'nonce'   => wp_create_nonce( WR_S4D ),
					'type'    => $type,
					'product' => is_product() ? $GLOBALS['post']->ID : 0,
				)
			)
		);
	}

	/**
	 * Method to show Share for Discounts.
	 *
	 * @param   string  $type  Type of share for discounts.
	 *
	 * @return  void
	 */
	public static function show( $type = null ) {
		// Check if Share for Discounts is enabled?
		if ( empty( $type ) && ! ( $type = self::is_enabled() ) ) {
			return;
		}

		// Get saved settings.
		$settings = WR_Share_For_Discounts_Settings::get();

		// Load template file.
		include_once WR_S4D_PATH . "templates/share/{$type}.php";
	}

	/**
	 * Method to set a cookie for storing discount.
	 *
	 * @return  void
	 */
	public static function discount() {
		// Verify request.
		$product = isset( $_REQUEST['product'] ) ? absint( $_REQUEST['product'] ) : null;
		$nonce   = isset( $_REQUEST['nonce'  ] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : null;
		$type    = isset( $_REQUEST['type'   ] ) ? sanitize_text_field( $_REQUEST['type' ] ) : null;

		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, WR_S4D ) ) {
			exit;
		}

		// Get saved settings.
		$settings = WR_Share_For_Discounts_Settings::get();

		// Get current discount data.
		$discount = isset( $_COOKIE[ WR_S4D ] ) ? maybe_unserialize( stripslashes( $_COOKIE[ WR_S4D ] ) ) : array();

		switch ( $type ) {
			case 'cart':
				if ( $settings['enable_cart_discount'] && $settings['cart_discount_amount'] ) {
					// Generate a coupon to apply.
					$coupon_code = strtoupper( 'cart_discount_' . time() );
					$coupon_id   = wp_insert_post(
						array(
							'post_type'   => 'shop_coupon',
							'post_status' => 'publish',
							'post_title'  => $coupon_code,
							'post_name'   => $coupon_code,
						)
					);

					if ( $coupon_id ) {
						// Set discount amount.
						update_post_meta( $coupon_id, 'coupon_amount', $settings['cart_discount_amount'] );
						update_post_meta( $coupon_id, 'discount_type', $settings['cart_discount_type'  ] );

						// Make sure coupon can be used one-time only.
						update_post_meta( $coupon_id, 'usage_limit'         , 1 );
						update_post_meta( $coupon_id, 'usage_limit_per_user', 1 );

						// Add coupon to cart.
						WC()->cart->add_discount( $coupon_code );

						// Update discount data.
						$discount['cart_discount'] = $coupon_code;

						// Print output to reload the cart page.
						$message = '<script>window.parent.location.reload();</script>';
					} else {
						// Print error message.
						$message = '<script>window.parent.alert("' . __( 'Failed to apply cart discount!', 'wr-share-for-discounts' ) . '");</script>';
					}
				} else {
					// Print error message.
					$message = '<script>window.parent.alert("' . __( 'Cart discount is not enabled!', 'wr-share-for-discounts' ) . '");</script>';
				}
			break;

			case 'coupon':
				if ( $settings['enable_discount_coupon'] && $settings['discount_coupon_amount'] ) {
					// Generate a coupon to apply.
					$coupon_code = strtoupper( 'discount_coupon_' . time() );
					$coupon_id   = wp_insert_post(
						array(
							'post_type'   => 'shop_coupon',
							'post_status' => 'publish',
							'post_title'  => $coupon_code,
							'post_name'   => $coupon_code,
						)
					);

					if ( $coupon_id ) {
						// Set discount amount.
						update_post_meta( $coupon_id, 'coupon_amount', $settings['discount_coupon_amount'] );
						update_post_meta( $coupon_id, 'discount_type', $settings['discount_coupon_type'  ] );

						// Make sure coupon can be used one-time only.
						update_post_meta( $coupon_id, 'usage_limit'         , 1 );
						update_post_meta( $coupon_id, 'usage_limit_per_user', 1 );

						// Update discount data.
						$discount['discount_coupon'] = $coupon_code;

						// Print output to reload the cart page.
						$message = '<script>window.parent.location.reload();</script>';
					} else {
						// Print error message.
						$message = '<script>window.parent.alert("' . __( 'Failed to create discount coupon!', 'wr-share-for-discounts' ) . '");</script>';
					}
				} else {
					// Print error message.
					$message = '<script>window.parent.alert("' . __( 'Discount coupon is not enabled!', 'wr-share-for-discounts' ) . '");</script>';
				}
			break;

			case 'product':
			default:
				// Get product.
				if ( $product = wc_get_product( $product ) ) {
					// Get product ID.
					$product_id = $product->get_id();

					if ( 'WC_Product_Variation' == get_class( $product ) ) {
						$product_id = $product->get_parent_id();
					}

					// Get saved meta data.
					$meta_data = WR_Share_For_Discounts::get_meta_data( $product_id );
					$enabled   = self::is_enabled( $product_id );

					if ( 'product' == $enabled ) {
						// Prepare product discount data.
						$type      = $meta_data['enable'] ? $meta_data['type'     ] : $settings['product_discount_type'  ];
						$min_price = $meta_data['enable'] ? $meta_data['min_price'] : $settings['mass_discount_min_price'];
						$amount    = $meta_data['enable'] ? $meta_data['amount'   ] : $settings['product_discount_amount'];
						$unit      = $meta_data['enable'] ? $meta_data['unit'     ] : $settings['product_discount_unit'  ];

						// Discount product price based on discount type.
						if ( 'individual' == $type ) {
							// Update discount data.
							$discount[ $product_id ] = true;

							// Update live cookie data.
							$_COOKIE[ WR_S4D ] = $discount;
						}

						elseif ( 'mass' == $type ) {
							if ( ! isset( $discount[ $product_id ] ) ) {
								// Prepare min price.
								$min_price = ( float ) $min_price;

								// Convert product object to array of product object to support variable product.
								$products = array( $product );

								if ( 'variable' == $product->get_type() ) {
									$products = array_map( 'wc_get_product', $product->get_children() );
								}

								// Loop thru array of product object to update discounted price.
								foreach ( $products as $product ) {
									// Get current product price.
									$price = $product->get_sale_price();

									if ( ! $price ) {
										$price = $product->get_regular_price();
									}

									// Verify product price.
									if ( $min_price == $price ) {
										continue;
									}

									// Update discounted price.
									if ( 'percent' == $unit ) {
										$price -= $price * ( ( float ) $amount / 100 );
									} else {
										$price -= ( float ) $amount;
									}

									// Make sure discounted price does not less then the minimum allowed.
									if ( $min_price > $price ) {
										$price = $min_price;
									}

									// Update product sale price.
									$pid = 'WC_Product_Variation' == get_class( $product ) ? $product->variation_id : $product->get_id();

									update_post_meta( $pid, '_price'     , $price );
									update_post_meta( $pid, '_sale_price', $price );
								}

								// Update discount data.
								$discount[ $product_id ] = true;
							}
						}

						// Print the discounted product price.
						if ( 'WC_Product_Variation' == get_class( $product ) ) {
							// Clear transient to refresh price tags.
							delete_transient( 'wc_var_prices_' . $product->get_parent_id() );

							$message = $product->get_price_html();
						} else {
							if ( 'WC_Product_Variable' == get_class( $product ) ) {
								// Clear transient to refresh price tags.
								delete_transient( 'wc_var_prices_' . $product->get_id() );
							}

							// Get display price for the product.
							$message = array(
								'price'      => $product->get_price_html(),
								'variations' => array(),
							);

							// Get display price for all variation also.
							foreach ( $product->get_children() as $variation ) {
								$variation = $product->get_child( $variation );

								$message[ 'variations' ][ $variation->variation_id ] = array(
									'price'      => $variation->get_price(),
									'price_html' => $variation->get_price_html(),
								);
							}
						}
					} else {
						// Print error message.
						$message = '<script>window.parent.alert("' . __( 'Product discount is not enabled!', 'wr-share-for-discounts' ) . '");</script>';
					}
				} else {
					// Print error message.
					$message = '<script>window.parent.alert("' . __( 'Product not found!', 'wr-share-for-discounts' ) . '");</script>';
				}
			break;
		}

		// Set cookie to store discount.
		setcookie( WR_S4D, maybe_serialize( $discount ), time() + YEAR_IN_SECONDS, '/', $_SERVER['HTTP_HOST'], is_ssl(), true );

		// Print message if has.
		if ( isset( $message ) ) {
			echo '' . ( is_string( $message )
				? $message
				: htmlentities( json_encode( $message ), ENT_QUOTES|ENT_SUBSTITUTE )
			);
		}

		// Exit immediately to prevent WordPress from processing further.
		exit;
	}

	/**
	 * Method to calculate discounted price.
	 *
	 * @param   float   $price     Current price.
	 * @param   object  $product   WooCommerce product object.
	 * @param   array   $discount  Discount data.
	 *
	 * @return  float
	 */
	public static function calculate( $price, $product, $discount = null ) {
		// Get product ID.
		$product_id = $product->get_id();

		if ( 'WC_Product_Variation' == get_class( $product ) ) {
			$product_id = $product->get_parent_id();

			// Clear transient to refresh price tags.
			delete_transient( 'wc_var_prices_' . $product_id );
		}

		// Get saved settings.
		$settings = WR_Share_For_Discounts_Settings::get();

		// Get saved meta data.
		$meta_data = WR_Share_For_Discounts::get_meta_data( $product_id );
		$enabled   = self::is_enabled( $product_id );

		// Prepare product discount data.
		$type      = $meta_data['enable'] ? $meta_data['type'     ] : $settings['product_discount_type'  ];
		$min_price = $meta_data['enable'] ? $meta_data['min_price'] : $settings['mass_discount_min_price'];
		$amount    = $meta_data['enable'] ? $meta_data['amount'   ] : $settings['product_discount_amount'];
		$unit      = $meta_data['enable'] ? $meta_data['unit'     ] : $settings['product_discount_unit'  ];

		if ( 'product' != $enabled || 'mass' == $type ) {
			return $price;
		}

		// Get current discount data.
		if ( empty( $discount ) ) {
			$discount = isset( $_COOKIE[ WR_S4D ] ) ? $_COOKIE[ WR_S4D ] : array();

			if ( ! is_array( $discount ) ) {
				$discount = maybe_unserialize( stripslashes( $_COOKIE[ WR_S4D ] ) );
			}
		}

		if ( isset( $discount[ $product_id ] ) ) {
			if ( empty( $price ) ) {
				$price = $product->get_regular_price();
			}

			if ( 'percent' == $unit ) {
				$price -= $price * ( ( float ) $amount / 100 );
			} else {
				$price -= ( float ) $amount;
			}
		}

		return $price;
	}

	/**
	 * Method to check if Share for Discounts is enabled.
	 *
	 * @param   mixed  $product  The current product.
	 *
	 * @return  mixed  Type of Share for Discounts enabled (product, cart, coupon) or boolean FALSE if none enabled.
	 */
	protected static function is_enabled( $product = null ) {
		// Get saved settings.
		$settings = WR_Share_For_Discounts_Settings::get();

		if ( ! ( $settings['enable_facebook_sharing'] || $settings['enable_twitter_sharing'] || $settings['enable_google_plus_sharing'] ) ) {
			return false;
		}

		// Check if Share for Discounts is enabled for current page.
		if ( $product || is_product() ) {
			if ( ! $settings['enable_product_discount'] ) {
				return false;
			}

			// Get saved meta data.
			$meta_data = WR_Share_For_Discounts::get_meta_data( $product );

			if ( ! $meta_data['enable'] || empty( $meta_data['amount'] ) ) {
				// Check global options.
				if ( empty( $settings['product_discount_amount'] ) || empty( $settings['discounted_products'] ) ) {
					return false;
				}

				// Check if product is discounted.
				if ( empty( $product ) ) {
					global $post;

					$product = wc_get_product( $post->ID );
				} else {
					$product = wc_get_product( $product );
				}

				if ( ! in_array( $product->get_id(), array_map( 'absint', explode( ',', $settings['discounted_products'] ) ) ) ) {
					return false;
				}
			}

			return 'product';
		}

		if ( is_cart() ) {
			if ( ! $settings['enable_cart_discount'] || empty( $settings['cart_discount_amount'] ) ) {
				return false;
			}

			return 'cart';
		}

		if ( ! $settings['enable_discount_coupon'] || empty( $settings['discount_coupon_amount'] ) ) {
			return false;
		}

		return 'coupon';
	}
}

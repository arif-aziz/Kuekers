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

/**
 * Define class to register necessary meta boxes.
 */
class WR_Custom_Attributes_Meta_Box {
	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function initialize() {
		add_action( 'add_meta_boxes', array( 'WR_Custom_Attributes_Meta_Box', 'add'  ) );
		add_action( 'save_post'     , array( 'WR_Custom_Attributes_Meta_Box', 'save' ) );
	}

	/**
	 * Register meta boxes for adding product variation gallery images.
	 *
	 * @return  void
	 */
	public static function add() {
		global $post;

		if ( 'product' == $post->post_type ) {
			// Get product details.
			$product = function_exists('wc_get_product') ? wc_get_product($post) : get_product($post);

			// If product is variable, check if it has any variation using Color Picker attribute.
			if ( $product->is_type( 'variable' ) ) {
				// Get all product variation attributes.
				$attributes = $product->get_variation_attributes();

				// Loop thru attributes to check if Color Picker is used.
				foreach ( $attributes as $attribute_name => $options ) {
					// Get custom attribute type.
					global $wpdb;

					$attr = current(
						$wpdb->get_results(
							"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
							"WHERE attribute_name = '" . substr( $attribute_name, 3 ) . "' LIMIT 0, 1;"
						)
					);

					if ( $attr && ( 'color picker' == $attr->attribute_type || 'image select' == $attr->attribute_type ) ) {

						if( 'color picker' == $attr->attribute_type ) {
							// Color Picker attribute is used in product variation, add meta boxes for selecting variation images.
							foreach ( $options as $option ) {
								add_meta_box(
									sprintf( "product-gallery-color-%s", $option ),
									ucwords( sprintf( __( 'Product Gallery %s Color', 'wr-custom-attributes' ), $option ) ),
									array( __CLASS__, 'display' ),
									'product',
									'side',
									'low',
									array( 'name' => $attribute_name, 'value' => $option )
								);
							}
						} elseif( 'image select' == $attr->attribute_type ) {
							// Color Picker attribute is used in product variation, add meta boxes for selecting variation images.
							foreach ( $options as $option ) {
								add_meta_box(
									sprintf( "product-gallery-images-%s", $option ),
									ucwords( sprintf( __( 'Product Gallery %s Images', 'wr-custom-attributes' ), $option ) ),
									array( __CLASS__, 'display' ),
									'product',
									'side',
									'low',
									array( 'name' => $attribute_name, 'value' => $option )
								);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Display meta box to select gallery images for product variation.
	 *
	 * @param   WP_Post  $post    WordPress's post object.
	 * @param   object   $params  Meta box parameters.
	 *
	 * @return  void
	 */
	public static function display( $post, $params ) {
		// Generate meta key to get product variation gallery.
		$meta_key = "_product_image_gallery_{$params['args']['name']}-{$params['args']['value']}";

		// Print nonce field once.
		static $nonce_field_printed;

		if ( ! isset( $nonce_field_printed ) ) {
			wp_nonce_field( 'wr_save_product_variation_gallery_images', 'wr_save_product_variation_gallery_images' );

			$nonce_field_printed = true;
		}
		?>
		<div class="product_variation_images_container">
			<ul class="product_images">
				<?php
				// Get product variation gallery.
				$product_image_gallery = get_post_meta( $post->ID, $meta_key, true );
				$attachments           = array_filter( explode( ',', $product_image_gallery ) );
				$update_meta           = false;
				$updated_gallery_ids   = array();

				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

						if ( empty( $attachment ) ) {
							$update_meta = true;
							continue;
						}
				?>
				<li class="image" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
					<?php echo '' . $attachment; ?>
					<ul class="actions">
						<li>
							<a href="#" class="delete tips" data-tip="<?php
								esc_attr_e( 'Delete image', 'wr-custom-attributes' );
							?>">
								<?php esc_html_e( 'Delete', 'wr-custom-attributes' ); ?>
							</a>
						</li>
					</ul>
				</li>
				<?php
						// Rebuild ids to be saved.
						$updated_gallery_ids[] = $attachment_id;
					}

					// Need to update product meta to set new gallery ids.
					if ( $update_meta ) {
						update_post_meta( $post->ID, $meta_key, implode( ',', $updated_gallery_ids ) );
					}
				}
				?>
			</ul>

			<input type="hidden" class="product_variation_image_gallery" name="<?php echo esc_attr( $meta_key ); ?>" value="<?php
				echo esc_attr( $product_image_gallery );
			?>" />
		</div>
		<p class="add_product_variation_images hide-if-no-js">
			<a href="#" data-choose="<?php
				echo esc_attr( sprintf(
					__( 'Add Images to Product Gallery %s', 'wr-custom-attributes' ),
					ucfirst( $params['args']['value'] )
				) );
			?>" data-update="<?php
				esc_attr_e( 'Add to gallery', 'wr-custom-attributes' );
			?>" data-delete="<?php
				esc_attr_e( 'Delete image', 'wr-custom-attributes' );
			?>" data-text="<?php
				esc_attr_e( 'Delete', 'wr-custom-attributes' );
			?>">
				<?php _e( 'Add product gallery images', 'wr-custom-attributes' ); ?>
			</a>
		</p>
		<?php
	}

	/**
	 * Save meta boxes.
	 *
	 * @param   int  $post_id  The ID of the post being saved.
	 *
	 * @return  void
	 */
	public static function save( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['wr_save_product_variation_gallery_images'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wr_save_product_variation_gallery_images'], 'wr_save_product_variation_gallery_images' ) ) {
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

		// Sanitize and save meta boxes.
		foreach ( $_POST as $k => $v ) {
			if ( 0 === strpos( $k, '_product_image_gallery_' ) ) {
				// Sanitize user input.
				$v = implode( ',', array_map( 'absint', array_map( 'trim', explode( ',', $v ) ) ) );

				// Update the meta field in the database.
				update_post_meta( $post_id, $k, $v );
			}
		}
	}
}

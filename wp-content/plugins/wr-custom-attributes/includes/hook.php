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
 * Define class to hook into WordPress and WooCommerce.
 */
class WR_Custom_Attributes_Hook {
	/**
	 * Variable to hold supported custom attribute types.
	 *
	 * @var  array
	 */
	protected static $types;

	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function initialize() {
		// Define supported custom attribute types.
		self::$types = array(
			'color picker' => __( 'Color Picker', 'wr-custom-attributes' ),
			'image select' => __( 'Image Select', 'wr-custom-attributes' ),
			'text label'   => __( 'Text Label'  , 'wr-custom-attributes' ),
		);

		// Register filter to add custom types for WooCommerce product attributes.
		add_filter( 'product_attributes_type_selector', array( __CLASS__, 'register' ) );

		// Register action to print values for custom attribute types in add/edit product screen.
		add_action( 'woocommerce_product_option_terms', array( __CLASS__, 'print_values' ), 10, 2 );

		// Register Ajax action to detect if Color Picker attribute is used for product variations.
		add_action( 'wp_ajax_wr-detect-color-picker-attribute', array( __CLASS__, 'detect_color_picker_attribute' ) );

		// Register filter to get product image gallery (attachment IDs) for the selected color.
		add_filter( 'woocommerce_product_get_gallery_image_ids', array( __CLASS__, 'get_product_image_gallery' ), 10, 2 );

		// Register Ajax action to get product image gallery (HTML) for the selected color.
		add_action( 'wp_ajax_wr-get-product-image-gallery', array( __CLASS__, 'print_product_image_gallery' ) );
		add_action( 'wp_ajax_nopriv_wr-get-product-image-gallery', array( __CLASS__, 'print_product_image_gallery' ) );

		// Register filter to generate HTML code for product options of custom types in product details page.
		add_filter( 'woocommerce_attribute', array( __CLASS__, 'generate_options' ), 10, 3 );

		// Register actions to handle variation form.
		add_action( 'woocommerce_before_variations_form',    array( __CLASS__, 'start_capture' ) );
		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'stop_capture'  ) );

		// Check if screen to add/edit attribute values is requested.
		global $pagenow;

		if (
			in_array( $pagenow, array( 'edit-tags.php', 'term.php' ) )
			||
			( 'admin-ajax.php' == $pagenow && 'add-tag' == $_REQUEST['action'] )
		) {
			$taxonomy = isset( $_REQUEST['taxonomy' ] ) ? sanitize_text_field( $_REQUEST['taxonomy' ] ) : null;

			if ( $taxonomy && 'pa_' == substr( $taxonomy, 0, 3 ) ) {
				// Get custom attribute type.
				global $wpdb;

				$attribute = current(
					$wpdb->get_results(
						"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
						"WHERE attribute_name = '" . esc_sql( substr( $taxonomy, 3 ) ) . "' LIMIT 0, 1;"
					)
				);

				if ( array_key_exists( $attribute->attribute_type, self::$types ) ) {
					// Add actions to print custom fields for add/edit attribute value form.
					add_action( "{$taxonomy}_add_form_fields" , array( __CLASS__, 'add'  ), 10    );
					add_action( "{$taxonomy}_edit_form_fields", array( __CLASS__, 'edit' ), 10, 2 );

					// Add action to save custom data for attribute value of custom types.
					add_action( "created_{$taxonomy}", array( __CLASS__, 'save' ), 10, 2 );
					add_action( "edited_{$taxonomy}" , array( __CLASS__, 'save' ), 10, 2 );
				}
			}
		}
	}

	/**
	 * Method to add custom types for WooCommerce product attributes.
	 *
	 * @param   array  $types  Current attribute types.
	 *
	 * @return  array
	 */
	public static function register( $types ) {
		return array_merge( $types, self::$types );
	}

	/**
	 * Method to print form fields for adding attribute value for custom attribute types.
	 *
	 * @param   string  $taxonomy  Current taxonomy slug.
	 *
	 * @return  void
	 */
	public static function add( $taxonomy ) {
		// Get custom attribute type.
		global $wpdb;

		$attribute = current(
			$wpdb->get_results(
				"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
				"WHERE attribute_name = '" . substr( $taxonomy, 3 ) . "' LIMIT 0, 1;"
			)
		);

		if ( $attribute && array_key_exists( $attribute->attribute_type, self::$types ) ) {
			// Load template to print form fields for the custom attribute type.
			include_once WR_CA_PATH . 'templates/admin/new/' . str_replace( ' ', '-', $attribute->attribute_type ) . '.php';
		}
	}

	/**
	 * Method to print form fields for editing attribute value for custom attribute types.
	 *
	 * @param object $tag      Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return  void
	 */
	public static function edit( $tag, $taxonomy ) {
		// Get custom attribute type.
		global $wpdb;

		$attribute = current(
			$wpdb->get_results(
				"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
				"WHERE attribute_name = '" . substr( $taxonomy, 3 ) . "' LIMIT 0, 1;"
			)
		);

		if ( $attribute && array_key_exists( $attribute->attribute_type, self::$types ) ) {
			// Load template to print form fields for the custom attribute type.
			include_once WR_CA_PATH . 'templates/admin/edit/' . str_replace( ' ', '-', $attribute->attribute_type ) . '.php';
		}
	}

	/**
	 * Method to save custom data for attribute value of custom types.
	 *
	 * @param   int  $term_id  Term ID.
	 * @param   int  $tt_id    Term taxonomy ID.
	 *
	 * @return  void
	 */
	public static function save( $term_id, $tt_id ) {
		// Save custom data.
		foreach ( $_POST as $key => $value ) {
			if ( '' != $value && 'wr_' == substr( $key, 0, 3 ) ) {
				update_woocommerce_term_meta( $term_id, sanitize_key( $key ), sanitize_text_field( $value ) );
			}
		}
	}

	/**
	 * Method to print values for custom attribute types in add/edit product screen.
	 *
	 * @param   object  $attribute  Attribute data.
	 * @param   int     $i          Current attribute index.
	 *
	 * @return  void
	 */
	public static function print_values( $attribute, $i ) {
		// Verify attribute type.
		if ( array_key_exists( $attribute->attribute_type, self::$types ) ) {
			if ( isset( $_POST['taxonomy'] ) ) {
				$taxonomy = sanitize_text_field( $_POST['taxonomy'] );
			} else {
				$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );
			}
			?>
			<select name="attribute_values[<?php echo esc_attr( $i ); ?>][]" multiple="multiple" data-placeholder="<?php
				esc_attr_e( 'Select terms', 'woocommerce' );
			?>" class="multiselect attribute_values wc-enhanced-select">
				<?php
				$all_terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );

				if ( $all_terms ) :

				foreach ( $all_terms as $term ) :
				?>
				<option value="<?php
					echo esc_attr( version_compare(WC_VERSION, '3.0.0', 'lt') ? $term->slug : $term->term_id );
				?>" <?php
					selected( has_term( absint( $term->term_id ), $taxonomy, 0 ), true );
				?>>
					<?php echo esc_html( $term->name ); ?>
				</option>
				<?php
				endforeach;

				endif;
				?>
			</select>
			<button class="button plus select_all_attributes"><?php _e( 'Select all', 'woocommerce' ); ?></button>
			<button class="button minus select_no_attributes"><?php _e( 'Select none', 'woocommerce' ); ?></button>
			<button class="button fr plus add_new_attribute"><?php _e( 'Add new', 'woocommerce' ); ?></button>
			<?php
		}
	}

	/**
	 * Detect if Color Picker attribute is used for product variations.
	 *
	 * @return  void
	 */
	public static function detect_color_picker_attribute() {
		if ( isset( $_REQUEST['attributes'] ) ) {
			global $wpdb;

			foreach ( array_keys( $_REQUEST['attributes'] ) as $attribute_name ) {
				// Get custom attribute type.
				$attr = current(
					$wpdb->get_results(
						"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
						"WHERE attribute_name = '" . substr( sanitize_key( $attribute_name ), 3 ) . "' LIMIT 0, 1;"
					)
				);

				if ( $attr && $attr->attribute_type == 'color picker' ) {
					wp_send_json_success( $attribute_name );
				}
			}
		}

		wp_send_json_error();
	}

	/**
	 * Method to get product image gallery for the selected color.
	 *
	 * @param   array       $attachment_ids  Current product gallery attachment IDs.
	 * @param   WC_Product  $product         Current product object.
	 *
	 * @return  array
	 */
	public static function get_product_image_gallery( $attachment_ids, $product ) {
		if ( $product->is_type( 'variable' ) ) {
			global $wpdb;

			// Prepare variation attributes.
			$attributes = $product->get_variation_attributes();

			// Alter variations form to support custom attribute types.
			foreach ( $attributes as $attribute_name => $options ) {
				// Get custom attribute type.
				$attr = current(
					$wpdb->get_results(
						"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
						"WHERE attribute_name = '" . substr( $attribute_name, 3 ) . "' LIMIT 0, 1;"
					)
				);

				if ( $attr && ( $attr->attribute_type == 'color picker' || $attr->attribute_type == 'image select' ) ) {
					// Check if certain attribute value is requested.
					$key = 'attribute_' . sanitize_title( $attribute_name );

					if ( isset( $_REQUEST[ $key ] ) && in_array( $_REQUEST[ $key ], $options ) ) {
						// Get term data.
						$term = get_term_by( 'slug', wc_clean( $_REQUEST[ $key ] ), $attribute_name );

						if ( $term ) {
							// Get image gallery for the selected color.
							$meta_key      = "_product_image_gallery_{$term->taxonomy}-{$term->slug}";
							$image_gallery = get_post_meta( $product->get_id(), $meta_key, true );

							if ( $image_gallery ) {
								$attachment_ids = array_filter(
									array_filter( ( array ) explode( ',', $image_gallery ) ),
									'wp_attachment_is_image'
								);
							}
						}
					}
				}
			}
		}

		return $attachment_ids;
	}

	/**
	 * Print product image gallery for the selected color.
	 *
	 * @return  void
	 */
	public static function print_product_image_gallery() {
		// Initialize necessary global variables..
		$GLOBALS['post'] = get_post( $_REQUEST['product'] );
		$GLOBALS['product'] = function_exists('wc_get_product') ? wc_get_product($_REQUEST['product']) : get_product($_REQUEST['product']);

		// Print HTML for product image gallery.
		woocommerce_show_product_images();

		exit;
	}

	/**
	 * Method to generate HTML code for product options of custom types in product details page.
	 *
	 * @param   string  $html       Current HTML.
	 * @param   array   $attribute  Attribute data.
	 * @param   array   $values     Attribute values.
	 *
	 * @return  void
	 */
	public static function generate_options( $html, $attribute, $values ) {
		global $wpdb, $product;

		// Get custom attribute type.
		$attr = current(
			$wpdb->get_results(
				"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
				"WHERE attribute_name = '" . substr( $attribute['name'], 3 ) . "' LIMIT 0, 1;"
			)
		);

		if ( $attr && array_key_exists( $attr->attribute_type, self::$types ) ) {
			// Get terms if this is a taxonomy - ordered. We need the names too.
			$options = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'all' ) );

			// Load template to print product options for custom attribute types.
			include WR_CA_PATH . 'templates/shop/simple/' . str_replace( ' ', '-', $attr->attribute_type ) . '.php';
		} else {
			return $html;
		}
	}

	/**
	 * Method to start capture variation form output.
	 *
	 * @return  void
	 */
	public static function start_capture() {
		// Hide default image gallery and attribute select box.
		echo '
			<style type="text/css">
				.woocommerce-product-gallery,
				select[data-attribute_name] {
					display: none !important;
				}
				.woocommerce-product-gallery.active-product-gallery,
				select[data-attribute_name].active-attribute-selector {
					display: block !important;
				}
			</style>
		';
	}

	/**
	 * Method to stop capture variation form output.
	 *
	 * @return  void
	 */
	public static function stop_capture() {
		global $wpdb, $product;

		// Get available variations.
		if ( $product->is_type( 'variable' ) ) {
			// Prepare variation attributes.
			$attributes = $product->get_variation_attributes();

			// Alter variations form to support custom attribute types.
			foreach ( array_keys( $attributes ) as $attribute_name ) {
				// Get custom attribute type.
				$attr = current(
					$wpdb->get_results(
						"SELECT attribute_type FROM {$wpdb->prefix}woocommerce_attribute_taxonomies " .
						"WHERE attribute_name = '" . substr( $attribute_name, 3 ) . "' LIMIT 0, 1;"
					)
				);

				if ( $attr && array_key_exists( $attr->attribute_type, self::$types ) ) {
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$options = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );

					// Generate request variable name.
					$key = 'attribute_' . sanitize_title( $attribute_name );

					// Get selected attribute value.
					$selected = isset( $_REQUEST[ $key ] )
						? wc_clean( $_REQUEST[ $key ] )
						: $product->get_variation_default_attribute( $attribute_name );

					echo '<script id="' . $attribute_name . '_custom_attribute_selector" type="text/html">';

					include WR_CA_PATH . 'templates/shop/variable/' . str_replace( ' ', '-', $attr->attribute_type ) . '.php';

					echo '</script>';

					// If HTML is loaded via Ajax, include code to manually init custom attribute types.
					if ( defined( 'DOING_AJAX' ) ) {
						echo '<script type="text/javascript">(function($){$.init_wr_custom_attributes()})(jQuery);</script>';
					}
				}
			}
		}
	}
}

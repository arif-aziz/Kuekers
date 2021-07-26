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
 * Define core class.
 */
class WR_Share_For_Discounts {
	/**
	 * Variable to hold class prefix supported for autoloading.
	 *
	 * @var  string
	 */
	protected static $prefix = 'WR_Share_For_Discounts_';

	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function initialize() {
		// Register class autoloader.
		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		// Register admin initialization for Share for Discounts.
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		// Add Share for Discounts into product details screen.
		add_action( 'woocommerce_before_single_product', array( 'WR_Share_For_Discounts_Share', 'init' ) );
		add_action( 'woocommerce_share'                , array( 'WR_Share_For_Discounts_Share', 'show' ) );

		// Add Share for Discounts into cart details screen.
		add_action( 'woocommerce_before_cart'  , array( 'WR_Share_For_Discounts_Share', 'init' ) );
		add_action( 'woocommerce_cart_contents', array( 'WR_Share_For_Discounts_Share', 'show' ) );

		// Register Ajax action to apply discounts.
		add_action( 'wp_ajax_' . WR_S4D       , array( 'WR_Share_For_Discounts_Share', 'discount' ) );
		add_action( 'wp_ajax_nopriv_' . WR_S4D, array( 'WR_Share_For_Discounts_Share', 'discount' ) );

		// Register filter to apply discounts.
		if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			add_filter( 'woocommerce_get_price', array( 'WR_Share_For_Discounts_Share', 'calculate' ), 90, 2 );
		} else {
			add_filter( 'woocommerce_product_get_price', array( 'WR_Share_For_Discounts_Share', 'calculate' ), 90, 2 );
		}
		add_filter( 'woocommerce_get_sale_price', array( 'WR_Share_For_Discounts_Share', 'calculate' ), 90, 2 );

		add_filter( 'woocommerce_variation_prices_price'     , array( 'WR_Share_For_Discounts_Share', 'calculate' ), 90, 2 );
		add_filter( 'woocommerce_variation_prices_sale_price', array( 'WR_Share_For_Discounts_Share', 'calculate' ), 90, 2 );

		// Add shortcode to embed HTML for Share for Discounts.
		add_shortcode( 'share_for_discounts', array( 'WR_Share_For_Discounts_Shortcode', 'generate' ) );
	}

	/**
	 * Add settings for Share for Discounts.
	 *
	 * @return  void
	 */
	public static function admin_init() {
		// Register Share for Discounts tab with WooCommerce settings screen.
		add_filter( 'woocommerce_settings_tabs_array'    , array( 'WR_Share_For_Discounts_Settings', 'register' ), 30 );
		add_action( 'woocommerce_settings_' . WR_S4D     , array( 'WR_Share_For_Discounts_Settings', 'show'     )     );
		add_action( 'woocommerce_settings_save_' . WR_S4D, array( 'WR_Share_For_Discounts_Settings', 'save'     )     );

		// Register meta box with the 'product' custom post type of WooCommerce.
		add_action( 'add_meta_boxes', array( 'WR_Share_For_Discounts_Meta_Box', 'add'  ) );
		add_action( 'save_post'     , array( 'WR_Share_For_Discounts_Meta_Box', 'save' ) );

		// Add settings link into plugins list.
		add_filter( 'plugin_action_links_' . WR_S4D_BASENAME, array( 'WR_Share_For_Discounts_Settings', 'add_action_links' ) );
	}

	/**
	 * Method to get settings for Share for Discounts.
	 *
	 * @return  array
	 */
	public static function get_settings() {
		// Get saved settings.
		return WR_Share_For_Discounts_Settings::get();
	}

	/**
	 * Method to get meta data for Share for Discounts.
	 *
	 * @param   mixed  $product  The product to get meta data for.
	 *
	 * @return  array
	 */
	public static function get_meta_data( $product = null ) {
		// Get saved meta data.
		return WR_Share_For_Discounts_Meta_Box::get( $product );
	}

	/**
	 * Method to autoload class declaration file.
	 *
	 * @param   string  $class_name  Name of class to load declaration file for.
	 *
	 * @return  mixed
	 */
	public static function autoload( $class_name ) {
		// Verify class prefix.
		if ( 0 !== strpos( $class_name, self::$prefix ) ) {
			return false;
		}

		// Generate file path from class name.
		$base = WR_S4D_PATH . 'includes/';
		$path = strtolower( str_replace( '_', '/', substr( $class_name, strlen( self::$prefix ) ) ) );

		// Check if class file exists.
		$standard    = $path . '.php';
		$alternative = $path . '/' . basename( $path ) . '.php';

		while ( true ) {
			// Check if file exists in standard path.
			if ( @is_file( $base . $standard ) ) {
				$exists = $standard;

				break;
			}

			// Check if file exists in alternative path.
			if ( @is_file( $base . $alternative ) ) {
				$exists = $alternative;

				break;
			}

			// If there is no more alternative file, quit the loop.
			if ( false === strrpos( $standard, '/' ) || 0 === strrpos( $standard, '/' ) ) {
				break;
			}

			// Generate more alternative files.
			$standard    = preg_replace( '#/([^/]+)$#', '-\\1', $standard );
			$alternative = dirname( $standard ) . '/' . substr( basename( $standard ), 0, -4 ) . '/' . basename( $standard );
		}

		// Include class declaration file if exists.
		if ( isset( $exists ) ) {
			return include_once $base . $exists;
		}

		return false;
	}
}

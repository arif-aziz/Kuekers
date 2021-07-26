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
 * Define core class.
 */
class WR_Custom_Attributes {
	/**
	 * Variable to hold class prefix supported for autoloading.
	 *
	 * @var  string
	 */
	protected static $prefix = 'WR_Custom_Attributes_';

	/**
	 * Initialize.
	 *
	 * @return  void
	 */
	public static function initialize() {
		// Register class autoloader.
		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		// Load plugin textdomain.
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );

		// Register action to enqueue necessary assets.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_backend_assets'  ) );
		add_action( 'wp_enqueue_scripts'   , array( __CLASS__, 'enqueue_frontend_assets' ) );

		// Initialize custom attribute types for WooCommerce.
		WR_Custom_Attributes_Hook::initialize();

		// Initialize meta boxes for adding product variation gallery images.
		WR_Custom_Attributes_Meta_Box::initialize();
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.1
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'wr-custom-attributes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Method to enqueue necessary assets for the screen to add/edit product.
	 *
	 * @return  void
	 */
	public static function enqueue_backend_assets() {
		global $pagenow, $post_type;

		if ( 'post.php' == $pagenow && 'product' == $post_type ) {
			// Enqueue stylesheet.
			wp_enqueue_style( WR_CA, WR_CA_URL . 'assets/css/back-end.css' );

			// Enqueue script.
			wp_enqueue_script( WR_CA, WR_CA_URL . 'assets/js/back-end.js', array( 'jquery' ) );

			wp_localize_script( WR_CA, 'wr_custom_attribute', array(
				'refresh_tip' => __(
					"If you don't see meta boxes for selecting product variation images at the right side, click to refresh the page.",
					'wr-custom-attributes'
				)
			) );
		}
	}

	/**
	 * Method to enqueue necessary assets for rendering custom attribute types in front-end.
	 *
	 * @return  void
	 */
	public static function enqueue_frontend_assets() {
		global $post;

		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() || ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'nitro_product' ) || has_shortcode( $post->post_content, 'nitro_products' ) ) ) ) {
			// Enqueue stylesheet.
			wp_enqueue_style( WR_CA, WR_CA_URL . 'assets/css/front-end.css' );

			// Enqueue script.
			wp_enqueue_script( WR_CA, WR_CA_URL . 'assets/js/front-end.js', array( 'jquery' ) );
		}
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
		$base = WR_CA_PATH . 'includes/';
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

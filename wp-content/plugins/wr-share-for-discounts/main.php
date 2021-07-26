<?php
/**
 * Plugin Name: WR Share for Discounts
 * Plugin URI:  http://www.woorockets.com/
 * Description: An add-on for WooCommerce that allows customer share a product to get discount.
 * Version:     1.0.6
 * Author:      WooRockets <admin@woorockets.com>
 * Author URI:  http://woorockets.com/
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wr-share-for-discounts
 */

// Define plugin textdomain.
define( 'WR_S4D', 'wr-share-for-discounts' );

// Define path to plugin directory.
define( 'WR_S4D_PATH', plugin_dir_path( __FILE__ ) );

// Define URL to plugin directory.
define( 'WR_S4D_URL', plugin_dir_url( __FILE__ ) );

// Define plugin base file.
define( 'WR_S4D_BASENAME', plugin_basename( __FILE__ ) );

// Load the core class.
require_once WR_S4D_PATH . 'includes/core.php';

// Instantiate an object of the core class.
WR_Share_For_Discounts::initialize();

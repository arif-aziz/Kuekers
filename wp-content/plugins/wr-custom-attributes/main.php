<?php
/**
 * Plugin Name: WR Custom Attributes
 * Plugin URI:  http://www.woorockets.com
 * Description: An add-on for WooCommerce that provides some custom product attribute types.
 * Version:     1.1.0
 * Author:      WooRockets <admin@woorockets.com>
 * Author URI:  http://www.woorockets.com
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wr-custom-attributes
 */

// Define plugin textdomain.
define( 'WR_CA', 'wr-custom-attributes' );

// Define path to plugin directory.
define( 'WR_CA_PATH', plugin_dir_path( __FILE__ ) );

// Define URL to plugin directory.
define( 'WR_CA_URL', plugin_dir_url( __FILE__ ) );

// Define plugin base file.
define( 'WR_CA_BASENAME', plugin_basename( __FILE__ ) );

// Load the core class.
require_once WR_CA_PATH . 'includes/core.php';

// Instantiate an object of the core class.
WR_Custom_Attributes::initialize();

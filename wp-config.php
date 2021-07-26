<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'kuekers_pradesgaDB');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '/v{]aC50I:,Mf?y?XEX6~nqhb!4k^zNsx`<*91Oms(8v{BfjIW)5E^8kLVb}MME=');
define('SECURE_AUTH_KEY',  'n!I~C?fq<.&6M:c0QfZ89+27`dRI.dS`?&,M8|UU*$:k*Nu&^CR.cU(XLZfKjU3c');
define('LOGGED_IN_KEY',    '|]UT]$mD9<h4gk~@=X1r&uE>xj]w,Y h|+}6q9b=uKf<w>a @6y2:R^xI><hP>w<');
define('NONCE_KEY',        '^VU8[g@Z(Y-1!e*TxR1x&}lupaxzZTP;8OidW*W Q3^lGT~]<X*/|LE#=rX.B_o/');
define('AUTH_SALT',        'w!9Ll=X#37l#d%`9{LF_7{V#:q8ck:Ml@CrM+1QzWN8eBHtg)X8+h}nlw?Al`zS~');
define('SECURE_AUTH_SALT', '&+l@eFWGs>l<[Vf/ 9~/hb[MAG@f5:pV-R t$Xm>lTaD%V(-H`,{5S.^g[]riS`/');
define('LOGGED_IN_SALT',   'P/1kZ^DswqnlgqtZ?lLF,.E hip5jl5WQ|;)f%Z#&=~kPWq/+tMyY6^Rj,G6uL=,');
define('NONCE_SALT',       '@D:niWx37l]a[Kn|>ma?o<3]si}m[A9/KO,4srJ F(p2ZF +@CLW6j.Ifc(6|Prr');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define( 'WP_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

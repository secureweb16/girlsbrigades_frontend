<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'girlsbrigade' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ga,~#m3*0B;c2*9-bZdg7m0#]j#rz)-8Sr2K(}AefB2c1DzHz-`,lE&}WJ(rz1$_' );
define( 'SECURE_AUTH_KEY',  'cDFI;x|=s@)@$1U@CghSmBSwT[Gf`c M2wq/d@e~_)Rb(WBJi%Lwp!k|3<PiyoWM' );
define( 'LOGGED_IN_KEY',    ')3)jBl6iJ.8O@a63)y9$P[zkb)(1(JV *DnFwo]GaD5@pl#[}G0nS9uLc~-%#uo7' );
define( 'NONCE_KEY',        'c8P]$CNhvLj`p0#%y$;xSqhe7GhxQC1 yP*jm6yI8roQyYmi#Rcv8&21<1#8YqER' );
define( 'AUTH_SALT',        'Te4+6+X~0Odv)JFGhBNPXjX*rnU=)w.PrXf ssXE[Aa>%s2{3nj@JO)RlJm01?I;' );
define( 'SECURE_AUTH_SALT', 'qB)Q128)/}e^D!gT^o{d0x4K;anOIt+sE ~-i:>g0XN.cOWs4(x0nYwh-`/xtZ:-' );
define( 'LOGGED_IN_SALT',   '1Ttv_@j%TCa5E$N40/xdn=;?8CZMiJGw.$g+IQ`g~o`,Kssp-2c}~8BP?k*J$Wx,' );
define( 'NONCE_SALT',       '1KzVEFUUm0:pIP6$ZVpwws4$69<b]YsA.`$lmI()J#xJoXJZ.a~?+O[hJyDGL]wC' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

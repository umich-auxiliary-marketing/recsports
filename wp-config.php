<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 define( 'WP_DEBUG', true );
 define('DISABLE_WP_CRON', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress_recsports');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'jvm93(#JVM!');

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
define('AUTH_KEY',         '?:_T2yU/PiqujFi)osYVKU&m5/r)bXH9qo9IC=<H5LaQ}7=LR=Duhch+iMwf7AP ');
define('SECURE_AUTH_KEY',  '#ZAgiDvR76]m9bz(qEuoLH#G?w5y_Do4q/#LFF@e;/WK3dG|FO=6[Basm7-Ok@An');
define('LOGGED_IN_KEY',    'v4*^,+B(MWx`mV:hY-2A@ULacl3i}I(FjxCW(myWIUL8,[T v^&V^]Gnltxqv@UA');
define('NONCE_KEY',        '6v9H({=Go%.%ACb=a&)L.4miB$Ud^Rute#FqJkwlA9^&0HYA<6dnm}>&{)>H<SND');
define('AUTH_SALT',        '(1]RF7FcbTTjeHnsb+.vPzAo[^RAf@dE2UV7tC9nHO=PTngY}5I%W$/ZzG4SB{pG');
define('SECURE_AUTH_SALT', '/HdZzb9[>t+j-WZnp?;L+g%2H qyhif-`4W=LjtlNKO^A|1LyTP3}X?2,0k2j8T/');
define('LOGGED_IN_SALT',   ']:=./6Ux&JN6R;xqduE)-09-YY9edqdoC$K$faC p;&4~wg Ekr}9Oct*q5|{r&Y');
define('NONCE_SALT',       'l#E+%.%pF>c1}sOGvyA^,D!h:(WiaB?k8Yw54r{IQL2D*tPiK)NCom2YCS2k{+hW');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

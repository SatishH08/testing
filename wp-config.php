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
//define('DB_NAME', 'tgt_dam_opentext');
define('DB_NAME', 'tgt_dam_opentext');
/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
//define('DB_PASSWORD', 'Target@1234');
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
define('AUTH_KEY',         'OstNk_efo,-r6Env;0hLfOwT8KqiulbBmM5w~JUhT=VV[0a+KpP6Qs6Lt;I<Mi%J');
define('SECURE_AUTH_KEY',  '_9]f2k@Q+&Vxnix?G.{WamgqmyD.0:;I Te7MQ9cg? PWdeF}mvcr@mZ6 l*M46i');
define('LOGGED_IN_KEY',    '@vQ_8V-07A_U37?FrAw*nW_!Hr.}s80UU@7kEQ-NM[-=QZ.D-kq9Dx?}1;O%ic[)');
define('NONCE_KEY',        'tg%T!fS/um2$>MdzbJO=Dh23)7wnmdM?Ke2V*}k?#N1gaGY*_+qIr2rP<jVWS$NZ');
define('AUTH_SALT',        '>Q8QrE`Lwjq;d@:(c;(FOyoYb5BUP/}C2QSQ9+tK~s0i(Irp?gOo4ex1<kcs):?=');
define('SECURE_AUTH_SALT', 'Q#UDIMTrTHsH|Y[nKJ*cxr3dTR}Re}8W7VH)uM)1ei]pB>}Ly$N8_9o>^$M3cXD5');
define('LOGGED_IN_SALT',   'Cx:]{@4S$}qq]2ETU-_Hcch2TskLvy_h#|X^VtVldq&A((kGnwF@w`f_)+9g/[9@');
define('NONCE_SALT',       'y7&BprQFS:cVgj4bFZT093FP5/5P-.b4<B_KYPw5<?I}#zQ|Rd)LBah3<zA:Stxd');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tg_';

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

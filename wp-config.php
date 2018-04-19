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
define('DB_NAME', 'db_moocsnews');

/** MySQL database username */
define('DB_USER', 'moocsnews');

/** MySQL database password */
define('DB_PASSWORD', 'moocsnews@123.');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         '!p|!&7Yyp0!~w9y6OQ=I]dDZe:0T?qf52m[6g^P#,=z4gYVOIPQ5z&P#YU0/RLsm');
define('SECURE_AUTH_KEY',  ']z12M;Vfox?Xwghw(wYwu.-}8GBiZ5y=zdMloF8sG4i2zdaaIrTdJabO4jcie[0M');
define('LOGGED_IN_KEY',    'm)!r^<3F9KL2}#a*N/q)t^T4YdTj PVNJuo-Oju iC.kICG0j1)_FrB$CU 4qiA6');
define('NONCE_KEY',        '2`DaI$kpXL4iIe:qec8rJCF77XGuJOO,Pjtae`_`md9_}43A[gNero+.lqL$#J!{');
define('AUTH_SALT',        '+EHf?319CF3GwG3&Tm`SW!zTkOowsg$tg]x,+Dd4#^t`}btel!tKspOYo^7BmKOO');
define('SECURE_AUTH_SALT', '3-~e`W=</K}`MUM+^WUS:|9&Fg6bT@~Voa|phb3{=?;Cwg9Cr5wBuD_m{wgSi/#1');
define('LOGGED_IN_SALT',   'k[$9#L^q7rmjRK]@;56GNOdtpZ4^gL%}1~g;.fqp QKl(atOq2,/X;2^Qe&tA{i9');
define('NONCE_SALT',       '_:pfFx=~0?KiB{(yh^2I.7JFJd>8T3xf(Iq9p+:6{b)yj8zu]/y6fZhYp2N0,7,/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'moocsnews_';

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

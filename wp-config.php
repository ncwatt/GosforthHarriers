<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'dbuser' );

/** Database password */
define( 'DB_PASSWORD', 'w0rdpr3ss' );

/** Database hostname */
define( 'DB_HOST', 'mariadb' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/** Force update method to be direct  */
define('FS_METHOD','direct');

/** Diable automatic updates */
define( 'WP_AUTO_UPDATE_CORE', false );

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
define( 'AUTH_KEY',         '#)&bItMc`$J;!l_t_Sd Nubi}d+u<3kz0a;u,aAGc&(1QC<A*po,/pc4r+t:w 9Q' );
define( 'SECURE_AUTH_KEY',  'dUIqU1mBwn(Lu_>Vs|okiW OK5K/qRKDS1g{QGvIQa2LOpa=wO1O=P>7(&z<p^rH' );
define( 'LOGGED_IN_KEY',    'B)E^Q46F%>ij1|acu<Y.i44iCh?Nt7IhV)>M1*AReIAzjFxi];{Z[gOD)]z4=R9C' );
define( 'NONCE_KEY',        '$>=~oOR{t_Z=aUX<cbeJ$!!7]=<M3BXs$GMI1]y:P^Oo@{JlTb@sseM@mlAR3{2/' );
define( 'AUTH_SALT',        'Ah.&u*)rsO|lERaNMk7beWaYQi<qd[XO.S.l_;neks_;5KmTpV%)Kqpj(R7&I8?U' );
define( 'SECURE_AUTH_SALT', ';Zy{6:jKqNqA|:/GqfmOxXG,$)cJe]lPML#PE$2$egdJU`2M)RY<2v--)5+/@.Y:' );
define( 'LOGGED_IN_SALT',   'R%(Bf(uQ#a+y#T67mCaPFBHuTi2 )D3ImDWVQ30ZiJ7+IturUl9D=vf@L#H24SS:' );
define( 'NONCE_SALT',       '1E-87HbV>N9Dz,E=8juq&~u;D@`gVNPM&t?/ }tF[w*BGIA=5FI| ^3LjpQkE)Wv' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/*define( 'WP_DEBUG_LOG', true );*/

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

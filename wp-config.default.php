<?php
/**
 * Default config settings
 *
 * Enter any WordPress config settings that are default to all environments
 * in this file. These can then be overridden in the environment config files.
 * 
 * Please note if you add constants in this file (i.e. define statements) 
 * these cannot be overridden in environment config files.
 * 
 * @package    Studio 24 WordPress Multi-Environment Config
 * @version    1.0
 * @author     Studio 24 Ltd  <info@studio24.net>
 */
  
define( 'WP_MEMORY_LIMIT', '64M' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'o.;xe]YN [0C@w3wsM$#m2{h%4>Z *+M8a @9l|$b9Q[_uk<!|IEnlzM[3XLVj(*');
define('SECURE_AUTH_KEY',  '_MVR8~+H!]fw;l,-N(:tH.0>S*QHzB#X||,>Nt:qrU;0ZuRCUcE|N3mZ3;YWAACg');
define('LOGGED_IN_KEY',    '5AWuwRO&MfVvV 1Z_m[-o[+}R:[fnXj{k:V/6]4rtVYcYBxI0oyTE3`t|7&}JTMB');
define('NONCE_KEY',        'JUMhc!m1z8-BfI(=^-QaIF2_V|jCAQ rHf5?XQ~J7g$`^S^+JP2*%i:FYXzBs*Jt');
define('AUTH_SALT',        '`;]{gP%Hgtt{6-0Q4%Gt ~FK=5RK8|B;7cJ?+7H1ZExy@VW~{eeNN~G;<tc|(W@J');
define('SECURE_AUTH_SALT', 'gI)f(KPk!wx|{,,hVTb@[j7J),[Qq6TeQ!E{p}2l}I*cTjuir,-HDHc@%Mcp(X: ');
define('LOGGED_IN_SALT',   '726U1P`+[dL9Pux|Tv*_wqf: M4|4^0mqlA&*(w{-F<9)/d`+,V)4|%zg1]X4b<i');
define('NONCE_SALT',       'iI^Pz{jo+:yIO, Wg?e-eH}U`86N||&N^sm~.o0^hq};JvulcGtn>ob(n3^M{F+e');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

define( 'WP_ALLOW_MULTISITE', true );
/* Multisite */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'onetribewellness.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

define( 'SUNRISE', 'on' );
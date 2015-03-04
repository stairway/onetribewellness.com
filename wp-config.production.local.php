<?php
/**
 * Local Production environment config settings
 *
 * Enter any WordPress config settings that are specific to this environment 
 * in this file.
 * 
 * @package    Studio 24 WordPress Multi-Environment Config
 * @version    1.0
 * @author     Studio 24 Ltd  <info@studio24.net>
 */
  

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_onetribewellness');

/** MySQL database username */
define('DB_USER', 'wpAndrew');

/** MySQL database password */
define('DB_PASSWORD', 'kzr739rp8');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* Revisr */
define( 'REVISR_GIT_DIR', '/Volumes/Data/Users/ahaller/Sites/onetribewellness.com/wp-content/themes/onetribewellness' );

/* Multisite */
define('DOMAIN_CURRENT_SITE', 'onetribewellness.local');
define('PATH_CURRENT_SITE', '/');

define( 'SUNRISE', 'on' );
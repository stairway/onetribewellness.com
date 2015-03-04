<?php
/**
 * Setup environments
 * 
 * Set environment based on the current server hostname, this is stored
 * in the $hostname variable
 * 
 * You can define the current environment via: 
 *     define('WP_ENV', 'production');
 * 
 * @package    Studio 24 WordPress Multi-Environment Config
 * @version    1.0
 * @author     Studio 24 Ltd  <info@studio24.net>
 */


// Set environment based on hostname
switch ($hostname) {
    case 'onetribewellness.local':
        define( 'WP_ENV', 'production.local' );
        define( 'REVISR_GIT_DIR', '/Volumes/Data/Users/ahaller/Sites/onetribewellness.com/wp-content/themes/onetribewellness' );
        break;
        
    case 'staging.onetribewellness.local':
        define( 'WP_ENV', 'staging.local' );
        define( 'REVISR_GIT_DIR', '/Volumes/Data/Users/ahaller/Sites/onetribewellness.com/wp-content/themes/onetribewellness_dev' );
        break;
    
    case 'staging.onetribewellness.com':
        define( 'WP_ENV', 'staging' );
        define( 'REVISR_GIT_DIR', '/home/onetri6/public_html/onetribewellness.com/wp-content/themes/onetribewellness_dev' );
        break;

    case 'onetribewellness.com':
    default: 
        define( 'WP_ENV', 'production' );
        define( 'REVISR_GIT_DIR', '/home/onetri6/public_html/onetribewellness.com/wp-content/themes/onetribewellness' );
}

<?php
/*
Plugin Name: Admin Branding
Plugin URI: http://www.wpbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=pluginuri
Description: Brand the WordPress admin section and login screen.
Version: 1.1.2
Author: Gabriel Nordeborn
Author URI: http://www.wpbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=authoruri
Text Domain: wpbizplugins-uac
*/

/*  Admin Branding
    Copyright 2014  Gabriel Nordeborn  (email : gabriel@wpbizplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 *
 * START BY INCLUDING THE VARIOUS EMBEDDED PLUGINS. CURRENTLY:
 *  - ReduxFramework for options
 *
 */
  

// Include Redux if plugin isn't available
if ( ! class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' ) ) {

    require_once( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' );

}

require_once( dirname( __FILE__ ) . '/inc/redux-config.php' );
require_once( dirname( __FILE__ ) . '/inc/custom-functions.php' );         // Import our custom functions first

// Register activation hook
register_activation_hook( __FILE__, 'wpbizplugins_uac_activation_function' );

// Load localization
function wpbizplugins_uac_init_plugin() {

    load_plugin_textdomain( 'wpbizplugins-uac', false, dirname( __FILE__ ) . '/lang' );

}

add_action( 'admin_init', 'wpbizplugins_uac_init_plugin' );

/**
 * Function for redirecting to login page if option is set.
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_do_redirect() {

    // Get the login URL
    $login_url = get_option( 'wpbizplugins_uac_login_url' );
    $login_url = sanitize_title_with_dashes( $login_url );

    if( ( isset( $login_url ) ) && ( $login_url != '' ) ) {

        $match_url = get_site_url() . '/' . $login_url;

        // Set the base protocol to http
        $protocol = 'http';
        // check for https
        if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
            $protocol .= "s";
        }

        $current_url = $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

        if( $current_url == $match_url ) {

            $redir_url = admin_url();
            //header ('HTTP/1.1 301 Moved Permanently');
            header ('Location: ' . $redir_url);
            exit();

        }

    }

}

wpbizplugins_uac_do_redirect();

function wpbizplugins_uac_load_custom_wp_admin_style() {

        // Custom CSS
        wp_register_style( 'wpbizplugins-admin-customization', plugins_url( '/assets/css/admin-customization.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'wpbizplugins-admin-customization' );

}

add_action( 'admin_enqueue_scripts', 'wpbizplugins_uac_load_custom_wp_admin_style' );

/**
 * Function for outputting the admin panel modification CSS, if needed
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_output_css_for_admin_panel() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'show_extra_menu_styling' ] ) ) && ( $wpbizplugins_uac_options[ 'show_extra_menu_styling' ] == true ) ) {

        $css = get_option( 'wpbizplugins_uac_admin_panel_css' );
        echo $css;

    }
}

add_action( 'admin_head', 'wpbizplugins_uac_output_css_for_admin_panel' );

/**
 * Modify the admin title.
 *
 */

function wpbizplugins_uac_admin_title( $admin_title, $title ) {

    return get_bloginfo( 'name' ).' &bull; ' . $title;

}

/**
 * Wrapper function for changing the admin title
 *
 */

function wpbizplugins_uac_admin_title_wrapper() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'remove_wp_from_title' ] ) ) && ( $wpbizplugins_uac_options[ 'remove_wp_from_title' ] == true ) ) add_filter( 'admin_title', 'wpbizplugins_uac_admin_title', 10, 2 );

}

add_action( 'admin_init', 'wpbizplugins_uac_admin_title_wrapper' );

/**
 * Function for adding a custom logo to the admin menu
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_add_logo_to_admin_menu() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'menu_logo' ][ 'url' ] ) ) && ( $wpbizplugins_uac_options[ 'menu_logo' ][ 'url' ] != '' ) ) {

        $image = '<img id="admin-menu-logo" src="' . $wpbizplugins_uac_options[ 'menu_logo' ][ 'url' ] . '">';

        if( ( isset( $wpbizplugins_uac_options[ 'menu_logo_link_url' ] ) ) && ( $wpbizplugins_uac_options[ 'menu_logo_link_url' ] != '' ) ) {

            if( ( isset( $wpbizplugins_uac_options[ 'menu_logo_link_newwindow' ] ) ) && ( $wpbizplugins_uac_options[ 'menu_logo_link_newwindow' ] == true ) ) $extra_html = ' target="_blank"'; else $extra_html = '';

            $image = '<a href="' . $wpbizplugins_uac_options[ 'menu_logo_link_url' ] . '"' .  $extra_html . '>' . $image . '</a>';

        }

        ?>
        <script type="text/javascript">
            jQuery( document ).ready( function() {
                jQuery( '#adminmenuwrap' ).prepend( '<div id="admin-menu-logo-container"><?php echo $image; ?></div>');
            });
        </script>
        <style type="text/css">
        #admin-menu-logo-container {
            padding: 10px 10px 10px 10px;
        }
        #admin-menu-logo {
            display: block;
            max-height: 100%;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            vertical-align: middle;
        }

        </style>
        <?php
 
    }
}

add_action( 'admin_footer', 'wpbizplugins_uac_add_logo_to_admin_menu' );

/**
 * Add custom text to the footer
 *
 * @since 1.0.2
 *
 */

function wpbizplugins_uac_add_custom_text_to_footer() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'footer_text' ] ) ) && ( $wpbizplugins_uac_options[ 'footer_text' ] != '' ) ) {

        echo apply_filters( 'the_content', $wpbizplugins_uac_options[ 'footer_text' ] );
 
    }

}

add_action( 'in_admin_footer', 'wpbizplugins_uac_add_custom_text_to_footer' );

/**
 * Function for removing unwanted dashboard metaboxes.
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_remove_unwanted_dashboard_widgets() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'show_rightnow' ] ) ) && ( $wpbizplugins_uac_options[ 'show_rightnow' ] == false ) ) remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    if( ( isset( $wpbizplugins_uac_options[ 'show_activity' ] ) ) && ( $wpbizplugins_uac_options[ 'show_activity' ] == false ) ) remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
    if( ( isset( $wpbizplugins_uac_options[ 'show_quickpress' ] ) ) && ( $wpbizplugins_uac_options[ 'show_quickpress' ] == false ) ) remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    if( ( isset( $wpbizplugins_uac_options[ 'show_primary' ] ) ) && ( $wpbizplugins_uac_options[ 'show_primary' ] == false ) ) remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );

}

add_action( 'wp_dashboard_setup', 'wpbizplugins_uac_remove_unwanted_dashboard_widgets', 9999 );

/**
 * Use a transient to set and output the CSS we want to hide in the admin section.
 *
 * @since 1.0
 *
 */

function wpbizplugins_uac_load_css_to_hide_transient() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }


    // Get any existing copy of our transient data
    if ( ( $css = get_transient( 'wpbizplugins_uac_css_to_hide' ) ) === false ) {

        // It wasn't there, so regenerate the data and save the transient
         $css = wpbizplugins_uac_get_css_to_hide( $wpbizplugins_uac_options[ 'css_to_hide' ] );
         set_transient( 'wpbizplugins_uac_css_to_hide', $css, 24 * HOUR_IN_SECONDS );

    }

    // Use the data like you would have normally...
    echo $css;

}

add_action( 'admin_head', 'wpbizplugins_uac_load_css_to_hide_transient' );

/**
 * Function for changing the name of the dashboard.
 *
 * @since 1.0.0
 */

function wpbizplugins_uac_change_dashboard_name( $menu ) {  

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        return $menu;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return $menu;
    }


    if( ( is_admin() ) && ( isset( $wpbizplugins_uac_options[ 'dashboard_name' ] ) ) && ( $wpbizplugins_uac_options[ 'dashboard_name' ] != '' ) ) {

        foreach( $menu as $id => $item_array ) {

            if( $item_array[0] == 'Dashboard' ) $menu[ $id ][ 0 ] = $wpbizplugins_uac_options[ 'dashboard_name' ];

        }

    }

    return $menu;
}

add_filter( 'add_menu_classes', 'wpbizplugins_uac_change_dashboard_name' );

function wpbizplugins_uac_change_dashboard_name_on_dashboard() {

    global $wpbizplugins_uac_options;

    if( ( is_admin() ) && ( isset( $wpbizplugins_uac_options[ 'dashboard_name'] ) ) && ( $wpbizplugins_uac_options[ 'dashboard_name'] != '' ) ) {

        echo '<script type="text/javascript">jQuery( document ).ready( function() {
        jQuery( "div#wpwrap div#wpcontent div#wpbody div#wpbody-content div.wrap h2" ).text("' . $wpbizplugins_uac_options[ 'dashboard_name'] . '");
    });</script>';

    }

}

add_action('admin_head-index.php', 'wpbizplugins_uac_change_dashboard_name_on_dashboard');

/**
 * Add your own CSS and JS to the admin section. 
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_load_custom_admin_css() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    $css_general = get_option( 'wpbizplugins_uac_admin_general_css' );

    if( $css_general != false ) echo $css_general;

    echo '<style type="text/css">';

    /**
     * BUTTONS
     *
     */

    if( ( isset( $wpbizplugins_uac_options[ 'show_extra_button_styling' ] ) ) && ( $wpbizplugins_uac_options[ 'show_extra_button_styling' ] == true ) ) {

        echo '.button-primary { 
            background: none repeat scroll 0% 0% ' . $wpbizplugins_uac_options[ 'button_color_background' ] . ' !important; 
            border-color: ' . $wpbizplugins_uac_options[ 'button_color_border' ] . ' !important;
            box-shadow: 0px 1px 0px rgba(150, 150, 150, 0.5) inset, 0px 1px 0px rgba(0, 0, 0, 0.15) !important;
        }'; 

        echo '.button-primary:hover { 
            background: none repeat scroll 0% 0% ' . $wpbizplugins_uac_options[ 'button_color_border' ] . ' !important; 
            border-color: ' . $wpbizplugins_uac_options[ 'button_color_background' ] . ' !important;
            box-shadow: none !important;
        }'; 

    }

    if( ( isset( $wpbizplugins_uac_options[ 'admin_custom_css'] ) ) && ( $wpbizplugins_uac_options[ 'admin_custom_css'] != '' ) ) {
        echo $wpbizplugins_uac_options[ 'admin_custom_css' ];
    }

    echo '</style>';

   /*echo '<style type="text/css">
    #adminmenuwrap {
        height: 100%;
    }
    </style>';*/

}

add_action( 'admin_head', 'wpbizplugins_uac_load_custom_admin_css' );

function wpbizplugins_uac_load_custom_admin_js() {

    global $wpbizplugins_uac_options;

    // Exit if the current is above the capability threshold.
    if( ( isset( $wpbizplugins_uac_options[ 'show_for_admin' ] ) ) && ( $wpbizplugins_uac_options[ 'show_for_admin' ] == true ) && ( current_user_can( 'delete_plugins' ) ) ) {
        //return;
    } elseif( ( isset( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) && ( current_user_can( $wpbizplugins_uac_options[ 'capability_threshold' ] ) ) ) {
        return;
    }

    if( ( isset( $wpbizplugins_uac_options[ 'admin_custom_js'] ) ) && ( $wpbizplugins_uac_options[ 'admin_custom_js'] != '' ) ) {
        echo '<script type="text/javascript">' . $wpbizplugins_uac_options[ 'admin_custom_js' ] . '</script>';
    }

}

add_action( 'admin_footer', 'wpbizplugins_uac_load_custom_admin_js' );

/**
 *
 * Functions for styling the login page.
 *
 */

function wpbizplugins_uac_style_login_page() { 

    $css = get_option( 'wpbizplugins_uac_css_for_login' );

    echo $css;

}

add_action( 'login_head', 'wpbizplugins_uac_style_login_page' );

function wpbizplugins_uac_js_for_login_page() { 

    $js = get_option( 'wpbizplugins_uac_js_for_login' );

    echo $js;

}

add_action( 'login_footer', 'wpbizplugins_uac_js_for_login_page' );

/**
 * Change login title and link
 *
 * @since 1.1.1
 *
 */

function wpbizplugins_uac_login_link( $login_header_url ) {

    global $wpbizplugins_uac_options;

    if( ( isset( $wpbizplugins_uac_options[ 'login_link' ] ) ) && ( $wpbizplugins_uac_options[ 'login_link' ] != '' ) ) $login_header_url = $wpbizplugins_uac_options[ 'login_link' ];

    return $login_header_url;

}

add_filter( 'login_headerurl', 'wpbizplugins_uac_login_link' );

function wpbizplugins_uac_login_title( $login_header_title ) {

    global $wpbizplugins_uac_options;

    if( ( isset( $wpbizplugins_uac_options[ 'login_title' ] ) ) && ( $wpbizplugins_uac_options[ 'login_title' ] != '' ) ) $login_header_title = $wpbizplugins_uac_options[ 'login_title' ];

    return $login_header_title;

}

add_filter( 'login_headertitle', 'wpbizplugins_uac_login_title' );

function wpbizplugins_uac_login_link_blank() {

    global $wpbizplugins_uac_options;

    if( ( isset( $wpbizplugins_uac_options[ 'login_link' ] ) ) && ( $wpbizplugins_uac_options[ 'login_link' ] != '' ) ) {
        echo '<script type="text/javascript">jQuery( document ).ready( function() {
            jQuery( "#login a" ).attr( "target", "_blank" );
        });
        </script>';
    }

}

add_action( 'login_footer', 'wpbizplugins_uac_login_link_blank' );
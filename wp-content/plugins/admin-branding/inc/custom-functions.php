<?php
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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  021101301  USA
*/

/**
 *
 * This file contains custom functions.
 *
 */

/**
 * Activation hook
 *
 */

function wpbizplugins_uac_activation_function() {

    
    
}

/**
 * Function for getting an array of the current dashboard widgets, in the right format.
 *
 * @return Array Returns array of available dashboard widgets.
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_return_array_of_dashboard_widgets( $list_for_redux = true ) {

    global $wp_meta_boxes;
    $dashboard = $wp_meta_boxes[ 'dashboard' ];

    $return_list = array();

    foreach( $dashboard as $top_level ) {

        foreach( $top_level as $sub_level ) {

            foreach( $sub_level as $item ) {

                $current_item_array = array(

                    $item[ 'id' ]     => strip_tags( $item[ 'title' ] ),
                    );

                $return_list = array_merge( $return_list, $current_item_array );
            }

        }

    }

    return $return_list;
}

/**
 * Function to return the CSS to hide formatted and ready to be output in our admin CSS section.
 *
 * @param string The output of the css_to_hide field in the options.
 * @return string The formatted CSS based on the css_to_hide-field.
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_get_css_to_hide( $css_to_hide ) {

    global $wpbizplugins_uac_options;

    $css_to_hide_array = explode( '&', $css_to_hide );

    $css = '<style type="text/css">';

    if( $wpbizplugins_uac_options[ 'show_footer_messages' ] == false ) {
        $css .= '#footer-upgrade, #footer-thankyou { display: none; }';
    }

    foreach( $css_to_hide_array as $line ) {
        $css .= $line . '{ display: none !important; }';
    }

    $css .= '</style>';

    return $css;
}

function wpbizplugins_uac_get_js_for_login( $js ) {

    $return_string = '<script type="text/javascript">' . $js . '</script>';

    return $return_string;

}

function wpbizplugins_uac_get_css_for_login( $options ) {

    $css = '<style type="text/css">';

    /**
     * SET CUSTOM LOGOTYPE AND BACKGROUND
     *
     */

    if( $options[ 'custom_logo' ][ 'url' ] != '' ) {

        // Set the width to 320 if it's above that, this way the logo won't be oversized (but might be slightly deformed..)
        if( $options[ 'custom_logo' ][ 'width' ] >= 320) $width = 320; else $width = $options[ 'custom_logo' ][ 'width' ];

        $css .= '.login #login h1 a {
                    background-image: url( "' .  $options[ 'custom_logo' ][ 'url' ] . '" );
                    background-size: 100% 100% !important;
                    background-position: center top !important;
                    background-repeat: no-repeat !important;
                    width: ' . $width . 'px;
                    height: ' . $options[ 'custom_logo' ][ 'height' ] . 'px;
                    margin-bottom: 10px;
                    padding-bottom: 0px;
                    display: block;
                    
                }
                ';
    }

    $css .= 'body {';

    if( $options[ 'custom_background' ][ 'background-image' ] != '' ) {
     
        // Background image.
        $css .=  'background-image: url( "'      . $options[ 'custom_background' ][ 'background-image' ] .        '") !important;';
         
        // Background image options
        if( $options[ 'custom_background' ][ 'background-repeat' ] != '' )      $css .=  'background-repeat: '     . $options[ 'custom_background' ][ 'background-repeat' ] .       ' !important;';
        if( $options[ 'custom_background' ][ 'background-position' ] != '' )    $css .=  'background-position: '   . $options[ 'custom_background' ][ 'background-position' ] .     ' !important;';
        if( $options[ 'custom_background' ][ 'background-size' ] != '' )        $css .=  'background-size: '       . $options[ 'custom_background' ][ 'background-size' ] .         ' !important;';
        if( $options[ 'custom_background' ][ 'background-attachment' ] != '' )  $css .=  'background-attachment: ' . $options[ 'custom_background' ][ 'background-attachment' ] .   ' !important;';

    } else {
 
        // Background color
        $css .=  'background-color: '      . $options[ 'custom_background' ][ 'background-color' ] .        ' !important;';

    }

    $css .= '}';

    /**
     * SHOW AND HIDE VARIOUS ELEMENTS
     *
     */

    if( $options[ 'show_rememberme' ] == false ) {
        $css .= '.login .forgetmenot { display: none !important; }';
    }

    if( $options[ 'show_lostyourpassword' ] == false ) {
        $css .= '.login #nav { display: none !important; }';
    }

    if( $options[ 'show_backtoblog' ] == false ) {
        $css .= '.login #backtoblog { display: none !important; }';
    }

    /**
     * CHANGE THE LOOK OF THE LOGIN FORM
     *
     */

    if( $options[ 'login_box_shadow' ] == false ) $css .= '.login form { box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.0); }';

    $css .= '.login form {';

    if( $options[ 'login_box_background' ][ 'background-image' ] != '' ) {
     
        // Background image.
        $css .=  'background-image: url( "'      . $options[ 'login_box_background' ][ 'background-image' ] .        '") !important;';
         
        // Background image options
        if( $options[ 'login_box_background' ][ 'background-repeat' ] != '' )      $css .=  'background-repeat: '     . $options[ 'login_box_background' ][ 'background-repeat' ] .       ' !important;';
        if( $options[ 'login_box_background' ][ 'background-position' ] != '' )    $css .=  'background-position: '   . $options[ 'login_box_background' ][ 'background-position' ] .     ' !important;';
        if( $options[ 'login_box_background' ][ 'background-size' ] != '' )        $css .=  'background-size: '       . $options[ 'login_box_background' ][ 'background-size' ] .         ' !important;';
        if( $options[ 'login_box_background' ][ 'background-attachment' ] != '' )  $css .=  'background-attachment: ' . $options[ 'login_box_background' ][ 'background-attachment' ] .   ' !important;';

    } else {
 
        // Background color
        $css .=  'background-color: '      . $options[ 'login_box_background' ][ 'background-color' ] .        ' !important;';

    }

    $css .= '}';

    $css .= '.login label { color: ' . $options[ 'login_box_text_color' ] . '; }';

    /**
     * BUTTONS
     *
     */

    if( $options[ 'show_extra_button_styling' ] == true ) {

        $css .= '.button-primary { 
            background: none repeat scroll 0% 0% ' . $options[ 'button_color_background' ] . ' !important; 
            border-color: ' . $options[ 'button_color_border' ] . ' !important;
            box-shadow: 0px 1px 0px rgba(150, 150, 150, 0.5) inset, 0px 1px 0px rgba(0, 0, 0, 0.15) !important;
        }'; 

        $css .= '.button-primary:hover { 
            background: none repeat scroll 0% 0% ' . $options[ 'button_color_border' ] . ' !important; 
            border-color: ' . $options[ 'button_color_background' ] . ' !important;
            box-shadow: none !important;
        }';

        $css .= 'input:focus, select:focus, textarea:focus { border-color: ' . $options[ 'button_color_border' ] . ' !important; box-shadow: 0px 0px 2px ' . $options[ 'button_color_background' ] . ' !important; }';

    }


    /**
     * CUSTOM CSS
     *
     */

    $css .= $options[ 'login_custom_css' ];

    // Close the style
    $css .= '</style>';

    return $css;

}

/**
 * Return array of capabilities for use with restricting access to editing the plugin contents.
 *
 * @return array Returns an array of all available capabilities.
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_return_capabilities_array() {

    $capabilities_array = array(
        'activate_plugins',
        'add_users',
        'create_users',
        'delete_others_pages',
        'delete_others_posts',
        'delete_pages',
        'delete_plugins',
        'delete_posts',
        'delete_private_pages',
        'delete_private_posts',
        'delete_published_pages',
        'delete_published_posts',
        'delete_themes',
        'delete_users',
        'edit_dashboard',
        'edit_others_pages',
        'edit_others_posts',
        'edit_pages',
        'edit_plugins',
        'edit_posts',
        'edit_private_pages',
        'edit_private_posts',
        'edit_published_pages',
        'edit_published_posts',
        'edit_theme_options',
        'edit_themes',
        'edit_users',
        'export',
        'import',
        'install_plugins',
        'install_themes',
        'list_users',
        'manage_categories',
        'manage_links',
        'manage_options',
        'moderate_comments',
        'promote_users',
        'publish_pages',
        'publish_posts',
        'read',
        'read_private_pages',
        'read_private_posts',
        'remove_users',
        'switch_themes',
        'unfiltered_html',
        'unfiltered_upload',
        'update_core',
        'update_plugins',
        'update_themes',
        'upload_files'
    );

    $capabilities_array_keypair = array();

    foreach( $capabilities_array as $capability ) {

        $capabilities_array_keypair[$capability] = $capability;

    }

    return $capabilities_array_keypair;
}

/**
 * Function to return CSS for general styling of the admin seection.
 *
 * @return string Contains the CSS.
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_get_admin_styling_css( $options ) {

    ob_start();

    ?>
    <style type="text/css">

    <?php

    /**
     * ADMIN MENU STYLING
     *
     */

    switch ( $options[ 'menu_layout' ] ) {
        case 'floating_rounded':
            ?>
            
            #adminmenuwrap {
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                padding-top: 20px;
                padding-bottom: 20px;
                margin-top: 50px;
                left: 10px;
                -webkit-box-shadow: 5px 4px 10px 0px rgba(50, 50, 50, 0.52);
                -moz-box-shadow:    5px 4px 10px 0px rgba(50, 50, 50, 0.52);
                box-shadow:         5px 4px 10px 0px rgba(50, 50, 50, 0.52);
                margin-bottom: 50px;
            }

            .wrap {
                padding: 20px 20px 20px 20px;
            }

            <?php
            break;
        
    }

    /**
     * HIDE VARIOUS SCREENS
     *
     */

    if( $options[ 'hide_screen' ] == true ) echo '#screen-options-link-wrap { display: none; }';
    if( $options[ 'hide_help' ] == true ) echo '#contextual-help-link-wrap { display: none; }';
    if( $options[ 'hide_update_nag' ] == true ) echo '.update-nag { display: none; }';

    /**
     * BACKGROUND ETC
     *
     */

    echo '#wpwrap {';

    if( $options[ 'custom_background_admin' ][ 'background-image' ] != '' ) {
     
        // Background image.
        echo  'background-image: url( "'      . $options[ 'custom_background_admin' ][ 'background-image' ] .        '") !important;';
         
        // Background image options
        if( $options[ 'custom_background_admin' ][ 'background-repeat' ] != '' )      echo  'background-repeat: '     . $options[ 'custom_background_admin' ][ 'background-repeat' ] .       ' !important;';
        if( $options[ 'custom_background_admin' ][ 'background-position' ] != '' )    echo  'background-position: '   . $options[ 'custom_background_admin' ][ 'background-position' ] .     ' !important;';
        if( $options[ 'custom_background_admin' ][ 'background-size' ] != '' )        echo  'background-size: '       . $options[ 'custom_background_admin' ][ 'background-size' ] .         ' !important;';
        if( $options[ 'custom_background_admin' ][ 'background-attachment' ] != '' )  echo  'background-attachment: ' . $options[ 'custom_background_admin' ][ 'background-attachment' ] .   ' !important;';

    } else {
 
        // Background color
        echo  'background-color: '      . $options[ 'custom_background_admin' ][ 'background-color' ] .        ' !important;';

    }

    echo '}';

    /**
     * MISC STUFF
     *
     */
    
    // Set own icon for the admin bar
    if( $options[ 'adminbar_icon' ][ 'url' ] != '' ) echo '#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before { content: url(' . $options[ 'adminbar_icon' ][ 'url' ] . ') !important;';
    
    ?>

    </style>

    <?php

    $css = ob_get_contents();
    ob_end_clean();

    return $css;    

}

/**
 * Function to return CSS for styling the admin menu.
 *
 * @return string Contains the CSS.
 * @since 1.0.0
 *
 */

function wpbizplugins_uac_get_admin_menu_css( $options ) {

    $panel_background_color = $options[ 'menu_color_panel' ];
    $panel_background_color_shade = $options[ 'menu_color_panel_shade' ];
    $menu_item_background_hover = $options[ 'menu_color_hover_background' ];
    $icon_color = $options[ 'menu_color_icons' ];
    $text_color = $options[ 'menu_color_text' ];
    $text_hover_color = $options[ 'menu_color_text_hover' ];

    ob_start();

    ?>
    <style type="text/css">

    /* Admin Menu */
    #adminmenuback, #adminmenuwrap, #adminmenu { background: <?php echo $panel_background_color ?> !important; }

    #adminmenu a { color: <?php echo $text_color ?>; }

    #adminmenu div.wp-menu-image:before { color: <?php echo $icon_color; ?>; }

    #adminmenu a:hover, #adminmenu li.menu-top:hover, #adminmenu li.opensub > a.menu-top, #adminmenu li > a.menu-top:focus { color: <?php echo $text_color; ?>; background-color: <?php echo $menu_item_background_hover; ?>; }

    #adminmenu li.menu-top:hover div.wp-menu-image:before, #adminmenu li.opensub > a.menu-top div.wp-menu-image:before { color: white; }

    /* Active tabs use a bottom border color that matches the page background color. */
    .about-wrap h2 .nav-tab-active, .nav-tab-active, .nav-tab-active:hover { border-bottom-color: <?php echo $panel_background_color ?>; }

    /* Admin Menu: submenu */
    #adminmenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu { background: <?php echo $panel_background_color_shade; ?>; }

    #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after { border-right-color: <?php echo $panel_background_color_shade; ?>; }

    #adminmenu .wp-submenu .wp-submenu-head { color: <?php echo $text_color; ?>; }

    #adminmenu .wp-submenu a, #adminmenu .wp-has-current-submenu .wp-submenu a, .folded #adminmenu .wp-has-current-submenu .wp-submenu a, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a { color: <?php echo $text_color; ?>; }
    #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu .wp-has-current-submenu .wp-submenu a:focus, #adminmenu .wp-has-current-submenu .wp-submenu a:hover, .folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus, .folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover { color: <?php echo $text_hover_color; ?>; }

    /* Admin Menu: current */
    #adminmenu .wp-submenu li.current a, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a { color: white; }
    #adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus { color: <?php echo $menu_item_background_hover; ?>; }

    ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu > li.current > a.current:after { border-right-color: <?php echo $panel_background_color ?>; }

    #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head, .folded #adminmenu li.current.menu-top { color: white; background: <?php echo $menu_item_background_hover; ?>; }

    #adminmenu li.wp-has-current-submenu div.wp-menu-image:before { color: white; }

    /* Admin Menu: bubble */
    #adminmenu .awaiting-mod, #adminmenu .update-plugins { color: white; background: <?php echo $menu_item_background_hover; ?>; }

    #adminmenu li.current a .awaiting-mod, #adminmenu li a.wp-has-current-submenu .update-plugins, #adminmenu li:hover a .awaiting-mod, #adminmenu li.menu-top:hover > a .update-plugins { color: white; background: <?php echo $panel_background_color_shade; ?>; }

    /* Admin Menu: collapse button */
    #collapse-menu { color: <?php echo $icon_color; ?>; }

    #collapse-menu:hover { color: white; }

    #collapse-button div:after { color: <?php echo $icon_color; ?>; }

    #collapse-menu:hover #collapse-button div:after { color: white; }

    /* Admin Bar */
    #wpadminbar { color: white; background: <?php echo $panel_background_color; ?>; }

    #wpadminbar .ab-item, #wpadminbar a.ab-item, #wpadminbar > #wp-toolbar span.ab-label, #wpadminbar > #wp-toolbar span.noticon { color: white; }

    #wpadminbar .ab-icon, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before, #wpadminbar .ab-item:after { color: <?php echo $icon_color; ?>; }

    #wpadminbar .ab-top-menu > li:hover > .ab-item, #wpadminbar .ab-top-menu > li.hover > .ab-item, #wpadminbar .ab-top-menu > li > .ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus, #wpadminbar-nojs .ab-top-menu > li.menupop:hover > .ab-item, #wpadminbar .ab-top-menu > li.menupop.hover > .ab-item { color: white; background: <?php echo $panel_background_color_shade; ?>; }

    #wpadminbar > #wp-toolbar li:hover span.ab-label, #wpadminbar > #wp-toolbar li.hover span.ab-label, #wpadminbar > #wp-toolbar a:focus span.ab-label { color: white; }

    #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar li:hover .ab-item:after, #wpadminbar li:hover #adminbarsearch:before { color: white; }

    /* Admin Bar: submenu */
    #wpadminbar .menupop .ab-sub-wrapper { background: <?php echo $panel_background_color_shade; ?>; }

    #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu { background: <?php echo $panel_background_color_shade; ?>; }

    #wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar-nojs .quicklinks .menupop:hover ul li a { color: <?php echo $text_color; ?>; }

    #wpadminbar .quicklinks li .blavatar, #wpadminbar .menupop .menupop > .ab-item:before { color: <?php echo $icon_color; ?>; }

    #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover .ab-item:after, #wpadminbar li.hover .ab-item:after, #wpadminbar li:hover #adminbarsearch:before { color: <?php echo $text_hover_color; ?>; }

    #wpadminbar .quicklinks li a:hover .blavatar, #wpadminbar .menupop .menupop > .ab-item:hover:before { color: white; }

    /* Admin Bar: search */
    #wpadminbar #adminbarsearch:before { color: <?php echo $icon_color; ?>; }

    #wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus { color: white; background: <?php echo $panel_background_color_shade; ?>; }

    #wpadminbar #adminbarsearch .adminbar-input::-webkit-input-placeholder { color: white; opacity: .7; }

    #wpadminbar #adminbarsearch .adminbar-input:-moz-placeholder { color: white; opacity: .7; }

    #wpadminbar #adminbarsearch .adminbar-input::-moz-placeholder { color: white; opacity: .7; }

    #wpadminbar #adminbarsearch .adminbar-input:-ms-input-placeholder { color: white; opacity: .7; }

    /* Admin Bar: my account */
    #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img { border-color: <?php echo $icon_color; ?>; background-color: <?php echo $icon_color; ?>; }

    #wpadminbar #wp-admin-bar-user-info .display-name { color: white; }

    #wpadminbar #wp-admin-bar-user-info a:hover .display-name { color: <?php echo $text_hover_color; ?>; }

    #wpadminbar #wp-admin-bar-user-info .username { color: <?php echo $text_color; ?>; }


    </style>

    <?php

    $css = ob_get_contents();
    ob_end_clean();

    return $css;
}
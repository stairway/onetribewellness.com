<?php

if (!class_exists('WPBizPlugins_AdminCustomization_Config')) {

    class WPBizPlugins_AdminCustomization_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs. ;)
            if ( true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            // add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action( $options, $css ) {

            global $wpbizplugins_uac_options_config;

            // Get the CSS to hide and put it in a transient
            $css_to_hide = wpbizplugins_uac_get_css_to_hide( $options[ 'css_to_hide' ] );
            set_transient( 'wpbizplugins_uac_css_to_hide', $css_to_hide, 24 * HOUR_IN_SECONDS );

            // Get the CSS for the login and put it in an option
            $css_for_login = wpbizplugins_uac_get_css_for_login( $options );
            update_option( 'wpbizplugins_uac_css_for_login', $css_for_login );

            // Get the custom JS for the login and put it in an option
            $js_for_login = wpbizplugins_uac_get_js_for_login( $options[ 'login_custom_js' ] );
            update_option( 'wpbizplugins_uac_js_for_login', $js_for_login );

            // Set the login URL to separate option
            $login_url = sanitize_title_with_dashes( $options[ 'login_url' ] );
            $wpbizplugins_uac_options_config->ReduxFramework->set( 'login_url', $login_url );
            
            update_option( 'wpbizplugins_uac_login_url', $login_url );

            // Get the CSS for the general admin styling
            $css_for_admin_general = wpbizplugins_uac_get_admin_styling_css( $options );
            update_option( 'wpbizplugins_uac_admin_general_css', $css_for_admin_general );

            // Get the CSS for the admin panel styling
            $css_for_admin_menu = wpbizplugins_uac_get_admin_menu_css( $options );
            update_option( 'wpbizplugins_uac_admin_panel_css', $css_for_admin_menu );

        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            
            //$sections = array();
            /*$sections[] = array(
                'title' => ''//__('Section via hook', 'wpbizplugins-uac'),
                'desc' => ''//__('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'wpbizplugins-uac'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );*/

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            //$defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {  

            // ACTUAL DECLARATION OF SECTIONS

            $this->sections[] = array(
                'title' => __('General settings', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-cogs',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'       => 'show_for_admin',
                        'type'     => 'switch',
                        'title'    => __( 'Show changes for admin?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Controls whether the plugin changes are visible for administrators', 'wpbizplugins-uac' ),
                        'desc'     => __( 'Useful when you are configuring the plugin and want to see the changes.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'capability_threshold',
                        'type'     => 'select',
                        'title'    => __('Capability threshold', 'wpbizplugins-uac'),
                        'subtitle' => __('The capability threshold', 'wpbizplugins-uac'),
                        'desc'     => __('Anyone that <strong>does not</strong> have this capability will have all of the settings in this plugin applied. Can for example be used to have all but administrators be affected by the plugin, by setting it to a capability that only administrators have.', 'wpbizplugins-uac'),
                        // Must provide key => value pairs for select options
                        'options'  => wpbizplugins_uac_return_capabilities_array(),
                        'default'  => 'delete_plugins',
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'login_url',
                        'type'     => 'text',
                        'title'    => __('Extra login URL', 'wpbizplugins-uac'),
                        'subtitle' => __('Add an extra login URL for your website', 'wpbizplugins-uac'),
                        'desc'     => __('If you want a more pleasant login URL than /wp-admin, add your desired URL here, in the form of url. <em>Example:</em> login. This will let you login on http://www.yourdomain.com/login.<br /><br /><strong>NOTE:</strong> The URL will automatically get converted into a URL friendly format.', 'wpbizplugins-uac'),
                        'default'  => '',
                        'compiler' => true
                    ),

                )
            );


            $this->sections[] = array(
                'title' => __('Color theme', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-tint',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'        => 'custom_background_admin',
                        'type'      => 'background',
                        'title'     => __('Body background', 'wpbizplugins-uac'),
                        'subtitle'  => __('Body background with image, color, etc.', 'wpbizplugins-uac'),
                        'desc'      => __('You can set the background of the admin section to anything you want here.', 'wpbizplugins-uac'),
                        'compiler'  => true,
                        'default'   => array(
                            'background-color' => '#f1f1f1'
                        )
                    ),

                    /**
                     * BUTTONS
                     *
                     */

                    array(
                        'id'       => 'show_extra_button_styling',
                        'type'     => 'switch',
                        'title'    => __( 'Set your own button color?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Activate this if you want to change the colors of the main buttons in the WordPress admin (and on the login page).', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'            => 'button_color_background',
                        'type'          => 'color',
                        'title'         => __('Button background color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Background color for the primary buttons.', 'wpbizplugins-uac'),
                        'default'       => '#2EA2CC',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_button_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'button_color_border',
                        'type'          => 'color',
                        'title'         => __('Button border and hover color', 'wpbizplugins-uac'),
                        'subtitle'      => __( 'Color for both the border and the hover for the primary buttons.', 'wpbizplugins-uac'),
                        'default'       => '#0074A2',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_button_styling', 'equals', true )
                    ),

                    /**
                     * MENU STYLING AND GENERAL COLOR THEME
                     *
                     */

                    array(
                        'id'       => 'show_extra_menu_styling',
                        'type'     => 'switch',
                        'title'    => __( 'Set your own menu color theme?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides extra menu styling.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'            => 'menu_color_panel',
                        'type'          => 'color',
                        'title'         => __('Panel background color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Background color for the panels.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#222222',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'menu_color_panel_shade',
                        'type'          => 'color',
                        'title'         => __('Panel background color, darker shade', 'wpbizplugins-uac'),
                        'subtitle'      => __('Darker shade for the background color for the panels.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#444444',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'menu_color_hover_background',
                        'type'          => 'color',
                        'title'         => __('Hover and active item background color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Hover and active item background color for the panels.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#222222',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'menu_color_icons',
                        'type'          => 'color',
                        'title'         => __('Icon color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Color of icons.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#ffffff',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'menu_color_text',
                        'type'          => 'color',
                        'title'         => __('Text color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Color of the text.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#ffffff',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),

                    array(
                        'id'            => 'menu_color_text_hover',
                        'type'          => 'color',
                        'title'         => __('Text color: Hover', 'wpbizplugins-uac'),
                        'subtitle'      => __('Hover color for the text.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#222222',
                        'validate'      => 'color',
                        'transparent'   => true,
                        'compiler'      => true,
                        'required'      => array( 'show_extra_menu_styling', 'equals', true )
                    ),
                )
            );

            $this->sections[] = array(
                'title' => __('Login screen', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-key',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Admin Customization configuration
                     *
                     */

                    array(
                        'id'       => 'custom_logo',
                        'type'     => 'media', 
                        'url'      => true,
                        //'mode'     => true,
                        'title'    => __('Custom logo', 'wpbizplugins-uac'),
                        'desc'     => __('Set a custom logo. Used in the dashboard and on the login screen. <strong>Make sure the logo is not wider than 320px!</strong>', 'wpbizplugins-uac'),
                        'subtitle' => __('Upload the custom logo here.', 'wpbizplugins-uac'),
                        'compiler' => true,
                        'readonly' => false
                    ),

                    array(
                        'id'       => 'login_link',
                        'type'     => 'text',
                        'title'    => __('Link for logo', 'wpbizplugins-uac'),
                        'subtitle' => __('Fill in this if you want your custom logo to link to something', 'wpbizplugins-uac'),
                        'desc'     => __('For example: Link to you company website.', 'wpbizplugins-uac'),
                        'default'  => '',
                        'validate' => 'url',
                        'required' => array('custom_logo','!=',''),
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'login_title',
                        'type'     => 'text',
                        'title'    => __('Title for logo link', 'wpbizplugins-uac'),
                        'subtitle' => __('The title of the logo link', 'wpbizplugins-uac'),
                        //'desc'     => __('For example: Link to you company website.', 'wpbizplugins-uac'),
                        'default'  => '',
                        'required' => array('custom_logo','!=',''),
                        'compiler' => true
                    ),

                    array(
                        'id'        => 'custom_background',
                        'type'      => 'background',
                        'title'     => __('Body background', 'wpbizplugins-uac'),
                        'subtitle'  => __('Body background with image, color, etc.', 'wpbizplugins-uac'),
                        'desc'      => __('You can set the background of the login screen to anything you want here.', 'wpbizplugins-uac'),
                        'compiler'  => true,
                        'default'   => array(
                            'background-color' => '#f1f1f1'
                        )
                    ),

                    array(
                        'id'       => 'login_box_shadow',
                        'type'     => 'switch',
                        'title'    => __( 'Box shadow on login box?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Do you want a box shadow on the login form?', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'        => 'login_box_background',
                        'type'      => 'background',
                        'title'     => __('Login box background', 'wpbizplugins-uac'),
                        'subtitle'  => __('Background for the login box.', 'wpbizplugins-uac'),
                        'compiler'  => true,
                        'default'   => array(
                            'background-color' => '#fff'
                        )
                    ),

                    array(
                        'id'            => 'login_box_text_color',
                        'type'          => 'color',
                        'title'         => __('Label text color', 'wpbizplugins-uac'),
                        'subtitle'      => __('Color of the text labels found inside the login box.', 'wpbizplugins-uac'),
                        //'desc'          => __('Will be used for buttons, the map, and more.', 'wpbizplugins-uac'),
                        'default'       => '#7777777',
                        'validate'      => 'color',
                        'transparent'   => false,
                        'compiler'      => true
                    ),


                    array(
                        'id'       => 'show_rememberme',
                        'type'     => 'switch',
                        'title'    => __( 'Show "Remember me"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the "Remember me"-button from the login form', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_lostyourpassword',
                        'type'     => 'switch',
                        'title'    => __( 'Show "Lost your password?"-link?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the "Lost your password?"-link from the login form', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_backtoblog',
                        'type'     => 'switch',
                        'title'    => __( 'Show "Back to blog"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the "Back to blog"-link from the login form', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'login_custom_css',
                        'type'     => 'ace_editor',
                        'title'    => __( 'Custom CSS for login screen', 'wpbizplugins-uac' ),
                        'subtitle' => __( 'Paste your custom CSS for the login screen here.', 'wpbizplugins-uac' ),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        //'desc'     => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'  => '',
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'login_custom_js',
                        'type'     => 'ace_editor',
                        'title'    => __( 'Custom JS for login screen', 'wpbizplugins-uac' ),
                        'subtitle' => __( 'Paste your custom JS for the login screen here.', 'wpbizplugins-uac' ),
                        'mode'     => 'javascript',
                        'theme'    => 'monokai',
                        //'desc'     => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'  => '',
                        'compiler' => true
                    ),
                )
            );

            $this->sections[] = array(
                'title' => __('Header', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'       => 'adminbar_icon',
                        'type'     => 'media', 
                        'url'      => true,
                        //'mode'     => true,
                        'title'    => __('Custom icon for the admin bar', 'wpbizplugins-uac'),
                        'desc'     => __('Set a custom icon for the admin bar. <strong>MUST BE 20px x 20px TO LOOK GOOD.</strong>', 'wpbizplugins-uac'),
                        'subtitle' => __('Upload the custom icon here.', 'wpbizplugins-uac'),
                        'readonly' => false,
                        'compiler' => true
                    ),

                    
                )
            );

            $this->sections[] = array(
                'title' => __('Dashboard', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-dashboard',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Admin Customization configuration
                     *
                     */

                    array(
                        'id'       => 'dashboard_name',
                        'type'     => 'text',
                        'title'    => __('Name of the Dashboard', 'wpbizplugins-uac'),
                        'subtitle' => __('Change the name of the Dashboard', 'wpbizplugins-uac'),
                        'desc'     => __('You can change the name of the Dashboard displayed on top of the menu here. Just write your own name in the field.', 'wpbizplugins-uac'),
                        'default'  => '',
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_rightnow',
                        'type'     => 'switch',
                        'title'    => __( 'Show dashboard widget "Right now"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the dashboard widget "Right now"', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_activity',
                        'type'     => 'switch',
                        'title'    => __( 'Show dashboard widget "Activity"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the dashboard widget "Activity"', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_quickpress',
                        'type'     => 'switch',
                        'title'    => __( 'Show dashboard widget "Quick press"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the dashboard widget "Quick press"', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'show_primary',
                        'type'     => 'switch',
                        'title'    => __( 'Show dashboard widget "Primary"?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the dashboard widget "Primary"', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                )
            );

            $this->sections[] = array(
                'title' => __('Menu', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-th-list',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Admin Customization configuration
                     *
                     */

                    array(
                        'id'       => 'menu_logo',
                        'type'     => 'media', 
                        'url'      => true,
                        'compiler' => true,
                        //'mode'     => true,
                        'title'    => __('Custom logo on top of menu', 'wpbizplugins-uac'),
                        'desc'     => __('Set a custom logo that appears on top of the WordPress admin menu. <strong>Recommended size: 140px x 140px</strong>', 'wpbizplugins-uac'),
                        'subtitle' => __('Upload the custom logo here.', 'wpbizplugins-uac'),
			'readonly' => false
                    ),

                    array(
                        'id'       => 'menu_layout',
                        'type'     => 'button_set',
                        'title'    => __('Select menu style', 'wpbizplugins-uac'),
                        'subtitle' => __('A preview of the selected style will appear underneath the select box.', 'wpbizplugins-uac'),
                        'desc'     => __('Choose from presets making the WordPress admin menu look a bit more fancy.', 'wpbizplugins-uac'),
                        //Must provide key => value pairs for options
                        'options' => array(
                            'default'                   => 'Default',
                            'floating_rounded'          => 'Floating and rounded'
                         ),
                        'default'   => 'floating_rounded',
                        'compiler'  => true
                    ),

                    array(
                        'id'       => 'menu_logo_link_url',
                        'type'     => 'text',
                        'title'    => __('Link for logo', 'wpbizplugins-uac'),
                        'subtitle' => __('Fill in this if you want your custom logo to link to something', 'wpbizplugins-uac'),
                        'desc'     => __('For example: Link to you company website.', 'wpbizplugins-uac'),
                        'default'  => '',
                        'validate' => 'url',
                        'required' => array('menu_logo','!=',''),
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'menu_logo_link_newwindow',
                        'type'     => 'switch',
                        'title'    => __( 'Open logo link in new window?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Toggle if you want the logo link to open in a new window.', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true,
                        'required' => array(
                                array('menu_logo','!=',''),
                                array('menu_logo_link_url','!=',''),
                            ),
                    ),

                )
            );

            $this->sections[] = array(
                'title' => __('Footer', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'       => 'show_footer_messages',
                        'type'     => 'switch',
                        'title'    => __( 'Show footer messages?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Shows or hides the footer messages "Thanks for creating with WordPress", and the current WordPress version.', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'               => 'footer_text',
                        'type'             => 'editor',
                        'title'            => __( 'Own text in footer', 'wpbizplugins-uac'),
                        'subtitle'         => __( 'You can add your own text to the admin footer here. Add your own links, logotype or anything you\'d like to in here. You can even add videos.', 'wpbizplugins-uac'),
                        'default'          => '',
                        'args'   => array(
                            'teeny'            => false,
                            'textarea_rows'    => 10
                        ),
                        'compiler'          => true,
                    ),
                )
            );


            $this->sections[] = array(
                'title' => __('Show and hide elements', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-eye-open',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'       => 'remove_wp_from_title',
                        'type'     => 'switch',
                        'title'    => __( 'Remove "WordPress" from the title of admin pages?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Enable this to remove the word WordPress from the titles in the browser bar on all admin pages.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'hide_screen',
                        'type'     => 'switch',
                        'title'    => __( 'Hide Screen Options tab?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Do you want to hide the Screen Options tab on every page in the admin?', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'hide_help',
                        'type'     => 'switch',
                        'title'    => __( 'Hide Help tab?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Do you want to hide the Help tab on every page in the admin?', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => false,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'hide_update_nag',
                        'type'     => 'switch',
                        'title'    => __( 'Hide update nag?', 'wpbizplugins-uac'),
                        'subtitle' => __( 'Hides the nag screen telling you you need to upgrade WordPress.', 'wpbizplugins-uac' ),
                        //'desc'     => __( 'If you turn this off, the functionality for testimonials will not be loaded.', 'wpbizplugins-uac' ),
                        'on'       => __( 'Yes', 'wpbizplugins-uac'),
                        'off'      => __( 'No', 'wpbizplugins-uac'),
                        'default'  => true,
                        'compiler' => true
                    ),

                    array(
                        'id'       => 'css_to_hide',
                        'type'     => 'ace_editor',
                        'title'    => __('Hide any CSS-class or ID', 'wpbizplugins-uac'),
                        'subtitle' => __('Put a CSS class or ID here and it will get hidden', 'wpbizplugins-uac'),
                        'desc'     => 'Put CSS classes or IDs separated by <strong>&</strong>, and they will get automatically hidden in the admin interface. Example:' . '<p><code>#some-id & .some-class & .another-class</code></p>',
                        'default'  => '',
                        'mode'     => 'text',
                        'theme'    => 'chrome',
                        'compiler' => true

                    ),

                )
            );

            $this->sections[] = array(
                'title' => __('CSS and JS', 'wpbizplugins-uac'),
                'desc' => __('Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-uac'),
                'icon' => 'el-icon-css',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(

                    array(
                        'id'       => 'admin_custom_css',
                        'type'     => 'ace_editor',
                        'title'    => __('Custom CSS for admin section', 'wpbizplugins-uac'),
                        'subtitle' => __('Put your own custom CSS for the admin section here', 'wpbizplugins-uac'),
                        //'desc'     => 'Put CSS classes or IDs separated by <strong>&</strong>, and they will get automatically hidden in the admin interface. Example:' . '<p><code>#some-id & .some-class & .another-class</code></p>',
                        'default'  => '',
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'compiler' => true

                    ),

                    array(
                        'id'       => 'admin_custom_js',
                        'type'     => 'ace_editor',
                        'title'    => __('Custom JS for admin section', 'wpbizplugins-uac'),
                        'subtitle' => __('Put your own custom JS/jQuery for the admin section here', 'wpbizplugins-uac'),
                        'desc'     => __('<strong>Remember:</strong> jQuery in the WordPress admin uses no-conflict, which means you call jQuery through the variable <strong>jQuery()</strong> and <u>not</u> $().', 'wpbizplugins-uac' ),
                        'default'  => '',
                        'mode'     => 'javascript',
                        'theme'    => 'monokai',
                        'compiler' => true

                    )
                )
            );

            $this->sections[] = array(
                'title'     => __('Import / Export', 'wpbizplugins-uac'),
                'desc'      => __('Import and Export the menu settings from file, text or URL.', 'wpbizplugins-uac'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => __('Import Export', 'wpbizplugins-uac'),
                        'subtitle'      => __('Save and restore your menu options', 'wpbizplugins-uac'),
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
           

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'wpbizplugins-uac'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            /*$this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'wpbizplugins-uac'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wpbizplugins-uac')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'wpbizplugins-uac'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wpbizplugins-uac')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'wpbizplugins-uac');
        */
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            //$theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'wpbizplugins_uac_options',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => __('Admin Branding Configuration', 'wpbizplugins-uac'),            // Name that appears at the top of your panel
                //'display_version'   => '1.0',  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title' => __('Admin Branding', 'wpbizplugins-uac'),
                'page_title' => __('Admin Branding Options', 'wpbizplugins-uac'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => false,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'edit.php?post_type=wpbizplugins-uac',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => plugins_url( '../assets/img/wpbizplugins-ab-menuicon.png', __FILE__ ),                       // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-dashboard',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'wpbizplugins_uac_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/wpbizplugins',
                'title' => __('Like us on Facebook', 'wpbizplugins-uac'),
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/wpbizplugins',
                'title' => __('Follow us on Twitter', 'wpbizplugins-uac'),
                'icon'  => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            $this->args['intro_text'] = '<img src="' . plugins_url( '../assets/img/wpbizplugins-ab-logo.png', __FILE__ ) . '">
            <p>' . __('Welcome to the Admin Branding configuration. Set up your own custom branding here.', 'wpbizplugins-uac') . '</p>
            <p>Find more business related WordPress plugins at <a href="http://www.wpbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=uac-header" target="_blank">WPBizPlugins.com</a>.</p>
            <br /><hr /><br />
            <p><strong>Like the Admin Branding plugin?</strong> Well, if so, I have a feeling you\'ll <em>really</em> like my other <strong>free</strong> plugin called <strong><a href="http://www.wpbizplugins.com/wordpress-plugins/custom-admin-help-boxes?utm_source=uac&utm_medium=plugin&utm_campaign=uac-header" target="_blank">Custom Admin Help Boxes</a></strong>, that lets you add your own custom help boxes to the dashboard or edit dialogue of any page/post/custom post type. Perfect for making your WordPress admin experience even more branded! Read more on the <a href="http://www.wpbizplugins.com/wordpress-plugins/custom-admin-help-boxes?utm_source=uac&utm_medium=plugin&utm_campaign=uac-header" target="_blank">WPBizPlugins website</a>.</p>';

            // Add content after the form.
            $this->args['footer_text'] = '<a href="http://www.wpbizplugins.com?utm_source=uac&utm_medium=plugin&utm_campaign=uac" target="_blank"><img style="margin-top: 20px; margin-bottom: 20px;" src="' . plugins_url( '../assets/img/wpbizplugins-footer-img.png', __FILE__ ) . '"></a>';
        }

    }
    
    global $wpbizplugins_uac_options_config;
    $wpbizplugins_uac_options_config = new WPBizPlugins_AdminCustomization_Config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;

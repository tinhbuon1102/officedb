<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "realty_theme_option";

    /*
     *
     * --> Action hook examples
     *
     */

    // If Redux is running as a plugin, this will remove the demo notice and links
    add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');


    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        'opt_name'              => $opt_name,
        'page_slug'             => 'theme-options',
        'page_title'            => esc_html__( 'Theme Options', 'realty' ),
        'disable_tracking'      => true,
        'dev_mode'              => false,
        'forced_dev_mode_off'   => true,
        'customizer'            => false,
        'update_notice'         => false,
        'intro_text'            => '',
        'footer_text'           => '',
        'footer_credit'     	  => '&nbsp;',
        'admin_bar'             => true,
        'menu_title'            => esc_html__( 'Theme Options', 'realty' ),
        'menu_type'             => 'submenu',
        'allow_sub_menu'        => true,
        'page_icon'             => 'icon-themes',
        'page_priority'         => null,
        'page_parent'           => 'themes.php',
        'page_permissions'      => 'manage_options',
        'class'                 => 'realty-theme-option',
        'async_typography'      => true,
        'output'                => true,
        'output_tag'            => true,
        'settings_api'          => true,
        'cdn_check_time'        => '1440',
        'compiler'              => true,
        'page_permissions'      => 'manage_options',
        'save_defaults'         => true,
        'show_import_export'    => true,
        'transient_time'        => '3600',
        'network_sites'         => true,
        'display_version'       => $theme->get( 'Version' ),
        'display_name'          => $theme->get( 'Name' ),
        // HINTS
	      'hints'                 => array(
          'icon'          => 'el el-question-sign',
          'icon_position' => 'right',
          'icon_color'    => 'lightgray',
          'icon_size'     => 'normal',
          'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
          ),
          'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
          ),
          'tip_effect'    => array(
            'show' => array(
              'effect'   => 'slide',
              'duration' => '500',
              'event'    => 'mouseover',
            ),
          'hide' => array(
              'effect'   => 'slide',
              'duration' => '500',
              'event'    => 'click mouseleave',
            ),
          ),
        ),
    );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => esc_html__( 'Theme Information 1', 'realty' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'realty' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'realty' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'realty' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'realty' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

		/**
		 * General
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'General', 'realty' ),
      'desc'      => '',
      'icon'      => 'fa fa-cog',
      'fields'    => array(
	      array(
          'id'       	=> 'site-layout',
          'type'     	=> 'image_select',
          'title'    	=> esc_html__( 'Site Layout', 'realty' ),
          'subtitle' 	=> esc_html__( 'Choose between full-width & boxed', 'realty' ),
          'desc'      => esc_html__( 'Default:', 'realty' ) . ' full-width',
          'options'   => array(
						'full-width' => array(
						  'alt' => esc_html__( 'Full-Width', 'realty' ),
						  'img' => ReduxFramework::$_url.'assets/img/1col.png',
						),
						'boxed' => array(
						  'alt' => esc_html__( 'Boxed', 'realty' ),
						  'img' => ReduxFramework::$_url.'assets/img/3cm.png',
						 )
          ),
          'default'   => 'full-width',
        ),
        array(
          'id'        		=> 'site-max-width',
          'type'      		=> 'slider',
          'title'     		=> esc_html__( 'Site Maximum Width', 'realty' ),
          'subtitle'  		=> esc_html__( 'Set maximum width for boxed layout in pixel.', 'realty' ),
          'desc'      		=> esc_html__( 'Default', 'realty' ) . ': 1170',
          'required'  		=> array( 'site-layout', 'equals', 'boxed' ),
          'default'   		=> 1170,
          'min'      			=> 760,
          'step'      		=> 10,
          'max'       		=> 1600,
          'display_value' => 'text',
        ),
      	array(
          'id'        							=> 'color-body-background',
          'type'      							=> 'background',
          'output'    							=> array( 'body, .section-title span' ),
          'title'     							=> esc_html__( 'Body Background Color', 'realty' ),
          'default'   							=> array( 'background-color' => '#ffffff' ),
          'background-repeat'  			=> false,
          'background-attachment'  	=> false,
          'background-position'			=> false,
          'background-image'  			=> false,
          'transparent'			  			=> false,
          'background-size'	  			=> false,
          'preview'									=> false,
        ),
        array(
          'id'       							=> 'color-accent',
					'type'     							=> 'color',
					'title'    							=> esc_html__( 'Accent Color', 'realty' ),
			    'default'  							=> '#43becc',
			    'validate' 							=> 'color',
			    'transparent'		  			=> false,
				),
      	array(
          'id'        => 'favicon',
          'type'      => 'media',
          'title'     => esc_html__( 'Favicon', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false,
          'desc'      => esc_html__( 'Upload Square Graphic (Recommendation: 64x64 PNG file). In WordPress 4.3+ you can set the favicon under "Appearance > Customize" and click on "Site Identity" as well.', 'realty' ),
        ),
        array(
          'id'        => 'logo',
          'type'      => 'media',
          'title'     => __( 'Logo', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'desc'      => esc_html__( 'Make sure to upload a retina logo as well using the "Retina Logo" option below.', 'realty' ),
        ),
        array(
          'id'        => 'logo-retina',
          'type'      => 'media',
          'title'     => __( 'Retina Logo', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'desc'      => esc_html__( 'Image has to be double the size of your logo above. File name has to be same as logo above with "@2x" at the end. If your logo is named "logo.png", then retina logo has to be named "logo@2x.png".', 'realty' ),
        ),
        array(
          'id'        => 'logo-url',
          'type'      => 'text',
          'title'     => esc_html__( 'Logo URL', 'realty' ),
          'desc'      => esc_html__( 'Helpful when using intro page as your frontpage. If empty, URL will be your site address.', 'realty' ),
          'validate' 	=> 'url'
        ),
        array(
          'id'        => 'logo-login',
          'type'      => 'media',
          'title'     => esc_html__( 'WordPress Login Page - Logo', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false,
          'desc'      => esc_html__( 'Max. dimension: 320x80px', 'realty' ),
        ),
        array(
          'id'          => 'background-login',
			    'type'        => 'color',
			    'title'       => esc_html__( 'WordPress Login Page - Background Color', 'realty' ),
			    'default'     => '#f8f8f8',
			    'validate'    => 'color',
			    'transparent' => false,
				),
				array(
          'id'        => 'header-login-modal-layout',
          'type'      => 'radio',
          'title'     => esc_html__( 'Login Page or Popup', 'realty' ),
          'desc'      => esc_html__( 'If you select login page make sure to create a new page and assign page template "Login Page".', 'realty' ),
           //Must provide key => value pairs for radio options
          'options'   => array(
          	'login-popup' 	=> esc_html__( 'Login Popup', 'realty' ),
            'login-page' 		=> esc_html__( 'Login Page', 'realty' ),
          ),
          'default'   => 'login-popup'
				),
				array(
          'id'        => 'google-recaptcha-site-key',
          'type'      => 'text',
          'title'     => esc_html__( 'Google reCAPTCHA Site Key', 'realty' ),
          'subtitle'  => '<a href="https://developers.google.com/recaptcha/docs/start" target="_blank">https://developers.google.com/recaptcha/docs/start</a>',
          'desc'      => esc_html__( 'If site key and secret key set, Google reCAPTCHA shows on all contact forms. Helps to prevent spam.', 'realty' ),
        ),
        array(
          'id'        => 'google-recaptcha-secret-key',
          'type'      => 'text',
          'title'     => esc_html__( 'Google reCAPTCHA Secret Key', 'realty' ),
          'subtitle'  => '<a href="https://developers.google.com/recaptcha/docs/start" target="_blank">https://developers.google.com/recaptcha/docs/start</a>',
          'desc'      => esc_html__( 'If site key and secret key set, Google reCAPTCHA shows on all contact forms. Helps to prevent spam.', 'realty' ),
        ),
        array(
          'id'        => 'custom-styles',
          'type'      => 'ace_editor',
          'mode' 			=> 	'css',
					'theme' 		=> 	'chrome',
          'title'     => esc_html__( 'Custom Styles (CSS)', 'realty' ),
          'subtitle'  => esc_html__( 'Inline CSS right before closing <strong>&lt;/head&gt;</strong>', 'realty' ),
          'default'   => '',
        ),
        array(
          'id'        => 'custom-scripts',
          'type'      => 'ace_editor',
          'mode' 			=> 	'javascript',
					'theme' 		=> 	'chrome',
          'title'     => esc_html__( 'Custom Scripts (Google Analytics etc.)', 'realty' ),
          'subtitle'  => esc_html__( 'Inline scripts right before closing <strong>&lt;/body&gt;</strong>', 'realty' ),
          'desc'      => esc_html__( 'Use "jQuery" selector, instead of "$" shorthand. Do not add any &lt;script&gt; tags, they are already applied to this code.', 'realty' ),
          'default'   => '',
        ),
        array(
          'id'        => 'enable-rtl-support',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable Right-to-Left Language Support', 'realty' ),
          'subtitle'  => esc_html__( 'Required for carousel etc.', 'realty' ),
          'default'   => 0
        ),
      )
    ) );

    /**
		 * Pages
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Pages', 'realty' ),
      'icon'      => 'fa fa-file-text-o',
      'fields'    => array(
	      array(
          'id'        => 'property-search-results-page',
          'type'      => 'select',
          'data'      => 'pages',
          'title'     => esc_html__( 'Property Search Results Page', 'realty' ),
          'desc'      => esc_html__( 'Make sure the selected page contains [property_listing] and [property_search_form] shortcode to display property search results.', 'realty' )
        ),
	      array(
          'id'        => 'user-registration-terms-page',
          'type'      => 'select',
          'data'      => 'pages',
          'title'     => esc_html__( 'User Registration: Select "Terms & Conditions" Page', 'realty' ),
          'desc'      => esc_html__( 'Select the page that contains your "Terms & Conditions". When a page has been selected, users are required to accept your terms and conditions in order to register.', 'realty' )
        ),
        array(
          'id'        => '404-page',
          'type'      => 'select',
          'data'      => 'pages',
          'title'     => esc_html__( 'Custom 404 Error Page', 'realty' ),
          'subtitle'  => esc_html__( 'Content of selected page will be shown to visitors who request a non-existing, so called "404 Error Page".', 'realty' ),
          'desc'      => esc_html__( 'If nothing selected, default 404 Content will be displayed.', 'realty' ),
        ),
      )
    ) );


    /**
		 * Header
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Header', 'realty' ),
      'icon'      => 'fa fa-arrow-up',
      'fields'    => array(
	      array(
          'id'        		=> 'header-layout',
          'type'      		=> 'radio',
          'title'     		=> esc_html__( 'Header Layout', 'realty' ),
          'options'   		=> array(
	          'default'   => esc_html__( 'Navigation below site logo', 'realty' ),
	          'nav-right' => esc_html__( 'Navigation next to site logo', 'realty' ),
          ),
          'default'   		=> 'nav-right'
        ),
      	array(
          'id'        							=> 'color-header-background',
          'type'      							=> 'background',
          'output'    							=> array( '#header' ),
          'title'     							=> esc_html__( 'Site Header Background', 'realty' ),
          'default'   							=> array( 'background-color' => '#fff' ),
          'background-repeat'  			=> false,
          'background-attachment'  	=> false,
          'background-position'			=> false,
          'background-image'  			=> false,
          'transparent'			  			=> false,
          'background-size'	  			=> false,
        ),
        array(
          'id'       								=> 'color-header',
			    'type'     								=> 'color',
			    'output'									=> array( '.top-header, .top-header a, .site-branding, .site-title a, .site-description a, .primary-menu a' ),
			    'title'    								=> esc_html__( 'Site Header Font Color', 'realty' ),
			    'default'  								=> '#787878',
			    'validate' 								=> 'color',
			    'transparent'		  				=> false,
				),
				array(
          'id'        => 'show-sub-menu-by-default-on-mobile',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Sub Menu By Default On Mobile', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'disable-header-login-register-bar',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable Login/Register Bar', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'site-header-hide-property-submit-link',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Hide "Property Submit" Header Link', 'realty' ),
          'subtitle'  => esc_html__( 'For Non-Logged-In Visitors', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'site-header-position-fixed',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable Fixed/Scrolling Header', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'header-tagline',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Site Tagline', 'realty' ),
          'subtitle'  => esc_html__( 'Display "Settings  > General > Tagline" underneath logo', 'realty' ),
          'default'   => 0
        ),
      )
    ) );


    /**
		 * Map
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Map', 'realty' ),
      'icon'      => 'fa fa-map-marker',
      'fields'    => array(
	      array(
          'id'        => 'google-maps-api-key',
          'type'      => 'text',
          'title'     => esc_html__( 'Google Maps API Key', 'realty' ),
          'subtitle'  => esc_html__( 'Required since June 22, 2016', 'realty' ),
          'desc'      => '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key</a>',
        ),
      	array(
          'id'        => 'map-marker-property-default',
          'type'      => 'image_select',
          'title'     => esc_html__( 'Default Map Marker: Property', 'realty' ),
          'subtitle'  => esc_html__( 'Default: Green (Dimension: 100x138)', 'realty' ),
          //Must provide key => value(array:title|img) pairs for radio options
          'options'   => array(
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-fat.png'    => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-fat.png'   => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-fat.png'  => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-fat.png'   => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-fat.png' ),
          ),
          'default'   => get_template_directory_uri().'/lib/images/map-marker/map-marker-green-fat.png'
        ),
      	array(
          'id'        => 'map-marker-property',
          'type'      => 'media',
          'title'     => esc_html__( 'Custom Map Marker: Property', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'subtitle'  => esc_html__( 'Transparent PNG file (Recommended Dimension: 100x138)', 'realty' ),
        ),
        array(
          'id'        => 'map-marker-cluster-default',
          'type'      => 'image_select',
          'title'     => esc_html__( 'Default Map Marker: Cluster', 'realty' ),
          'subtitle'  => esc_html__( 'Default: Red (Dimension: 100x100)', 'realty' ),
          //Must provide key => value(array:title|img) pairs for radio options
          'options'   => array(
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-round.png'   => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-round.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-round.png'  => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-round.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-round.png' => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-round.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-round.png'  => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-round.png' ),
          ),
          'default'   => get_template_directory_uri().'/lib/images/map-marker/map-marker-gold-round.png'
        ),
        array(
          'id'        => 'map-marker-cluster',
          'type'      => 'media',
          'title'     => esc_html__( 'Custom Map Marker: Cluster', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'desc'      => esc_html__( 'For a non-square graphic, you have to add some Custom CSS to position the cluster number: #map .cluster > div { line-height: 1 !important; padding-top: ??px !important; }', 'realty' ),
          'subtitle'  => esc_html__( 'Transparent Square PNG file (Recommended Dimension: 100x100)', 'realty' ),
        ),
				array(
          'id'        => 'enable-vertical-listing-marker-sync',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Listing To Map Sync', 'realty' ),
          'desc'      => esc_html__( 'If checked, different map marker highlights listing on mouseover.', 'realty' ),
          'default'   => 1
        ),
        array(
          'id'        => 'map-marker-property-sync',
          'type'      => 'image_select',
          'title'     => esc_html__( 'Default Map Marker: Listing To Map Sync', 'realty' ),
          'subtitle'  => esc_html__( 'Default: Green (Dimension: 100x138)', 'realty' ),
          //Must provide key => value(array:title|img) pairs for radio options
          'options'   => array(
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-fat.png'   => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-red-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-fat.png'  => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-blue-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-fat.png' => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-green-fat.png' ),
            get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-fat.png'  => array( 'title' => '', 'img' => get_template_directory_uri() . '/lib/images/map-marker/map-marker-gold-fat.png' ),
          ),
          'default'   => get_template_directory_uri().'/lib/images/map-marker/map-marker-red-fat.png',
          'required' 	=> array( 'enable-vertical-listing-marker-sync', '=', 1 ),
        ),
        array(
          'id'        => 'map-alternate-marker-property',
          'type'      => 'media',
          'title'     => esc_html__( 'Custom Map Marker: Listing To Map Sync', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'subtitle'  => esc_html__( 'Transparent PNG file (Recommended Dimension: 100x138)', 'realty' ),
					'required' 	=> array( 'enable-vertical-listing-marker-sync', '=', 1 ),
        ),
				array(
          'id'        => 'disable-google-maps-api',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Don\'t load Google Maps API', 'realty' ),
          'desc'      => esc_html__( 'Check this box, if map is not showing. Another plugin might already load the API, and a duplicate API request produces an error.', 'realty' ),
          'default'   => 0
        ),
				array(
          'id'        => 'style-your-map',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Style Your Map', 'realty' ),
          'desc'      => esc_html__( 'Check this box if you want to add custom styling to your map.', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'map-style',
          'type'      => 'ace_editor',
          'mode' 			=> 'javascript',
					'theme' 		=> 	'chrome',
          'title'     => esc_html__( 'Map Style (JavaScript Array)', 'realty' ),
          'desc'      => sprintf( esc_html__( 'Copy+paste code from one of the maps over at %s.', 'realty' ), '<a href="https://snazzymaps.com/explore" target=_"blank">https://snazzymaps.com/explore</a>' ),
					'required' 	=> 	array( 'style-your-map','=', 1 ),
          'default'   => '',
        ),
			)
		) );


    /**
		 * Property
		 *
		 */

		// Default Fields
		$default_fields_search = array(
			esc_html__( 'Default Fields', 'realty' ) => array(
        'estate_search_by_keyword'       => esc_html__( 'Search by Keyword', 'realty' ),
        'estate_property_id'             => esc_html__( 'Property ID', 'realty' ),
        'estate_property_location'       => esc_html__( 'Location', 'realty' ),
        'estate_property_status'         => esc_html__( 'Status', 'realty' ),
        'estate_property_type'           => esc_html__( 'Type', 'realty' ),
        'estate_property_price'          => esc_html__( 'Price', 'realty' ),
        'estate_property_pricerange'     => esc_html__( 'Price Range (set options above)', 'realty' ),
        'estate_property_size'           => esc_html__( 'Size', 'realty' ),
        'estate_property_rooms'          => esc_html__( 'Rooms', 'realty' ),
        'estate_property_bedrooms'       => esc_html__( 'Bedrooms', 'realty' ),
        'estate_property_bathrooms'      => esc_html__( 'Bathrooms', 'realty' ),
        'estate_property_garages'        => esc_html__( 'Garages', 'realty' ),
        'estate_property_available_from' => esc_html__( 'Availability / Date', 'realty' ),
      )
		);

		$default_taxonomies = array(
			esc_html__( 'Default Taxonomies', 'realty' ) => array(
        'estate_property_location' => esc_html__( 'Location', 'realty' ),
        'estate_property_status'   => esc_html__( 'Status', 'realty' ),
        'estate_property_type'     => esc_html__( 'Type', 'realty' ),
      )
    );

    // Default Property Listing Fields
		$default_fields_listing = array(
			esc_html__( 'Default Listing Fields', 'realty' ) => array(
				'estate_property_id'             => esc_html__( 'Property ID', 'realty' ),
				'estate_property_size'           => esc_html__( 'Size', 'realty' ),
				'estate_property_rooms'          => esc_html__( 'Rooms', 'realty' ),
				'estate_property_bedrooms'       => esc_html__( 'Bedrooms', 'realty' ),
				'estate_property_bathrooms'      => esc_html__( 'Bathrooms', 'realty' ),
				'estate_property_garages'        => esc_html__( 'Garages', 'realty' ),
				'estate_property_available_from' => esc_html__( 'Availability / Date', 'realty' ),
				'estate_property_updated'        => esc_html__( 'Date Updated', 'realty' ),
				'estate_property_views'          => esc_html__( 'Property Views', 'realty' ),
			)
    );

    require_once ( get_template_directory() . '/lib/functions-advanced-custom-fields.php' );

		// Check if ACF is activated & ACF for post type "property" field groups
		$acf_fields = array();
		$acf_fields_names = array();
		$acf_fields_labels = array();
		$merged_fields_search = array();
		$merged_fields_listing = array();

		if ( tt_acf_active() && tt_acf_group_id_property() ) {
		  $acf_fields_names = tt_acf_fields_name( tt_acf_group_id_property() );
		  $acf_fields_labels = tt_acf_fields_label( tt_acf_group_id_property() );
		}

		if ( ! tt_is_array_empty( $acf_fields_names ) && ! tt_is_array_empty( $acf_fields_labels ) ) {
			$acf_fields = array_combine( $acf_fields_names, $acf_fields_labels );
			$acf_fields = array( esc_html__( 'Advanced Custom Fields', 'realty' ) => $acf_fields );
			$merged_fields_search = array_merge( $default_fields_search, $acf_fields );
			$merged_fields_listing = array_merge( $default_fields_listing, $acf_fields );
		} else {
			$merged_fields_search = $default_fields_search;
			$merged_fields_listing = $default_fields_listing;
		}

		/**
		 * Single Property
		 *
		 */

		Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Single Property', 'realty' ),
      'icon'      => 'fa fa-home',
      'fields'    => array(
        array(
          'id'        => 'property-slug',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Slug', 'realty' ),
          'subtitle'  => 'Default "property"',
          'desc'      => esc_html__( 'URL slug for single property URL. I.e. http://yoursite.com/immobilie/nice-place/ (where "immobilie" represents the property slug"). Go to "Settings > Permalinks" and click "Save Changes" to apply your new property slug.', 'realty' ),
          'default'   => 'property',
        ),
        array(
          'id'        => 'property-show-login-users',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Single Property Page To Logged In Users Only', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'property-meta-data-type',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Meta Data', 'realty' ),
			    'options'  	=> array(
		        'default' 	=> esc_html__( 'Default', 'realty' ),
		        'custom' 		=> esc_html__( 'Custom', 'realty' )
			    ),
			    'default'  	=> 'default',
        ),
        array(
					'id'       		=> 'property-custom-meta-data',
					'type'     		=> 'repeater',
					'title'    		=> esc_html__( 'Custom Meta Data', 'realty' ),
					'desc' 		    => sprintf( esc_html__( 'Only single value field types (i.e. text, number) are accepted. Advanced Custom Fields with a type of "checkbox" etc. won\'t work. %s format: "fa fa-expand". %s format "icon-heart".', 'realty' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">FontAwesome icon class</a>', '<a href="' . get_template_directory_uri() . '/assets/fonts/realty/icons-reference.html" target="_blank">Realty icon class</a>' ),
					'bind_title' 	=> 'property-custom-meta-data-label',
					//'bind_title' 	=> false,
					//'group_values' => false, // Group all fields below within the repeater ID
					//'static'		 	=> 8,
					//'limit' 		=> 8,
					'sortable' 		=> true,
					'required' 		=> 	array( 'property-meta-data-type', '=', 'custom' ),
					'fields' 			=> array(
					  array(
					    'id'          => 'property-custom-meta-data-field',
					    'type'        => 'select',
					    'options'     => $merged_fields_listing,
					    'placeholder' => esc_html__( 'Field Type', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-meta-data-icon-class',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'FontAwesome Icon Class', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-meta-data-label',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'Label', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-meta-data-label-plural',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'Label Plural', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-meta-data-tooltip',
					    'type'        => 'checkbox',
					    'subtitle'		=> esc_html__( 'Show Tooltip Only', 'realty' ),
					    'default'   	=> 0,
					  )
					)
				),
				array(
          'id'        => 'property-meta-data-hide-print',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Property Meta Data: Hide Print Icon', 'realty' ),
          'default'   => 0,
        ),
        array(
          'id'        		=> 'property-id-type',
          'type'      		=> 'radio',
          'title'     		=> esc_html__( 'Property ID Type', 'realty' ),
          'options'   		=> array(
            'post_id' 			=> esc_html__( 'Post ID', 'realty' ),
            'custom_id' 		=> esc_html__( 'Custom Property ID', 'realty' ),
          ),
          'default'   		=> 'post_id'
        ),
				array(
          'id'        		=> 'property-layout',
          'type'      		=> 'radio',
          'title'     		=> esc_html__( 'Default Single Property Layout', 'realty' ),
          'options'   		=> array(
            'layout-full-width' => esc_html__( 'Full Width Property Image / Slideshow', 'realty' ),
            'layout-boxed'      => esc_html__( 'Boxed Property Image / Slideshow', 'realty' ),
          ),
          'default'       => 'layout-full-width'
        ),
        array(
          'id'        => 'property-single-slideshow-autoplay',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Property Slideshow Auto Play', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'property-slideshow-animation-type',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Slideshow Animation', 'realty' ),
          'options'   => array(
            'fade' 			=> esc_html__( 'Fade', 'realty' ),
            'slide' 		=> esc_html__( 'Slide', 'realty' ),
          ),
          'default'   => 'fade'
        ),
        array(
          'id'        		=> 'property-lightbox',
          'type'      		=> 'radio',
          'title'     		=> esc_html__( 'Property Lightbox', 'realty' ),
          'options'   		=> array(
	          'magnific-popup' 	=> esc_html__( 'Magnific Popup', 'realty' ),
	          'intense-images' 	=> esc_html__( 'Intense Images', 'realty' ),
	          'none' 						=> esc_html__( 'None', 'realty' ),
          ),
          'default'   		=> 'magnific-popup'
        ),
        array(
          'id'        => 'property-image-height',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Image Height', 'realty' ),
          'subtitle'  => esc_html__( 'Min. 400px for proper display on mobile', 'realty' ),
			    'options'  	=> array(
		        'original' 		=> esc_html__( 'Original Image Ratio', 'realty' ),
		        'fullscreen' 	=> esc_html__( 'Fullscreen', 'realty' ),
		        'custom' 			=> esc_html__( 'Custom Height', 'realty' )
			    ),
			    'default'  => 'custom',
        ),
        array(
          'id'        => 'property-image-custom-height',
          'type'      => 'text',
          'title'     => esc_html__( 'Custom Property Image Height', 'realty' ),
          'subtitle'  => esc_html__( 'Default', 'realty' ) . ': 600',
          'desc'      => esc_html__( 'Recommendation: Min. 400px for proper display on mobile devices.', 'realty' ),
          'validate'  => 'numeric',
          'default'   => '600',
					'required' 	=> 	array( 'property-image-height', '=', 'custom' ),
        ),
        array(
          'id'        => 'property-image-width',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Image Width', 'realty' ),
          'desc'      => esc_html__( 'Select smaller dimension for faster page load time. Setting doesn\'t effect slideshow container width, image still stretches full width.', 'realty' ),
			    'options'  	=> array(
		        'full' 								=> esc_html__( 'Original Image Ratio', 'realty' ),
		        'thumbnail-1600' 			=> esc_html__( '1600px', 'realty' ),
		        'thumbnail-1200' 			=> esc_html__( '1200px (Default)', 'realty' )
			    ),
			    'default'  => 'thumbnail-1200',
        ),
        array(
          'id'        => 'property-slideshow-navigation-type',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Slideshow Navigation', 'realty' ),
          'subtitle'  => esc_html__( 'Choose Property Slidshow Navigation Type', 'realty' ),
          'options'   => array(
            'thumbnail'	=> esc_html__( 'Thumbnails', 'realty' ),
            'dots' 			=> esc_html__( 'Dots', 'realty' ),
          ),
          'default'   => 'thumbnail'
        ),
        array(
          'id'        => 'property-additional-details-layout',
          'type'      => 'radio',
          'title'     => esc_html__( 'Additional Details Layout', 'realty' ),
			    'options'  	=> array(
		        'tab'			=> esc_html__( 'Tab', 'realty' ),
		        'grid' 		=> esc_html__( 'Grid', 'realty' )
			    ),
			    'default'  	=> 'tab',
        ),
        array(
          'id'        => 'property-additional-details-hide-empty',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Hide Empty Additional Details', 'realty' ),
          'default'   => 0
        ),
				array(
          'id'        => 'additional-area-hidden-fields',
          'type'      => 'select',
          'options'   => $acf_fields,
          'multi'			=> true,
          'sortable'	=> false,
          'title'     => esc_html__( 'Hide Selected Additional Fields', 'realty' ),
          'subtitle'  => esc_html__( 'Applied on single property page', 'realty' ),
          'desc'      => esc_html__( 'Select all additional fields you want to hide in additional fields area on single property page.', 'realty' )
        ),
        array(
          'id'        => 'property-features-hide-non-applicable',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Hide Non Applicable Property Features', 'realty' ),
          'default'   => 0
        ),
      	array(
          'id'        => 'property-title-details',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Details', 'realty' ),
        ),

        array(
          'id'        => 'property-title-additional-details',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Additional Details', 'realty' ),
          'subtitle'  => esc_html__( 'Additional custom fields can be added from "Custom Fields" menu. Prefix any "Field Name" with "additional_".', 'realty' ),
          'default'   => esc_html__( 'Additional details', 'realty' ),
        ),
        array(
          'id'        => 'property-title-features',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Features', 'realty' ),
          'default'   => esc_html__( 'Features', 'realty' ),
        ),
        array(
          'id'        => 'property-title-map',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Map', 'realty' ),
          'default'   => esc_html__( 'Location', 'realty' ),
        ),
        array(
          'id'        => 'property-title-attachments',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Attachments', 'realty' ),
          'default'   => esc_html__( 'Attachments', 'realty' ),
        ),
        array(
          'id'        => 'property-title-floor-plan',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Floor Plan', 'realty' ),
          'default'   => esc_html__( 'Floor Plan', 'realty' ),
        ),
        array(
          'id'        => 'property-title-agent',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Agent', 'realty' ),
          'default'   => esc_html__( 'Agent', 'realty' ),
        ),
        array(
          'id'        => 'show-single-property-map',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Single Property Map', 'realty' ),
          'default'   => 1
        ),
        array(
          'id'        => 'property-floor-plan-disable',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable Floor Plans', 'realty' ),
          'default'   => 0
        ),
				array(
          'id'        => 'property-social-sharing',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Display Social Sharing Buttons', 'realty' ),
          'default'   => 1
        ),
        array(
          'id'        => 'property-follow-disabled',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable Follow The Property', 'realty' ),
          'desc'      => esc_html__( 'Email icon next to property title, to subscribe to notifications via email when property is updated.', 'realty' ),
          'default'   => 0
        ),
        array(
					'id'        => 'property-agent-information',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Show Agent Information', 'realty' ),
					'default'   => 1
				),
				array(
					'id'        => 'property-show-agent-to-logged-in-users',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Show Agent Information To Logged In Users', 'realty' ),
					'default'   => 0
				),
        array(
          'id'        => 'property-contact-form',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Contact Form', 'realty' ),
          'default'   => 1
        ),
        array(
          'id'        => 'property-contact-form-any-shortcode',
          'type'      => 'text',
          'title'     => esc_html__( 'Use Any Contact Form shortcode Instead Of Default Contact Form', 'realty' ),
          'desc'      => esc_html__( 'Enter your Contact Form complete shortcode. Make sure the relevent plugin is activate. Leave empty to use the default contact form.', 'realty' ),
          
          'default'   => '',
          'required' 	=> 	array( 'property-contact-form', '=', '1' ),
        ),
        array(
          'id'        => 'property-contact-form-cf7-shortcode',
          'type'      => 'text',
          'title'     => esc_html__( 'Use Contact Form 7 Instead Of Default Contact Form', 'realty' ),
          'desc'      => esc_html__( 'Enter your Contact Form 7 shortcode ID. Make sure CF7 plugin is activated and that ID entered matches your CF7 form ID. Leave empty to use the default contact form.', 'realty' ),
          'validate'  => 'numeric',
          'default'   => '',
					'required' 	=> 	array( 'property-contact-form', '=', '1' ),
        ),
				array(
          'id'        => 'send-email-to-admin-only-cf7',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Send Contact Form Email to Admin Only', 'realty' ),
          'subtitle'  => esc_html__( 'Applies when using Contact Form 7 plugin.', 'realty' ),
          'default'   => '0'
        ),
        array(
          'id'        => 'property-contact-form-default-email',
          'type'      => 'text',
          'title'     => esc_html__( 'Default Contact Email Address', 'realty' ),
          'desc'      => esc_html__( 'Used, if agent has no email address, and on his/her profile page.', 'realty' ),
          'validate' 	=> 'email',
          'default'   => '',
        ),
				array(
          'id'        => 'property-contact-form-cc-admin',
          'type'      => 'text',
          'title'     => esc_html__( 'CC To Admin Email Address', 'realty' ),
          'desc'      => esc_html__( 'Used, if admin wants to get CC of the each email sent to Agents from property page.', 'realty' ),
          'validate' 	=> 'email',
          'default'   => '',
        ),
				array(
          'id'        => 'property-show-similar-properties',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Similar Properties', 'realty' ),
          'default'   => '1'
        ),
				array(
          'id'        => 'property-similar-properties-criteria',
          'type'      => 'checkbox',
					'required' 	=> array( 'property-show-similar-properties', '=', '1' ),
          'title'     => 'Similar Properties',
          'subtitle'  => 'Check criteria that a property has to meet in order to be listed on a property detail page under "Similar Properties".',
          'compiler'  => 'true',
          'options'   => array(
            'location'       => esc_html__( 'Same location', 'realty' ),
            'status'         => esc_html__( 'Same status', 'realty' ),
            'type' 			     => esc_html__( 'Same type', 'realty' ),
            'min_rooms'	     => esc_html__( 'Min. Rooms', 'realty' ),
            'max_price'      => esc_html__( 'Max. Price', 'realty' ),
            'available_from' => esc_html__( 'Available From', 'realty' ),

          ),
          'default'   => array(
            'location'       => '1',
            'status'         => '0',
            'type'           => '0',
            'min_rooms'      => '0',
            'max_price'      => '0',
            'available_from' => '0',
          )
        ),
        array(
          'id'            => 'property-similar-properties-columns',
          'type'          => 'spinner',
          'title'         => esc_html__( 'Similar Properties Columns', 'realty' ),
          'default'       => 3,
          'min'           => 1,
          'step'          => 1,
          'max'           => 4,
          'display_value' => 'label',
					'required'      => array( 'property-show-similar-properties', '=', '1' )
				),
				array(
          'id'        => 'property-comments',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Property Comments', 'realty' ),
          'default'   => 0
        ),
      )
    ) );

		/**
		 * Property Listings
		 *
		 */

		Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Property Listings', 'realty' ),
      'icon'      => 'fa fa-bars',
      'fields'    => array(
        array(
          'id'        => 'property-listing-type',
          'type'      => 'radio',
          'title'     => esc_html__( 'Property Listing Meta Data', 'realty' ),
			    'options'  	=> array(
		        'default' => esc_html__( 'Default', 'realty' ),
		        'custom' 	=> esc_html__( 'Custom', 'realty' )
			    ),
			    'default'  	=> 'default',
        ),
        array(
					'id'       		=> 'property-custom-listing',
					'type'     		=> 'repeater',
					'title'    		=> esc_html__( 'Custom Meta Data', 'realty' ),
					'desc' 		    => sprintf( esc_html__( 'Only single value field types (i.e. text, number) are accepted. Advanced Custom Fields with a type of "checkbox" etc. won\'t work. %s format: "fa fa-expand". %s format "icon-heart".', 'realty' ), '<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">FontAwesome icon class</a>', '<a href="' . get_template_directory_uri() . '/assets/fonts/realty/icons-reference.html" target="_blank">Realty icon class</a>' ),
					'bind_title' 	=> 'property-custom-listing-label',
					//'bind_title' 	=> false,
					//'group_values' => false, // Group all fields below within the repeater ID
					//'static'		 	=> 8,
					//'limit' 		=> 8,
					'sortable' 		=> true,
					'required' 		=> 	array( 'property-listing-type','=','custom' ),
					'fields' 			=> array(
					  array(
					    'id'          => 'property-custom-listing-field',
					    'type'        => 'select',
					    'options'     => $merged_fields_listing,
					    'placeholder' => esc_html__( 'Field Type', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-listing-icon-class',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'Icon Class', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-listing-label',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'Label', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-listing-label-plural',
					    'type'        => 'text',
					    'placeholder' => esc_html__( 'Label Plural', 'realty' ),
					  ),
					  array(
					    'id'          => 'property-custom-listing-tooltip',
					    'type'        => 'checkbox',
					    'subtitle'		=> esc_html__( 'Show Tooltip Only', 'realty' ),
					    'default'   	=> 0,
					  )
					)
				),
				array(
          'id'        => 'property-listing-default-view',
          'type'      => 'radio',
          'title'     => esc_html__( 'Default Property Listing View', 'realty' ),
			    'options'  	=> array(
		        'grid-view' 	=> esc_html__( 'Grid View', 'realty' ),
		        'list-view' 	=> esc_html__( 'List View', 'realty' )
			    ),
			    'default'  	=> 'grid-view',
        ),
				array(
          'id'        => 'property-listing-columns',
          'type'      => 'radio',
          'title'     => esc_html__( 'Default Number Of Columns', 'realty' ),
			    'options'  	=> array(
		        'col-md-6' 					=> esc_html__( '2 Columns', 'realty' ),
		        'col-lg-4 col-md-6' => esc_html__( '3 Columns', 'realty' ),
		        'col-lg-3 col-md-6' => esc_html__( '4 Columns', 'realty' ),
			    ),
			    'default'  	=> 'col-lg-4 col-md-6',
        ),
        array(
          'id'            => 'search-results-per-page',
          'type'          => 'spinner',
          'title'         => esc_html__( 'Default Number of Properties Per Page', 'realty' ),
          'desc'          => esc_html__( 'Used for property search, taxonomies etc.', 'realty' ),
          'default'       => 10,
          'min'           => 2,
          'step'          => 1,
          'max'           => 50,
          'display_value' => 'label'
				),
				array(
          'id'            => 'property-new-badge',
          'type'          => 'spinner',
          'title'         => esc_html__( '"New" Property Badge', 'realty' ),
          'desc'          => esc_html__( 'Add <i class="icon-hot-topic"></i> icon to property, if published within the last .. days. Set to "0" to disable this feature.', 'realty' ),
          'default'       => 7,
          'min'           => 0,
          'step'          => 1,
          'max'           => 360,
          'display_value' => 'label'
				),
				array(
          'id'        => 'property-listing-status-tag',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Show Property Status Tag', 'realty' ),
          'subtitle'  => esc_html__( 'E.g.: rented out, sold, for sale etc.', 'realty' ),
          'default'   => 1
        ),
				array(
          'id'        => 'listing-sample-image',
          'type'      => 'media',
          'title'     => esc_html__( 'Property Listing Placeholder Image', 'realty' ),
          'compiler'  => 'true',
          'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
          'desc'      => esc_html__( 'Image will be displayed in property listings if no featured image is provided. Upload any custom image of appropriate size (recommended: 400x300px).', 'realty' ),
        ),
				array(
          'id'        => 'property-comparison-disabled',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable "Property Comparison"', 'realty' ),
          'default'   => 0
        ),
				array(
          'id'        => 'property-favorites-disabled',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable "Add To Favorites"', 'realty' ),
          'default'   => 0
        ),
				array(
          'id'        => 'property-favorites-temporary',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Allow Non-Logged-In Visitors To Save Favorites Temporary', 'realty' ),
          'default'   => 0
        ),
        array(
          'id'        => 'enable-social-on-listing',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable Social Sharing On Single Listing', 'realty' ),
          'default'   => '1',
        ),
			)
		) );

    /**
		 * Property Search
		 *
		 */
    Redux::setSection( $opt_name, array(
	      'title'     => esc_html__( 'Property Search', 'realty' ),
	      'desc'      => esc_html__( 'Even if you currently don\'t use the price range slider, it is highly recommended to set default values, instead of leaving them blank.', 'realty' ),
	      'class'			=> 'property-search-section',
	      'icon'      => 'el-icon-search',
	      'fields'    => array(
				  array(
						'indent' 		=> true,
						'id'        => 'section-property-search',
						'type'      => 'section',
				  ),
				  array(
					  'id'        => 'search_results_default_order',
					  'type'      => 'select',
					  'title'     => esc_html__( 'Default Search Results Order', 'realty' ),
					  'options'   => array(
						  'featured'   => esc_html__( 'Featured First', 'realty' ),
						  'date-new'   => esc_html__( 'Sort By Date (Newest First)', 'realty' ),
						  'date-old'   => esc_html__( 'Sort By Date (Oldest First)', 'realty' ),
						  'price-high' => esc_html__( 'Sort by Price (Highest First)', 'realty' ),
						  'price-low'  => esc_html__( 'Sort by Price (Lowest First)', 'realty' ),
						  'name-asc'   => esc_html__( 'Sort by Name (Ascending)', 'realty' ),
						  'name-desc'  => esc_html__( 'Sort by Name (Descending)', 'realty' ),
						  'random'     => esc_html__( 'Random', 'realty' ),
					  ),
					  'default'   => 'featured'
				 ),
	        array(
	          'id'        => 'property-search-price-range-min',
	          'type'      => 'text',
	          'title'    	=> esc_html__( 'Price range: Min. price', 'realty' ),
	          'validate'  => 'numeric',
	          'default'   => 0,
					),
					array(
	          'id'        => 'property-search-price-range-max',
	          'type'      => 'text',
	          'title'    	=> esc_html__( 'Price range: Max. price', 'realty' ),
	          'validate'  => 'numeric',
	          'default'   => 1000000,
					),
					array(
	          'id'        => 'property-search-price-range-step',
	          'type'      => 'text',
	          'title'    	=> esc_html__( 'Price range: Step', 'realty' ),
	          'validate'  => 'numeric',
	          'default'   => 10000,
					),
	        array(
	          'id'        => 'datepicker-language',
	          'type'      => 'select',
	          'data'      => 'pages',
	          'title'     => esc_html__( 'Datepicker Language', 'realty' ),
	          'desc'      => esc_html__( 'Select the language of the property search datepicker.', 'realty' ),
				    'options'  	=> array(
			        'en' => 'English (Default)',
			        'ar' => 'Arabic',
			        'az' => 'Azerbaijani',
			        'bg' => 'Bulgarian',
			        'ca' => 'Catalan',
			        'cs' => 'Czech',
			        'cy' => 'Welch',
			        'da' => 'Danish',
			        'de' => 'German',
			        'el' => 'Greek',
			        'es' => 'Spanish',
			        'et' => 'Estonian',
			        'fa' => 'Persian',
			        'fi' => 'Finnish',
			        'fr' => 'French',
			        'he' => 'Hebrew',
			        'hr' => 'Croatian',
			        'hu' => 'Hungarian',
			        'id' => 'Bahasa Indonesia',
			        'is' => 'Icelandic',
			        'it' => 'Italian',
			        'ja' => 'Japanese',
			        'ka' => 'Georgian',
			        'kk' => 'Kazakh',
			        'kr' => 'Korean',
			        'lt' => 'Lithuanian',
			        'lv' => 'Latvian',
			        'mk' => 'Macedonian',
			        'ms' => 'Malay',
			        'nb' => 'Norwegian (bokmal)',
			        'nl-BE' => 'Belgium-Dutch',
			        'nl' => 'Dutch',
			        'no' => 'Norwegian',
			        'pl' => 'Polish',
			        'pt-BR' => 'Brazilian',
			        'pt' => 'Portuguese',
			        'ro' => 'Romanian',
			        'rs-latin' => 'Serbian-latin',
			        'rs' => 'Serbian-cyrillic',
			        'ru' => 'Russian',
			        'sk' => 'Slovak',
			        'sl' => 'Slovene',
			        'sq' => 'Albanian',
			        'sv' => 'Swedish',
			        'sw' => 'Swahili',
			        'th' => 'Thai',
			        'tr' => 'Turkish',
			        'ua' => 'Ukrainian',
			        'vi' => 'Vietnamese',
			        'zh-CN' => 'Simplified Chinese',
			        'zh-TW' => 'Traditional Chinese'
				    ),
				    'default'  => 'en',
	        ),
	        array(
	          'id'        => 'property-search-features',
	          'type'      => 'select',
	          'data'      => 'terms',
	          'args' 			=> array(
	          	'taxonomies' 			=> 'property-features'
	          ),
	          'multi'			=> true,
	          'sortable'	=> true,
	          'title'     => esc_html__( 'Property Features', 'realty' ),
	          'desc'      => esc_html__( 'Select all property features you want to add to property search form. Order via drag & drop.', 'realty' )
	        ),
	        array(
	          'id'        => 'property-search-field-relation',
	          'type'      => 'radio',
	          'title'     => esc_html__( 'Search Field Relation', 'realty' ),
	           //Must provide key => value pairs for radio options
	          'options'   => array(
	          	'OR' 			=> esc_html__( 'OR', 'realty' ),
	            'AND' 		=> esc_html__( 'AND', 'realty' ) . ' ' . esc_html__( '(Default)', 'realty' ),
	          ),
	          'default'   => 'AND'
					),
					array(
	          'id'        => 'property-search-price-dropdown',
		        'type'      => 'radio',
	          'title'     => esc_html__( 'Price Dropdown', 'realty' ),
	          'desc'      => esc_html__( 'If any dropdown option selected, make sure to set price range parameters above (min, max and step) as well.', 'realty' ),
					  'options'   => array(
						  'none'           => esc_html__( 'None', 'realty' ),
						  'dropdown-steps' => esc_html__( 'Dropdown Steps', 'realty' ),
						  'dropdown-range' => esc_html__( 'Dropdown Range', 'realty' ),
					  ),
					  'default'   => 'none'
					),
					array(
	          'id'        => 'property-search-field-dropdowns',
		        'type'      => 'checkbox',
	          'title'     => esc_html__( 'Dropdown for rooms, bedrooms, bathrooms, garages.', 'realty' ),
	          'default'   => 0
					),
	        array(
		      	'indent' 		=> false,
		        'id'        => 'section-property-search-end',
		        'type'      => 'section',
		      ),
	      	array(
		      	'indent' 		=> true,
		        'id'        => 'section-property-search-fields',
		        'type'      => 'section',
		        'title'     => esc_html__( 'Fields: Property Search', 'realty' ),
		        'subtitle'  => esc_html__( 'All search field attributes for every search field are mandatory. "Unique Search Parameter" has to be all lowercase letters, no spaces. Underscores and dashes allowed.', 'realty' ),
		      ),
	      	array(
						'id'       		=> 'property-search-fields',
						'type'     		=> 'repeater',
						'bind_title'  => 'property-search-label',
						'full_width' 	=> true,
						//'bind_title' 	=> false,
						//'group_values' => false, // Group all fields below within the repeater ID
						//'static'		 	=> 8,
						'limit'       => 50,
						'sortable' 	  => true,
						'fields' => array(
						  array(
						    'id'          => 'property-search-field',
						    'type'        => 'select',
						    'options'     => $merged_fields_search,
						    'placeholder' => esc_html__( 'Search Field Type', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-compare',
						    'type'        => 'select',
						    'options' 		=> array(
						      'equal'    						=> esc_html__( 'Equal', 'realty' ),
									'greater_than'       	=> esc_html__( 'Greater than', 'realty' ),
									'less_than'    				=> esc_html__( 'Less than', 'realty' ),
									'like'    						=> esc_html__( 'Like', 'realty' )
								),
						    'placeholder' => esc_html__( 'Compare As', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-label',
						    'type'        => 'text',
						    'placeholder' => esc_html__( 'Search Label', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-parameter',
						    'type'        => 'text',
						    'placeholder' => esc_html__( 'Unique Search Parameter', 'realty' ) . ' (' . esc_html__( 'required', 'realty' ) . ')',
						  )
						)
					),
					array(
		      	'indent' 		=> false,
		        'id'        => 'section-property-search-fields-end',
		        'type'      => 'section',
		      ),
					array(
						'indent' 		=> true,
	          'id'        => 'section-property-search-mini-fields',
	          'type'      => 'section',
	          'title'     => esc_html__( 'Fields: Property Search Mini', 'realty' ),
	          'subtitle'  => esc_html__( 'The following search mini setup is used in shortcode [property_slider], which is also available in Visual Composer &quot;Property Slider&quot;. Search fields that are used in property search too, should have identical parameters. Mini search doesn\'t show &quot;More&quot; link for features.', 'realty' ),
	        ),

					// Mini Search
					array(
						'id'       		=> 'property-search-mini-fields',
						'type'     		=> 'repeater',
						'full_width' 	=> true,
						'bind_title' 	=> 'property-search-mini-label',
						//'bind_title' 	=> false,
						//'group_values' => false, // Group all fields below within the repeater ID
						//'static'		 	=> 8,
						//'limit' 		=> 8,
						'sortable' 		=> true,
						'fields' 			=> array(
						  array(
						    'id'          => 'property-search-mini-field',
						    'type'        => 'select',
						    'options'     => $merged_fields_search,
						    'placeholder' => esc_html__( 'Search Field Type', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-mini-compare',
						    'type'        => 'select',
						    'options' 		=> array(
						      'equal'    						=> esc_html__( 'Equal', 'realty' ),
									'greater_than'       	=> esc_html__( 'Greater than', 'realty' ),
									'less_than'    				=> esc_html__( 'Less than', 'realty' ),
									'like'    						=> esc_html__( 'Like', 'realty' )
								),
						    'placeholder' => esc_html__( 'Compare As', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-mini-label',
						    'type'        => 'text',
						    'placeholder' => esc_html__( 'Search Label', 'realty' ),
						  ),
						  array(
						    'id'          => 'property-search-mini-parameter',
						    'type'        => 'text',
						    'placeholder' => esc_html__( 'Unique Search Parameter', 'realty' ) . ' (' . esc_html__( 'required', 'realty' ) . ')',
						  )
						)
					),
					array(
		      	'indent' 		=> false,
		        'id'        => 'section-property-search-mini-fields-end',
		        'type'      => 'section',
		      ),

      )
    ) );

		/**
		 * Property Submit
		 *
		 */
    Redux::setSection( $opt_name, array(
			'icon' 		=> 'fa fa-plus',
			'title' 	=> esc_html__( 'Property Submit', 'realty' ),
			'fields' 	=> array(

        array(
          'id'        => 'submit-property-title-label',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Title: Title Field Label', 'realty' ),
          'subtitle'  => esc_html__( 'Property Submit: Main Title Field Label', 'realty' ),
          'default'   => esc_html__( 'Title', 'realty' ),
        ),
        array(
          'id'        => 'submit-property-content-label',
          'type'      => 'text',
          'title'     => esc_html__( 'Property Content: Content Field Label', 'realty' ),
          'subtitle'  => esc_html__( 'Property Submit: Property Description Field Label', 'realty' ),
          'default'   => esc_html__( 'Content', 'realty' ),
        ),
				array(
          'id'        => 'property-submit-notification-email-recipient',
          'type'      => 'text',
          'title'     => esc_html__( 'Send Email Notification To', 'realty' ),
          'subtitle'  => esc_html__( 'Get notified about property submit via email.', 'realty' ),
          'desc'      => sprintf( esc_html__( 'If notification ends up in spam folder, set up %s for the email address entered above.', 'realty' ), '<a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a>' ),
          'validate'  => 'email',
          'default'   => '',
        ),
        array(
          'id'        => 'property-submit-disabled-for-subscriber',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Disable Property Submit For User Role "Subscriber"', 'realty' ),
          'desc'      => esc_html__( 'If checked, only agents and admins are able to submit properties.', 'realty' ),
          'default'   => 0
        ),
				array(
					'id'        => 'property-submission-type',
					'title'     => esc_html__( 'Property Submit Type', 'realty' ),
          'subtitle'  => esc_html__( 'Set how users are allowed to submit properties.', 'realty' ),
					'type'      => 'select',
					'options'   => array(
						'no-paid-submission'  => esc_html__( 'Free / No Paid Submission', 'realty' ),
						'per-listing'         => esc_html__( 'Pay Per Listing', 'realty' ),
						'membership'          => esc_html__( 'Membership', 'realty' )
					),
					'default'   => esc_html__( 'no-paid-submission', 'realty' ),
					'desc'      => esc_html__( 'If you select "Membership" make sure you have created at least one membership package under "Memberships".', 'realty' ),
				),
				array(
          'id'        => 'package-slug',
          'type'      => 'text',
          'title'     => esc_html__( 'Package Slug', 'realty' ),
          'subtitle'  => 'Default "package"',
          'desc'      => esc_html__( 'URL slug for single package URL. I.e. http://yoursite.com/package/package-name/ (where "package" represents the package slug"). Go to "Settings > Permalinks" and click "Save Changes" to apply your new package slug.', 'realty' ),
          'default'   => 'package',
          'required'  => array(
						array( 'property-submission-type', '=', 'membership' ),
					),
        ),
        array(
          'id'        => 'invoice-slug',
          'type'      => 'text',
          'title'     => esc_html__( 'Invoice Slug', 'realty' ),
          'subtitle'  => 'Default "invoice"',
          'desc'      => esc_html__( 'URL slug for single invoice URL. I.e. http://yoursite.com/invoice/invoice-name/ (where "invoice" represents the invoice slug"). Go to "Settings > Permalinks" and click "Save Changes" to apply your new invoice slug.', 'realty' ),
          'default'   => 'invoice',
          'required'  => array(
						array( 'property-submission-type', '=', 'membership' ),
					),
        ),
        array(
          'id'        => 'paypal-enable',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable PayPal payments', 'realty' ),
          'default'   => 0,
          'required'  => array( 'property-submission-type', '!=', 'no-paid-submission' ),
        ),
        array(
          'id'        => 'paypal-alerts-hide',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Hide PayPal payment notifications on submit page', 'realty' ),
          'default'   => 0,
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),
        array(
          'id'        => 'paypal-enable-subscription',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable property subscription via PayPal', 'realty' ),
          'desc'      => esc_html__( 'If checked, recurring payments (subscriptions) will be charged instead of one-time payments.', 'realty' ),
          'default'   => 0,
					'required'  => array(
						array( 'paypal-enable', '=', '1' ),
						array( 'property-submission-type', '=', 'per-listing' ),
					),
        ),
        array(
          'id'        => 'paypal-subscription-recurrence',
          'type'      => 'text',
          'title'     => esc_html__( 'Subscription Recurrences', 'realty' ),
          'desc'      => esc_html__( 'Charge subscription fee every .. (period)', 'realty' ),
          'validate'  => 'numeric',
          'default'   => 12,
					'required'  => array(
						array( 'paypal-enable', '=', '1' ),
						array( 'property-submission-type', '=', 'per-listing' ),
						array( 'paypal-enable-subscription', '=', '1' ),
					),
        ),
        array(
          'id'        => 'paypal-subscription-period',
          'type'      => 'select',
          'title'     => esc_html__( 'Subscription Period', 'realty' ),
          'options'   => array(
          	'D'         => esc_html__( 'Days', 'realty' ),
            'W'         => esc_html__( 'Weeks', 'realty' ),
            'M'         => esc_html__( 'Months', 'realty' ),
            'Y'         => esc_html__( 'Years', 'realty' ),
          ),
          'default'   => 'M',
          'required'  => array(
						array( 'paypal-enable', '=', '1' ),
						array( 'property-submission-type', '=', 'per-listing' ),
						array( 'paypal-enable-subscription', '=', '1' ),
					),
        ),
				array(
          'id'        => 'paypal-amount',
          'type'      => 'text',
          'title'     => esc_html__( 'Amount to pay per property', 'realty' ),
          'subtitle'  => esc_html__( 'Format: 25.00', 'realty' ),
          'default'   => '25.00',
					'required'  => array(
						array( 'paypal-enable', '=', '1' ),
						array( 'property-submission-type', '=', 'per-listing' ),
					),
        ),
        array(
          'id'        => 'paypal-featured-amount',
          'type'      => 'text',
          'title'     => esc_html__( 'Charge additional .. to set property "Featured"', 'realty' ),
          'subtitle'  => esc_html__( 'Format: 10.00', 'realty' ),
          'desc'      => esc_html__( 'To disable "Featured" property option, set the amount to "0"', 'realty' ),
          'default'   => '10.00',
					'required'  => array(
						array( 'paypal-enable', '=', '1' ),
						array( 'property-submission-type', '=', 'per-listing' ),
					),
        ),
        array(
          'id'        => 'paypal-merchant-id',
          'type'      => 'text',
          'title'     => esc_html__( 'PayPal merchant ID or email address', 'realty' ),
          'default'   => '',
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),
        array(
          'id'        => 'paypal-ipn-email-address',
          'type'      => 'text',
          'title'     => esc_html__( 'IPN (Instant Payment Notification) email address', 'realty' ),
          'validate'  => 'email',
          'default'   => '',
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),

        array(
          'id'        => 'paypal-currency-code',
          'type'      => 'text',
          'title'     => esc_html__( 'PayPal currency code', 'realty' ),
          'default'   => 'USD',
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),
        array(
          'id'        => 'paypal-sandbox',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable PayPal Sandbox for Testing', 'realty' ),
          'subtitle'  => esc_html__( 'Disable to process live transactions.', 'realty' ),
          'default'   => 0,
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),
        array(
          'id'        => 'paypal-ssl',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Post PayPal payments over SSL connection', 'realty' ),
          'subtitle'  => esc_html__( 'Recommendation: Enable SSL', 'realty' ),
          'desc'      => esc_html__( 'If disabled, HTTP connection will be used.', 'realty' ),
          'default'   => 1,
          'required'  => array( 'paypal-enable', '=', '1' ),
        ),
				array(
          'id'        => 'enable-stripe-payments',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable Stripe Payments', 'realty' ),
          'default'   => 0,
          'required'  => array( 'property-submission-type', '!=', 'no-paid-submission' ),
        ),
				array(
          'id'        => 'stripe-api-secret-key',
          'type'      => 'text',
          'title'     => esc_html__( 'Stripe API Key', 'realty' ),
          'subtitle'  => esc_html__( 'Enter API live key, If you want to enable testing or sandbox mode then please enter API testing keys', 'realty' ),
          'default'   => '',
					'required'  => array('enable-stripe-payments', '=', '1'),
        ),
				array(
          'id'        => 'stripe-api-publishable-key',
          'type'      => 'text',
          'title'     => esc_html__( 'Stripe Publishable Key', 'realty' ),
          'subtitle'  => esc_html__( 'Enter API live key, If you want to enable testing or sandbox mode then please enter API testing keys', 'realty' ),
          'default'   => '',
					'required'  =>array('enable-stripe-payments', '=', '1'),
        ),
				array(
          'id'        => 'stripe-sandbox-testing',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable Stripe Sandbox for Testing', 'realty' ),
          'subtitle'  => esc_html__( 'Disable to process live transactions. Please enter Stripe testing keys above for testing.', 'realty' ),
          'default'   => 0,
          'required'  => array('enable-stripe-payments', '=', '1'),
        ),
        array(
          'id'        => 'paypal-auto-publish',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Auto publish properties on payment completion', 'realty' ),
          'subtitle'  => esc_html__( 'If disabled you have to publish properties manually.', 'realty' ),
          'default'   => 1,
        ),
			)
		) );

		/**
		 * Agents
		 *
		 */
    Redux::setSection( $opt_name, array(
			'icon' 		=> 'fa fa-user',
			'title' 	=> esc_html__( 'Agents', 'realty' ),
			'fields' 	=> array(
				array(
					'id'        => 'show-agent-email',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Email', 'realty' ),
					'default'   => 1
				),
				array(
					'id'        => 'show-agent-office',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Office Phone', 'realty' ),
					'default'   => 1
				),
				array(
					'id'        => 'show-agent-mobile',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Mobile Phone', 'realty' ),
					'default'   => 1
				),
				array(
					'id'        => 'show-agent-fax',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Fax Number', 'realty' ),
					'default'   => 1
		    ),
		    array(
					'id'        => 'show-agent-website',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Website', 'realty' ),
					'default'   => 1
		    ),
		    array(
					'id'        => 'show-agent-social-networks',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Display Agent Social Networks', 'realty' ),
					'default'   => 1
		    ),
		    array(
					'id'        => 'allow-html-author-bio',
					'type'      => 'checkbox',
					'title'     => esc_html__( 'Allow HTML in Author Bio', 'realty' ),
					'default'   => 0,
					'desc'      => esc_html__( 'Enabling this option comes with potential security risks. Don\'t turn on, if you not 100% your users won\'t harm you.', 'realty' ),
				),
			)
		) );


	  /**
		 * Typography
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Typography', 'realty' ),
      'icon'      => 'fa fa-paragraph',
      'fields'    => array(
        array(
          'id'            => 'typography-header',
          'type'          => 'typography',
          'title'         => esc_html__( 'Typography Header', 'realty' ),
          'google'        => true,
          'font-backup'   => false,
          'font-style'    => false,
          'font-weight'   => false,
          'text-align'		=> false,
          //'subsets'       => false, // Only appears if google is true and subsets not set to false
          'font-size'     => true,
          'line-height'   => false,
          //'word-spacing'  => true,  // Defaults to false
          //'letter-spacing'=> true,  // Defaults to false
          'color'         => false,
          //'preview'       => false, // Disable the previewer
          'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
          'output'        => array( '#header' ), // An array of CSS selectors to apply this font style to dynamically
          'units'         => 'em', // Defaults to px
          'default'       => array(
              'font-family'   => 'Source Sans Pro',
              'google'        => true,
              ),
        ),
        array(
          'id'            => 'typography-headings',
          'type'          => 'typography',
          'title'         => esc_html__( 'Typography Headings', 'realty' ),
          'google'        => true,
          'font-backup'   => false,
          'font-style'    => false,
          'font-weight'   => true,
          'text-align'		=> false,
          //'subsets'       => false, // Only appears if google is true and subsets not set to false
          'font-size'     => true,
          'line-height'   => false,
          //'word-spacing'  => true,  // Defaults to false
          //'letter-spacing'=> true,  // Defaults to false
          'color'         => true,
          //'preview'       => false, // Disable the previewer
          'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
          'output'        => array( 'h1, h2, h3, h4, h5, h6' ), // An array of CSS selectors to apply this font style to dynamically
          'units'         => 'em', // Defaults to px
          //'subtitle'      => esc_html__( '', 'realty' ),
          'default'       => array(
              'font-family'   => 'Source Sans Pro',
              'font-style'    => '400',
              'google'        => true,
              'color' 				=> '#42484b'
              ),
        ),
        array(
          'id'            => 'typography-body',
          'type'          => 'typography',
          'title'         => esc_html__( 'Typography Body', 'realty' ),
          'google'        => true,
          'font-backup'   => false,
          'font-style'    => false,
          'font-weight'   => true,
          'text-align'		=> false,
          //'subsets'       => false, // Only appears if google is true and subsets not set to false
          'font-size'     => true,
          'line-height'   => false,
          //'word-spacing'  => true,  // Defaults to false
          //'letter-spacing'=> true,  // Defaults to false
          'color'         => true,
          //'preview'       => false, // Disable the previewer
          'all_styles'    => false,    // Enable all Google Font style/weight variations to be added to the page
          'output'        => array( 'body' ), // An array of CSS selectors to apply this font style to dynamically
          'units'         => 'em', // Defaults to px
          //'subtitle'      => esc_html__( '', 'realty' ),
          'default'       => array(
              'font-family'   => 'Fira Sans',
              'font-style'    => '400',
              'google'        => true,
              'color' 				=> '#787878'
              ),
        ),
      )
    ) );

    /**
		 * Email -xxx-inactive-for-now
		 *
		 */

    /*
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Email', 'realty' ),
      'icon'      => 'el el-envelope',
      'desc'      => esc_html__( 'By default the theme uses your "Site Title" and "Email Address" from "Settings > General" for all outgoing emails. To override this behavior set a custom "Name" and "Email Address" using the fields below. For proper email delivery we recommend to check the "Enable SMTP" option and enter the correct settings below.', 'realty' ),
      'fields'    => array(
        array(
          'id'        => 'admin-name',
          'type'      => 'text',
          'title'     => esc_html__( 'Name', 'realty' ),
        ),
        array(
          'id'        => 'admin-email',
          'type'      => 'text',
          'validate'  => 'email',
          'title'     => esc_html__( 'Email Address', 'realty' ),
        ),
        array(
          'id'        => 'smtp-on',
          'type'      => 'checkbox',
          'title'     => esc_html__( 'Enable SMTP (optional)', 'realty' ),
          'subtitle'  => esc_html__( 'Please contact your email provider about your SMTP details.', 'realty' ),
          'default'   => 0,
        ),
        array(
          'id'        => 'smtp-host',
          'type'      => 'text',
          'title'     => esc_html__( 'SMTP Host', 'realty' ),
          'subtitle'  => esc_html__( 'e.g. "smpt.gmail.com"', 'realty' ),
					'required'  => array( 'smtp-on', '=', 1 ),
        ),
        array(
          'id'        => 'smtp-username',
          'type'      => 'text',
          'title'     => esc_html__( 'SMTP Username', 'realty' ),
          'subtitle'  => esc_html__( 'e.g. "yourname@yourwebsite.com"', 'realty' ),
          'required'  => array( 'smtp-on', '=', 1 ),
        ),
        array(
          'id'        => 'smtp-password',
          'type'      => 'text',
          'title'     => esc_html__( 'SMTP Password', 'realty' ),
          'required'  => array( 'smtp-on', '=', 1 ),
        ),
        array(
          'id'        => 'smtp-port',
          'type'      => 'text',
          'title'     => esc_html__( 'SMTP Port', 'realty' ),
          'subtitle'  => esc_html__( 'e.g. 25, 465, 587 etc.', 'realty' ),
          'required'  => array( 'smtp-on', '=', 1 ),
        ),
        array(
          'id'        => 'smtp-secure',
          'type'      => 'select',
          'title'     => esc_html__( 'SMTP Secure', 'realty' ),
          'subtitle'  => esc_html__( 'Either SSL, TLS or none.', 'realty' ),
          'options'   => array(
            'none' => '-',
            'ssl'  => 'SSL',
            'tls'  => 'TLS'
			    ),
			    'default'   => 'none',
			    'required'  => array( 'smtp-on', '=', 1 ),
        ),
      )
    ) );
    */

    /**
		 * Format Pricing
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Format Pricing', 'realty' ),
      'icon'      => 'fa fa-dollar',
      'fields'    => array(
        array(
          'id'        => 'price-prefix',
          'type'      => 'text',
          'title'     => esc_html__( 'Price Prefix', 'realty' ),
          'subtitle'  => esc_html__( 'e.g. "from "', 'realty' ),
          'desc'      => esc_html__( 'If filled out, prefix appears on every property price. Can be overwritten for each property.', 'realty' ),
          'default'   => '',
        ),
        array(
          'id'        => 'price-suffix',
          'type'      => 'text',
          'title'     => esc_html__( 'Price Suffix', 'realty' ),
          'subtitle'  => esc_html__( 'e.g. "/month"', 'realty' ),
          'desc'      => esc_html__( 'If filled out, suffix appears on every property price. Can be overwritten for each property.', 'realty' ),
          'default'   => '',
        ),
        array(
          'id'        => 'currency-sign',
          'type'      => 'text',
          'title'     => esc_html__( 'Currency Sign', 'realty' ),
          'subtitle'  => esc_html__( 'Default', 'realty' ) . ': $',
          'default'   => '$',
        ),
        array(
          'id'        => 'currency-sign-position',
          'type'      => 'radio',
          'title'     => esc_html__( 'Currency Sign Position', 'realty' ),
          'options'   => array(
          	'left' 			=> 'Left',
            'right' 		=> 'Right',
          ),
          'default'   => 'left'
				),
				array(
          'id'        => 'price-thousands-separator',
          'type'      => 'text',
          'title'     => esc_html__( 'Thousands Separator', 'realty' ),
          'subtitle'  => esc_html__( 'Default', 'realty' ) . ': ,',
          'default'   => ',',
        ),
        array(
          'id'            => 'price-decimals',
          'type'          => 'spinner',
          'title'         => esc_html__( 'Price Decimals', 'realty' ),
          'subtitle'      => esc_html__( 'Default', 'realty' ) . ': 0',
          'default'       => 0,
          'min'           => 0,
          'step'          => 1,
          'max'           => 2,
          'display_value' => 'label'
				),
      )
    ) );



    /**
		 * Footer
		 *
		 */
    Redux::setSection( $opt_name, array(
      'title'     => esc_html__( 'Footer', 'realty' ),
      'icon'      => 'fa fa-anchor',
      'fields'    => array(
      	array(
          'id'        							=> 'color-footer-background',
          'type'      							=> 'background',
          'output'    							=> array( '#footer' ),
          'title'     							=> esc_html__( 'Footer Top Background Color', 'realty' ),
          'default'   							=> array( 'background-color' => '#333' ),
          'background-repeat'  			=> false,
          'background-attachment'  	=> false,
          'background-position'			=> false,
          'background-image'  			=> false,
          'transparent'			  			=> false,
          'background-size'	  			=> false,
        ),
        array(
          'id'        							=> 'color-footer-bottom-background',
          'type'      							=> 'background',
          'output'    							=> array( '#footer-bottom' ),
          'title'     							=> esc_html__( 'Footer Bottom Background Color', 'realty' ),
          'default'   							=> array( 'background-color' => '' ),
          'background-repeat'  			=> false,
          'background-attachment'  	=> false,
          'background-position'			=> false,
          'background-image'  			=> false,
          'transparent'			  			=> false,
          'background-size'	  			=> false,
        ),
        array(
          'id'       								=> 'color-footer',
			    'type'     								=> 'color',
			    'title'    								=> esc_html__( 'Footer Font Color', 'realty' ),
			    'subtitle' 								=> esc_html__( 'Default', 'realty' ) . ': #fff',
			    'default'  								=> '#fff',
			    'output'    							=> array( '#footer .widget-title, #footer p' ),
			    'validate' 								=> 'color',
			    'transparent'		  				=> false,
				),
      )
    ) );

    if ( file_exists( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) ) {
        $tabs['docs'] = array(
            'icon'    => 'el-icon-book',
            'title'   => esc_html__( 'Documentation', 'realty' ),
            'content' => nl2br( file_get_contents( trailingslashit( dirname( __FILE__ ) ) . 'README.html' ) )
        );
    }

    /*
     * <--- END SECTIONS
     */

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    function compiler_action( $options, $css, $changed_values ) {
        echo '<h1>The compiler hook has run!</h1>';
        echo "<pre>";
        print_r( $changed_values ); // Values that have changed since the last save
        echo "</pre>";
        //print_r($options); //Option values
        //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    function dynamic_section( $sections ) {
        //$sections = array();
        $sections[] = array(
            'title'  => esc_html__( 'Section via hook', 'realty' ),
            'desc'   => esc_html__( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'realty' ),
            'icon'   => 'el el-paper-clip',
            // Leave this as a blank section, no options just some intro text set above.
            'fields' => array()
        );

        return $sections;
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    function change_arguments( $args ) {
        //$args['dev_mode'] = true;

        return $args;
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    function change_defaults( $defaults ) {
        $defaults['str_replace'] = 'Testing filter hook!';

        return $defaults;
    }

    // Remove the demo link and the notice of integrated demo from the redux-framework plugin
    function remove_demo() {

        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }

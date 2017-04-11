<?php
/**
 * Include the TGM_Plugin_Activation class.
 *
 */
require_once get_template_directory() . '/lib/class-tgm-plugin-activation.php';

function tt_register_required_plugins() {

  $plugins = array(

    array(
	    'name'                => 'Advanced Custom Fields Pro',
	    'slug'                => 'advanced-custom-fields-pro',
	    'source'              => get_stylesheet_directory() . '/lib/plugins/advanced-custom-fields-pro.zip',
			'version'             => '5.4.8',
	    'required'            => true,
		),

		array(
			'name' 								=> 'Contact Form 7',
			'slug' 								=> 'contact-form-7',
			'required' 						=> false,
		),

		array(
			'name'               => 'Envato Market',
			'slug'               => 'envato-market',
			'source'             => get_template_directory_uri() . '/lib/plugins/envato-market.zip',
			'required'           => false,
			'version'            => '1.0.0-RC2',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),

		array(
	    'name'                => 'Realty - Data Migration to v2.0 (ONLY REQUIRED TO UPDATE FROM VERSION 1.x TO 2.x)',
	    'slug'                => 'realty-data-migration',
	    'source'              => get_stylesheet_directory() . '/lib/plugins/realty-data-migration.zip',
			'version'             => '2.0',
	    'required'            => false,
		),

		array(
			'name'               => 'Realty Core',
			'slug'               => 'realty-core',
			'source'             => get_template_directory_uri() . '/lib/plugins/realty-core.zip',
			'required'           => true,
			'version'            => '1.0',
		),

		array(
			'name'               => 'Redux Framework',
			'slug'               => 'redux-framework',
			'required'           => true,
			'version'            => '3.6.2',
		),

		array(
			'name'               => 'Templatera',
			'slug'               => 'templatera',
			'source'             => get_template_directory_uri() . '/lib/plugins/templatera.zip',
			'required'           => true,
			'version'            => '1.1.11',
		),

		array(
			'name'               => 'WPBakery Visual Composer',
			'slug'               => 'js_composer',
			'source'             => get_template_directory_uri() . '/lib/plugins/js_composer.zip',
			'required'           => true,
			'version'            => '5.0',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),

  );

  $config = array(
    'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
    'default_path' => '',                      // Default absolute path to pre-packaged plugins.
    'menu'         => 'tgmpa-install-plugins', // Menu slug.
    'has_notices'  => true,                    // Show admin notices or not.
    'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
    'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
    'is_automatic' => false,                   // Automatically activate plugins after installation or not.
    'message'      => '',                      // Message to output right before the plugins table.
    'strings'      => array(
      'page_title'                      => __( 'Install Required Plugins', 'realty' ),
      'menu_title'                      => __( 'Install Plugins', 'realty' ),
      'installing'                      => __( 'Installing Plugin: %s', 'realty' ), // %s = plugin name.
      'oops'                            => __( 'Something went wrong with the plugin API.', 'realty' ),
      'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'realty' ), // %1$s = plugin name(s).
      'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'realty' ), // %1$s = plugin name(s).
      'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'realty' ), // %1$s = plugin name(s).
      'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'realty' ), // %1$s = plugin name(s).
      'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'realty' ), // %1$s = plugin name(s).
      'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'realty' ), // %1$s = plugin name(s).
      'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'realty' ), // %1$s = plugin name(s).
      'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'realty' ), // %1$s = plugin name(s).
      'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'realty' ),
      'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'realty' ),
      'return'                          => __( 'Return to Required Plugins Installer', 'realty' ),
      'plugin_activated'                => __( 'Plugin activated successfully.', 'realty' ),
      'complete'                        => __( 'All plugins installed and activated successfully. %s', 'realty' ), // %s = dashboard link.
      'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
    )
  );

  tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'tt_register_required_plugins' );

<?php
/**
 * Theme Functions
 *
 */
require_once get_template_directory() . '/lib/functions-add-to-favorites.php';
require_once get_template_directory() . '/lib/functions-add-to-follow.php';
require_once get_template_directory() . '/lib/functions-add-to-contact.php';
require_once get_template_directory() . '/lib/functions-advanced-custom-fields.php';
require_once get_template_directory() . '/lib/functions-compare-properties.php';
require_once get_template_directory() . '/lib/functions-email.php';
require_once get_template_directory() . '/lib/functions-forms.php';
require_once get_template_directory() . '/lib/functions-login.php';
require_once get_template_directory() . '/lib/functions-maps.php';
require_once get_template_directory() . '/lib/functions-property.php';
require_once get_template_directory() . '/lib/functions-theme.php';
require_once get_template_directory() . '/lib/functions-theme-options.php';
require_once get_template_directory() . '/lib/functions-theme-setup.php';
require_once get_template_directory() . '/lib/functions-plugin-activation.php';
require_once get_template_directory() . '/lib/functions-property-search.php';
require_once get_template_directory() . '/lib/functions-property-views.php';

require_once get_template_directory() . '/lib/redux/loader.php';
require_once get_template_directory() . '/lib/meta-box/meta-box.php';
require_once get_template_directory() . '/lib/meta-box.php';

/**
 * Realty 3 Update
 * @xxx-do: Deactivate Realty theme and activate it again > Add docs note instead!
 *
 */
if ( ! function_exists( 'realty_3_upgrade' ) ) {
	function realty_3_upgrade() {
		global $realty_theme_option;

		// Stop, if no old logo exists
		if ( empty( $realty_theme_option['logo-menu']['url'] ) ) {
			return;
		}

		$get_realty_theme_options = get_option( 'realty_theme_option' );

		// New Logo (divide dimension by two, as old logo was uploaded retina size by default)
		$get_realty_theme_options['logo']['url'] = $get_realty_theme_options['logo-menu']['url'];
		$get_realty_theme_options['logo']['id'] = $get_realty_theme_options['logo-menu']['id'];

		$logo_height = $get_realty_theme_options['logo-menu']['height'];
		$logo_height = round( $logo_height / 2 );
		$get_realty_theme_options['logo']['height'] = $logo_height;

		$logo_width = $get_realty_theme_options['logo-menu']['width'];
		$logo_width = round( $logo_width / 2 );
		$get_realty_theme_options['logo']['width'] = $logo_width;

		$get_realty_theme_options['logo']['thumbnail'] = $get_realty_theme_options['logo-menu']['thumbnail'];

		// Retina Logo
		$get_realty_theme_options['logo-retina'] = $get_realty_theme_options['logo-menu'];

		update_option( 'realty_theme_option', $get_realty_theme_options );

	}
}
//add_action( 'after_switch_theme', 'realty_3_upgrade' );

/**
 * Realty 3.0 - Admin Notifications
 *
 */
if ( ! function_exists( 'tt_realty_3_admin_notifications' ) ) {
	function tt_realty_3_admin_notifications() {

		global $pagenow, $realty_theme_option;

		if ( ! isset( $realty_theme_option['property-search-results-page'] ) || empty( $realty_theme_option['property-search-results-page'] ) ) {
			$class = 'notice notice-info is-dismissible theme-option-property-search-page-notification';
			$message = esc_html__( 'Required: Please go to "Appearance > Theme Options > Pages" and set the page you want to use as your "Property Search Results Page".', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
		}

		if ( $pagenow == 'post.php' ) {

			$class = 'notice notice-error page-template-agents-notification hide';
			$message = esc_html__( 'Page template "Agents" is no longer supported in Realty 3.0. Please use "Default Template" and appropriate Visual Composer template instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

			$class = 'notice notice-error page-template-contact-notification hide';
			$message = esc_html__( 'Page template "Contact" is no longer supported in Realty 3.0. Please use "Default Template" and appropriate Visual Composer template instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

			$class = 'notice notice-error page-template-intro-notification hide';
			$message = esc_html__( 'Page template "Intro" is no longer supported in Realty 3.0. Please use "Default Template" and appropriate Visual Composer template instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

			$class = 'notice notice-error page-template-subscription-packages-notification hide';
			$message = esc_html__( 'Page template "Membership Packages" is no longer supported in Realty 3.0. Please use "Default Template" and Visual Composer shortcode "Membership Packages" instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

			$class = 'notice notice-error page-template-property-map-notification hide';
			$message = esc_html__( 'Page template "Property Map" is no longer supported in Realty 3.0. Please use "Default Template" and appropriate Visual Composer template instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );


			$class = 'notice notice-error page-template-property-search-notification hide';
			$message = esc_html__( 'Page template "Property Search Results" is no longer supported in Realty 3.0. Please use page template "Property Map Vertical" or appropriate Visual Composer shortcodes instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

			$class = 'notice notice-error page-template-slideshow-notification hide';
			$message = esc_html__( 'Page template "Slideshow" is no longer supported in Realty 3.0. Please use "Default Template" and appropriate Visual Composer template instead.', 'realty' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

		}

	}
}
add_action( 'admin_notices', 'tt_realty_3_admin_notifications' );

/**
 * Redux Admin Scripts
 *
 */
if ( ! function_exists( 'tt_themeOptionsStyles' ) ) {
	function tt_themeOptionsStyles( $hook ) {

		if ( 'themes.php?page=_options' == $hook ) {
	  	return;
	  }

		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/dev-font-awesome.css', array(), '4.6.3', 'all' );
		wp_enqueue_style( 'redux-custom', get_template_directory_uri() . '/lib/redux/style.css', array(), time(), 'all' );
		wp_enqueue_style( 'custom-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), time(), 'all' );

	}
}
add_action( 'admin_enqueue_scripts', 'tt_themeOptionsStyles' );
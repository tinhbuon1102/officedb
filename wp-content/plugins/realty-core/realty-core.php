<?php
/*
Plugin Name: Realty Core
Plugin URI: http://themetrail.com/docs/realty123/
Description: Required plugin for Realty - Real Estate Theme
Author: ThemeTrail
Author URI: http://themetrai1l.com
Version: 1231231.0
*/

define( 'REALTY_CORE_PLUGIN_DIR_URL', plugin_dir_path( __FILE__ ) );

// Deactivate old "Custom Post Types & Taxonomies" and "Memebership Packages" plugins
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// FIRST: Deactivate obsolete old Realty plugins
if ( is_plugin_active('realty-custom-post-types-and-taxonomies/realty-custom-post-types-and-taxonomies.php') ) {
	deactivate_plugins( 'realty-custom-post-types-and-taxonomies/realty-custom-post-types-and-taxonomies.php' );
}

if ( is_plugin_active( 'realty-membership-and-invoices/membership-and-invoices.php' ) ) {
	deactivate_plugins( 'realty-membership-and-invoices/membership-and-invoices.php' );
}

// Custom Post Types & Taxonomies
if ( ! is_plugin_active( 'realty-custom-post-types-and-taxonomies/realty-custom-post-types-and-taxonomies.php' ) && ! is_plugin_active('realty-membership-and-invoices/membership-and-invoices.php') ) {
	require_once REALTY_CORE_PLUGIN_DIR_URL . 'custom-post-types.php';
	require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-membership.php';
	require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-property-payments-paypal.php';
	require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-property-payments-stripe.php';
}

// Functions
require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-scripts.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-membership.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-property-payments-paypal.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'functions-property-payments-stripe.php';

// Shortcodes
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-agents.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-button.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-contact-form.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-google-maps.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-latest-posts.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-membership-packages.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-notification.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-carousel.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-listing.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-map.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-search-form.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-search-form-custom.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-property-slider.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-section-title.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-single-property.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'shortcodes/shortcode-testimonial.php';

// Widgets
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-agent-properties.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-custom-property-search-form.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-featured-agent.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-featured-properties.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-latest-posts.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-membership-packages.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-property-listing.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-property-map.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-property-search-form.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-single-property.php';
require_once REALTY_CORE_PLUGIN_DIR_URL . 'widgets/widget-testimonial.php';
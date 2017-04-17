<?php
/**
 * Theme content width
 *
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140;
}

/**
 * Theme setup
 *
 */
function tt_estate_setup() {

	// Make theme available for translation.
	// Load plugin related strings (Redux, VC etc.) after textdomain!
	load_theme_textdomain( 'realty', get_template_directory() . '/languages' );

	// Visual Composer
	if ( class_exists( 'Vc_Manager' ) ) {
		require_once get_template_directory() . '/lib/visual-composer.php';
	}

	// Redux - Theme options
	if ( class_exists( 'ReduxFramework' ) ) {
		require_once get_template_directory() . '/lib/redux/realty-theme-options.php';
	}

	// This theme styles the visual editor to resemble the theme style.
	// add_editor_style( 'assets/css/editor-styles.css' ); // not necessary due to VC integration

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document <title>.
	add_theme_support('title-tag');

	// Switch default core markup for search form, comment form, comments, gallery and caption to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Enable support for Post Thumbnails and declare custom sizes.
	add_theme_support( 'post-thumbnails' );

	// Custom Image Sizes
	add_image_size( 'thumbnail-1600', 1600, 9999, false ); // @since v1.6.2 > Regenerate thumbnails: https://wordpress.org/plugins/regenerate-thumbnails/
	add_image_size( 'thumbnail-1200', 1200, 9999, false );
	add_image_size( 'thumbnail-16-9', 1200, 675, true );
	add_image_size( 'thumbnail-1200-400', 1200, 400, true );
	add_image_size( 'thumbnail-400-300', 400, 300, true );
	add_image_size( 'property-thumb', 600, 300, true );
	add_image_size( 'property-thumb-long', 600, 600, true );
	add_image_size( 'square-400', 400, 400, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array( 'primary' => esc_html__( 'Main Menu', 'realty' ) ) );

}
add_action( 'after_setup_theme', 'tt_estate_setup' );

/**
 * Admin scripts & styles
 *
 */
function tt_admin_scripts( $hook ) {

	// Absolute Template Path
  $tt_abs_path = array( 'template_url' => get_template_directory_uri() );
  wp_localize_script( 'jquery', 'abspath', $tt_abs_path );

  // Enqueue Only For Posts
	if ( 'post.php' != $hook ) {
  	return;
  }

	wp_enqueue_script( 'realty-admin', get_template_directory_uri() . '/assets/js/admin.js' );
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'tt_admin_scripts' );

/**
 * Theme scripts & styles
 *
 */
function tt_realty_scripts() {

	/**
	 * Styles
	 *
	 */
	wp_deregister_style( 'prettyphoto' ); // = Visual Composer PrettyPhtoto Styles

	global $realty_theme_option;

	if ( is_rtl() || $realty_theme_option['enable-rtl-support'] ) {
		// https://github.com/morteza/bootstrap-rtl
		wp_enqueue_style( 'bootstrap-rtl', '//cdn.rawgit.com/morteza/bootstrap-rtl/master/dist/css/bootstrap-rtl.min.css', null, '3.3.4' );
	}

	if ( WP_DEBUG ) {
		// DEV MODE - All JS is bundled into main.min.css
		wp_enqueue_style( 'chosen', get_template_directory_uri() . '/lib/js/chosen/chosen.css', null, null );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/dev-font-awesome.css', null, null );
		wp_enqueue_style( 'font-realty', get_template_directory_uri() . '/assets/fonts/realty/dev-styles.css', null, null );
		wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/lib/js/magnific-popup/magnific-popup.css', null, null );
		wp_enqueue_style( 'nouislider', get_template_directory_uri() . '/lib/js/nouislider/nouislider.min.css', null, null );
		wp_enqueue_style( 'slick', get_template_directory_uri() . '/lib/js/slick/slick.css', null, null );
	}	else {
		// PRODUCTION - Concatinated & minified main CSS file
		wp_enqueue_style( 'theme-main-min', get_template_directory_uri() . '/assets/css/theme-main.min.css', null, null );
	}

	wp_enqueue_style( 'theme', get_stylesheet_uri(), null, null );
	wp_enqueue_style( 'print', get_template_directory_uri() . '/print.css', null, null, 'print' );

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if ( is_plugin_active( 'dsidxpress/dsidxpress.php' ) ) {
		wp_enqueue_style( 'dsidxpress', get_template_directory_uri() . '/assets/css/idx.css', null, null );
	}

	if ( is_rtl() || $realty_theme_option['enable-rtl-support'] ) {
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/rtl.css', null, null );
	}

	/**
	 * Scripts
	 *
	 */
	if ( isset ( $realty_theme_option['google-maps-api-key'] ) && ! empty( $realty_theme_option['google-maps-api-key'] ) ) {
		$google_maps_api_key = '&key=' . $realty_theme_option['google-maps-api-key'];
	} else {
		$google_maps_api_key = null;
	}

	wp_enqueue_script( 'jquery', null, null, false );
	wp_register_script( 'google-maps-api', "//maps.googleapis.com/maps/api/js?libraries=places$google_maps_api_key", array( 'jquery' ), null, false );

	if ( WP_DEBUG ) {
		// DEV MODE - All JS is bundled into main.min.js
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/lib/js/bootstrap/bootstrap.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'bootstrap-datepicker', get_template_directory_uri() . '/lib/js/bootstrap/bootstrap-datepicker/bootstrap-datepicker.js', array( 'bootstrap' ), null, true );
		wp_enqueue_script( 'chosen', get_template_directory_uri() . '/lib/js/chosen/chosen.jquery.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'classie', get_template_directory_uri() . '/lib/js/classie.js', array(), null );
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/lib/js/jquery.fitvids.js', array(), null );
		wp_enqueue_script( 'form', get_template_directory_uri() . '/lib/js/jquery.form.js', array(), null );
		wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/lib/js/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'intense', get_template_directory_uri() . '/lib/js/intense.min.js', array( 'jquery' ), null );
		wp_enqueue_script( 'jquery-validate', get_template_directory_uri() . '/lib/js/jquery-validate/jquery.validate.min.js', array( 'jquery' ), null );
		wp_enqueue_script( 'additional-methods', get_template_directory_uri() . '/lib/js/jquery-validate/additional-methods.min.js', array( 'jquery-validate' ), null );
		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/lib/js/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), null );
		wp_enqueue_script( 'nouislider', get_template_directory_uri() . '/lib/js/nouislider/nouislider.min.js', array( 'jquery' ), null );
		wp_enqueue_script( 'slick', get_template_directory_uri() . '/lib/js/slick/slick.js', array( 'jquery' ), null );
		wp_enqueue_script( 'store2', get_template_directory_uri() . '/lib/js/store.js', array(), null );
		wp_enqueue_script( 'throttledresize', get_template_directory_uri() . '/lib/js/jquery.throttledresize.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'wnumb', get_template_directory_uri() . '/lib/js/wNumb.min.js', array( 'jquery' ), null );
		if ( ! is_page_template( 'template-property-submit.php' ) ) {
			wp_enqueue_script( 'google-maps-api' );
			wp_enqueue_script( 'google-maps-info-cluster-oms', get_template_directory_uri() . '/lib/js/google-maps/google-maps.min.js', array( 'google-maps-api' ), null );
		}
		wp_enqueue_script( 'theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery', 'bootstrap', 'chosen' ), null );
	}	else {
		// PRODUCTION - Concatinated & minified main JS file
		if ( ! is_page_template( 'template-property-submit.php' ) ) {
			wp_enqueue_script( 'google-maps-api' );
			wp_enqueue_script( 'google-maps-info-cluster-oms', get_template_directory_uri() . '/lib/js/google-maps/google-maps.min.js', array( 'google-maps-api' ), null );
		}
		wp_enqueue_script( 'theme-main-min', get_template_directory_uri() . '/assets/js/theme-main.min.js', array( 'jquery'), null, true );
	}

	if ( $realty_theme_option['datepicker-language'] && $realty_theme_option['datepicker-language'] != 'en' && ! is_page_template( 'template-property-submit.php' ) ) {
		wp_enqueue_script( 'datepicker-'. $realty_theme_option['datepicker-language'], get_template_directory_uri() . '/assets/js/bootstrap-datepicker/locales/bootstrap-datepicker.' . $realty_theme_option['datepicker-language'] . '.js', array('jquery' ), null, false );
	}

	if ( ! $realty_theme_option['property-favorites-disabled'] && $realty_theme_option['property-favorites-temporary'] ) {
		wp_enqueue_script( 'ajax-favorites-temporary', get_template_directory_uri() . '/assets/js/ajax-favorites-temporary.js', array( 'jquery' ), null, true );
	}

	if ( is_page_template( 'template-property-submit.php' ) ) {
		wp_enqueue_script( 'property-submit', get_template_directory_uri() . '/assets/js/theme-property-submit.js', array( 'jquery' ), null, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( isset( $_GET['sign-user'] ) || isset( $_GET['user'] ) ) {
		wp_enqueue_style( 'notify-css', get_template_directory_uri() . '/lib/js/jquery-notification/css/jquery.notifyBar.css', null, null );
		wp_enqueue_script( 'notify-bar', get_template_directory_uri() . '/lib/js/jquery-notification/jquery.notifyBar.js', array( 'jquery' ), null, true );
	}

	wp_localize_script( 'jquery', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'jquery', 'map_options', array( 'map_style' => $realty_theme_option['map-style'] ) );

}
add_action( 'wp_enqueue_scripts', 'tt_realty_scripts' );

/**
 * Register widget areas
 *
 */
if ( ! function_exists( 'tt_widgets_init' ) ) {
	function tt_widgets_init() {


		register_sidebar(
			array(
				'name'           => esc_html__( 'Blog Sidebar', 'realty' ),
				'id'             => 'sidebar_blog',
				'before_widget'  => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'   => '</div></li>',
				'before_title'   => '<h5 class="widget-title">',
				'after_title'    => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Header Sidebar', 'realty' ),
				'id'            => 'sidebar_header',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Property Sidebar', 'realty' ),
				'id'            => 'sidebar_property',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Agent Sidebar', 'realty' ),
				'id'            => 'sidebar_agent',
				'before_widget' => '<li class="widget %2$s">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5><div class="widget-content">'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Page Sidebar', 'realty' ),
				'id'            => 'sidebar_page',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'IDX Sidebar', 'realty' ),
				'id'            => 'sidebar_idx',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top - Column 1', 'realty' ),
				'id'            => 'sidebar_footer_1',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top - Column 2', 'realty' ),
				'id'            => 'sidebar_footer_2',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Top - Column 3', 'realty' ),
				'id'            => 'sidebar_footer_3',
				'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
				'after_widget'  => '</div></li>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Bottom - Left', 'realty' ),
				'id'            => 'sidebar_footer_bottom_left',
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Bottom - Center', 'realty' ),
				'id'            => 'sidebar_footer_bottom_center',
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Bottom - Right', 'realty' ),
				'id'            => 'sidebar_footer_bottom_right',
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5 class="widget-title">',
				'after_title'   => '</h5>'
			)
		);

	}
}
add_action( 'widgets_init', 'tt_widgets_init' );

/**
 * No Title > Add Widget Content Wrapper To Widget
 * http://wordpress.stackexchange.com/questions/74732/adding-a-div-to-wrap-widget-content-after-the-widget-title
 *
 */
if ( ! function_exists( 'tt_check_sidebar_params' ) ) {
	function tt_check_sidebar_params( $params ) {
	  global $wp_registered_widgets;

	  $settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
	  if (is_object( $settings_getter ) ) {

		  $settings = $settings_getter->get_settings();
		  $settings = $settings[ $params[1]['number'] ];

		  if ( $params[0][ 'after_widget' ] == '</div></li>' && isset( $settings[ 'title' ] ) && empty( $settings[ 'title' ] ) ) {
				$params[0][ 'before_widget' ] .= '<div class="empty-title">';
		  }

	  }

	  return $params;
	}
}
add_filter( 'dynamic_sidebar_params', 'tt_check_sidebar_params' );

<?php
include 'define.php';
include 'common_functions.php';

add_action('init', 'realty_init');
function realty_init() {
	if (!session_id()) session_start();
}

add_filter( 'body_class', 'realty_body_class', 10, 2 );
function realty_body_class($classes, $class){
	$classes[] = pll_current_language();
	return $classes;
}

add_action('wp_head', 'realty_wp_head');
function realty_wp_head() {
	$_SESSION['mirrormx_customer_chat']['lang'] = pll_current_language();

	if (is_user_logged_in())
	{
		$user = wp_get_current_user();
		$avatar_id = get_user_meta($user->ID, 'user_avatar', true);
		$_SESSION['mirrormx_customer_chat']['guest'] = array(
			'id' => '',
			'name' => $user->data->display_name,
			'mail' => $user->data->user_email, 'roles' => array(0 => 'GUEST'),
			'last_activity' => date('Y-m-d H:i:s'),
			'image' => $avatar_id ? wp_get_attachment_url($avatar_id) : ''
		);
	}
	else {
		if (isset($_SESSION['mirrormx_customer_chat']) && isset($_SESSION['mirrormx_customer_chat']['guest']))
			unset($_SESSION['mirrormx_customer_chat']['guest']);
	}
}

/**
 * Loads the child theme textdomain.
 */
function tt_child_theme_setup() {
	load_child_theme_textdomain( 'realty', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tt_child_theme_setup' );

add_action('wp_enqueue_scripts', 'icheck_scripts');
function icheck_scripts ()
{
	wp_enqueue_style('icheck_css', get_stylesheet_directory_uri() . '/js/icheck/skins/square/square.css');
	wp_enqueue_script('icheck_js', get_stylesheet_directory_uri() . '/js/icheck/icheck.js', array(
		'jquery'
	));
}

function custom_scripts ()
{
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'));
	wp_enqueue_script('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.js', array('jquery'));
	wp_enqueue_style('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.css');
	
	wp_enqueue_style('validation_engine_css', get_stylesheet_directory_uri() . '/js/validationEngine/validationEngine.jquery.css');
	wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine.js', array('jquery'));
	wp_enqueue_script('validation_engine_lang', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine-'.pll_current_language().'.js', array('jquery'));
	wp_enqueue_script('overlay', get_stylesheet_directory_uri() . '/js/loadingoverlay.js');
	wp_enqueue_script('autokana', get_stylesheet_directory_uri() . '/js/jquery.autoKana.js');
}
add_action('wp_enqueue_scripts', 'custom_scripts');

//ショートコードを使ったphpファイルの呼び出し方法
add_shortcode('myphp', 'my_php_Include');
function my_php_Include($params = array()) {
	extract(shortcode_atts(array('file' => 'default'), $params));
	ob_start();
	include(STYLESHEETPATH . "/template-parts/$file.php");
	return ob_get_clean();
}

add_filter( 'excerpt_length', 'realty_excerpt_length', 99999 );
function realty_excerpt_length( $length ) {
	return 20;
}

function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'login-with-ajax/login-with-ajax.php',
		'profile-builder-pro/index.php'
	);
	$myplugins = $wp_list_table->items;
	foreach ( $myplugins as $key => $val )
	{
		if ( in_array($key, $hidearr) )
		{
			unset($wp_list_table->items[$key]);
		}
	}
}
// add_action('pre_current_active_plugins', 'hide_plugin_order_by_product');

add_action( 'lwa_register_form', 'realty_register_form' );
function realty_register_form(){
	echo do_shortcode('[wppb-register]');
}

add_filter( 'wppb_field_css_class', 'realty_wppb_field_css_class', 10, 4 );
function realty_wppb_field_css_class($class, $field, $error_var)
{
	$aFieldClass = array(
		'Username' => 'col-sm-6 col-xs-12',
		'E-mail' => 'col-sm-6 col-xs-12',
		
		'Name' => 'col-sm-6 col-xs-12',
		'Name Kana' => 'col-sm-6 col-xs-12',
		
		'Company Name' => 'col-sm-6 col-xs-12',
		'Address' => 'col-sm-6 col-xs-12',
		
		'Phone Number' => 'col-sm-6 col-xs-12',
		'Comment' => 'col-sm-6 col-xs-12',
		
		'Password' => 'col-sm-6 col-xs-12',
		'Repeat Password' => 'col-sm-6 col-xs-12',
	);
	
	foreach ($aFieldClass as $fieldName => $fieldClass)
	{
		if (strtolower($fieldName) == strtolower($field['field-title'])) {
			$class .= ' ' . $fieldClass;
			break;
		}
	}
	
	return $class;
}

add_filter( 'wppb_after_form_fields', 'realty_wppb_after_form_fields', 10, 4 );
function realty_wppb_after_form_fields($tag, $formType, $formID)
{
	return $tag . '<div class="clear cls"></div>';
}


add_action( 'register_new_user', 'autoLoginUser', 10, 1 );
function autoLoginUser($user_id){
	$user = get_user_by( 'id', $user_id );
	if( $user && isset($_POST['login-with-ajax']) ) {
		wp_set_password($_POST['passw1'], $user_id);
		wp_set_current_user( $user_id, $user->user_login );
		wp_set_auth_cookie( $user_id );
		
		realty_save_account_details ($user_id);
		
		do_action( 'wp_login', $user->user_login, $user);
	}
}

function realty_save_account_details ($user_id)
{
	if ( class_exists('Profile_Builder_Form_Creator') )
	{
		$formBuilder = new Profile_Builder_Form_Creator(array(
			'form_type' => 'register'
		));
		foreach ( $formBuilder->args['form_fields'] as $field )
		{
			do_action('wppb_save_form_field', $field, $user_id, $_REQUEST, $formBuilder->args['form_type']);
		}
	}
}

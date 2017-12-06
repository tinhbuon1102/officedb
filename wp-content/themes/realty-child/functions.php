<?php
include 'define.php';
include 'common_functions.php';

add_action( 'personal_options', array ( 'T5_Hide_Profile_Bio_Box', 'start' ) );

/*function add_files() {
wp_enqueue_style( 'over-style', get_stylesheet_directory_uri() . '/overwrite.css?'. filemtime( get_stylesheet_directory() . '/overwrite.css') );
}
add_action( 'wp_enqueue_scripts', 'add_files' );*/
function wpb_add_google_fonts() {
 
wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i', false ); 
}
 
add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

function my_custom_styles() {
  // Register my custom stylesheet
  wp_register_style( 'over-style', get_stylesheet_directory_uri() . '/overwrite.css?'. filemtime( get_stylesheet_directory() . '/overwrite.css'), array(), null  );
  // Load my custom stylesheet
  wp_enqueue_style( 'over-style' );
}
add_action( 'wp_enqueue_scripts', 'my_custom_styles', 30 );

/**
 * Captures the part with the biobox in an output buffer and removes it.
 *
 * @author Thomas Scholz, <info@toscho.de>
 *
 */
class T5_Hide_Profile_Bio_Box
{
    /**
     * Called on 'personal_options'.
     *
     * @return void
     */
    public static function start()
    {
        $action = ( IS_PROFILE_PAGE ? 'show' : 'edit' ) . '_user_profile';
        add_action( $action, array ( __CLASS__, 'stop' ) );
        ob_start();
    }

    /**
     * Strips the bio box from the buffered content.
     *
     * @return void
     */
    public static function stop()
    {
        $html = ob_get_contents();
        ob_end_clean();

        // remove the headline
        $headline = __( IS_PROFILE_PAGE ? 'About Yourself' : 'About the user' );
        $html = str_replace( '<h2>' . $headline . '</h2>', '', $html );

        // remove the table row
        $html = preg_replace( '~<tr>\s*<th><label for="description".*</tr>~imsUu', '', $html );
        print $html;
    }
}
/*
 * Let Editors manage users, and run this only once.
 */
function isa_editor_manage_users() {
 
    if ( get_option( 'isa_add_cap_editor_once' ) != 'done' ) {
     
        // let editor manage users
 
        $edit_editor = get_role('editor'); // Get the user role
        $edit_editor->add_cap('edit_users');
        $edit_editor->add_cap('list_users');
        $edit_editor->add_cap('promote_users');
        $edit_editor->add_cap('create_users');
        $edit_editor->add_cap('add_users');
        $edit_editor->add_cap('delete_users');
 
        update_option( 'isa_add_cap_editor_once', 'done' );
    }
 
}
add_action( 'init', 'isa_editor_manage_users' );

/*function hide_personal_options(){
echo "\n" . '<script type="text/javascript">jQuery(document).ready(function($) { $(\'form#your-profile > h3:first\').hide(); $(\'form#your-profile > table:first\').hide(); $(\'form#your-profile\').show(); });</script>' . "\n";
}
add_action('admin_head','hide_personal_options');*/

/*****************************************************
    ユーザー一覧に表示フィールドを追加する
*****************************************************/
 
add_action('manage_users_columns','manage_users_columns');
add_action('manage_users_custom_column','custom_manage_users_custom_column',10,3);
 
function manage_users_columns($column_headers) {
    $column_headers['user_company'] = trans_text('Company Name');
    return $column_headers;
}
 
function custom_manage_users_custom_column($custom_column,$column_name,$user_id) {
    if ( $column_name == 'user_company' ) {
    	$user_info = get_userdata($user_id);
    	
    	${$column_name} = $user_info->$column_name;
    	$custom_column = "\t".${$column_name}."\n";
    }
 
    return $custom_column;
}

add_filter('manage_users_columns','remove_users_columns');
function remove_users_columns($column_headers) {
    if (current_user_can('moderator')) {
      unset($column_headers['posts']);
    }
 
    return $column_headers;
}

add_action('init', 'realty_init');
function realty_init() {
	if (!session_id()) session_start();
	
	$result = add_role(
			'customer',
			__( 'Customer' ),
			array(
				'read'         => true,  // true allows this capability
				'false'   => true,
				'delete_posts' => false, // Use false to explicitly deny
			)
			);
	
	remove_filter( 'user_contactmethods', 'tt_custom_user_contact_methods' );
	date_default_timezone_set(get_option('timezone_string'));
}

function remove_menus () {
    if (!current_user_can('administrator')) { //管理者ではない場合
		global $menu;
    unset($menu[5]);  // 投稿
    unset($menu[10]); // メディア
    unset($menu[15]); // リンク
    unset($menu[20]); // ページ
    unset($menu[25]); // コメント
    unset($menu[60]); // テーマ
    unset($menu[65]); // プラグイン
    //unset($menu[70]); // プロフィール
    unset($menu[75]); // ツール
    unset($menu[80]); // 設定
		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'vc-welcome' );
		remove_menu_page( 'smart-slider-3' );
		remove_menu_page( 'edit.php?post_type=invoice' );
		remove_menu_page( 'edit.php?post_type=package' );
		remove_menu_page( 'edit.php?post_type=owners' );
		remove_menu_page( 'edit.php?post_type=property' );
    }
}
add_action('admin_menu', 'remove_menus');

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

add_action( 'wp_loaded', 'realty_wp_loaded' );
function realty_wp_loaded(){
	// Update verification account
	if (isset($_GET['r']) && $_GET['r'] == 'verify_account' && isset($_GET['key'])  && isset($_GET['user']) && $_GET['user'])
	{
		global $wpdb;
		if (is_user_logged_in())
		{
			wp_redirect( site_url() );
			exit();
		}
	
		$key = $_GET['key'];
		$user_login = $_GET['user'];
	
		$user = check_password_reset_key( $key, $user_login );
	
		if ( ! $user || is_wp_error( $user ) )
		{
			if ( $user && $user->get_error_code() === 'expired_key' )
				wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=expiredkey' ) );
			else
				wp_redirect( site_url( 'wp-login.php?action=lostpassword&error=invalidkey' ) );
		}
		else {
			$wpdb->update($wpdb->users, array('user_activation_key' => ''), array('user_login' => $user_login) );
			wp_cache_delete($user->ID, 'users');
		}
	}
}
register_nav_menus( array(
    'sp_second_menu' => 'sp用下に表示させるメニュー'
) );
register_nav_menus( array(
    'sp_bottom_menu' => 'sp用一番下に表示させるメニュー'
) );
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
   wp_enqueue_script('imagemap_js', get_stylesheet_directory_uri() . '/js/jquery.imagemapster.js', array('jquery'));
	
	wp_enqueue_script('imgfit_js', get_stylesheet_directory_uri() . '/js/jquery.imagefit.min.js', array('jquery'));
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'));
	wp_enqueue_script('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.js', array('jquery'));
	wp_enqueue_style('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.css');
	
	wp_enqueue_style('validation_engine_css', get_stylesheet_directory_uri() . '/js/validationEngine/validationEngine.jquery.css');
	wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine.js', array('jquery'));
	wp_enqueue_script('validation_engine_lang', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine-'.pll_current_language().'.js', array('jquery'));
	wp_enqueue_script('overlay', get_stylesheet_directory_uri() . '/js/loadingoverlay.js');
	wp_enqueue_script('autokana', get_stylesheet_directory_uri() . '/js/jquery.autoKana.js');
	wp_enqueue_style('howcon', get_stylesheet_directory_uri() . '/howcon.css');
	//menu dropdown
	wp_enqueue_script('cutedropcss', get_stylesheet_directory_uri() . '/js/easy-small-dropdown/dist/js/jktCuteDropdown.js', array('jquery'));
	wp_enqueue_style('cutedropjs', get_stylesheet_directory_uri() . '/js/easy-small-dropdown/dist/css/jktCuteDropdown.css');

// 	wp_enqueue_script('typekit', get_stylesheet_directory_uri() . '/js/typekit.js');
	
	if (is_front_page())
	{
		wp_enqueue_script('mousewheel', get_stylesheet_directory_uri() . '/js/jquery.mousewheel.min.js', array( 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-effects-core' ));
		wp_enqueue_script('scroll_bar', get_stylesheet_directory_uri() . '/js/jquery.mCustomScrollbar.js');
		wp_enqueue_style('scroll_bar', get_stylesheet_directory_uri() . '/css/jquery.mCustomScrollbar.css');
	}
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
	return 60;
}

function hide_plugin_order_by_product ()
{
	global $wp_list_table;
	$hidearr = array(
		'login-with-ajax/login-with-ajax.php',
		'profile-builder-pro/index.php',
		'smart-slider-3/smart-slider-3.php',
		'redux-framework/redux-framework.php',
		'realty-core/realty-core.php',
		'wp-fastest-cache-premium/wpFastestCachePremium.php',
// 		'wp-fastest-cache/wpFastestCache.php',
		'regenerate-thumbnails/regenerate-thumbnails.php',
		'disable-users/init.php',
		'theme-my-login/theme-my-login.php'
	);
	$active_plugins = get_option('active_plugins');
	
	$myplugins = $wp_list_table->items;
	foreach ( $myplugins as $key => $val )
	{
		if ( in_array($key, $hidearr) && in_array($key, $active_plugins))
		{
			unset($wp_list_table->items[$key]);
		}
	}
}
add_action('pre_current_active_plugins', 'hide_plugin_order_by_product');

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
		
		global $wpdb;
		
		$hash = wp_hash_password( $_POST['passw1'] );
		$wpdb->update($wpdb->users, array('user_pass' => $hash), array('ID' => $user_id) );
		wp_cache_delete($user_id, 'users');
		
		realty_save_account_details ($user_id);
		
// 		wp_set_current_user( $user_id, $user->user_login );
// 		wp_set_auth_cookie( $user_id );
// 		do_action( 'wp_login', $user->user_login, $user);
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


function realty_custom_jpeg_quality() {
	return 85;
}
add_filter( 'jpeg_quality', 'realty_custom_jpeg_quality', 10 );
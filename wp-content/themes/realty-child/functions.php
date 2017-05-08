<?php
include 'define.php';
include 'common_functions.php';

/**
 * Loads the child theme textdomain.
 */
function tt_child_theme_setup() {
	load_child_theme_textdomain( 'realty', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tt_child_theme_setup' );

function icheck_scripts ()
{
	wp_enqueue_style('icheck_css', get_stylesheet_directory_uri() . '/js/icheck/skins/square/square.css');
	wp_enqueue_script('icheck_js', get_stylesheet_directory_uri() . '/js/icheck/icheck.js', array(
		'jquery'
	));
}
add_action('wp_enqueue_scripts', 'icheck_scripts');

function custom_scripts ()
{
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'));
	wp_enqueue_script('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.js', array('jquery'));
	wp_enqueue_style('typehead', get_stylesheet_directory_uri() . '/js/typehead/jquery.typeahead.css');
	
	wp_enqueue_style('validation_engine_css', get_stylesheet_directory_uri() . '/js/validationEngine/validationEngine.jquery.css');
	wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine.js', array('jquery'));
	wp_enqueue_script('validation_engine_lang', get_stylesheet_directory_uri() . '/js/validationEngine/jquery.validationEngine-'.pll_current_language().'.js', array('jquery'));
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

add_action( 'register_form', 'realty_register_form' );
function realty_register_form(){
	echo do_shortcode('[wppb-register]');
?>
<?php
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
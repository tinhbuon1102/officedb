<?php
define('SITE_DATE_FORMAT', 'Y/m/d');
define('SITE_TIME_FORMAT', 'G:i');

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
	wp_enqueue_script('custom_js', get_stylesheet_directory_uri() . '/js/custom.js', array(
		'jquery'
	));
}
add_action('wp_enqueue_scripts', 'custom_scripts');

//ショートコードを使ったphpファイルの呼び出し方法
function my_php_Include($params = array()) {
	extract(shortcode_atts(array('file' => 'default'), $params));
	ob_start();
	include(STYLESHEETPATH . "/template-parts/$file.php");
	return ob_get_clean();
}
add_shortcode('myphp', 'my_php_Include');

function getAvailablePrefectures() {
	return array('東京都', 'Tokyo');
}

function getAvailableCities() {
	$cities_jp = require(ABSPATH . 'dataAddress/cities_jp.php');
	$cities_en = include(ABSPATH . 'dataAddress/cities_en.php');
	$cities = array();
	foreach ($cities_en as $city_index => $city)
	{
		$cities[$city] = $cities_jp[$city_index];
	}
	return $cities;
}

function getAvaileableTypes(){
	return array('Building' => '建物', 'Floor' => '床');
}

function getAvaileableStatuses(){
	return array('For Rent' => '賃貸用', 'For Sale' => '販売用');
}

function insertTermTranslation($tran_en, $tran_jp, $term_name){
	$term_jp = wp_insert_term( $tran_jp, $term_name);
	$term_en = wp_insert_term( $tran_en, $term_name);
	if (function_exists('pll_save_term_translations'))
	{
		// Remove Trans for term en
		$object_eng = PLL()->model->term->get_object_term( $term_en['term_id'], 'term_translations' );
		$term_trans = unserialize($object_eng->description);
		wp_delete_term($object_eng->term_id, 'term_translations');
		PLL()->model->term->delete_translation( $object_eng->term_id );
		foreach ($term_trans as $object_id) {
			wp_remove_object_terms($object_id, $object_eng->term_id, 'term_translations');
		}
	
		// Add trans EN to group with JA language
		$term_trans = array('ja' => $term_jp['term_id'], 'en' => $term_en['term_id']);
		$object_jp = PLL()->model->term->get_object_term( $term_jp['term_id'], 'term_translations' );
		wp_update_term($object_jp->term_id, 'term_translations', array( 'description' => serialize( $term_trans )));
		wp_set_object_terms($term_en['term_id'], $object_jp->term_id, 'term_translations');
			
		//
		wp_set_object_terms($term_en['term_id'], 38, 'term_language');
		wp_set_object_terms($term_jp['term_id'], 35, 'term_language');
			
	}
}

function pr($data)
{
	echo '<pre>'; print_r($data); echo '</pre>';
}

function jpn_mccompare($a, $b) {
	$fca = ord(substr($a, 0, 1)); $fcb = ord(substr($b, 0, 1));
	if (($fca >= 127 && $fcb >= 127) || ($fca < 127 && $fcb < 127))
		$res = $a > $b ? 1 : -1;
		else
			$res = $a > $b ? -1 : 1;
			return $res;
}

function getDateFormat($hasTime = true)
{
	$format = SITE_DATE_FORMAT;
	$format .= $hasTime ? ' ' . SITE_TIME_FORMAT : '';
	return $format;
}

function getTimeFormat($time)
{
	$format = SITE_TIME_FORMAT;
	return date($format, strtotime($time));
}

function getDateFromStampTime($stmTime)
{
	return date('Y-m-d H:i:s', $stmTime);
}

function renderJapaneseDate($date, $hasTime = false)
{
	return date(getDateFormat($hasTime), strtotime($date));
}

add_action('init', 'realty_theme_init', 10, 3);
function realty_theme_init()
{
	// Import new location
	if (isset($_GET['import_location']))
	{
		importLocationFromPrefecture ();
	}
	
	if (isset($_GET['api_add_image']))
	{
		$image_url = $_GET['api_add_image'];
		$post_id = $_GET['post_id'];
		
		$image = basename($image_url);
		$upload_dir = wp_upload_dir();
		$temp_folder = $upload_dir['basedir'] . '/temp/';
		$filename = $temp_folder . $image;
		
		$attachs = get_image_id($image, $post_id);
		
		if ($attachs && !empty($attachs))
		{
			foreach ($attachs as $attach)
			{
				// Delete old thumbnail
				wp_delete_attachment( $attach->ID, true );
			}
		}

		$image_file = file_get_contents($image_url);
		if ($image_file)
		{
			file_put_contents($filename, file_get_contents($image_url));
			$attach_id = attachImageToProduct($image, $post_id, true);
			die('done attach_id = ' . $attach_id);
		}
		else {
			die ('not ok');
		}
	}
}

function attachImageToProduct ($image, $post_id, $setThumbnail = false)
{
	if ( !function_exists('media_handle_sideload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	}
	
	// $filename should be the path to a file in the upload directory.
	$upload_dir = wp_upload_dir();
	$temp_folder = $upload_dir['basedir'] . '/temp/';
	$filename = $temp_folder . $image;

	$file_array = array();
	$file_array['name'] = basename($filename);
	$file_array['tmp_name'] = $filename;

	$attach_id = media_handle_sideload($file_array, $post_id);
	if (is_wp_error($attach_id))
	{
		return '';
	}

	if ($setThumbnail)
	{
		// Generate the metadata for the attachment, and update the database record.
		set_post_thumbnail( $post_id, $attach_id );
	}
	return $attach_id;
}

function get_image_id($image_url, $post_id) {
	global $wpdb;
	$attachment = $wpdb->get_results($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_parent='%s' AND guid LIKE '%s';",$post_id, '%' . str_replace('.', '%', $image_url) . '%' ));
	return $attachment;
}

function importLocationFromPrefecture () {
	global $wpdb;
	// Delete old location;
	$terms = $wpdb->get_results(
	"SELECT  t.*, tt.* 
			FROM wp_terms AS t  
			INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id 
			WHERE tt.taxonomy IN ('property-location','property-type','property-status') ORDER BY t.name ASC ");
	
	if (!empty($terms))
	{
		// Don't reimport
		//@TODO uncomment
		if (count($terms) >= 50) return;

		
		foreach ($terms as $term)
		{
			wp_delete_term($term->term_id, $term->taxonomy);
			wp_remove_object_terms($term->term_id, $term->term_taxonomy_id, 'term_translations');

			$objectTerms = wp_get_object_terms(array($term->term_id), 'term_translations');
			if (!empty($objectTerms))
			{
				wp_delete_term($objectTerms[0]->term_id, $objectTerms[0]->taxonomy);
				wp_remove_object_terms($objectTerms[0]->term_id, $objectTerms[0]->term_taxonomy_id, 'term_translations');
				
				//
				wp_remove_object_terms($objectTerms[0]->term_id, 38, 'term_language');
				wp_remove_object_terms($objectTerms[0]->term_id, 35, 'term_language');
			}
		}
	}
	
	$cities = getAvailableCities();
	$types = getAvaileableTypes();
	$statuses = getAvaileableStatuses();
	
	foreach ($cities as $en => $jp)
	{
		insertTermTranslation($en, $jp, 'property-location');
	}
	
	foreach ($types as $en => $jp)
	{
		insertTermTranslation($en, $jp, 'property-type');
	}
	
	foreach ($statuses as $en => $jp)
	{
		insertTermTranslation($en, $jp, 'property-status');
	}

	return $cities;
}

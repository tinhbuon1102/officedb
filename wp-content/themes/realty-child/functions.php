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
	return array('東京都');
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

// add_action('init', 'realty_theme_init', 10, 3);
function realty_theme_init()
{
	// Import new location
	// 	importLocationFromPrefecture ();
}
function importLocationFromPrefecture () {
	// Delete old location;
	$terms = get_terms( array(
		'taxonomy' => 'property-location',
		'hide_empty' => false,
	) );

	pr($terms);die;
	if (!empty($terms))
	{
		// Don't reimport
		if (count($terms) >= 50) return;

		foreach ($terms as $term)
		{
			wp_delete_term($term->term_id, $term->taxonomy);
		}
	}

	$dir = ABSPATH . 'dataAddress/zipcode';
	$cities = array();
	$prefectures = getAvailablePrefectures();

	for($i=0; $i<10; $i++)
	{
		$file = $dir . DIRECTORY_SEPARATOR . $i. '.csv';
		if(file_exists($file)){
			$spl = new SplFileObject($file);
			while (!$spl->eof()) {
				$columns = $spl->fgetcsv();
				if(in_array($columns[1], $prefectures)){
					$cities[$columns[2]] = $columns[2];
				}
			}
		}
	}

	setlocale(LC_COLLATE, "jpn");
	usort ($cities, "jpn_mccompare");
	foreach ($cities as $city)
	{
		wp_insert_term( $city, 'property-location');
	}

	return $cities;
}

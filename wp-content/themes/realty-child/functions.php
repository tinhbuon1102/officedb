<?php
include 'define.php';

add_action('wp_head', 'initWordpressHeader');
function initWordpressHeader() {
	
}

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

function importSpecific() {
	global $wpdb;
	$terms = $wpdb->get_results(
			"SELECT  t.*, tt.*
			FROM wp_terms AS t
			INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
			WHERE tt.taxonomy IN ('property-location') AND name='Other' ORDER BY t.name ASC ");

	if (count($terms) == 0)
	{
		$cities = array('Other' => 'その他');
		foreach ($cities as $en => $jp)
		{
			insertTermTranslation($en, $jp, 'property-location');
		}
	}
	pr('existing');die;
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

	pr('done');die;

	return $cities;
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


function getSearchingCities(){
	$cities = array();
	$cities[LANGUAGE_JA] = array (
		1 => '渋谷区',
		2 => '港区',
		3 => '目黒区',
		4 => '中央区',
		5 => '新宿区',
		6 => '千代田区',
		7 => '品川区',
		8 => '台東区',
		9 => '豊島区',
		10 => '中野区',
		11 => 'その他',
	);
	
	$cities[LANGUAGE_EN] =array (
		1 => 'Shibuya ku',
		2 => 'Minato-ku',
		3 => 'Meguro-ku',
		4 => 'Chuo-ku',
		5 => 'Shinjuku-ku',
		6 => 'Chiyoda-ku',
		7 => 'Shinagawa-ku',
		8 => 'Taito Ward',
		9 => 'Toshima-ku',
		10 => 'Nakano ku',
		11 => 'Other',
	);
	return $cities;
}

function getNotSearchingCities($get_by = false){
	$citiesInvi = array();
	$cities = getSearchingCities();
	$cities = array_merge($cities[LANGUAGE_EN], $cities[LANGUAGE_JA]);
	
	$allCities = get_terms('property-location', array(
		'orderby' => 'id',
		'order' => 'ASC',
		'parent' => 0,
		'hide_empty' => false
	));
	
	foreach ($allCities as $city)
	{
		if (!in_array($city->name, $cities))
		{
			$citiesInvi[$city->term_id] = $get_by ? $city->{$get_by} : $city;
		}
	}
	return $citiesInvi;
}

function getOtherCities(){
	return array('other', '%e3%81%9d%e3%81%ae%e4%bb%96');
}

function trans_text($text, $echo = false)
{
	$text = __($text, 'realty');
	if ($echo) echo $text;
	else  return $text;
}

function getSearchingSizes(){
	return array(
		'-30' => '-30',
		'30-50' => '30-50',
		'50-75' => '50-75',
		'75-100' => '75-100',
		'100-150' => '100-150',
		'150-200' => '150-200',
		'300-500' => '300-500',
		'500-600' => '500-600',
		'700-800' => '700-800',
		'800-900' => '800-900',
		'900-1000' => '900-1000',
		'1000-' => '1000-',
	);
}

function insertTermTranslation($tran_en, $tran_jp, $term_name){
	$term_jp = wp_insert_term( $tran_jp, $term_name);
	$term_en = wp_insert_term( $tran_en, $term_name);
	$term_trans = array(LANGUAGE_JA => $term_jp['term_id'], LANGUAGE_EN => $term_en['term_id']);
	
	if (function_exists('pll_save_term_translations'))
	{
		// Make 2 post with same group
		PLL()->model->term->set_language( $term_en['term_id'], 38 );
		PLL()->model->term->set_language( $term_jp['term_id'], 35 );
		
		// Remove Trans for term en
		$object_eng = PLL()->model->term->get_object_term( $term_en['term_id'], 'term_translations' );
		$term_trans = unserialize($object_eng->description);
		wp_delete_term($object_eng->term_id, 'term_translations');
		PLL()->model->term->delete_translation( $object_eng->term_id );
		if (!empty($term_trans))
		{
			foreach ($term_trans as $object_id) {
				wp_remove_object_terms($object_id, $object_eng->term_id, 'term_translations');
			}
		}
	
		// Add trans EN to group with JA language
		$term_trans = array(LANGUAGE_JA => $term_jp['term_id'], LANGUAGE_EN => $term_en['term_id']);
		$object_jp = PLL()->model->term->get_object_term( $term_jp['term_id'], 'term_translations' );
		wp_update_term($object_jp->term_id, 'term_translations', array( 'description' => serialize( $term_trans )));
		wp_set_object_terms($term_en['term_id'], $object_jp->term_id, 'term_translations');
			
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
// 		importLocationFromPrefecture ();
	}
	
	if (isset($_GET['import_specific']))
	{
// 		importSpecific();
	}
	
	if (isset($_GET['api_add_image']))
	{
		$image_url = $_GET['api_add_image'];
		$post_id = $_GET['post_id'];
		$building_id = $_GET['building_id'];
		
		$image = $building_id . basename($image_url);
		$upload_dir = wp_upload_dir();
		$temp_folder = $upload_dir['basedir'] . '/temp/';
		$filename = $temp_folder . $image;
		
		$attach = get_image_id($image, $post_id);
		if ($attach)
		{
			// Generate the metadata for the attachment, and update the database record.
			set_post_thumbnail( $post_id, $attach->ID );
		}
		else {
			$image_file = file_get_contents($image_url);
			if ($image_file)
			{
				file_put_contents($filename, file_get_contents($image_url));
				$attach_id = attachImageToProduct($filename, $post_id, true);
				die('done attach_id = ' . $attach_id);
			}
		}
	}
}

function attachImageToProduct ($filename, $post_id)
{
	if ( !function_exists('media_handle_sideload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	}
	
	$file_array = array();
	$file_array['name'] = basename($filename);
	$file_array['tmp_name'] = $filename;

	$attach_id = media_handle_sideload($file_array, $post_id);
	
	if ($attach_id)
		set_post_thumbnail( $post_id, $attach_id );
	
	return $attach_id;
}

function get_image_id($image_url, $post_id) {
	global $wpdb;
	$aExplode = explode('.', $image_url);
	$attachment = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%s';",'%' . $aExplode[0] . '%' ));
	return $attachment;
}

add_filter('posts_orderby_request', 'realty_posts_orderby_request', 10, 2);
function realty_posts_orderby_request( $orderby, &$query )
{
	global $wpdb;
	if ($query->query['post_type'] == 'property' && $query->query['property_query_listing'] == 1) {
		$orderby = "wp_postmeta.meta_value + 0 " . $query->query['order'];
	}

	return $orderby;
}


add_filter('posts_fields', 'realty_posts_fields', 10, 2);
function realty_posts_fields( $fields, $query )
{
	global $wpdb;
	if ($query->query['post_type'] == 'property' && $query->query['property_query_listing'] == 1) {
		$fields = "$wpdb->posts.ID, wp_postmeta.meta_value  as price";
	}
	return $fields;
}

add_filter('post_limits_request', 'realty_post_limits_request', 10, 2);
function realty_post_limits_request( $limits, &$query )
{
	global $wpdb;
	if ($query->query['post_type'] == 'property' && $query->query['property_query_listing'] == 1) {
		$query->property_limit = $limits;
		$limits = '';
	}
	return $limits;
}


add_filter( 'posts_request', 'realty_posts_request', 10 ,2 );
function realty_posts_request ($request, $query)
{
	global $wpdb;
	if ($query->query['post_type'] == 'property' && $query->query['property_query_listing_request'] == 1)
	{
		$request = str_replace('FROM wp_posts', 'FROM wp_posts INNER JOIN ('.$query->query['custom_inner_join'].') as t1 ON wp_posts.ID = t1.ID ', $request);

	}
	elseif ($query->query['post_type'] == 'property' && $query->query['property_query_listing'] == 1)
	{
		$request = 'SELECT wp_posts.ID, price FROM wp_posts INNER JOIN ('. $request . ') as t ON wp_posts.ID = t.ID GROUP BY wp_posts.pinged ' . $query->property_limit;
	}

	return $request;
}

function buildSearchArgs($search_results_args){
	if (isset($search_results_args['meta_query']))
	{
		foreach ($search_results_args['meta_query'] as $meta_field)
		{
			if ($meta_field['key'] == 'estate_property_size')
			{
				// Don't group floor if has size in Search
				return $search_results_args;
			}
		}
	}
	
	$custom_query_args_group['post_type'] = 'property';
	$custom_query_args_group['posts_per_page'] = -1;
	$custom_query_args_group['order'] = !$_GET[ 'order-by' ] ? 'ASC' : $search_results_args['order'];
	$custom_query_args_group['property_query_listing'] = true;
	$custom_query_args_group['meta_query'] = array(array(
		'key'     => 'estate_property_price',
		'value'   => '',
		'compare' => '!='
	
	));
		
	$custom_query_group = new WP_Query( $custom_query_args_group );
	$search_results_args['property_query_listing_request'] = 1;
	$search_results_args['custom_inner_join'] = $custom_query_group->request;
	
	return $search_results_args;
}

function getStationLines(){
	$lines = array(
		'JR中央線' => 'JR Chuo',
		'JR中央本線' => 'JR Chuo Main',
		'JR五日市線' => 'JR Goto-city',
		'JR京浜東北線' => 'JR Keihin Tohoku',
		'JR京葉線' => 'JR Keiyo',
		'JR八高線' => 'JR Hachimoto',
		'JR南武線' => 'JR Nambu',
		'JR埼京線' => 'JR Saikyo',
		'JR宇都宮線' => 'JR Utsunomiya',
		'JR山手線' => 'JR Yamanote',
		'JR常磐線各駅停車' => 'JR Joban',
		'JR常磐線快速' => 'JR Joban  Rapid',
		'JR東海道本線' => 'JR Tokaido Main',
		'JR横浜線' => 'JR Yokohama',
		'JR横須賀線' => 'JR Yokosuka',
		'JR武蔵野線' => 'JR Musashino',
		'JR湘南新宿ライン' => 'JR Shonan Shinjuku',
		'JR総武線' => 'JR Sobu',
		'JR総武線快速' => 'JR Sobu',
		'JR青梅線' => 'JR Ome',
		'JR高崎線' => 'JR Takasaki',
		'上越新幹線' => 'Joetsu Shinkansen',
		'東北新幹線' => 'Tohoku Shinkansen',
		'北陸新幹線' => 'Hokuriku Shinkansen',
		'JR上野東京ライン' => 'JR Ueno Tokyo',
		'東京メトロ丸ノ内分岐線' => 'Tokyo Metro Marunouchi',
		'東京メトロ丸ノ内線' => 'Tokyo Metro Marunouchi',
		'東京メトロ千代田線' => 'Tokyo Metro Chiyoda',
		'東京メトロ半蔵門線' => 'Tokyo Metro Hanzomon',
		'東京メトロ南北線' => 'Tokyo Metro Namboku',
		'東京メトロ日比谷線' => 'Tokyo Metro Hibiya',
		'東京メトロ有楽町線' => 'Tokyo Metro Yurakucho',
		'東京メトロ東西線' => 'Tokyo Metro Tozai',
		'東京メトロ銀座線' => 'Tokyo Metro Ginza',
		'東京メトロ副都心線' => 'Tokyo Metro Fukutoshin',
		'西武国分寺線' => 'Seibu Kokubu',
		'西武多摩川線' => 'Seibu Tamaegawa',
		'西武多摩湖線' => 'Seibu Tama Lake',
		'西武山口線' => 'Seibu Yamaguchi',
		'西武拝島線' => 'Seibu Hazime',
		'西武新宿線' => 'Seibu Shinjuku',
		'西武有楽町線' => 'Seibu Yurakucho',
		'西武池袋線' => 'Seibu Ikebukuro',
		'西武西武園線' => 'Seibu Seibu',
		'西武豊島線' => 'Seibuti Toshima',
		'東武亀戸線' => 'Tobu Kameido',
		'東武伊勢崎線' => 'Tobu Isesaki',
		'東武大師線' => 'Tobu Daisuke',
		'東武東上本線' => 'Tobu Higashiiboken',
		'東海道新幹線' => 'Tokaido Shinkansen',
		'東急世田谷線' => 'Tokyu Setagaya',
		'東急多摩川線' => 'Tokyu Tamagawa',
		'東急大井町線' => 'Tokyu Oimacho',
		'東急東横線' => 'Tokyu Toyoko',
		'東急池上線' => 'Tokyu Ikegami',
		'東急田園都市線' => 'Tokyu Denentoshi',
		'東急目黒線' => 'Tokyu Meguro',
		'都営三田線' => 'Toei Mita',
		'都営大江戸線' => 'Toei Oedo',
		'都営新宿線' => 'Toei Shinjuku',
		'都営浅草線' => 'Toei Asakusa',
		'都電荒川線' => 'Aritogawa',
		'日暮里・舎人ライナー' => 'Nippori Territor',
		'京王線' => 'Keio',
		'京王新線' => 'Keio new',
		'京王井の頭線' => 'Keio Inokashira',
		'京王相模原線' => 'Keio Sagamihara',
		'京王高尾線' => 'Keio Takaoka',
		'京王動物園線' => 'Keio Zoological',
		'京王競馬場線' => 'Keio Racecourse',
		'京成押上線' => 'Keisei Keeping',
		'京成本線' => 'Keisei Main',
		'京成金町線' => 'Keisei gem',
		'京成成田空港線' => 'Keisei Narita Airport',
		'京浜急行本線' => 'Keihin Kyuko Main',
		'京浜急行空港線' => 'Keihin Kyuko Airport',
		'小田急多摩線' => 'Odakyu Tasa',
		'小田急小田原線' => 'Odakyu Odawara',
		'多摩モノレール' => 'Tama Monorail',
		'東京りんかい線' => 'Tokyo Rinkai',
		'北総鉄道' => 'North total',
		'新交通ゆりかもめ' => 'New Traffic Yurikamome',
		'埼玉高速鉄道' => 'Saitama High-speed',
		'つくばエクスプレス線' => 'Tsukuba Express',
		'東京モノレール羽田線' => 'Tokyo Monorail Haneda',
		'上野モノレール' => 'Ueno Monorail',
		'御岳登山鉄道' => 'Otori mountain',
		'高尾登山電鉄線' => 'Takao Tozan Electric'
	);
	return $lines;
}

function translateStationLine($line)
{
	if (pll_current_language() == LANGUAGE_EN)
	{
		$lines = getStationLines();
		$line = $lines[$line];
	}
	return $line;
}

function translateBuildingValue($field, $building, $floor, $property_id){
	global $wpdb;
	$field = trim($field);
	$current_lang = pll_current_language();
	switch ($field)
	{
		case "area_ping":
			if (!$floor['area_m']) return FIELD_MISSING_VALUE;
				return $current_lang == LANGUAGE_EN ? $floor['area_m'].AREA_M2 : $floor[$field].trans_text('tsubo');
			break;
			
		case 'floor_up_down' :
			if (!$floor['floor_down'] && !$floor['floor_up']){
				return FIELD_MISSING_VALUE;
			}
			else{
				$return = $floor['floor_down'] ? sprintf(trans_text('Floor Down: %s'), $floor['floor_down']) : '';
				$return .= $floor['floor_up'] ? FIELD_MISSING_VALUE . sprintf(trans_text('Floor Up: %s'), $floor['floor_up']) : FIELD_MISSING_VALUE;
				return $return;
			}
			break;
			
		case "rent_unit_price_opt":
			return $floor[$field] == FLOOR_UNIT_OPTION_UNDECIDED ? trans_text('Undecided') : trans_text('Ask');
			break;
			
		case "unit_condo_fee_opt":
			switch ($floor[$field])
			{
				case FLOOR_UNIT_CONDO_FEE_NONE:
					return trans_text('None');
					break;
				case FLOOR_UNIT_CONDO_FEE_UNDECIDED:
					return trans_text('Undecided');
					break;
				case FLOOR_UNIT_CONDO_FEE_ASK:
					return trans_text('Ask');
					break;
				case FLOOR_UNIT_CONDO_FEE_INCLUDED:
					return trans_text('Included');
					break;
			}
			break;
			
		case 'move_in_date' :
			return $floor[$field] ? $floor[$field] : FIELD_MISSING_VALUE;
			break;
			
		case 'built_year' :
			return ($building[$field] && trim($building[$field]) != '-') ? $building[$field] : FIELD_MISSING_VALUE;
			break;
			
		case 'total_floor_space':
			return $building[$field] ? $building[$field] . AREA_M2 : FIELD_MISSING_VALUE;
			break;
			
		case 'earth_quake_res_std' :
			switch ($building[$field])
			{
				case EARTH_QUAKE_OLD_STANDARD:
					return trans_text('Old earthquake resistance standard');
					break;
				case EARTH_QUAKE_REINFOCED:
					return trans_text('Earthquake resistant reinforced');
					break;
				case EARTH_QUAKE_NEW_STANDARD:
					return trans_text('New earthquake resistance standard');
					break;
				case EARTH_QUAKE_ISOLATION_STRUCTURE:
					return trans_text('Base isolation structure');
					break;
				case EARTH_QUAKE_UNKNOW:
					return trans_text('Unknown');
					break;
				case EARTH_QUAKE_DAMPING_STRUCTURE:
					return trans_text('Damping structure');
					break;
				default: 
					return FIELD_MISSING_VALUE;
					break;
			}
			break;
			
		case 'elevator' :
			$elevatorExp = explode('-',$building['elevator']);
			$return = '';
			if($elevatorExp[0] == 1){
				$return .= trans_text('Exists');
				if($elevatorExp[1] != "" || $elevatorExp[2] != "" || $elevatorExp[3] != "" || $elevatorExp[4] != "" || $elevatorExp[5] != "") 
					$return .= '(';
				$return .= isset($elevatorExp[1]) && $elevatorExp[1] != "" ? $elevatorExp[1].trans_text('Group') : "";
				$return .= isset($elevatorExp[2]) && $elevatorExp[2] != "" ? '/'.$elevatorExp[2].trans_text('Human power') : "";
				$return .= isset($elevatorExp[3]) && $elevatorExp[3] != "" ? $elevatorExp[3].trans_text('For basic loading') : "";
				$return .= isset($elevatorExp[4]) && $elevatorExp[4] != "" ? $elevatorExp[4].trans_text('Human power') : "";
				$return .= isset($elevatorExp[5]) && $elevatorExp[5] != "" ? $elevatorExp[5]. trans_text('Group') : "";
				if($elevatorExp[1] != "" || $elevatorExp[2] != "" || $elevatorExp[3] != "" || $elevatorExp[4] != "" || $elevatorExp[5] != "") $return .= ')';
			}else if($elevatorExp[0] == -2){
				$return .= trans_text('Unknown');
			}else if($elevatorExp[0] == 2){
				$return .= trans_text('Not exists');
			}else{
				$return .= FIELD_MISSING_VALUE;
			}
			return $return;
			break;
			
		case 'parking_unit_no' :
			$parkingUnitNo = explode('-', $building['parking_unit_no']);
			if($parkingUnitNo[0] == 1){
				return trans_text('Exists').($parkingUnitNo[1] != "" ? '('.$parkingUnitNo[1].' '.trans_text('Stand').')' : "");
			}else if($parkingUnitNo[0] == 2){
				return trans_text('No exists');
			}else if($parkingUnitNo[0] == 3){
				return trans_text('Exist but unknown unit number');
			}
			break;
			
		case 'opticle_cable' :
			if($building['opticle_cable'] == 0){
				return trans_text('Unknown');
			}else if($building['opticle_cable'] == 1){
				return trans_text('Pull Yes');
			}else if($building['opticle_cable'] == 2){
				return trans_text('Nothing');
			}else{
				return FIELD_MISSING_VALUE;
			}
			break;
			
		case 'std_floor_space' :
			return $building['std_floor_space'] != "" ? $building['std_floor_space'].' ' . trans_text('tsubo') : FIELD_MISSING_VALUE;
			break;
			
		case 'security_id':
			$securityDetails = $wpdb->get_row("SELECT * FROM `security` WHERE security_id=" . (int)$building['security_id']);
			return $securityDetails ? $securityDetails->security_name : FIELD_MISSING_VALUE;
			break;
			
		case 'renewal_data':
			return $building[$field] ? $building[$field] : FIELD_MISSING_VALUE;
			break;
			
		case 'avg_neighbor_fee':
			if (!$building['avg_neighbor_fee_min'] && !$building['avg_neighbor_fee_max']){
				$return = FIELD_MISSING_VALUE;
			}
			else{
				$return = $building['avg_neighbor_fee_min'] ? sprintf(trans_text('¥%s'), $building['avg_neighbor_fee_min']) : '';
				$return .= $building['avg_neighbor_fee_max'] ? FIELD_MISSING_VALUE . sprintf(trans_text('¥%s'), $building['avg_neighbor_fee_max']) : FIELD_MISSING_VALUE;
			}
			return $return;
			break;
		case 'type_of_use' :
			$floorTypeUseArray = array();
			$typeOfUse = array();
			$floorTypeUse = $floor['type_of_use'];
			$floorTypeUseArray = explode(',',$floorTypeUse);
			$opt1Val = trans_text('×事');
			if ( in_array('1', $floorTypeUseArray) )
			{
				$opt1Val = trans_text('○事');
			}
			$typeOfUse[] = $opt1Val;
			$opt1Val = trans_text('×店');
			if ( in_array('2', $floorTypeUseArray) )
			{
				$opt1Val = trans_text('○店');
			}
			$typeOfUse[] = $opt1Val;
			$opt2Val = trans_text('×倉');
			if ( in_array('5', $floorTypeUseArray) )
			{
				$opt2Val = trans_text('○倉');
			}
			$typeOfUse[] = $opt2Val;
			$opt3Val = trans_text('×他');
			$otherArray = array();
			$useOfType = $wpdb->get_results("SELECT * FROM `use_types` WHERE is_active = 1");
			foreach ( $useOfType as $uType )
			{
				$uType = (array)$uType;
				if ( $uType['user_type_id'] == '1' || $uType['user_type_id'] == '2' || $uType['user_type_id'] == '5' )
				{
					continue;
				}
				else
				{
					$otherArray[] = $uType['user_type_id'];
				}
			}
			$intersect = array_intersect($floorTypeUseArray, $otherArray);
			
			if ( ! empty($intersect) )
			{
				$opt3Val = trans_text('○他');
			}
			$typeOfUse[] = $opt3Val;
			return implode(' | ', $typeOfUse);
			break;
	}
}

function renderPrice($price) {
	$price = str_replace(',', '', $price);
	if ($price)
	{
		$price = number_format($price, 0);
		return '<span class="price_currency">¥</span><span class="price">'.$price.'</span>';
	}
	else {
		return FIELD_MISSING_VALUE;
	}
}
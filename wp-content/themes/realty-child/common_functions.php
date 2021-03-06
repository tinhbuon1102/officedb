<?php
function pr($data)
{
	echo '<pre>'; print_r($data); echo '</pre>';
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

function isEnglish(){
	return pll_current_language() == LANGUAGE_EN;
}

function getSearchUrl()
{
	return get_option('siteurl') . '/' . (isEnglish() ? 'search-properties-2' : 'search-properties');
}

function getLanguageID() {
	return isEnglish() ? SITE_LANGUAGE_EN : SITE_LANGUAGE_JA;
}

function formatNumber($number)
{
	if (strpos($number, ',') !== false && strpos($number, '.') !== false)
	{
		$number = str_replace(',', '', $number);
	}
	elseif (strpos($number, ',') !== false)
	{
		$number = str_replace(',', '', $number);
	}


	$aNumber = explode('.', $number);
	if (isset($aNumber[1]))
		$decimal = strlen($aNumber[1]) >= 2 ? 2 : strlen($aNumber[1]);
		else
			$decimal = 0;

			$number = $number ? $number : 0;

			return number_format($number, $decimal);
}

function updateFloorKana(){
	global $wpdb;
	// Custom Price
	$offset = (int)$_GET['offset'];
	$limit = $_GET['limit'] ? (int)$_GET['limit'] : 100;

	$buildings = $wpdb->get_results("SELECT  * FROM building LIMIT $offset, $limit");
	foreach ($buildings as $building)
	{
		$buildingContents = $wpdb->get_results("SELECT  * FROM wp_postmeta WHERE meta_key='".BUILDING_TYPE_CONTENT."' AND meta_value LIKE '%building_id\";s:". strlen($building->building_id) .":\"". $building->building_id ."\"%'");
		if (!empty($buildingContents))
		{
			foreach ($buildingContents as $buildingContent)
			{
				update_post_meta($buildingContent->post_id, 'estate_property_kana_name', $building->name_kana);

				$search_text = $building->name . ' ' . $building->name_en;
				$search_text .= $building->name_kana . ' ' . $building->search_keywords_ja . ' ' . $building->search_keywords_en;
				update_post_meta($buildingContent->post_id, 'estate_property_search', $search_text);
			}
		}
	}
	die('updateFloorPrice');
}

function updateFloorPrice(){
	global $wpdb;
	// Custom Price
	$offset = (int)$_GET['offset'];
	$limit = $_GET['limit'] ? (int)$_GET['limit'] : 100;

	$floors = $wpdb->get_results("SELECT  * FROM floor LIMIT $offset, $limit");

	foreach ($floors as $floor)
	{
		$floorContents = $wpdb->get_results("SELECT  * FROM wp_postmeta WHERE meta_key='".FLOOR_TYPE_CONTENT."' AND meta_value LIKE '%floor_id\";s:". strlen($floor->floor_id) .":\"". $floor->floor_id ."\"%'");
		if (!empty($floorContents))
		{
			foreach ($floorContents as $floorContent)
			{
				update_post_meta($floorContent->post_id, 'estate_property_price', (float)str_replace(',', '', $floor->rent_unit_price));
				update_post_meta($floorContent->post_id, 'estate_property_floor_down', $floor->floor_down);
				update_post_meta($floorContent->post_id, 'estate_property_floor_up', $floor->floor_up);
			}
		}
	}
	die('updateFloorPrice');
}

function updateStation() {
	global $wpdb;
	// Custom Price
	$offset = (int)$_GET['offset'];
	$limit = $_GET['limit'] ? (int)$_GET['limit'] : 500;

	$stations = $wpdb->get_results("SELECT  * FROM building_station GROUP BY building_id ORDER BY time ASC LIMIT $offset, $limit ");
	foreach ($stations as $station)
	{
		$buildingContents = $wpdb->get_results("SELECT  * FROM wp_postmeta WHERE meta_key='jpdb_floor_building_id_en' AND meta_value=" . (int)$station->building_id . ' LIMIT 1');
		if (!empty($buildingContents))
		{
			foreach ($buildingContents as $buildingContent)
			{
				update_post_meta($buildingContent->post_id, 'estate_property_station', $station->name_en);
			}
		}
	}
	die('updateStation');
}

function changeNewsTitle(){
	$recent_posts = wp_get_recent_posts(array(
		'post_type' => 'news',
		'posts_per_page' => -1,
		'orderby' => array( 'post_modified' => 'DESC' )
	));
	foreach ($recent_posts as $recent_post)
	{
		$my_post = array(
			'ID'           => $recent_post['ID'],
			'post_title'   => str_replace('has new vacancy', '', str_replace('に新しい空室が出ました', '', str_replace('。', '', $recent_post['post_title']))),
		);
		wp_update_post( $my_post );
	}

	$recent_posts = wp_get_recent_posts(array(
		'post_type' => 'news',
		'posts_per_page' => -1,
		'orderby' => array( 'post_modified' => 'DESC' )
	));
	foreach ($recent_posts as $recent_post)
	{
		$my_post = array(
			'ID'           => $recent_post['ID'],
			'post_title'   => str_replace('has new vacancy', '', str_replace('に新しい空室が出ました', '', str_replace('。', '', $recent_post['post_title']))),
		);
		wp_update_post( $my_post );
	}
}

function importLocationFromPrefecture () {
	global $wpdb;

	$terms = $wpdb->get_results('SELECT * FROM wp_term_taxonomy where taxonomy = "term_translations" AND description LIKE "%a:1:{s:2%" AND term_id > 2585', ARRAY_A);
	foreach ($terms as $term)
	{
		wp_delete_term($term['term_id'], $term['taxonomy']);
	}


	// Delete old location;
	$terms = $wpdb->get_results(
			"SELECT  t.*, tt.*
			FROM wp_terms AS t
			INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
			WHERE tt.taxonomy IN ('property-location','property-type','property-status','category') ORDER BY t.name ASC ");

	$cities = getAvailableCities();
	$types = getAvaileableTypes();
	$statuses = getAvaileableStatuses();
	$news = getAvaileableNews();

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

	foreach ($news as $en => $jp)
	{
		insertTermTranslation($en, $jp, 'category');
	}

	pr('done');die;

	return $cities;
}

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
function getAvaileableNews(){
	return array('Added Info' => '追加情報', 'Vacancy Info' => '空室情報');
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
		1 => 'Shibuya-ku',
		2 => 'Minato-ku',
		3 => 'Meguro-ku',
		4 => 'Chuo-ku',
		5 => 'Shinjuku-ku',
		6 => 'Chiyoda-ku',
		7 => 'Shinagawa-ku',
		8 => 'Taito Ward',
		9 => 'Toshima-ku',
		10 => 'Nakano-ku',
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
		'200-300' => '200-300',
		'300-500' => '300-500',
		'500-600' => '500-600',
		'700-800' => '700-800',
		'800-900' => '800-900',
		'900-1000' => '900-1000',
		'1000-' => '1000-',
	);
}

function insertTermTranslation($tran_en, $tran_jp, $term_name){
	$term_jp = (array)get_term_by('name', $tran_jp, $term_name);
	$term_en = (array)get_term_by('name', $tran_en, $term_name);
	if (!$term_en || !isset($term_en['term_id']))
	{
		$term_en = (array)get_term_by('slug', str_replace(' ', '-', strtolower($tran_en)), $term_name);
	}

	if (!$term_jp || !isset($term_jp['term_id']))
	{
		$term_jp = (array)wp_insert_term( $tran_jp, $term_name);
		$term_en = (array)wp_insert_term( $tran_en, $term_name);
	}

	$term_trans = array(LANGUAGE_JA => $term_jp['term_id'], LANGUAGE_EN => $term_en['term_id']);

	if (function_exists('pll_save_term_translations'))
	{
		// Make 2 post with same group
		PLL()->model->term->save_translations($term_en['term_id'], $term_trans);
		PLL()->model->term->save_translations($term_jp['term_id'], $term_trans);
	}
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

function writeRealtyLog($data)
{
	$fp = fopen(ABSPATH . '/log/log.txt', 'a+');
	fwrite($fp, var_export($data, true) . PHP_EOL);
	fclose($fp);
}
add_action('wp_loaded', 'realty_theme_init', 10, 3);
function realty_theme_init()
{
	// Import new location
	if (isset($_GET['import_location']))
	{
		importLocationFromPrefecture ();
	}

	if (isset($_GET['update_floor_price']))
	{
		updateFloorPrice();
	}

	if (isset($_GET['update_kana']))
	{
		updateFloorKana();
	}

	if (isset($_GET['update_station']))
	{
		updateStation();
	}

	if (isset($_GET['change_news_title']))
	{
		changeNewsTitle();
	}

	if (isset($_GET['api_add_image']))
	{
		$image_base_url = $_GET['image_base_url'];
		foreach ($_GET['post_id'] as $index_get => $post_id)
		{
			$image_url = $image_base_url . $_GET['api_add_image'][$index_get];
			$building_id = $_GET['building_id'][$index_get];
			
			$image = $building_id . basename($image_url);
			$image = substr($image, 0, strlen($image) - 4) . '.jpg';
			
			$upload_dir = wp_upload_dir();
			$temp_folder = $upload_dir['basedir'] . '/temp/';
			$filename = $temp_folder . $image;
			
			$aPostId[] = pll()->model->post->get( $post_id, 'ja' );
			$aPostId[] = pll()->model->post->get( $post_id, 'en' );
			
			foreach ($aPostId as $post_id)
			{
				if (!$post_id) continue;
					
				$attach = get_image_id($image, $post_id);
				if ($attach)
				{
					// Generate the metadata for the attachment, and update the database record.
					set_post_thumbnail( $post_id, $attach->ID );
				}
				else {
					if (!isset($image_file))
					{
						$image_file = file_get_contents($image_url);
					}
			
					if ($image_file)
					{
						realty_compress_image($image_url, $filename);
						$attach_id = attachImageToProduct($filename, $post_id, true);
					}
				}
			}
		}
		
		if (class_exists("WpFastestCache"))
		{
			$fastestCache = new WpFastestCache();
			$fastestCache->deleteCache();
		}
		die('done');
	}
	
	if (isset($_GET['check_existing_floor']))
	{
		global $wpdb;
		$checkSql = 'SELECT pt.*, f.floor_id FROM wp_postmeta pt
					INNER JOIN wp_posts p ON p.ID = pt.post_id
					LEFT JOIN floor f ON pt.meta_value = f.floor_id
					WHERE 
						p.post_type = "publish"
						AND pt.meta_key = "jpdb_floor_id"
						AND f.floor_id is NULL;';
		$aNoExistings = $wpdb->get_results($checkSql);
		if ($aNoExistings){
			foreach ($aNoExistings as $aNoExisting)
			{
				wp_trash_post($aNoExisting->post_id);
			}
		}
	}
	
	if (isset($_GET['remove_room_number']))
	{
		global $wpdb;
		$roomnumber_sql = "SELECT p.post_title, p.guid, f.roomname, p.ID FROM wp_posts p 
				INNER JOIN wp_postmeta pt ON p.ID = pt.post_id
				LEFT JOIN floor f ON f.floor_id = pt.`meta_value`
				WHERE 
				p.post_type = 'property'
				AND pt.meta_key = 'jpdb_floor_id'
				AND f.roomname != ''
				GROUP BY p.ID";
		$properties = $wpdb->get_results($roomnumber_sql);
		foreach ($properties as $property)
		{
			$property->post_title = trim(str_replace($property->roomname, '', $property->post_title));
			// Update post title
			$sql = " UPDATE $wpdb->posts SET post_title = '" . $property->post_title . "' WHERE ID = $property->ID";
			$wpdb->query($sql);
			
			$post_title = regenerate_post_clear_diacritics($property->post_title);
			$guid = home_url() . '/' . sanitize_title_with_dashes($post_title);
			
			// Update post_name and guid
			$sql = "
					UPDATE $wpdb->posts
					SET post_name = '" . sanitize_title_with_dashes($post_title) . "',
					guid = '" . $guid . "'
					WHERE ID = $property->ID";
			$wpdb->query($sql);
		}
		die('done remove_room_number');
	}
}

function regenerate_post_clear_diacritics($str) {
	$table = array(
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
		'Æ' => 'A', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
		'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'Ç' => 'C', 'ç' => 'c',
		'Ď' => 'D', 'ď' => 'd',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ě' => 'E', 'è' => 'e',
		'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ě' => 'e',
		'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'Ñ' => 'N', 'ñ' => 'n',
		'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
		'ð' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
		'Ŕ' => 'R', 'Ř' => 'R', 'Ŕ' => 'R', 'ŕ' => 'r', 'ř' => 'r',
		'Š' => 'S', 'š' => 's', 'Ś' => 'S', 'ś' => 's',
		'Ť' => 'T', 'ť' => 't',
		'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ù' => 'u', 'ú' => 'u',
		'û' => 'u', 'ü' => 'u',
		'Ý' => 'Y', 'ÿ' => 'y', 'ý' => 'y', 'ý' => 'y',
		'Ž' => 'Z', 'ž' => 'z', 'Ź' => 'Z', 'ź' => 'z',
		'Đ' => 'Dj', 'đ' => 'dj', 'Þ' => 'B', 'ß' => 's', 'þ' => 'b',
	);

	return  strtr($str, $table);
}

function realty_compress_image($source_url, $destination_url, $quality = 85) {
	$info = getimagesize($source_url);

	if ($info['mime'] == 'image/jpeg')
		$image = imagecreatefromjpeg($source_url);

		elseif ($info['mime'] == 'image/gif')
		$image = imagecreatefromgif($source_url);

		elseif ($info['mime'] == 'image/png')
		{
			$image = imagecreatefrompng($source_url);
			$quality = 70;
		}

		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
}

add_action('get_header', 'realty_theme_init_header', 10, 3);
function realty_theme_init_header()
{
	if (isset($_GET['api_send_follow_email']))
	{
		$floor_id = (int)$_GET['api_send_follow_email'];
		$lang = $_GET['lang'];

		// Get property by news
		$new_args = array(
			'post_type' => 'property',
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => FLOOR_TYPE,
					'value' => $floor_id,
				),
				array(
					'key' => FLOOR_TYPE . '_' . $lang,
					'value' => $floor_id,
				)
			)
		);
		$the_news_query = new WP_Query( $new_args );
		if ( $the_news_query->have_posts() )
		{
			while ( $the_news_query->have_posts() ) {
				$the_news_query->the_post();
				global $post;
				tt_property_updated_send_email( get_the_ID() );
			}

		}
		echo json_encode(array('success' => 1)); die;
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
	$attachment = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_parent=".$post_id." guid LIKE '%s';",'%' . $aExplode[0] . '%' ));
	return $attachment;
}

add_action( 'wp_insert_post', 'realty_insert_post', 10, 3 );
function realty_insert_post($post_ID, $post, $update)
{
	if (isset($query->query['post_type']) && $post->post_type == 'news' && !$post->pinged)
	{
		$my_post = array(
			'ID'           => $post_ID,
			'pinged' => $post_ID,
		);
		// Update the post into the database
		wp_update_post( $my_post );
	}
}


add_filter('posts_orderby_request', 'realty_posts_orderby_request', 10, 2);
function realty_posts_orderby_request( $orderby, &$query )
{
	global $wpdb;
	if (isset($query->query['is_similar']) && $query->query['is_similar'])
	{
		$orderby = "ABS (CAST(wp_postmeta.meta_value AS CHAR) - ".$query->query['similar_size'].") " . $query->query['orderby']['size_clause'];
	}

	if (isset($query->query['post_type']) && $query->query['post_type'] == 'news') {
		$orderby = "post_modified DESC";
	}
	return $orderby;
}


add_filter( 'posts_request', 'realty_posts_request', 10 ,2 );
function realty_posts_request ($request, $query)
{
	global $wpdb;
	if (isset($query->query['post_type']) && $query->query['post_type'] == 'property' && isset($query->query['property_query_listing_request']) && $query->query['property_query_listing_request'] == 1)
	{
		if ($_GET['action'] != 'realty_get_floors')
		{
			$request = str_replace('GROUP BY wp_posts.ID', 'GROUP BY wp_posts.pinged', $request);
		}

		if (isset($query->query['meta_query']) &&
				isset($query->query['meta_query'][0]) &&
				isset($query->query['meta_query'][0][0]) &&
				$query->query['meta_query'][0][0]['key'] == 'estate_property_station' &&
				isset($query->query['meta_query'][0][1]) &&
				$query->query['meta_query'][0][1]['key'] == 'estate_property_google_maps')
		{
			
			if ($query->query['post_type'] == 'property')
			{
				$text_search = "( mt1.meta_key = 'estate_property_station' AND mt1.meta_value LIKE '%".$query->query['s']."%' )
    OR
    ( mt1.meta_key = 'estate_property_google_maps' AND mt1.meta_value LIKE '%".$query->query['s']."%' )";
			}
			$request = str_replace(PHP_EOL, ' ', $request);
			$request = preg_replace('!\s+!', ' ', $request);
				
			$text_search = str_replace(PHP_EOL, ' ', $text_search);
			$text_search = preg_replace('!\s+!', ' ', $text_search);
				
			
			$text_filter = "wp_posts.post_title LIKE '%".$query->query['s']."%'";
			$text_keyword = "(wp_postmeta.meta_key = 'estate_property_search' AND wp_postmeta.meta_value LIKE '%".$query->query['s']."%')";
			$request = str_replace($text_search, ' 1=1 ', $request);
			$request = str_replace($text_filter, '('.$text_filter . ' OR ' . $text_keyword . ' OR ' . $text_search.')', $request);
		}
		
		if ($query->query['post_type'] == 'property')
		{
			$query->query['paged'] = $query->query['paged'] <= 0 ? 1 : $query->query['paged'] ; 
			$limit = 'LIMIT ' . ($query->query['paged'] - 1) * $query->query['posts_per_page'] . ', ' . $query->query['posts_per_page'];
			$request = str_replace('SQL_CALC_FOUND_ROWS', '', $request);
			$request = str_replace('GROUP BY wp_posts.pinged', 'GROUP BY wp_posts.ID', $request);
			$request = str_replace($limit, '', $request);
			$request = "SELECT SQL_CALC_FOUND_ROWS * FROM ($request) as t GROUP BY pinged ORDER BY post_date DESC $limit";
		}
	}
	elseif (isset($query->query['post_type']) && $query->query['post_type'] == 'news')
	{
		$request = str_replace('GROUP BY wp_posts.ID', 'GROUP BY wp_posts.pinged', $request);
	}

	if (isset($query->query['post_type']) && $query->query['post_type'] == 'property' && isset($query->query['s']) && $query->query['s'])
	{
		$text_search = "OR (wp_posts.post_excerpt LIKE '%".$query->query['s']."%') OR (wp_posts.post_content LIKE '%".$query->query['s']."%')";
		$request = str_replace($text_search, '', $request);
	}

	return $request;
}

function buildSearchArgs($search_results_args){
	if (isset($search_results_args['meta_query']))
	{
		foreach ($search_results_args['meta_query'] as $meta_field)
		{
			if ((isset($meta_field['key']) && $meta_field['key'] == 'estate_property_size') && strpos($_SERVER['REQUEST_URI'], 'search-properties') === false && strpos($_SERVER['REQUEST_URI'], 'property-core-section-listing') === false)
			{
				// Don't group floor if has size in Search
				return $search_results_args;
			}
		}
	}

	if (isset($search_results_args['is_single']) && $search_results_args['is_single'])
	{
		return $search_results_args;
	}

	$search_results_args['property_query_listing_request'] = 1;

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

function explodeRangeValue($string, $subfix = ''){
	//total_rent_space_unit
	$aString = array();

	$string = str_replace(' ', '', $string);
	preg_match('/[~|-|～]/u', $string, $matches);

	if (count($matches))
	{
		$aString = explode($matches[0], $string);
	}else {
		$matches[0] = '';
		$aString[] = $string;
	}

	foreach ($aString as &$subData)
	{
		$subData = formatNumber($subData) . $subfix;
	}
	return implode($matches[0], $aString);
}

function convertDateFormat($date)
{
	$date = str_replace('Sep', 'Sept', $date);
	$date = str_replace('Jun', 'June', $date);
	$date = str_replace('Jul', 'July', $date);
	return $date;
}

function getBuilding($single_property_id) {
	global $wpdb;
	$building_id = get_post_meta($single_property_id, FLOOR_BUILDING_TYPE, true);
	$building = (array)$wpdb->get_row("SELECT * FROM building WHERE building_id=".(int)$building_id);
	$stations = $wpdb->get_results("SELECT * FROM building_station WHERE building_id=".(int)$building_id, ARRAY_A);
	$building['stations'] = $stations;
	return $building;
}

function getBuildingByBuildingID($building_id)
{
	global $wpdb;
	$building = (array)$wpdb->get_row("SELECT * FROM building WHERE building_id=".(int)$building_id);
	$stations = $wpdb->get_results("SELECT * FROM building_station WHERE building_id=".(int)$building_id, ARRAY_A);
	$building['stations'] = $stations;
	return $building;
}

function getFloor($single_property_id)
{
	global $wpdb;
	$floor_id = get_post_meta($single_property_id, FLOOR_TYPE, true);
	$floor = (array)$wpdb->get_row("SELECT * FROM floor WHERE floor_id=".(int)$floor_id);
	return $floor;
}

function translateBuildingValue($field, $building, $floor, $property_id){
	global $wpdb;
	$field = trim($field);
	$current_lang = pll_current_language();
	switch ($field)
	{
		case "area_ping":
			if (!$floor['area_m']) return FIELD_MISSING_VALUE;
			if(isEnglish()) 
				return formatNumber($floor['area_m']).AREA_M2 . ' | ' . formatNumber($floor[$field]).trans_text('tsubo');
			else 
				return formatNumber($floor[$field]).trans_text('tsubo');
			break;

		case 'floor_up_down' :
			$floor['floor_down'] = str_replace(' ', '', $floor['floor_down']);
			$floor['floor_up'] = str_replace(' ', '', $floor['floor_up']);

			if (!$floor['floor_down'] && !$floor['floor_up']){
				return FIELD_MISSING_VALUE;
			}
			else{
				if ($floor['floor_down'] != '')
				{
					$floor_down = str_replace('-', '', $floor['floor_down']);
					if (strpos($floor['floor_down'], '-') !== false)
					{
						// underground
						$floor_down = $current_lang == LANGUAGE_EN ? 'B' . $floor_down : '地下'.$floor_down.'階';
					}
					else {
						$floor_down = $current_lang == LANGUAGE_EN ? $floor_down . 'F' : $floor_down.'階';
					}
					$floorLevel[] = $floor_down;
				}
				if ($floor['floor_up'] != '')
				{
					$floor_up = str_replace('-', '', $floor['floor_up']);
					if (strpos($floor['floor_up'], '-') !== false)
					{
						// underground
						$floor_up = $current_lang == LANGUAGE_EN ? 'B' . $floor_up : '地下'.$floor_up.'階';
					}
					else {
						$floor_up = $current_lang == LANGUAGE_EN ? $floor_up . 'F' : $floor_up.'階';
					}
					$floorLevel[] = $floor_up;
				}

				return implode(FIELD_MISSING_VALUE, $floorLevel);
			}
			break;

		case "rent_unit_price_opt":
			return $floor[$field] == FLOOR_UNIT_OPTION_UNDECIDED ? trans_text('Undecided') : trans_text('Ask');
			break;

		case "unit_condo_fee" :
			if (!$floor['unit_condo_fee'])
			{
				return translateBuildingValue('unit_condo_fee_opt', $building, $floor, $property_id);
			}
			if (false && isEnglish())
			{
				$price = renderPrice(formatNumber(str_replace(',', '', $floor['unit_condo_fee'])) / OFFICE_DB_FEE_RATE);
				return $price . '/' . AREA_M2;
			}
			else {
				return renderPrice(formatNumber(str_replace(',', '', $floor['unit_condo_fee']))). '/' . trans_text('tsubo');
			}
			
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
				default:
					return FIELD_MISSING_VALUE;
					break;
			}
			break;

		case 'move_in_date' :
			if ($floor[$field])
			{
				if ($current_lang == LANGUAGE_EN)
				{
					$aExplodeDate = explode('/', $floor[$field]);
					$szDate = isset($aExplodeDate[2]) ? $aExplodeDate[2] : '';
					unset($aExplodeDate[2]);
					$move_date = strtotime(implode('-', $aExplodeDate));
					$dateFormatWithoutDate = 'M.Y';
					$dateFormatWithDate = 'M.d,Y';
						
					if (strpos($szDate, '月内') !== false)
					{
						$floor[$field] = date($dateFormatWithoutDate, $move_date);
					}
					elseif (strpos($szDate, '上旬') !== false)
					{
						$floor[$field] = 'Early ' . date($dateFormatWithoutDate, $move_date);
					}
					elseif (strpos($szDate, '中旬') !== false)
					{
						$floor[$field] = 'Mid ' . date($dateFormatWithoutDate, $move_date);
					}
					elseif (strpos($szDate, '下旬') !== false)
					{
						$floor[$field] = 'End ' . date($dateFormatWithoutDate, $move_date);
					}
					elseif (is_numeric($szDate))
					{
						$floor[$field] = date($dateFormatWithDate, $move_date);
					}
				}

				$floor[$field] = convertDateFormat($floor[$field]);
				return trans_text($floor[$field]);
			}
			else {
				return FIELD_MISSING_VALUE;
			}
				
			break;
		case 'built_year' :
			$aExplodeDate = explode('-', $building[$field]);
				
			if (trim($building[$field]) == '-')
			{
				$building[$field] = FIELD_MISSING_VALUE;
			}
			else {
				if ((count($aExplodeDate) == 2 && !$aExplodeDate[1]) || count($aExplodeDate)  == 1)
				{
					$aExplodeDate[1] = 1;
					$dateFormat = 'Y';
				}
				else{
					$dateFormat = 'M.Y';
				}

				if (isEnglish())
				{
					$building[$field] = date($dateFormat, strtotime(implode('-', $aExplodeDate)));
				}else {
					$building[$field] = date('Y年m月', strtotime(implode('-', $aExplodeDate)));
				}
			}
				
			$building[$field] = convertDateFormat($building[$field]);
			return $building[$field];
			break;

		case 'total_floor_space':
			return $building[$field] ? explodeRangeValue($building[$field], AREA_M2) : FIELD_MISSING_VALUE;
			break;

		case 'total_rent_space_unit':
			return $building[$field] ? explodeRangeValue($building[$field], AREA_M2) : FIELD_MISSING_VALUE;
			break;
				
		case 'earth_quake_res_std' :
			switch ($building[$field])
			{
				case EARTH_QUAKE_OLD_STANDARD:
					return trans_text('old-seismic building code');
					break;
				case EARTH_QUAKE_REINFOCED:
					return trans_text('Reinforced for Seismic Resistance');
					break;
				case EARTH_QUAKE_NEW_STANDARD:
					return trans_text('new-seismic building code');
					break;
				case EARTH_QUAKE_ISOLATION_STRUCTURE:
					return trans_text('quake-absorbing structure');
					break;
				case EARTH_QUAKE_UNKNOW:
					return trans_text('Unknown');
					break;
				case EARTH_QUAKE_DAMPING_STRUCTURE:
					return trans_text('Vibration Control Structure');
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
				if($elevatorExp[1] != "" || $elevatorExp[2] != "" || $elevatorExp[3] != "" || $elevatorExp[4] != "" || $elevatorExp[5] != "")
				// 					$return .= '(';
					$return .= isset($elevatorExp[1]) && $elevatorExp[1] != "" ? $elevatorExp[1].trans_text('ELV(s)') : trans_text('Exists');
					// 					$return .= isset($elevatorExp[2]) && $elevatorExp[2] != "" ? '/'.$elevatorExp[2].trans_text('Human power') : "";
					// 					$return .= isset($elevatorExp[3]) && $elevatorExp[3] != "" ? $elevatorExp[3].trans_text('For basic loading') : "";
					// 					$return .= isset($elevatorExp[4]) && $elevatorExp[4] != "" ? $elevatorExp[4].trans_text('Human power') : "";
					// 					$return .= isset($elevatorExp[5]) && $elevatorExp[5] != "" ? $elevatorExp[5]. trans_text('Group') : "";
					// 					if($elevatorExp[1] != "" || $elevatorExp[2] != "" || $elevatorExp[3] != "" || $elevatorExp[4] != "" || $elevatorExp[5] != "") $return .= ')';
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
				return ($parkingUnitNo[1] != "" ? $parkingUnitNo[1] . (!isEnglish() ? '台' : '') : trans_text('Exists'));
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
			if (isEnglish())
			{
				return $building['std_floor_space'] != "" ? 
					((formatNumber(str_replace(',', '', $building['std_floor_space']) * OFFICE_DB_FEE_RATE).' ' . AREA_M2) . '<br/>' . 
					formatNumber(str_replace(',', '', $building['std_floor_space'])) .' ' . trans_text('tsubo')) 
				: FIELD_MISSING_VALUE;
			}
			else {
				return $building['std_floor_space'] != "" ? formatNumber(str_replace(',', '', $building['std_floor_space'])) .' ' . trans_text('tsubo') : FIELD_MISSING_VALUE;
			}
			break;

		case 'security_id':
			$securityDetails = $wpdb->get_row("SELECT * FROM `security` WHERE security_id=" . (int)$building['security_id']);
			return $securityDetails ? trans_text($securityDetails->security_name) : FIELD_MISSING_VALUE;
			break;

		case 'renewal_data':
			return $building[$field] ? (isEnglish() ? trans_text($building['renewal_data_en']) : trans_text($building[$field])) : FIELD_MISSING_VALUE;
			break;

		case 'avg_neighbor_fee':
			if (!$building['avg_neighbor_fee_min'] && !$building['avg_neighbor_fee_max']){
				$return = FIELD_MISSING_VALUE;
			}
			else{
				if (isEnglish())
				{
					$return = $building['avg_neighbor_fee_min'] ? renderPrice(str_replace(',', '', $building['avg_neighbor_fee_min']) / OFFICE_DB_FEE_RATE) : FIELD_MISSING_VALUE;
					$return .= $building['avg_neighbor_fee_max'] ? FIELD_MISSING_VALUE . renderPrice(str_replace(',', '', $building['avg_neighbor_fee_max']) / OFFICE_DB_FEE_RATE) : FIELD_MISSING_VALUE;
				}
				else {
					$return = $building['avg_neighbor_fee_min'] ? renderPrice($building['avg_neighbor_fee_min']) : FIELD_MISSING_VALUE;
					$return .= $building['avg_neighbor_fee_max'] ? FIELD_MISSING_VALUE . renderPrice($building['avg_neighbor_fee_max']) : FIELD_MISSING_VALUE;
				}
			}
			return $return;
			break;
		case 'type_of_use' :
			$userTypesList = $wpdb->get_results("SELECT * FROM `use_types` WHERE user_type_id IN(". (string)$floor['type_of_use'] .")");
			$typeOfUse = array();
			foreach($userTypesList as $useList){
				$typeOfUse[] = trans_text($useList->user_type_name);
			}
			return implode(',', $typeOfUse);
			break;
				
		case 'contract_period_duration' :
			return $floor[$field] ? trans_text($floor[$field]) . trans_text('年') : FIELD_MISSING_VALUE;
			break;
				
		case 'contract_period':
			$return = '';
			if(isset($floor['contract_period_opt']) && $floor['contract_period_opt'] != ""){
				if($floor['contract_period_opt'] == 1){
					$return .=  trans_text('普通借家');
				}elseif($floor['contract_period_opt'] == 2){
					$return .=  trans_text('定借');
				}elseif($floor['contract_period_opt'] == 3){
					$return .=  trans_text('定借希望');
				}else{
					$return .=  FIELD_MISSING_VALUE;
				}
			}else{
				$return .=  FIELD_MISSING_VALUE;
			}

			if(isset($floor['contract_period_optchk']) && $floor['contract_period_optchk'] == 1){
				$return .=  trans_text('<br>年数相談');
			}

			return $return;
			break;
				
		case 'floor_material':
			return $floor[$field] ? trans_text($floor[$field]) : FIELD_MISSING_VALUE;
			break;

		case 'oa_type':
			return $floor[$field] ? trans_text($floor[$field]) : FIELD_MISSING_VALUE;
			break;

		case 'oa_height':
			return $floor[$field] ? trans_text(formatNumber($floor[$field])) . 'mm' : FIELD_MISSING_VALUE;
			break;
				
		case 'ceiling_height':
			return $floor[$field] ? trans_text(formatNumber($floor[$field])) . 'mm' : FIELD_MISSING_VALUE;
			break;

		case 'construction_type_name':
			if (!$building['construction_type_name_en'])
			{
				$construction = trans_text(($building[$field]));
			}
			else {
				if (isEnglish())
				{
					$construction = $building['construction_type_name_en'];
				}else {
					$construction = $building[$field];
				}
			}
			return $construction ? $construction . '' : '';
			break;

		case 'vacancy_info':
			return $floor[$field] ? trans_text('Avaiable') : trans_text('Not Available');
			break;
				
		default :
				
			break;
	}
}

function renderPrice($price) {
	$price = str_replace(',', '', $price);
	if ($price)
	{
		$price = ceil($price);
		$price = formatNumber($price, 0);
		return '<span class="price_currency">¥</span><span class="price">'.$price.'</span>';
	}
	else {
		return FIELD_MISSING_VALUE;
	}
}

function is_url_exist($url){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($code == 200){
		$status = true;
	}else{
		$status = false;
	}
	curl_close($ch);
	return $status;
}


function getBuildingPDF($building_id)
{
	global $wpdb;
	$Pdf = $wpdb->get_row("SELECT * FROM building_pdf_upload WHERE building_id = " . (int)$building_id . ' ORDER BY added_on DESC LIMIT 1');
	$pdfUrl = '';
	if ($Pdf && $Pdf->file_name)
	{
		$folder = OFFICE_DB_SITE_URL . '/buildingPdfUploads/';
		$pdfUrl = $folder . $Pdf->file_name;
		// 		if (!is_url_exist($pdfUrl))
			// 		{
			// 			$pdfUrl = '';
			// 		}
			}
			return $pdfUrl;
}

function getListBestPropertyViewed($post_per_page = 0) {
	$post_per_page = $post_per_page ? $post_per_page : PROPERTY_VIEWED_LIMIT;
	$query_args['post_type'] = 'property';
	$query_args['posts_per_page'] = $post_per_page;
	$query_args['order'] = 'DESC';
	$query_args['meta_key'] = 'estate_property_views_count';
	$query_args['orderby'] = 'meta_value_num';
	return new WP_Query( $query_args );
}

function renderPropertyPrice($property_id, $building, $floor)
{
	$property_price = doubleval( get_post_meta( $property_id, 'estate_property_price', true ) );
	if ($property_price <= 0)
	{
		return translateBuildingValue('rent_unit_price_opt', $building, $floor, $property_id);
	}
	if (isEnglish())
	{
		$price = renderPrice($property_price / OFFICE_DB_FEE_RATE);
		return $price . '/' . AREA_M2;
	}
	else {
		$price = renderPrice($property_price);
		return $price . '/' . trans_text('tsubo');
	}
}

function getBuildingFloorPicUrl($type_images, $type) {
	$image_types = array(
		'building_front' => '/buildingPictures/front/',
		'building_entrance' => '/buildingPictures/entrance/',
		'building_infront' => '/buildingPictures/inFront/',

		'floor_bathroom' => '/floorPictures/bathroom/',
		'floor_indoor' => '/floorPictures/indoor/',
		'floor_kitchen' => '/floorPictures/kitchen/',
		'floor_other' => '/floorPictures/other/',
		'floor_prospect' => '/floorPictures/prospect/',
		'floor_tenant' => '/floorPictures/tenant/',

		'plan' => '/planPictures/',
	);

	$images = array();
	foreach ($type_images as $image)
	{
		$image_path = OFFICE_DB_FOLDER_PATH . $image_types[$type] . $image;
		if (file_exists($image_path) && is_file($image_path))
		{
			$image_url = OFFICE_DB_SITE_URL . $image_types[$type] . $image;$image_url = OFFICE_DB_SITE_URL . $image_types[$type] . $image;
			$images[] = $image_url;
		}
	}
	return $images;
}

function realty_array_filter($string) {
	global $main_image;
	foreach ($main_image as $image)
	{
		if (!$image) continue;
		
		if (strpos($image, '.') !== false)
		{
			$image = substr($image, 0, strlen($image) - 4);
		}
		if (strpos($string, '.') !== false)
		{
			$string = substr($string, 0, strlen($string) - 4);
		}
		if (strpos($image, $string) !== false)
		{
			return false;
		}
	}
	return true;
}

function getBuildingFloorPictures($building, $floor, $property_id){
	global $wpdb, $main_image;
	$building_id = $building['building_id'];
	$floor_id = $floor['floor_id'];

	if ( has_post_thumbnail( $property_id ) ) {
		$thumbnail_id = get_post_thumbnail_id($property_id);
		$thumbnail_url_array = wp_get_attachment_image_src($thumbnail_id, $property_image_width, true);
		$thumbnail_url = $thumbnail_url_array[0];
	}

	// Get gallery from building and floor
	$buildingPictureRow = $wpdb->get_row("SELECT * FROM building_pictures WHERE building_id=".(int)$building_id);
	$all_images = array();
	if ($buildingPictureRow)
	{
		$thumbnail_name = basename($thumbnail_url);
		$main_image = array($buildingPictureRow->main_image, substr($thumbnail_name, strlen($building['building_id'])));
		$front_images = array_filter(explode(',' , $buildingPictureRow->front_images), 'realty_array_filter');
		$entrance_images = array_filter(explode(',' , $buildingPictureRow->entrance_images), 'realty_array_filter');
		$in_front_images = array_filter(explode(',' , $buildingPictureRow->in_front_building_images), 'realty_array_filter');

		// get image urls
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($front_images, 'building_front'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($entrance_images, 'building_entrance'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($in_front_images, 'building_infront'));
	}

	$floorPictureRow = $wpdb->get_row("SELECT * FROM floor_pictures WHERE floor_id=".(int)$floor_id . " AND building_id=".(int)$building_id);
	if ($floorPictureRow)
	{
		$indoor_images = explode(',', $floorPictureRow->indoor_image);
		$kitchen_images = explode(',', $floorPictureRow->kitchen_image);
		$bathroom_images = explode(',', $floorPictureRow->bathroom_image);
		$prospect_images = explode(',', $floorPictureRow->prospect_image);
		$other_images = explode(',', $floorPictureRow->other_image);
		$tenant_list_images = explode(',', $floorPictureRow->tenant_list_image);

		// get image urls
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($indoor_images, 'floor_indoor'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($kitchen_images, 'floor_kitchen'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($bathroom_images, 'floor_bathroom'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($prospect_images, 'floor_prospect'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($other_images, 'floor_other'));
		$all_images = array_merge($all_images, getBuildingFloorPicUrl($tenant_list_images, 'floor_tenant'));
	}
	$planPictureResults = $wpdb->get_results("SELECT * FROM plan_picture WHERE building_id=".(int)$building_id);
	if (!empty($planPictureResults))
	{
		$plan_images = array();
		foreach ($planPictureResults as $planPictureResult)
		{
			$plan_images[] = $planPictureResult->name;
		}

		// 		$all_images = array_merge($all_images, getBuildingFloorPicUrl($plan_images, 'plan'));
	}

	if ($thumbnail_url)
	{
		array_unshift($all_images, $thumbnail_url);
	}
	return $all_images;
}


add_action('wp_footer', 'realty_add_mobile_search');
function realty_add_mobile_search() {
	echo '<div id="temporary_search_block_wraper" style="display: none;"><div class="temporary_search_block hidden-pc">' . do_shortcode('[property_search_form search_form_columns="3" search_type="mini"]') . '</div></div>';
}


function realty_excerpt($limit) {
	$content = get_the_content();
	$content = preg_replace('/\[.+\]/','', $content);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);

	$content = mb_substr($content, 0, $limit);
	if ($content < $limit) {
		$content = $content.'...';
	} else {
		$content = $content;
	}

	return $content;
}

add_filter( 'post_type_link', 'realty_post_type_link', 10, 3 );
function realty_post_type_link($permalink, $post, $leavename)
{
	if ($post->post_type == 'news')
	{
		$floor_id = get_post_meta($post->ID, FLOOR_TYPE, true);
		if ($floor_id)
		{
			// Get property by news
			$new_args = array(
				'post_type' => 'property',
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => FLOOR_TYPE,
						'value' => $floor_id,
					)
				)
			);
			$the_news_query = new WP_Query( $new_args );
			if ( $the_news_query->have_posts() )
			{
				while ( $the_news_query->have_posts() ) {
					$the_news_query->the_post();
					global $post;
					$new_property = $post;
				}

			}
		}

		if (isset($new_property))
		{
			$permalink = get_permalink($new_property);
		}
	}
	return $permalink;
}

add_action( 'wp_ajax_tt_ajax_delete_user_profile', 'tt_ajax_delete_user_profile_function' );
if ( ! function_exists( 'tt_ajax_delete_user_profile_function' ) ) {
	function tt_ajax_delete_user_profile_function() {
		$user_id = $_POST['user_id'];
		$user = get_user_by('ID', $user_id);
		$deleted = 0;
		if (in_array('customer', $user->roles))
		{
			//$deleted = wp_delete_user($user_id);
			update_user_meta( $user_id, 'ja_disable_user', 1 );
			// Delete all user meta
			delete_user_meta($user_id, 'realty_user_follow');
			delete_user_meta($user_id, 'realty_user_contact');
			delete_user_meta($user_id, 'realty_user_favorites');
			$deleted = 1;
		}
		
		ob_start();
		wp_logout();
		ob_end_clean();

		echo json_encode(array('success' => $deleted, 'redirect' => get_option('siteurl'))); die;
	}
}

function get_floors_by_building($building_id)
{
	// Get list same building
	$buildingArgs = array(
		'post_type' => 'property',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => FLOOR_BUILDING_TYPE,
				'value' => $building_id,
				'compare' => '=',
			),
			array(
				'relation' => 'AND',
				'floor_down' => array(
					'key'       => 'estate_property_floor_down',
					'compare'   => 'EXISTS',
					'type'      => 'numeric'
				),
				'floor_up' => array(
					'key'       => 'estate_property_floor_up',
					'compare'   => 'EXISTS',
					'type'      => 'numeric'
				),
			)
		),
		'orderby' => array( 'floor_down' => 'ASC', 'floor_up' => 'ASC' )
	);
	
	return $buildingArgs;
}

add_action( 'wp_ajax_realty_get_floors', 'realty_get_floors' );
add_action( 'wp_ajax_nopriv_realty_get_floors', 'realty_get_floors' );
function realty_get_floors($building_id = 0){
	$building_id = $_REQUEST['building_id'];
	$building = getBuildingByBuildingID($building_id);
	$buildingArgs = get_floors_by_building($building_id);
	
	$action = $_GET['action'];
	$building_id = $_GET['building_id'];
	$isBuildingHaveBothVacant = realty_building_has_both_vacant($building_id);
	
	$buildingArgs = apply_filters( 'property_search_args', $buildingArgs );
	$query_floors_results = new WP_Query($buildingArgs);
	
	$responseArray = array();
	$responseHtml = '';
	if ($query_floors_results->have_posts() && $query_floors_results->post_count > 1) {
		while ( $query_floors_results->have_posts() ) : $query_floors_results->the_post();
			global $post;
			$related_property_id = get_the_ID();
			$related_floor = getFloor($related_property_id);
			
			$google_maps = get_post_meta( $related_property_id, 'estate_property_google_maps', true );
			$estate_property_station = isEnglish() ? $building['stations'][0]['name_en'] : $building['stations'][0]['name'];
			
			// out if floor has no vacant
			if ($isBuildingHaveBothVacant && !$related_floor['vacancy_info']) continue;
	
			$floor = array();
			$floor['floor_up_down'] = translateBuildingValue('floor_up_down', $building, $related_floor, $related_property_id);
			$floor['area_ping'] = translateBuildingValue('area_ping', $building, $related_floor, $related_property_id);
			$floor['rent_unit_price'] = translateBuildingValue('rent_unit_price_opt', $building, $related_floor, $related_property_id);
			$floor['unit_condo_fee'] = translateBuildingValue('unit_condo_fee_opt', $building, $related_floor, $related_property_id);
			$floor['move_in_date'] = translateBuildingValue('move_in_date', $building, $related_floor, $related_property_id);
			$floor['favorite'] = tt_add_remove_favorites();
			
			if (!isset($responseArray['address']))
			{
				$responseArray['address'] = $google_maps['address'];
				$responseArray['station'] = $estate_property_station;
				$responseArray['content'] = get_the_content();
			}
			
			$row = '<tr class="overlink_row '. ($related_floor['vacancy_info'] ? 'vacancy_available' : 'vacancy_unavailable') .'">
						<td class="overlink floor_up_down"><a href="'.get_permalink().'">'.$floor['floor_up_down'].'</a></td>
						<td class="overlink area_ping"><a href="'.get_permalink().'">'.$floor['area_ping'].'</a></td>
						<td class="overlink rent_unit_price"><a href="'.get_permalink().'">'.$floor['rent_unit_price'].'</a></td>
						<td class="overlink unit_condo_fee"><a href="'.get_permalink().'">'.$floor['unit_condo_fee'].'</a></td>
						<td class="overlink move_in_date"><a href="'.get_permalink().'">'.$floor['move_in_date'].'</a></td>
						<td class="overlink vacancy_info '. ($related_floor['vacancy_info'] ? 'available' : 'unavailable') .'">
							<a href="'.get_permalink().'">'.($related_floor['vacancy_info'] ? trans_text('Avaiable') : trans_text('Not Available')).'</a>
						</td>
						<td class="favorite_column">'.$floor['favorite'].'</td>
					</tr>';
			$responseArray['floor'][] = $floor;
			$responseHtml .= $row;
		endwhile;
	}
	
	if (defined( 'DOING_AJAX' ) && DOING_AJAX )
	{
		$responseArray['html'] = $responseHtml;
		echo json_encode($responseArray);die;
	}
	else {
		return $responseArray;
	}
}

add_filter( 'wp_mail', 'realty_wp_mail', 10, 1 );
function realty_wp_mail ($atts)
{
	if(defined('PROPERTY_MAIL_TESTING') && PROPERTY_MAIL_TESTING == true && is_array($atts) && isset($atts['headers']))
	{
		if (is_string($atts['headers']))
		{
			if (defined('PROPERTY_MAIL_CC') || PROPERTY_MAIL_CC)
			{
				$atts['headers'] .= 'Cc: ' . PROPERTY_MAIL_CC . PHP_EOL;
			}
			if (defined('PROPERTY_MAIL_BCC') || PROPERTY_MAIL_BCC)
			{
				$atts['headers'] .= 'Bcc: ' . PROPERTY_MAIL_BCC . PHP_EOL;
			}
		}
		else {
			if (defined('PROPERTY_MAIL_CC') || PROPERTY_MAIL_CC)
			{
				$atts['headers'][] = 'Cc: ' . PROPERTY_MAIL_CC . PHP_EOL;
			}
			if (defined('PROPERTY_MAIL_BCC') || PROPERTY_MAIL_BCC)
			{
				$atts['headers'][] = 'Bcc: ' . PROPERTY_MAIL_BCC . PHP_EOL;
			}
		}
	}
	
	return $atts;
}

function realty_recaptcha_scripts() {
	wp_deregister_script( 'google-recaptcha' );

	$url = 'https://www.google.com/recaptcha/api.js';
	$url = add_query_arg( array(
		'onload' => 'recaptchaCallback',
		'render' => 'explicit',
		'hl' => pll_current_language()), $url ); // es is the language code for Spanish language

	wp_register_script( 'google-recaptcha', $url, array(), '2.0', true );
}

add_action( 'wpcf7_enqueue_scripts', 'realty_recaptcha_scripts', 11 );


// add_filter( 'registration_errors', 'realty_registration_errors', 10, 3 );
function realty_registration_errors($errors, $sanitized_user_login, $user_email) {
	global $realty_theme_option, $current_user;
	
	// If reCAPTCHA not entered or incorrect reCAPTCHA answer
	$wppb_recaptcha_response = wppb_validate_captcha_response( $realty_theme_option['google-recaptcha-site-key'], $realty_theme_option['google-recaptcha-secret-key'] );
	if ( $wppb_recaptcha_response == false ) {
		$errors->add( 'wppb_recaptcha_error', __('Please enter a (valid) reCAPTCHA value','profile-builder') );
	}
	
	return $errors;
}

add_action('wp_head','realty_hook_meta_tag', 9999);
function realty_hook_meta_tag() {
	$output = '';
	if (is_singular('property')) {
		$property_id = get_the_ID();
		$building_id = get_post_meta($property_id, FLOOR_BUILDING_TYPE, true);
		$isBuildingHaveBothVacant = realty_building_has_both_vacant($building_id);
		if ($isBuildingHaveBothVacant)
		{
			$property_meta = getFloor($property_id);
			if (!$property_meta['vacancy_info'])
			{
				$output='<meta name="robots" content="noindex, nofollow" />';
			}
		}
	}
	echo $output;
}

function realty_building_has_both_vacant($building_id)
{
	global $wpdb;
	$result = $wpdb->get_row("select COUNT(*) as count, SUM(vacancy_info) as sum FROM  floor where show_frontend = 1 and building_id = $building_id");
	if ($result->count > $result->sum && $result->sum > 0)
	{	
		// This case, building has both vacant and no vacant
		return true;
	}
	else
	{
		return false;
	}
}
<?php
function get_favorite_property_list($user_id = false, $meta_key = 'realty_user_favorites'){
	$user = get_currentuserinfo();
	$user_id = $user->ID;

	$buildingList = get_user_meta($user_id, $meta_key, true);
	if (!$buildingList) return array();
	
	// Sort by value date descending
	arsort($buildingList);
	
	$buildingIds = array_keys($buildingList);

	$args = array(
		'post_type' => 'property',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'relation' => 'OR',
			)
		),
	);
	
	foreach ($buildingIds as $buildingId)
	{
		$args['meta_query'][0][] = array(
			'key'       => 'jpdb_floor_building_id',
			'compare'   => '=',
			'value'      => $buildingId
		);
	}

	$aProperties = get_posts($args);
	
	$properties = array();
	// Sort properties by building id
	foreach ($buildingIds as $buildingId)
	{
		$isBuildingHaveBothVacant = realty_building_has_both_vacant($buildingId);
		
		foreach ($aProperties as $aProperty)
		{
			$floor = getFloor($aProperty->ID);
			
			// out if floor has no vacant
			if ($isBuildingHaveBothVacant && !$floor['vacancy_info']) continue;
			
			if ($aProperty->pinged == FLOOOR_BUILDING_PARENT . $buildingId)
			{
				$properties[] = $aProperty;
			}
		}
	}
	
	$get_user_meta_follow = (array)get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()
	$tableFloors = array();
	
	//Count floor same building
	$aFloorCounting = array();
	foreach ($properties as $property_index => $property) {
		$aFloorCounting[$property->pinged] = (isset($aFloorCounting[$property->pinged]) ? $aFloorCounting[$property->pinged] : 0) + 1;
	}
	
	$current_lang = pll_current_language();
	foreach ($properties as $property_index => $property) {
		$single_property_id = $property->ID;
		$building = getBuilding($single_property_id);
		$floor = getFloor($single_property_id);
		
		$google_maps = get_post_meta( $single_property_id, 'estate_property_google_maps', true );

		$building_id_lang = $building['building_id'].'_'.$current_lang;
		$isSubcribed = (! empty( $get_user_meta_follow ) && isset($get_user_meta_follow[0]) && isset($get_user_meta_follow[0][$building_id_lang])) ? true : false;
		$inquiryUrl = pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en');
		$inquiryUrl .=  '?id='. $single_property_id;
		$buildingName = get_post_meta($single_property_id, 'post_title_building', true);
		$floorNumber = substr($property->post_title, strlen($buildingName));
		
		$tableFloors[$property->pinged][$property_index]['thumbnail'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . get_the_post_thumbnail($single_property_id, 'thumbnail') . '</a>';
		$tableFloors[$property->pinged][$property_index]['name'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . $property->post_title . '</a>';
		$tableFloors[$property->pinged][$property_index]['size'] = translateBuildingValue('area_ping', $building, $floor, $single_property_id);
		$tableFloors[$property->pinged][$property_index]['address'] = $google_maps['address'];
		$tableFloors[$property->pinged][$property_index]['rent_unit_price'] = $floor['rent_unit_price'] ? renderPrice($floor['rent_unit_price']) : translateBuildingValue('rent_unit_price_opt', $building, $floor, $single_property_id);
		$tableFloors[$property->pinged][$property_index]['deposit'] = renderPrice($floor['total_deposit']);
		$tableFloors[$property->pinged][$property_index]['date_move'] = translateBuildingValue('move_in_date', $building, $floor, $single_property_id);
		$tableFloors[$property->pinged][$property_index]['property_id'] = $single_property_id;
		$tableFloors[$property->pinged][$property_index]['pinged'] = $property->pinged;
		$tableFloors[$property->pinged][$property_index]['title'] = $property->post_title ;
		$tableFloors[$property->pinged][$property_index]['building_name'] = $buildingName ;
		$tableFloors[$property->pinged][$property_index]['floor_number'] = $floorNumber ;
		$tableFloors[$property->pinged][$property_index]['vacancy_info'] = $floor['vacancy_info'] ;
		$tableFloors[$property->pinged][$property_index]['fixed_floor'] = $floor['fixed_floor'] ;
		
		$tableFloors[$property->pinged][$property_index]['subscribed'] = '<a class="btn btn-success add-to-follow-popup follow-popup '. ($isSubcribed ? ' subscribed' : '') .'" data-fav-id="'. $single_property_id .'" data-subscribe="'. trans_text('Subscribe') .'" data-unsubscribe="'. trans_text('Unsubscribe') .'"  href="javascript:void(0)">' . ($isSubcribed ? trans_text('Unsubscribe') : trans_text('Subscribe')) . '</a>';
		$tableFloors[$property->pinged][$property_index]['contact_url'] = $inquiryUrl;
		foreach ($aFloorCounting as $pinged => $floorCounting)
		{
			if ($pinged == $property->pinged)
			{
				$tableFloors[$property->pinged][$property_index]['related_count'] = $floorCounting;
			}
		}
	}
	// Sort by floor number
	if (!function_exists('sort_by_floor_number'))
	{
		function sort_by_floor_number($a, $b)
		{
			return $a['floor_number'] - $b['floor_number'];
		}
	}
	
	foreach ($tableFloors as $pinged => &$tableFloor)
	{
		usort($tableFloor, 'sort_by_floor_number');
	}
	return $tableFloors;
}

function buildListFavoriteProperty($show_remove = false, $is_modal = false){
	$user = get_currentuserinfo();
	$user_id = $user->ID;
	
	$aTableFloors = get_favorite_property_list(false, 'realty_user_favorites');
	$get_user_meta_follow = (array)get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()
	$tableHtml = '';
	if (!empty($aTableFloors) || $is_modal) {
		ob_start();
		?>
	<!--<h4><?php //echo trans_text('With list of properties below :')?></h4>-->
	<table class="favorite_list_later">
		<thead>
			<tr>
				<th class="floor_picture" ><?php echo trans_text('Building Name')?></th>
				<?php if ($show_remove) {?>
				<!--<th class="floor_subscribe"><?php //echo trans_text('Subscribe Setting')?></th>-->
				<?php }?>
				<th colspan="2" class="floor_name"><?php echo trans_text('Floor')?></th>
				<th class="floor_rent"><?php echo trans_text('Rent')?></th>
				<th class="floor_area"><?php echo trans_text('Area')?></th>
				<th class="floor_date_move"><?php echo trans_text('Date of occupancy')?></th>
				<?php if ($show_remove) {?>
				<th class="floor_contact"></th>
				<th class="floor_action_remove"></th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
		<?php 
		$current_lang = pll_current_language();
		foreach ($aTableFloors as $pinged => $tableFloors) {
			$building_id = get_post_meta($tableFloors[0]['property_id'], FLOOR_BUILDING_TYPE, true);
			$building_id_lang = $building_id.'_'.$current_lang;
			$isSubcribed = (! empty( $get_user_meta_follow ) && isset($get_user_meta_follow[0]) && isset($get_user_meta_follow[0][$building_id_lang])) ? true : false;
			
			$tableFloors = array_values($tableFloors);
		?>
			<tr class="favorite_item">
			<td class="show-sp name_sp"><span class="bld_name_sp"><?php echo $tableFloors[0]['building_name']?></span></td>
			<td class="floor_picture">
			<span class="bld_name"><?php echo $tableFloors[0]['building_name']?></span><!--
			--><span class="flex-wrap"><span class="floor_thumb"><?php echo $tableFloors[0]['thumbnail']?></span><!--
			--><?php if ($show_remove) {?>
				<span class="hide-sp floor_subscribe" ><a class="btn btn-success add-to-follow-popup follow-popup <?php echo ($isSubcribed ? ' subscribed' : '')?>" data-fav-id="<?php echo $tableFloors[0]['property_id']?>" data-subscribe="<?php echo trans_text('Subscribe')?>" data-unsubscribe="<?php echo trans_text('Unsubscribe')?>" href="javascript:void(0)"><?php echo $isSubcribed ? trans_text('Unsubscribe') : trans_text('Subscribe'); ?></a></span>
				<?php }?></span><!--/flex-wrap-->
			</td>
				
				<td class="floors-td" colspan="6">
					<table class="tmp_table">
					<?php foreach ($tableFloors as $indexFloor => $floor) {
						$inquiryUrl = pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en');
						$inquiryUrl .=  '?id='. $floor['property_id'];
						
					?>
						<tr class="tmp_table_row">
						<td class="floor_checkbox">
								<span><input type="checkbox" name="floor_checked[]" class="form-control chosen-select floor_checked" value="<?php echo $floor['property_id']?>"/></span>
							</td>
							<?php if (!$floor['fixed_floor'] || !$floor['vacancy_info'] ) {?>
							<td class="floor_name">
								<span class="status <?php if ($floor['vacancy_info']) {?>vacant<?php } else {?>novacant<?php }?>"><?php echo $floor['vacancy_info'] ? trans_text('Avaiable') : trans_text('Not Available');?></span>
								<?php echo $floor['floor_number']?>
							</td>
							<td class="floor_rent"><?php echo $floor['rent_unit_price']?></td>
							<td class="floor_area"><?php echo $floor['size']?></td>
							<td class="floor_date_move"><?php echo $floor['date_move']?></td>
							<?php } else {?>
							<td class="floor_novacant" colspan="4"><?php echo trans_text('このビルは満室です')?></td>
							<?php }?>
							<?php if ($show_remove) {?>
							<td class="floor_contact"><a class="btn btn-success" href="<?php echo $inquiryUrl;?>"><?php echo trans_text('Contact now')?></a></td>
							<?php }?>
						</tr>
					<?php }?>
					</table>
				</td>
				<?php if ($show_remove) {?>
				<td class="show-sp action-groups" ><span class="floor_subscribe"><a class="btn btn-success add-to-follow-popup follow-popup <?php echo ($isSubcribed ? ' subscribed' : '')?>" data-fav-id="<?php echo $tableFloors[0]['property_id']?>" data-subscribe="<?php echo trans_text('Subscribe')?>" data-unsubscribe="<?php echo trans_text('Unsubscribe')?>" href="javascript:void(0)"><?php echo $isSubcribed ? trans_text('Unsubscribe') : trans_text('Subscribe'); ?></a></span>
				<span class="floor_action_remove" ><a href="javascript:void(0)" class="remove_property add-to-favorites" data-fav-id="<?php echo $floor['property_id']?>" ><?php echo trans_text('Remove')?></a></span>
				</td>
				<?php }?>
				<?php if ($show_remove) {?>
				<td class="floor_action_remove hide-sp" ><a href="javascript:void(0)" class="remove_property add-to-favorites" data-fav-id="<?php echo $floor['property_id']?>" ><?php echo trans_text('Remove')?></a></td>
				<?php }?>
			</tr>
		<?php }
		?>
		
		<tr class="favorite_item_tmp element-disable" style="display:none">
		<td class="show-sp name_sp"></td>
			<td class="floor_picture" ></td>
			<td class="floors-td" colspan="6">
				<table class="tmp_table">
					<tr class="tmp_table_row">
						<td class="floor_checkbox form-group checkbox"></td>
						<td class="floor_name"></td>
						<td class="floor_rent"></td>
						<td class="floor_area"></td>
						<td class="floor_date_move"></td>
						<?php if ($show_remove) {?>
						<td class="floor_contact"><a class="btn btn-success" href="<?php echo $inquiryUrl = pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en');?>"><?php echo trans_text('Contact')?></a></td>
						<?php }?>
					</tr>
				</table>
			</td>
			<?php if ($show_remove) {?>
			<td class="show-sp action-groups">
			<span class="floor_subscribe" ></span><!--
			--><span class="floor_action_remove" ><a href="javascript:void(0)" class="remove_property add-to-favorites" ><?php echo trans_text('Remove')?></a></span>
			</td>
			<?php }?>
			
			
			
			<?php if ($show_remove) {?>
			<td class="floor_action_remove hide-sp" ><a href="javascript:void(0)" class="remove_property add-to-favorites" ><?php echo trans_text('Remove')?></a></td>
			<?php }?>
		</tr>
		</tbody>
	</table>
	<?php }?>
	<?php if (!count($tableFloors)) {?>
	<style>
		.favorite_list_later, .modal-body .button_groups .btn-success {display: none;}
	</style>
	<p class="favorite_item no-favorite-list alert alert-info"><?php esc_html_e( 'There is no added properties', 'realty' ); ?></p>
	<?php }?>
<?php 
	$tableHtml = ob_get_contents();
	ob_end_clean();
	
	return $tableHtml;
}

/**
 * AJAX - Favorites
 *
 */
if ( ! function_exists( 'tt_ajax_add_remove_favorites' ) ) {
	function tt_ajax_add_remove_favorites() {

		global $wpdb;
		$user_id = $_GET['user'];
		$aProperty_id = explode(',', $_GET['property']);
		$propertyIds = array();
		
		foreach ($aProperty_id as $property_id)
		{
			// Get Favorites Meta Data
			$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()
			
			$building_id = get_post_meta($property_id, FLOOR_BUILDING_TYPE, true);
			$current_lang = pll_current_language();
			$building_id_lang = $building_id.'_'.$current_lang;
			$current_time = time();
			
			if ( ! $get_user_meta_favorites ) {
				// No User Meta Data Favorites Found -> Add Data
				$get_user_meta_favorites = array();
				$get_user_meta_favorites[0][$building_id] = $current_time;
			} else {
				// Meta Data Found -> Update Data
				if ( isset($get_user_meta_favorites[0][$building_id])) {
					// Remove Favorite
					unset($get_user_meta_favorites[0][$building_id]);
			
					// Remove follow
					$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()
					if ($get_user_meta_follow && isset($get_user_meta_follow[0]) && isset($get_user_meta_follow[0][$building_id_lang]))
					{
						unset( $get_user_meta_follow[0][$building_id_lang] );
						update_user_meta( $user_id, 'realty_user_follow', $get_user_meta_follow[0] );
					}
					
				} else {
					// Add New Favorite
					$get_user_meta_favorites[0][$building_id] = $current_time;
				}
			}
			update_user_meta( $user_id, 'realty_user_favorites', $get_user_meta_favorites[0] );
		}
		$tableFloors = get_favorite_property_list($user_id, 'realty_user_favorites');
		// remove index
		$tableFloors = array_values($tableFloors);
		echo json_encode(array('floors' => $tableFloors)); die;
	}
}
add_action( 'wp_ajax_tt_ajax_add_remove_favorites', 'tt_ajax_add_remove_favorites' );

/**
 * Favorites - Click
 *
 */
if ( !function_exists('tt_add_remove_favorites') ) {
	function tt_add_remove_favorites( $property_id = 0, $is_custom = '' ) {

		global $realty_theme_option;

		if ( $realty_theme_option['property-favorites-disabled'] ) {
			return;
		}

		$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}
		
		$building_id = get_post_meta($property_id, FLOOR_BUILDING_TYPE, true);
		$favicon = '%s';
		if ( is_user_logged_in() ) {
			// Logged-In User
			$user_id = get_current_user_id();
			$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()

			if ( ! empty( $get_user_meta_favorites ) && isset($get_user_meta_favorites[0][$building_id]) ) {
				// Property Is Already In Favorites
				$class = $is_custom ? 'add-to-favorites custom-fav fa fa-star' : 'add-to-favorites origin fa fa-star';
				$text = __( 'Remove From Favorites', 'realty' );
			} else {
				// Property Isn't In Favorites
				$class = $is_custom ? 'add-to-favorites custom-fav fa fa-star-o' : 'add-to-favorites origin  fa fa-star-o';
				$text = __( 'Add To Favorites', 'realty' );
			}
		} else {
			// Not Logged-In Visitor
			$class = $is_custom ? 'add-to-favorites custom-fav fa fa-star-o' : 'add-to-favorites origin fa fa-star-o';
			$text = __( 'Add To Favorites', 'realty' );
		}

		if ($is_custom){
			$favicon = '<a href="javascript:void(0);" class="btn btn-primary btn-square btn-line-border add-to-favorites_wraper">%s<span>'.$text.'</span></a>';
		}
		
		return sprintf($favicon, '<i class="'.$class.'" data-fav-id="' . $property_id . '" data-toggle="tooltip" data-remove-title="'.__( 'Remove From Favorites', 'realty' ).'" data-add-title="'.__( 'Add To Favorites', 'realty' ).'" title="' . $text . '"></i>');

	}
}

/**
 * Favorites - Script
 *
 */
if ( ! function_exists( 'tt_favorites_script' ) ) {
	function tt_favorites_script() {

		global $realty_theme_option;
		$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
		?>

		<script>
		jQuery('body').on('click', '.favorite-header', function(e){
			e.preventDefault();
			jQuery('#favorite-multiple-modal').modal('show');
		});
		
		<?php
		// Temporary Favorites
		if ( ! is_user_logged_in() && $realty_theme_option['property-favorites-temporary'] ) {
		?>
		jQuery('.add-to-favorites').each(function() {

			// Check If item Already In Favorites Array
			function inArray(needle, haystack) {
		    if ( haystack ) {
			    var length = haystack.length;
			    for( var i = 0; i < length; i++ ) {
			      if(haystack[i] == needle) return true;
			    }
			    return false;
		    }
			}

			// Check If Browser Supports LocalStorage
			if (!store.enabled) {
		    console.log('<?php echo __( 'Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.', 'realty' ); ?>');
				return;
		  }
			// Toggle Heart Class
			if ( inArray( jQuery(this).attr('data-fav-id'), store.get('favorites') ) ) {
				if (!jQuery(this).hasClass('custom-fav'))
				{
					jQuery(this).toggleClass('fa-star fa-star-o');
				}
				else {
					jQuery(this).toggleClass('fa-star-o fa-star');
				}


			}

		});
		<?php } ?>


		jQuery('body').on("click",'.add-to-follow-popup',function() {

		<?php if ( is_user_logged_in() ) { // Logged-In User ?>

				<?php $user_id = get_current_user_id();	?>
				var elementCLick = jQuery(this);
				jQuery('body').LoadingOverlay("show");
				jQuery.ajax({
				  type: 'GET',
				  url: ajax_object.ajax_url,
				  data: {
				    'action'        :   'tt_ajax_add_remove_follow', // WP Function
				    'user'					: 	<?php echo $user_id; ?>,
				    'property'			: 	elementCLick.attr('data-fav-id')
				  },
				  success: function (response) {
					elementCLick.toggleClass('subscribed');

					if (elementCLick.hasClass('subscribed'))
						elementCLick.text(elementCLick.attr('data-unsubscribe'));
					else
						elementCLick.text(elementCLick.attr('data-subscribe'));
					
					jQuery('body').LoadingOverlay("hide");
				  },
				  error: function () {
					  jQuery('body').LoadingOverlay("hide");
				  }
				});

			<?php }?>

		});

		function checkboxFavoriteInitial(){
			jQuery('#bulk-remove').attr('data-fav-id', '');
			jQuery('input.floor_checked').on('ifChanged', function(event){
				var checkedIds = [];
				jQuery('input.floor_checked:visible:checked').each(function(){
					checkedIds.push(jQuery(this).closest('tr').find('.remove_property').attr('data-fav-id'));
				});
				
				jQuery('#bulk-remove').attr('data-fav-id', checkedIds.join(','));
			});
		}
		checkboxFavoriteInitial();
		
		jQuery('body').on('click', '#contact-inquiry', function(e){
			e.preventDefault();
			var aChecked = [];
			jQuery('input[name="floor_checked[]"]').each(function(){
				if (jQuery(this).is(':checked')){
					aChecked.push(jQuery(this).val());
				}
			});
			if (aChecked.length)
			{
				location.href = jQuery(this).attr('href') + '?id=' + aChecked.join(','); 
			}
		});
		
		jQuery('body').on("click",'.add-to-favorites_wraper',function(e) {
			e.preventDefault();
			jQuery(this).find('.add-to-favorites').click();
		});
		
		jQuery('body').on("click",'.add-to-favorites',function(e) {
			e.preventDefault();
	        e.stopPropagation();

			<?php
			// Logged-In User Or Temporary Favorites Enabled
			if ( is_user_logged_in() || $add_favorites_temporary ) {
			?>

				// Toggle Favorites Tooltips
				var elementCLick = jQuery(this);
				var property_id = elementCLick.attr('data-fav-id');
				var single_wraper = elementCLick.closest('#single_property_wraper');
				var is_single = single_wraper.length;
				var is_remove = false;
				var show_popup = false;
				var title;


				if (!property_id) return '';

				// find all floor same building 
				var relatedFloors = [property_id];
				if (jQuery('.favorite_list_later input.floor_checked:visible').length)
				{
					jQuery('#favorite-multiple-modal .favorite_list_later:eq(0) input.floor_checked').each(function(){
						if (jQuery(this).val() == property_id) {
							var buildingTable = jQuery(this).closest('table.tmp_table');

							buildingTable.find('input.floor_checked').each(function(){
								if (relatedFloors.indexOf(jQuery(this).val()) == -1) 
								{
									relatedFloors.push(jQuery(this).val());
								}
							});
						}
					});
				}

				if (jQuery('.overlink_row .favorite_column').length)
				{
					jQuery('#building_detail_modal .add-to-favorites').each(function(){
						if (jQuery(this).attr('data-fav-id') == property_id) {
							var buildingTable = jQuery(this).closest('.propertyTable');

							buildingTable.find('.add-to-favorites').each(function(){
								if (relatedFloors.indexOf(jQuery(this).attr('data-fav-id')) == -1) 
								{
									relatedFloors.push(jQuery(this).attr('data-fav-id'));
								}
							});
						}
					});
				}
				
								
				if (is_single)
				{
					if (!single_wraper.find('a.add-to-favorites_wraper i.add-to-favorites').hasClass('fa-star-o') || elementCLick.hasClass('remove_property'))
					{
						if (!confirm('<?php echo trans_text("Removing will stop subscription too, are you sure to remove?")?>'))
						{
							return '';
						}
						
					}
					
					single_wraper.find('i.add-to-favorites.origin').toggleClass('fa-star fa-star-o');

					jQuery.each(relatedFloors, function(property_index, property_id){
						jQuery('i.add-to-favorites[data-fav-id="'+property_id+'"]').toggleClass('fa-star-o fa-star');
					});

					if (single_wraper.find('a.add-to-favorites_wraper i.add-to-favorites').hasClass('fa-star'))
					{
						title = single_wraper.find('a.add-to-favorites_wraper i.add-to-favorites').attr('data-remove-title');
						show_popup = true;
					}
					else if (single_wraper.find('a.add-to-favorites_wraper i.add-to-favorites').hasClass('fa-star-o') || elementCLick.hasClass('remove_property'))
					{
						title = single_wraper.find('a.add-to-favorites_wraper i.add-to-favorites').attr('data-add-title');
						show_popup = false;
						is_remove = true;
					}

					single_wraper.find('i.add-to-favorites.origin').attr('data-original-title', title);
					single_wraper.find('a.add-to-favorites_wraper span').text(title);
					single_wraper.find('a.add-to-favorites_wraper').attr('title', title);

					jQuery('#single_favorite_text').text(title);
				}
				else {
					if (elementCLick.hasClass('custom-fav'))
					{						
						jQuery.each(relatedFloors, function(property_index, property_id){
							jQuery('i.add-to-favorites[data-fav-id="'+property_id+'"]').toggleClass('fa-star-o fa-star');
						});
						
						
					}
					else {

						var element = elementCLick.find('i').length ? elementCLick.find('i') : elementCLick.closest('i');
						
						if (!element.hasClass('fa-star-o') || elementCLick.hasClass('remove_property'))
						{
							if (!confirm('<?php echo trans_text("Removing will stop subscription too, are you sure to remove?")?>'))
							{
								return '';
							}
							
						}

						jQuery.each(relatedFloors, function(property_index, property_id){
							jQuery('i.add-to-favorites[data-fav-id="'+property_id+'"]').toggleClass('fa-star fa-star-o');
						});

						if (element.hasClass('fa-star'))
						{
							title = element.attr('data-remove-title');
							show_popup = true;
						}
						
						else if (element.hasClass('fa-star-o') || elementCLick.hasClass('remove_property'))
						{
							title = element.attr('data-add-title');
							show_popup = false;
							is_remove = true;
						}

						element.attr('data-original-title', title);
					}
				}
					

				<?php if ( is_user_logged_in() ) { ?>
					<?php $user_id = get_current_user_id();	?>
					jQuery('body').LoadingOverlay("show");
					jQuery.ajax({
					  type: 'GET',
					  url: ajax_object.ajax_url,
					  dataType: 'json',
					  data: {
					    'action'        :   'tt_ajax_add_remove_favorites', // WP Function
					    'user'					: 	<?php echo $user_id; ?>,
					    'property'			: 	elementCLick.attr('data-fav-id')
					  },
					  success: function (response) {

							var aFloors = response.floors;
							
							jQuery('body').LoadingOverlay("hide");
							if (show_popup || elementCLick.hasClass('remove_property'))
							{
								// show popup
								if (elementCLick.closest('.page-user-favorites').length == 0)
								{
									jQuery('#favorite-multiple-modal').modal('show');
								}

								jQuery('.favorite_item').remove();	

								if (aFloors.length)
								{
									jQuery('.favorite_list_later').show();
									jQuery('.modal-body .button_groups .btn-success').css('display', 'inline-block');
									jQuery('.no-favorite-list').remove();


									var favorite_html = '';
									var favorite_count = 0;
									jQuery.each(aFloors, function(pinged, floors){
										favorite_count++;
										var building_row = jQuery('tr.favorite_item_tmp:eq(0)').clone();
										var current_floor = [];
										building_row.find('tr').remove();
										
										jQuery.each(floors, function(floor_index, floor){
											var floor_row = jQuery('tr.favorite_item_tmp:eq(0) table.tmp_table tr').clone();
											current_floor = floor;
											
											floor_row.find('.floor_checkbox').html('<span><input type="checkbox" name="floor_checked[]" class="form-control chosen-select floor_checked" value="'+ floor.property_id +'"/></span>');
											floor_row.find('.floor_name').html('<span>'+floor.floor_number+'</span>');
											floor_row.find('.floor_rent').html('<span>'+floor.rent_unit_price+'</span>');
											floor_row.find('.floor_area').html('<span>'+floor.size+'</span>');
											floor_row.find('.floor_deposit').html(floor.deposit);
											floor_row.find('.floor_date_move').html(floor.date_move);

											building_row.find('table.tmp_table').append(floor_row);
										});

										building_row.removeClass('favorite_item_tmp element-disable');
										building_row.show();
										building_row.addClass('favorite_item');

										building_row.find('.floor_picture').html('<span class="bld_name">'+current_floor.building_name+'</span><span class="flex-wrap"><span class="floor_thumb">'+current_floor.thumbnail+'</span><span class="hide-sp floor_subscribe" >'+current_floor.subscribed+'</span></span>');
										building_row.find('.name_sp').html('<span class="bld_name_sp">'+current_floor.building_name+'</span>');
										building_row.find('.floor_subscribe').html(current_floor.subscribed);
										building_row.find('.floor_contact a').attr('href', current_floor.contact_url);
										building_row.find('.floor_action_remove a').attr('data-fav-id', current_floor.property_id);
										jQuery('.favorite_list_later > tbody').append(building_row);
									});

									// Update header count
									jQuery('.favorite-list-count').html(favorite_count);
									
									checkboxFavoriteInitial();
								}
								else {
									jQuery('.favorite_list_later').hide();
									jQuery('.modal-body .button_groups .btn-success').hide();
									jQuery('.favorite_item').remove();
									
									jQuery('.favorite_list_later').before('<p class="favorite_item no-favorite-list alert alert-info"><?php esc_html_e( 'There is no added properties', 'realty' ); ?></p>');
									jQuery('#favorite-multiple-modal').modal('hide');
									if (elementCLick.closest('form.shortcode-form').length)
									{
										$('.favorite_list_later').fadeOut();
									}
								}

								jQuery('.floor_checkbox input').iCheck({
								    checkboxClass: 'icheckbox_square',
								    radioClass: 'iradio_square',
								    increaseArea: '20%' // optional
								});
							}
							else{
								if (is_remove)
								{
									jQuery('.floor_checked[value="'+property_id+'"]').closest('tr.favorite_item').remove();
									jQuery('.favorite-list-count').html(parseInt(jQuery('.favorite-list-count:eq(0)').text()) - 1);
								}
							}
						  
					  },
					  error: function () { jQuery('body').LoadingOverlay("hide"); }
					});

					<?php } else if ( $add_favorites_temporary ) { ?>

					if (!store.enabled) {
				    console.log('<?php echo __( 'Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.', 'realty' ); ?>');
						return;
				  }

					// Check For Temporary Favorites (store.js plugin)
					if ( store.get('favorites') ) {

						// Check If item Already In Favorites Array
						function inArray(needle, haystack) {
					    var length = haystack.length;
					    for( var i = 0; i < length; i++ ) {
				        if(haystack[i] == needle) return true;
					    }
					    return false;
						}

						var getFavs = store.get('favorites');
						var newFav = elementCLick.attr('data-fav-id');

						if ( inArray( newFav, getFavs ) ) {
							// Remove Old Favorite
							var index = getFavs.indexOf(newFav);
							getFavs.splice(index, 1);
						} else {
							// Add New Favorite
							getFavs.push( newFav );
						}
						store.set( 'favorites', getFavs );

					} else {

						var arrayFav = [];
						arrayFav.push( elementCLick.attr('data-fav-id') );
						store.set( 'favorites', arrayFav );

					}

					console.log( store.get('favorites') );

				<?php } ?>

			<?php } else { // Not Logged-In Visitor - Show Modal ?>
				jQuery('#msg-login-to-add-favorites').removeClass('hide');
				jQuery('a[href="#tab-login"]').tab('show');
				jQuery('#login-modal').modal();
				jQuery('#login-modal').on('hidden.bs.modal', function () {
					jQuery('#msg-login-to-add-favorites').addClass('hide');
				});
			<?php } ?>

		});
		</script>

	<?php
	}
}
add_action( 'wp_footer', 'tt_favorites_script', 21 );

/**
 * Favorites Temporary
 *
 */
if ( ! function_exists( 'tt_ajax_favorites_temporary' ) ) {
	function tt_ajax_favorites_temporary() {

		$favorites_temporary_args['post_type'] = 'property';
		$favorites_temporary_args['post_status'] = 'publish';
		$favorites_temporary_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		global $realty_theme_option;
		$search_results_per_page = $realty_theme_option['search-results-per-page'];

		// Search Results Per Page: Check for Theme Option
		if ( $search_results_per_page ) {
			$favorites_temporary_args['posts_per_page'] = $search_results_per_page;
		} else {
			$favorites_temporary_args['posts_per_page'] = 10;
		}

		if ( isset( $_GET['favorites'] ) ) {
			$favorites_temporary_args['post__in'] = $_GET['favorites'];
		} else {
			$favorites_temporary_args['post__in'] = array( '0' );
		}

		$query_favorites_temporary = new WP_Query( $favorites_temporary_args );
		?>

		<?php if ( $query_favorites_temporary->have_posts() ) : ?>
			<div class="property-items">
				<ul class="row list-unstyled">
					<?php
						$count_results = $query_favorites_temporary->found_posts;
						while ( $query_favorites_temporary->have_posts() ) : $query_favorites_temporary->the_post();
						global $realty_theme_option;
						$columns = $realty_theme_option['property-listing-columns'];
						if ( empty($columns) ) {
							$columns = "col-md-6";
						}
					?>
						<li class="<?php echo $columns; ?>">
							<?php get_template_part( 'lib/inc/template/property', 'item' );	?>
						</li>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</ul>
			</div>

		<?php endif; ?>

		<?php
		die();

	}
}
add_action('wp_ajax_tt_ajax_favorites_temporary', 'tt_ajax_favorites_temporary');
add_action('wp_ajax_nopriv_tt_ajax_favorites_temporary', 'tt_ajax_favorites_temporary');


add_action( 'wp_footer', 'tt_favorite_modal', 21 );
function tt_favorite_modal(){
	?>
	<div class="modal fade modal-custom" id="favorite-multiple-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
				<span class="linericon-cross" aria-hidden="true">X</span>
			</button>
			<div class="modal-header">
				<h4 class="modal-title" ><?php echo __('Favorite List', 'realty')?></h4>
			</div>
			<div class="modal-body">
				<?php echo buildListFavoriteProperty(true, true);?>
				 
				<div class="button_groups">
					  <a class="btn btn-success" id="contact-inquiry" href="<?php echo pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en')?>"><?php echo trans_text('Contact all checked offices')?></a>
					  <button type="button" class="btn btn-danger btn-close"  data-dismiss="modal"><?php echo trans_text('Close')?></button>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
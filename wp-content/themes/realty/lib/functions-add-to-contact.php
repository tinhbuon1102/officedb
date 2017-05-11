<?php
function get_contact_property_list($user_id = false){
	$user = get_currentuserinfo();
	$user_id = $user->ID;
	
	$propertyIdList = get_user_meta($user_id, 'realty_user_contact', true);
	if (!$propertyIdList) return array();
	
	$args = array(
		'post_type' => 'property',
		'posts_per_page' => -1,
		'post__in' => $propertyIdList
	);
	
	$properties = get_posts($args);
	
	$tableFloors = array();
	foreach ($properties as $property_index => $property) {
		$single_property_id = $property->ID;
		$building = get_post_meta($single_property_id, BUILDING_TYPE_CONTENT, true);
		$floor = get_post_meta($single_property_id, FLOOR_TYPE_CONTENT, true);
		$google_maps = get_post_meta( $single_property_id, 'estate_property_google_maps', true );
	
		$tableFloors[$property_index]['thumbnail'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . get_the_post_thumbnail($single_property_id, 'thumbnail') . '</a>';
		$tableFloors[$property_index]['name'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . $property->post_title . '</a>';
		$tableFloors[$property_index]['size'] = translateBuildingValue('area_ping', $building, $floor, $single_property_id);
		$tableFloors[$property_index]['address'] = $google_maps['address'];
		$tableFloors[$property_index]['rent_unit_price'] = $floor['rent_unit_price'] ? renderPrice($floor['rent_unit_price']) : translateBuildingValue('rent_unit_price_opt', $building, $floor, $single_property_id);
		$tableFloors[$property_index]['deposit'] = renderPrice($floor['total_deposit']);
		$tableFloors[$property_index]['date_move'] = translateBuildingValue('move_in_date', $building, $floor, $single_property_id);
	}
	return $tableFloors;
}

function buildListContactProperty(){
	$tableFloors = get_contact_property_list();
	$tableHtml = '';
	if (!empty($tableFloors)) {
		ob_start();
		?>
	<h4><?php echo trans_text('With list of properties below :')?></h4>
	<input type="hidden" name="send_multiple" value="1"/>
	<table id="contact_list_later">
		<thead>
			<tr>
				<th class="floor_picture"><?php echo trans_text('Picture')?></th>
				<th class="floor_name"><?php echo trans_text('Name')?></th>
				<th class="floor_rent"><?php echo trans_text('Rent')?></th>
				<th class="floor_area"><?php echo trans_text('Area')?></th>
				<th class="floor_deposit"><?php echo trans_text('Total deposit')?></th>
				<th class="floor_date_move"><?php echo trans_text('Date of occupancy')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($tableFloors as $floor) {?>
		<tr class="contact_item">
			<td class="floor_picture"><?php echo $floor['thumbnail']?></td>
			<td class="floor_name"><?php echo $floor['name']?></td>
			<td class="floor_rent"><?php echo $floor['rent_unit_price']?></td>
			<td class="floor_area"><?php echo $floor['size']?></td>
			<td class="floor_deposit"><?php echo $floor['deposit']?></td>
			<td class="floor_date_move"><?php echo $floor['date_move']?></td>
		</tr>
		<?php }?>
		</tbody>
	</table>
<?php 
	$tableHtml = ob_get_contents();
	ob_end_clean();
	}
	return $tableHtml;
}
/**
 * AJAX - contact
*
*/
if ( ! function_exists( 'tt_ajax_add_remove_contact' ) ) {
	function tt_ajax_add_remove_contact() {

		$user_id = $_GET['user'];
		$property_id = $_GET['property'];

		$property_translations = pll_get_post_translations( $property_id );
		
		foreach ($property_translations as $property_id) {
			// Get contact Meta Data
			$get_user_meta_contact = get_user_meta( $user_id, 'realty_user_contact', false ); // false = array()
	
			if ( ! $get_user_meta_contact ) {
				// No User Meta Data contact Found -> Add Data
				$create_contact = array($property_id);
				add_user_meta( $user_id, 'realty_user_contact', $create_contact );
			} else {
				// Meta Data Found -> Update Data
				if ( ! in_array( $property_id, $get_user_meta_contact[0] ) ) {
					// Add New Favorite
					array_unshift( $get_user_meta_contact[0], $property_id ); // Add To Beginning Of contact Array
					update_user_meta( $user_id, 'realty_user_contact', $get_user_meta_contact[0] );
				} else {
					// Remove Favorite
					$removeFavoriteFromPosition = array_search( $property_id, $get_user_meta_contact[0] );
					unset($get_user_meta_contact[0][$removeFavoriteFromPosition]);
					update_user_meta( $user_id, 'realty_user_contact', $get_user_meta_contact[0] );
				}
			}
		}

		$tableFloors = get_contact_property_list($user_id);
		echo json_encode(array('floors' => $tableFloors)); die;
	}
}
add_action( 'wp_ajax_tt_ajax_add_remove_contact', 'tt_ajax_add_remove_contact' );

/**
 * contact - Click
 *
 */
if ( !function_exists('tt_add_remove_contact') ) {
	function tt_add_remove_contact( $property_id = 0, $is_custom = '' ) {

		global $realty_theme_option;

		$add_contact_temporary = true;

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		$favicon = '%s';
		if ( is_user_logged_in() ) {
			// Logged-In User
			$user_id = get_current_user_id();
			$get_user_meta_contact = get_user_meta( $user_id, 'realty_user_contact', false ); // false = array()

			if ( ! empty( $get_user_meta_contact ) && in_array( $property_id, $get_user_meta_contact[0] ) ) {
				// Property Is Already In contact
				$class = 'add-to-contact origin icon-heart';
				$text = __( 'Remove From contact', 'realty' );
			} else {
				// Property Isn't In contact
				$class = 'add-to-contact origin icon-heart-1';
				$text = __( 'Add To contact', 'realty' );
			}
		} else {
			// Not Logged-In Visitor
			$class = 'add-to-contact origin icon-heart-1';
			$text = __( 'Add To contact', 'realty' );
		}

		$favicon = '<a href="javascript:void(0);" class="btn btn-primary btn-square btn-line-border add-to-contact_wraper">%s<span>'.$text.'</span></a>';

		return sprintf($favicon, '<i class="'.$class.'" data-fav-id="' . $property_id . '" data-toggle="tooltip" data-remove-title="'.__( 'Remove From contact', 'realty' ).'" data-add-title="'.__( 'Add To contact', 'realty' ).'" title="' . $text . '"></i>');

	}
}

/**
 * contact - Script
 *
 */
if ( ! function_exists( 'tt_contact_script' ) ) {
	function tt_contact_script() {

		global $realty_theme_option;
		$add_contact_temporary = $realty_theme_option['property-contact-temporary'];
		?>

		<script>
		<?php
		// Temporary contact
		if ( ! is_user_logged_in() && $realty_theme_option['property-contact-temporary'] ) {
		?>
		jQuery('.add-to-contact').each(function() {

			// Check If item Already In contact Array
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
			if ( inArray( jQuery(this).attr('data-fav-id'), store.get('contact') ) ) {
				if (!jQuery(this).hasClass('custom-contact'))
				{
					jQuery(this).toggleClass('icon-heart icon-heart-1');
				}
			}

		});
		<?php } ?>

		
		jQuery('body').on("click",'.add-to-contact_wraper',function(e) {
			e.preventDefault();
			jQuery(this).find('.add-to-contact').click();
		});
		
		jQuery('body').on("click",'.add-to-contact',function(e) {
	        e.stopPropagation();
	        var show_popup = false;

			<?php
			// Logged-In User Or Temporary contact Enabled
			if ( is_user_logged_in() || $add_contact_temporary ) {
			?>

				// Toggle contact Tooltips
				var title;
				
				jQuery('i.add-to-contact').toggleClass('icon-heart icon-heart-1');

				if (jQuery('a.add-to-contact_wraper i.add-to-contact').hasClass('icon-heart'))
				{
					title = jQuery('a.add-to-contact_wraper i.add-to-contact').attr('data-remove-title');
					show_popup = true;	
				}
				else if (jQuery('a.add-to-contact_wraper i.add-to-contact').hasClass('icon-heart-1'))
				{
					title = jQuery('a.add-to-contact_wraper i.add-to-contact').attr('data-add-title');
					show_popup = false;
				}
				
				jQuery('i.add-to-contact').attr('data-original-title', title);
				jQuery('a.add-to-contact_wraper span').text(title);
				jQuery('a.add-to-contact_wraper').attr('title', title);

				<?php if ( is_user_logged_in() ) { ?>
					<?php $user_id = get_current_user_id();	?>

					if (show_popup)
					{
						jQuery('body').LoadingOverlay("show");
					}
					
					jQuery.ajax({
					  type: 'GET',
					  url: ajax_object.ajax_url,
					  dataType: 'json',
					  data: {
					    'action'        :   'tt_ajax_add_remove_contact', // WP Function
					    'user'					: 	<?php echo $user_id; ?>,
					    'property'			: 	jQuery(this).attr('data-fav-id')
					  },
					  success: function (response) {
						if (show_popup)
						{
							jQuery('body').LoadingOverlay("hide");
							// show popup
							jQuery('#contact-multiple-modal').modal('show');

							var floors = response.floors;
							jQuery('#contact_list_later .contact_item').remove();
							if (floors.length)
							{
								jQuery.each(floors, function(floor_index, floor){
									var floor_row = jQuery('tr.contact_item_tmp').clone();
									floor_row.removeClass('contact_item_tmp element-disable');
									floor_row.addClass('contact_item');
									floor_row.find('.floor_picture').html(floor.thumbnail);
									floor_row.find('.floor_name').html(floor.name);
									floor_row.find('.floor_rent').html(floor.rent_unit_price);
									floor_row.find('.floor_area').html(floor.size);
									floor_row.find('.floor_deposit').html(floor.deposit);
									floor_row.find('.floor_date_move').html(floor.date_move);

									console.log(floor_row);

									jQuery('#contact_list_later').append(floor_row);
								});
							}
						}
					  },
					  error: function () { jQuery('body').LoadingOverlay("hide"); }
					});

					<?php } else if ( $add_contact_temporary ) { ?>

					if (!store.enabled) {
				    console.log('<?php echo __( 'Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.', 'realty' ); ?>');
						return;
				  }

					// Check For Temporary contact (store.js plugin)
					if ( store.get('contact') ) {

						// Check If item Already In contact Array
						function inArray(needle, haystack) {
					    var length = haystack.length;
					    for( var i = 0; i < length; i++ ) {
				        if(haystack[i] == needle) return true;
					    }
					    return false;
						}

						var getFavs = store.get('contact');
						var newFav = jQuery(this).attr('data-fav-id');

						if ( inArray( newFav, getFavs ) ) {
							// Remove Old Favorite
							var index = getFavs.indexOf(newFav);
							getFavs.splice(index, 1);
						} else {
							// Add New Favorite
							getFavs.push( newFav );
						}
						store.set( 'contact', getFavs );

					} else {

						var arrayFav = [];
						arrayFav.push( jQuery(this).attr('data-fav-id') );
						store.set( 'contact', arrayFav );

					}

					console.log( store.get('contact') );

				<?php } ?>

			<?php } else { // Not Logged-In Visitor - Show Modal ?>
				jQuery('#msg-login-to-add-contact').removeClass('hide');
				jQuery('a[href="#tab-login"]').tab('show');
				jQuery('#login-modal').modal();
				jQuery('#login-modal').on('hidden.bs.modal', function () {
					jQuery('#msg-login-to-add-contact').addClass('hide');
				});
			<?php } ?>

		});
		</script>

	<?php
	}
}
add_action( 'wp_footer', 'tt_contact_script', 21 );

/**
 * contact Temporary
 *
 */
if ( ! function_exists( 'tt_ajax_contact_temporary' ) ) {
	function tt_ajax_contact_temporary() {

		$contact_temporary_args['post_type'] = 'property';
		$contact_temporary_args['post_status'] = 'publish';
		$contact_temporary_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		global $realty_theme_option;
		$search_results_per_page = $realty_theme_option['search-results-per-page'];

		// Search Results Per Page: Check for Theme Option
		if ( $search_results_per_page ) {
			$contact_temporary_args['posts_per_page'] = $search_results_per_page;
		} else {
			$contact_temporary_args['posts_per_page'] = 10;
		}

		if ( isset( $_GET['contact'] ) ) {
			$contact_temporary_args['post__in'] = $_GET['contact'];
		} else {
			$contact_temporary_args['post__in'] = array( '0' );
		}

		$query_contact_temporary = new WP_Query( $contact_temporary_args );
		?>

		<?php if ( $query_contact_temporary->have_posts() ) : ?>
			<div class="property-items">
				<ul class="row list-unstyled">
					<?php
						$count_results = $query_contact_temporary->found_posts;
						while ( $query_contact_temporary->have_posts() ) : $query_contact_temporary->the_post();
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
add_action('wp_ajax_tt_ajax_contact_temporary', 'tt_ajax_contact_temporary');
add_action('wp_ajax_nopriv_tt_ajax_contact_temporary', 'tt_ajax_contact_temporary');
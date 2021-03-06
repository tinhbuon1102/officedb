<?php
function get_contact_property_list($user_id = false, $meta_key = 'realty_user_contact'){
	$user = get_currentuserinfo();
	$user_id = $user->ID;
	
	if (isset($_REQUEST['id']))
	{
		$propertyIdList = explode(',', $_REQUEST['id']);
	}
	else 
		$propertyIdList = get_user_meta($user_id, $meta_key, true);
	
	update_user_meta( $user_id, 'realty_user_contact', $propertyIdList );
		
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
		
		$building = getBuilding($single_property_id);
		$floor = getFloor($single_property_id);
		
		$google_maps = get_post_meta( $single_property_id, 'estate_property_google_maps', true );
	
		$tableFloors[$property_index]['thumbnail'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . get_the_post_thumbnail($single_property_id, 'thumbnail') . '</a>';
		$tableFloors[$property_index]['name'] = '<a target="_blank" href="'.get_permalink($single_property_id).'">' . $property->post_title . '</a>';
		$tableFloors[$property_index]['size'] = translateBuildingValue('area_ping', $building, $floor, $single_property_id);
		$tableFloors[$property_index]['address'] = $google_maps['address'];
		$tableFloors[$property_index]['rent_unit_price'] = $floor['rent_unit_price'] ? renderPrice($floor['rent_unit_price']) : translateBuildingValue('rent_unit_price_opt', $building, $floor, $single_property_id);
		$tableFloors[$property_index]['deposit'] = renderPrice($floor['total_deposit']);
		$tableFloors[$property_index]['date_move'] = translateBuildingValue('move_in_date', $building, $floor, $single_property_id);
		$tableFloors[$property_index]['property_id'] = $single_property_id;
	}
	return $tableFloors;
}

function buildListContactProperty($show_remove = false, $is_modal = false){
	$tableFloors = get_contact_property_list();
	$tableHtml = '';
	if (!empty($tableFloors) || $is_modal) {
		ob_start();
		?>
	<h4><?php echo trans_text('With list of properties below :')?></h4>
	<table class="contact_list_later">
		<thead>
			<tr>
				<th class="floor_picture" colspan="2"><?php echo trans_text('Property Name')?></th>
				<th class="floor_rent"><?php echo trans_text('Rent')?></th>
				<th class="floor_area"><?php echo trans_text('Area')?></th>
				<!--<th class="floor_deposit"><?php //echo trans_text('Total deposit')?></th>-->
				<th class="floor_date_move"><?php echo trans_text('Date of occupancy')?></th>
				<?php if ($show_remove) {?>
				<th class="floor_action_remove"><?php echo trans_text('Remove')?></th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($tableFloors as $floor) {?>
		<tr class="contact_item">
			<td class="floor_picture"><?php echo $floor['thumbnail']?></td>
			<td class="floor_name"><?php echo $floor['name']?></td>
			<td class="floor_rent"><?php echo $floor['rent_unit_price']?></td>
			<td class="floor_area"><?php echo $floor['size']?></td>
			<!--<td class="floor_deposit"><?php //echo $floor['deposit']?></td>-->
			<td class="floor_date_move"><?php echo $floor['date_move']?></td>
			<?php if ($show_remove) {?>
			<td class="floor_action_remove"><a href="javascript:void(0)" class="remove_property add-to-contact" data-fav-id="<?php echo $floor['property_id']?>" ><?php echo trans_text('Remove')?></a></td>
			<?php }?>
		</tr>
		<?php }?>
		<tr class="contact_item_tmp element-disable" style="display:none">
			<td class="floor_picture"></td>
			<td class="floor_name"></td>
			<td class="floor_rent"></td>
			<td class="floor_area"></td>
			<!--<td class="floor_deposit"></td>-->
			<td class="floor_date_move"></td>
			<?php if ($show_remove) {?>
			<td class="floor_action_remove"><a href="javascript:void(0)" class="remove_property add-to-contact" ><?php echo trans_text('Remove')?></a></td>
			<?php }?>
		</tr>
		</tbody>
	</table>
	<?php if (!count($tableFloors)) {?>
	<style>
		.contact_list_later, .modal-body .button_groups .btn-success {display: none;}
	</style>
	<p class="contact_item no-contact-list alert alert-info"><?php esc_html_e( 'There is no added properties', 'realty' ); ?></p>
	<?php }?>
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
		$tmpClass = 'fa-star';
		if ( is_user_logged_in() ) {
			// Logged-In User
			$user_id = get_current_user_id();
			$get_user_meta_contact = (array)get_user_meta( $user_id, 'realty_user_contact', false ); // false = array()

			if ( ! empty( $get_user_meta_contact ) && isset($get_user_meta_contact[0]) && is_array($get_user_meta_contact[0]) && in_array( $property_id, $get_user_meta_contact[0] ) ) {
				// Property Is Already In contact
				$class = 'add-to-contact origin fa '.$tmpClass.' ' . CONTACT_ICON_SELECTED;
				$text = __( 'Contact This Office', 'realty' );
			} else {
				// Property Isn't In contact
				$class = 'add-to-contact origin fa '.$tmpClass.' ' . CONTACT_ICON_NOT_SELECTED;
				$text = __( 'Contact This Office', 'realty' );
			}
		} else {
			// Not Logged-In Visitor
			$class = 'add-to-contact origin fa '.$tmpClass.' ' . CONTACT_ICON_NOT_SELECTED;
			$text = __( 'Contact This Office', 'realty' );
		}

		$inquiryUrl = pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en');
		$inquiryUrl .=  '?id='. $property_id;
		
		if ($is_custom){
			$favicon = '<a target="_blank" href="'. $inquiryUrl .'" class="btn btn-primary btn-square btn-lg add-to-contact_wraper1" id="contact_list_button">%s<span>'.$text.'</span></a>';
		}
		else{
			$favicon = '<a target="_blank" href="'. $inquiryUrl .'" >%s</a>';
		}
		return sprintf($favicon, '<i class="'.$class.'" data-fav-id="' . $property_id . '" data-toggle="tooltip" data-remove-title="'.__( 'Contact This Office', 'realty' ).'" data-add-title="'.__( 'Contact This Office', 'realty' ).'" title="' . $text . '"></i>');

	}
}

add_action( 'wp_footer', 'tt_contact_script', 21 );
if ( ! function_exists( 'tt_contact_script' ) ) {
	function tt_contact_script() {

		global $realty_theme_option;
		$add_contact_temporary = isset($realty_theme_option['property-contact-temporary']) ? $realty_theme_option['property-contact-temporary'] : '';
		?>

		<script>
		<?php
		// Temporary contact
		if ( ! is_user_logged_in() && $realty_theme_option['property-contact-temporary'] ) {
		?>
		jQuery('.floor_action_remove .add-to-contact').each(function() {

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
					jQuery(this).toggleClass('<?php echo CONTACT_ICON_SELECTED?> <?php echo CONTACT_ICON_NOT_SELECTED?>');
				}
			}

		});
		<?php } ?>

		
		jQuery('body').on("click",'.add-to-contact_wraper',function(e) {
			e.preventDefault();
			jQuery(this).find('.add-to-contact').click();
		});
		
		jQuery('body').on("click",'.floor_action_remove .add-to-contact',function(e) {
	        e.stopPropagation();
	        var show_popup = false;
	        var elementCLick = jQuery(this);
	        var property_id = elementCLick.attr('data-fav-id');
	        

			<?php
			// Logged-In User Or Temporary contact Enabled
			if ( is_user_logged_in() || $add_contact_temporary ) {
			?>

				// Toggle contact Tooltips
				var title;
				var is_remove = false;
				
				jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').toggleClass('<?php echo CONTACT_ICON_SELECTED?> <?php echo CONTACT_ICON_NOT_SELECTED?>');

				if (jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').hasClass('<?php echo CONTACT_ICON_SELECTED?>') || elementCLick.hasClass('remove_property'))
				{
					title = jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').attr('data-remove-title');
					show_popup = true;	
				}
				else if (jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').hasClass('<?php echo CONTACT_ICON_NOT_SELECTED?>'))
				{
					title = jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').attr('data-add-title');
					show_popup = false;
					is_remove = true;
				}
				
				jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').attr('data-original-title', title);

				if (jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').closest('a.add-to-contact_wraper').length) {
					jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').closest('a.add-to-contact_wraper').find('span').text(title);
					jQuery('i.add-to-contact[data-fav-id="'+property_id+'"]').closest('a.add-to-contact_wraper').attr('title', title);
				}

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
						var floors = response.floors;
						
						// Update header count
						jQuery('.contact-list-count').html(floors.length);
						
						if (show_popup)
						{
							jQuery('body').LoadingOverlay("hide");
							// show popup
							if (!elementCLick.closest('form.shortcode-form').length)
							{
								jQuery('#contact-multiple-modal').modal('show');
							}
							jQuery('.contact_item').remove();

							if (floors.length)
							{
								jQuery('.contact_list_later').show();
								jQuery('.modal-body .button_groups .btn-success').css('display', 'inline-block');
								jQuery('.no-contact-list').remove();
								
								jQuery.each(floors, function(floor_index, floor){
									var floor_row = jQuery('tr.contact_item_tmp:eq(0)').clone();
									floor_row.removeClass('contact_item_tmp element-disable');
									floor_row.show();
									floor_row.addClass('contact_item');
									floor_row.find('.floor_picture').html(floor.thumbnail);
									floor_row.find('.floor_name').html(floor.name);
									floor_row.find('.floor_rent').html(floor.rent_unit_price);
									floor_row.find('.floor_area').html(floor.size);
									floor_row.find('.floor_deposit').html(floor.deposit);
									floor_row.find('.floor_date_move').html(floor.date_move);
									floor_row.find('.floor_action_remove a').attr('data-fav-id', floor.property_id);

									jQuery('.contact_list_later').append(floor_row);
								});
							}
							else {
								jQuery('.contact_list_later').hide();
								jQuery('.modal-body .button_groups .btn-success').hide();
								jQuery('.contact_item').remove();
								
								jQuery('.contact_list_later').before('<p class="contact_item no-contact-list alert alert-info"><?php esc_html_e( 'There is no added properties', 'realty' ); ?></p>');
								jQuery('#contact-multiple-modal').modal('hide');
								if (elementCLick.closest('form.shortcode-form').length)
								{
									$('.contact_list_later').fadeOut();
								}
							}
						}
						else{
							if (is_remove)
							{
								jQuery('.remove_property.add-to-contact[data-fav-id="'+property_id+'"]').closest('tr').remove();
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
add_action('wp_ajax_tt_ajax_contact_temporary', 'tt_ajax_contact_temporary');
add_action('wp_ajax_nopriv_tt_ajax_contact_temporary', 'tt_ajax_contact_temporary');

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

// add_action( 'wp_footer', 'tt_contact_modal', 21 );

function tt_contact_modal(){
?>
	<div class="modal fade modal-custom" id="contact-multiple-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
				<span class="linericon-cross" aria-hidden="true">X</span>
			</button>
			<div class="modal-header">
				<h4 class="modal-title" ><?php echo __('Contact List', 'realty')?></h4>
			</div>
			<div class="modal-body">
				<?php echo buildListContactProperty(true, true);?>
				 
				<div class="button_groups">
					  <a class="btn btn-success" href="<?php echo pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en')?>"><?php echo trans_text('Contact Now')?></a>
					  <button type="button" class="btn btn-danger"  data-dismiss="modal"><?php echo trans_text('Close')?></button>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
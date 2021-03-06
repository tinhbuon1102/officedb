<?php get_header(); ?>
<?php
	global $realty_theme_option;

	$current_language = pll_current_language();
	if ( $realty_theme_option['property-show-login-users'] && ! is_user_logged_in() ) {
		$show_property = false;
	} else {
		$show_property = true;
	}

	// Check: Are we trying to retrieve page template "Single Property Home Page" or single-property.php
	if ( isset( $page_template_single_property ) && ! empty( $page_template_single_property ) ) {
		// "Single Property Home Page"
		$single_property_id = $page_template_single_property;
	} else {
		// Single Property
		$single_property_id = $post->ID;
	}
?>
<style>
.property-image-container, .property-image-container .property-item, .property-image-container .loader-container {
    height: 305px;
}
</style>
<?php if ( $show_property ) { ?>

	<?php
		$property_location = get_the_terms( $single_property_id, 'property-location' );
		$property_status = get_the_terms( $single_property_id, 'property-status' );
		$property_type = get_the_terms( $single_property_id, 'property-type' );

		$date_format = get_option( 'date_format' );
		$today = current_time( $date_format );
		$last_updated_on = date_i18n( get_option( 'date_format' ), strtotime( $post->post_modified ) );

		$available_from = get_post_meta( $single_property_id, 'estate_property_available_from', true );
		$available_from_date = date_i18n(get_option( 'date_format' ), strtotime( $available_from ) );
		if ( ! empty( $available_from ) ) {
			$available_from_meta = get_field_object( 'estate_property_available_from', $single_property_id );
			$available_from_label = $available_from_meta['label'];
		}

		$single_property_layout = get_post_meta( $single_property_id, 'estate_property_layout', true );
		$property_status_update = get_post_meta( $single_property_id, 'estate_property_status_update', true );
		$property_video_provider = get_post_meta( $single_property_id, 'estate_property_video_provider', true );
		$property_video_id = get_post_meta( $single_property_id, 'estate_property_video_id', true );
		$property_images = get_post_meta( $single_property_id, 'estate_property_gallery', true );
		
		$featured = get_post_meta( $single_property_id, 'estate_property_featured', true );
		$property_id = get_post_meta( $single_property_id, 'estate_property_id', true );

		$price = intval( get_post_meta( $single_property_id, 'estate_property_price', true ) );

		$size = get_post_meta( $single_property_id, 'estate_property_size', true );
		$size_unit = get_post_meta( $single_property_id, 'estate_property_size_unit', true );
		if ( ! empty( $size ) ) {
			$size_meta = get_field_object( 'estate_property_size', $single_property_id );
			$size_label = $size_meta['label'];
		}

		$rooms = get_post_meta( $single_property_id, 'estate_property_rooms', true );
		if ( ! empty( $rooms ) ) {
			$rooms_meta = get_field_object('estate_property_rooms', $single_property_id);
			$rooms_label = $rooms_meta['label'];
		}

		$bedrooms = get_post_meta( $single_property_id, 'estate_property_bedrooms', true );
		if ( ! empty( $bedrooms ) ) {
			$bedrooms_meta = get_field_object( 'estate_property_bedrooms', $single_property_id );
			$bedrooms_label = $bedrooms_meta['label'];
		}

		$bathrooms = get_post_meta( $single_property_id, 'estate_property_bathrooms', true );
		if ( ! empty ( $bathrooms ) ) {
			$bathrooms_meta = get_field_object( 'estate_property_bathrooms', $single_property_id );
			$bathrooms_label = $bathrooms_meta['label'];
		}

		$garages = get_post_meta( $single_property_id, 'estate_property_garages', true );
		if ( ! empty( $garages ) ) {
			$garages_meta = get_field_object( 'estate_property_garages', $single_property_id );
			$garages_label = $garages_meta['label'];
		}

		$property_contact_information = get_post_meta( $single_property_id, 'estate_property_contact_information', true );
		$property_header_meta_type = '';
		$property_header_meta_status = '';
		if ( $property_type ) {
			foreach ( $property_type as $type ) {
				$property_header_meta_type =  $type->name;
				break;
			}
		}
		if ( $property_status ) {
			foreach ( $property_status as $status ) {
				$property_header_meta_status = ' &middot; ' . $status->name;
				break;
			}
		}

		$google_maps = get_post_meta( $single_property_id, 'estate_property_google_maps', true );
		// Get building info
		$building = getBuilding($single_property_id);
		$building_id = $building['building_id'];
		
		//Get floor info
		$floor = getFloor($single_property_id);
		
		// Get Plan
		$planPictureResults = $wpdb->get_results("SELECT * FROM plan_picture WHERE building_id=".(int)$building_id . ' AND plan_picture_id=' . (int)$building['plan_standard_id']);
		$plan_images = array();
		if (!empty($planPictureResults))
		{
			foreach ($planPictureResults as $planPictureResult)
			{
				$plan_images[] = $planPictureResult->name;
			}
			$plan_images = getBuildingFloorPicUrl($plan_images, 'plan');
		}
		// Get PDF
		$pdfUrl = getBuildingPDF($building_id);
		
		// Get list same building
		$buildingArgs = get_floors_by_building($building_id);
		$query_floors_results = new WP_Query($buildingArgs);
		
		$i_vacancy_floor = 0;
		while ( $query_floors_results->have_posts() ) : $query_floors_results->the_post();
			$related_property_id = get_the_ID();
			$related_floor = getFloor($related_property_id);
			// out if floor has no vacant
			if (!$related_floor['vacancy_info']) continue;
			else $i_vacancy_floor ++;
		endwhile;
		
		wp_reset_postdata();
		
		
		$property_layout = $realty_theme_option['property-layout'];
		$property_meta_data_type = $realty_theme_option['property-meta-data-type'];
		$social_sharing = $realty_theme_option['property-social-sharing'];
		$property_contact_form_default_email = $realty_theme_option['property-contact-form-default-email'];
		$property_image_height = $realty_theme_option['property-image-height'];
		$property_image_width = $realty_theme_option['property-image-width'];

		if ( ! isset( $property_image_width ) ) {
			$property_image_width = "full";
		}

		if ( $realty_theme_option['property-lightbox'] != "none" ) {
			$property_zoom = ' zoom';
		} else {
			$property_zoom = null;
		}

		$fit_or_cut = null;

		if ( $single_property_layout == 'theme_option_setting' || $single_property_layout == '' ) {
			if ( $property_layout == 'layout-full-width' ) {
				$layout = 'full-width';
			} else {
				$layout = 'boxed';
			}
		} else {
			if ( $single_property_layout == 'full_width' ) {
				$layout = 'full-width';
			} else {
				$layout = 'boxed';
			}
		}

		function wp_get_attachment_meta_data_title() {
			$attachment = get_post( get_post_thumbnail_id() );
			return $attachment->post_title;
		}
	?>

	<?php //if ( $layout == 'boxed' ) { ?>
		<div class="container">
	<?php //} ?>
	<div class="head-single">
	<div class="row">
	<div class="col-sm-7 col-md-9">
	<h1 class="bld-title">
	<span><?php echo ($i_vacancy_floor) ? get_the_title( $single_property_id ) : get_post_meta($single_property_id, 'post_title_building', true); ?></span>
	<?php if ( $property_status_update ) { ?>
						<span class="labeled"><div class="btn btn-dark btn-sm status-update"><?php echo $property_status_update; ?></div></span>
					<?php } ?>
	</h1>
	</div><!-- .col-sm-8 -->
	<?php
					$estate_property_id = '';

					if ( $realty_theme_option['property-id-type'] == "post_id" ) {
					  $estate_property_id = $single_property_id;
					} else {
					  $estate_property_id = $property_id;
					}
				?>

	      <?php if ( ! empty ( $estate_property_id ) ) { ?>
					<div class="col-sm-5 col-md-3 contact-id">
					<div class="property-meta id-meta">
						<?php echo trans_text('Contact Number')?>: <div class="meta-title"><?php echo $floor['floorId']?></div>
					</div>
					</div>
			  <?php } ?>

			
	</div><!--.row-->
	</div><!-- .head-single -->
	<div class="row"  id="single_property_wraper">
	<div class="col-sm-5">
<div class="property-slider-wrap clearfix">
<div class="property-header-container hidden-pc">

			<?php if ( $layout == "full-width" ) { ?>
			<?php } ?>

				<div class="property-header">
					<h1 class="title">
						<div class="clearfix mobile"></div>
						<div class="acicon-wrap">
						
						<span class="ac-icon-list last"><a href="#location_map" class="ac-cell"><i class="icon-pin-full" data-toggle="tooltip" title="<?php esc_html_e( 'Show Location', 'realty' );  ?>"></i></a></span>
						
								
			<?php 
		    if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false );

			if ( ! empty( $get_user_meta_follow ) && in_array( $single_property_id, $get_user_meta_follow[0] ) ) {
			?>
						<!--<span class="title_inner" id="single_subscribe_text"><?php //echo __('Unsubscribe From Email Updates', 'realty')?></span>-->
						<?php } else { ?>
						<!--<span class="title_inner" id="single_subscribe_text"><?php //echo __('Subscribe To Email Updates', 'realty')?></span>-->
						<?php } ?>
						<?php } else { ?>
						<!--<span class="title_inner" id="single_subscribe_text"><?php //echo __('Subscribe To Email Updates', 'realty')?></span>-->
						<?php } ?>
						</span></span>
						<?php echo tt_icon_property_video( $single_property_id ); ?>
						</div><!--/acicon-wrap-->
					</h1>
					<div class="clearfix"></div>
				</div>
			<?php if ( $layout == "full-width" ) { ?>
			<?php } ?>

		</div><!-- .property-header-container -->
	<div id="property-layout-<?php echo $layout; ?>">

		<?php
			$image_slider_id = 'property_image_slider';
		  include get_template_directory() . '/lib/inc/template/single-property-slideshow.php';
		?>
		

		

	</div>


	<?php if ( $realty_theme_option['property-slideshow-navigation-type'] == 'thumbnail' ) { ?>
		<!--<div class="container">-->
			<?php include get_template_directory() . '/lib/inc/template/single-property-slideshow-thumbnails.php'; ?>
		<!--</div>-->
	<?php } ?>
	<!-- section action buttons -->
		</div><!--/property-slider-wrap-->
		<section id="acbuttons">
			
			<?php if ( !is_user_logged_in() ) { ?>
			<div class="contact-argent">
				<a href="#contact_modal" data-toggle="modal" class="btn btn-primary btn-square btn-lg" id="contact_agent_button"><i class="topicon-icon-thinliner_mail"></i><?php echo __('Contact Argent', 'realty')?></a>
			</div>
			<?php } else { ?>
			<div class="contact-argent">
				<?php echo tt_add_remove_contact( $single_property_id, 'custom-contact' ) ?>
			</div>
			<?php }?>
			<div class="buttons-group row">
			
			<div class="col-sm-6">
			<?php echo tt_add_remove_favorites( $single_property_id, 'custom-fav' )?>
			</div>
			
			
			<?php if ($pdfUrl) {?>
			<div class="col-sm-6">
				<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo $pdfUrl ? $pdfUrl : '#'?>" target="_blank" class="btn btn-primary btn-square btn-line-border pdf-button"><i class="fa fa-file-pdf-o"></i><span><?php echo __('View PDF', 'realty')?></span></a>
				<?php else : ?>
				<a href="#" class="btn btn-primary btn-square btn-line-border" id="pdf_viewing_disable"><i class="iconthin-icon-thinliner_register-lock"></i><span><?php echo __('View PDF', 'realty')?></span></a>
				<?php endif; ?>
			</div>
			<?php }?>
			</div>
		</section>
	</div><!-- .col-sm-5 -->
	<div class="col-sm-7 fl-right">
	<?php
		$post_content = get_post_field( 'post_content', $single_property_id );
		if ( $post_content ) {
		?>
			<section id="property-content">
				<?php
					$property_title_details = $realty_theme_option['property-title-details'];
					if ( $property_title_details ) {
						echo '<h3 class="section-title"><span>' . $property_title_details . '</span></h3>';
					}
				?>
				<?php do_action( 'tt_single_property_content_before' ); ?>
				<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $single_property_id ) ); ?>
				<?php do_action( 'tt_single_property_content_after' ); ?>
			</section>
		<?php } ?>
	<section id="property-summary">
	
	<h3 class="section-title"><span><?php echo __('Floor Information', 'realty')?></span></h3>
	<?php 
		if (!$floor['vacancy_info'] && $i_vacancy_floor) {?>
			<div class="warning_message vacancy_message" id="floor_no_vacant"><?php echo trans_text('This floor has no vacant')?></div>
		<?php }else if (!$i_vacancy_floor) {?>
			<div class="warning_message vacancy_message" id="building_no_vacant" ><?php echo trans_text('Currently fully occupied')?></div>
		<?php }?>
	<?php if ($floor['vacancy_info']) {?>
	<table id="floorsummary" class="basic-table-style">
		<tbody>
		<tr>
				<th><?php echo __('Floor', 'realty')?></th>
				<td>
				<?php echo translateBuildingValue('floor_up_down', $building, $floor, $single_property_id)?></td>
			</tr>
			<tr>
				<th><?php echo __('Area', 'realty')?></th>
				<td><?php echo translateBuildingValue('area_ping', $building, $floor, $single_property_id)?></td>
			</tr>
			
			<tr>
				<th><?php echo __('Rent', 'realty')?></th>
				<td>
				<!--if Floor[rent_unit_price_opt] is selected
				[Floor[rent_unit_price_opt]]
				else
				[Floor[rent_unit_price]] -->
				<?php echo $floor['rent_unit_price'] ? renderPropertyPrice($single_property_id, $building, $floor) : translateBuildingValue('rent_unit_price_opt', $building, $floor, $single_property_id);?>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Common service', 'realty')?></th>
				<td>
				<!--if Floor[unit_condo_fee_opt] is selected
				[Floor[unit_condo_fee_opt]]
				else
				[Floor[unit_condo_fee]]-->
				<?php echo translateBuildingValue('unit_condo_fee', $building, $floor, $single_property_id);?>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Total deposit', 'realty')?></th>
				<td><?php echo renderPrice($floor['total_deposit']);?></td>
			</tr>
			<tr>
				<th><?php echo __('Contract period', 'realty')?></th>
				<td><?php echo translateBuildingValue('contract_period_duration', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
				<th><?php echo __('Contract type', 'realty')?></th>
				<td><?php echo translateBuildingValue('contract_period', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
				<th><?php echo __('Date of occupancy', 'realty')?></th>
				<td><?php echo translateBuildingValue('move_in_date', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
				<th><?php echo __('Floor Material', 'realty')?></th>
				<td data-th="<?php echo __('Floor Material', 'realty')?>"><?php echo translateBuildingValue('oa_type', $building, $floor, $single_property_id);?> <?php echo translateBuildingValue('oa_height', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
				<th><?php echo __('Ceiling Height', 'realty')?></th>
				<td data-th="<?php echo __('Ceiling Height', 'realty')?>"><?php echo translateBuildingValue('ceiling_height', $building, $floor, $single_property_id);?></td>
			</tr>
		</tbody>
	</table>
	<p class="note-word"><?php if (isEnglish()) { echo __('*Common Area Management Fee', 'realty'); }?></p>
	
	<?php } ?>
	</section>
	<section id="nearbyrate">
	<h3 class="section-title"><span><?php echo __('Nearby rate info', 'realty')?></span></h3>
	<div class="rate size-large">
		<?php echo translateBuildingValue('avg_neighbor_fee', $building, $floor, $single_property_id);?>
	</div>
	<p class="note-rate">※<?php echo __('1坪辺りの共益費込み賃料の目安', 'realty')?></p>
	</section>
	<section id="property-details">
	<h3 class="section-title"><span><?php echo __('Property details', 'realty')?></span></h3>
	<table class="fixTable">
		<tbody>
		<tr>
				<th colspan="1"><?php echo __('Address', 'realty')?></th><td data-th="<?php echo __('Address', 'realty')?>" colspan="3"><?php echo $google_maps['address']?></td>
			</tr>
			<tr>
				<th colspan="1"><?php echo __('Traffic', 'realty')?></th>
				<td data-th="<?php echo __('Traffic', 'realty')?>" colspan="3">
					<?php 
					if ($building['stations']) {
					$i = 0;
					foreach ($building['stations'] as $station)
					{
						$stationLines[] = translateStationLine($station['line']);
						$station_name = $current_language == LANGUAGE_EN ? $station['name_en'] : $station['name'];
						$stationDistances[$station_name] = $station['time']; 
						$i ++;
						if ($i == 3) break;
					}
					echo implode(' / ', $stationLines);
					?>
					<br/>
					<ul class="list-stations">
						<?php foreach ($stationDistances as $station_name => $min) {?>
						<li><?php echo sprintf(trans_text('%s by foot : %sminutes'),trans_text($station_name), $min)?></li>
						<?php }?>
					</ul>
					<?php
					}
					?>
				</td>
			<tr>
				<th colspan="1"><?php echo __('Structure', 'realty')?></th>
				<td data-th="<?php echo __('Structure', 'realty')?>" colspan="3">
				<?php $scaleFloor = explode('-', $building['floor_scale']);?>
					<?php echo translateBuildingValue('construction_type_name', $building, $floor, $single_property_id)?>
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo __('Scale', 'realty')?></th>
				<td data-th="<?php echo __('Scale', 'realty')?>" colspan="3">
				<?php $scaleFloor = explode('-', $building['floor_scale']);?>
					<?php echo sprintf(trans_text('below ground %s'),(isset($scaleFloor[1]) && $scaleFloor[1]) ? $scaleFloor[1] : '-');?>
					<?php echo FIELD_MISSING_VALUE . ' ' . sprintf(trans_text('above ground %s'),(isset($scaleFloor[0]) && $scaleFloor[0]) ? $scaleFloor[0] : '-');?>
				</td>
			</tr>
			<tr>
			<th><?php echo __('Established', 'realty')?></th>
			<td data-th="<?php echo __('Established', 'realty')?>"><?php echo translateBuildingValue('built_year', $building, $floor, $single_property_id);?></td>
			<th><?php echo __('Gross floor area', 'realty')?></th>
			<td data-th="<?php echo __('Gross floor area', 'realty')?>"><?php echo translateBuildingValue('total_floor_space', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
			<th><?php echo __('Total Area', 'realty')?></th>
			<td data-th="<?php echo __('Total Area', 'realty')?>">
				<?php echo translateBuildingValue('total_rent_space_unit', $building, $floor, $single_property_id);?>
			</td>
			<th><?php echo __('Typical floor area', 'realty')?></th>
			<td data-th="<?php echo __('Typical floor area', 'realty')?>"><?php echo translateBuildingValue('std_floor_space', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
			<th><?php echo __('Earthquake proof', 'realty')?></th>
			<td data-th="<?php echo __('Earthquake proof', 'realty')?>"><?php echo translateBuildingValue('earth_quake_res_std', $building, $floor, $single_property_id);?></td>
			<th><?php echo __('Elevator', 'realty')?></th>
			<td data-th="<?php echo __('Elevator', 'realty')?>"><?php echo translateBuildingValue('elevator', $building, $floor, $single_property_id);?></td>
			</tr>
			<tr>
			<th><?php echo __('Parking', 'realty')?></th>
			<td data-th="<?php echo __('Parking', 'realty')?>"><?php echo translateBuildingValue('parking_unit_no', $building, $floor, $single_property_id);?></td>
			<th><?php echo __('Security', 'realty')?></th>
			<td data-th="<?php echo __('Security', 'realty')?>"><?php echo translateBuildingValue('security_id', $building, $floor, $single_property_id);?></td>
			</tr>
			
			<tr>
			<th colspan="1"><?php echo __('Renewal', 'realty')?></th>
			<td data-th="<?php echo __('Renewal', 'realty')?>" colspan="3"><?php echo translateBuildingValue('renewal_data', $building, $floor, $single_property_id);?></td>
			</tr>
		</tbody>
	</table>
	</section>
	<section id="floorPlan">
	<h3 class="section-title"><span><?php echo __('Floor Plan', 'realty')?></span></h3>
	<div class="floorplan-img">
		<?php foreach ($plan_images as $plan_image) {?>
			<img class="floor-plan" data-mfp-src="<?php echo $plan_image; ?>" src="<?php echo $plan_image; ?>" />
		<?php }?>
	</div>
	</section>
	
		
		
		<?php
			/**
			 * Section: Floor Plan
			 *
			 */
			$property_floor_plan_disable = $realty_theme_option['property-floor-plan-disable'];
			$property_floor_plans = get_field( 'estate_property_floor_plans', $single_property_id );
			if ( ! $property_floor_plan_disable && $property_floor_plans ) {
		?>
		<section id="floor-plan" class="primary-tooltips">
			<?php include get_template_directory() . '/lib/inc/template/single-property-floor-plan.php'; ?>
		</section>
		<?php }	?>
		
		<?php // List related building floors?>
		<?php if ($query_floors_results->have_posts() && $query_floors_results->post_count > 1) {?>
		<section id="vacant-list">
		<h3 class="section-title"><span><?php echo __('Vacancy info', 'realty')?></span></h3>
		<table id="vacantfloors" class="basic-table-style has-border">
		<thead>
			<tr>
				<th class="th_floor"><?php echo __('floor', 'realty')?></th>
				<th class="th_use"><?php echo __('use', 'realty')?></th>
				<th class="th_area"><?php echo __('Area', 'realty')?></th>
				<th class="th_dateoccupancy"><?php echo __('Date of occupancy', 'realty')?></th>
				<th class="th_view">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$count_related = 0;
			while ( $query_floors_results->have_posts() ) : $query_floors_results->the_post();
				global $post;
				$related_property_id = get_the_ID();
				
				// out if same as existing
				if ($related_property_id == $single_property_id) continue;
				
				$related_floor = getFloor($related_property_id);
				
				// out if floor has no vacant
				if (!$related_floor['vacancy_info']) continue;
				
				$count_related ++;
			?>
			<tr>
				<td class="td_floor">
					<?php echo translateBuildingValue('floor_up_down', $building, $related_floor, $related_property_id)?>
				</td>
				<td class="td_use">
					<?php echo translateBuildingValue('type_of_use', $building, $related_floor, $related_property_id)?>
				</td>
				<td class="td_area">
					<?php echo translateBuildingValue('area_ping', $building, $related_floor, $related_property_id)?>
				</td>
				<td class="td_dateoccupancy">
					<?php echo translateBuildingValue('move_in_date', $building, $related_floor, $related_property_id)?>
				</td>
				<td class="td_view"><a href="<?php echo get_permalink($post)?>" class="btn btn-primary btn-square btn-line-border"><span><?php echo __('view details', 'realty')?></span></a></td>
			</tr>
			<?php endwhile;
			if (!$count_related) {
				echo '<style>';
				echo '#vacant-list{display: none;}';
				echo '</style>';
			}
			?>
		</tbody>
			
		</table>
		</section>
		<?php }?>

		<?php if ( $social_sharing ) { ?>
			<section class="primary-tooltips"><?php echo tt_social_sharing(); ?></section>
		<?php } ?>
				
	</div>
	<div class="col-sm-5">
	
	
	<?php
					/**
					 * Section: Map
					 *
					 */
					if ( ! tt_is_array_empty( $google_maps ) ) {
						$address = $google_maps['address'];
						if ( $google_maps['lat'] ) {
							$address_latitude = $google_maps['lat'];
					  } else {
							$address_latitude = null;
						}
						if ( $google_maps['lng'] ) {
							$address_longitude = $google_maps['lng'];
						} else {
							$address_longitude = null;
						}
					} else {
						$address = null;
					}
				?>

				<?php if ( ! empty ( $address ) && $realty_theme_option['show-single-property-map']  ) { ?>
					<section id="location_map">
						<?php include get_template_directory() . '/lib/inc/template/single-property-map.php'; ?>
					</section>
				<?php } ?>
	</div><!-- .col-sm-5 -->
	</div><!-- .row -->
	<?php //if ( $layout == 'boxed' ) { ?>
		</div><!-- .container -->
	<?php //} ?>

	<?php wp_reset_postdata(); ?>


	<div class="container">
		<div class="row">

			<?php if ( is_active_sidebar( 'sidebar_property' ) ) { ?>
				<div class="col-sm-8 col-md-9">
			<?php } else { ?>
				<div class="col-sm-12">
			<?php }	?>

			<div id="main-content" class="similar-wrap">
				<div class="modal fade modal-custom" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
									<span class="linericon-cross" aria-hidden="true">X</span>
								</button>
							</div>
							<div class="modal-body">
								<?php
									/**
									 * Section: Contact Form
									 * Check Theme Option + Property Settings For Author Contact Form
									 *
									 */
									$show_property_contact_form = $realty_theme_option['property-contact-form'];
									if ( $show_property_contact_form && $property_contact_information != 'none' ) {
										include get_template_directory() . '/lib/inc/template/contact-form.php';
									}
								?>
							</div>
						</div>
					</div>
				</div>
								
				<?php
					/**
					 * Section: Similar Properties
					 *
					 */
					if ( $realty_theme_option['property-show-similar-properties'] ) {
						include get_template_directory() . '/lib/inc/template/single-property-similar-properties.php';
					}
				?>

				<?php
					/**
					 * Section: Property Comments
					 *
					 */
					if ( $realty_theme_option['property-comments'] && ( comments_open() || get_comments_number() ) ) {
						comments_template();
					}
				?>

			</div><!-- #main-container -->

			<?php
				if ( $realty_theme_option['property-single-slideshow-autoplay'] ) {
					$autoplay = true;
				} else {
					$autoplay = false;
				}

				if ( $realty_theme_option['property-slideshow-navigation-type'] == 'thumbnail' ) {
					$as_nav_for = true;
					$show_dots = false;
				} else {
					$as_nav_for = false;
					$show_dots = true;
				}

				if ( $realty_theme_option['property-slideshow-animation-type'] == 'fade' ) {
					$fade = true;
				} else {
					$fade = false;
				}

				$slider_params = array(
					'id'                            => $image_slider_id,
					'images_to_show'                => 1,
					'images_to_show_lg'             => 1,
					'images_to_show_md'             => 1,
					'images_to_show_sm'             => 1,
					'autoplay'                      => $autoplay,
					'autoplay_speed'                => 5000,
					'fade'                          => false,
					'infinite'                      => true,
					'show_arrows'                   => true,
					'show_arrows_below'             => false,
					'show_dots'                     => false, //$show_dots,
					'show_dots_below'               => false,
					'property_slider_height'        => $realty_theme_option['property-image-height'],
					'property_slider_custom_height' => $realty_theme_option['property-image-custom-height'],
					'as_nav_for'                    => $as_nav_for,
				);

				tt_script_slick_slider( $slider_params );
			?>

			</div><!-- .col-sm-9 -->

			<?php if ( is_active_sidebar( 'sidebar_property' ) ) : ?>
				<div class="col-sm-4 col-md-3">
					<ul id="sidebar">
						<?php dynamic_sidebar( 'sidebar_property' ); ?>
					</ul>
				</div>
			<?php endif; ?>

		</div><!-- .row -->
	</div><!-- .container -->

<?php } else { // Show property only to logged in users ?>
	<div class="container">
		<p class="alert alert-info"><?php esc_html_e( 'You have to be logged-in to view property details. Click Login/Register in the top menu.', 'realty' ); ?></p>
	</div>
<?php } ?>

<?php get_footer(); ?>

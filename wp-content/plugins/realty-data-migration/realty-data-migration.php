<?php
/**
 * Plugin Name: Realty - Data Migration to v2.0
 * Plugin URI: http://themetrail.com/docs/realty/#data-migration
 * Description: Running this plugin will update your database, please make sure to backup your database before proceeding! Activate Plugin, activate another theme than Realty, then switch back to Realty. Edit a property to see if the data has been migrated properly. then deactivate and delete the plugin
 * Author: ThemeTrail
 * Version: 2.0
 * Author URI: http://themetrail.com
 */

// Realty 2.0: Update Property Post Meta In Database -> Create ACF Pro Post Meta Out Of metabox.io
function tt_migrate_property_data_to_acf(){
	if ( class_exists( 'acf' ) ) {
		if ( taxonomy_exists('property-location')) {
			$property_args = array( 'post_type' => 'property', 'posts_per_page' => -1 );
			$property_query = get_posts( $property_args );
			
			foreach ( $property_query as $post ) {
			
				// Property Images || @roy: this already works
				
				$property_images = get_post_meta( $post->ID, 'estate_property_images', false );
				if ( $property_images ) {
					update_field(  'estate_property_gallery', $property_images, $post->ID);
				}
				
				// Property Taxonomies || this works
				if(get_the_terms( $post->ID, 'property-location' )) {
					$wp_locations=get_the_terms( $post->ID, 'property-location' );
					$property_locations=array();
					foreach($wp_locations as $locations){
						
							$property_locations[]=$locations->term_id;
							update_field('acf-property-location', $property_locations, $post->ID);
						
					}
					update_field('acf-property-location', $property_locations, $post->ID);
				}
				if(get_the_terms($post->ID, 'property-type' )){
					$wp_types=get_the_terms($post->ID, 'property-type' ); 
					$property_types=array();
					foreach($wp_types as $type){
						$property_types[]=$type->term_id;
					}
					update_field('acf-property-type', $property_types, $post->ID);
				}
				if(get_the_terms($post->ID, 'property-status' )){
					$wp_status=get_the_terms($post->ID, 'property-status' );
					$property_status=array();
					foreach($wp_status as $status){
						$property_status[]=$status->term_id;
					}
					update_field('acf-property-status', $property_status, $post->ID);
				}
				if(get_the_terms($post->ID, 'property-features' )){
					$wp_features=get_the_terms($post->ID, 'property-features' );
					$property_feature=array();
					foreach($wp_features as $feature){
						$property_feature[]=$feature->term_id;
					}
					update_field('acf-property-features', $property_feature, $post->ID);
				}
				
				// Google Maps (Property Address & Lat/Lng Coordinates) || @roy: this already works
				$address = get_post_meta( $post->ID, 'estate_property_address', true );
				$location = get_post_meta( $post->ID, 'estate_property_location', true );
				if ( $location ) {
					$coordinates = explode( ',', $location );
					$google_maps = array(
						'address' => $address,
						'lat'     => $coordinates[0],
						'lng'     => $coordinates[1],
					);
				}
				if ( $address && $location ) {
					update_field( 'estate_property_google_maps', $google_maps, $post->ID );
				}
				
				// Property Attachments || @roy: this does work now!!
			
				$attachments = get_post_meta( $post->ID, 'estate_property_attachments', false );
				if ( $attachments ) {
					$attachment_ids=array();
					$attachments_count = count( $attachments );
					if(is_array($attachments)){
						foreach ( $attachments as  $attachment ) {
							$attachment_ids[]=array('estate_property_attachment'=>$attachment);
						}
						  // echo wp_get_attachment_url( $attachment );
						update_field( 'estate_property_attachments_repeater', $attachment_ids , $post->ID );                    
					   /* echo "<pre>";
						print_r($attachment_ids);
						echo "</pre>";*/
					} else {
						update_field('estate_property_attachments_repeater', $attachments, $post->ID ); 
					}
				}
				
				// Property Floor Plans || migration!!
				$property_floor_plan_title = get_post_meta( $post->ID, 'estate_floor_plan_title', true );
				$property_floor_plan_price = get_post_meta( $post->ID, 'estate_floor_plan_price', true );
				$property_floor_plan_size = get_post_meta( $post->ID, 'estate_floor_plan_size', true );
				$property_floor_plan_rooms = get_post_meta( $post->ID, 'estate_floor_plan_rooms', true );
				$property_floor_plan_bedrooms = get_post_meta( $post->ID, 'estate_floor_plan_bedrooms', true );
				$property_floor_plan_bathrooms = get_post_meta( $post->ID, 'estate_floor_plan_bathrooms', true );
				$property_floor_plan_description = get_post_meta( $post->ID, 'estate_floor_plan_description', true );
				$property_floor_plan_image = get_post_meta( $post->ID, 'estate_floor_plan_image', true );
				$foor_plans=array();
				$i=0;
				if($property_floor_plan_image){
					foreach ( $property_floor_plan_image as $image ) {
						$foor_plans[]=array(
							'acf_estate_floor_plan_title'=>$property_floor_plan_title[$i],
							'acf_estate_floor_plan_price'=>$property_floor_plan_price[$i],
							'acf_estate_floor_plan_size'=>$property_floor_plan_size[$i],
							'acf_estate_floor_plan_rooms'=>$property_floor_plan_rooms[$i],
							'acf_estate_floor_plan_bedrooms'=>$property_floor_plan_bedrooms[$i],
							'acf_estate_floor_plan_bathrooms'=>$property_floor_plan_bathrooms[$i],
							'acf_estate_floor_plan_description'=>$property_floor_plan_description[$i],
							'acf_estate_floor_plan_image'=>$image
						);
						$i++;
						
					}
					update_field( 'estate_property_floor_plans', $foor_plans , $post->ID );
				}
					/*echo "<pre>";
					print_r($foor_plans );
					echo "</pre>";		*/
			}
		}else {
			add_action( 'admin_notices', 'pat_admin_error_notice' ); 

		}
	}
	else {
		add_action( 'admin_notices', 'acf_admin_error_notice' ); 
	}
}
add_action( 'after_switch_theme', 'tt_migrate_property_data_to_acf');
function acf_admin_error_notice() {
	$class = "update-nag";
	$message = "The following required plugin is currently inactive or not installed: Advanced Custom Fields Pro.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}
function pat_admin_error_notice() {
	$class = "update-nag";
	$message = "The following required plugin is currently inactive or not installed: Properties And Types.";
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
}
?>
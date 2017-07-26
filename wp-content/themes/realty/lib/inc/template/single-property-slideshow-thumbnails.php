<?php 
if (!isset($buildingFloorPictures))
{
	$buildingFloorPictures = getBuildingFloorPictures($building, $floor, $single_property_id);
}

if ( $property_images || ( $property_video_provider && $property_video_id && has_post_thumbnail() ) || !empty($buildingFloorPictures)) { ?>
	<div id="property_thumbnails"><!--property-thumbnails-->
		<?php
			if ( $property_images || !empty($buildingFloorPictures)) {
				if ($property_images) {
					// Gallery Images
					foreach ( $gallery_array as $slide ) {
						$attachment = wp_get_attachment_image_src( $slide->ID, 'property-thumb' );
						$attachment_url = $attachment[0];
						echo '<div><a href="#"><img  src="' . $attachment_url . '" alt="" /></a></div>';
					}
				}
				
				if (!empty($buildingFloorPictures)) {
					// Gallery Images
					foreach ( $buildingFloorPictures as $image_url ) { ?>
						
					<div class="property-image <?php echo esc_attr( $property_zoom ); ?>"  data-image="<?php echo esc_attr ( $image_url ); ?>" data-mfp-src="<?php echo esc_attr( $image_url ); ?>" title="<?php echo esc_attr( $slide->post_title ); ?>" data-title="<?php echo esc_attr( $slide->post_title ); ?>" data-hash="#" style="width:100%;">
						<img src="<?php echo esc_attr ( $image_url ); ?>" alt=" " />
					</div>
					<?php }
				} ?>
			<?php } else {
				// Featured Image Only
				$thumbnail_attr = array(
					'title' => wp_get_attachment_meta_data_title(),
					'data-title' => wp_get_attachment_meta_data_title(),
					'data-mfp-src' => wp_get_attachment_url( get_post_thumbnail_id() ),
				);

				echo '<div><a href="#">';
				the_post_thumbnail( 'property-thumb', $thumbnail_attr );
				echo '</a></div>';
			}
		?>
	</div>
<?php } ?>
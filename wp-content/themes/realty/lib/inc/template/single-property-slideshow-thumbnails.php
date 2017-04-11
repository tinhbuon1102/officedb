<?php if ( $property_images || ( $property_video_provider && $property_video_id && has_post_thumbnail() ) ) { ?>
	<div id="property-thumbnails">
		<?php
			if ( $property_images ) {
				// Gallery Images
				foreach ( $gallery_array as $slide ) {
					$attachment = wp_get_attachment_image_src( $slide->ID, 'property-thumb' );
					$attachment_url = $attachment[0];
					echo '<div><a href="#"><img src="' . $attachment_url . '" alt="" /></a></div>';
				}
			} else {
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
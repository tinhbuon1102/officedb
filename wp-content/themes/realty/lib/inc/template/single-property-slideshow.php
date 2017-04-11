<?php
	$classes_property_image_container = array();
	$classes_property_image_container[] = 'property-image-container';
	$classes_property_image_container[] = $realty_theme_option['property-image-height'];
	$classes_property_image_container = join( ' ', $classes_property_image_container );

	$i = 1;
?>

<div class="<?php echo esc_attr( $classes_property_image_container ); ?>">
	<div class="hide-initially" id="<?php echo esc_attr( $image_slider_id ); ?>">

		<?php if ( ! tt_is_array_empty( $property_images ) ) { // Gallery Images ?>

			<?php
				$args = array(
					'post_type' => 'attachment',
					'orderby' => 'post__in',
					'post__in' => $property_images,
					'posts_per_page' => count( $property_images ),
				);

				$gallery_array = get_posts( $args );
				$main_images = wp_get_attachment_image_src( $gallery_array[0]->ID, $property_image_width );
				$main_images_url = $main_images[0];
			?>

			<?php foreach ( $gallery_array as $slide ) { ?>

				<?php
					$attachment = wp_get_attachment_image_src( $slide->ID, $property_image_width );
					$attachment_url = $attachment[0];
				?>

				<?php if ( $realty_theme_option['property-image-height'] != 'original' ) { ?>
					<div class="property-image <?php echo esc_attr( $property_zoom ); ?>" style="background-image: url(<?php echo esc_attr( $attachment_url ); ?>)" data-image="<?php echo esc_attr( $attachment_url ); ?>" data-mfp-src="<?php echo esc_attr( $attachment_url ); ?>" class="property-image <?php echo esc_attr( $property_zoom ); ?>" title="<?php echo esc_attr( $slide->post_title ); ?>" data-title="<?php echo esc_attr( $slide->post_title ); ?>" data-hash="#"></div>
				<?php } ?>

				<?php if ( $realty_theme_option['property-image-height'] == "original" ) { ?>
					<?php
						$thumbnail_attr = array(
							'class' => 'property-image',
							'title' => wp_get_attachment_meta_data_title(),
							'data-image' => wp_get_attachment_url( $slide->ID ),
							'data-title' => wp_get_attachment_meta_data_title(),
							'data-mfp-src' => wp_get_attachment_url( $slide->ID ),
							'data-src' => wp_get_attachment_url( $slide->ID ),
							'data-hash' => 'slide'. $i,
						);
						echo wp_get_attachment_image( $slide->ID, $property_image_width, false, $thumbnail_attr );
					?>
				<?php } ?>

				<?php $i++; ?>

			<?php }	?>

		<?php } else { // Featured Image Only ?>

			<?php if ( has_post_thumbnail( $single_property_id ) ) { ?>

				<?php
					$thumbnail_id = get_post_thumbnail_id( $single_property_id );
					$thumbnail_url_array = wp_get_attachment_image_src( $thumbnail_id, $property_image_width, true);
					$thumbnail_url = $thumbnail_url_array[0];
				?>

				<?php if ( $realty_theme_option['property-image-height'] != 'original' ) { ?>
					<div class="property-image <?php echo esc_attr( $property_zoom ); ?>" style="background-image:url(<?php echo esc_url( $thumbnail_url ); ?>)" data-title="<?php echo wp_get_attachment_meta_data_title(); ?>" data-image="<?php echo wp_get_attachment_url( $thumbnail_id ); ?>" data-mfp-src="<?php echo wp_get_attachment_url( $thumbnail_id ); ?>" title="<?php echo wp_get_attachment_meta_data_title(); ?>" data-hash="#"></div>
				<?php } ?>

				<?php if ( $realty_theme_option['property-image-height'] == "original" ) { ?>
					<?php
						$thumbnail_attr = array(
							'class' => 'property-image',
							'title' => wp_get_attachment_meta_data_title(),
							'data-image' => wp_get_attachment_url( $thumbnail_id ),
							'data-title' => wp_get_attachment_meta_data_title(),
							'data-mfp-src' => wp_get_attachment_url( $thumbnail_id ),
							'data-src' => wp_get_attachment_url( $thumbnail_id ),
							'data-hash' => 'slide'. $i,
						);
						echo wp_get_attachment_image( $thumbnail_id, $property_image_width, false, $thumbnail_attr );
					?>
				<?php } ?>

				<?php } ?>

				<?php $i++; ?>

			<?php }	?>

	</div><!-- Property Carousel -->

	<div class="loader-container">
		<div class="svg-loader"></div>
	</div>
</div><!-- .property-image-container -->
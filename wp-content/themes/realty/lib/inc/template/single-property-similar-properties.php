<?php
	$criteria = $realty_theme_option['property-similar-properties-criteria'];
	$columns = $realty_theme_option['property-similar-properties-columns'];

	$args_similar_properties = array(
		'post_type'					=> 'property',
		'posts_per_page' 		=> -1,
		'post__not_in'			=> array( $single_property_id )
	);

	// Theme Options: Similar Properties Criteria
	$tax_query = array();

	if ( $property_location ) {
		foreach ( $property_location as $location ) {
			$location = $location->slug;
			break;
		}
	} else {
		$location = '';
	}

	if ( $criteria['location'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-location',
			'field' 		=> 'slug',
			'terms'			=> $location
		);
	}

	if ( $property_status ) {
		foreach ( $property_status as $status ) {
			$status = $status->slug;
			break;
		}
	} else {
		$status = '';
	}

	if ( $criteria['status'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-status',
			'field' 		=> 'slug',
			'terms'			=> $status
		);
	}

	if ( $property_type ) {
		foreach ( $property_type as $type ) {
			$type = $type->slug;
			break;
		}
	} else {
		$type = '';
	}

	if ( $criteria['type'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-type',
			'field' 		=> 'slug',
			'terms'			=> $type
		);
	}

	$tax_count = count( $tax_query );
	if ( $tax_count > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	if ( $tax_count > 0 ) {
		$args_similar_properties['tax_query'] = $tax_query;
	}

	$meta_query = array();

	if ( $criteria['min_rooms'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_rooms',
			'value' 		=> $rooms,
			'compare'   => '>=',
			'type'			=> 'NUMERIC'
		);
	}

	if ( $criteria['max_price'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_price',
			'value' 		=> $price,
			'compare'   => '<=',
			'type'			=> 'NUMERIC'
		);
	}

	if ( $criteria['available_from'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_available_from',
			'value' 		=> $available_from,
			'compare'   => '<=',
			'type'			=> 'DATE'
		);
	}

	$meta_count = count( $meta_query );
	if ( $meta_count > 1 ) {
	  $meta_query['relation'] = 'AND';
	}

	if ( $meta_count > 0 ) {
		$args_similar_properties['meta_query'] = $meta_query;
	}
?>

<?php $query_similar_properties = new WP_Query( $args_similar_properties ); ?>

<?php if ( $query_similar_properties->have_posts() ) : ?>

	<?php
		$similar_properties_carousel_id = 'similar_properties_carousel';
		$show_arrows_below = true;
	?>

	<section id="similar-properties">
		<h3 class="section-title"><span><?php esc_html_e( 'Similar Properties', 'realty' ); ?> (<?php echo $query_similar_properties->found_posts; ?>)</span></h3>
		<div class="property-items">

			<div id="<?php echo esc_attr( $similar_properties_carousel_id ); ?>">
				<?php
					while ( $query_similar_properties->have_posts() ) : $query_similar_properties->the_post();
						get_template_part( 'lib/inc/template/property', 'item' );
					endwhile;
				?>
			</div>

			<?php if ( $show_arrows_below ) { ?>
				<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $similar_properties_carousel_id ); ?>"></div>
			<?php } ?>

			<?php wp_reset_postdata(); ?>

		</div>
	</section>

	<?php
		$similar_properties_carousel_params = array(
			'id'                => $similar_properties_carousel_id,
			'images_to_show'    => $columns,
			'images_to_show_lg' => $columns,
			'images_to_show_md' => 1,
			'images_to_show_sm' => 1,
			'autoplay'          => false,
			'autoplay_speed'    => 5000,
			'fade'              => false,
			'infinite'          => false,
			'show_arrows'       => true,
			'show_arrows_below' => true,
			'show_dots'         => false,
			'show_dots_below'   => false,
		);

		tt_script_slick_slider( $similar_properties_carousel_params );
	?>

<?php endif; ?>
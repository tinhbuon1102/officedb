<?php
/**
 * Property Comparison - Script
 *
 */
if ( ! function_exists( 'tt_comparison_script') ) {
	function tt_comparison_script() {
	?>
		<script>
		// Check If item Already In Favorites Array
		function inArray(needle, haystack) {
	    var length = haystack.length;
	    for( var i = 0; i < length; i++ ) {
	      if(haystack[i] == needle) return true;
	    }
	    return false;
		}

		if ( !store.enabled ) {
			throw new Error("<?php esc_html_e( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'realty' ); ?>");
	  }

		jQuery('.container').on('click', '.compare-property', function() {

			jQuery('#compare-properties-popup').show();

		  // Check If Browser Supports LocalStorage
			if ( !store.enabled ) {
		    throw new Error("<?php echo __( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'realty' ); ?>");
		  }

		  if ( store.get('comparison') ) {

				var getComparisonAll = store.get('comparison');
				var propertyToCompare = jQuery(this).attr('data-compare-id');

				// Add To Comparison, If Its Not Already In It
				if ( !inArray( propertyToCompare, getComparisonAll ) && getComparisonAll.length < 4 ) {
					getComparisonAll.push( propertyToCompare );
				}

				store.set( 'comparison', getComparisonAll );
				comparisonLength = getComparisonAll.length;

			} else {

				var arrayComparison = [];
				arrayComparison.push( jQuery(this).attr('data-compare-id') );
				store.set( 'comparison', arrayComparison );
				var comparisonLength = store.get('comparison').length;

			}

			console.log( store.get('comparison') );

			// Update Comparison Popup Thumbnails
			var properties;
			properties = store.get('comparison');

			jQuery.ajax({
			  type: 'GET',
			  url: ajax_object.ajax_url,
			  data: {
			    'action'          :   'tt_ajax_property_comparison_thumbnails', // WP Function
			    'properties'      :   properties
			  },
			  success: function(response) {
			  	// If Temporary Favorites Found, Show Them
			  	if ( store.get('comparison') != "" ) {
			  		jQuery('#compare-properties-thumbnails').html(response);
			  		// Show Max. Message
			  		if ( comparisonLength == 4 ) {
							jQuery('#compare-properties-popup .alert').toggleClass('hide');
						}
			  	}
			  }
			});

		});
		</script>
	<?php
	}
}
add_action( 'wp_footer', 'tt_comparison_script', 22 );

/**
 * Property Comparison - Page Template Output
 *
 */
if ( ! function_exists( 'tt_ajax_property_comparison' ) ) {
	function tt_ajax_property_comparison() {

		$property_comparison_args['post_type'] = 'property';
		$property_comparison_args['post_status'] = 'publish';
		$property_comparison_args['posts_per_page'] = '4';
		//$property_comparison_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		if ( isset( $_GET['properties'] ) ) {
			$property_comparison_args['post__in'] = $_GET['properties'];
		} else {
			$property_comparison_args['post__in'] = array( '0' );
		}

		$query_property_comparison = new WP_Query( $property_comparison_args );
		?>

		<?php if ( $query_property_comparison->have_posts() ) : ?>

			<?php
				$count_results = $query_property_comparison->found_posts;
				$properties = array();
			?>

			<?php while ( $query_property_comparison->have_posts() ) : $query_property_comparison->the_post(); ?>

				<?php
					global $post;

					$property_to_compare = array();
					$property['property_permalink'] = get_permalink();
					$property['property_title'] = get_the_title();
					$property['property_id'] = get_the_ID();
					if ( has_post_thumbnail() ) {
					$property['property_thumbnail'] = get_the_post_thumbnail( $post->ID, 'property-thumb' );
					} else {
						$property['property_thumbnail'] = '<img src ="//placehold.it/600x300/eee/ccc/&text=.." />';
					}
					$property['property_price'] = tt_property_price();
					$property['property_features'] = get_the_terms( $post->ID, 'property-features');
					$property['property_type'] = get_the_term_list( $post->ID, 'property-type', '', ', ', '' );
					$property['property_status'] = get_the_term_list( $post->ID, 'property-status', '', ', ', '' );
					$property['property_location'] = get_the_term_list( $post->ID, 'property-location', '', ' <small><span class="text-muted"></span></small> ', '' );
					$google_maps = get_post_meta( $post->ID, 'estate_property_google_maps', true );
					if ( isset( $google_maps ) ) {
						$property_address = $google_maps['address'];
					}
					$property['property_address'] = $property_address;
					$property['property_size'] = get_post_meta( $post->ID, 'estate_property_size', true );
					$property['property_size_unit'] = get_post_meta( $post->ID, 'estate_property_size_unit', true );
					$property['property_rooms'] = get_post_meta( $post->ID, 'estate_property_rooms', true );
					$property['property_bedrooms'] = get_post_meta( $post->ID, 'estate_property_bedrooms', true );
					$property['property_bathrooms'] = get_post_meta( $post->ID, 'estate_property_bathrooms', true );
					$property['property_garages'] = get_post_meta( $post->ID, 'estate_property_garages', true );

					$properties[] = $property;
				?>

			<?php endwhile;	?>

			<div class="comparison-main">
				<div class="comparison-row">

					<div class="comparison-header">&nbsp;</div>

					<?php for ( $i = 0; $i < $count_results; $i++ ) { ?>

						<div class="comparison-data primary-tooltips">
							<i class="remove-property-from-comparison" data-compare-id="<?php echo esc_attr( $properties[$i]['property_id'] ); ?>" data-toggle="tooltip" title="<?php esc_html_e( 'Remove', 'realty' ); ?>">&times;</i>
							<a href="<?php echo esc_url( $properties[$i]['property_permalink'] ); ?>">
								<?php echo $properties[$i]['property_thumbnail']; ?>
								<h6 class="property-title"><?php echo $properties[$i]['property_title']; ?></h6>
							</a>
							<div class="property-address"><?php echo $properties[$i]['property_address']; ?></div>
						</div>
					<?php } ?>

				</div>
			</div>

			<?php
				// Property Attributes
				$property_attributes = array(
					'property_price'					=> __( 'Price', 'realty' ),
					'property_type'						=> __( 'Type', 'realty' ),
					'property_status'					=> __( 'Status', 'realty' ),
					'property_location'				=> __( 'Location', 'realty' ),
					'property_size'						=> __( 'Size', 'realty' ),
					'property_rooms'					=> __( 'Rooms', 'realty' ),
					'property_bedrooms'				=> __( 'Bedrooms', 'realty' ),
					'property_bathrooms'			=> __( 'Bathrooms', 'realty' ),
					'property_garages'				=> __( 'Garages', 'realty' ),
					'property_features'				=> __( 'Property Features', 'realty' ),
				);
			?>

			<div class="comparison-attributes">

				<?php foreach ( $property_attributes as $attribute_key => $attribute_value ) { ?>

					<?php if ( $attribute_key == "property_features" ) { ?>

						<?php for ( $i = 0; $i < $count_results; $i++ ) { ?>

							<?php
								// Get All Existing Property Features
								$all_property_features = get_terms( 'property-features', array( 'hide_empty=0' ) );
							?>

							<?php foreach ( $all_property_features as $single_property_feature ) { ?>

								<div class="comparison-row">
									<div class="comparison-header"><?php echo $single_property_feature->name; ?></div>

									<?php for ( $i = 0; $i < $count_results; $i++ ) { ?>
										<div class="comparison-data">

											<?php $current_property_features = $properties[$i][$attribute_key]; ?>

											<?php
												// Has Current Property The Currently Queried Feature?
												if ( $current_property_features ) {
													foreach ( $current_property_features as $current_property_feature ) {
														if ( $single_property_feature->term_id == $current_property_feature->term_id ) {
															$has_feature = true;
															break;
														} else {
															$has_feature = false;
														}
													}
												}	else {
													$has_feature = false;
												}
											?>

											<?php if ( $has_feature ) { ?>
												<i class="icon-check-2 text-success"></i>
											<?php } else { ?>
												<i class="icon-subtract-1 text-muted"></i>
											<?php } ?>

										</div>
									<?php } ?>
								</div>
							<?php } ?>

						<?php } ?>

					<?php } else { ?>
						<div class="comparison-row">
						<div class="comparison-header"><?php echo $attribute_value; ?></div>
							<?php for ( $i = 0; $i < $count_results; $i++ ) { ?>
								<div class="comparison-data">
									<?php echo $properties[$i][$attribute_key]; ?>
									<?php if ( $attribute_key == "property_size" ) { ?>
									 <?php echo ' ' . $properties[$i]['property_size_unit']; ?>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>

			</div>

			<?php wp_reset_query(); ?>

		<?php endif; ?>

		<?php die();

	}
}
add_action('wp_ajax_tt_ajax_property_comparison', 'tt_ajax_property_comparison');
add_action('wp_ajax_nopriv_tt_ajax_property_comparison', 'tt_ajax_property_comparison');


/**
 * Property Comparison Thumbnails
 *
 */
if ( ! function_exists( 'tt_ajax_property_comparison_thumbnails' ) ) {
	function tt_ajax_property_comparison_thumbnails() {

		$property_comparison_args['post_type'] = 'property';
		$property_comparison_args['post_status'] = 'publish';
		$property_comparison_args['posts_per_page'] = '4';

		if ( isset( $_GET['properties'] ) ) {
			$property_comparison_args['post__in'] = $_GET['properties'];
		} else {
			$property_comparison_args['post__in'] = array( '0' );
		}

		$query_property_comparison = new WP_Query( $property_comparison_args );
		?>

		<?php if ( $query_property_comparison->have_posts() ) : ?>

			<?php $count_results = $query_property_comparison->found_posts; ?>

			<ul class="row list-unstyled">
				<?php while ( $query_property_comparison->have_posts() ) : $query_property_comparison->the_post(); ?>
				<li class="col-sm-4 col-md-2">
					<?php if ( has_post_thumbnail() ) { ?>
						<?php the_post_thumbnail( 'property-thumb' ); ?>
					<?php } else { ?>
						<img src ="//placehold.it/600x300/eee/ccc/&text=.." />
					<?php } ?>
				</li>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_query(); ?>

		<?php endif; ?>

		<?php die();

	}
}
add_action('wp_ajax_tt_ajax_property_comparison_thumbnails', 'tt_ajax_property_comparison_thumbnails');
add_action('wp_ajax_nopriv_tt_ajax_property_comparison_thumbnails', 'tt_ajax_property_comparison_thumbnails');
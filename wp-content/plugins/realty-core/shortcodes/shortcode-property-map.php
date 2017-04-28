<?php
/**
 * Shortcode: Map
 *
 */
if ( ! function_exists( 'tt_map' ) ) {
	function tt_map( $atts ) {

		extract( shortcode_atts( array(
			'height'             => '400',
			//'type'             => 'ROADMAP',
			'address'			       => '',
			'latitude'           => '',
			'longitude'          => '',
			'zoomlevel'          => '13',
			'scrollwheel'        => false,
			'streetviewcontrol'  => false,
			'draggable'          => true,
			'disabledefaultui'   => true,
			'location'	      	 =>'',
			'status'			       => '',
			'type'				       => '',
			'features'		       => '',
			'single_property_id' => null,
		), $atts ) );

		$scrollwheel = ! empty( $scrollwheel ) ? 'true' : 'false';
		$draggable = ! empty( $draggable ) ? 'true' : 'false';
		$streetviewcontrol = ! empty( $streetviewcontrol ) ? 'true' : 'false';
		$disabledefaultui = ! empty( $disabledefaultui ) ? 'true' : 'false';

		if ( ! empty( $latitude ) ) {
			$latitude = $latitude;
		} else {
			$latitude = 0;
		}

		if ( ! empty( $longitude ) ) {
			$longitude = $longitude;
		} else {
			$longitude = 0;
		}

		ob_start();

		// Property Query
		$query_properties_args = array();

		// For use in single-property.php map section
		if ( $single_property_id ) {
			$query_properties_args['post__in'] = array( $single_property_id );
			$query_properties_args['is_single'] = true;
			$zoomlevel = '13';
		}

		/* TAX QUERIES:
		============================== */
		$tax_query = array();

		if ( $location ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-location',
				'field' 		=> 'slug',
				'terms'			=> $location
			);
		}

		if ( $status ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-status',
				'field' 		=> 'slug',
				'terms'			=> $status
			);
		}

		if ( $type ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-type',
				'field' 		=> 'slug',
				'terms'			=> $type
			);
		}

		if ( $features ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-features',
				'field' 		=> 'slug',
				'terms'			=> $features
			);
		}

		// Count Taxonomy Queries + set their relation for search query
		$tax_count = count( $tax_query );
		if ( $tax_count > 1 ) {
			$tax_query['relation'] = 'AND';
		}

		$query_properties_args['tax_query'] = $tax_query;

		// Build property query
		$query_properties_args = apply_filters( 'property_search_args', $query_properties_args );
		// Get all matching properties for map
		$query_properties_args['posts_per_page'] = -1;
	
		$query_properties = new WP_Query( $query_properties_args );

		if ( $query_properties->have_posts() ) :

			$random = rand();
			$property_string = '';

			while ( $query_properties->have_posts() ) : $query_properties->the_post();

				global $post, $realty_theme_option;
				$google_maps = get_post_meta( $post->ID, 'estate_property_google_maps', true );

				if ( ! tt_is_array_empty( $google_maps ) ) {

					//$address = $google_maps['address'];

					if ( $google_maps['lat'] ) {
						$address_latitude = $google_maps['lat'];
					} else {
						$address_latitude = '51.5286416';
					}
					if ( $google_maps['lng'] ) {
						$address_longitude = $google_maps['lng'];
					} else {
						$address_longitude = '-0.1015987';
					}

				}

				// Check If We Have LatLng Coordinates From Google Maps
				if ( $google_maps ) {

					$property_string .= "{ ";
					$property_string .= "permalink:'" . get_the_permalink() . "', ";
					$property_string .= "title:'" . get_the_title() . "', ";
					$property_string .= "price:'" . tt_property_price() . "', ";
					$property_string .= "latLng: new google.maps.LatLng(" . $address_latitude . ", " . $address_longitude . "), ";
					if ( has_post_thumbnail() ) {
						$property_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'property-thumb' );
						$property_string .= "thumbnail: '" . $property_thumbnail[0] . "'";
					}	else {
						$property_string .= "thumbnail: '//placehold.it/600x300/eee/ccc/&text=..'";
					}
					$property_string .= " }," . "\n";

				}

			endwhile; ?>

			<?php wp_reset_postdata(); ?>

			<script>
				var map = null, markers = [], newMarkers = [], markerCluster = null, infobox = [], address = null;
				<?php echo tt_mapMarkers(); ?>

				/**
				 * initMap
				 *
				 */
				function initMap() {

					var	mapOptions = {
						center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
						zoom: <?php echo $zoomlevel; ?>,
				    scrollwheel: <?php echo $scrollwheel; ?>,
				    streetViewControl: <?php echo $streetviewcontrol; ?>,
				    draggable: <?php echo $draggable; ?>,
				    disableDefaultUI: <?php echo $disabledefaultui; ?>,
					};

					map = new google.maps.Map(document.getElementById("map"), mapOptions);

					<?php if ( $realty_theme_option['style-your-map'] ) { ?>
						if ( map_options.map_style !== '' ) {
							var styles = JSON.parse(map_options.map_style);
							map.setOptions( { styles: styles } );
						}
					<?php } ?>

					<?php if ( $address ) { ?>
						address = "<?php echo $address; ?>";
						//console.log(address);
				  <?php } else { ?>
					  address = null;
					  //console.log('No address');
				  <?php } ?>

					markers = initMarkers(map, [ <?php echo $property_string; ?> ]);

					// Spiderfier
					var oms = new OverlappingMarkerSpiderfier(map, { markersWontMove: true, markersWontHide: true, keepSpiderfied: true, legWeight: 5 });

					function omsMarkers( markers ) {
					  for ( var i = 0; i < markers.length; i++ ) {
							oms.addMarker( markers[i] );
					  }
					}

					omsMarkers(markers);

				}
				google.maps.event.addDomListener(window, 'load', initMap);

				/**
				 * Get latLng from property address and grab it with callback, as geocode calls asynchonous
				 *
				 */
			  function getLatLng(callback) {
				  var geocoder = new google.maps.Geocoder();
				  if ( geocoder && address ) {
					  geocoder.geocode( { 'address': address}, function(results, status ) {
							if (status == google.maps.GeocoderStatus.OK) {
								latLng = results[0].geometry.location;
								callback(latLng);
							} else {
								console.log("Geocoder failed due to: " + status);
							}
					  });
				  }
			  }

				/**
				 * initMarkers
				 *
				 */
				function initMarkers(map, markerData) {

					<?php if ( $latitude != 0 && $longitude != 0 ) { ?>
						map.setCenter(new google.maps.LatLng(<?php echo $latitude; ?> , <?php echo $longitude; ?>));
					<?php } else { ?>
						getLatLng(function(latLng) {
							map.setCenter(latLng);
						});
					<?php } ?>

					var bounds = new google.maps.LatLngBounds();

					for ( var i = 0; i < markerData.length; i++ ) {

						marker = new google.maps.Marker({
						map: map,
						position: markerData[i].latLng,
						icon: customIcon,
						animation: google.maps.Animation.DROP
						}),

						infoboxOptions = {
							content: 	'<div class="map-marker-wrapper">'+
													'<div class="map-marker-container">'+
														'<div class="arrow-down"></div>'+
														'<img src="'+markerData[i].thumbnail+'" />'+
														'<div class="content">'+
															'<a href="'+markerData[i].permalink+'">'+
																'<h5 class="title">'+markerData[i].title+'</h5>'+
															'</a>'+
															markerData[i].price+
														'</div>'+
													'</div>'+
												'</div>',
							disableAutoPan: false,
						  pixelOffset: new google.maps.Size(-33, -90),
						  zIndex: null,
						  isHidden: true,
						  alignBottom: true,
						  closeBoxURL: "<?php echo get_template_directory_uri() . '/lib/images/close.png'; ?>",
						  infoBoxClearance: new google.maps.Size(25, 25)
						};

						newMarkers.push(marker);

						newMarkers[i].infobox = new InfoBox(infoboxOptions);

						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
								if ( newMarkers[i].infobox.getVisible() ) {
									newMarkers[i].infobox.hide();
								} else {
									jQuery('.infoBox').hide();
									newMarkers[i].infobox.show();
								}

								newMarkers[i].infobox.open(map, this);
								map.setCenter(marker.getPosition());
								map.panBy(0,-175);
							}
						})( marker, i ) );

						google.maps.event.addListener(map, 'click', function() {
							jQuery('.infoBox').hide();
					  });

						// Extend map bounds for this marker
					  bounds.extend(markerData[i].latLng);

					} // for (each marker)

					// Create new map bounds to have all marker on the map
					// If not on single-property.php, as only required for multiple markers
					<?php if ( ! $single_property_id ) { ?>
					map.fitBounds(bounds);
					<?php } ?>
					markerCluster = new MarkerClusterer(map, newMarkers, markerClusterOptions);

					return newMarkers;

				} // initMarkers()
		  </script>

		  <?php echo tt_script_map_controls( 'map', $random ); ?>

			<div class="map-wrapper" style="width: 100%; height: <?php echo $height . 'px'; ?>">

				<?php if ( $disabledefaultui == 'true' ) { ?>
					<ul class="map-controls list-unstyled">
						<li><a href="#" class="control zoom-in" id="zoom-in-<?php echo $random; ?>"><i class="icon-add"></i></a></li>
						<li><a href="#" class="control zoom-out" id="zoom-out-<?php echo $random; ?>"><i class="icon-subtract"></i></a></li>
						<li><a href="#" class="control map-type" id="map-type-<?php echo $random; ?>">
							<i class="icon-image"></i>
							<ul class="list-unstyled">
								<li id="map-type-roadmap-<?php echo $random; ?>" class="map-type"><?php esc_html_e( 'Roadmap', 'realty' ); ?></li>
								<li id="map-type-satellite-<?php echo $random; ?>" class="map-type"><?php esc_html_e( 'Satellite', 'realty' ); ?></li>
								<li id="map-type-hybrid-<?php echo $random; ?>" class="map-type"><?php esc_html_e( 'Hybrid', 'realty' ); ?></li>
								<li id="map-type-terrain-<?php echo $random; ?>" class="map-type"><?php esc_html_e( 'Terrain', 'realty' ); ?></li>
							</ul>
						</a></li>
						<li><a href="#" id="current-location-<?php echo $random; ?>" class="control"><i class="icon-crosshair"></i> <?php esc_html_e( 'My Location', 'realty' ); ?></a></li>
					</ul>
				<?php } ?>

				<div id="map" class="google-map" style="height: <?php echo $height . 'px'; ?>">
					<div class="loader-container">
						<div class="svg-loader"></div>
					</div>
				</div>

			</div>

			<?php wp_reset_query(); ?>

			<?php else: ?>

				<div class="map-no-properties-found">
					<p class="lead text-center"><?php esc_html_e( 'No Properties Found.', 'realty' ) ?></p>
				</div>

		<?php endif; ?>

		<?php return ob_get_clean();

	}
}
add_shortcode( 'map', 'tt_map' ); // Backwards-compatibility for Realty prior to v3.0
add_shortcode( 'property_map', 'tt_map' );

// Visual Composer Map
function tt_vc_map_map() {
	vc_map( array(
		'name' => esc_html__( 'Property Map', 'realty' ),
		'base' => 'property_map',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => __( 'Height in pixel', 'realty' ),
				'param_name' => 'height',
				'value' => __( '400', 'realty' ),
				'description' => ''
			),
			/* Results in listing error
			array(
				'type' => 'dropdown',
				'heading' => __( 'Type', 'realty' ),
				'param_name' => 'type',
				'value' => array(
					__( 'Roadmap', 'realty' ) => 'ROADMAP',
					__( 'Satellite', 'realty' ) => 'SATELLITE',
					__( 'Hybrid', 'realty' ) => 'HYBRID',
					__( 'Terrain', 'realty' ) => 'TERRAIN',
				),
				'std' => 'ROADMAP'
			),
			*/
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => __( 'Latitude (Map Center)', 'realty' ),
				'param_name' => 'latitude',
				'value' => '',
				'description' => __( 'Visit <a href="http://mondeca.com/index.php/en/any-place-en" target="_blank">http://mondeca.com/index.php/en/any-place-en</a> to retrieve coordinate of any address.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => __( 'Longitude (Map Center)', 'realty' ),
				'param_name' => 'longitude',
				'value' => '',
				'description' => __( 'Visit <a href="http://mondeca.com/index.php/en/any-place-en" target="_blank">http://mondeca.com/index.php/en/any-place-en</a> to retrieve coordinate of any address.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Address (Map Center)', 'realty' ),
				'param_name' => 'address',
				'value' => '',
				'description' => esc_html__( 'E.g. "London, UK". Leave latitude/longitude empty. Use if you no latitude/longitude available. ', 'realty' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Zoom level', 'realty' ),
				'param_name' => 'zoom',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10,
					11,
					12,
					13,
					14,
					15,
					16,
					17,
					18,
					19,
					20,
				),
				'std' => 13,
				'description' => esc_html__( 'Only applicable when address or coordinates are set, otherwise map bounds will be calculated automatically.', 'realty' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Scroll wheel', 'realty' ),
				'param_name' => 'scrollwheel',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Draggable', 'realty' ),
				'param_name' => 'draggable',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Street view controls', 'realty' ),
				'param_name' => 'streetviewcontrol',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Disable Default UI', 'realty' ),
				'param_name' => 'disabledefaultui',
				'value' => array( '' => true ),
				'std' => true,
				'description' => esc_html__( 'If checked, show custom map controls.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Location', 'realty' ),
				'param_name' => 'location',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Location". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Status', 'realty' ),
				'param_name' => 'status',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Status". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Type', 'realty' ),
				'param_name' => 'type',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Type". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Features', 'realty' ),
				'param_name' => 'features',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Features". Separate by comma.', 'realty' ),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_map' );

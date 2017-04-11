<?php
/**
 * Custom Map Marker Icons
 *
 */
if ( ! function_exists( 'tt_mapMarkers' ) ) {
	function tt_mapMarkers() {

		global $realty_theme_option;

		// Map Marker - Property
		$default_marker_property = $realty_theme_option['map-marker-property-default'];

		if ( ! empty( $realty_theme_option['map-marker-property']['url'] ) ) {
			if ( is_ssl() ) {
				$custom_marker_property_url = str_replace( 'http://', 'https://', $realty_theme_option['map-marker-property']['url'] );
			} else {
			  $custom_marker_property_url = $realty_theme_option['map-marker-property']['url'];
			}
			$custom_marker_property = $realty_theme_option['map-marker-property'];
			$custom_marker_property_width_retina = $custom_marker_property['width'] / 2;
			$custom_marker_property_height_retina = $custom_marker_property['height'] / 2;
		}

		// Map Marker - Cluster
		$default_marker_cluster = $realty_theme_option['map-marker-cluster-default'];

		if ( ! empty( $realty_theme_option['map-marker-cluster']['url'] ) ) {
			if ( is_ssl() ) {
		    $custom_marker_cluster_url = str_replace( 'http://', 'https://', $realty_theme_option['map-marker-cluster']['url'] );
		  } else {
			  $custom_marker_cluster_url = $realty_theme_option['map-marker-cluster']['url'];
		  }
			$custom_marker_cluster = $realty_theme_option['map-marker-cluster'];
			$custom_marker_cluster_width_retina = $custom_marker_cluster['width'] / 2;
			$custom_marker_cluster_height_retina = $custom_marker_cluster['height'] / 2;
		}

		// Map Marker - Sync
		$default_marker_sync = $realty_theme_option['map-marker-property-sync'];

		if ( ! empty( $realty_theme_option['map-alternate-marker-property']['url'] ) ) {
			if ( is_ssl() ) {
				$custom_alt_marker_property_url = str_replace( 'http://', 'https://', $realty_theme_option['map-alternate-marker-property']['url'] );
			} else {
			  $custom_alt_marker_property_url = $realty_theme_option['map-alternate-marker-property']['url'];
			}
			$custom_alt_marker_property = $realty_theme_option['map-alternate-marker-property'];
			$custom_alt_property_width_retina = $custom_alt_marker_property['width'] / 2;
			$custom_alt_property_height_retina = $custom_alt_marker_property['height'] / 2;
		}
	?>

	<?php if ( ! empty( $realty_theme_option['map-marker-property']['url'] ) ) { // Custom map marker property ?>

		var customIcon = new google.maps.MarkerImage(
			'<?php echo $custom_marker_property['url']; ?>',
			null, // size is determined at runtime
		  null, // origin is 0,0
		  null, // anchor is bottom center of the scaled image
		  new google.maps.Size(<?php echo $custom_marker_property_width_retina; ?>, <?php echo $custom_marker_property_height_retina; ?>)
		);

	<?php } else { // Default map marker property ?>

		var customIcon = new google.maps.MarkerImage(
			'<?php echo $default_marker_property; ?>',
			null, // size is determined at runtime
		  null, // origin is 0,0
		  null, // anchor is bottom center of the scaled image
		  new google.maps.Size(50, 69)
		);

	<?php } ?>

	<?php if ( ! empty( $realty_theme_option['map-marker-cluster']['url'] ) ) { // Custom map marker cluster ?>

		var markerClusterOptions = {
			gridSize: 60, // Default: 60
			maxZoom: 14,
			styles: [{
				width: <?php echo $custom_marker_cluster_width_retina; ?>,
				height: <?php echo $custom_marker_cluster_height_retina; ?>,
				url: "<?php echo $custom_marker_cluster['url']; ?>"
			}]
		};

	<?php }	else { // Default map marker cluster ?>

		var markerClusterOptions = {
			gridSize: 60, // Default: 60
			maxZoom: 14,
			styles: [{
				width: 50,
				height: 50,
				url: "<?php echo $default_marker_cluster; ?>"
			}]
		};

	<?php } ?>

	<?php if ( $realty_theme_option['enable-vertical-listing-marker-sync'] ) { ?>

		<?php if ( ! empty( $realty_theme_option['map-alternate-marker-property']['url'] ) ) { // Custom map marker sync ?>
	    var customAltIcon = new google.maps.MarkerImage(
		    '<?php echo $custom_alt_marker_property_url; ?>',
		    null, // size is determined at runtime
		    null, // origin is 0,0
		    null, // anchor is bottom center of the scaled image
		    new google.maps.Size(<?php echo $custom_alt_property_width_retina; ?>, <?php echo $custom_alt_property_height_retina; ?>)
		  );
		<?php } else { // Default map marker sync ?>
			var customAltIcon = new google.maps.MarkerImage(
				'<?php echo $default_marker_sync; ?>',
			  null, // size is determined at runtime
			  null, // origin is 0,0
			  null, // anchor is bottom center of the scaled image
			  new google.maps.Size(50, 69)
			);
	  <?php } ?>

		jQuery('.property-item').on('mouseenter', function(){
			var data_sync_id = jQuery(this).attr('data-sync-id');
			newMarkers[data_sync_id].setIcon(customAltIcon);
		}).on('mouseleave', function(){
			var data_sync_id = jQuery(this).attr('data-sync-id');
			newMarkers[data_sync_id].setIcon(customIcon);
		});

	<?php } ?>

	<?php

	}
}

/**
 * Map controls
 *
 */
if ( ! function_exists( 'tt_script_map_controls' ) ) {
	function tt_script_map_controls( $map_id = 'map', $random ) {
		?>
		<script>
			function map_controls() {

				// Zoom In
				google.maps.event.addDomListener(document.getElementById('zoom-in-<?php echo $random; ?>'), 'click', function () {
					var currentZoom = <?php echo $map_id; ?>.getZoom();
					currentZoom++;
					if ( currentZoom > 19 ) {
						currentZoom = 19;
					}
					<?php echo $map_id; ?>.setZoom(currentZoom);
				});

				// Zoom Out
				google.maps.event.addDomListener(document.getElementById('zoom-out-<?php echo $random; ?>'), 'click', function () {
					var currentZoom = <?php echo $map_id; ?>.getZoom();
					currentZoom--;
					if ( currentZoom > 19 ) {
						currentZoom = 19;
					}
					<?php echo $map_id; ?>.setZoom(currentZoom);
				});

				// Map Type: Roadmap
				google.maps.event.addDomListener(document.getElementById('map-type-roadmap-<?php echo $random; ?>'), 'click', function () {
					<?php echo $map_id; ?>.setMapTypeId(google.maps.MapTypeId.ROADMAP);
				});

				// Map Type: Satellite
				google.maps.event.addDomListener(document.getElementById('map-type-satellite-<?php echo $random; ?>'), 'click', function () {
					<?php echo $map_id; ?>.setMapTypeId(google.maps.MapTypeId.SATELLITE);
				});

				// Map Type: Hybrid
				google.maps.event.addDomListener(document.getElementById('map-type-hybrid-<?php echo $random; ?>'), 'click', function () {
					<?php echo $map_id; ?>.setMapTypeId(google.maps.MapTypeId.HYBRID);
				});

				// Map Type: Terrain
				google.maps.event.addDomListener(document.getElementById('map-type-terrain-<?php echo $random; ?>'), 'click', function () {
					<?php echo $map_id; ?>.setMapTypeId(google.maps.MapTypeId.TERRAIN);
				});

				jQuery('.map-type li').click(function() {
					jQuery('.map-type li').removeClass('active');
					jQuery(this).addClass('active');
				});

				// Geolocation - Current Location
				jQuery('#current-location-<?php echo $random; ?>').click(function() {

					// Current Location Marker
					var markerCurrent = new google.maps.Marker({
					  //clickable: false,
					  icon: new google.maps.MarkerImage('//maps.gstatic.com/mapfiles/mobile/mobileimgs2.png',
						  new google.maps.Size(22,22),
						  new google.maps.Point(0,18),
						  new google.maps.Point(11,11)),
					  shadow: null,
					  zIndex: null,
					  map: <?php echo $map_id; ?>
					});

					jQuery(this).toggleClass('active');

					if ( !jQuery('#current-location-<?php echo $random; ?>').hasClass('draw') ) {
						// Create Loading Element
					  var loading = document.createElement('div');
				    loading.setAttribute( 'class', 'loader-container' );
				    loading.innerHTML = '<div class="svg-loader"></div>';
				    document.getElementById('<?php echo $map_id; ?>').appendChild(loading);
					}

					// Current Position
					if (navigator.geolocation) {

						navigator.geolocation.getCurrentPosition(function(current) {

					    var me = new google.maps.LatLng(current.coords.latitude, current.coords.longitude);
					    markerCurrent.setPosition(me);
							<?php echo $map_id; ?>.panTo(me);

							// Remove Loader
				    	loading.remove();

							// https://developers.google.com/maps/documentation/javascript/examples/circle-simple
							var currentRadiusCircleOptions = {
					      strokeColor: '#00CFF0',
					      strokeOpacity: 0.6,
					      strokeWeight: 2,
					      fillColor: '#00CFF0',
					      fillOpacity: 0.2,
					      map: <?php echo $map_id; ?>,
					      center: me,
					      visible: true,
					      radius: 1000 // Unit: meter
					    };

					    // When Initializing
							if ( !jQuery('#current-location-<?php echo $random; ?>').hasClass('draw') ) {

						    // Create Circle
						    currentRadiusCircle = new google.maps.Circle(currentRadiusCircleOptions);

							}

							jQuery('#current-location-<?php echo $random; ?>').addClass('draw');

							// Toggle Crrent Location Icon & Circle
							if ( jQuery('#current-location-<?php echo $random; ?>').hasClass('active') ) {
								markerCurrent.setMap(<?php echo $map_id; ?>);
								currentRadiusCircle.setMap(<?php echo $map_id; ?>);
							}
							else {
								markerCurrent.setMap(null);
								currentRadiusCircle.setMap(null);
							}

						});

					} else {
					  console.log("Current Position Not Found");
					}

					// Toggle Current Location Circle Visibility
					google.maps.event.addListener(markerCurrent, 'click', (function() {
						if ( currentRadiusCircle.getVisible() ) {
					  	currentRadiusCircle.set( 'visible', false );
						} else {
					  	currentRadiusCircle.set( 'visible', true );
						}
					}));

				});

			}
			google.maps.event.addDomListener(window, 'load', map_controls);
		</script>
		<?php
	}
}
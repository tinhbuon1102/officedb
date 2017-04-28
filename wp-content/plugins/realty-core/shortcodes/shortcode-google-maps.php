<?php
/**
 * Shortcode: "Google Maps"
 *
 */
if ( ! function_exists( 'tt_google_maps' ) ) {
	function tt_google_maps( $atts ) {

		extract( shortcode_atts( array(
			'id'                => rand(),
			'addresses'         => '',
			'logo_urls'         => '',
			'phone_numbers'     => '',
			'mobile_numbers'    => '',
			'email_addresses'   => '',
			'use_lat_lng'       => false,
			'latitudes'         => '',
			'longitudes'        => '',
			'height'            => '400',
			'zoom'              => '14',
			'scrollwheel'       => false,
			'streetviewcontrol' => false,
			'draggable'         => true,
			'disabledefaultui'  => true,
		), $atts));

		ob_start();

		global $realty_theme_option;

		$scrollwheel = ! empty( $scrollwheel ) ? 'true' : 'false';
		$streetviewcontrol = ! empty( $streetviewcontrol ) ? 'true' : 'false';
		$draggable = ! empty( $draggable ) ? 'true' : 'false';
		$disabledefaultui = ! empty( $disabledefaultui ) ? 'true' : 'false';
		?>

		<?php
			$addresses = preg_split( "/\\r\\n|\\r|\\n/", $addresses );
			$logo_urls = preg_split( "/\\r\\n|\\r|\\n/", $logo_urls );
			$phone_numbers = preg_split( "/\\r\\n|\\r|\\n/", $phone_numbers );
			$mobile_numbers = preg_split( "/\\r\\n|\\r|\\n/", $mobile_numbers );
			$email_addresses = preg_split( "/\\r\\n|\\r|\\n/", $email_addresses );
			$latitudes = preg_split( "/\\r\\n|\\r|\\n/", $latitudes );
			$longitudes = preg_split( "/\\r\\n|\\r|\\n/", $longitudes );

			$count_addresses = count( $addresses );
			$count_addresses = $count_addresses -1; // To account for $i starting at 0
		?>

		<script>
			<?php // Can't use random map var name, as property map search results won't sync anymore ?>
			var map_<?php echo $id; ?>, newMarkers_<?php echo $id; ?> = [], i = 0;

			function initMap() {

			  var mapOptions = {
			    zoom: <?php echo $zoom; ?>,
			    scrollwheel: <?php echo $scrollwheel; ?>,
			    streetViewControl: <?php echo $streetviewcontrol; ?>,
			    draggable: <?php echo $draggable; ?>,
			    disableDefaultUI: <?php echo $disabledefaultui; ?>,
			  };

			  map_<?php echo $id; ?> = new google.maps.Map(document.getElementById('map_<?php echo $id; ?>'), mapOptions);

			  <?php if ( $realty_theme_option['style-your-map'] ) { ?>
					if (map_options.map_style!=='') {
						var styles = JSON.parse(map_options.map_style);
						map_<?php echo $id; ?>.setOptions( { styles: styles } );
					}
				<?php } ?>

			  <?php echo tt_mapMarkers(); ?>

				<?php if ( is_array( $addresses ) ) { ?>

				<?php

					$i = 0;

					foreach ( $addresses as $address ) {

						if ( isset( $logo_urls[$i] ) ) {
							$logo_src = str_replace( '<br />', '', $logo_urls[$i] );
						} else {
							$logo_src = null;
						}

						if ( isset( $phone_numbers[$i] ) ) {
							$phone_number = str_replace( '<br />', '', $phone_numbers[$i] );
						} else {
							$phone_number = null;
						}

						if ( isset( $mobile_numbers[$i] ) ) {
							$mobile_number = str_replace( '<br />', '', $mobile_numbers[$i] );
						} else {
							$mobile_number = null;
						}

						if ( isset( $email_addresses[$i] ) ) {
							$email_id = str_replace( '<br />', '', $email_addresses[$i] );
						} else {
							$email_id = null;
						}

						if ( isset( $latitudes[$i] ) ) {
							$latitude = str_replace( '<br />', '', $latitudes[$i] );
						} else {
							$latitude = null;
						}

						if ( isset( $longitudes[$i] ) ) {
							$longitude = str_replace( '<br />', '', $longitudes[$i] );
						} else {
							$longitude = null;
						}
						?>

						<?php if ( ! empty( $address ) ) { ?>

			  		var address = "<?php echo $address; ?>";

						geocoder = new google.maps.Geocoder();

						geocoder.geocode( { "address": address}, function(results, status) {

					    if (status == google.maps.GeocoderStatus.OK) {

					      map_<?php echo $id; ?>.setCenter(results[0].geometry.location);

								<?php if ( $latitude && $longitude ) { ?>
							  	var latLng = new google.maps.LatLng(<?php echo  $latitude; ?>, <?php echo $longitude; ?>);
							  <?php } else { ?>
							  	var latLng = results[0].geometry.location;
							  <?php } ?>

					      var marker = new google.maps.Marker({
				          map: map_<?php echo $id; ?>,
				          position: latLng,
				          icon: customIcon
					      });

					      var logo 		= '<?php echo $logo_src; ?>';
					      var address = '<?php echo $address; ?>';
					      var phone 	= '<?php echo $phone_number; ?>';
					      var mobile 	= '<?php echo $mobile_number; ?>';
					      var email 	= '<?php echo antispambot( $email_id ); ?>';

					      // http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/docs/reference.html
								if ( address ) {

								  infobox = new InfoBox({
								  content: 	'<div class="map-marker-wrapper">'+
											'<div class="map-marker-container">'+
					  						'<div class="arrow-down"></div>'+
												<?php if ( $logo_src ) { ?>'<div class="logo"><img src="'+logo+'" style="max-width:50%" /></div>'+<?php } ?>
												'<div class="content">'+
												<?php if ( $address ) { ?>'<div class="contact-detail"><i class="icon-pin-full"></i>'+address+'</div>'+<?php } ?>
												<?php if ( $phone_number ) { ?>'<div class="contact-detail"><i class="icon-phone"></i>'+phone+'</div>'+<?php } ?>
												<?php if ( $mobile_number ) { ?>'<div class="contact-detail"><i class="icon-mobile"></i>'+mobile+'</div>'+<?php } ?>
												<?php if ( $email_id ) { ?>'<div class="contact-detail"><i class="icon-email"></i><a href="mailto:'+email+'">'+email+'</a></div>'+<?php } ?>
												'</div>'+
											'</div>'+
								    '</div>',
									  disableAutoPan: false,
									  pixelOffset: new google.maps.Size(-33, -90),
									  zIndex: null,
									  alignBottom: true,
									  closeBoxURL: "<?php echo get_template_directory_uri() . '/lib//images/close.png'; ?>",
									  infoBoxClearance: new google.maps.Size(60, 60)
									});

									var logo 	= '';
									var address = '';
									var phone 	= '';
									var mobile 	= '';
									var email 	= '';

						      newMarkers_<?php echo $id; ?>[i] = infobox;

									// Show infobox initially
								  infobox.open(map_<?php echo $id; ?>, marker);
								  map_<?php echo $id; ?>.panTo(results[0].geometry.location);

								  <?php if ( $i == $count_addresses ) { ?>
										infobox.show();
									<?php } else { ?>
										infobox.hide();
									<?php } ?>
								  google.maps.event.addListener(marker, 'click', function(i) {
							    	if ( infobox.getVisible() ) {
								    	infobox.hide();
							    	} else {
								    	infobox.show();
							    	}
							    	infobox.open(map_<?php echo $id; ?>, marker);
										map_<?php echo $id; ?>.panTo(results[0].geometry.location);
									});

						  	}

					    } else {
					      alert("Geocode was not successful for the following reason: " + status);
					    }

					  }); // geocode

						i++;

						<?php } // if ( ! empty( $address ) ?>

						<?php $i++; ?>

					<?php } // foreach ( $addresses as $address ) ?>

				<?php } // if ( is_array( $addresses ) ?>

			} // initMap

			google.maps.event.addDomListener(window, 'load', initMap);
			google.maps.event.addDomListener(window, 'resize', initMap);
		</script>

		<?php echo tt_script_map_controls( 'map_' . $id, $id ); ?>

		<?php wp_enqueue_script( 'google-maps-api' ); ?>

		<div class="map-wrapper" style="width: 100%; height: <?php echo $height . 'px'; ?>">

			<?php if ( $disabledefaultui == 'true' ) { ?>
				<ul class="map-controls list-unstyled">
					<li><a href="#" class="control zoom-in" id="zoom-in-<?php echo $id; ?>"><i class="icon-add"></i></a></li>
					<li><a href="#" class="control zoom-out" id="zoom-out-<?php echo $id; ?>"><i class="icon-subtract"></i></a></li>
					<li><a href="#" class="control map-type" id="map-type-<?php echo $id; ?>">
						<i class="icon-image"></i>
						<ul class="list-unstyled">
							<li id="map-type-roadmap-<?php echo $id; ?>" class="map-type"><?php esc_html_e( 'Roadmap', 'realty' ); ?></li>
							<li id="map-type-satellite-<?php echo $id; ?>" class="map-type"><?php esc_html_e( 'Satellite', 'realty' ); ?></li>
							<li id="map-type-hybrid-<?php echo $id; ?>" class="map-type"><?php esc_html_e( 'Hybrid', 'realty' ); ?></li>
							<li id="map-type-terrain-<?php echo $id; ?>" class="map-type"><?php esc_html_e( 'Terrain', 'realty' ); ?></li>
						</ul>
					</a></li>
				</ul>
			<?php } ?>

			<div class="google-map" id="map_<?php echo $id; ?>" style="width: 100%; height: <?php echo $height . 'px'; ?>"></div>
				<div class="loader-container">
					<div class="svg-loader"></div>
				</div>
			</div>

		</div>

		<?php
		return ob_get_clean();
	}
}
add_shortcode( 'google_maps', 'tt_google_maps' );

// Visual Composer Map
function tt_vc_map_shortcode_google_maps() {
	vc_map( array(
		'name' => esc_html__( 'Google Maps', 'realty' ),
		'base' => 'google_maps',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',	// icon-wpb-map-pin
		'desc' => esc_html__( 'Enter one longitude per line. Visit <a href="http://mondeca.com/index.php/en/any-place-en" target="_blank">http://mondeca.com/index.php/en/any-place-en</a> to retrieve coordinate of any address.', 'realty' ),
		'params' => array(
			array(
				'type' => 'textarea',
				'heading' => esc_html__( 'Addresses', 'realty' ),
				'param_name' => 'addresses',
				'value' => '',
				'description' => esc_html__( 'Multiple locations supported. Enter one address per line (same for logo, phone, mobile, email.', 'realty' ),
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__( 'Logo URLs', 'realty' ),
				'param_name' => 'logo_urls',
				'value' => '',
				'description' => esc_html__( 'Enter one full URL per line.', 'realty' ),
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__( 'Office Numbers', 'realty' ),
				'param_name' => 'phone_numbers',
				'value' => '',
				'description' => esc_html__( 'Enter one office number per line.', 'realty' ),
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__( 'Mobile Numbers', 'realty' ),
				'param_name' => 'mobile_numbers',
				'value' => '',
				'description' => esc_html__( 'Enter one mobile number per line.', 'realty' ),
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__( 'Email Addresses', 'realty' ),
				'param_name' => 'email_addresses',
				'value' => '',
				'description' => esc_html__( 'Enter one email address per line.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Height in pixel', 'realty' ),
				'param_name' => 'height',
				'value' => esc_html__( '400', 'realty' ),
				'description' => ''
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use Latitude/Longitude Instead Of Address For Map Marker', 'realty' ),
				'param_name' => 'use_lat_lng',
				'value' => array( '' => true ),
				'description' => esc_html__( 'Check to manually enter coordinates for more precious map markers.', 'realty' ),
			),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Latitude', 'realty' ),
				'param_name' => 'latitudes',
				'value' => '',
				'description' => esc_html__( 'Enter one latitude per line. Visit <a href="http://mondeca.com/index.php/en/any-place-en" target="_blank">http://mondeca.com/index.php/en/any-place-en</a> to retrieve coordinate of any address.', 'realty' ),
				'dependency' => array(
					'element' => 'use_lat_lng',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Longitude', 'realty' ),
				'param_name' => 'longitudes',
				'value' => '',
				'description' => esc_html__( 'Enter one longitude per line. Visit <a href="http://mondeca.com/index.php/en/any-place-en" target="_blank">http://mondeca.com/index.php/en/any-place-en</a> to retrieve coordinate of any address.', 'realty' ),
				'dependency' => array(
					'element' => 'use_lat_lng',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Zoom level', 'realty' ),
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
				'std' => 13
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Scroll wheel', 'realty' ),
				'param_name' => 'scrollwheel',
				'value' => array( '' => true ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Street view controls', 'realty' ),
				'param_name' => 'streetviewcontrol',
				'value' => array( '' => true ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Draggable', 'realty' ),
				'param_name' => 'draggable',
				'value' => array( '' => true ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Disable Default UI', 'realty' ),
				'param_name' => 'disabledefaultui',
				'value' => array( '' => true ),
				'description' => esc_html__( 'If checked, show custom map controls.', 'realty' ),
			),
		),
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_shortcode_google_maps' );
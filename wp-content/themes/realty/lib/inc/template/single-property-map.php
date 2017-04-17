<?php
	$property_title_map = $realty_theme_option['property-title-map'];
	if ( $property_title_map && ! is_page_template( 'template-property-submit.php' ) ) {
		//echo '<h3 class="section-title"><span>' . $property_title_map . '</span></h3>';
		if ( $address ) {
		 //echo '<p class="text-muted">' . $address . '</p>';
		}
	}
?>

<?php echo do_shortcode( '[property_map single_property_id="' . $single_property_id . '" latitude="' . $address_latitude . '" longitude="' . $address_longitude . '" address="'. $address . '"]' ); ?>
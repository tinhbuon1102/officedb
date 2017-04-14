<?php
/**
 * Shortcode: Property Custom Search Form
 *
 */
if ( ! function_exists( 'tt_property_custom_search_form' ) ) {
	function tt_property_custom_search_form( $atts ) {

	  extract( shortcode_atts( array(
		  'search_form_columns' => 3,
			'location'            => '',
			'type'                => '',
			'status'              => '',
			'features'            => '',
			'id'                  => '',
			'price'               => '',
			'size'                => '',
			'rooms'               => '',
			'bedrooms'            => '',
			'bathrooms'           => '',
			'garages'             => '',
			'search_keyword'      => '',
			'available_from'      => '',
			'minprice'            => '',
			'maxprice'            => '',
			'pricerange'          => '',
		), $atts ) );

		ob_start();

		if ( isset( $search_form_columns ) ) {
			$search_form_columns = $search_form_columns;
		}

		$search_fields = array();
		$search_labels = array();
		$search_parameters = array();
		$search_id = '[custom_property_search_form';

		if ( $location ) {
			$search_fields[] = 'estate_property_location';
			$search_labels[] = $location;
			$search_parameters[] = 'estate_property_location';
			$search_id .= ' ' . 'location="' . $location . '"';
		}
		if ( $type ) {
			$search_fields[] = 'estate_property_type';
			$search_labels[] = $type;
			$search_parameters[] = 'estate_property_type';
			$search_id .= ' ' . 'type="' . $type . '"';
		}
		if ( $status ) {
			$search_fields[] = 'estate_property_status';
			$search_labels[] = $status;
			$search_parameters[] = 'estate_property_status';
			$search_id .= ' ' . 'status="' . $status . '"';
		}
		if ( $id ) {
			$search_fields[] = 'estate_property_id';
			$search_labels[] = $id;
			$search_parameters[] = 'estate_property_id';
			$search_id .= ' ' . 'id="' . $id . '"';
		}
		if ( $price ) {
			$search_fields[] = 'estate_property_price';
			$search_labels[] = $price;
			$search_parameters[] = 'estate_property_price';
			$search_id .= ' ' . 'price="' . $price . '"';
		}
		if ( $size ) {
			$search_fields[] = 'estate_property_size';
			$search_labels[] = $size;
			$search_parameters[] = 'estate_property_size';
			$search_id .= ' ' . 'size="' . $size . '"';
		}
		if ( $rooms ) {
			$search_fields[] = 'estate_property_rooms';
			$search_labels[] = $rooms;
			$search_parameters[] = 'estate_property_rooms';
			$search_id .= ' ' . 'rooms="' . $rooms . '"';
		}
		if ( $bedrooms ) {
			$search_fields[] = 'estate_property_bedrooms';
			$search_labels[] = $bedrooms;
			$search_parameters[] = 'estate_property_bedrooms';
			$search_id .= ' ' . 'bedrooms="' . $bedrooms . '"';
		}
		if ( $bathrooms ) {
			$search_fields[] = 'estate_property_bathrooms';
			$search_labels[] = $bathrooms;
			$search_parameters[] = 'estate_property_bathrooms';
			$search_id .= ' ' . 'bathrooms="' . $bathrooms . '"';
		}
		if ( $garages ) {
			$search_fields[] = 'estate_property_garages';
			$search_labels[] = $garages;
			$search_parameters[] = 'estate_property_garages';
			$search_id .= ' ' . 'garages="' . $garages . '"';
		}
		if ( $search_keyword ) {
			$search_fields[] = 'estate_search_by_keyword';
			$search_labels[] = $search_keyword;
			$search_parameters[] = 'estate_search_by_keyword';
			$search_id .= ' ' . 'search_keyword="' . $search_keyword . '"';
		}
		if ( $available_from ) {
			$search_fields[] = 'estate_property_available_from';
			$search_labels[] = $available_from;
			$search_parameters[] = 'estate_property_available_from';
			$search_id .= ' ' . 'available_from="' . $available_from . '"';
		}
		if ( $minprice ) {
			$search_fields[] = 'estate_property_price_min';
			$search_labels[] = $minprice;
			$search_parameters[] = 'estate_property_price_min';
			$search_id .= ' ' . 'minprice="' . $minprice . '"';
		}
		if ( $maxprice ) {
			$search_fields[] = 'estate_property_price_max';
			$search_labels[] = $maxprice;
			$search_parameters[] = 'estate_property_price_max';
			$search_id .= ' ' . 'maxprice="' . $maxprice . '"';
		}
		if ( $pricerange ) {
			$search_fields[] = 'estate_property_pricerange';
			$search_labels[] = $pricerange;
			$search_parameters[] = 'estate_property_pricerange';
			$search_id .= ' ' . 'pricerange="' . $pricerange . '"';
		}
		$search_id .= ']';

		if ( $search_id ) {
			set_query_var( 'property_search_id', base64_encode( $search_id ) );
		}
		if ( ! tt_is_array_empty( $search_fields ) ) {
			set_query_var( 'property_search_fields', $search_fields );
		}
		if ( ! tt_is_array_empty( $search_labels ) ) {
			set_query_var( 'property_search_labels', $search_labels );
		}
		if ( ! tt_is_array_empty($search_parameters ) ) {
			set_query_var( 'property_search_parameters', $search_parameters );
		}

		//get_template_part( 'lib/inc/template/search-form' );
		include( locate_template( 'lib/inc/template/search-form.php' ) );

		return ob_get_clean();

	}
}
add_shortcode( 'property_multiple_search_form', 'tt_property_custom_search_form' ); // obsolete since renaming in v2.4
add_shortcode( 'custom_property_search_form', 'tt_property_custom_search_form' );

// Visual Composer Map
function tt_vc_map_realty_custom_property_search_form() {
	vc_map( array(
		'name' => esc_html__( 'Property Custom Search Form', 'realty' ),
		'base' => 'custom_property_search_form',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Search Form Columns', 'realty' ),
				'param_name' => 'search_form_columns',
				'value' => array(
					esc_html__( '1 Column', 'realty' )  => 1,
					esc_html__( '2 Columns', 'realty' ) => 2,
	        esc_html__( '3 Columns', 'realty' ) => 3,
	        esc_html__( '4 Columns', 'realty' ) => 4,
				),
				'std' => 3
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Location Label', 'realty' ),
				'param_name' => 'location',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Type Label', 'realty' ),
				'param_name' => 'type',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Status Label', 'realty' ),
				'param_name' => 'status',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Features Label', 'realty' ),
				'param_name' => 'features',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'ID Label', 'realty' ),
				'param_name' => 'id',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Price Label', 'realty' ),
				'param_name' => 'price',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Size Label', 'realty' ),
				'param_name' => 'size',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Rooms Label', 'realty' ),
				'param_name' => 'rooms',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Bedrooms Label', 'realty' ),
				'param_name' => 'bedrooms',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Bathrooms Label', 'realty' ),
				'param_name' => 'bathrooms',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Garages Label', 'realty' ),
				'param_name' => 'garages',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Search Keyword Label', 'realty' ),
				'param_name' => 'search_keyword',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Available From Label', 'realty' ),
				'param_name' => 'available_from',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Min. Price Label', 'realty' ),
				'param_name' => 'minprice',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Max. Price Label', 'realty' ),
				'param_name' => 'maxprice',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Price Range Label', 'realty' ),
				'param_name' => 'pricerange',
				'value' => '',
				'description' => esc_html__( 'Entering a field label will add this field to this custom property search.', 'realty' ),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_realty_custom_property_search_form' );
<?php
/**
 * Shortcode: Single Property
 *
 */
if ( ! function_exists( 'tt_realty_single_property' ) ) {
	function tt_realty_single_property( $atts ) {

		extract( shortcode_atts( array(
			'id'		=> null
		), $atts ) );

		$query_properties_args = array(
			'post_type' 			=> 'property',
			'posts_per_page' 	=> 1,
			'page_id' 				=> $id
		);

		$query_properties = new WP_Query( $query_properties_args );

		$args = array(
			'post_type'      => 'property',
			'posts_per_page' => 1,
		);

		ob_start();

		if ( $query_properties->have_posts() ) :
			while ( $query_properties->have_posts() ) : $query_properties->the_post();
				get_template_part( 'lib/inc/template/property-item' );
			endwhile;
		wp_reset_query();
		endif;

		return ob_get_clean();

	}
}
add_shortcode( 'single_property', 'tt_realty_single_property' );

// Visual Composer Map
function tt_vc_map_realty_single_property() {

	$property_args = array(
		'post_type'      => 'property',
		'posts_per_page' => -1,
	);

	$property_query = new WP_Query( $property_args );

	$property_ids = array();

	while ( $property_query->have_posts() ) : $property_query->the_post();
		$property_title = get_the_title() . ' (ID: ' . get_the_id() . ')';
		$properties[$property_title] = get_the_id();
	endwhile;

	vc_map( array(
		'name' => esc_html__( 'Single Property', 'realty' ),
		'base' => 'single_property',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Property', 'realty' ),
				'param_name' => 'id',
				'value' =>  $properties,
				'description' => '',
			),
		)
	) );
}
// add_action( 'vc_before_init', 'tt_vc_map_realty_single_property' );
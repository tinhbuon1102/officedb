<?php
/**
 * Shortcode: Property Search Form
 *
 */
if ( ! function_exists( 'tt_realty_property_search_form' ) ) {
	function tt_realty_property_search_form( $atts ) {

		extract( shortcode_atts( array(
			'search_form_columns' => 3,
			'search_type' => 'full',
		), $atts ) );

		if ( isset( $search_form_columns ) && !empty( $search_form_columns ) ) {
			$search_form_columns = $search_form_columns;
		}

		/*
		if ( isset( $search_results_columns ) ) {
			$search_results_columns = $search_results_columns;
		}
		*/

		ob_start();
			if ($search_type == 'full')
				include( locate_template( 'lib/inc/template/search-form.php' ) );
			else
				include( locate_template( 'lib/inc/template/search-form-mini.php' ) );
			
		return ob_get_clean();

	}
}
add_shortcode( 'property_search_form', 'tt_realty_property_search_form' );

// Visual Composer Map
function tt_vc_map_realty_property_search_form() {
	vc_map( array(
		'name' => esc_html__( 'Property Search Form', 'realty' ),
		'base' => 'property_search_form',
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
			/*
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Search Results Columns', 'realty' ),
				'param_name' => 'search_results_columns',
				'value' => array(
					esc_html__( '1 Column', 'realty' )  => 1,
					esc_html__( '2 Columns', 'realty' ) => 2,
	        esc_html__( '3 Columns', 'realty' ) => 3,
	        esc_html__( '4 Columns', 'realty' ) => 4,
				),
				'std' => 3
			),
			*/
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_realty_property_search_form' );
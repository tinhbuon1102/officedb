<?php
/**
 * Shortcode: Section Title
 *
 */
if ( ! function_exists( 'tt_realty_section_title' ) ) {
	function tt_realty_section_title( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'heading'			=> 'h1',
			'style'				=> '1',
			'text_align'	=> 'left'
		), $atts ) );

		if ( $text_align == 'center' ) {
			$text_align = 'text-center';
		} else if ( $text_align == 'right' ) {
			$text_align = 'text-right';
		} else {
			$text_align = 'text-left';
		}

		if ( $heading ) {
			return '<' . $heading . ' class="section-title style' . $style . ' ' . $text_align . '"><span>' . do_shortcode( $content ) . '</span></' . $heading . '>';
		} else {
			return '<h1 class="section-title"><span>' . do_shortcode( $content ) . '</span></h1>';
		}

	}
}
add_shortcode( 'section_title', 'tt_realty_section_title' );

// Visual Composer Map
function tt_vc_map_realty_section_title() {
	vc_map( array(
		'name' => esc_html__( 'Section Title', 'realty' ),
		'base' => 'section_title',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Title', 'realty' ),
				'param_name' => 'content',
				//'value' => '',
				'description' => '',
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Element tag', 'realty' ),
				'param_name' => 'heading',
				'value' => array(
					'h1',
					'h2',
					'h3',
					'h4',
					'h5',
					'h6',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Style', 'realty' ),
				'param_name' => 'style',
				'value' => array(
					__( 'Style 1', 'realty' ) => '1',
					__( 'Style 2', 'realty' ) => '2',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Text align', 'realty' ),
				'param_name' => 'text_align',
				'value' => array(
					__( 'left', 'realty' ) => 'left',
					__( 'center', 'realty' ) => 'center',
					__( 'right', 'realty' ) => 'right',
				),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_realty_section_title' );

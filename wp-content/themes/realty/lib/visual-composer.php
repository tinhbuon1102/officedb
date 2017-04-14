<?php
/**
	* Visual Composer
	* https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524297
	*/
function tt_vc_set_as_theme() {
	vc_set_as_theme( $disable_updater = true );
}
add_action( 'vc_before_init', 'tt_vc_set_as_theme' );

// Remove VC Elements
vc_remove_element( 'vc_btn' );
// xxx-check-before-release
vc_remove_element( 'vc_images_carousel' );
vc_remove_element( 'vc_gmaps' );
vc_remove_element( 'vc_message' );
vc_remove_element( 'vc_posts_slider' );

// Shortcode: vc_row
vc_remove_param( 'vc_row', 'full_width' ); // VC's default "Row Stretch"

vc_add_param('vc_row', array(
	'type'        => 'checkbox',
	'heading'     => esc_html__( 'Full width row?', 'realty' ),
	'param_name'  => 'full_width',
	'description' => esc_html__( 'If checked row spans over full site width.', 'realty' ),
	'value'       => array( esc_html__( 'Yes', 'realty' ) => 'full_width' ),
	'weight'      => 1,
));

vc_add_param('vc_row', array(
	'type'        => 'checkbox',
	'heading'     => esc_html__( 'Background Overlay', 'realty' ),
	'param_name'  => 'background_overlay',
	'description' => esc_html__( 'If checked you can set an overlay for this row container.', 'realty' ),
	'value'       => array( esc_html__( 'Yes', 'realty' ) => 'full_width' ),
	'weight'      => 1,
));

vc_add_param('vc_row', array(
	'type'        => 'colorpicker',
	'heading'     => esc_html__( 'Overlay Color', 'realty' ),
	'param_name'  => 'background_overlay_color',
	'description' => '',
	'value'       => 'rgba(0,0,0,0.3)',
	'weight'      => 1,
	'dependency'  => array(
		'element'     => 'background_overlay',
		'not_empty'   => true,
	),
));

vc_add_param('vc_row', array(
	'type'        => 'checkbox',
	'heading'     => esc_html__( 'Background Slideshow', 'realty' ),
	'param_name'  => 'slideshow_bg',
	'description' => esc_html__( 'If checked, slideshow will be used as row background.', 'realty' ),
	//'value'       => array( esc_html__( 'Yes', 'realty' ) => 'full_width' ),
	'weight'      => 1,
));

vc_add_param('vc_row', array(
	'type'        => 'attach_images',
	'heading'     => esc_html__( 'Background Slideshow Images', 'realty' ),
	'param_name'  => 'slideshow_bg_url',
	'description' => '',
	'value'       => '',
	'weight'      => 1,
	'dependency'  => array(
		'element'     => 'slideshow_bg',
		'not_empty'   => true,
	),
));

// Shortcode: vc_toggle
vc_remove_param( 'vc_toggle', 'color' );
vc_remove_param( 'vc_toggle', 'size' );
vc_remove_param( 'vc_toggle', 'style' );


// Shortcode: vc_tta_accordion
vc_remove_param( 'vc_tta_accordion', 'title' );
vc_remove_param( 'vc_tta_accordion', 'style' );
vc_remove_param( 'vc_tta_accordion', 'shape' );
vc_remove_param( 'vc_tta_accordion', 'color' );
vc_remove_param( 'vc_tta_accordion', 'no_fill' );
vc_remove_param( 'vc_tta_accordion', 'spacing' );
vc_remove_param( 'vc_tta_accordion', 'gap' );
vc_remove_param( 'vc_tta_accordion', 'c_align' );
vc_remove_param( 'vc_tta_accordion', 'autoplay' );
vc_remove_param( 'vc_tta_accordion', 'c_icon' );
vc_remove_param( 'vc_tta_accordion', 'c_position' );

// Shortcode: vc_tta_tabs
vc_remove_param( 'vc_tta_tabs', 'title' );
vc_remove_param( 'vc_tta_tabs', 'style' );
vc_remove_param( 'vc_tta_tabs', 'shape' );
vc_remove_param( 'vc_tta_tabs', 'color' );
vc_remove_param( 'vc_tta_tabs', 'no_fill_content_area' );
vc_remove_param( 'vc_tta_tabs', 'spacing' );
vc_remove_param( 'vc_tta_tabs', 'gap' );
vc_remove_param( 'vc_tta_tabs', 'alignment' );
//vc_remove_param( 'vc_tta_tabs', 'autoplay' ); if removed, causes error in v4.11.2
vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
vc_remove_param( 'vc_tta_tabs', 'pagination_color' );

// Shortcode: vc_tta_tour
vc_remove_param( 'vc_tta_tour', 'title' );
vc_remove_param( 'vc_tta_tour', 'style' );
vc_remove_param( 'vc_tta_tour', 'shape' );
vc_remove_param( 'vc_tta_tour', 'color' );
vc_remove_param( 'vc_tta_tour', 'no_fill_content_area' );
vc_remove_param( 'vc_tta_tour', 'spacing' );
vc_remove_param( 'vc_tta_tour', 'gap' );
vc_remove_param( 'vc_tta_tour', 'alignment' );
vc_remove_param( 'vc_tta_tour', 'controls_size' );
//vc_remove_param( 'vc_tta_tour', 'autoplay' ); if removed, causes error in v4.11.2
vc_remove_param( 'vc_tta_tour', 'pagination_style' );
vc_remove_param( 'vc_tta_tour', 'pagination_color' );

// Shortcode: vc_tta_pageable
vc_remove_param( 'vc_tta_pageable', 'pagination_color' );
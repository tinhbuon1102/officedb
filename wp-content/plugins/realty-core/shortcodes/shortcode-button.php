<?php
/**
 * Shortcode: "Button"
 *
 */
if ( ! function_exists( 'tt_shortcode_button' ) ) {
	function tt_shortcode_button( $atts ) {

		extract( shortcode_atts( array(
			'type'   => 'primary',
			'size'   => '',
			'text'   => esc_html__( 'Click me', 'realty' ),
			'url'    => '',
			'target' => '',
		), $atts));

		$size = ( ! empty( $size) ) ? ' btn-' . $size : null;
		$target = ( $target == '_blank' ) ? ' target="_blank"' : null;
		$url = vc_build_link( $url );
		return '<a href="' . $url['url'] . '"' . $target . ' class="btn btn-' . $type . $size . '">' . $text . '</a>';

	}
}
add_shortcode( 'realty_button', 'tt_shortcode_button' );

// Visual Composer Map
function tt_vc_map_shortcode_button() {
	vc_map( array(
		'name' => esc_html__( 'Button', 'realty' ),
		'base' => 'realty_button',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Type', 'realty' ),
				'param_name' => 'type',
				'value' => array(
					esc_html__( 'Primary', 'realty' ) => 'primary',
					esc_html__( 'Light', 'realty' ) => 'light',
					esc_html__( 'Dark', 'realty' ) => 'dark',
					esc_html__( 'Ghost primary', 'realty' ) => 'ghost-primary',
					esc_html__( 'Ghost light', 'realty' ) => 'ghost-light',
					esc_html__( 'Ghost dark', 'realty' ) => 'ghost-dark',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Size', 'realty' ),
				'param_name' => 'size',
				'value' => array(
					esc_html__( 'Medium', 'realty' ) => '',
					esc_html__( 'Small', 'realty' ) => 'sm',
					esc_html__( 'Large', 'realty' ) => 'lg',
				),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Text', 'realty' ),
				'param_name' => 'text',
				'value' => esc_html__( 'Click me', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'URL', 'realty' ),
				'param_name' => 'url',
				'value' => 'http://yourcompany.com',
				'description' => esc_html__( 'Make sure to include http:// or https:// protocol at the beginning of your URL.', 'realty' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Open link in new tab', 'realty' ),
				'param_name' => 'target',
				'description' => '',
				'value' => array( esc_html__( 'Yes', 'realty' ) => '_blank' ),
			),
		),
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_shortcode_button' );
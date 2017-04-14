<?php
/**
 * Shortcode: "Notification"
 *
 */
if ( ! function_exists( 'tt_shortcode_notification' ) ) {
	function tt_shortcode_notification( $atts ) {

		extract( shortcode_atts( array(
			'type'        => 'info',
			'text'        => esc_html__( 'Notification text goes here..', 'realty' ),
			'dismissable' => false,
			'url'         => '',
			'target'      => '',
		), $atts));

		$url = vc_build_link( $url );
		$target = ( $target == '_blank' ) ? ' target="_blank"' : null;

		$classes = array();
		$classes[] = 'alert';
		$classes[] = 'alert-' . $type;
		if ( $dismissable ) {
			$classes[] = 'alert-dismissable';
		}
		$classes = join( ' ', $classes );

		$output = '<p class="' . $classes .'">';

		if ( $dismissable ) {
			$output .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		}

		if ( $url['url'] ) {
			$output .= '<a href="' . $url['url'] . '"' . $target . ' >';
		}

		$output .= $text;

		if ( $url['url'] ) {
			$output .= '</a>';
		}

		$output .= '</p>';

		return $output;

	}
}
add_shortcode( 'alert', 'tt_shortcode_notification' ); // Backwards-compatibility
add_shortcode( 'realty_notification', 'tt_shortcode_notification' );

// Visual Composer Map
function tt_vc_map_shortcode_notification() {
	vc_map( array(
		'name' => esc_html__( 'Notification', 'realty' ),
		'base' => 'realty_notification',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Type', 'realty' ),
				'param_name' => 'type',
				'value' => array(
					esc_html__( 'Info', 'realty' ) => 'info',
					esc_html__( 'Success', 'realty' ) => 'success',
					esc_html__( 'Warning', 'realty' ) => 'warning',
					esc_html__( 'Danger', 'realty' ) => 'danger',
				),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Text', 'realty' ),
				'param_name' => 'text',
				'value' => esc_html__( 'Notification text goes here..', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Dismissable', 'realty' ),
				'param_name' => 'dismissable',
				'description' => '',
				//'value' => array( esc_html__( 'Yes', 'realty' ) => '_blank' ),
			),
			array(
				'type' => 'vc_link',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Link to', 'realty' ),
				'param_name' => 'url',
				'value' => '',
				'description' => esc_html__( 'Make sure to include http:// or https:// protocol at the beginning of your URL.', 'realty' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Open link in new tab', 'realty' ),
				'param_name' => 'target',
				'description' => '',
				'value' => array( esc_html__( 'Yes', 'realty' ) => '_blank' ),
				'dependency' => array(
					'element' => 'url',
					'not_empty' => true,
				),
			),
		),
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_shortcode_notification' );
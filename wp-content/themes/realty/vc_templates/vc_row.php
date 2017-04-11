<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */
$el_class = $full_height = $full_width = $equal_height = $flex_row = $background_overlay = $background_overlay_color = $content_placement = $parallax = $parallax_image = $css = $el_id = $slideshow_bg = $slideshow_bg_url = $video_bg = $video_bg_url = $video_bg_parallax = '';
$before_output = $output = $after_output = '';
$before_output = '<div class="container">';
$after_output = '</div>';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class );

$css_classes = array(
	'vc_row',
	// 'wpb_row', //deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);
$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

/**
	* Mechanic Theme: Custom Full Width Paramater
	*/

if ( ! empty( $full_width ) ) {
	$css_classes[] = 'full-width';
	$before_output = '<div class="full-width">';
	$after_output = '</div>';
} else {
	$css_classes[] = 'boxed';
}

if ( ! empty( $background_overlay ) && ! empty( $background_overlay_color ) ) {
	$css_classes[] = 'overlay';
	//$wrapper_attributes[] = 'style="background: ' . $background_overlay_color . '"';
	$background_overlay = '<div class="background-overlay" style="background: ' . $background_overlay_color . '"></div>';
}

/*
if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $content_placement ) ) {
		$css_classes[] = ' vc_row-o-content-' . $content_placement;
	}
}
*/

if ( ! empty( $full_height ) ) {
	$css_classes[] = 'vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-o-columns-' . $columns_placement;
		if ( 'stretch' === $columns_placement ) {
			$css_classes[] = 'vc_row-o-equal-height';
		}
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = 'vc_row-flex';
}

$has_slideshow_bg = ( ! empty( $slideshow_bg ) && ! empty( $slideshow_bg_url ) );

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_image = $video_bg_url;
	$css_classes[] = ' vc_video-bg-container';
	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}

if ( ! empty( $parallax ) ) {
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="1.5"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];
		}
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}

if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= $before_output;

if ( $slideshow_bg_url ) {
	$slideshow_bg_url = explode( ',', $slideshow_bg_url );

	$output .= '<div class="hide-initially" id="row_background_slider">';

	foreach ( $slideshow_bg_url as $slideshow_image ) {
		$image_url = wp_get_attachment_image_src( $slideshow_image, 'full' );
		$output .= '<div style="background: url(' . $image_url[0] . '); height: 100vh; background-repeat: no-repeat; background-size: cover; background-position: center center"></div>';
	}

	$output .= '</div>';

	$slider_params = array(
		'id'                => 'row_background_slider',
		'images_to_show'    => 1,
		'images_to_show_lg' => 1,
		'images_to_show_md' => 1,
		'images_to_show_sm' => 1,
		'autoplay'          => true,
		'autoplay_speed'    => 5000,
		'fade'              => true,
		'infinite'          => true,
		'show_arrows'       => false,
		'show_arrows_below' => false,
		'show_dots'         => false,
		'show_dots_below'   => false,
	);

	$output .= tt_script_slick_slider( $slider_params );
}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
$output .= wpb_js_remove_wpautop( $content );
$output .= $background_overlay;
$output .= '</div>';
$output .= $after_output;

echo $output;
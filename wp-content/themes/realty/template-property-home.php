<?php
/*
Template Name: Single Property Home Page
*/
get_header();

$page_template_single_property = get_post_meta( $post->ID, 'estate_single_property_id', true );
include( locate_template( 'single-property.php') );

get_footer();
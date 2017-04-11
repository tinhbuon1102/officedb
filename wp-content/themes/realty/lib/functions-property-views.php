<?php
/**
 * Count property views, sets
 *
 */
if ( ! function_exists( 'tt_get_property_views' ) ) {
	function tt_get_property_views( $postID ) {

    $count_key = 'estate_property_views_count';
    $count = get_post_meta( $postID, $count_key, true );

    if ( $count == '' ) {
      delete_post_meta( $postID, $count_key );
      add_post_meta( $postID, $count_key, '0' );
      return 0;
    }

    return $count;

	}
}

if ( ! function_exists( 'property_views_load' ) ) {
	function property_views_load() {

		global $wp_query, $tt_property_views;

		// Check if we're on a singular post view
		if ( is_singular() ) {
			// Get the post object
			$post = $wp_query->get_queried_object();

			if ( get_post_type( $post ) == 'property' ) {
				// Set the post ID for later use because we wouldn't want a custom query to change this
				$tt_property_views = $post->ID;

				/* Enqueue the jQuery library.
				wp_enqueue_script( 'jquery' );
				*/

				// Load the property views JavaScript in the footer
				add_action( 'wp_footer', 'property_views_load_scripts' );
			}
		}

	}
}
add_action( 'template_redirect', 'property_views_load' );

if ( ! function_exists( 'property_views_load_scripts' ) ) {
	function property_views_load_scripts() {

		global $tt_property_views;

		// Create a nonce for the AJAX request
		$nonce = wp_create_nonce( 'property_views_ajax' );

		// Display JavaScript
		echo '<script type="text/javascript">/* <![CDATA[ */ jQuery(document).ready( function() { jQuery.post( "' . admin_url( 'admin-ajax.php' ) . '", { action : "entry_views", _ajax_nonce : "' . $nonce . '", post_id : ' . $tt_property_views. ' } ); } ); /* ]]> */</script>' . "\n";

	}
}
add_action( 'wp_ajax_entry_views', 'property_views_update_ajax' );
add_action( 'wp_ajax_nopriv_entry_views', 'property_views_update_ajax' );

if ( ! function_exists( 'property_views_update_ajax' ) ) {
	function property_views_update_ajax() {

		// Check the AJAX nonce to make sure this is a valid request
		check_ajax_referer( 'property_views_ajax' );

		// If the post ID is set, set it to the $post_id variable and make sure it's an integer
		if ( isset( $_POST['post_id'] ) ) {
			$post_id = absint( $_POST['post_id'] );
		}

		// If $post_id isn't empty, pass it to the property_views_update() function to update the view count
		if ( ! empty( $post_id ) ) {
			property_views_update( $post_id );
		}

	}
}

if ( ! function_exists( 'property_views_update' ) ) {
	function property_views_update( $post_id = '' ) {
		global $wp_query;

		// If we're on a singular view of a post, calculate number of views
		if ( ! empty( $post_id ) ) {

			// Allow devs to override the meta key used. By default, this is 'Views'
			$meta_key = 'estate_property_views_count';

			// Get the number of views the post currently has.
			$old_views = get_post_meta( $post_id, $meta_key, true );

			// Increment views count
			$new_views = absint( $old_views ) + 1;

			// Update view count
			update_post_meta( $post_id, $meta_key, $new_views, $old_views );
		}

	}
}
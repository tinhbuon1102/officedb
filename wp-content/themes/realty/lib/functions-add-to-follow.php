<?php
/**
 * AJAX - Follow
 *
 */
if ( ! function_exists('tt_ajax_add_remove_follow') ) {
	function tt_ajax_add_remove_follow() {

		$user_id = $_GET['user'];
		$property_id = $_GET['property'];

		// Get follow Meta Data
		$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()

		// No User Meta Data follow Found -> Add Data
		if ( !$get_user_meta_follow ) {
			$create_follow = array($property_id);
			add_user_meta( $user_id, 'realty_user_follow', $create_follow );
		}
		// Meta Data Found -> Update Data
		else {
			// Add New Follow
			if ( ! in_array( $property_id, $get_user_meta_follow[0] ) ) {
				array_unshift( $get_user_meta_follow[0], $property_id ); // Add To Beginning Of follow Array
				update_user_meta( $user_id, 'realty_user_follow', $get_user_meta_follow[0] );
			}
			// Remove Follow
			else {
				$removeFollowFromPosition = array_search( $property_id, $get_user_meta_follow[0] );
				unset( $get_user_meta_follow[0][$removeFollowFromPosition] );
				update_user_meta( $user_id, 'realty_user_follow', $get_user_meta_follow[0] );
			}
		}

	}
}
add_action('wp_ajax_tt_ajax_add_remove_follow', 'tt_ajax_add_remove_follow');

/**
 * Follow - Click
 *
 */
if ( ! function_exists( 'tt_add_remove_follow' ) ) {
	function tt_add_remove_follow( $property_id = 0 ) {

		global $realty_theme_option;

		if ( $realty_theme_option['property-follow-disabled'] )
		return;

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		// Logged-In User
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()

			if ( ! empty( $get_user_meta_follow ) && in_array( $property_id, $get_user_meta_follow[0] ) ) {
				// Follow: true
				$favicon = '<i class="add-to-follow icon-email-1" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Unsubscribe From Email Updates', 'realty' ) . '"></i>';
			} else {
				// Follow: false
				$favicon = '<i class="add-to-follow icon-email" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Subscribe To Email Updates', 'realty' ) . '"></i>';
			}
		} else {
			// Not Logged-In Visitor
			$favicon = '<i class="add-to-follow icon-email" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Subscribe To Email Updates', 'realty' ) . '"></i>';
		}

		return $favicon;

	}
}

/**
 * Follow - Script
 *
 */
if ( ! function_exists( 'tt_follow_script' ) ) {
	function tt_follow_script() {

		global $realty_theme_option;
		?>

		<script>
		jQuery('body').on("click",'.add-to-follow',function() {

			<?php if ( is_user_logged_in() ) { // Logged-In User ?>

					// Toggle Follow Tooltips
					if ( jQuery(this).hasClass('icon-email-1') ) {
						jQuery(this).attr('data-original-title', '<?php esc_html_e( 'Unsubscribe From Email Updates', 'realty' ); ?>');
					}

					if ( jQuery(this).hasClass('icon-email') ) {
						jQuery(this).attr('data-original-title', '<?php esc_html_e( 'Subscribe To Email Updates', 'realty' ); ?>');
					}

					if (jQuery('#single_subscribe_text').length)
						jQuery('#single_subscribe_text').text(jQuery(this).attr('data-original-title'));

					jQuery(this).find('i').toggleClass('icon-email icon-email-1');
					jQuery(this).closest('i').toggleClass('icon-email icon-email-1');

					<?php if ( is_user_logged_in() ) { ?>
						<?php $user_id = get_current_user_id();	?>
						jQuery.ajax({
						  type: 'GET',
						  url: ajax_object.ajax_url,
						  data: {
						    'action'        :   'tt_ajax_add_remove_follow', // WP Function
						    'user'					: 	<?php echo $user_id; ?>,
						    'property'			: 	jQuery(this).attr('data-fol-id')
						  },
						  success: function (response) { },
						  error: function () { }
						});
					<?php	} ?>

				<?php } else { // Not Logged-In Visitor - Show Modal ?>
					jQuery('a[href="#tab-login"]').tab('show');
					jQuery('#login-modal').modal();
					jQuery('#msg-login-to-add-follow').removeClass('hide');
					jQuery('#msg-login-to-add-follow').addClass('hide');
				<?php } ?>

			});
		</script>

	<?php
	}
}
add_action( 'wp_footer', 'tt_follow_script', 21 );


/**
 * Sent Message when property is updated.
 *
 */
if ( ! function_exists( 'tt_property_updated_send_email' ) ) {
	function tt_property_updated_send_email( $post_id ) {

		// If this is just a revision, don't send the email.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$users = get_users( 'meta_key=realty_user_follow' );

		foreach ( $users as $user ) {
			$follows = get_user_meta( $user->ID, 'realty_user_follow', true );

			foreach ( $follows as $follow ) {
				if( $post_id == $follow ) {

					$post_title = get_the_title();
					$post_url = get_permalink();
					$subject = esc_html__( 'A property that you follow has been updated', 'realty' ) . ' | ' . get_bloginfo( 'name' );

					$message = '<h2 style="margin-bottom: 0.5em">' . $post_title . '</h2>';

					if ( has_post_thumbnail() ) {
						$message .=  '<a href="' . $post_url . '">' . wp_get_attachment_image( get_post_thumbnail_id(), 'thumbnail' ) . '</a>';
					}

					$message .= '<p><a href="' . $post_url . '">' . $post_url  . '</a></p>';
					$message .= '<div style="height:1px; margin: 1em 0; background-color:#eee"></div>';
					$message .= '<p style="color: #999">' . esc_html__( 'TpTo unsubscribe from update notifications about this property please follow the link above, then click the envelope icon next to the property title.', 'realty' ) . '</p>';

					$headers[] = "From: $user->display_name <$user->user_email>";
					$headers[] = "Content-Type: text/html; charset=UTF-8";
					add_filter( 'wp_mail_content_type', 'tt_set_html_content_type_plugin' );
					// Send email to user.
					// wp_mail( $user->user_email, $subject, $message, $headers );
					wp_mail( $user->user_email, $subject, $message, $headers );

					remove_filter( 'wp_mail_content_type', 'tt_set_html_content_type_plugin' );

				}
			}

		}

	}
}
add_action( 'save_post', 'tt_property_updated_send_email' );

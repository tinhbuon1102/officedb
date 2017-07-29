<?php
/*
Template Name: User - Profile
*/
?>

<?php if ( ! empty( $_POST['submit'] ) ) { ?>

	<?php
		$allowed_file_types = array( "image/gif", "image/jpeg", "image/jpg", "image/png" );
		$upload_errors = '';
	?>

	<?php if ( ! empty( $_FILES['user_image']['name'] ) ) { // User Image Upload ?>

		<?php
			// Check to make sure its a successful upload
			if ( $_FILES['user_image']['error'] !== UPLOAD_ERR_OK ) __return_false();

			if ( ! in_array( $_FILES['user_image']['type'], $allowed_file_types ) ) {
		    	$upload_errors .= '<p class="alert alert-danger" role="alert" >' . esc_html__( 'Invalid file type:', 'realty' ) . ' "' . $_FILES['user_image']['type'] . '". ' . esc_html__( 'Supported file types: gif, jpg, jpeg, png.', 'realty' ) . '</p>';
	  	}

	  	// Max. file size 5 MB
			if ( $_FILES['user_image']['size'] > 5000000 ) {
				$upload_errors .= '<p class="alert alert-danger" role="alert" >' . esc_html__( 'File is too large. Max. upload file size is 5 MB.', 'realty' ) . '</p>';
			}

			if ( ! $upload_errors ) {

				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				$attachment_id = media_handle_upload( 'user_image', 0 );
				$attachment_url = wp_get_attachment_url( $attachment_id );
				// Upload Profile Picture
				update_user_meta( get_current_user_id(), 'user_image', $attachment_url );

			}
		?>

	<?php } ?>

	<?php if ( ! $upload_errors ) { ?>

		<?php
			// Update user profile information
			wp_update_user(
				array(
					'ID'                  => get_current_user_id(),
					'company_name'        => $_POST['company_name'],
					'first_name'          => $_POST['first_name'],
					'last_name'           => $_POST['last_name'],
					'office_phone_number' => $_POST['office_phone_number'],
					'mobile_phone_number' => $_POST['mobile_phone_number'],
					'fax_number'          => $_POST['fax_number'],
					'user_email'          => $_POST['user_email'],
					'user_url'            => $_POST['user_url'],
					'description'         => $_POST['description'],
					'custom_facebook'     => $_POST['custom_facebook'],
					'custom_twitter'      => $_POST['custom_twitter'],
					'custom_google'       => $_POST['custom_google'],
					'custom_linkedin'     => $_POST['custom_linkedin'],
				)
			);
		?>

		<?php
			// Update password, if not empty
			if ( $_POST['user_pass'] != '' ) {
				wp_update_user(
					array(
						'ID'        => get_current_user_id(),
						'user_pass' => $_POST['user_pass']
					)
				);
			}
		?>

	<?php } ?>

<?php } ?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="page-user-profile">

		<?php
			if ( is_user_logged_in() ) {
				the_content();
			}
		?>

		<div id="main-content" class="container primary-tooltips">

			<?php if ( is_user_logged_in() ) { ?>

				<?php
					$user_id = get_current_user_id();
					$userdata = get_userdata( $user_id );
				?>

				<?php
					if ( ! empty( $_POST['submit'] ) ) {
						// Submitted -> Check For Errors
						if ( $upload_errors ) {
							echo $upload_errors;
						} else {
							echo '<p class="alert alert-success" role="alert">' . esc_html__( 'Profile successfully updated.', 'realty' ) . '</p>';
						}
					}
				?>

				<div class="row">

					<div class="col-md-8">
						<?php echo do_shortcode('[wppb-edit-profile]')?>
					</div>

					<div class="col-md-4">
						<?php
							get_template_part( 'lib/inc/template/user-menu' );
							get_template_part( 'lib/inc/template/user-subscribed-package' );
						?>
					</div>

				</div><!-- .row -->

			<?php } else { ?>
				<p class=" alert alert-info"><?php esc_html_e( 'Login to view and edit your profile.', 'realty' ); ?></p>
			<?php } ?>

		</div>

	<script>
	function previewProfilePicture( input ) {
	  if ( input.files && input.files[0] ) {
	    var reader = new FileReader();
	    reader.onload = function (e) {
	        jQuery('#preview-user-image').attr( 'src', e.target.result );
	    }
	    reader.readAsDataURL( input.files[0] );
	  }
	}

	jQuery('#user_image').change(function() {
		previewProfilePicture(this);
	});

	// AJAX Property Submit - Delete Uploaded Image
	jQuery('.delete-uploaded-image').click(function() {

		jQuery.ajax({
	    type: 'POST',
	    url: ajax_object.ajax_url,
	    data: {
		    'action'          :   'tt_ajax_delete_user_profile_picture_function', // WP Function
		    'user_id'    			:   jQuery(this).attr('data-user-id')
	    },
	    success: function (response) {
				jQuery('#preview-user-image').attr('src', '//placehold.it/400x400/eee/ccc/&text=..');
	    },
	    error: function (response) {
	    	// Error Message
	    }
	  });

	});

	var delete_user_text1 = '<?php echo trans_text('Are you sure to unregister from membership ?')?>';
	var delete_user_text2 = '<?php echo trans_text('Your account will be deleted and can not be undone')?>';
	var delete_user_success = '<?php echo trans_text('Your account are deleted')?>';
	
	jQuery('body').on('click', '#delete_user', function(){
		if (confirm(delete_user_text1)) {
			if (confirm(delete_user_text2)) {
				jQuery('body').LoadingOverlay("show");
				jQuery.ajax({
				    type: 'POST',
				    url: ajax_object.ajax_url,
				    dataType: 'json',
				    data: {
					    'action':   'tt_ajax_delete_user_profile', // WP Function
					    'user_id':   jQuery(this).attr('data-user-id')
				    },
				    success: function (response) {
				    	jQuery('body').LoadingOverlay("hide");
					    if (response.success)
					    {
					    	alert(delete_user_success);
					    	location.href = response.redirect;
					    }
				    },
				    error: function (response) {
				    	// Error Message
				    	jQuery('body').LoadingOverlay("hide");
				    }
				});
			}
		}
	});
	</script>

<?php endwhile; ?>

<?php get_footer(); ?>
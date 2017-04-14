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
						<form id="profile-edit" enctype="multipart/form-data" method="post">

							<h3><?php printf( esc_html__( "Welcome %s!", 'realty' ), $userdata->user_login ); ?></h3>

							<div class="row">

								<div class="col-sm-4">

									<div class="form-group" style="margin-bottom: 1em">
										<label for="user_image" class="hide"><?php esc_html_e( 'Profile Image', 'realty' ); ?> <i class="icon-info-circle" data-toggle="tooltip" title="<?php esc_html_e( '(JPEG, JPG, PNG, GIF. Max: 5 MB)', 'realty' ); ?>"></i></label>
										<p style="position: relative; margin-top: 30px">
											<?php if ( $userdata->user_image ) { ?>
												<?php
													$profile_image_id = tt_get_image_id( $userdata->user_image );
													$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
												?>
												<img id="preview-user-image" src="<?php echo $profile_image_array[0]; ?>" alt="" />
												<i class="icon-close delete-uploaded-image" data-user-id="<?php echo get_current_user_id(); ?>"></i>
											<?php } else { ?>
												<img id="preview-user-image" class="placeholder-avatar" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder-avatar-400x400.png'; ?>" alt="" />
											<?php } ?>
										</p>
										<input type="file" name="user_image" id="user_image" />
									</div>

								</div>

								<div class="col-sm-8">

									<!--
									<div class="form-group hide">
										<label for="user_name"><?php esc_html_e( 'Username', 'realty' ); ?> <i class="icon-info-circle" data-toggle="tooltip" title="<?php esc_html_e( 'Usernames cannot be changed.', 'realty' ); ?>"></i></label>
										<input type="text" name="user_name" id="user_name" class="form-control text-muted" value="<?php echo $userdata->user_login; ?>" disabled />
									</div>
									-->

									<div class="form-group">
										<label for="first_name"><?php esc_html_e( 'First Name', 'realty' ); ?></label>
										<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $userdata->first_name; ?>" />
									</div>

									<div class="form-group">
										<label for="last_name"><?php esc_html_e( 'Last Name', 'realty' ); ?></label>
										<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $userdata->last_name; ?>" />
									</div>

									<div class="form-group">
										<label for="user_email"><?php esc_html_e( 'Email Address', 'realty' ); ?> <i class="icon-info-circle" data-toggle="tooltip" title="<?php esc_html_e( 'Send property contact form messages to this address.', 'realty' ); ?>"></i></label>
										<input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo $userdata->user_email; ?>" />
									</div>

								</div>


							</div><!-- .row -->

							<div class="form-group">
								<label for="description"><?php esc_html_e( 'About', 'realty' ); ?></label>
								<textarea name="description" id="description" class="form-control text-muted" rows="5"><?php echo esc_attr( $userdata->description ); ?></textarea>
							</div>

							<div class="form-group">
								<label for="company_name"><?php esc_html_e( 'Company Name', 'realty' ); ?></label>
								<input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo esc_attr( $userdata->company_name ); ?>" />
							</div>

							<div class="form-group">
								<label for="office_phone_number"><?php esc_html_e( 'Phone', 'realty' ); ?></label>
								<input type="text" name="office_phone_number" id="office_phone_number" class="form-control" value="<?php echo esc_attr( $userdata->office_phone_number ); ?>" />
							</div>

							<div class="form-group">
								<label for="mobile_phone_number"><?php esc_html_e( 'Mobile', 'realty' ); ?></label>
								<input type="text" name="mobile_phone_number" id="mobile_phone_number" class="form-control" value="<?php echo esc_attr( $userdata->mobile_phone_number ); ?>" />
							</div>

							<div class="form-group">
								<label for="fax_number"><?php esc_html_e( 'Fax', 'realty' ); ?></label>
								<input type="text" name="fax_number" id="fax_number" class="form-control" value="<?php echo esc_attr( $userdata->fax_number ); ?>" />
							</div>

							<div class="form-group">
								<label for="custom_facebook"><?php esc_html_e( 'Facebook', 'realty' ); ?></label>
								<input type="url" name="custom_facebook" id="custom_facebook" class="form-control" value="<?php echo esc_url( $userdata->custom_facebook ); ?>" placeholder="http://facebook.com" />
							</div>

							<div class="form-group">
								<label for="custom_google"><?php esc_html_e( 'Google+', 'realty' ); ?></label>
								<input type="url" name="custom_google" id="custom_google" class="form-control" value="<?php echo esc_url( $userdata->custom_google ); ?>" placeholder="http://google.com" />
							</div>

							<div class="form-group">
								<label for="user_url"><?php esc_html_e( 'Website', 'realty' ); ?></label>
								<input type="url" name="user_url" id="user_url" class="form-control" value="<?php echo esc_url( $userdata->user_url ); ?>" placeholder="http://yourcompany.com" />
							</div>

							<div class="form-group">
								<label for="custom_twitter"><?php esc_html_e( 'Twitter', 'realty' ); ?></label>
								<input type="url" name="custom_twitter" id="custom_twitter" class="form-control" value="<?php echo esc_url( $userdata->custom_twitter ); ?>" placeholder="http://twitter.com" />
							</div>

							<div class="form-group">
								<label for="custom_linkedin"><?php esc_html_e( 'Linkedin', 'realty' ); ?></label>
								<input type="url" name="custom_linkedin" id="custom_linkedin" class="form-control" value="<?php echo esc_url( $userdata->custom_linkedin ); ?>" placeholder="http://linkedin.com" />
							</div>

							<div class="form-group">
								<label for="user_pass"><?php esc_html_e( 'Password', 'realty' ); ?></label>
								<input type="text" name="user_pass" id="user_pass" class="form-control" value="" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" />
							</div>

							<div class="form-group">
								<input type="submit" name="submit" id="submit-profile-update" class="form-control" value="<?php echo esc_attr( 'Save Changes', 'realty' ); ?>" />
							</div>

						</form>
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
	</script>

<?php endwhile; ?>

<?php get_footer(); ?>
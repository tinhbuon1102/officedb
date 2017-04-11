<?php
	/*
	Template Name: Property Submit
	*/
	acf_form_head();
	get_header();
	acf_enqueue_uploader();
?>

<?php
	$is_assigned_agent = false;
	$property_id = 0;
	$post_status = 'pending';

	global $realty_theme_option;
?>

<?php
	// Check If We Create Or Edit A Property
	if ( isset( $_GET['edit'] ) && ! empty( $_GET['edit'] ) ) {

		// Edit Property
		$edit = $_GET['edit'];
		$property_id = $edit;
		$property = get_post( $edit );
		$property_author = '';

		if ( get_post_type ( $property) == 'property' ) {
			$assigned_agent = get_post_meta( $property_id, 'estate_property_custom_agent', true );
			$property_author = $property->post_author ;
			if ( get_current_user_id() == $assigned_agent ) {
				$is_assigned_agent = true;
			}
		}
		$acf_form_post_id = $edit;
		$submit_value = esc_html__( 'Update Property', 'realty' );
		$updated_message = '<div class="alert alert-success alert-dismissable">' . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . esc_html__( 'Property update successful.', 'realty' ) . '</div>';

	} else {

		// New Property
		$edit = null;
		$acf_form_post_id = 'new_post';
		$submit_value = esc_html__( 'Submit Property', 'realty' );
		$updated_message = '<div class="alert alert-success alert-dismissable">' . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . esc_html__( 'Property has been published.', 'realty' ) . '</div>';

	}
?>

<?php if ( is_user_logged_in() ) { ?>

	<?php
		global $current_user;
		$current_user = wp_get_current_user();
		$current_user_role = $current_user->roles[0];
		$allow_submit_all = false;

		// User Role "Agent" and "Admin" can publish, "Subscriber" is "pending"
		if ( $current_user_role == 'agent' || current_user_can( 'manage_options' ) ) {

			$post_status = 'publish';
			$allow_submit_all = true;

		} else {

			$post_status = 'pending';
			$submit_type = $realty_theme_option['property-submission-type'];

			if ( $submit_type == 'per-listing' ) {
				$updated_message = '<div class="alert alert-success alert-dismissable">' . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . esc_html__( 'Property is submitted. To publish it go to "My Properties" and make payment.', 'realty' ) . '</div>';
			} else if ( $submit_type == 'membership' ) {
				if ( ! $edit ) {
					$updated_message = '<div class="alert alert-success alert-dismissable">' . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . esc_html__( 'Property is submitted and published.', 'realty' ) . '</div>';
				}
			} else {
				$updated_message = '<div class="alert alert-success alert-dismissable">' . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . esc_html__( 'Property is submitted. It will be published soon.', 'realty' ) . '</div>';
			}
		}

		$allowed_submit_on_membership = false;
	?>

	<?php if ( $realty_theme_option['property-submission-type'] == 'membership' && $current_user_role == 'subscriber' ) { ?>

		<?php
			//echo tt_is_user_membership_valid($current_user->ID);
			$valid_member = tt_is_user_membership_valid( $current_user->ID );
		?>

		<?php if ( $valid_member == 1 ) { ?>

			<?php if ( tt_user_remaining_listings( $current_user->ID ) > 0 || tt_user_remaining_featured_listings( $current_user->ID ) > 0 || tt_user_remaining_listings( $current_user->ID ) == 'Unlimited' || tt_user_remaining_featured_listings( $current_user->ID ) == 'Unlimited' ) { ?>

				<?php
					$allowed_submit_on_membership = true;
					$post_status = 'publish';
					$remaining_listings = tt_user_remaining_listings( $current_user->ID );
					$featured_listings = tt_user_remaining_featured_listings( $current_user->ID );
					$package_id = get_user_meta( $current_user->ID, 'subscribed_package_default_id', true );
					$package_title = get_the_title( $package_id );
				?>

			<?php } else { ?>

					<?php
					$allowed_submit_on_membership = true;
					$post_status = 'pending';
					$remaining_listings = tt_user_remaining_listings( $current_user->ID );
					$featured_listings = tt_user_remaining_featured_listings( $current_user->ID );
					$package_id = get_user_meta( $current_user->ID, 'subscribed_package_default_id', true );
					$package_title = get_the_title( $package_id );
				?>

				<?php } ?>

				<div class="container">
					<p class="alert alert-info">
						<?php printf( esc_html__( "Your Package: %s | Remaining Listings %s | Remaining Featured Listings: %s" , 'realty' ), $package_title, $remaining_listings, $featured_listings ); ?>
					</p>
				</div>

			<?php } // $valid_member == 1 ?>

		<?php } ?>

<?php } ?>

<?php
	// http://www.advancedcustomfields.com/resources/acf_form/
	$form_options = array(
		'post_id'         => $acf_form_post_id,
		'post_title'      => true,
		'post_content'    => true,
		'return'          => get_permalink() . '?updated=true',
		'form_attributes' => array(
			'id' => 'property-submit',
		),
		'new_post'		    => array(
			'post_type'   => 'property',
			'post_status' => $post_status
		),
		'submit_value'    => $submit_value,
		'updated_message' => $updated_message,
	);
?>

<?php tt_page_banner();	?>

<div class="container">
	<div id="main-content">

		<?php if ( ! $edit ) { ?>
			<h1 class="section-title"><span><?php esc_html_e( 'Submit New Property', 'realty' ); ?></span></h1>
		<?php } ?>

		<?php if ( is_user_logged_in() && ( ( $property_id == 0 || get_current_user_id() == $property_author ) || $is_assigned_agent ) || current_user_can( 'manage_options' )  ) { ?>

			<?php if ( $realty_theme_option['property-submission-type'] == 'per-listing' && !$realty_theme_option['paypal-alerts-hide'] && ( $current_user_role == "subscriber" && !$realty_theme_option['property-submit-disabled-for-subscriber'] && ( get_post_status( $property_id ) != 'publish' || $property_id == 0 ) ) ) { ?>

				<p class="alert alert-info alert-dismissable property-payment-note">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo esc_html__( 'Publishing Fee', 'realty' ) . ': ' . $realty_theme_option['paypal-currency-code'] . ' ' . $realty_theme_option['paypal-amount']; ?>

					<?php if ( doubleval($realty_theme_option['paypal-featured-amount']) > 0 ) { ?>
						<?php echo ' | ' . esc_html__( '"Featured" upgrade', 'realty' ) . ': ' . $realty_theme_option['paypal-currency-code'] . ' ' . $realty_theme_option['paypal-featured-amount']; ?>
					<?php }	?>
				</p>

				<p class="alert alert-info alert-dismissable property-payment-note-2">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php
						if ( $realty_theme_option['paypal-auto-publish'] ) {
							esc_html_e( 'Property will be published automatically after payment completion.', 'realty' );
						} else {
							esc_html_e( 'Property will be published manually after payment completion.', 'realty' );
						}
					?>
				</p>

		  <?php } ?>

		  <?php if ( $current_user_role == 'subscriber' && $realty_theme_option['property-submit-disabled-for-subscriber'] ) { ?>
				<p class="alert alert-danger"><?php esc_html_e( 'As a subscriber you do not have permission to submit or edit properties.', 'realty' ); ?></p>
			<?php } else { ?>

				<?php if ( $realty_theme_option['property-submission-type'] == 'membership' ) { ?>

					<?php if ( $allowed_submit_on_membership || $allow_submit_all ) { ?>
						<?php acf_form( $form_options ); ?>
					<?php } else { ?>
						<p class="alert alert-info"><?php esc_html_e( 'Your subscription package is either expired or you have reached your allowed number of listings. Please visit your profile page and check the status or select a package to subscribe.', 'realty' ); ?></p>
						<?php echo do_shortcode('[membership_packages]'); ?>
					<?php } ?>

				<?php }	else { ?>

					<?php acf_form( $form_options ); ?>

				<?php } ?>

			<?php } ?>

		<?php } else { ?>

			<p class="alert alert-info"><?php esc_html_e( 'You have to be logged-in to submit properties.', 'realty' ); ?></p>

			<?php echo tt_login_form(); ?>

		<?php } ?>

	</div>
</div>

<?php get_footer(); ?>
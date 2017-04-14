<?php get_header(); ?>

<div class="container">

	<?php global $realty_theme_option; ?>

	<?php if ( $realty_theme_option['property-submission-type'] == 'membership' ) { ?>

		<div class="row">
			<div class="col-sm-6">

				<?php
					$active = get_post_meta( $post->ID, 'estate_if_package_active', true );
					$subscription_unit = get_post_meta( $post->ID, 'estate_package_period_unit', true );
					$number_of_ocurrances = get_post_meta( $post->ID, 'estate_package_valid_renew', true );
					$regular_listings = get_post_meta( $post->ID, 'estate_package_allowed_listings', true );
					$featured_listings = get_post_meta( $post->ID, 'estate_package_allowed_featured_listings', true );
					$package_price = get_post_meta( $post->ID, 'estate_package_price', true );
					$stripe_id = get_post_meta( $post->ID, 'estate_package_stripe_id', true );
					$user_role = '';
				?>

				<?php if ( is_user_logged_in() ) { ?>

					<?php
						global $current_user;
						$current_user = wp_get_current_user();
						$user_role = $current_user->roles[0];
					?>

					<?php if ( $user_role == 'subscriber' ) { ?>

						<?php
							$user_listings = get_user_meta( $current_user->ID, 'subscribed_listing_remaining', true );
							$user_featured = get_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', true );
							$free_subscribed_before = get_user_meta( $current_user->ID, 'subscribed_free_package_once', true );
							$package_id = get_user_meta ( $current_user->ID, 'subscribed_package_default_id', true );
							$package = get_post( $package_id );
						?>

						<?php if ( tt_is_user_membership_valid( $current_user->ID ) == 1 ) { ?>

							<?php if ( $user_listings <= $regular_listings || $user_featured <= $featured_listings || $user_listings == 'Unlimited' ) { ?>

								<p class="alert alert-info">
									<?php printf( esc_html__( 'You are already subscribed to %s. Listings are available. If you still want to subscribe to this package proceed to make payment. Doing so makes your current package obsolete, as you can\'t have two active packages at the same time.', 'realty' ), $package->post_title ); ?>
								</p>

							<?php } else { ?>

							<p class="alert alert-info">
								<?php printf( esc_html__( 'You are already subscribed to %s, but your publishing quota is used up. Proceed to make payment for this package in order to publish more properties.', 'realty' ), $package->post_title ); ?>
							</p>

							<?php } ?>

						<?php } ?>

					<?php } ?>

				<?php }	?>

				<?php include( locate_template( 'lib/inc/template/membership-package-item.php') ); ?>

				<?php if ( is_user_logged_in() ) { ?>

					<?php if ( isset( $_GET['payment'] ) && $_GET['payment'] == 'failed') { ?>
						<p class="alert alert-danger">
							<?php esc_html_e( 'Payment Failed! Please try again with correct payment information.', 'realty' ); ?>
						</p>
					<?php } ?>

					<?php if ( $user_role != 'agent' && ! current_user_can( 'manage_options' )  ) { ?>

			    	<?php if ( $package_price > 0 ) { // Not Free Package ?>

						  <ul class="list-unstyled" style="margin-bottom: 1em">
								<li style="margin-bottom: 1em">
									<div class="package-paypal-button">
										<?php
											if ( $realty_theme_option['paypal-enable'] ) {
												echo tt_package_payment_button( $post->ID );
											}
										?>
									</div>
								</li>
								<li style="margin-bottom: 1em">
									<?php
										if ( $realty_theme_option['enable-stripe-payments'] ) {
											tt_stripe_payment_form( $post->ID );
										}
									?>
								</li>
							</ul>

			        <?php } else { // Free Package ?>

						    <?php if ( isset( $_GET['subscribe_for_free'] ) && $_GET['subscribe_for_free'] == 'confirmed' ) { ?>

									<?php if ( $free_subscribed_before == 'Yes' ) { ?>
										<p class="alert alert-danger"><?php esc_html_e( 'You already subscribed to free package. Please go for other premium packages.', 'realty' ); ?></p>
									<?php } else { ?>
										<p class="alert alert-info"><?php esc_html_e( 'You are successfully subscribed.', 'realty' ); ?></p>
									  <?php
										  $invoice_id = tt_create_user_invoice( $current_user->ID, $post->ID, '' );
											update_user_meta( $current_user->ID, 'subscribed_free_package_once', 'Yes' );
									  ?>
									<?php } ?>

								<?php }	else { ?>
								   <a class="btn btn-primary" style="background-color: <?php echo esc_attr( $realty_theme_option['paypal-enable'] ); ?>" href="?subscribe_for_free=confirmed"><?php esc_html_e( 'Subscribe', 'realty' );?></a>
								<?php } ?>

						<?php } ?>

					<?php } else { // Role "subscriber ?>
						<p class="alert alert-info"><?php esc_html_e( 'You are logged in as admin or agent. Please login as "Subscriber" to subscribe to this package.', 'realty' ); ?></p>
					<?php } ?>

				<?php } else { // Not logged-in visitor ?>
					<p class="alert alert-info"><?php esc_html_e( 'You are not logged in. Please login as "Subscriber" to subscribe to this package.', 'realty' ); ?></p>
				<?php } ?>

			</div>
			<div class="col-sm-6">
				<?php
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
				?>
			</div>
		</div><!-- .row -->

	<?php } else { // Memberships aren't enabled ?>
		<p class="alert alert-info"><?php esc_html_e( 'This page is not available, memberships are not enabled.', 'realty' ); ?></p>
	<?php } ?>

</div><!-- .container -->

<?php get_footer(); ?>
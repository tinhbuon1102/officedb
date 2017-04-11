<?php if ( is_user_logged_in() ) { ?>

	<?php
		$user_id = get_current_user_id();
		$userdata = get_userdata( $user_id );
	?>

	<?php if ( $userdata->subscribed_package != '' ) { ?>

		<?php
			$package_subscribed = $userdata->subscribed_package;
			$remain_listings = $userdata->subscribed_listing_remaining;

			if ( $remain_listings == -1 ) {
				$remain_listings = 'Unlimited';
			}

			$remain_listings_featured = $userdata->subscribed_featured_listing_remaining;
			if ( $remain_listings_featured == -1 ) {
				$remain_listings_featured = 'Unlimited';
			}

			$package_id = $userdata->subscribed_package_default_id;
			$package_title = get_the_title( $package_id );
		?>

		<div class="widget-memebership-package border-box">
			<h4><?php esc_html_e( 'Subscribed Package:', 'realty' ); ?></h4>
			<ul class="list-unstyled">
				<li class="lead"><?php echo $package_title; ?></li>
				<li><?php esc_html_e( 'Remaining Listings:', 'realty' ); ?> <?php echo $remain_listings; ?></li>
				<li><?php esc_html_e( 'Remaining Featured Listings: ', 'realty' ); ?> <?php echo $remain_listings_featured; ?></li>
			</ul>
		</div>

	<?php } ?>

<?php } ?>
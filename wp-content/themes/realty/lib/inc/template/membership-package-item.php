<div class="property-item border-box">

	<div class="property-title">
		<h3 class="title" style="text-align:center;">
			<?php the_title(); ?>
		</h3>
  </div>

  <?php
	  $subscription_unit_singular = substr( $subscription_unit, 0 , -1 );
  ?>

	<div class="property-content">
		<div class="property-meta clearfix">
			<div style="width: 70%">
				<div class="meta-data"><?php esc_html_e( 'Regular Listings', 'realty' ); ?></div>
			</div>
			<div style="width: 30%">
				<div class="meta-data"><?php echo $regular_listings; ?></div>
			</div>
			<div style="width: 70%">
				<div class="meta-data"><?php esc_html_e( 'Featured Listings', 'realty' ); ?></div>
			</div>
			<div style="width: 30%">
				<div class="meta-data"><?php echo $featured_listings; ?></div>
			</div>
			<div style="width: 70%">
				<div class="meta-data"><?php esc_html_e( 'Subscription Period', 'realty' ); ?></div>
			</div>
			<div style="width: 30%">
				<div class="meta-data"><?php echo $number_of_ocurrances . ' ' . _n( $subscription_unit_singular, $subscription_unit, $number_of_ocurrances, 'realty' ); ?></div>
			</div>
		</div>
		<div class="property-price">
			<?php
				echo esc_html__( 'Price:', 'realty' ) . ' ';

				if ( $package_price == 0 ) {
					esc_html_e( 'Free', 'realty' );
				} else {
					echo $realty_theme_option['currency-sign'] . $package_price;
				}
			?>
		</div>
	</div>

</div>
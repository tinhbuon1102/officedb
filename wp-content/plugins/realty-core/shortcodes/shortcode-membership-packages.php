<?php
/**
 * Shortcode: Membership Packages
 *
 */
if ( ! function_exists( 'tt_membership_packages' ) ) {
	function tt_membership_packages( $atts ) {

		extract( shortcode_atts( array(
			'columns' => 3
		), $atts ) );

		if ( isset ( $columns ) ) {
			if ( $columns == '4' ) {
				$columns = 'col-md-3 col-sm-4';
			} else if ( $columns == '3' ) {
				$columns = 'col-md-4 col-sm-6';
			} else if ( $columns == '2' ) {
				$columns = 'col-md-6 col-sm-12';
			} else if ( $columns == '1' ) {
				$columns = 'col-md-12';
			}
		} else {
			$columns = 'col-md-4 col-sm-6';
		}

		ob_start();

		global $realty_theme_option;
	?>

	<?php if ( $realty_theme_option['property-submission-type'] == 'membership' ) { ?>

		<?php
			$query_package_args = array(
				'post_type'    => 'package',
				'orderby'      => 'date',
				'order'        => 'ASC',
				'post_status'  => 'publish',
			);

			$query_packages = new WP_Query( $query_package_args);
		?>

		<div class="property-items show-compare subscriptions " data-view="grid-view" style="opacity: 1;">
			<ul class="row list-unstyled">

				<?php if ( $query_packages->have_posts() ) : ?>

					<?php while ( $query_packages->have_posts() ) : $query_packages->the_post(); ?>

						<?php
							$active = get_post_meta( get_the_ID(), 'estate_if_package_active', true );
							$subscription_unit = get_post_meta( get_the_ID(), 'estate_package_period_unit', true );
							$number_of_ocurrances = get_post_meta(get_the_ID(), 'estate_package_valid_renew', true );
							$regular_listings = get_post_meta( get_the_ID(), 'estate_package_allowed_listings', true );
							$featured_listings = get_post_meta( get_the_ID(), 'estate_package_allowed_featured_listings', true );
							$package_price = get_post_meta( get_the_ID(), 'estate_package_price', true );
							$stripe_id = get_post_meta( get_the_ID(), 'estate_package_stripe_id', true );

							if ( $regular_listings == -1 ) {
								$regular_listings = 'Unlimited';
							}

							if ( $featured_listings == -1 ) {
								$featured_listings = 'Unlimited';
							}
						?>

						<?php if ( $active == true ) { ?>
							<li class="<?php echo esc_attr( $columns );?>">
								<?php include( locate_template( 'lib/inc/template/membership-package-item.php' ) ); ?>
								<?php
									if ( is_user_logged_in() ) {
										$subscribe_url = get_permalink( get_the_ID() );
									} else {
										$subscribe_url = '?login';
									}
								?>
								<a href="<?php echo esc_attr( $subscribe_url ); ?>">
									<input type="button" value="<?php esc_html_e( 'Subscribe', 'realty' ); ?>" class="subscribe-button btn btn-primary btn-block form-control">
								</a>
							</li>
						<?php } ?>

					<?php endwhile; ?>

					<?php wp_reset_query(); ?>

				<?php endif; ?>

			</ul>
		</div><!-- package item -->

	<?php } else { ?>
		<p class="alert alert-info">
			<?php esc_html_e( 'Please first enable memberships under "Appearance > Theme Options > Property Submit" and create membership packages.', 'realty' ); ?>
		</p>
	<?php } ?>

	<?php return ob_get_clean();

	}
}
add_shortcode( 'membership_packages', 'tt_membership_packages' );

// Visual Composer Map
function tt_vc_map_membership_packages() {
	vc_map( array(
		'name' => esc_html__( 'Membership Packages', 'realty' ),
		'base' => 'membership_packages',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'realty' ),
				'param_name' => 'columns',
				'value' => array(
					1,
					2,
					3,
					4,
				),
				'std' => 3
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_membership_packages' );
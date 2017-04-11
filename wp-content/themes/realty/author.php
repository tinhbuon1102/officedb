<?php get_header(); ?>

<?php
	global $realty_theme_option;

	$property_contact_form_default_email = $realty_theme_option['property-contact-form-default-email'];
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$agent = $author->ID;
	$company_name = get_user_meta( $agent, 'company_name', true );
	$first_name = get_user_meta( $agent, 'first_name', true );
	$last_name = get_user_meta( $agent, 'last_name', true );
	$userdata = get_userdata( $agent );
	$email = $userdata->user_email;
	$office = get_user_meta( $agent, 'office_phone_number', true );
	$mobile = get_user_meta( $agent, 'mobile_phone_number', true );
	$fax = get_user_meta( $agent, 'fax_number', true );
	$website = get_userdata( $agent );
	$website = $website->user_url;
	$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
	$bio = get_user_meta( $agent, 'description', true );
	$bio = preg_replace('/\n(\s*\n)+/', '</p><p>', $bio);
	$profile_image = get_user_meta( $agent, 'user_image', true );
	$author_profile_url = get_author_posts_url( $agent );
	$facebook = get_user_meta( $agent, 'custom_facebook', true );
	$twitter = get_user_meta( $agent, 'custom_twitter', true );
	$google = get_user_meta( $agent, 'custom_google', true );
	$linkedin = get_user_meta( $agent, 'custom_linkedin', true );

	$author_has_published_properties = true; // Always show public profile, even when user has no published properties

	// Query 1: Has user published any properties?
	$property_args = array(
		'post_type' 				=> 'property',
		'posts_per_page' 		=> -1,
		'author'						=> $agent,
	);

	// Query 2: Is agent assigned to any properties?
	$property_args_agent_assigned = array(
		'post_type' 				=> 'property',
		'posts_per_page' 		=> -1,
		'author__not_in'		=> $agent,
		'meta_query' 				=> array(
			array(
				'key' 		=> 'estate_property_custom_agent',
				'value' 	=> $author->ID,
				'compare'	=> '='
			)
		)
	);

	// Create two queries
	$query_property = new WP_Query( $property_args );
	$query_property_assigned_agent = new WP_Query( $property_args_agent_assigned );
	$query_combined_results = new WP_Query();

	// Set posts and post_count
	$query_combined_results->posts = array_merge( $query_property->posts, $query_property_assigned_agent->posts );
	$query_combined_results->post_count = $query_property->post_count + $query_property_assigned_agent->post_count;

	// Check if user has any published or assigned properties
	if ( $query_combined_results->post_count ) {
		$author_has_published_properties = true;
	}

	$query_property = new WP_Query( $property_args );
	if ( $query_property->have_posts() ) : $query_property->the_post();
		$author_has_published_properties = true;
		wp_reset_query();
	endif;
?>

<div class="container">
	<div class="row">

		<?php if ( is_active_sidebar( 'sidebar_agent' ) ) { ?>
			<div class="col-sm-8 col-md-9">
		<?php } else { ?>
			<div class="col-sm-12">
		<?php } ?>

			<?php if ( $author_has_published_properties ) { ?>

				<?php
					include get_template_directory() . '/lib//inc/template/agent-information.php';
					include get_template_directory() . '/lib/inc/template/contact-form.php' ;
				?>

				<?php if ( $query_combined_results->have_posts() ) : ?>

					<?php
						$property_carousel_id = 'agent_carousel_' . rand();
						$show_arrows_below = true;
					?>
					<section class="property-items">
						<h4 class="section-title"><span><?php esc_html_e( 'Properties Of This Agent', 'realty' ); ?></span></h4>
						<div class="hide-initially" id="<?php echo esc_attr( $property_carousel_id ); ?>">
							<?php while ( $query_combined_results->have_posts() ) : $query_combined_results->the_post(); ?>
								<?php get_template_part( 'lib/inc/template/property', 'item' ); ?>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div>

						<?php if ( $show_arrows_below ) { ?>
							<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $property_carousel_id ); ?>"></div>
						<?php } ?>

						<?php
							$slider_params = array(
								'id'                => $property_carousel_id,
								'images_to_show'    => 2,
								'images_to_show_lg' => 2,
								'images_to_show_md' => 2,
								'images_to_show_sm' => 1,
								'autoplay'          => false,
								'autoplay_speed'    => 5000,
								'fade'              => false,
								'infinite'          => false,
								'show_arrows'       => true,
								'show_arrows_below' => $show_arrows_below,
								'show_dots'         => false,
								'show_dots_below'   => false,
							);

							tt_script_slick_slider( $slider_params );
						?>

					</section>

				<?php endif; ?>

			<?php } else { ?>
				<p>
					<?php esc_html_e( 'Publish at least one property to enable your public user profile.', 'realty' ); ?>
				</p>
				</div><!-- #agent -->
			<?php }	?>

		</div><!-- .col-sm-8 -->

		<?php if ( is_active_sidebar( 'sidebar_agent' ) ) : ?>
			<div class="col-sm-4 col-md-3">
				<ul id="sidebar">
					<?php dynamic_sidebar( 'sidebar_agent' ); ?>
				</ul>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>
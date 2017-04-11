<?php get_header();
/*
Template Name: Property Submit Listing
*/
?>

<?php tt_page_banner();	?>

<div class="container">

	<h1 class="section-title"><span><?php esc_html_e( 'My Properties', 'realty' ); ?></span></h1>

	<?php if ( is_user_logged_in() ) { ?>

		<?php if ( isset( $_GET['payment'] ) && $_GET['payment'] == 'failed' ) { // In case Strip payment failed ?>
			<p class="alert alert-danger">
				<?php esc_html_e( 'Payment failed! Please try again with correct information.', 'realty' ); ?>
			</p>
		<?php } ?>

		<?php if ( isset( $_GET['payment'] ) && $_GET['payment'] == 'paid' ) { ?>
			<p class="alert alert-success">
				<?php esc_html_e( 'Payment completed! Thank you.', 'realty' ); ?>
			</p>
		<?php } ?>

		<?php
			global $realty_theme_option;
		  $columns_theme_option = $realty_theme_option['property-listing-columns'];
			$search_results_per_page = $realty_theme_option['search-results-per-page'];
		?>

		<?php if ( current_user_can( 'manage_options' ) ) { // If admin > show all properties ?>

			<?php
				$property_args_admin = array(
					'post_type' 				=> 'property',
					'post_status'				=> array( 'publish', 'pending', 'draft' ),
					'paged' 						=> get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
				);
			?>

			<?php
				// Search Results Per Page: Check for Theme Option
				if ( $search_results_per_page ) {
					$property_args_admin['posts_per_page'] = $search_results_per_page;
				} else {
					$property_args_admin['posts_per_page'] = 10;
				}

				$query_combined_results = new WP_Query( $property_args_admin );
			?>

		<?php } else { // Query for non-admins ?>

			<?php
				global $current_user;
				$current_user = wp_get_current_user();

			  // Query: Has user any published properties?
				$property_args = array(
					'post_type' 		  => 'property',
					'posts_per_page' 	=> -1,
					'author' 			    => $current_user->ID,
			    	'post_status'		=> array( 'publish', 'pending', 'draft' )
				);

				// Query 2: Is agent assigned to any properties?
				$property_args_agent_assigned = array(
					'post_type'       => 'property',
					'posts_per_page' 	=> -1,
					'author__not_in'  => $current_user->ID,
			        'post_status'	=> array( 'publish', 'pending', 'draft' ),
					'meta_query'      => array(
						array(
							'key' 		=> 'estate_property_custom_agent',
							'value' 	=> $current_user->ID,
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
			?>

	  <?php } ?>

	  <?php if ( $query_combined_results->have_posts() ) : ?>

	  	<?php echo do_shortcode('[property_search_form]'); ?>

	  	<div class="property-items">
		  	<ul class="row list-unstyled">
					<?php while ( $query_combined_results->have_posts() ) : $query_combined_results->the_post(); ?>
			  		<li class="<?php echo esc_attr( $columns_theme_option ); ?>">
							<?php get_template_part( 'lib/inc/template/property', 'item' ); ?>
						</li>
					<?php endwhile; ?>
	  		</ul>
	  	</div>

		  <?php wp_reset_postdata(); ?>

		  <?php if ( current_user_can( 'manage_options' ) ) : ?>

		  <div id="pagination">
				<?php
					// Built Property Pagination
					$big = 999999999; // need an unlikely integer

					echo paginate_links( array(
						'base' 		  => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' 	  => '%#%',
						'total' 	  => $query_combined_results->max_num_pages,
						'end_size'  => 4,
						'mid_size'  => 2,
						'type'		  => 'list',
						'current'   => $property_args_admin['paged'],
						'prev_text' => '<i class="icon-arrow-left"></i>',
						'next_text' => '<i class="icon-arrow-right"></i>',
					) );
				?>
			</div>

			<?php endif; ?>

	  <?php else : ?>

			<p class="alert alert-info">
		  	<?php esc_html_e( 'You haven\'t submitted any properties yet.', 'realty' ); ?>
			</p>

	  <?php endif; ?>

	<?php } else { // Not Logged-In Visitor ?>

		<p class="alert alert-info">
			<?php esc_html_e( 'You have to be logged-in to view your submitted properties.', 'realty' ); ?>
		</p>

	<?php echo tt_login_form(); ?>

	<?php } ?>

</div><!-- .container -->

<?php get_footer(); ?>
<?php get_header();
/*
Template Name: User - Follows
*/

global $realty_theme_option;
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="page-user-follow" class="container">
	<h1><?php the_title()?></h1>

		<?php the_content(); ?>

		<?php if ( is_user_logged_in() ) { ?>

			<?php
				$user_id = get_current_user_id();
				 $get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()

				// Check For follow
				if ( ! $get_user_meta_follow ) {
					$number_of_follow = 0;
				} else {
					$number_of_follow = count( $get_user_meta_follow[0] );
				}
			?>

			<?php if ( $number_of_follow > 0 ) { ?>

				<?php
					$follow_args = array(
		        'post_type' => 'property',
		        'post__in' => $get_user_meta_follow[0],
		        'posts_per_page' => $number_of_follow,
				'post_status' => 'publish',
		        'orderby' => 'post__in'
		      );

		      $follow_query = new WP_Query( $follow_args );
	      ?>

				<?php if ( $follow_query->have_posts() ) : ?>

	      	<div class="property-items">
		      	<ul class="row list-unstyled">

							<?php
					      global $realty_theme_option;
								$columns_theme_option = $realty_theme_option['property-listing-columns'];
							?>

							<?php while ( $follow_query->have_posts() ) : $follow_query->the_post(); ?>

								<?php if ( empty( $columns_theme_option ) ) { ?>
									<li class="col-md-4 col-sm-6">
								<?php	} else { ?>
									<?php if ( $columns_theme_option == "col-md-6" ) { ?>
										<li class="col-md-4 col-sm-6">
									<?php }	else if ( $columns_theme_option == "col-lg-4 col-md-6" ) { ?>
										<li class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
									<?php }	else if ( $columns_theme_option == "col-lg-3 col-md-6" ) { ?>
										<li class="col-lg-3 col-md-6">
									<?php } ?>
								<?php }	?>

					      <?php get_template_part( 'lib/inc/template/property', 'item' ); ?>

					      </li>

				      <?php endwhile; ?>
				      <?php wp_reset_query(); ?>

						</ul>
					</div>

			<?php else :?>
			<p class=" alert alert-info">
				<?php esc_html_e( 'You haven\'t added any follow.', 'realty' ); ?>
			</p>
	      <?php endif; ?>

			<?php } else { // No follow Saved ?>
				<p class=" alert alert-info">
					<?php esc_html_e( 'You haven\'t added any follow.', 'realty' ); ?>
				</p>
			<?php } ?>

			<p id="msg-no-follow" class="hide alert alert-info">
				<?php esc_html_e( 'You haven\'t added any follow.', 'realty' ); ?>
			</p>

		<?php }	else { ?>
				<p class=" alert alert-info"><?php esc_html_e( 'To view your follow you have to login or create an account.', 'realty' ); ?></p>
		<?php }	?>

	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
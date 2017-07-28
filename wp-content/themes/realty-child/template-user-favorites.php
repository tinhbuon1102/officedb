<?php get_header();
/*
Template Name: User - Favorites
*/

global $realty_theme_option;
$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="page-user-favorites" class="container">
		<h1><?php the_title()?></h1>
		<?php the_content(); ?>

		<?php if ( is_user_logged_in() ) { ?>

			<?php
				$user_id = get_current_user_id();
				 $get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()

				// Check For Favorites
				if ( ! $get_user_meta_favorites ) {
					$number_of_favorites = 0;
				} else {
					$number_of_favorites = count( $get_user_meta_favorites[0] );
				}
			?>

			<?php if ( $number_of_favorites > 0 ) { ?>

				<?php
					$favorites_args = array(
		        'post_type' => 'property',
		        'post__in' => $get_user_meta_favorites[0],
		        'posts_per_page' => $number_of_favorites,
				'post_status' => 'publish',
		        'orderby' => 'post__in'
		      );

		      $favorites_query = new WP_Query( $favorites_args );
	      ?>

				<?php if ( $favorites_query->have_posts() ) : ?>

	      	<div class="property-items">
		      	<ul class="row list-unstyled">

							<?php
					      global $realty_theme_option;
								$columns_theme_option = $realty_theme_option['property-listing-columns'];
							?>

							<?php while ( $favorites_query->have_posts() ) : $favorites_query->the_post(); ?>

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
				<?php esc_html_e( 'You haven\'t added any favorites.', 'realty' ); ?>
			</p>
	      <?php endif; ?>

			<?php } else { // No Favorites Saved ?>
				<p class=" alert alert-info">
					<?php esc_html_e( 'You haven\'t added any favorites.', 'realty' ); ?>
				</p>
			<?php } ?>

			<p id="msg-no-favorites" class="hide alert alert-info">
				<?php esc_html_e( 'You haven\'t added any favorites.', 'realty' ); ?>
			</p>

		<?php }	else { ?>

			<?php if ( $add_favorites_temporary ) { // Temporary Favorites ?>
				<div id="favorites-temporary"></div>
			<?php } else { ?>
				<p class=" alert alert-info"><?php esc_html_e( 'To view your favorites you have to login or create an account.', 'realty' ); ?></p>
			<?php } ?>
		<?php }	?>

	</div>

	<script>
	jQuery('.add-to-favorites').click(function() {
		jQuery(this).closest('li').fadeOut(400, function() {
			jQuery(this).remove();
			var numberOfFavorites = jQuery('.property-item').length;
			if ( numberOfFavorites == 0 ) {
				jQuery('#msg-no-favorites').toggleClass('hide');
			}
		});
	});
	</script>

<?php endwhile; ?>

<?php get_footer(); ?>
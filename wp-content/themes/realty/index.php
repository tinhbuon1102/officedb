<?php get_header(); ?>

<?php tt_page_banner(); ?>

<div class="container">
	<div class="row">

		<?php if ( is_active_sidebar( 'sidebar_blog' ) ) { ?>
			<div class="col-sm-9">
		<?php } else { ?>
			<div class="col-sm-12">
		<?php }	?>

			<?php if ( have_posts() ) : ?>

				<?php if ( is_archive() ) { ?>
					<h2>
						<?php if ( is_author() ) :
							printf( esc_html__( 'All posts by: %s', 'realty' ), get_the_author() );
							elseif ( is_tag() ) : printf( esc_html__( 'Tag: %s', 'realty' ), single_tag_title( '', false ) );
							elseif (is_category() ) : printf( esc_html__( 'Category: %s', 'realty' ), single_cat_title( '', false ) );
							elseif ( is_date() ) :
								if ( is_day() ) :
									printf( esc_html__( 'Daily Archives: %s', 'realty' ), get_the_date() );

								elseif ( is_month() ) :
									printf( esc_html__( 'Monthly Archives: %s', 'realty' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'realty' ) ) );

								elseif ( is_year() ) :
									printf( esc_html__( 'Yearly Archives: %s', 'realty' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'realty' ) ) );
								else :
									esc_html_e( 'Archives', 'realty' );
								endif;
							endif;
						?>
					</h2>
				<?php } ?>

				<?php if ( is_search() ) { ?>
					<h2 class="blog-page-title"><?php printf( esc_html__( 'Search Results for: %s', 'realty' ), get_search_query() ); ?></h2>
				<?php } ?>

				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content' );
					endwhile;
				?>

				<?php wp_reset_postdata(); ?>

			<?php else : ?>
				<p class="lead text-muted"><?php esc_html_e( 'No posts found.', 'realty' ); ?></p>
			<?php endif; ?>

			<?php tt_blog_pagination(); ?>
		</div>

		<?php if ( is_active_sidebar( 'sidebar_blog' ) ) { ?>
			<div class="col-sm-3">
				<?php get_sidebar(); ?>
			</div>
		<?php } ?>

	</div>
</div>

<?php get_footer(); ?>
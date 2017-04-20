<?php get_header(); ?>

<div class="taxonomy-results container">
	<div class="search-results-header clearfix">

		<?php if ( have_posts() ) : ?>

			<h2 class="page-title">
				<?php
					echo esc_html__( 'Category:', 'realty' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
					echo ' (' . $wp_query->found_posts . ')';
				?>
			</h2>

			<div class="taxonomy-description">
				<?php the_archive_description( '<div>', '</div>' ); ?>
			</div>

			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', 'archive' );
				endwhile;
			?>

			<div id="pagination">
				<?php
					// Built Property Pagination
					global $wp_query;
					$big = 999999999; // need an unlikely integer
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					echo paginate_links( array(
						'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' 			=> '?page=%#%',
						'total' 			=> $wp_query->max_num_pages,
						//'show_all'		=> true,
						'end_size'    => 4,
						'mid_size'    => 2,
						'type'				=> 'list',
						'current'     => $paged,
						'prev_text' 	=> '<i class="icon-arrow-left"></i>',
						'next_text' 	=> '<i class="icon-arrow-right"></i>',
					) );
				?>
			</div>

			<?php
			else :
				get_template_part( 'content', 'none' );
			endif;
		?>

	</div>
</div>

<?php get_footer(); ?>

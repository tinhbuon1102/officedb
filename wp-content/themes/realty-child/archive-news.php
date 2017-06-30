<?php get_header(); ?>
<div class="container news-archive">
<div class="vc_row vc_row-fluid style_a boxed title-wrap-news">
<div class="wpb_column vc_column_container vc_col-sm-12">
<div class="vc_column-inner "><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element ">
		<div class="wpb_wrapper">
			<h1 class="big-title">News</h1>
<p class="ja-title">新着情報</p>
		</div>
	</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-12">
	<div class="search-results-header clearfix">

		<?php if ( have_posts() ) : ?>

			

			<div class="taxonomy-description">
				<?php the_archive_description( '<div>', '</div>' ); ?>
			</div>

			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content-news', 'archive' );
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
<!--<div class="col-sm-4">
<?php //get_sidebar(); ?>
</div>-->
</div>

</div>
<?php get_footer(); ?>

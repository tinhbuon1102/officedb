<?php get_header(); ?>

<div class="container">
	<div class="row">

		<?php global $post; ?>

			<?php if ( is_active_sidebar( 'sidebar_blog' ) ) { ?>
				<div class="col-sm-9">
			<?php } else { ?>
				<div class="col-sm-12">
			<?php } ?>

		<?php
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content' );
			endwhile;
		?>

		<?php
			// Previous / Next Post Navigation
			$previousPost = get_adjacent_post();
			$nextPost = get_adjacent_post( false, "", false );
		?>

		<?php if ( $previousPost || $nextPost ) { ?>
			<div id="blog-prev-next-post">
				<div class="row">
					<?php	if ( $previousPost ) { ?>
						<?php $previous_attachment_id =  get_post_thumbnail_id( $previousPost->ID ) ; ?>
				    <div class="prev-post <?php if ( $nextPost ) { echo "col-sm-6"; } else { echo "col-sm-12"; } ?>">
					    <a href="<?php echo get_permalink( $previousPost->ID )?>">
					    	<div class="text-muted"><?php esc_html_e( 'Previous read:', 'realty' ); ?></div>
					    	<h5 class="title"><?php echo $previousPost->post_title; ?></h5>
					    </a>
				    </div>
						<?php } ?>

						<?php if ( $nextPost ) { ?>
							<?php $next_attachment_id =  get_post_thumbnail_id( $nextPost->ID ); ?>
					    <div class="next-post <?php if ( $previousPost ) { echo "col-sm-6 text-right"; } else { echo "col-sm-12"; } ?>">
						    <a href="<?php echo get_permalink( $nextPost->ID )?>">
						    	<div class="text-muted"><?php esc_html_e( 'Next read:', 'realty' ); ?></div>
						    	<h5 class="title"><?php echo $nextPost->post_title; ?></h5>
						    </a>
					    </div>
						<?php } ?>
				</div>
			</div><!-- #blog-prev-next-post -->
		<?php } ?>

		<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>
		</div>

		<?php if ( is_active_sidebar( 'sidebar_blog' ) ) { ?>
			<div class="col-sm-3">
				<?php get_sidebar(); ?>
			</div>
		<?php } ?>

	</div>
</div>

<?php get_footer(); ?>
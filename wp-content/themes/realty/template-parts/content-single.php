<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-header">
		<div class="entry-header-content">
			<?php if ( ! is_singular() ) { ?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><h2 class="entry-title"><?php the_title(); ?></h2></a>
			<?php } else { ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php } ?>
			<p class="post-date"><?php the_date(); ?></p>
		</div>
		<?php if ( has_post_thumbnail() ) { ?>
		 <?php the_post_thumbnail( get_the_id(), 'vehicle-single', array( 'class' => 'featured-image' ) ); ?>
		 <div class="featured-image-overlay"></div>
		<?php }	?>
	</div>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php
		$tags_list = get_the_tag_list( '', ', ', '' );
		$categories_list = get_the_category_list( ', ' );
		?>

		<?php if ( $tags_list || $categories_list ) {	?>

			<div class="post-meta">

			<?php if ( $tags_list ) { ?>
				<div class="tags-list">
					<?php echo esc_html__( 'Tags:', 'realty' ) . ' ' . $tags_list; ?>
				</div>
			<?php } ?>

			<?php if ( $categories_list ) { ?>
				<div class="categories-list">
					<?php echo esc_html__( 'Categories:', 'realty' ) . ' ' . $categories_list; ?>
				</div>
			<?php } ?>

			</div>

		<?php }?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'realty' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<div class="entry-footer">
		<?php echo realty_post_social_sharing(); ?>
	</div>

</article>

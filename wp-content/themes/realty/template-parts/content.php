<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="entry-header">

	<div class="header-content">

		<?php if ( has_post_thumbnail() ) { ?>
			<div class="header-media">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_post_thumbnail( 'thumbnail-16-9' ); ?></a>
			</div>
		<?php } ?>

		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else : ?>
			<h2 class="entry-title"><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<div class="header-meta">
			<div class="post-date"><?php echo get_the_date('M d, Y'); ?></div>
		</div>

	</div>

</div><!-- .entry-header -->

<?php echo tt_post_content_navigation(); ?>

</article>
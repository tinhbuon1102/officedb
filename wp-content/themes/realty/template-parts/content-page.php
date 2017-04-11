<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php tt_page_banner(); ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'realty' ),
				'after'  => '</div>',
			) );
		?>

	</div>

</article>
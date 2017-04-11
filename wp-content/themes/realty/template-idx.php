<?php get_header();
/*
Template Name: IDX
*/
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div class="container">
	<div class="row">

		<?php if ( is_active_sidebar( 'sidebar_idx' ) ) { ?>
			<div class="col-sm-8 col-md-9">
		<?php } else { ?>
			<div class="col-sm-12">
		<?php }	?>

			<div id="main-content" class="content-box">
				<h3 class="section-title"><span><?php the_title(); ?></span></h3>
				<?php the_content(); ?>
			</div>

		</div>

		<?php if ( is_active_sidebar( 'sidebar_idx' ) ) { ?>
			<div class="col-sm-4 col-md-3">
				<ul id="sidebar">
					<?php dynamic_sidebar( 'sidebar_idx' ); ?>
				</ul>
			</div>
		<?php } ?>


	</div>
	</div>

<?php
endwhile;

get_footer();
?>
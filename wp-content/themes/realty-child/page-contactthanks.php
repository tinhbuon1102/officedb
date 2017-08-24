<?php
/*
Template Name: Thank you
*/
?>
	<?php get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
			/**
			 * Add .container wrapper, if:
			 * (1) VC plugin not active OR
			 * (2) active, but no actual VC elements (vc_row etc.) being used.
			 * Which is the case when updating to v3.0 from a previous Realty version
			 *
			 */
			$raw_content = get_the_content();

			if ( strpos( $raw_content, 'vc_row' ) ) {
				$has_vc_elements = true;
			} else {
				$has_vc_elements = false;
			}
		?>

		<?php if ( ! class_exists( 'Vc_Manager' ) || ! $has_vc_elements ) { ?>
			<div class="container thank-content">
		<?php } ?>

			<?php get_template_part( 'template-parts/content', 'thankyou' ); ?>


		<?php if ( ! class_exists( 'Vc_Manager' ) || ! $has_vc_elements ) { ?>
			</div>
		<?php } ?>

	<?php endwhile; ?>

<?php get_footer(); ?>
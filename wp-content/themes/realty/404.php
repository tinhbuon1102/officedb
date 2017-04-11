<?php get_header(); ?>

<div id="main-content" class="container">
	<?php global $realty_theme_option; ?>

	<?php
		if ( isset( $realty_theme_option['404-page'] ) && ! empty( $realty_theme_option['404-page'] ) ) {
			// Check For Selected 404 Error Page in Theme Options Panel
			echo do_shortcode( get_post_field( 'post_content', $realty_theme_option['404-page'] ) );
		}	else {
			// No Custom 404 page Selected, Show The Following Default 404 Content
	?>
		<div class="text-align-center">
			<h1><?php esc_html_e( 'Sorry, but the requested page does not exist.', 'realty' ); ?></h1>
			<p class="lead text-muted"><?php esc_html_e( 'Featured properties:', 'realty' ); ?></p>
		</div>

		<?php echo do_shortcode( '[property_carousel show_featured_only="true" images_to_show="3" images_to_show_lg="3" images_to_show_md="2" images_to_show_sm="1" show_arrows="" show_dots="true"]' ); ?>

		<div class="text-align-center" style="padding-top: 30px;">
			<a href="<?php echo home_url( '/' ); ?>" class="btn btn-ghost-primary btn-sm">&laquo; <?php esc_html_e( 'Back to home page', 'realty' ); ?></a>
		</div>
	<?php } ?>
</div>

<?php get_footer(); ?>
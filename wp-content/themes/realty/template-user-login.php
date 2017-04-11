<?php
/*
Template Name: User - Login
*/

global $current_user;
$current_user = wp_get_current_user();

if ( $current_user->ID != '' ) {
	wp_redirect( home_url() . '?sign-user=successful' );
	exit();
}
?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="main-content" class="container">
  	<?php
	    if ( get_the_content() ) {
      	the_content();
    	}
    ?>

    <?php echo tt_login_form(); ?>
	</div>

<?php endwhile; ?>

<?php get_footer();?>
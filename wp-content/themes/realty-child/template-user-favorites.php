<?php get_header();
/*
Template Name: User - Favorites
*/

global $realty_theme_option;
$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="page-user-favorites" class="container page-user-favorites">
		<h1><?php the_title()?></h1>
		<?php the_content(); ?>

		<?php if ( is_user_logged_in() ) { 
			echo buildListFavoriteProperty(true, false);
			?>
			<div class="button_groups">
				<a class="btn btn-success" id="contact-inquiry" href="<?php echo pll_current_language() == LANGUAGE_JA ? site_url('inquiry') : site_url('inquiry-en')?>"><span class="sp-none"><?php echo trans_text('Contact all checked offices')?></span><span class="sp-show"><?php echo trans_text('Bulk contact')?></span></a>
				<a class="btn btn-removeblk add-to-favorites remove_property" id="bulk-remove" href="#"><span class="sp-none"><?php echo trans_text('Remove all checked offices')?></span><span class="sp-show"><?php echo trans_text('Bulk remove')?></span></a>
			</div>
		<?php }?>
		
<?php endwhile; ?>

<?php get_footer(); ?>
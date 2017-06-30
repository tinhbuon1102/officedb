<?php 
$news_post = get_post();

$categories = get_the_category($news_post->ID); // post ID
	foreach ( $categories as $cat )
	{
		$cat_name = $cat->cat_name;
	}
$news_url = get_permalink($news_post);

$floor_vacancy = get_post_meta($news_post->ID, 'floor_vacancy', true);
if ($floor_vacancy)
{
	$news_post->post_title .= ' ' .  trans_text( 'has new vacancy');
}
else {
	$news_post->post_title .= ' ' .  trans_text( 'is added newly');
}
?>
<article id="post-<?php $news_post->ID; ?>" <?php post_class('', $news_post->ID); ?>>
<?php if ( has_post_thumbnail($news_post->ID) ) { ?>
			<div class="header-media">
				<a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo get_the_post_thumbnail($news_post->ID, 'property-thumb-long' ); ?></a>
			</div>
		<?php } ?>
<div class="entry-header">

	<div class="header-content">

		

		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo $news_post->post_title; ?></a></h1>
		<?php else : ?>
			<h2 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo $news_post->post_title; ?></a></h2>
		<?php endif; ?>

		<div class="header-meta clearfix">
			<div class="meta"><?php echo $cat_name?>&nbsp;|&nbsp;<span class="post-date"><?php echo renderJapaneseDate($news_post->post_modified)?><?php //echo get_post_time('F d, Y', $news_post->ID); ?></span></div>
		</div>

	</div>
	<div class="chc-news"><a href="<?php echo esc_url( $news_url ); ?>" class="check_now">Check now</a></div>

</div><!-- .entry-header -->

<?php //echo tt_post_content_navigation(); ?>

</article>
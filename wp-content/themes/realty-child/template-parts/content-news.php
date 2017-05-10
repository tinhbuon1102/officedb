<?php 
$building_id = get_post_meta(get_the_ID(), 'jpdb_building_id', true);
if ($building_id)
{
	// Get property by news
	$new_args = array(
		'post_type' => 'property',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => FLOOR_BUILDING_TYPE,
				'value' => $building_id,
			)
		)
	);
	$the_news_query = new WP_Query( $new_args );
	if ( $the_news_query->have_posts() )
	{
		while ( $the_news_query->have_posts() ) {
			$the_news_query->the_post();
			global $post;
			$new_property = $post;
		}

	}
}
$news_url = isset($new_property) ? get_permalink($new_property->ID) : get_permalink();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="entry-header">

	<div class="header-content">

		

		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php else : ?>
			<h2 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>

		<div class="header-meta">
			<div class="post-date"><?php echo get_post_time('F d, Y'); ?>
</div>
		</div>

	</div>

</div><!-- .entry-header -->
<?php if ( has_post_thumbnail() ) { ?>
			<div class="header-media">
				<a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php the_post_thumbnail( 'thumbnail-16-9' ); ?></a>
			</div>
		<?php } ?>
<?php echo tt_post_content_navigation(); ?>


</article>
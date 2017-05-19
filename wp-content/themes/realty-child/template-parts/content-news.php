<?php 
$news_post = get_post();
$building_id = get_post_meta(get_the_ID(), FLOOR_BUILDING_TYPE, true);

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

$categories = get_the_category($news_post->ID); // post ID
	foreach ( $categories as $cat )
	{
		$cat_name = $cat->cat_name;
	}
$news_url = isset($new_property) ? get_permalink($new_property->ID) : get_permalink($news_post);
?>
<article id="post-<?php $news_post->ID; ?>" <?php post_class('', $news_post->ID); ?>>

<div class="entry-header">

	<div class="header-content">

		

		<?php if ( is_single() ) : ?>
			<h1 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo $news_post->post_title; ?></a></h1>
		<?php else : ?>
			<h2 class="entry-title"><a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo $news_post->post_title; ?><?php $locale = get_locale(); /* get current locale */ ?><?php if ('en_US' == $locale  ) : /* English */?>&nbsp;<?php else:  /* Japanese */ ?><?php endif; ?><?php esc_html_e( 'is added newly', 'realty' ); ?></a></h2>
		<?php endif; ?>

		<div class="header-meta clearfix">
			<div class="post-date"><?php echo renderJapaneseDate($news_post->post_date)?><?php //echo get_post_time('F d, Y', $news_post->ID); ?></div>
			<div class="meta"><?php echo $cat_name?>&nbsp;|&nbsp;<span class="common-cat">NEWS</span></div>
		</div>

	</div>

</div><!-- .entry-header -->
<?php if ( has_post_thumbnail($news_post->ID) ) { ?>
			<div class="header-media">
				<a href="<?php echo esc_url( $news_url ); ?>" rel="bookmark"><?php echo get_the_post_thumbnail($news_post->ID, 'property-thumb-long' ); ?></a>
			</div>
		<?php } ?>
<?php echo tt_post_content_navigation(); ?>

<a href="<?php echo esc_url( $news_url ); ?>" class="check_now">Check now</a>
</article>
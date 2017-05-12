<ul class="latest-news-list row">
<?php
$recent_posts = wp_get_recent_posts(array(
	'post_type' => 'news',
	'posts_per_page' => 3,
));

foreach ( $recent_posts as $recent )
{
	$building_id = get_post_meta($recent['ID'], 'jpdb_building_id', true);
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
	
	
	$categories = get_the_category($recent['ID']); // post ID
	foreach ( $categories as $cat )
	{
		$cat_name = $cat->cat_name;
	}
	$news_url = isset($new_property) ? get_permalink($new_property->ID) : get_permalink($recent['ID']);
	?>
<li class="col-sm-4">
		<div class="inner">
			<div class="con-inner">
				<div class="post-date"><?php echo renderJapaneseDate($recent['post_date'])?></div>
				<div class="title"><a href="<?php echo $news_url?>" title="<?php echo $recent["post_title"]?>"><?php echo $recent["post_title"]?></a></div>
				<div class="meta">
					<?php echo $cat_name?>&nbsp;|&nbsp;
					<span class="common-cat">NEWS</span>
				</div>
				<div class="news_thumbnail">
					<a href="<?php echo $news_url?>" title="<?php echo $recent["post_title"]?>">
					<?php 
					//echo get_the_post_thumbnail($recent['ID'], 'thumbnail');
					?>
					</a>
				</div>
			</div>
			<a href="<?php echo $news_url?>" title="Look <?php echo esc_attr($recent[" post_title"])?>" class="check_now">Check now</a>
		</div>
	</li>

<?php
}
?>
</ul>
<ul class="latest-news-list row">
<?php
$recent_posts = wp_get_recent_posts(array(
	'post_type' => 'news'
));

foreach ( $recent_posts as $recent )
{
	$categories = get_the_category($recent['ID']); // post ID
	?>
<li class="col-sm-4">
	<div class="inner">
	<div class="con-inner">
		<div class="post-date"><?php echo renderJapaneseDate($recent['post_date'])?></div>
		<div class="title"><?php echo $recent["post_title"]?></div>
		<div class="meta">
			<?php foreach($categories as $cat)
				{ 
				  	echo $cat->cat_name; 
				}
			?>&nbsp;|&nbsp;<span class="common-cat">NEWS</span>
		</div>
		</div>
		<a href="<?php echo get_permalink($recent[" ID"])?>" title="Look <?php echo esc_attr($recent[" post_title"])?>" class="check_now">Check now</a>
	</div>
</li>

<?php
}
?>
</ul>
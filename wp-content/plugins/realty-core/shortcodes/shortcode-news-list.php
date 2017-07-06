<?php
/**
 * Shortcode: News Home listing
 *
 */
if ( ! function_exists( 'tt_realty_news_listing' ) ) {
	function tt_realty_news_listing( $atts ) {

		global $wpdb;
		extract( shortcode_atts( array(
			'sort_by'                  => 'date',
			'per_page'                 => 4,
		), $atts ) );

		$args = array(
			'post_type' => 'news',
			'posts_per_page' => $per_page,
			'orderby' => array( 'post_modified' => 'DESC' )
		);
		$query_search_results = new WP_Query( $args );
		$recent_posts = $query_search_results->get_posts();
		
		if (!count($recent_posts)) return '';
		
		ob_start();
		
		echo '<ul class="homenews-list">';
		foreach ( $recent_posts as $recent )
		{
			$recent = (array)$recent;
			$floor_vacancy = get_post_meta($recent['ID'], 'floor_vacancy', true);
			if ($floor_vacancy)
			{
				$recent['post_title'] .= ' ' .  trans_text( 'has new vacancy');
			}
			else {
				$recent['post_title'] .= ' ' .  trans_text( 'is added newly');
			}
		
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
						break;
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
			<li>
				<span class="news-thumbnail">
					<a href="<?php echo $news_url?>" title="<?php echo $recent["post_title"]?>" class="title"><?php echo get_the_post_thumbnail($recent['ID'], 'thumbnail');?></a>
				</span>
				
				<div class="post_info">
					<a href="<?php echo $news_url?>" title="<?php echo $recent["post_title"]?>" class="title"><?php echo $recent["post_title"]?></a>
					<div class="post-meta"><?php echo renderJapaneseDate($recent['post_modified'])?> | <span class="added-info-ja"><?php echo $cat_name; ?></span></div>
				</div>
			</li>
			
			<?php
			}
			?>
		</ul>
		<?php
		wp_reset_query();
		return ob_get_clean();

	}
}

add_shortcode( 'news_listing', 'tt_realty_news_listing' );
// Visual Composer Map
function tt_vc_news_listing() {
	vc_map( array(
		'name' => esc_html__( 'Home News Listing', 'realty' ),
		'base' => 'news_listing',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Listings Per Page', 'realty' ),
				'param_name' => 'per_page',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10
				),
				'description' => '',
				'std' => 5
			),		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_news_listing' );

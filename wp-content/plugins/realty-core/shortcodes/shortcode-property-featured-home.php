<?php
/**
 * Shortcode: Property Listings
 *
 */
if ( ! function_exists( 'tt_realty_property_featured_home' ) ) {
	function tt_realty_property_featured_home( $atts ) {

		extract( shortcode_atts( array(
			'sort_by'                  => 'date',
			'per_page'                 => 4,
			'view'                     => 'grid-view',
		), $atts ) );

		$args = array(
			'post_type' => 'property',
			'posts_per_page' => $per_page,
			'orderby' => $sort_by,
			'order' => 'DESC',
			
			'orderby'    => array(
				'meta_value_num' => 'DESC',
				'date' => 'DESC',
			),
			
			'meta_query' => array(
				array(
					'key' => 'estate_property_featured',
					'orderby' => 'meta_value_num',
					'order' => DESC,
				),
			)
		);
		
		$args = buildSearchArgs($args);
		$the_query = new WP_Query( $args );
		ob_start();
		if ( $the_query->have_posts() ) : ?>
	  	<div id="property-items-featured" class="property-items-featured">
	  	<div class="container">
	  	<div class="row">
	  		<?php 
	  		$count_featured = 0;
	  		while ( $the_query->have_posts() ) : $the_query->the_post();
		  		global $post;
		  		$count_featured++;
		  		$building = get_post_meta($post->ID, BUILDING_TYPE_CONTENT, true);
		  		$post->post_title = $building['name_ja'] ? $building['name_en'] : $post->post_title;
	  		
	  		?>
	  		<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="container vc_row wpb_row vc_inner vc_row-fluid feature_row sm-flex ">
					<?php if ($count_featured % 2 != 0) {
						getHomeFeaturedCol(1);
						getHomeFeaturedCol(2);
					}else {
						getHomeFeaturedCol(1);
						getHomeFeaturedCol(2);
					}
					?>
					
					
				</div>
				</div>
	<?php 
	  		endwhile;
	  		?>
	  		</div><!--/.row-->
	  		</div><!--/.container-->
		</div><!-- .property-items -->
		<?php endif;?>
		<?php
		wp_reset_query();
		return ob_get_clean();

	}
}

function getHomeFeaturedCol($col)
{
	global $post;
	$google_maps = get_post_meta( get_the_ID(), 'estate_property_google_maps', true );
	$building = getBuilding(get_the_ID());
	
	switch($col)
	{
		case 1:
			?>
			<div class="order1 wpb_column vc_column_container vc_col-sm-4">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="wpb_single_image wpb_content_element vc_align_left">
							<figure class="wpb_wrapper vc_figure imgfit">
								<a href="<?php the_permalink()?>" target="_self" class="vc_single_image-wrapper vc_box_border_grey">
									<?php the_post_thumbnail()?>
								</a>
							</figure>
						</div>
					</div>
				</div>
			</div>
			<?php
			break;
			
		case 2:
			$post_title = get_post_meta(get_the_ID(), 'post_title_building', true);
			
			$aProperty_title = explode(' ', $post_title);
			?>
			<div class="order2 wpb_column vc_column_container vc_col-sm-8">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper table-wrap">
					<div class="bldg-name table-cell">
						<h3 class="vc_custom_heading feature-name">
							<?php 
							foreach ($aProperty_title as $property_title)
							{
								echo '<span class="letter-home-word">'.$property_title.'</span>';
							}
							?>
						</h3>
						</div>
						<div class="feature-desc txt-col table-cell">
									
										<?php echo realty_excerpt(PROPERTY_HOME_FEATURE_CONTENT_LIMIT);?>
										<ul class="locate-info">
											<li class="addr"><?php echo $google_maps['address']?></li>
											<li class="station">
												<?php 
												if ($building['stations']) {
													foreach ($building['stations'] as $station)
													{
														$station_name = isEnglish() ? $station['name_en'] : $station['name'];
														echo sprintf(trans_text('%s by foot : %sminutes'),trans_text($station_name), $station['time']);
														
														break;
													}
												}
												?>
											</li>
										</ul>
									
									
								</div>
								<div class="more-col table-cell">
									<p class="read_more">
										<a href="<?php the_permalink()?>">&gt; <?php echo trans_text('More Info')?></a>
									</p>
									</div>
					</div>
				</div>
			</div>
			<?php
			break;
			
		case 3:
			?>
			<div class="order3 wpb_column vc_column_container vc_col-sm-4">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="wpb_raw_code wpb_content_element wpb_raw_html">
							<div class="wpb_wrapper">
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			break;
	}
}

add_shortcode( 'property_featured_home', 'tt_realty_property_featured_home' );
// Visual Composer Map
function tt_vc_featured_home() {
	vc_map( array(
		'name' => esc_html__( 'Property Featured Home', 'realty' ),
		'base' => 'property_featured_home',
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
				'std' => 3
			),		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_featured_home' );

<?php
/**
 * Shortcode: Property Listings
 *
 */
if ( ! function_exists( 'tt_realty_property_featured_home' ) ) {
	function tt_realty_property_featured_home( $atts ) {

		extract( shortcode_atts( array(
			'sort_by'                  => 'date',
			'per_page'                 => 3,
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
	  		<style>
	  			#property-items-featured h3 {
	  				line-height: 1; text-align: left; font-family: PT Serif; font-weight: 400; font-style: italic
	  			}
	  		</style>
	  		<?php 
	  		$count_featured = 0;
	  		while ( $the_query->have_posts() ) : $the_query->the_post();
		  		global $post;
		  		$count_featured++;
		  		$building = get_post_meta($post->ID, BUILDING_TYPE_CONTENT, true);
		  		$post->post_title = $building['name_en'] ? $building['name_en'] : $post->post_title;
	  		
	  		?>
				<div class="container vc_row wpb_row vc_inner vc_row-fluid feature_row sm-flex ">
					<?php if ($count_featured % 2 != 0) {
						getHomeFeaturedCol(2);
						getHomeFeaturedCol(3);
						getHomeFeaturedCol(1);
					}else {
						getHomeFeaturedCol(3);
						getHomeFeaturedCol(1);
						getHomeFeaturedCol(2);
					}
					?>
					
					
				</div>
	<?php 
	  		endwhile;
	  		?>
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
	switch($col)
	{
		case 1:
			?>
			<div class="order1 wpb_column vc_column_container vc_col-sm-4">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="wpb_single_image wpb_content_element vc_align_left">
							<figure class="wpb_wrapper vc_figure">
								<a href="<?php the_permalink()?>" target="_self" class="vc_single_image-wrapper   vc_box_border_grey">
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
			$aProperty_title = explode(' ', get_the_title());
			?>
			<div class="order2 wpb_column vc_column_container vc_col-sm-4">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<h3 class="vc_custom_heading fontbig">
							<?php 
							foreach ($aProperty_title as $property_title)
							{
								echo '<span class="letters-'. strlen($property_title) .'">'.$property_title.' &nbsp;</span>';
							}
							?>
						</h3>
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
								<div class="s1 txt-col">
									<p class="font_8">
										<?php echo realty_excerpt(PROPERTY_HOME_FEATURE_CONTENT_LIMIT);?>
									</p>
									<p class="read_more">
										<a href="<?php the_permalink()?>">&gt; <?php echo trans_text('More Info')?></a>
									</p>
								</div>
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

<?php
/**
 * Shortcode: Property Carousel
 *
 */
if ( ! function_exists( 'tt_realty_property_carousel' ) ) {
	function tt_realty_property_carousel( $atts ) {

		extract( shortcode_atts( array(
			'id'                => 'property_carousel_' . rand(),
			'sort_by'           => 'date-new',
			'show_featured_only'=> false,
			'location'          => '',
			'status'            => '',
			'type'              => '',
			'features'          => '',
			'max_price'         => '',
			'min_rooms'         => '',
			'available_from'    => '',
			'autoplay'          => false,
			'autoplay_speed'    => 5000,
			'fade'              => false,
			'images_to_show'    => 1,
			'images_to_show_lg' => 1,
			'images_to_show_md' => 1,
			'images_to_show_sm' => 1,
			'infinite'          => true,
			'show_arrows'       => true,
			'show_arrows_below' => true,
			'show_dots'         => false,
			'show_dots_below'   => true,
		), $atts ) );

		global $realty_theme_option;
		ob_start();

		$classes[] = 'property-carousel';

		if ( $show_arrows && $show_arrows_below ) {
			$classes[] = 'arrows-below';
		}

		if ( $show_dots && $show_dots_below ) {
			$classes[] = 'dots-below';
		}

		$classes = join(' ', $classes );

		// Custom Query
		$custom_query_args['post_type'] = 'property';

		/**
		 * Tax Queries
		 *
		 */
		$tax_query = array();

		if ( $location ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-location',
				'field' 		=> 'slug',
				'terms'			=> explode( ',', $location ),
				'operator'  => 'IN'
			);
		}

		if ( $status ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-status',
				'field' 		=> 'slug',
				'terms'			=> explode( ',', $status ),
				'operator'  => 'IN'
			);
		}

		if ( $type ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-type',
				'field' 		=> 'slug',
				'terms'			=> explode( ',', $type ),
				'operator'  => 'IN'
			);
		}

		if ( $features ) {
			$tax_query[]	= array(
				'taxonomy' 	=> 'property-features',
				'field' 		=> 'slug',
				'terms'			=> explode( ',', $features ),
				'operator'	=> 'AND'
			);
		}

		// Count Taxonomy Queries + set their relation for search query
		$tax_count = count( $tax_query );

		if ( $tax_count > 0 ) {
			$custom_query_args['tax_query'] = $tax_query;
		}

		if ( $tax_count > 1 ) {
			$tax_query['relation'] = 'AND';
		}

		/**
		 * Meta Queries
		 *
		 */
		$meta_query = array();

		if( $max_price ) {
			$meta_query[] = array(
				'key' 			=> 'estate_property_price',
				'value' 		=> $max_price,
				'compare'		=> '<=',
				'type' 			=> 'NUMERIC',
			);
		}

		if( $min_rooms ) {
			$meta_query[] = array(
				'key' 			=> 'estate_property_rooms',
				'value' 		=> $min_rooms,
				'compare'		=> '>=',
				'type' 			=> 'NUMERIC',
			);
		}

		if( $available_from ) {
			$meta_query[] = array(
				'key' 			=> 'estate_property_available_from',
				'value' 		=> $available_from,
				'compare'		=> '<=',
				'type' 			=> 'NUMERIC',
			);
		}

		// Count Meta Queries + set their relation for search query
		$meta_count = count( $meta_query );

		if ( $meta_count > 0 ) {
			$custom_query_args['meta_query'] = $meta_query;
		}

		if ( $meta_count > 1 ) {
		  $meta_query['relation'] = 'AND';
		}

		/**
		 * Order by
		 *
		 */
		if ( ! empty( $_GET[ 'order-by' ] ) ) {
			$orderby = $_GET[ 'order-by' ];
		} else {
			$orderby = $sort_by;
		}

		// By Date (Newest First)
		if ( $orderby == 'date-new' ) {
			$custom_query_args['orderby'] = 'date';
			$custom_query_args['order'] = 'DESC';
		}

		// By Date (Oldest First)
		if ( $orderby == 'date-old' ) {
			$custom_query_args['orderby'] = 'date';
			$custom_query_args['order'] = 'ASC';
		}

		// By Price (Highest First)
		if ( $orderby == 'price-high' ) {
			$custom_query_args['meta_key'] = 'estate_property_price';
			$custom_query_args['orderby'] = 'meta_value_num';
			$custom_query_args['order'] = 'DESC';
		}

		// By Price (Lowest First)
		if ( $orderby == 'price-low' ) {
			$custom_query_args['meta_key'] = 'estate_property_price';
			$custom_query_args['orderby'] = 'meta_value_num';
			$custom_query_args['order'] = 'ASC';
		}

		// By Name (Ascending)
		if ( $orderby == 'name-asc' ) {
			$custom_query_args['orderby'] = 'title';
			$custom_query_args['order'] = 'ASC';
		}

		// By Name (Ascending)
		if ( $orderby == 'name-desc' ) {
			$custom_query_args['orderby'] = 'title';
			$custom_query_args['order'] = 'DESC';
		}

		if ( $orderby == 'featured' ) {
			$custom_query_args['orderby'] = 'meta_value';
			$custom_query_args['order'] = 'DESC';
			$custom_query_args['meta_key'] = 'estate_property_featured';
		}

		if ( $orderby == 'random' ) {
			$custom_query_args['orderby'] = 'rand';
			$custom_query_args['order'] = '';
		}

		if ( $show_featured_only ) {
			$meta_query[] = array(
				'key'	  	=> 'estate_property_featured',
				'value'	  	=> '1',
				'compare' => 'LIKE'
			);
			$custom_query_args['meta_query'] = $meta_query;
		}

		$custom_query = new WP_Query( $custom_query_args );
		?>

		<?php if ( $custom_query->have_posts() ) : ?>

			<div class="<?php echo esc_attr( $classes ); ?>">

				<div class="hide-initially" id="<?php echo esc_attr( $id ); ?>">
					<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
						<div>
							<?php get_template_part( 'lib/inc/template/property', 'item' ); ?>
						</div>
					<?php endwhile; ?>
				</div>

				<?php if ( $show_arrows_below ) { ?>
					<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $id ); ?>"></div>
				<?php } ?>

				<?php wp_reset_postdata(); ?>

			</div>

		<?php endif; ?>

		<?php
			$slider_params = array(
				'id'                => $id,
				'images_to_show'    => $images_to_show,
				'images_to_show_lg' => $images_to_show_lg,
				'images_to_show_md' => $images_to_show_md,
				'images_to_show_sm' => $images_to_show_sm,
				'autoplay'          => $autoplay,
				'autoplay_speed'    => $autoplay_speed,
				'fade'              => $fade,
				'infinite'          => $infinite,
				'show_arrows'       => $show_arrows,
				'show_arrows_below' => $show_arrows_below,
				'show_dots'         => $show_dots,
				'show_dots_below'   => $show_dots_below,
			);

			tt_script_slick_slider( $slider_params );
		?>

		<?php
		return ob_get_clean();

	}
}
add_shortcode( 'property_carousel', 'tt_realty_property_carousel' );

// Visual Composer Map
function tt_vc_map_property_carousel() {
	vc_map( array(
		'name' => esc_html__( 'Property Carousel', 'realty' ),
		'base' => 'property_carousel',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Sort By', 'realty' ),
				'param_name' => 'sort_by',
				'value' => array(
					'date-new',
					'date-old',
					'price-high',
					'price-low',
					'name-asc',
					'name-desc',
					'featured',
					'random'
				),
				'description' => '',
				'std' => 'date-new'
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show only featured properties', 'realty' ),
				'param_name' => 'show_featured_only',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Location', 'realty' ),
				'param_name' => 'location',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Location". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Status', 'realty' ),
				'param_name' => 'status',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Status". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Type', 'realty' ),
				'param_name' => 'type',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Type". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Features', 'realty' ),
				'param_name' => 'features',
				'value' => '',
				'description' => esc_html__( 'Use "Slug" as you can find under "Properties > Property Features". Separate by comma.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Max. Price', 'realty' ),
				'param_name' => 'max_price',
				'value' => '',
				'description' => '',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Min. Rooms', 'realty' ),
				'param_name' => 'min_rooms',
				'value' => '',
				'description' => '',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Available From', 'realty' ),
				'param_name' => 'available_from',
				'value' => '',
				'description' => esc_html__( 'Format: YYYYMMDD', 'realty' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Autoplay', 'realty' ),
				'param_name' => 'autoplay',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Autoplay Speed in ms', 'realty' ),
				'param_name' => 'autoplay_speed',
				'value' => '5000',
				'dependency' => array(
					'element' => 'autoplay',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Fade Effect', 'realty' ),
				'param_name' => 'fade',
				'value' => array( '' => true ),
				'std' => false,
				'description' => esc_html__( 'Only works when you show one slide at once.', 'realty' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width from 1200px)', 'realty' ),
				'param_name' => 'images_to_show',
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
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 1199px)', 'realty' ),
				'param_name' => 'images_to_show_lg',
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
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 991px)', 'realty' ),
				'param_name' => 'images_to_show_md',
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
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 767px)', 'realty' ),
				'param_name' => 'images_to_show_sm',
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
				'std' => 1
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Infinite', 'realty' ),
				'param_name' => 'infinite',
				'value' => array( '' => true ),
				'std' => true,
				'description' => '',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show navigation arrows', 'realty' ),
				'param_name' => 'show_arrows',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show arrows below', 'realty' ),
				'param_name' => 'show_arrows_below',
				'value' => array( '' => true ),
				'std' => true,
				'dependency' => array(
					'element' => 'show_arrows',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show navigation dots', 'realty' ),
				'param_name' => 'show_dots',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show dots below', 'realty' ),
				'param_name' => 'show_dots_below',
				'value' => array( '' => true ),
				'std' => true,
				'dependency' => array(
					'element' => 'show_dots',
					'not_empty' => true,
				),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_property_carousel' );
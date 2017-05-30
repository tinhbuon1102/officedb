<?php
/**
 * Shortcode: Property Listings
*
*/
if ( ! function_exists( 'tt_realty_property_core_section' ) ) {
	function tt_realty_property_core_section( $atts ) {

		extract( shortcode_atts( array(
			'show_sorting_toggle_view' => false,
			'sort_by'                  => 'date-new',
			'per_page'                 => 9,
			'view'                     => 'grid-view',
			'columns'                  => 2,
			'location'                 => '',
			'status'                   => '',
			'type'                     => '',
			'features'                 => '',
			'max_price'                => '',
			'min_rooms'                => '',
			'available_from'           => '',
		), $atts ) );

		ob_start();

		// Property List/Grid view + sorting options
		if ( $show_sorting_toggle_view ) {
			echo tt_property_listing_sorting_and_view( $sort_by, $view );
		}
		?>

	  <div id="property-items" class="property-items show-compare<?php echo ' ' . esc_attr( $view ); ?>"  data-view="<?php echo $view; ?>">

			<?php get_template_part( 'lib/inc/template/property', 'comparison' ); ?>

			<?php
				if ( ! $per_page ) {
					$per_page = 10;
				}

				// Property Query
				if ( is_front_page() ) {
					$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
				} else {
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				}

				$custom_query_args['paged']	= $paged;
				$custom_query_args['post_type'] = 'property';
				
				

				if ( $per_page ) {
					$custom_query_args['posts_per_page'] = $per_page;
				} else {
					$custom_query_args['posts_per_page'] = 9;
				}

				/**
				 * Meta Queries
				 *
				 */
				$meta_query = array();
				
				$meta_query[] = array(
					'key' 			=> 'estate_property_core_section',
					'value' 		=> 0,
					'compare'		=> '>',
					'type' 			=> 'NUMERIC',
				);
				
				
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

				if ( $orderby == 'random' ) {
					$custom_query_args['orderby'] = 'rand';
					$custom_query_args['order'] = '';
				}

				// Check for search query
				if ( isset( $_GET['pageid'] ) || isset( $_GET['form'] ) || isset( $_GET['searchid'] )  ) {
					$custom_query_args = array();
					$custom_query_args = apply_filters( 'property_search_args', $custom_query_args );
				}
				

				$custom_query = new WP_Query( $custom_query_args );
			?>

			<?php if ( $custom_query->have_posts() ) : ?>

				<ul class="row list-unstyled">
					<?php
						while ( $custom_query->have_posts() ) : $custom_query->the_post();

							// Shortcode Columns Setting
							if ( isset( $columns ) && $columns == 1 ) {
								echo '<li class="col-md-12">';
							} else if ( isset( $columns ) && $columns == 2 ) {
								echo '<li class="col-md-6">';
							} else if ( isset( $columns ) && $columns == 3 ) {
								echo '<li class="col-lg-4 col-md-6">';
							} else if ( isset( $columns ) && $columns == 4 ) {
								echo '<li class="col-lg-3 col-md-6">';
							} else {
								echo '<li class="col-md-4 col-md-6">'; // Default: 3 columns
							}

							get_template_part( 'lib/inc/template/property', 'item' );

							echo '</li>';

						endwhile;
					?>
				</ul>

				<?php wp_reset_query(); ?>

				<?php if (!is_front_page()) {?>
				<div id="pagination">
					<?php
						// Built Property Pagination
						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ) . '#property-items',
							//'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' 			=> '?page=%#%',
							'total' 			=> $custom_query->max_num_pages,
							//'show_all'		=> true,
							'end_size'    => 4,
							'mid_size'    => 2,
							'type'				=> 'list',
							'current'     => $paged,
							'prev_text' 	=> '<i class="icon-arrow-left"></i>',
							'next_text' 	=> '<i class="icon-arrow-right"></i>',
						) );
					?>
				</div>
				<?php }?>

			<?php else : ?>

				<div>
					<p class="lead"><?php esc_html_e('No Properties Found.', 'realty' ) ?></p>
				</div>

			<?php endif; ?>

		</div><!-- .property-items -->

		<?php
		return ob_get_clean();

	}
}
add_shortcode( 'property_core_section', 'tt_realty_property_core_section' );

// Visual Composer Map
function tt_vc_map_realty_property_core_section() {

	vc_map( array(
		'name' => esc_html__( 'Property Core Section Listings', 'realty' ),
		'base' => 'property_core_section',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show Sorting Options', 'realty' ),
				'param_name' => 'show_sorting_toggle_view',
				'value' => array( '' => true ),
				'std' => false
			),
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
					10,
					11,
					12,
					13,
					14,
					15,
					16,
					17,
					18,
					19,
					20
				),
				'description' => '',
				'std' => 9
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'realty' ),
				'param_name' => 'columns',
				'value' => array(
					1,
					2,
					3,
					4,
				),
				'description' => '',
				'std' => 3,
				'dependency' => array(
					'element' => 'view',
					'value' => 'grid-view',
				),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_realty_property_core_section' );

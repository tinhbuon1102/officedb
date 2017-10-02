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
			'columns'                  => 3,
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

	  <script type="text/javascript">
	  	var global_request = <?php echo count($_GET) ? json_encode($_GET) : '{}'?>;
	  </script>
	  <div id="property-items" class="property-search-results  property-items show-compare<?php echo ' ' . esc_attr( $view ); ?>"  data-view="<?php echo $view; ?>">

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
				

				// Check for search query
				$custom_query_args = apply_filters( 'property_search_args', $custom_query_args );
				
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
								echo '<li class="col-lg-3 col-md-3 col-sm-4">';
							} else if ( isset( $columns ) && $columns == 4 ) {
								echo '<li class="col-lg-3 col-md-6">';
							} else {
								echo '<li class="col-md-4 col-md-6">'; // Default: 3 columns
							}

							set_query_var( 'show_floor_title', true );
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

		<div class="modal fade modal-custom" id="building_detail_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
						<span class="linericon-cross" aria-hidden="true">X</span>
					</button>
					<div class="modal-header">
						<h2 class="no-margin bld_name"></h2>
						<h4 class="no-margin bld_sublocate"><span class="addr"></span><span class="station"></span></h4>
					</div>
					<div class="modal-body">
						<div id="details-wrapper" class="details-wrapper motmot open">
							<div id="details" class="js-details container-relative">
								<div class="padding-right-large">
									<div class="container-max">
										<div class="row row-details">
											<div class="col-md-3 col-sm-3 col-xs-12 on-large-auto">
												<div class="container-relative">
													<div class="container-relative container-inline-block">
														<img id="details-enlarged-image" class="js-search-result-thumbnail responsive-img" src="http://front.office-jpdb.com/wp-content/uploads/2017/08/83959350e7c80627-1.jpg">
													</div>
												</div>
											</div>
											<div class="col-md-9 col-sm-9 col-xs-12 details-right-panel">
												<div class="container-flexible-medium left">
												<div class="details-summary"></div>
													<div class="propertyTable">
													<table>
														<thead>
															<tr class="column-head">
																<th class="floor_up_down"><?php echo __('Floor', 'realty')?></th>
																<th class="area_ping"><?php echo __('Area', 'realty')?></th>
																<th class="rent_unit_price"><?php echo __('Rent', 'realty')?></th>
																<th class="unit_condo_fee"><?php echo __('Common service', 'realty')?></th>
																<th class="move_in_date"><?php echo __('Date of occupancy', 'realty')?></th>
																<th class="vacancy_info"><?php echo __('Vacancy', 'realty')?></th>
																<th class="favorite_column"></th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
													</div>
												</div>
											</div>
										</div><!--/row-details-->
									</div>
								</div>
							</div>
						</div><!--/details-wrapper-->
					</div>
				</div>
			</div>
		</div>
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

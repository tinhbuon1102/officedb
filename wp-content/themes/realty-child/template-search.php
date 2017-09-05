<?php get_header();
/*
Template Name: Property Search
*/

global $realty_theme_option;
$listing_view = $realty_theme_option['property-listing-default-view'];
?>

<script>
jQuery(window).load(function() {
	var windowHeight = jQuery(window).height();
	var headerHeight = jQuery('#header').height();
	var verticalMapHeight = windowHeight - headerHeight;
	jQuery('.google-map').height(verticalMapHeight);
});
</script>

<div class="container search-result-container">
<h2 class="page-title"><?php echo __('Properties List', 'realty')?></h2>
	<div class="row">

		<div class="col-md-4 search-container">
			<?php
				wp_reset_postdata();

				// Property Search Form
				if ( isset( $_GET['searchid'] ) ) {
					echo do_shortcode( base64_decode( $_GET['searchid'] ) );
				} else {
					$search_form_columns = 4; // 4 Column Search Form
					include( locate_template( 'lib/inc/template/search-form-custom.php' ) );
				}

				// Build Property Search Query
				$search_results_args = array();
				$search_results_args = apply_filters( 'property_search_args', $search_results_args );

				$query_search_results = new WP_Query( $search_results_args );
			?>

			

		</div>

		<div class="col-md-8 map-container">
			<div id="property-search-results" data-view="<?php if ( isset( $listing_view ) ) { echo $listing_view; } else { echo 'grid-view'; } ?>">
				<div class="property-items show-compare">
			<?php if ( $query_search_results->have_posts() ) : ?>
				<?php $count_results = $query_search_results->found_posts; ?>
				<?php // echo tt_property_listing_sorting_and_view( 'date-low', 'grid-view', false ); xxx-dont-show-because-list-view-overflow-ugly ?>

						<?php get_template_part( 'lib/inc/template/property', 'comparison' ); ?>

						<ul class="row list-unstyled">
							<?php $property_counter = 0; // Required for map marker sync ?>
							<?php while ( $query_search_results->have_posts() ) : $query_search_results->the_post(); ?>
								<li class="col-md-4 col-sm-4 col-xs-6">
									<?php
										$property_id = get_the_id();
										include( locate_template( 'lib/inc/template/property-item-custom.php' ) );
										$property_counter++;
									?>
								</li>
							<?php endwhile; ?>
						</ul>
						
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
																				<th><?php echo __('Floor', 'realty')?></th>
																				<th><?php echo __('Area', 'realty')?></th>
																				<th><?php echo __('Rent', 'realty')?></th>
																				<th><?php echo __('Common service', 'realty')?></th>
																				<th><?php echo __('Date of occupancy', 'realty')?></th>
																				<th></th>
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

						<?php wp_reset_query(); ?>

						<?php 
						if (!is_front_page()) {?>
						<div id="pagination">
							<?php
								// Built Property Pagination
								$big = 999999999; // need an unlikely integer

								echo paginate_links( array(
									'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
									'format' 			=> '?page=%#%',
									'total' 			=> $query_search_results->max_num_pages,
									'end_size'    => 4,
									'mid_size'    => 2,
									'type'				=> 'list',
									'current'     => $search_results_args['paged'],
									'prev_text' 	=> '<i class="icon-arrow-left"></i>',
									'next_text' 	=> '<i class="icon-arrow-right"></i>',
								) );
							?>
						</div>
						<?php }?>

					<?php else : ?>
						<p class="lead text-center text-muted"><?php _e( 'No Properties Match Your Search Criteria.', 'realty' ); ?></p>
					<?php endif; ?>

				</div>
			</div>
		</div>

	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>
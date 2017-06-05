<?php get_header();
/*
Template Name: Property Map Vertical
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
	<div class="row flex-sm">

		<div class="col-sm-6 search-container order2">
			<?php
				wp_reset_postdata();

				// Property Search Form
				if ( isset( $_GET['searchid'] ) ) {
					echo do_shortcode( base64_decode( $_GET['searchid'] ) );
				} else {
					$search_form_columns = 4; // 4 Column Search Form
					$search_form_action = isEnglish() ? site_url('property-map-vertical-2') : site_url('property-map-vertical');
					include( locate_template( 'lib/inc/template/search-form.php' ) );
				}

				// Build Property Search Query
				$search_results_args = array();
				$search_results_args = apply_filters( 'property_search_args', $search_results_args );

				$query_search_results = new WP_Query( $search_results_args );
			?>

			<?php if ( $query_search_results->have_posts() ) : ?>
				<?php $count_results = $query_search_results->found_posts; ?>

				<h2 class="page-title"><?php echo esc_html__( 'Search Results', 'realty' ) . ' (<span>' . $count_results . '</span>)'; ?></h2>

				<?php // echo tt_property_listing_sorting_and_view( 'date-low', 'grid-view', false ); xxx-dont-show-because-list-view-overflow-ugly ?>

				<div id="property-search-results" data-view="<?php if ( isset( $listing_view ) ) { echo $listing_view; } else { echo 'grid-view'; } ?>">
					<div class="property-items show-compare">

						<?php get_template_part( 'lib/inc/template/property', 'comparison' ); ?>

						<ul class="row list-unstyled">
							<?php $property_counter = 0; // Required for map marker sync ?>
							<?php while ( $query_search_results->have_posts() ) : $query_search_results->the_post(); ?>
								<li class="col-lg-6">
									<?php
										$property_id = get_the_id();
										include( locate_template( 'lib/inc/template/property-item.php' ) );
										$property_counter++;
									?>
								</li>
							<?php endwhile; ?>
						</ul>

						<?php wp_reset_query(); ?>

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

					<?php else : ?>
						<p class="lead text-center text-muted"><?php _e( 'No Properties Match Your Search Criteria.', 'realty' ); ?></p>
					<?php endif; ?>

				</div>
			</div>

		</div>

		<div class="col-sm-6 map-container order1">
			<?php echo do_shortcode( '[map]' ); ?>
		</div>

	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>
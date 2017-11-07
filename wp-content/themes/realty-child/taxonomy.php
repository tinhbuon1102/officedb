<?php
global $wp_query, $realty_theme_option;
$taxonomy = $wp_query->query;
$taxonomy_name = key($taxonomy);
$taxonomy_slug = end($taxonomy);
if ($taxonomy_name == 'property-location')
{
	$oTerm = get_term_by('slug', $taxonomy_slug, $taxonomy_name);
	$search_page = get_permalink( tt_page_id_template_search());
	
	$params = array_merge(array("location['.$oTerm->term_id.']" => $taxonomy_slug), $_GET);
	$new_query_string = http_build_query($params);
	
	wp_redirect($search_page . '?' . $new_query_string, 301);
	exit();
}
get_header();

$listing_view = $realty_theme_option['property-listing-default-view'];

$grid_active = "active";
$list_active = "";

if ( $listing_view == "list-view" ) {
	$grid_active = "";
	$list_active = "active";
}


$_GET[str_replace('property-', '', $taxonomy_name)] = $taxonomy_slug;
$search_results_args = apply_filters( 'property_search_args', $search_results_args );
$search_results_args = buildSearchArgs($search_results_args);

$wp_query = new WP_Query( $search_results_args );
?>

<div class="taxonomy-results container">

	<div class="search-results-header clearfix">

		<h2 class="page-title">
			<?php
			if ( is_tax( 'property-location' ) ) {
				echo __( 'Location:', 'realty' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-status' ) ) {
				echo __( 'Status:', 'realty' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-type' ) ) {
				echo __( 'Type:', 'realty' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-features' ) ) {
				echo __( 'Feature:', 'realty' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			echo ' (' . $wp_query->found_posts . ')';
			?>
		</h2>

		<div class="taxonomy-description">
			<?php echo term_description(); ?>
		</div>

		<?php get_template_part( 'lib/inc/template/property', 'comparison' ); ?>
		<?php echo tt_property_listing_sorting_and_view( null, null, false ); ?>

	</div>

	<?php if ( have_posts() ) : ?>
	<div id="property-search-results" data-view="<?php echo $listing_view; ?>">

		<div class="property-items show-compare">

			<ul class="row list-unstyled">
				<?php
				while ( have_posts() ) : the_post();

				$columns = $realty_theme_option['property-listing-columns'];
				if ( empty($columns) ) {
					$columns = "col-md-6";
				}
				?>
				<li class="<?php echo $columns; ?>">
					<?php get_template_part( 'lib/inc/template/property', 'item' );	?>
				</li>
				<?php endwhile; ?>
			</ul>

			<div id="pagination">
			<?php
			// Built Property Pagination
			$big = 999999999; // need an unlikely integer
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			echo paginate_links( array(
				'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' 			=> '?page=%#%',
				'total' 			=> $wp_query->max_num_pages,
				//'show_all'		=> true,
				'end_size'           => 4,
				'mid_size'           => 2,
				'type'				=> 'list',
				'current'     => $paged,
				'prev_text' 	=> '<i class=realty-arrow-left"></i>',
				'next_text' 	=> '<i class="realty-arrow-right"></i>',
			) );
			?>
			</div>

			<?php
			else : ?>
			<p class="lead text-center text-muted"><?php _e( 'Nothing Matches Your Criteria.', 'realty' ); ?></p>
			<?php
			endif;
			?>

		</div>
	</div>

</div>

<?php get_footer(); ?>
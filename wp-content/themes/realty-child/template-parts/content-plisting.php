

<div class="container">

	<h2 class="page-title"><?php the_title(); ?></h2>

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
<div class="col-md-8">
<?php echo tt_post_content_navigation(); ?>
</div>
</div>
</div><!-- .container -->
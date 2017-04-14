<?php get_header();
/*
Template Name: Property Comparison
*/

global $realty_theme_option;
$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php tt_page_banner();	?>

	<div id="page-property-comparison" class="container">
			<div id="main-content">
				<?php the_content(); ?>
				<p id="msg-no-properties" class="hide alert alert-info"><?php esc_html_e( 'You haven\'t added any properties to compare.', 'realty' ); ?></p>
				<div id="property-comparison-table"></div>
			</div>

		</div>
	</div>

	<script>
	jQuery(window).load(function() {

		var properties;
		properties = store.get('comparison');

		jQuery.ajax({

		  type: 'GET',
		  url: ajax_object.ajax_url,
		  data: {
		    'action'          :   'tt_ajax_property_comparison', // WP Function
		    'properties'      :   properties
		  },
		  success: function (response) {

		  	// If Temporary Favorites Found, Show Them
		  	if ( store.get('comparison') != "" ) {

		  		jQuery('#property-comparison-table').html(response);

		  		if ( properties.length == 3 ) {
						jQuery('.comparison-data').addClass('columns-3');
					} else if ( properties.length == 4 ) {
						jQuery('.comparison-data').addClass('columns-4');
					} else {
						jQuery('.comparison-data').addClass('columns-2');
					}

			    jQuery('.remove-property-from-comparison').attr('title', 'Remove');
			    jQuery('.remove-property-from-comparison').toggleClass('fa-plus fa-minus');
			    jQuery('i').tooltip();

			    jQuery('.remove-property-from-comparison').click(function() {

						jQuery(this).closest('li').fadeOut(400, function() {
							jQuery(this).remove();
							var numberOfFavorites = jQuery('.property-item').length;
							if ( numberOfFavorites == 0 ) {
								jQuery('#msg-no-properties').toggleClass('hide');
							}
						});

						// Check If Browser Supports LocalStorage
						if (!store.enabled) {
					    throw new Error("<?php esc_html_e( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'realty' ); ?>");
					  }

						// Check For Proeprties To Compare (store.js plugin)
						if ( store.get('comparison') ) {

							// Check If item Already In Comparison Array
							function inArray(needle, haystack) {
						    var length = haystack.length;
						    for( var i = 0; i < length; i++ ) {
					        if(haystack[i] == needle) return true;
						    }
						    return false;
							}

							var getComparisonAll = store.get('comparison');
							var propertyToCompare = jQuery(this).attr('data-compare-id');

							// Remove Property From Comparison
							if ( inArray( propertyToCompare, getComparisonAll ) ) {
								var index = getComparisonAll.indexOf(propertyToCompare);
								getComparisonAll.splice(index, 1);
								location.reload();
							}

							store.set( 'comparison', getComparisonAll );

						}

					});

				} else {
					jQuery('#msg-no-properties').toggleClass('hide');
				}

				console.log( store.get('comparison') );

		  },
		  error: function () {
		    console.log("no properties to compare");
		  }

		});

	});
	</script>

<?php endwhile; ?>

<?php get_footer(); ?>
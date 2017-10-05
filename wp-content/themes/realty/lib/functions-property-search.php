<?php
/* PROPERTY SEARCH QUERY ARGUMENTS
============================== */
if ( ! function_exists( 'tt_property_search_args' ) ) {
	function tt_property_search_args( $search_results_args ) {

		global $realty_theme_option;

		/* define arrays for META & TAX QUERIES: */
		$meta_query = array();
		$tax_query = array();
		$search_fields = array();

		/* Generate Query */

		$search_results_args['post_type'] = 'property';
		$search_results_args['post_status'] = 'publish';
		$search_results_args['paged'] = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$search_results_per_page = $realty_theme_option['search-results-per-page'];

		// Search Results Per Page: Check for Theme Option
		if (!isset($search_results_args['posts_per_page']))
		{
			if ( $search_results_per_page ) {
				$search_results_args['posts_per_page'] = $search_results_per_page;
			} else {
				$search_results_args['posts_per_page'] = 10;
			}
		}

		/* META & TAX QUERIES:
		============================== */

		$i = 0;

		if ( isset( $_GET['searchid'] ) ) {
			foreach ( $_GET as $search_key => $search_value ) {
				if ( $search_key == "order-by" || $search_key == "pageid" ) {
					break;
				}
				// Check If Key Has A Value
				if ( ( ! empty( $search_value ) || $search_key == "price_range_min" ) && 
						$search_key != "order-by" && 
						$search_key != "action" &&
						$search_key != "building_id" &&
						$search_key != "lang" &&
						$search_key != "response" &&
						$search_key != "search_type" &&
						$search_key != "pageid" && 
						$search_key != "searchid" ) {
					$search_fields[] = $search_key;
					$search_parameters[] = $search_key;
				}
			}
		}
		
		$custom_search = $_GET['keyword'];
		$_GET['search_type'] = isset($_GET['search_type']) ? $_GET['search_type'] : 'floor';
		$search_type = $_GET['search_type'];
		
		if (isset($_GET['search_type']))
		{
			switch ($_GET['search_type'])
			{
				case 'station':
				case 'address':
					unset($_GET['keyword']);
					unset($realty_theme_option['property-search-parameter'][0]);
					unset($realty_theme_option['property-search-field'][0]);
					unset($realty_theme_option['property-search-compare'][0]);
					break;
			}
		}
		$searching_fields = array();
		$defaultParams = array(
			'keyword' => '',
			'location' => 'all',
			'size' => 'all',
		);
		$_GET = array_merge($defaultParams, $_GET);
		
		foreach ( $_GET as $search_key => $search_value ) {
			// Exclude all nonproperty seach parameters
			if ( 
					$search_key == "action" || 
					$search_key == "building_id" || 
					$search_key == "lang" || 
					$search_key == "response" ||
					$search_key == "search_type" ||
					$search_key == "order-by" || 
					$search_key == "pageid" || 
					$search_key == "login" || 
					$search_key == "user" || 
					$search_key == "sign-user" || 
					$search_key == "user-register" ) {
				continue;
			}
			// Check If Key Has A Value
			$aExcludeSearchKey = array('order-by', 'pagenumber', 'pageid', 'page_id', 'searchid', 'search_type');
			if ( ( ! empty( $search_value ) || $search_key == "price_range_min" ) && !in_array($search_key, $aExcludeSearchKey) ) {

				// Search Form Mini
				if ( isset ( $_GET['form'] ) && $_GET['form'] == "mini" ) {
					$search_parameters = $realty_theme_option['property-search-mini-parameter'];
					$search_fields = $realty_theme_option['property-search-mini-field'];
					$search_position = array_search( $search_key, $search_parameters );
					$search_compare = $realty_theme_option['property-search-mini-compare'][$search_position];
					$search_field = $search_fields[$search_position];
				}

				// Multi Search Form
				else if ( isset( $_GET['searchid'] ) ) {

					$search_field = $search_key;
					$search_fields[$i]= $search_key;
					$search_parameter = $search_key;
					$search_compare = 'equal';

					if ( $search_key == 'price_range_min' || $search_key == 'price_range_max' ) {
						$search_fields[$i] = 'estate_property_pricerange';
					}
					if ( $search_key == 'estate_property_available_from' ) {
						$search_compare = 'greater_than';
					}
					if ( $search_key == 'estate_property_price_min' ) {
						$search_fields[$i] = 'estate_property_price';
						$search_compare = 'greater_than';
					}
					if ( $search_key == 'estate_property_price_max' ) {
						$search_fields[$i] = 'estate_property_price';
						$search_compare = 'less_than';
					}

				} else {
					// Default Search Form
					$realty_theme_option['property-search-parameter'][] = 'type';
					$realty_theme_option['property-search-parameter'][] = 'status';
					
					$realty_theme_option['property-search-field'][] = 'estate_property_type';
					$realty_theme_option['property-search-field'][] = 'estate_property_status';
					
					$search_parameters = $realty_theme_option['property-search-parameter'];
					$search_fields = $realty_theme_option['property-search-field'];
					$search_position = array_search( $search_key, $search_parameters );
					$search_compare = $realty_theme_option['property-search-compare'][$search_position];
					$search_field = $search_fields[$search_position];
				}
				
				if ( $search_type == 'station' ) {
					$search_field = 'estate_property_station';
					$search_fields[$i] = $search_field;
					$search_value = $custom_search;
				}
				elseif ( $search_type == 'address' ) {
					$search_field = 'estate_property_google_maps';
					$search_fields[$i] = $search_field;
					$search_value = $custom_search;
				} 

				$search_field = trim($search_field);
				$searching_fields[$search_key] = $search_field;
				switch ( $search_compare ) {
					case 'greater_than' : case 'greather_than' : $search_compare = '>='; break; // Do NOT delete "greather_than" typo
					case 'less_than'                           : $search_compare = '<='; break;
					case 'like'                                : $search_compare = 'LIKE'; break;
					default                                    : $search_compare = '='; break;
				}

				// Default Fields
				$default_search_fields_array = array(
					'estate_search_by_keyword',
					'estate_property_id',
					'estate_property_location',
					'estate_property_type',
					'estate_property_status',
					'estate_property_price',
					'estate_property_pricerange',
					'estate_property_size',
					'estate_property_rooms',
					'estate_property_bedrooms',
					'estate_property_bathrooms',
					'estate_property_garages',
					'estate_property_available_from',
					'estate_property_station',
					'estate_property_google_maps'
				);
				
				// Default Fields
				if ( isset( $search_fields[$i] ) && in_array( $search_fields[$i], $default_search_fields_array ) ) {

					switch ( $search_fields[$i] ) {

						// Keyword Search
						case 'estate_search_by_keyword' :
							$search_results_args['s'] = $search_value;
							
							if (isset($_GET['search_type']) && $_GET['search_type'] == 'floor')
							{
								$meta_query[] = array(
									'relation' => 'OR',
									array(
										'key' 			=> 'estate_property_station',
										'value' 		=> $search_value,
										'compare' 	=> 'LIKE',
									),
									array(
										'key' 			=> 'estate_property_google_maps',
										'value' 		=> $search_value,
										'compare' 	=> 'LIKE'
									),
								);
							}
							
						break;
						
						case 'estate_property_station' :
							if (defined('DOING_AJAX') && DOING_AJAX) {
								$search_results_args['posts_per_page'] = 5;
							}
								
							$meta_query[] = array(
								'key' 			=> 'estate_property_station',
								'value' 		=> $search_value,
								'compare' 	=> 'LIKE'
							);
							break;
							
						case 'estate_property_google_maps' :
							if (defined('DOING_AJAX') && DOING_AJAX) {
								$search_results_args['posts_per_page'] = 5;
							}
							$meta_query[] = array(
							'key' 			=> 'estate_property_google_maps',
							'value' 		=> $search_value,
							'compare' 	=> 'LIKE'
									);
							break;

						case 'estate_property_id' :
							if ( $realty_theme_option['property-id-type'] == "post_id" ) {
								$search_results_args['p'] = $search_value; // Post ID = Default Property ID
							} else {
								$meta_query[] = array(
									'key' 			=> 'estate_property_id',
									'value' 		=> $search_value
								);
							}
						break;

						case 'estate_property_price' :

						if ( $realty_theme_option['property-search-price-dropdown'] == 'dropdown-range' ) {

							// Price Range Dropdown
							$price_dropdown_range = explode( '-', $search_value, 2 );
							$price_dropdown_range_count = count( $price_dropdown_range );

							if ( is_array( $price_dropdown_range ) && $price_dropdown_range_count == 2 ) {

								$meta_query[] = array(
								'key'     => 'estate_property_price',
								'value'   => array( $price_dropdown_range[0] , $price_dropdown_range[1] ),
								'type'    => 'NUMERIC',
								'compare' => 'BETWEEN'
								);

							} else {
								// For last option
								$meta_query[] = array(
								'key'     => 'estate_property_price',
								'value'   => $search_value,
								'type'    => 'NUMERIC',
								'compare' => '>='
								);


							}

						} else {

						// Single Price Value
						$meta_query[] = array(
							'key' 			=> 'estate_property_price',
							'value' 		=> $search_value,
							'type' 			=> 'NUMERIC',
					    'compare' 	=> $search_compare
						);

						}

						break;

						case 'estate_property_pricerange' :
							if ( $_GET['price_range_min'] == $realty_theme_option['property-search-price-range-min']  && $_GET['price_range_max'] == $realty_theme_option['property-search-price-range-max'] ) {
							} else {
								if ( $realty_theme_option['property-search-field-relation'] == "OR" ) {
									$meta_query[] = array(
										'key' 			=> 'estate_property_price',
										'value' 		=> -1,
										'type' 			=> 'NUMERIC',
						    			'compare' 	=> '='
							    		);
								}
								$meta_query[] = array(
									'key' 			=> 'estate_property_price',
									'value' 		=> array( $_GET['price_range_min'], $_GET['price_range_max'] ),
									'type' 			=> 'NUMERIC',
									'compare' 	=> 'BETWEEN'
								);
							}
						break;

						case 'estate_property_size' :
							if ($search_value && $search_value != 'all')
							{
								$aSizes = explode('-', $search_value); 
								$meta_query[] = array(
									'key' 			=> 'estate_property_size',
									'value' 		=> array((float)($aSizes[0] ? $aSizes[0] : 1), (float)($aSizes[1] ? $aSizes[1] : 100000000)),
									'type' 			=> 'NUMERIC',
							    	'compare' 	=> 'BETWEEN'
								);
							}
						break;

						case 'estate_property_rooms' :
							$meta_query[] = array(
								'key' 			=> 'estate_property_rooms',
								'value' 		=> $search_value,
								'type' 			=> 'NUMERIC',
						    	'compare' 	=> $search_compare
							);
						break;

						case 'estate_property_bedrooms' :
							$meta_query[] = array(
								'key' 			=> 'estate_property_bedrooms',
								'value' 		=> $search_value,
								'type' 			=> 'NUMERIC',
						    	'compare' 	=> $search_compare
							);
						break;

						case 'estate_property_bathrooms' :
							$meta_query[] = array(
								'key' 			=> 'estate_property_bathrooms',
								'value' 		=> $search_value,
								'type' 			=> 'NUMERIC',
						    	'compare' 	=> $search_compare
							);
						break;

						case 'estate_property_garages' :
							$meta_query[] = array(
								'key' 			=> 'estate_property_garages',
								'value' 		=> $search_value,
								'type' 			=> 'NUMERIC',
						    	'compare' 	=> $search_compare
							);
						break;

						case 'estate_property_available_from' :
							$meta_query[] = array(
								'key' 			=> 'estate_property_available_from',
								'value' 		=> $search_value,
								'type' 			=> 'DATE',
						    	'compare' 	=> $search_compare
							);
						break;

						case 'estate_property_location' :
							if ( $search_value != "all" ) {  //&& strpos( $search_value, '/' ) !== true
								$search_value = (array)$search_value;
								
								foreach ($search_value as $location_slug)
								{
									if (in_array($location_slug, getOtherCities()))
									{
										// If location selected has "other", will get all properties has other location
										// We have to must "$search_value" with other location (52 location)
										$search_value += getNotSearchingCities('slug');
										break;
									}
								}
								
								$tax_query[] = array(
									'taxonomy' 	=> 'property-location',
									'field' 		=> 'slug',
									'terms'			=> $search_value
								);
							}
						break;

						case 'estate_property_type' :
							if ( $search_value != "all" ) {
								$tax_query[] = array(
									'taxonomy' 	=> 'property-type',
									'field' 		=> 'slug',
									'terms'			=> $search_value
								);
							}
						break;

						case 'estate_property_status' :
							if ( $search_value != "all") {
								$tax_query[] = array(
									'taxonomy' 	=> 'property-status',
									'field' 		=> 'slug',
									'terms'			=> $search_value
								);
							}
						break;

						case 'feature' :
							/*if ( $search_value != "all" ) {
								$tax_query[] = array(
									'taxonomy' 	=> 'property-features',
									'field' 		=> 'slug',
									'terms'			=> $_GET['feature'][0]
								);
							}*/
						break;

					} // switch

				} // if (Default Fields)

				// Advanced Custom Fields (ACF plugin)
				else if ( tt_acf_active() && $search_value != 'all' && isset( $search_fields[$i] ) && in_array( $search_fields[$i], tt_acf_fields_name( tt_acf_group_id_property() ) ) ) {

					// Get Field Type
					$acf_field_position = array_search( $search_fields[$i], tt_acf_fields_name( tt_acf_group_id_property() ) );
					$acf_field_type_key = tt_acf_fields_type( tt_acf_group_id_property() );
					$acf_field_type = $acf_field_type_key[$acf_field_position];

					$type = '';

					switch ( $acf_field_type ) {
						case ( 'text' ) : $type = 'CHAR'; break;
						case ( 'number' ) : $type = 'NUMERIC'; break;
						case ( 'date_picker' ) : $type = 'DATE'; break;
					}

					// ACF Type: Checkbox & Radio Buttons
					if ( $acf_field_type == 'checkbox' || $acf_field_type == 'select' || $acf_field_type == 'radio' ) {
						$meta_query[] = array(
							'key' 			=> $search_key,
							'value' 		=> $search_value,
							'compare' 	=> 'LIKE'
						);
					} else if ( $acf_field_type == 'text' || $acf_field_type == 'number' || $acf_field_type == 'date_picker' ) {
						$meta_query[] = array(
							'key' 			=> $search_fields[$i],
							'value' 		=> $search_value,
							'type' 			=> $type,
				    		'compare' 	    => $search_compare
						);
					} else { // Type not supported, no comparison needed
						$meta_query[] = array(
							'key' 			=> $search_key,
							'value' 		=> $search_value
						);
					}

				} // endif ACF;

			} // endif !empty( $search_value )

			// Dont increase $i for price range, as we are using two parameters (min & max) already
			if ( $search_key != "price_range_min" ) {
				$i++;
			}

		} // end foreach()

		if ( isset( $_GET['feature'] ) ) {
			$taxs = $_GET['feature'];
			if ( $taxs ) {
				foreach ( $taxs as $taxi ) {
					$tax_query[] = array(
						'taxonomy' => 'property-features',
						'field'    => 'slug',
						'terms'    => $taxi
					);
				}
			}
		}

		// Search Results Order
		if (( ! empty( $_GET[ 'order-by' ]) || ! empty( $realty_theme_option['search_results_default_order'] ) ) && !isset($search_results_args['orderby']) ) {

			if ( ! empty( $_GET[ 'order-by' ] ) ) {
				$orderby = $_GET[ 'order-by' ];
			} else {
				$orderby = $realty_theme_option['search_results_default_order'];
			}

			// By Date (Newest First)
			if ( $orderby == 'date-new' ) {
				$search_results_args['orderby'] = 'date';
				$search_results_args['order'] = 'DESC';
			}

			// By Date (Oldest First)
			if ( $orderby == 'date-old' ) {
				$search_results_args['orderby'] = 'date';
				$search_results_args['order'] = 'ASC';
			}

			// By Price (Highest First)
			if ( $orderby == 'price-high' ) {
				$search_results_args['meta_key'] = 'estate_property_price';
				$search_results_args['orderby'] = 'meta_value_num';
				$search_results_args['order'] = 'DESC';
			}

			// By Price (Lowest First)
			if ( $orderby == 'price-low' ) {
				$search_results_args['meta_key'] = 'estate_property_price';
				$search_results_args['orderby'] = 'meta_value_num';
				$search_results_args['order'] = 'ASC';
			}
			// By Name (Ascending)
			if ( $orderby == 'name-asc' ) {
				$search_results_args['orderby'] = 'title';
				$search_results_args['order'] = 'ASC';
			}
			// By Name (Ascending)
			if ( $orderby == 'name-desc' ) {
				$search_results_args['orderby'] = 'title';
				$search_results_args['order'] = 'DESC';
			}
			if ( $orderby == 'featured' ) {
				$search_results_args['meta_key'] = 'estate_property_featured';
				$search_results_args['orderby'] = 'meta_value';
				$search_results_args['order'] = 'DESC';
				/* $meta_query[]= array(
						array(
							'key'     => 'estate_property_featured',
							'value'   => '1',
							'compare' => 'LIKE',
						),
				); */
			}

			// Random
			if ( $orderby == 'random' ) {
				$search_results_args['orderby'] = 'rand';
			}

		} else {
			$orderby = '';
		}

		// Count meta & tax querie, then set relation for search query
		$meta_count = count( $meta_query );
		$search_fields_realtion = $realty_theme_option['property-search-field-relation'];


		if ( $search_fields_realtion == "AND" ) {
			if ( $meta_count > 1 ) {
			  $meta_query['relation'] = 'AND';
			}

			// Count taxonomy queries + set their relation for search query
			$tax_count = count( $tax_query );
			if ( $tax_count > 1 ) {
				$tax_query['relation'] = 'AND';
			}
		} else {
			if ( $meta_count > 1 ) {
			  $meta_query['relation'] = 'OR';
			}

			// Count taxonomy queries + set their relation for search query
			$tax_count = count( $tax_query );
			if ( $tax_count > 1 ) {
				$tax_query['relation'] = 'OR';
			}

		}

		if ( $meta_count > 0 ) {
			$search_results_args['meta_query'] = isset($search_results_args['meta_query']) ? array_merge($search_results_args['meta_query'], $meta_query) : $meta_query;
		}

		if ( $tax_count > 0 ) {
			$search_results_args['tax_query'] = $tax_query;
		}

// 		if (defined('DOING_AJAX') && DOING_AJAX && $_REQUEST['action'] == 'tt_ajax_search') {
			// order by vacancy
			$search_results_args['meta_key'] = 'floor_vacancy';
			$search_results_args['orderby'] = 'meta_value_num';
			$search_results_args['order'] = 'DESC';
// 		}
		
		$size_key = array_search('estate_property_size', $searching_fields);
		if (
				((!isset($_GET[ 'order-by' ]) || !$_GET[ 'order-by' ] || !in_array($_GET['order-by'], array('price-high', 'price-low', 'size')))
				&& ($size_key === false || !$_GET[$size_key] || $_GET[$size_key] == 'all'))
				|| strpos($_SERVER['REQUEST_URI'], 'search-properties') !== false
				|| strpos($_SERVER['REQUEST_URI'], 'property-core-section-listing') !== false
			)
		{
			$search_results_args = buildSearchArgs($search_results_args);
		}
		
		return $search_results_args;

	}
}
add_filter( 'property_search_args', 'tt_property_search_args' );

/**
 * AJAX - Search
 *
 */
if ( ! function_exists( 'tt_ajax_search' ) ) {
	function tt_ajax_search() {

		// Build Property Search Query
		$search_results_args = array();
		global $realty_theme_option;

		if ( isset( $_GET['pagenumber'] ) ) {
			set_query_var( 'paged', $_GET['pagenumber'] );
		}

		$search_results_args = apply_filters( 'property_search_args', $search_results_args );

		// Query Only Property Owners' Properties On "My Properties" Page Template (Admins Can View Every Property)
		$page_template = get_page_template_slug($_GET['pageid']);
		if ( $page_template == 'template-property-submit-listing.php' && ! current_user_can( 'manage_options' ) ) {
			global $current_user;
			$current_user = wp_get_current_user();
			$search_results_args['author'] = $current_user->ID;
		}

		$count_results = 0;

		$query_search_results = new WP_Query( $search_results_args );

		if ( ! isset( $orderby ) || empty( $orderby ) ) {
			$orderby = "date-new";
		}

		if ((isset($_GET['response']) && $_GET['response'] == 'json'))
		{
			$floors = $stations = array();
			$aStation = $aDistrict = array();
			if ( $query_search_results->have_posts() ) {
				$count = 1;
				while ( $query_search_results->have_posts() ) : $query_search_results->the_post();
					$google_maps = get_post_meta( get_the_ID(), 'estate_property_google_maps', true );
					$post_title = isset($_GET['size']) && $_GET['size'] && $_GET['size'] != 'all' ? get_the_title() : get_post_meta(get_the_ID(), 'post_title_building', true);
					
					if ($_REQUEST['search_type'] == 'station')
					{
						$station = get_post_meta(get_the_ID(), 'estate_property_station', true);
						if (!in_array($station, $aStation))
						{
							$url = getSearchUrl() . '?search_type=station&keyword=' . $station;
							$stations[] = array('name' => $station, 'title' => $station, 'url' => $url, 'group_name' => trans_text('Stations'));
							$aStation[] = $station;
						}
					}
					elseif ($_REQUEST['search_type'] == 'address')
					{
						$locations = wp_get_post_terms( get_the_ID(), 'property-location');
						if (!empty($locations))
						{
							$district = $locations[0]->name;
							if (!in_array($district, $aDistrict))
							{
								$addresses[] = array(
									'name' => $district, 
									'title' => $district,
									'url' => get_term_link($locations[0]->term_id, 'property-location'),
									'group_name' => trans_text('Addresses')
								);
								$aDistrict[] = $district;
							}
						}
					}
					$floors[] = array(
						'id' => get_the_ID(),
						'id_str' => (string)get_the_ID(),
						'name' => (string)$post_title . '<span style="display:none">'.$_GET['keyword'].'</span>',
						'title' => (string)$post_title,
						'image_url' => (string)get_the_post_thumbnail_url(),
						'url' => (string)get_permalink(),
						'address' => (string)$google_maps['address'],
						'group_name' => trans_text('Floors')
					);
					$count ++;
				endwhile;
			}
			
			if ($_REQUEST['search_type'] == 'station')
			{
				echo json_encode(array('data' => array('station' => $stations,'floor' => $floors)));
			}
			elseif ($_REQUEST['search_type'] == 'address')
			{
				echo json_encode(array('data' => array('address' => $addresses)));
			}
			else {
				echo json_encode(array('data' => array('floor' => $floors)));
			}
			die;
		}
		
		if ( $query_search_results->have_posts() ) :

		$count_results = $query_search_results->found_posts;
		// template-property-search.php
	  get_template_part( 'lib/inc/template/property', 'comparison' );
		?>
		<ul class="row list-unstyled">
			<?php
				if ( $page_template == 'template-map-vertical.php' ) {
					// Specific column grid columns setting for vertical property map page template
					$columns = 'col-lg-6';
				} else {
					// Theme Option: Default Property Listings Columns
					$columns = $realty_theme_option['property-listing-columns'];
				}
				?>

				<?php $property_counter = 0; // Required for map marker sync ?>

			<?php while ( $query_search_results->have_posts() ) : $query_search_results->the_post(); ?>
				<li class="<?php echo $columns; ?>">
					<?php
						$property_id = get_the_id();
						include( locate_template( 'lib/inc/template/property-item-custom.php' ) );
						$property_counter++;
					?>
				</li>
			<?php endwhile; ?>
		</ul>

		<?php wp_reset_query(); ?>

		<div id="pagination" class="pagination-ajax">
			<?php
				// Built Property Pagination
				$big = 999999999; // need an unlikely integer
				$original_request_uri = $_SERVER['REQUEST_URI'];
				$base = $_GET['base'];
				$_SERVER['REQUEST_URI'] = $base;

				if ( is_rtl() || $realty_theme_option['enable-rtl-support'] ){
					$rtl_pagination_right_nav = '<i class="icon-arrow-left"></i>';
					$rtl_pagination_left_nav = '<i class="icon-arrow-right"></i>';
				} else {
					$rtl_pagination_right_nav = '<i class=icon-arrow-right"></i>';
					$rtl_pagination_left_nav = '<i class="icon-arrow-left"></i>';

				}
				//echo $rtl_pagination_right_nav;

				echo paginate_links( array(
					//'base' 				=> trim(str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )),
					'total' 			=> $query_search_results->max_num_pages,
					'type'				=> 'list',
					'end_size'    => 4,
					'mid_size'    => 2,
					'current'     => $search_results_args['paged'],
					'prev_next'  	=> true,
					'prev_text' 	=> $rtl_pagination_left_nav,
					'next_text' 	=> $rtl_pagination_right_nav,
				) );

				$_SERVER['REQUEST_URI'] = $original_request_uri;
			?>
		</div>

		<?php
		else : ?>
			<p class="lead text-center text-muted"><?php _e( 'No Properties Match Your Search Criteria.', 'realty' ); ?></p>
		<?php endif; ?>

		<script>
			jQuery( '.pagination-ajax a' ).each(function() {
				this.href = this.href.replace(jQuery( this ).attr('href'), jQuery( this ).attr('href')+'.property-items');
			});

			jQuery('.search-results-header, #property-search-results').fadeOut(0);

			<?php	if ( ! $count_results ) { // No Results Found ?>
				jQuery('#map-overlay-no-results, #property-search-results').fadeIn();
				jQuery('.page-title span').html(<?php echo 0; ?>);
			<?php } else { // Results Found ?>
				<?php
					// AJAX Refresh Property Map Markers
					$search_results_args['posts_per_page'] = -1;
					$query_search_results = new WP_Query( $search_results_args );

					if ( $query_search_results->have_posts() ) :

						$property_string = '';
						$i = 0;

						while ( $query_search_results->have_posts() ) : $query_search_results->the_post();
							global $post;
							$google_maps = get_post_meta( $post->ID, 'estate_property_google_maps', true );

							// Check For Map Coordinates
							if ( $google_maps ) {
								//$coordinate = explode( ',', $google_maps );
								$property_string .= "{ ";
								$property_string .= 'permalink:"' . get_permalink() . '", ';
								$property_string .= 'title:"' . get_the_title() . '", ';
								$property_string .= 'price:"' . tt_property_price() . '", ';
								$property_string .= 'latLng: new google.maps.LatLng(' . $google_maps['lat'] . ', ' . $google_maps['lng'] . '), ';

								if ( has_post_thumbnail() ) {
									$property_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'property-thumb' );
									$property_string .= 'thumbnail: "' . $property_thumbnail[0] . '"';
								} else {
									$property_string .= 'thumbnail: "//placehold.it/600x300/eee/ccc/&text=.."';
								}

								$property_string .= ' },' . "\n";
							}

						$i++;
						endwhile;

						wp_reset_query();

					endif;
				?>

				// Check: Do we have a map on the requesting page?
				if ( jQuery('.google-map').length > 0 ) {

					markerCluster.clearMarkers();
					initMarkers(map, [ <?php echo $property_string; ?> ]);
					markerCluster = new MarkerClusterer(map, newMarkers, markerClusterOptions);

					<?php echo tt_mapMarkers(); ?>

					// Zoom level 13 when bounds change
					google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
						if (this.getZoom() > 13) {
					    this.setZoom(13);
					  }
					});

					jQuery('#map-overlay-no-results').fadeOut();

				}

				jQuery('.search-results-header, #property-search-results').fadeIn();
				jQuery('.page-title span').html(<?php echo $count_results; ?>);

			<?php } ?>
		</script>

		<?php

		die();

	}
}
add_action('wp_ajax_tt_ajax_search', 'tt_ajax_search');
add_action('wp_ajax_nopriv_tt_ajax_search', 'tt_ajax_search');
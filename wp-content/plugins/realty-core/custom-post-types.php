<?php
/**
 * Register post type "property"
 *
 */
if ( ! function_exists( 'tt_register_custom_post_type_property' ) ) {
	function tt_register_custom_post_type_property() {

		global $realty_theme_option;

		if ( isset( $realty_theme_option['property-slug'] ) && ! empty( $realty_theme_option['property-slug'] ) ) {
			$property_slug = $realty_theme_option['property-slug'];
		} else {
			$property_slug = esc_html__( 'property', 'realty' );
		}

	  $labels = array(
	    'name'               => esc_html__( 'Properties','realty' ),
	    'singular_name'      => esc_html__( 'Property','realty' ),
	    'add_new'            => esc_html__( 'Add New','realty' ),
	    'add_new_item'       => esc_html__( 'Add New Property','realty' ),
	    'edit_item'          => esc_html__( 'Edit Property','realty' ),
	    'new_item'           => esc_html__( 'New Property','realty' ),
	    'view_item'          => esc_html__( 'View Property','realty' ),
	    'search_items'       => esc_html__( 'Search Property','realty' ),
	    'not_found'          => esc_html__( 'No Property found.','realty' ),
	    'not_found_in_trash' => esc_html__( 'No Property found in Trash.','realty' )
	  );

	  $args = array(
		  'labels'             => $labels,
		  'public'             => true,
		  'show_ui'            => true,
		  'show_in_admin_bar'  => true,
		  'menu_position'      => 20,
		  'menu_icon'          => 'dashicons-admin-home',
		  'publicly_queryable' => true,
		  'query_var'          => true,
		  'rewrite'            => true,
		  'hierarchical'       => true,
		  'supports'           => array(
		  	'title',
		  	'editor',
		  	'thumbnail',
		  	'author',
		  	'page-attributes',
		  	'comments'
		  ),
		  'rewrite'            => array( 'slug' => $property_slug )
	  );

	  register_post_type( 'property', $args );

	}
}
add_action( 'init', 'tt_register_custom_post_type_property' );


/**
 * Register property taxonomy "property-location"
 *
 */
if ( ! function_exists( 'tt_register_taxonomy_property_location' ) ) {
	function tt_register_taxonomy_property_location() {

		register_taxonomy( 'property-location', 'property', array(
		    'meta_box_cb'       => false,
		    'labels'            => array(
		    	'name'                       => esc_html__( 'Property Location', 'realty' ),
		    	'singular_name'              => esc_html__( 'Property Location', 'realty' ),
		    	'search_items'               => esc_html__( 'Search Property Location', 'realty' ),
		    	'popular_items'              => esc_html__( 'Popular Property Location', 'realty' ),
		    	'all_items'                  => esc_html__( 'All Property Location', 'realty' ),
		    	'edit_item'                  => esc_html__( 'Edit Property Location', 'realty' ),
		    	'update_item'                => esc_html__( 'Update Property Location', 'realty' ),
		    	'add_new_item'               => esc_html__( 'Add New Property Location', 'realty' ),
		    	'new_item_name'              => esc_html__( 'New Property Location Name', 'realty' ),
		    	'separate_items_with_commas' => esc_html__( 'Separate Property Location With Commas', 'realty' ),
		    	'add_or_remove_items'        => esc_html__( 'Add or Remove Property Location', 'realty' ),
		    	'choose_from_most_used'      => esc_html__( 'Choose From Most Used Property Location', 'realty' ),
		    	'parent'                     => esc_html__( 'Parent Property Location', 'realty' )
		    	),
		    'hierarchical'      => true,
		    'query_var'         => true,
		    'rewrite'           => array( 'slug' => esc_html__( 'property-location', 'realty' ) ),
		    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
				'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-Locations table
			)
		);

	}
}
add_action( 'init', 'tt_register_taxonomy_property_location', 0 );

/**
 * Register property taxonomy "property-status"
 *
 */
if ( ! function_exists( 'tt_register_taxonomy_property_status' ) ) {
	function tt_register_taxonomy_property_status() {

		register_taxonomy( 'property-status', 'property', array(
		    'meta_box_cb'       => false,
		    'labels'            => array(
		    	'name'                       => esc_html__( 'Property Status', 'realty' ),
		    	'singular_name'              => esc_html__( 'Property Status', 'realty' ),
		    	'search_items'               => esc_html__( 'Search Property Status', 'realty' ),
		    	'popular_items'              => esc_html__( 'Popular Property Status', 'realty' ),
		    	'all_items'                  => esc_html__( 'All Property Status', 'realty' ),
		    	'edit_item'                  => esc_html__( 'Edit Property Status', 'realty' ),
		    	'update_item'                => esc_html__( 'Update Property Status', 'realty' ),
		    	'add_new_item'               => esc_html__( 'Add New Property Status', 'realty' ),
		    	'new_item_name'              => esc_html__( 'New Property Status Name', 'realty' ),
		    	'separate_items_with_commas' => esc_html__( 'Separate Property Status With Commas', 'realty' ),
		    	'add_or_remove_items'        => esc_html__( 'Add or Remove Property Status', 'realty' ),
		    	'choose_from_most_used'      => esc_html__( 'Choose From Most Used Property Status', 'realty' ),
		    	'parent'                     => esc_html__( 'Parent Property Status', 'realty' )
		    	),
		    'hierarchical'      => true,
		    'query_var'         => true,
		    'rewrite'           => array( 'slug' => esc_html__( 'property-status', 'realty' ) ),
		    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
				'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-statuss table
			)
		);

	}
}
add_action( 'init', 'tt_register_taxonomy_property_status', 0 );

/**
 * Register property taxonomy "property-type"
 *
 */
if ( ! function_exists( 'tt_register_taxonomy_property_type' ) ) {
	function tt_register_taxonomy_property_type() {

		register_taxonomy( 'property-type', 'property', array(
		    'meta_box_cb'       => false,
		    'labels'            => array(
		    	'name'                       => esc_html__( 'Property Type', 'realty' ),
		    	'singular_name'              => esc_html__( 'Property Type', 'realty' ),
		    	'search_items'               => esc_html__( 'Search Property Type', 'realty' ),
		    	'popular_items'              => esc_html__( 'Popular Property Type', 'realty' ),
		    	'all_items'                  => esc_html__( 'All Property Type', 'realty' ),
		    	'edit_item'                  => esc_html__( 'Edit Property Type', 'realty' ),
		    	'update_item'                => esc_html__( 'Update Property Type', 'realty' ),
		    	'add_new_item'               => esc_html__( 'Add New Property Type', 'realty' ),
		    	'new_item_name'              => esc_html__( 'New Property Type Name', 'realty' ),
		    	'separate_items_with_commas' => esc_html__( 'Separate Property Type With Commas', 'realty' ),
		    	'add_or_remove_items'        => esc_html__( 'Add or Remove Property Type', 'realty' ),
		    	'choose_from_most_used'      => esc_html__( 'Choose From Most Used Property Type', 'realty' ),
		    	'parent'                     => esc_html__( 'Parent Property Type', 'realty' )
		    	),
		    'hierarchical'      => true,
		    'query_var'         => true,
		    'rewrite'           => array( 'slug' => esc_html__( 'property-type', 'realty' ) ),
		    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
				'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-types table
			)
		);

	}
}
add_action( 'init', 'tt_register_taxonomy_property_type', 0 );

/**
 * Register property taxonomy "property-features"
 *
 */
if ( ! function_exists( 'tt_register_taxonomy_property_features' ) ) {
	function tt_register_taxonomy_property_features() {

		register_taxonomy( 'property-features', 'property', array(
		    'meta_box_cb'       => false,
		    'labels'            => array(
		    	'name'                       => esc_html__( 'Property Features', 'realty' ),
		    	'singular_name'              => esc_html__( 'Property Features', 'realty' ),
		    	'search_items'               => esc_html__( 'Search Property Features', 'realty' ),
		    	'popular_items'              => esc_html__( 'Popular Property Features', 'realty' ),
		    	'all_items'                  => esc_html__( 'All Property Features', 'realty' ),
		    	'edit_item'                  => esc_html__( 'Edit Property Features', 'realty' ),
		    	'update_item'                => esc_html__( 'Update Property Features', 'realty' ),
		    	'add_new_item'               => esc_html__( 'Add New Property Features', 'realty' ),
		    	'new_item_name'              => esc_html__( 'New Property Features Name', 'realty' ),
		    	'separate_items_with_commas' => esc_html__( 'Separate Property Features With Commas', 'realty' ),
		    	'add_or_remove_items'        => esc_html__( 'Add or Remove Property Features', 'realty' ),
		    	'choose_from_most_used'      => esc_html__( 'Choose From Most Used Property Features', 'realty' ),
		    	'parent'                     => esc_html__( 'Parent Property Features', 'realty' )
		    	),
		    'hierarchical'      => true,
		    'query_var'         => true,
		    'rewrite'           => array( 'slug' => esc_html__( 'property-features', 'realty' ) ),
		    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
				'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-featuress table
			)
		);

	}
}
add_action( 'init', 'tt_register_taxonomy_property_features', 0 );

/**
 * Custom Property Columns
 *
 */
if ( ! function_exists( 'tt_property_columns' ) ) {
	function tt_property_columns( $property_columns ) {

	  $property_columns = array(
	    'cb' 				=> '<input type=\'checkbox\' />',
	    'thumbnail'	=> esc_html__( 'Thumbnail', 'realty' ),
	    'title' 		=> esc_html__( 'Property Name', 'realty' ),
	    'featured' 	=> esc_html__( 'Featured', 'realty' ),
	    //'address' => esc_html__( 'Address', 'realty' ),
	    'location' 	=> esc_html__( 'Location', 'realty' ),
	    'status' 		=> esc_html__( 'Status', 'realty' ),
	    'type' 			=> esc_html__( 'Type', 'realty' ),
	    //'features'=> esc_html__( 'Features', 'realty' ),
	    'price' 		=> esc_html__( 'Price', 'realty' ),
		  'views' 		=> esc_html__( 'Total Views', 'realty' ),
	    'author' 	  => esc_html__( 'Owner', 'realty' ),
	    'date' 		  => esc_html__( 'Published', 'realty' )
	  );

	  return $property_columns;

	}
}
add_filter( 'manage_edit-property_columns', 'tt_property_columns' );

if ( ! function_exists( 'tt_property_custom_columns' ) ) {
	function tt_property_custom_columns( $property_column ) {

	  global $post;

	  switch ( $property_column ) {
	    case 'thumbnail' :
	      if( has_post_thumbnail( $post->ID ) ) {
	      	the_post_thumbnail( 'thumbnail' );
	      } else {
	      	esc_html_e( '-', 'realty' );
	      }
	     break;
	    case 'featured' :
	      if( get_post_meta( $post->ID, 'estate_property_featured', true ) ) {
	      	esc_html_e( 'Yes', 'realty' );
	      } else {
	      	esc_html_e( 'No', 'realty' );
	      }
	    break;
	    case 'location' :
	      echo get_the_term_list( $post->ID, 'property-location', '', ', ', '' );
	    break;
	    case 'status' :
	      echo get_the_term_list( $post->ID, 'property-status', '', ', ', '' );
	    break;
	    case 'type' :
	      echo get_the_term_list( $post->ID, 'property-type', '', ', ', '' );
	    break;
			case 'price' :
		  	$properts_price = tt_property_price();
	      if ( empty( $properts_price ) ) {
		      esc_html_e( '-', 'realty' );
	      } else {
		      echo $properts_price;
	      }
	    break;
			case 'views' :
				$count_key = 'estate_property_views_count';
	      $count = get_post_meta( $post->ID, $count_key, true );
	      if ( empty( $count ) ) {
		      esc_html_e( '0', 'realty' );
	      } else {
		      echo $count;
	      }
	    break;
	  }

	}
}
add_action( 'manage_property_posts_custom_column', 'tt_property_custom_columns' );

if ( ! function_exists( 'tt_estate_property_columns_register_sortable' ) ) {
	function tt_estate_property_columns_register_sortable( $columns ) {

	    $columns['views'] = 'views';
			$columns['price'] = 'price';
			$columns['featured'] = 'featured';
			$columns['author'] = 'author';
			$columns['location'] = 'location';
			$columns['status'] = 'status';
			$columns['type'] = 'type';


	    return $columns;

	}
}
add_filter( 'manage_edit-property_sortable_columns', 'tt_estate_property_columns_register_sortable' );

/**
 * Only run our customization on the 'edit.php' page in the admin
 *
 */
if ( ! function_exists( 'my_edit_movie_load' ) ) {
	function my_edit_movie_load() {
		add_filter( 'request', 'tt_sort_property_columns' );
	}
}
add_action( 'load-edit.php', 'my_edit_movie_load' );

/**
 * Sort property columns
 *
 */
if ( ! function_exists( 'tt_sort_property_columns' ) ) {
	function tt_sort_property_columns( $vars ) {

		/* Check if we're viewing the 'property' post type. */
		if ( isset( $vars['post_type'] ) && 'property' == $vars['post_type'] ) {

			/* Check if 'orderby' is set to 'duration'. */
			if ( isset( $vars['orderby'] ) && 'featured' == $vars['orderby'] ) {

				/* Merge the query vars with our custom variables. */
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'estate_property_featured',
						'orderby' => 'meta_value'
					)
				);
			}

			if ( isset( $vars['orderby'] ) && 'price' == $vars['orderby'] ) {

				/* Merge the query vars with our custom variables. */
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'estate_property_price',
						'orderby' => 'meta_value_num'
					)
				);
			}

			if ( isset( $vars['orderby'] ) && 'views' == $vars['orderby'] ) {

				/* Merge the query vars with our custom variables. */
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'estate_property_views_count',
						'orderby' => 'meta_value_num'
					)
				);
			}

		}

		return $vars;

	}
}

/**
 * Property taxonomy sorting
 * XXX - DO NOT INDENT THIS SQL FUNCTION ! ! !
 *
 */
if ( ! function_exists( 'tt_taxonomy_column_sort' ) ) {
function tt_taxonomy_column_sort( $clauses, $wp_query ) {

	global $wpdb;

	if ( isset( $wp_query->query['orderby'] ) && 'location' == $wp_query->query['orderby'] ) {
		$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
		$clauses['where'] .= "AND (taxonomy = 'property-location' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";

		if ( strtoupper( $wp_query->get( 'order' ) ) == 'ASC' ) {
				$clauses['orderby'] .= 'ASC';
		} else {
				$clauses['orderby'] .= 'DESC';
		}

	}

	if ( isset( $wp_query->query['orderby'] ) && 'type' == $wp_query->query['orderby'] ) {
		$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
		$clauses['where'] .= "AND (taxonomy = 'property-type' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";

		if ( strtoupper( $wp_query->get( 'order' ) ) == 'ASC' ) {
				$clauses['orderby'] .= 'ASC';
		} else {
				$clauses['orderby'] .= 'DESC';
		}

	}

	if ( isset( $wp_query->query['orderby'] ) && 'status' == $wp_query->query['orderby'] ) {
		$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
		$clauses['where'] .= "AND (taxonomy = 'property-status' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";

		if ( strtoupper( $wp_query->get( 'order' ) ) == 'ASC' ) {
				$clauses['orderby'] .= 'ASC';
		} else {
				$clauses['orderby'] .= 'DESC';
		}

	}

	return $clauses;

}
}
add_filter( 'posts_clauses', 'tt_taxonomy_column_sort', 10, 2 );

/**
 * Register Membership Package post type
 *
 */
if ( ! function_exists('tt_register_membership_post_type') ) {
	function tt_register_membership_post_type() {

		global $realty_theme_option;

		if ( isset( $realty_theme_option['package-slug'] ) && ! empty( $realty_theme_option['package-slug'] ) ) {
			$package_slug = $realty_theme_option['package-slug'];
		} else {
			$package_slug = esc_html__( 'package', 'realty' );
		}

		$labels = array(
			'name'                  => esc_html__( 'Memberships', 'realty' ),
			'singular_name'         => esc_html__(  'Memberships', 'realty' ),
			'add_new'               => esc_html__( 'Add New Package', 'realty' ),
			'add_new_item'          =>  esc_html__( 'Add Packages', 'realty' ),
			'edit'                  =>  esc_html__( 'Edit Packages' , 'realty' ),
			'edit_item'             =>  esc_html__( 'Edit Package', 'realty' ),
			'new_item'              =>  esc_html__( 'New Packages', 'realty' ),
			'view'                  =>  esc_html__( 'View Package', 'realty' ),
			'view_item'             =>  esc_html__( 'View Package', 'realty' ),
			'search_items'          =>  esc_html__( 'Search Membership Packages', 'realty' ),
			'not_found'             =>  esc_html__( 'No Membership Packages found', 'realty' ),
			'not_found_in_trash'    =>  esc_html__( 'No Membership Packages found', 'realty' ),
			'parent'                =>  esc_html__( 'Parent Membership Package', 'realty' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'publicly_queryable' => true,
			'query_var' 		     => true,
			'show_ui' 			     => true,
			'rewrite'            => array( 'slug' => $package_slug ),
			'supports'           => array( 'title', 'editor' ),
			'can_export'         => true,
			'menu_position'      => 21,
			'menu_icon'          => 'dashicons-calendar',
		);

		register_post_type( 'package', $args );

	}
}
add_action( 'init', 'tt_register_membership_post_type' );

/**
 * Register Invocies post type
 *
 */
if ( ! function_exists( 'tt_register_invoice_post_type' ) ) {
	function tt_register_invoice_post_type() {

		global $realty_theme_option;

		if ( isset( $realty_theme_option['invoice-slug'] ) && ! empty( $realty_theme_option['invoice-slug'] ) ) {
			$invoice_slug = $realty_theme_option['invoice-slug'];
		} else {
			$invoice_slug = esc_html__( 'invoice', 'realty' );
		}

		$labels = array(
		'name'                  => esc_html__(  'Invoices', 'realty' ),
		'singular_name'         => esc_html__(  'Invoices', 'realty' ),
		'add_new'               => esc_html__( 'Add New Invoice', 'realty' ),
		'add_new_item'          =>  esc_html__( 'Add Invoices', 'realty' ),
		'edit'                  =>  esc_html__( 'Edit Invoices' , 'realty' ),
		'edit_item'             =>  esc_html__( 'Edit Invoice', 'realty' ),
		'new_item'              =>  esc_html__( 'New Invoices', 'realty' ),
		'view'                  =>  esc_html__( 'View Invoices', 'realty' ),
		'view_item'             =>  esc_html__( 'View Invoices', 'realty' ),
		'search_items'          =>  esc_html__( 'Search Invoices', 'realty' ),
		'not_found'             =>  esc_html__( 'No Invoices found', 'realty' ),
		'not_found_in_trash'    =>  esc_html__( 'No Invoices found', 'realty' ),
		'parent'                =>  esc_html__( 'Parent Invoice', 'realty' )
		);

		$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'publicly_queryable' => true,
		'query_var' 		     => true,
		'show_ui' 			     => true,
		'rewrite'            => array( 'slug' => $invoice_slug ),
		'supports'           => array('title'),
		'can_export'         => true,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-media-text',
		);

		register_post_type( 'invoice', $args );

	}
}
add_action( 'init', 'tt_register_invoice_post_type' );

/**
 * Custom Membership Columns
 *
 */
if ( ! function_exists( 'tt_package_columns' ) ) {
	function tt_package_columns( $package_columns ) {

		$package_columns = array(
		'cb'               => '<input type=\'checkbox\' />',
		'title'            => esc_html__( 'Package Title', 'realty' ),
		'active'           => esc_html__( 'Active', 'realty' ),
		'validperiod'      => esc_html__( 'Package Period', 'realty' ),
		'listings'         => esc_html__( 'Regular Listings', 'realty' ),
		'featuredlistings' => esc_html__( 'Featured Listings', 'realty' ),
		'packageprice'     => esc_html__( 'Price', 'realty' ),
		'packageID'        => esc_html__( 'Package ID', 'realty' ),
		);

	  return $package_columns;

	}
}
add_filter( 'manage_edit-package_columns', 'tt_package_columns' );

if ( ! function_exists( 'tt_package_custom_columns' ) ) {
	function tt_package_custom_columns( $package_column ) {

	  global $post;

	  switch ( $package_column ) {

	    case 'active' :
			  if ( get_post_meta( $post->ID, 'estate_if_package_active', true ) ) {
			  	esc_html_e( 'Yes', 'realty' );
			  } else {
					esc_html_e( 'No', 'realty' );
			  }
	    break;

	    case 'validperiod' :
	      $period = get_post_meta( $post->ID, 'estate_package_valid_renew', true );
				$period_unit = get_post_meta( $post->ID, 'estate_package_period_unit', true );

				if ( ! empty( $period_unit  ) && ! empty( $period ) ) {
					echo $period. ' '.$period_unit;
		  	}
	    break;

	    case 'listings' :
	      $listings = get_post_meta( $post->ID, 'estate_package_allowed_listings', true );

				if ( ! empty( $listings ) ) {
					echo $listings;
				}
		  break;

	    case 'featuredlistings' :
	      $featured = get_post_meta( $post->ID, 'estate_package_allowed_featured_listings', true );

				if ( ! empty( $featured ) ) {
					echo $featured;
				}
		  break;

			case 'packageprice' :
	      $price = get_post_meta( $post->ID, 'estate_package_price', true );
				if ( ! empty( $price ) ) {
					echo $price;
				}
		  break;

			case 'packageID' :
	      $package_id = get_post_meta( $post->ID, 'estate_package_stripe_id', true );
				if ( ! empty( $package_id ) ) {
					echo $package_id;
		  	}
		  break;

	  } // switch

	}
}
add_action('manage_package_posts_custom_column', 'tt_package_custom_columns');


/**
 * Custom Invoice Columns
 *
 */
if ( ! function_exists( 'tt_invoice_columns' ) ) {
	function tt_invoice_columns( $invoice_columns ) {

		$invoice_columns = array(
			'cb'            => '<input type=\'checkbox\' />',
			'title'         => esc_html__( 'Invoice Title', 'realty' ),
			'invoiceID'     => esc_html__( 'Invoice ID', 'realty' ),
			'userID'        => esc_html__( 'User ID', 'realty' ),
			'paymentmethod' => esc_html__( 'Payment Method', 'realty' ),
			'amountpaid'    => esc_html__( 'Amount Paid', 'realty' ),
			'date'          => esc_html__( 'Invoice Date', 'realty' ),
		);

	  return $invoice_columns;

	}
}
add_filter('manage_edit-invoice_columns', 'tt_invoice_columns');

if ( ! function_exists( 'tt_invoice_custom_columns' ) ) {
	function tt_invoice_custom_columns( $invoice_column ) {

	  global $post;

	  switch ( $invoice_column ) {

	    case 'invoiceID' :
				$invoice_id = get_post_meta( $post->ID, 'estate_invoice_id', true );
	      if ( $invoice_id ) {
      		echo $invoice_id;
      	}
	    break;

	    case 'userID' :
	      $user_id = get_post_meta( $post->ID, 'estate_invoiced_user_id', true );
				if ( ! empty( $user_id ) ) {
					echo $user_id;
		  	}
	    break;

	    case 'paymentmethod' :
	      $payment_method = get_post_meta( $post->ID, 'estate_invoice_payment_method', true );
				if ( ! empty( $payment_method ) ) {
					echo $payment_method;
				}
		  break;

	    case 'amountpaid' :
	      $amount_paid = get_post_meta( $post->ID, 'estate_invoice_amount_paid', true );
				if ( ! empty( $amount_paid ) ) {
					echo $amount_paid;
				}
			break;

	  } // switch

	}
}
add_action('manage_invoice_posts_custom_column', 'tt_invoice_custom_columns');
<?php
if ( !function_exists('estate_register_meta_boxes') ) {
	function estate_register_meta_boxes( $meta_boxes ) {

		$prefix = 'estate_';

		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
			$page_template = get_post_meta( $post_id, '_wp_page_template', true );
		} else {
			$page_template = null;
		}

		/**
		 * Page Template - "Single Property Home"
		 *
		 */

	  if ( $page_template == 'template-property-home.php' ) {

		  $property_query = get_posts( 'post_type=property&posts_per_page=-1&fields=ids' );
			if ( $property_query ) {
				$properties_array = array();
				foreach ( $property_query as $id ) {
					$properties_array[$id] = get_the_title( $id );
				}
			}

	    $meta_boxes[] = array(
				'id' 						=> 'page_template_single_property_home',
				'title' 				=> esc_html__( 'Single Property', 'realty' ),
				'pages' 				=> array( 'page' ),
				'context' 			=> 'normal',
				'priority' 			=> 'high',
				'autosave' 			=> true,
				'fields' 				=> array(
					array(
						'name' 					=> esc_html__( 'Select property you want to show on this page.', 'realty' ),
						'id'   					=> "{$prefix}single_property_id",
						'type' 					=> 'select',
						'options'       => $properties_array,
					),

				)
			);
	  }

		/**
		 * Page Settings
		 * @since 3.0 and VC integration you can set sidebar for every page individually (recommend to use „Page Sidebar“).
		 */
		$meta_boxes[] = array(
			'id' 						=> 'pages_settings',
			'title' 				=> esc_html__( 'Page Settings', 'realty' ),
			'pages' 				=> array( 'post', 'page', 'property', 'agent' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> esc_html__( 'Hide Site Header', 'realty' ),
					'id'   					=> "{$prefix}page_hide_site_header",
					'type' 					=> 'checkbox',
					'std'  					=> 0,
				),
				/*
				array(
					'name' 					=> esc_html__( 'Hide Sidebar', 'realty' ),
					'id'   					=> "{$prefix}page_hide_sidebar",
					'type' 					=> 'checkbox',
					'std'  					=> 0,
				),
				*/
				array(
					'name' 					=> esc_html__( 'Hide Site Footer', 'realty' ),
					'id'   					=> "{$prefix}page_hide_footer_widgets",
					'type' 					=> 'checkbox',
					'std'  					=> 0,
				),
			)
		);

		/**
		 * Post Type: Invoice
		 *
		 */
		$meta_boxes[] = array(
			'id' 						=> 'tt_user_invoice',
			'title' 				=> esc_html__( 'Invoice Data', 'realty' ),
			'pages' 				=> array( 'invoice' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
			    array(
					'name' 	=> esc_html__( 'Invoice Paid?', 'realty' ),
					'id'    => "{$prefix}if_invoice_paid",
					'type' 	=> 'checkbox',
					'std'  	=> 0,
				),
				array(
					'name'	=> esc_html__( 'Invoice ID', 'realty' ),
					'id'	  => "{$prefix}invoice_id",
					'desc'	=> '',
					'type' 	=> 'text',
					'std' 	=> ''
				),
				array(
					'name'	=> esc_html__( 'User ID', 'realty' ),
					'id'	  => "{$prefix}invoiced_user_id",
					'desc'	=> '',
					'type' 	=> 'text',
					'std' 	=> ''
				),
				array(
					'name'	=> esc_html__( 'Invoice For', 'realty' ),
					'id'	  => "{$prefix}invoice_item_title",
					'desc'	=> '',
					'type' 	=> 'text',
					'std' 	=> ''
				),
				array(
					'name'	=> esc_html__( 'Property Listing Or Package ID', 'realty' ),
					'id'	  => "{$prefix}invoice_item_id",
					'desc'	=> '',
					'type' 	=> 'text',
					'std' 	=> ''
				),
				array(
					'name'	=> esc_html__( 'Payment Method', 'realty' ),
					'id'	  => "{$prefix}invoice_payment_method",
					'desc'	=> '',
					'type' 	=> 'text',
					'std' 	=> ''
				),
				array(
					'name'	=>  esc_html__( 'Amount Paid', 'realty' ),
					'id'	  => "{$prefix}invoice_amount_paid",
					'desc'	=> 'Price Unit Will be same as set in Theme Options',
					'type' 	=> 'number',
					'std' 	=> '',
					'step'  => 0.01
				),
				array(
					'name'	=>  esc_html__( 'Listing Or Package Price', 'realty' ),
					'id'	  => "{$prefix}invoice_item_price",
					'desc'	=> 'Price Unit Will be same as set in Theme Options',
					'type' 	=> 'number',
					'std' 	=> '',
					'step'  => 0.01
				),
				array(
					'name' => esc_html__( 'Invoice Date', 'realty' ),
					'id'   => "{$prefix}date_invoice_created",
					'type' => 'date',
					// jQuery date picker options. See here http://api.jqueryui.com/datepicker
					'js_options' => array(
						'prependText'     => '',
						'dateFormat'      => 'yy/mm/dd',
						'changeMonth'     => true,
						'changeYear'      => true,
						'showButtonPanel' => false,
					),
				),
			)
		);

		/**
		 * Post Type: Package
		 *
		 */
		$meta_boxes[] = array(
			'id' 						=> 'tt_user_membership',
			'title' 				=> esc_html__( 'Package Details', 'realty' ),
			'pages' 				=> array( 'package' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
			    array(
					'name' 	=> esc_html__( 'Activate', 'realty' ),
					'id'    => "{$prefix}if_package_active",
					'type' 	=> 'checkbox',
					'std'  	=> 0,
				),
				array(
					'name'     => esc_html__( 'Package Subscription Period', 'realty' ),
					'id'	     => "{$prefix}package_valid_renew",
					'desc'	   => esc_html__( 'Subscription valid for __ days, weeks, months or years.', 'realty' ),
					'type'     => 'number',
					'min'      => 0,
					'std' 	   => ''
				),
				array(
					'name'     => esc_html__( 'Package Subscription Unit', 'realty' ),
					'id'       => "{$prefix}package_period_unit",
					'type'     => 'select',
					'options'  => array(
						'days'     => esc_html__( 'Days', 'realty' ),
						'weeks'    => esc_html__( 'Weeks', 'realty' ),
						'months'   => esc_html__( 'Months', 'realty' ),
						'years'    => esc_html__( 'Years', 'realty' )
					),
				),
				array(
					'name'	   => esc_html__( 'Allowed Regular Listings', 'realty' ),
					'id'	     => "{$prefix}package_allowed_listings",
					'desc'	   => esc_html__( 'Enter -1 for unlimited listings.', 'realty' ),
					'min'      => 0,
					'type' 	   => 'number',
					'std' 	   => '',
					'min'			 => -1,
				),
				array(
					'name'	   => esc_html__( 'Allowed Featured Listings', 'realty' ),
					'id'	     => "{$prefix}package_allowed_featured_listings",
					'desc'	   => esc_html__( 'Enter -1 for unlimited featured listings.', 'realty' ),
					'type' 	   => 'number',
					'std' 	   => '',
					'min'      => -1,
				),
				array(
					'name'	   => esc_html__( 'Package Price', 'realty' ),
					'id'	     => "{$prefix}package_price",
					'desc'	   => esc_html__( 'Enter "0" for free or no price.', 'realty' ),
					'type' 	   => 'number',
					'std' 	   => '',
					'min'      => 0,
					//'step'     => 0.01 // xxx-disabled-due-to-issue-in-plugin, see: https://github.com/rilwis/meta-box/issues/865
				),
				array(
					'name'	   => esc_html__( 'Unique Stripe Package ID', 'realty' ),
					'id'	     => "{$prefix}package_stripe_id",
					'desc'	   => '',
					'type' 	   => 'text',
					'std' 	   => ''
				),

			)
		);

		/**
		 * xxx-obsolete-since-3-0
		 *
		 */
		if ( post_type_exists( 'property' ) && $page_template == 'template-home-properties-map.php' ) {

			// Page Template "Property - Map"
			$meta_boxes[] = array(
				'id' 						=> 'property_map_settings',
				'title' 				=> esc_html__( 'Property Map Settings', 'realty' ),
				'pages' 				=> array( 'page' ),
				'context' 			=> 'normal',
				'priority' 			=> 'high',
				'autosave' 			=> true,
				'fields' 				=> array(
					array(
						'name' 					=> esc_html__( 'Property Location', 'realty' ),
						'id'   					=> "{$prefix}property_map_location",
						'type'    			=> 'taxonomy_advanced',
						'options' 			=> array(
							'taxonomy' 				=> 'property-location', // Taxonomy name
							'type' 						=> 'select_advanced', // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree', select_advanced or 'select'. Optional
							'args' 						=> array() // Additional arguments for get_terms() function. Optional
						),
					),
					array(
						'name' 					=> esc_html__( 'Property Status', 'realty' ),
						'id'   					=> "{$prefix}property_map_status",
						'type'    			=> 'taxonomy_advanced',
						'options' 			=> array(
							'taxonomy' 				=> 'property-status',
							'type' 						=> 'checkbox_list',
							'args' 						=> array()
						),
					),
					array(
						'name' 					=> esc_html__( 'Property Type', 'realty' ),
						'id'   					=> "{$prefix}property_map_type",
						'type'    			=> 'taxonomy_advanced',
						'options' 			=> array(
							'taxonomy' 				=> 'property-type',
							'type' 						=> 'checkbox_list',
							'args' 						=> array()
						),
					),
					array(
						'name' 					=> esc_html__( 'Custom Zoom Level', 'realty' ),
						'id'   					=> "{$prefix}property_map_custom_zoom_level",
						'desc'  				=> esc_html__( 'Enter only, if your properties are located very closeby, and you would like to zoom closer. Zoom targets oldest property.', 'realty' ),
						'type' 					=> 'number',
						'step'  				=> 1,
						'min'						=> 0
					),
				)
			);

		}

		return $meta_boxes;
	}
}
add_filter( 'rwmb_meta_boxes', 'estate_register_meta_boxes' );
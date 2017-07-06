<?php
/**
 * Empty array check
 *
 */
if ( ! function_exists( 'tt_is_array_empty' ) ) {
	function tt_is_array_empty( $arr_acf ) {
		if ( is_array( $arr_acf ) ) {
			foreach ( $arr_acf as $elm )
			if ( ! empty( $elm ) ) {
				return false;
			}
		}
		return true;
	}
}

/**
 * ACF Plugin active?
 *
 */
if ( ! function_exists( 'tt_acf_active' ) ) {
	function tt_acf_active() {

		if ( class_exists( 'acf' ) ) {
			return true;
		}

	}
}
add_action( 'plugins_loaded', 'tt_acf_active' );

/**
 * Get ACF field content by fiel name
 * (currently not in use, but useful to retrieve content of multi-value ACF fields such as radio, checkbox, select)
 *
 */
if ( ! function_exists( 'tt_acf_active' ) ) {
	function tt_acf_select( $acf_field_name = null ) {

		$acf_post_content_array = null;

		$acf_query = new WP_Query( array(
	    'post_type'      => 'acf-field',
	    'posts_per_page' => -1
		) );

		if ( $acf_query->have_posts() ) :

	    foreach ( $acf_query->posts as $acf_field ) :

				// ACF field name = post_excerpt
	    	$acf_field_name_db = $acf_field->post_excerpt;

	    	if ( $acf_field_name == $acf_field_name_db ) {

					$acf_post_content = $acf_field->post_content;
					$acf_post_content_array = maybe_unserialize( $acf_post_content );

					}

	    endforeach;

		endif;

		return $acf_post_content_array;

	}
}

/**
 * ACF: Group IDs for post type "property"
 *
 */
if ( ! function_exists( 'tt_acf_group_id_property' ) ) {
	function tt_acf_group_id_property() {

	  $group_id = array();
		$the_query = new WP_Query( array(
			'post_type'      => array( 'acf-field','acf-field-group', 'acf' ),
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'location'       => array( array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'property')
			)
		) );

		if ( $the_query->have_posts() ) :

			while ( $the_query->have_posts() ) : $the_query->the_post();
				$group_id[] = get_the_ID();
			endwhile;

			wp_reset_query();

		endif;

		return $group_id;
	}
}

/**
 * ACF: Property Fields Name
 *
 */
if ( ! function_exists( 'tt_acf_fields_name' ) ) {
	function tt_acf_fields_name( array $group_ids, $single = false ) {

		$acf_field_name = array();
		$acf_field_keys = array();

	    if ( $group_ids ) {
				foreach( $group_ids as $group_id ) {

					$acf_field_keys = apply_filters( 'acf/field_group/get_fields', $acf_field_keys, $group_id );

					if ( ! tt_is_array_empty( $acf_field_keys ) ) {

						$menu_order = array();

						foreach ( $acf_field_keys as $field_name => $value ) {
							$field = get_field_object( $field_name, false, array( 'load_value' => false ) );
							$menu_order[$field_name] = $field['menu_order'];
						}

					  array_multisort( $menu_order, SORT_ASC, $acf_field_keys );
						$field_post = get_post( $group_id );
						foreach ( $acf_field_keys as $key => $value ) {
							if ( stristr( $value, 'field_' ) ) {
							  $acf_field = get_field_object( $value, $group_id );
								if ( function_exists('icl_object_id') ) {

									$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
								 	$my_current_lang = apply_filters( 'wpml_current_language', NULL );
									if( stristr( $acf_field['name'], 'additional_' ) && $my_post_language_details['language_code'] == $my_current_lang ) {
										 $acf_field_name[] = $acf_field['name'];
									}

								} else {

									if( stristr( $acf_field['name'], 'additional_' ) ) {
										$acf_field_name[] = $acf_field['name'];
									}

								}
							}
						}

					} else {


						 $field_post = get_post( $group_id );
						 $acf_field = get_field_object( $field_post->post_name, $group_id );
						if ( function_exists('icl_object_id') ) {

	 					 	$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
	 					 	$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	 						if( stristr( $acf_field['name'], 'additional_' ) && isset($my_post_language_details['language_code']) && $my_post_language_details['language_code'] == $my_current_lang ) {
	 							 $acf_field_name[] = $acf_field['name'];
	 						}

						}	else {

							 if ( stristr( $acf_field['name'], 'additional_' ) ) {
							  	$acf_field_name[] = $acf_field['name'];
							 }
						}
					}

				}
			}

		return $acf_field_name;

	}
}

/**
 * ACF: Property Fields Label
 *
 */
if ( ! function_exists( 'tt_acf_fields_label' ) ) {
	function tt_acf_fields_label( array $group_ids,$single = false ) {

		$acf_field_label = array();
		$acf_field_keys = array();

		if ( $group_ids ) {
			foreach ( $group_ids as $group_id ) {

				$acf_field_keys = apply_filters( 'acf/field_group/get_fields', $acf_field_keys, $group_id );

				if ( ! tt_is_array_empty( $acf_field_keys )) {

					$menu_order = array();

					foreach( $acf_field_keys as $field_name => $value ) {
						$field = get_field_object($field_name, false, array('load_value' => false));
						$menu_order[$field_name] = $field['menu_order'];
					}

					array_multisort( $menu_order, SORT_ASC, $acf_field_keys );
					$field_post = get_post( $group_id );
					foreach ( $acf_field_keys as $key => $value ) {
						if ( stristr( $value, 'field_' ) ) {
							$acf_field = get_field_object( $value, $group_id );
							if ( function_exists('icl_object_id') ) {

								$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
			          $my_current_lang = apply_filters( 'wpml_current_language', NULL );
			          if ( stristr( $acf_field['name'], 'additional_' )  && $my_post_language_details['language_code'] == $my_current_lang ) {

			              $acf_field_label[] = $field_name;
			          }

							} else {

									if ( stristr( $acf_field['name'], 'additional_' ) ) {
										$acf_field_label[] = $acf_field['label'];
									}
							}
						}
					}

				} else {

					$field_name = get_the_title( $group_id );
					$field_post = get_post( $group_id );
					$acf_field = get_field_object( $field_post->post_name, $group_id );
					if ( function_exists('icl_object_id') ) {

						$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
	          $my_current_lang = apply_filters( 'wpml_current_language', NULL );
	          if ( stristr( $acf_field['name'], 'additional_' )  && isset($my_post_language_details['language_code']) && $my_post_language_details['language_code'] == $my_current_lang ) {

	              $acf_field_label[] = $field_name;
	          }

					} else {

						if ( stristr( $acf_field['name'], 'additional_' ) ) {
							$acf_field_label[] = $field_name;
						}
					}
				}

			}
		}

		return $acf_field_label;

	}
}

/**
 * ACF: Property Fields Type
 *
 */
if ( ! function_exists( 'tt_acf_fields_type' ) ) {
	function tt_acf_fields_type( array $group_ids, $single = false ) {

		$acf_field_types = array();
		$acf_field_keys = array();

		if ( $group_ids) {
			foreach ( $group_ids as $group_id ) {

				//$acf_field_keys = get_post_custom_keys( $group_id );
				$acf_field_keys = apply_filters('acf/field_group/get_fields', $acf_field_keys, $group_id);

				if ( ! tt_is_array_empty( $acf_field_keys ) ) {

					$menu_order = array();

					foreach( $acf_field_keys as $field_name => $value ) {
						$field = get_field_object( $field_name, false, array( 'load_value' => false ) );
						$menu_order[$field_name] = $field['menu_order'];
					}

				  array_multisort( $menu_order, SORT_ASC, $acf_field_keys );
					$field_post = get_post( $group_id );
					foreach ( $acf_field_keys as $key => $value ) {
						if ( stristr( $value, 'field_' ) ) {
						  $acf_field = get_field_object( $value, $group_id );

							if ( function_exists('icl_object_id') ) {

								$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
			          $my_current_lang = apply_filters( 'wpml_current_language', NULL );
			          if ( stristr( $acf_field['name'], 'additional_' )  && $my_post_language_details['language_code'] == $my_current_lang ) {

			              $acf_field_types[] = $field_name;
			          }

							} else {

									$field_post = get_post( $group_id );
									$acf_field = get_field_object( $field_post->post_name, $group_id );
									if ( function_exists('icl_object_id') ) {

										$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $field_post->post_parent ) ;
					          $my_current_lang = apply_filters( 'wpml_current_language', NULL );

					          if ( stristr( $acf_field['name'], 'additional_' )  && $my_post_language_details['language_code'] == $my_current_lang ) {

					              $acf_field_types[] = $field_name;
					          }

									} else {

										  if( stristr( $acf_field['name'], 'additional_' ) ) {
												$acf_field_types[] = $acf_field['type'];
										  }
									}

						}
						}
					}

				} else {

					$field_post = get_post( $group_id );
					$acf_field = get_field_object( $field_post->post_name, $group_id );

					if( stristr( $acf_field['name'], 'additional_' ) ) {
						$acf_field_types[] = $acf_field['type'];
					}

				}

			}
		}

		return $acf_field_types;

	}
}

/**
 * ACF: Property Fields "Required"
 *
 */
if ( ! function_exists( 'tt_acf_fields_required' ) ) {
	function tt_acf_fields_required( $group_id ) {

		$acf_field_keys = array();
		//$acf_field_keys = get_post_custom_keys( $group_id );
		$acf_field_keys = apply_filters('acf/field_group/get_fields', $acf_field_keys, $group_id);
		$acf_field_label = array();

		if ( ! tt_is_array_empty( $acf_field_keys ) ) {

			$menu_order = array();

			foreach( $acf_field_keys as $field_name => $value ) {
				$field = get_field_object( $field_name, false, array( 'load_value' => false ) );
				$menu_order[$field_name] = $field['menu_order'];
			}

			array_multisort( $menu_order, SORT_ASC, $acf_field_keys );

			foreach ( $acf_field_keys as $key => $value ) {
				if ( stristr( $value, 'field_' ) ) {
			  	$acf_field = get_field_object( $value, $group_id );
					$acf_field_label[] = $acf_field['required'];
				}
			}

		}

		return $acf_field_label;

	}
}

/**
 * ACF CSV import helper function
 *
 */
if ( ! function_exists( 'tt_csv_data_import_acf' ) ) {
	function tt_csv_data_import_acf() {

		global $pagenow;

		if ( class_exists( 'acf' ) ) {

			if ( taxonomy_exists('property-location' ) ) {

				$property_args = array(
					'post_type'      => 'property',
					'posts_per_page' => -1,
					'post_status'    => array(
						'publish',
						'pending',
						'draft',
						'auto-draft',
						'trash'
					)
				);

				$property_query = get_posts( $property_args );

				foreach ( $property_query as $post ) {

					// Property Images || @roy: this already works

					$property_images = get_post_meta( $post->ID, 'raw_property_gallery', true );

					if ( ! empty( $property_images ) ) {
						$property_image_ids = array();
						$property_gallery = explode( '|', $property_images );

						if ( is_array( $property_gallery ) ) {
							$property_image_ids = tt_wp_files_uploader( $property_gallery, $post->ID );
						} else {
							$property_image_ids[] = tt_uploader_single( $file_url );
						}
						update_field( 'estate_property_gallery', $property_image_ids, $post->ID );
						update_post_meta( $post->ID, 'raw_property_gallery', '', $property_images );
					}

					// Google Maps (Property Address & Lat/Lng Coordinates) || @roy: this already works
					$address = get_post_meta( $post->ID, 'raw_property_address', true );
					$lat = get_post_meta( $post->ID, 'raw_property_latitude', true );
					$lng = get_post_meta( $post->ID, 'raw_property_longitude', true );

					if ( ! empty( $address ) ) {
					    $google_maps = array(
							'address' => $address,
							'lat'     => $lat,
							'lng'     => $lng,
						);
						update_post_meta( $post->ID, 'raw_property_address', '', $address );
						update_post_meta( $post->ID, 'raw_property_latitude', '', $lat );
						update_post_meta( $post->ID, 'raw_property_longitude', '', $lng );
						update_field( 'estate_property_google_maps', $google_maps, $post->ID );
				   }

					$attachments = get_post_meta( $post->ID, 'raw_property_attachments', true );

					if ( ! empty( $attachments ) ) {

						$property_attachment_ids = array();
						$final_attachment_ids = array();
						$property_attachment = explode( '|', $attachments );

						if ( is_array( $property_attachment ) ) {
							$property_attachment_ids = tt_wp_files_uploader( $property_attachment, $post->ID );
						} else {
							$property_attachment_ids[] = tt_uploader_single( $property_attachment, $post->ID );
						}

						if ( is_array( $property_attachment_ids ) ) {
							foreach ( $property_attachment_ids as  $attachment ) {
								$final_attachment_ids[] = array( 'estate_property_attachment' => $attachment );
							}
							$attachment_object = get_field_object( 'estate_property_attachments_repeater',$post->ID);
							if ( $attachment_object ) {
							update_field( $attachment_object['key'], $final_attachment_ids , $post->ID );
							} else {
								update_field( 'field_55366c6a55cbc', $final_attachment_ids , $post->ID );
							}
							update_post_meta( $post->ID, 'raw_property_attachments', '', $attachments );
						} else {
							update_field('estate_property_attachments_repeater', $property_attachment_ids[0], $post->ID );
							update_post_meta( $post->ID, 'raw_property_attachments', '', $attachments );
						}

					} // $attachments

				} // foreach $property_query

			} // taxonomy_exists

		} else {
			// add_action( 'admin_notices', 'acf_admin_error_notice_csv' ); xxx-redundant-with-tgmpa plugin notices
		}

	}
}
//add_action( 'admin_init', 'tt_csv_data_import_acf');
add_action( 'after_switch_theme', 'tt_csv_data_import_acf');

if ( ! function_exists( 'acf_admin_error_notice_csv' ) ) {
	function acf_admin_error_notice_csv() {
		$class = 'update-nag';
		$message = 'The following required plugin is currently inactive or not installed: Advanced Custom Fields Pro.';
		echo "<div class=\"$class\"><p>$message</p></div>";
	}
}

/**
 * Files, attachments and image uploader to wordpress directory
 *
 */
if ( ! function_exists( 'tt_wp_files_uploader' ) ) {
	function tt_wp_files_uploader( $multiple_files, $post_id ) {

		$property_image_ids = array();

		if ( is_array( $multiple_files ) ) {
			foreach( $multiple_files as $file_url ) {
				// uploads the single file
				$attach_id = tt_uploader_single( $file_url, $post_id );
				$property_image_ids[] = $attach_id;
				//set_post_thumbnail( $post_id, $attach_id );
			}
			return $property_image_ids;
		} else {
			$property_image_id = tt_uploader_single( $multiple_files, $post_id );
			return  $property_image_id;
		}

	}
}

/**
 * Takes URL, uploads file and return ID of the attachment
 *
 */
if ( ! function_exists( 'tt_uploader_single' ) ) {
	function tt_uploader_single( $file_url , $post_id ) {

		if ( $file_url ) {
				$image_url  = $file_url;
				$upload_dir = wp_upload_dir(); // Set upload folder
				$image_data = file_get_contents( $image_url ); // Get image data
				$filename   = basename( $image_url ); // Create image file name

				// Check folder permission and define file location
				if( wp_mkdir_p( $upload_dir['path'] ) ) {
					$file = $upload_dir['path'] . '/' . $filename;
				} else {
					$file = $upload_dir['basedir'] . '/' . $filename;
				}

				// Create the image  file on the server
				file_put_contents( $file, $image_data );

				// Check image file type
				$wp_filetype = wp_check_filetype( $filename, null );

				// Set attachment data
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_file_name( $filename ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Create the attachment
				$attach_id = wp_insert_attachment( $attachment, $file, 0 );

				// Include image.php
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Define attachment metadata
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file, $post_id );

				// Assign metadata to attachment
				wp_update_attachment_metadata( $attach_id, $attach_data );

				return $attach_id;
		}

	}
}

/* ACF Pro Google Map API Key Set */

function realty_acf_google_map_api( $api ){

	$the_key = '';
	global $realty_theme_option;
	if( !empty ( $realty_theme_option['google-maps-api-key'] )) {

	$the_key = sanitize_text_field ( $realty_theme_option['google-maps-api-key'] );
	$api['key'] = $the_key;

	}

	return $api;

}

add_filter('acf/fields/google_map/api', 'realty_acf_google_map_api');

function realty_acf_init() {

       $the_key = '';
	global $realty_theme_option;
	if( !empty ( $realty_theme_option['google-maps-api-key'] )) {

	$the_key = sanitize_text_field ( $realty_theme_option['google-maps-api-key'] );
	acf_update_setting('google_api_key', $the_key);

	}
}
add_action('acf/init', 'realty_acf_init');

function realty_change_property_submit_labels( $field ) {

	global $realty_theme_option;
	if($field['name'] == '_post_title' && !empty( $realty_theme_option['submit-property-title-label'] ))  {

		$property_title = $realty_theme_option['submit-property-title-label'];
		$field['label'] = $property_title;        //// change this according to your choice

	}
	if($field['name'] == '_post_content' && !empty( $realty_theme_option['submit-property-content-label'] )) {

		$property_content = $realty_theme_option['submit-property-content-label'];
		$field['label'] = $property_content;  //// change this according to your choice

	}
	return $field;

}
add_filter( 'acf/get_valid_field', 'realty_change_property_submit_labels');

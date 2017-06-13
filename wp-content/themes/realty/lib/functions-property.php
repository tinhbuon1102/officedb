<?php
/**
 * Property Price
 *
 */
if ( ! function_exists( 'tt_property_price' ) ) {
	function tt_property_price( $property_id = 0 ) {

		global $post, $realty_theme_option;

		if ( $property_id == 0 ) {
			$property_id = $post->ID;
		}

		$property_price = doubleval( get_post_meta( $property_id, 'estate_property_price', true ) );
		
		$property_price_prefix = get_post_meta( $property_id, 'estate_property_price_prefix', true );
		$property_price_suffix = get_post_meta( $property_id, 'estate_property_price_suffix', true );

		$currency_sign = $realty_theme_option['currency-sign'];
		$currency_sign_position = $realty_theme_option['currency-sign-position'];
		$price_thousands_separator = $realty_theme_option['price-thousands-separator'];
		$price_prefix = $realty_theme_option['price-prefix'];
		$price_suffix = $realty_theme_option['price-suffix'];

		if ( $realty_theme_option['price-decimals'] ) {
			$decimals = $realty_theme_option['price-decimals'];
		} else {
			$decimals = 0;
		}

		$decimal_point = '.';

		// Default Currency Sign "$"
		if ( empty( $currency_sign ) ) {
	  	$currency_sign = esc_html__( '$', 'realty' );
	  }

	  if ( ! empty( $property_price ) ) {

		  if ( $property_price == -1 ) {
				$output = esc_html__( 'Price Upon Request', 'realty' );
		  } else if ( $property_price ) {

				$output = '';

				if ( $property_price_prefix ) {
					$output .= $property_price_prefix . '&nbsp;';
				} else if ( $price_prefix ) {
					$output .= $price_prefix . '&nbsp;';
				}

				$formatted_price = number_format( $property_price, $decimals, $decimal_point, $price_thousands_separator );

				if ( $currency_sign_position == 'right' ) {
					$output .= $formatted_price . $currency_sign;
				} else {
					$output .= $currency_sign . $formatted_price;
				}

				if ( $property_price_suffix ) {
					$output .= $property_price_suffix;
				} else if ( $price_suffix ) {
					$output .= $price_suffix;
				}

			} else {
				$output = false;
			}

		return $output;

		}

	}
}

if ( ! function_exists( 'tt_get_formatted_price' ) ) {
	function tt_get_formatted_price( $price=0) {

		global $realty_theme_option;

		$currency_sign = $realty_theme_option['currency-sign'];
		$currency_sign_position = $realty_theme_option['currency-sign-position'];
		$price_thousands_separator = $realty_theme_option['price-thousands-separator'];
		$price_prefix = $realty_theme_option['price-prefix'];
		$price_suffix = $realty_theme_option['price-suffix'];

		if ( $realty_theme_option['price-decimals'] ) {
			$decimals = $realty_theme_option['price-decimals'];
		} else {
			$decimals = 0;
		}

		$decimal_point = '.';

		// Default Currency Sign "$"
		if ( empty( $currency_sign ) ) {
	  	$currency_sign = esc_html__( '$', 'realty' );
	  }

	  $formatted_price = number_format( $price, $decimals, $decimal_point, $price_thousands_separator );

		return $currency_sign . $formatted_price;

		}

	}


/**
 * Icon - New Property
 *
 */
if ( ! function_exists( 'tt_icon_new_property' ) ) {
	function tt_icon_new_property() {

		// Current Date
		$today = date( 'r' );
		// Property Publishing Date
		$property_published = get_the_time( 'r' );
		// Property Age in Days
		$property_age = round( (strtotime( $today ) - strtotime( $property_published ) ) / ( 24 * 60 * 60 ), 0 );

		// If Property Publishing Date is .. days or less, show New Icon
		global $realty_theme_option;
		$new_days_integer = $realty_theme_option['property-new-badge'];
		if ( $new_days_integer && $property_age <= $new_days_integer ) {
			return '<i class="icon-hot-topic" data-toggle="tooltip" title="' . esc_html__( 'New Offer', 'realty' ) . '"></i>';
		} else {
			return false;
		}

	}
}

/**
 * Icon - Featured Property
 *
 */
if ( ! function_exists( 'tt_icon_property_featured' ) ) {
	function tt_icon_property_featured( $property_id = 0 ) {

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		$property_featured = get_post_meta( get_the_ID(), 'estate_property_featured', true );
		if ( $property_featured ) {
			echo '<i class="icon-star-1" data-toggle="tooltip" title="' . esc_html__( 'Featured Property', 'realty' ) . '"></i>';
		} else {
			return false;
		}

	}
}

/**
 * Icon - Property Address
 *
 */
if ( ! function_exists( 'tt_icon_property_address' ) ) {
	function tt_icon_property_address( $property_id = 0 ) {

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		$google_maps = get_post_meta( get_the_ID(), 'estate_property_google_maps', true );
		if ( isset( $google_maps ) ) {
			$property_address = $google_maps['address'];
		}
		if ( $property_address ) {
			echo '<i class="icon-location-pin-medium-1" data-toggle="tooltip" title="' . $property_address . '"></i>';
		} else {
			return false;
		}

	}
}

/**
 * Icon - Property Video
 *
 */
if ( ! function_exists( 'tt_icon_property_video' ) ) {
	function tt_icon_property_video( $property_id = 0 ) {

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		$property_video_provider = get_post_meta( $property_id, 'estate_property_video_provider', true );
		$property_video_id = get_post_meta( $property_id, 'estate_property_video_id', true );

		if ( $property_video_id && ( $property_video_provider == "youtube" || $property_video_provider == "vimeo" ) ) {
			if ( $property_video_provider == "youtube") {
				$video_url = '//www.youtube.com/watch?v=';
			}
			if ( $property_video_provider == "vimeo" ) {
				$video_url = '//vimeo.com/';
			}
			return '<a href="' . $video_url . $property_video_id . '" class="property-video-popup"><i class="icon-video-camera" data-toggle="tooltip" title="' . esc_html__( 'Watch Trailer', 'realty' ) . '"></i></a>';
		} else {
			return false;
		}

	}
}

/**
 * Icon - Delete Property
 *
 */
if ( ! function_exists( 'tt_ajax_delete_property_function' ) ) {
	function tt_ajax_delete_property_function() {
		wp_trash_post( $_GET['delete_property'] );
		die;
	}
}
add_action( 'wp_ajax_tt_ajax_delete_property_function', 'tt_ajax_delete_property_function' );

/**
 * Icons - Property Attachments
 *
 */
if ( ! function_exists( 'tt_icon_attachment' ) ) {
	function tt_icon_attachment( $type ) {

	switch( $type ) {

		// PDF
		case 'pdf':
		return '<i class="icon-file-pdf-1"></i>';

		// Image
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'bmp':
		case 'tif':
		case 'tiff':
		return '<i class="icon-file-picture"></i>';

		// Audio
		case 'mp3':
		case 'wav':
		case 'm4a':
		case 'aif':
		case 'wma':
		case 'ra':
		case 'mpa':
		case 'iff':
		case 'm3u':
		return '<i class="icon-file-music-1"></i>';

		// Video
		case 'avi':
		case 'flv':
		case 'm4v':
		case 'mov':
		case 'mp4':
		case 'mpg':
		case 'rm':
		case 'swf':
		case 'wmv':
		return '<i class="icon-file-video-2"></i>';

		// Text
		case 'txt':
		case 'log':
		case 'tex':
		return '<i class="icon-file-text"></i>';

		// Doc
		case 'doc':
		case 'docx':
		case 'odt':
		case 'msg':
		case 'rtf':
		case 'wps':
		case 'wpd':
		case 'pages':
		return '<i class="icon-file-words-1"></i>';

		// Spreadsheet
		case 'csv':
		case 'xls':
		case 'xlsx':
		case 'xml':
		case 'xlr':
		return '<i class="icon-file-excel-1"></i>';

		// ZIP
		case 'zip':
		case 'rar':
		case '7z':
		case 'zipx':
		case 'tar.gz':
		case 'gz':
		case 'pkg':
		return '<i class="icon-file-zipped"></i>';

		// Other
		default:
		return '<i class="icon-new-1"></i>';

		}

	}
}

/**
 * Property Sorting & View
 *
 */
if ( ! function_exists( 'tt_property_listing_sorting_and_view' ) ) {
	function tt_property_listing_sorting_and_view( $sort_order, $view, $show_sorting = true ) {

		global $realty_theme_option;
		$default_search_order = $realty_theme_option['search_results_default_order'];
		$default_view = $realty_theme_option['property-listing-default-view'];

		if ( ! empty( $_GET[ 'order-by' ] ) ) {
			$orderby = $_GET[ 'order-by' ];
		} else {
			if ( ! empty( $sort_order ) ) {
			   $orderby = $sort_order;
			} else if ( ! empty( $default_search_order ) ) {
			  $orderby = $default_search_order;
			} else {
				$orderby = 'date-new';
			}
		}

		if ( empty( $view ) ) {
			$view = $default_view;
		}
		?>
		<div class="search-results-header clearfix">

			<?php if ( $show_sorting ) { ?>
				<div class="search-results-order clearfix">
					<div class="form-group select">
						<select name="order-by" id="orderby" class="form-control <?php if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) { echo 'chosen-select chosen-rtl'; } else { echo 'chosen-select'; } ?>">
							<option value="featured" <?php selected( 'featured', $orderby ); ?>><?php esc_html_e( 'Featured First', 'realty' ); ?></option>
							<option value="date-new" <?php selected( 'date-new', $orderby ); ?>><?php esc_html_e( 'Sort by Date (Newest First)', 'realty' ); ?></option>
							<option value="date-old" <?php selected( 'date-old', $orderby ); ?>><?php esc_html_e( 'Sort by Date (Oldest First)', 'realty' ); ?></option>
							<option value="price-high" <?php selected( 'price-high', $orderby ); ?>><?php esc_html_e( 'Sort by Price (Highest First)', 'realty' ); ?></option>
							<option value="price-low" <?php selected( 'price-low', $orderby ); ?>><?php esc_html_e( 'Sort by Price (Lowest First)', 'realty' ); ?></option>
			        <option value="name-asc" <?php selected( 'name-asc', $orderby ); ?>><?php esc_html_e( 'Sort by Name (Ascending)', 'realty' ); ?></option>
			        <option value="name-desc" <?php selected( 'name-desc', $orderby ); ?>><?php esc_html_e( 'Sort by Name (Descending)', 'realty' ); ?></option>
							<option value="random" <?php selected( 'random', $orderby ); ?>><?php esc_html_e( 'Random', 'realty' ); ?></option>
						</select>
					</div>
				</div>
			<?php }?>

			<div class="search-results-view primary-tooltips">
				<i class="icon-synchronize-1 <?php if ( ! $orderby || $orderby != 'random' ) { echo 'hide'; } ?>" data-toggle="tooltip" title="<?php esc_html_e( 'Reload', 'realty' ); ?>"></i>
				<i class="icon-view-grid<?php if ( $view == 'grid-view' ) { echo ' active'; } ?>" data-view="grid-view" data-toggle="tooltip" title="<?php esc_html_e( 'Grid View', 'realty' ); ?>"></i>
				<i class="icon-view-list<?php if ( $view == 'list-view' ) { echo ' active'; } ?>" data-view="list-view" data-toggle="tooltip" title="<?php esc_html_e( 'List View', 'realty' ); ?>"></i>
			</div>

		</div>
		<?php

	}
}

/**
 * Property Submit - Delete Images
 *
 */
if ( ! function_exists( 'tt_ajax_delete_uploaded_image_function' ) ) {
	function tt_ajax_delete_uploaded_image_function() {
		delete_post_meta( $_POST['property_id'], 'estate_property_images', $_POST['image_id'] );
		die;
	}
}
add_action( 'wp_ajax_tt_ajax_delete_uploaded_image_function', 'tt_ajax_delete_uploaded_image_function' );


/**
 * Property Submit Listing - Edit link
 *
 */
if ( ! function_exists( 'tt_permalink_property_submit_edit' ) ) {
	function tt_permalink_property_submit_edit( $url ) {

		global $post;
		if ( is_page_template( 'template-property-submit-listing.php' ) ) {
			// Get page that is using "Property Submit" Page Template
			$template_page_property_submit_array = get_pages( array (
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'template-property-submit.php'
			) );
			foreach ( $template_page_property_submit_array as $template_page_property_submit ) {
				$submit_page = $template_page_property_submit->ID;
				break;
			}
			return get_permalink( $submit_page ) . '?edit=' . $post->ID;
		} else {
			return add_query_arg( $_GET, $url );
		}

	}
}
add_filter( 'the_permalink', 'tt_permalink_property_submit_edit' );

/**
 * Property Vimeo Embed URL
 *
 */
if ( ! function_exists( 'vimeo_oembed_fetch_url' ) ) {
	function vimeo_oembed_fetch_url( $provider, $url, $args ) {
		if ( strpos( $provider, 'vimeo.com' ) !== false )  {
			foreach ( $args as $key => $value ) {
				$provider = add_query_arg( $key, absint( $value ), $provider );
			}
		}
		return $provider;
	}
}
add_filter( 'oembed_fetch_url', 'vimeo_oembed_fetch_url', 10, 3 );

/**
 * Property Payment - Meta Boxes
 *
 */
if ( ! function_exists('tt_meta_box_property_payment') ) {
	function tt_meta_box_property_payment( $post ) {

	  $payment_data = get_post_custom( $post->ID );
	  $empty  = '-';

	  $property_payment_txn_id = isset( $payment_data['property_payment_txn_id'] ) ? $payment_data['property_payment_txn_id'][0] : $empty;
	  $property_payment_date   = isset( $payment_data['property_payment_payment_date'] ) ? $payment_data['property_payment_payment_date'][0] : $empty;
	  $property_payment_payer_email = isset( $payment_data['property_payment_payer_email'] ) ? $payment_data['property_payment_payer_email'][0] : $empty;
	  $property_payment_first_name = isset( $payment_data['property_payment_first_name'] ) ? $payment_data['property_payment_first_name'][0] : $empty;
	  $property_payment_last_name = isset( $payment_data['property_payment_last_name'] ) ? $payment_data['property_payment_last_name'][0] : $empty;
	  $property_payment_status = isset( $payment_data['property_payment_status'] ) ? $payment_data['property_payment_status'][0] : $empty;
	  $property_payment_mc_gross = isset( $payment_data['property_payment_mc_gross'] ) ? $payment_data['property_payment_mc_gross'][0] : $empty;
	  $property_payment_mc_currency = isset( $payment_data['property_payment_mc_currency'] ) ? $payment_data['property_payment_mc_currency'][0] : $empty;

	  $output  = '<p>';
	  $output .= '<span style="color:limegreen; font-weight:700; text-transform:uppercase">' . $property_payment_status . '</span><br />';
	  $output .= $property_payment_mc_currency . ' ' . $property_payment_mc_gross . '<br />';
	  $output .= $property_payment_first_name . ' ' . $property_payment_last_name . '<br />';
	  $output .= $property_payment_payer_email . '<hr>';
	  $output .= '</p>';
	  $output .= $property_payment_txn_id . '<br />';
	  $output .= $property_payment_date . '<br />';

	  if ( $property_payment_status == 'Completed' ) {
	  	echo $output;
	  } else {
		  echo '-';
	  }
	}
}

if ( ! function_exists('tt_add_meta_box_property_payment') ) {
	function tt_add_meta_box_property_payment() {
	  add_meta_box( 'meta-box', esc_html__( 'Property Payment Details', 'realty' ), 'tt_meta_box_property_payment', 'property', 'normal', 'core' );
	}
}
add_action( 'add_meta_boxes', 'tt_add_meta_box_property_payment' );

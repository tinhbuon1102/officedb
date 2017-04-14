<?php
/**
 * User listings counter update on saving the property
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */

if ( ! function_exists( 'tt_update_user_listings_counter' ) ) {
	function tt_update_user_listings_counter( $post_id, $post, $update ) {

		$slug = 'property';

		if ( $update || $slug != $post->post_type ) {
			return;
		}

		global $realty_theme_option;

		if ( is_user_logged_in() && $realty_theme_option['property-submission-type'] == 'membership' ) {

			global $current_user;
			$current_user = wp_get_current_user();
			$user_role = $current_user->roles[0];

			if ( $user_role == 'subscriber' ) {

				$subscribed_package = intval( get_user_meta( $current_user->ID, 'subscribed_package_default_id', true ) );
				$subscribed_regular_listings = get_post_meta( $subscribed_package, 'estate_package_allowed_listings', true );

				$subscribed_featured_listings = get_post_meta( $subscribed_package, 'estate_package_allowed_featured_listings', true );

				$prev_counter = get_user_meta( $current_user->ID, 'subscribed_listing_remaining', true );
				$prev_counter_featured = get_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', true );
				$featured = false;

				if ( isset( $_POST['acf']['field_553623e61c243'] ) ) {
					$featured = $_POST['acf']['field_553623e61c243'];
					if ( $subscribed_featured_listings == -1 ) {
						return;
					}
				}

				if ( $subscribed_regular_listings == -1 && $featured == false ) {
					return;
				}

				if ( $subscribed_package != '' ) {
					if ( $featured == false) {
							if ( $prev_counter > 0) {
								update_user_meta( $current_user->ID, 'subscribed_listing_remaining', $prev_counter-1, $prev_counter );
							} else {
								$property = array(
									'ID'	        => $post_id,
									'post_status' => 'pending',
								);
								wp_update_post( $property );
							}
					} else {
						if ( $prev_counter_featured > 0 ) {
							update_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', $prev_counter_featured - 1, $prev_counter_featured );
						} else {
							update_post_meta($id , 'estate_property_featured' , false );
							$_POST['acf']['field_553623e61c243'] = false;
							if ( $prev_counter > 0 ) {
								update_user_meta( $current_user->ID, 'subscribed_listing_remaining', $prev_counter-1, $prev_counter );
							} else {
								$property = array(
									'ID'	        => $post_id,
									'post_status' => 'pending',
								);
								wp_update_post( $property );
							}
						}
					}
				}

			}

		}

	}
}

add_action( 'save_post', 'tt_update_user_listings_counter', 10 , 3);

if ( ! function_exists( 'tt_update_listing_counter_on_update_property' ) ) {
	function tt_update_listing_counter_on_update_property( $id ) {

		$slug = 'property';
		$post_type = get_post_type( $id );

		if ( $slug != $post_type ) {
			return;
		}

		global $realty_theme_option;
		$featured_check = get_post_meta( $id , 'estate_property_featured', true);

	  if ( $realty_theme_option['property-submission-type'] == 'membership' ) {

				global $current_user;
				$current_user = wp_get_current_user();
				$user_role = $current_user->roles[0];

				if ( $user_role == 'subscriber' ) {

					$subscribed_package = intval( get_user_meta( $current_user->ID, 'subscribed_package_default_id', true ) );
					$subscribed_regular_listings = get_post_meta( $subscribed_package, 'estate_package_allowed_listings', true );

					$subscribed_featured_listings = get_post_meta( $subscribed_package, 'estate_package_allowed_featured_listings', true );

					$prev_counter = get_user_meta( $current_user->ID, 'subscribed_listing_remaining', true );
					$prev_counter_featured = get_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', true );
					$featured = false;

					if ( isset( $_POST['acf']['field_553623e61c243'] ) ) {
						$featured = $_POST['acf']['field_553623e61c243'];
						if ( $subscribed_featured_listings == -1 ) {
							return;
						}
					}

					if ( $subscribed_regular_listings == -1 && $featured == false ) {
						return;
					}

					if ( $subscribed_package != '' ) {
						if ( $featured == false && $featured_check == true) {
							if ( $prev_counter > 0 ) {
								update_user_meta( $current_user->ID, 'subscribed_listing_remaining', $prev_counter - 1, $prev_counter );
								update_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', $prev_counter_featured + 1, $prev_counter_featured );
								update_post_meta($id , 'estate_property_featured' , $featured , $featured_check  );
								$_POST['acf']['field_553623e61c243'] = $featured;
							} else {
								update_post_meta($id , 'estate_property_featured' , $featured_check, $featured_check  );
								$_POST['acf']['field_553623e61c243'] = $featured_check;
							}
						}	else if ( $featured == true && $featured_check == false ) {
							if ( $prev_counter_featured > 0  ) {
								update_user_meta( $current_user->ID, 'subscribed_featured_listing_remaining', $prev_counter_featured - 1, $prev_counter_featured );
								update_user_meta( $current_user->ID, 'subscribed_listing_remaining', $prev_counter + 1, $prev_counter );
								update_post_meta($id , 'estate_property_featured' , $featured, $featured_check  );
								$_POST['acf']['field_553623e61c243'] = $featured;
							} else {
								update_post_meta($id , 'estate_property_featured' , $featured_check, $featured_check  );
								$_POST['acf']['field_553623e61c243'] = $featured_check;
							}
						} else {
							// do nothing
						}
					}

				}

	   }

	}
}
add_action('pre_post_update', 'tt_update_listing_counter_on_update_property', 5 ,1);

/**
 * Get user featured listings which are remaining from package subscirbed
 *
 */
if ( ! function_exists( 'tt_user_remaining_featured_listings' ) ) {
	function tt_user_remaining_featured_listings( $user_id ) {

		$listings = get_user_meta( $user_id, 'subscribed_featured_listing_remaining', true );
		if ( $listings == -1 ) {
			return 'Unlimited';
		} else {
			return $listings;
		}

	}
}

/**
 * Get user listings which are remaining from package subscirbed
 *
 */
if( ! function_exists( 'tt_user_remaining_listings' ) ) {
	function tt_user_remaining_listings( $user_id ) {

		//$listings = get_the_author_meta( 'subscribed_listing_remaining' , $user_id );
		$listings = get_user_meta( $user_id, 'subscribed_listing_remaining', true );

		if ( $listings == -1 ) {
			return 'Unlimited';
		} else {
			return $listings;
		}

	}
}

/**
 * Get all listings for a user
 *
 */
if ( ! function_exists( 'tt_get_user_listings' ) ) {
	function tt_get_user_listings( $user_id ) {

	  $args = array(
			'post_type'   =>  'property',
			'post_status' =>  array( 'pending', 'publish' ),
			'author'      =>  $user_id,
		);
		$posts = new WP_Query( $args );
		return $posts->found_posts;
		wp_reset_query();

	}
}

/**
 * Get user check dates for membership validity
 *
 */
if ( ! function_exists( 'tt_is_user_membership_valid' ) ) {
	function tt_is_user_membership_valid( $user_id ) {

		if ( $user_id ) {

			$package_id = get_user_meta ( $user_id, 'subscribed_package_default_id', true );
			if ( ! empty( $package_id ) ) {

				$activation_date = get_user_meta( $user_id, 'user_package_activation_time', true );
				$biling_unit = get_post_meta( $package_id, 'estate_package_period_unit', true );
				$billing_recurrence = get_post_meta( $package_id, 'estate_package_valid_renew', true );
				$time_now = time();
				$seconds = 0;

				switch( $biling_unit ) {
					case 'days':
						$seconds = 60*60*24;
						break;
					case 'weeks':
						$seconds = 60*60*24*7;
						break;
					case 'months':
						$seconds = 60*60*24*30;
						break;
					case 'years':
						$seconds = 60*60*24*365;
						break;
				}

			   $time_period = $seconds * $billing_recurrence;
				// Tf this time is more than than actvation time + time period of package, then not valid
				if( $time_now > $activation_date + $time_period ) {
					return false;
				} else {
					return true;
				}
			} else {
				return 'invalid';
			}

		} // $user_id

	}
}

/**
 * User invoice after payment is made
 *
 */
if ( ! function_exists( 'tt_create_user_invoice' ) ) {
	function tt_create_user_invoice( $user_id = null, $item_id, $method ) {

		global $current_user, $realty_theme_option;

		// for Stripe (PayPal payments need to pass user_id with form, as transaction is off-site)
		if ( ! isset( $user_id ) || empty( $user_id ) ) {
			//$user_id = get_current_user_id();
		}

		//$current_user = wp_get_current_user();
		//$current_user_role = $current_user->roles[0];
		$user_info = get_userdata( $user_id );
		$current_user_role = $user_info->roles[0];

		$first_name = get_user_meta( $user_id, 'first_name', true );
		$last_name = get_user_meta( $user_id, 'last_name', true );
		if ( $first_name || $last_name ) {
			$user_complete_name = $first_name . ' ' . $last_name . ' | ';
		} else {
			$user_complete_name = null;
		}

		if ( isset ( $_POST['stripeEmail']) && $_POST['stripeEmail'] ) {
			$email_user = $_POST['stripeEmail'];
		} else {
			$email_user = $current_user->user_email;
		}

		if ( $user_id && $item_id && $current_user_role == 'subscriber' ) {

			if ( get_post_type( $item_id ) == 'package' ) {

				$plan_title = get_the_title( $item_id );
				$plan_stripe_id = get_post_meta( $item_id, 'estate_package_stripe_id', true );
				$regular_listings = get_post_meta( $item_id, 'estate_package_allowed_listings', true );
				$featured_listings = get_post_meta( $item_id, 'estate_package_allowed_featured_listings', true );
				$price_package = get_post_meta( $item_id, 'estate_package_price', true );
				$time_now = time();
				$from_date = date_i18n( get_option( 'date_format' ), $time_now );

				$invoice = array(
					'post_status'	=> 'publish',
					'post_type' 	=> 'invoice',
					'post_title'	=> $user_complete_name . $plan_title,
				);
				$invoice_id = wp_insert_post( $invoice );

				update_post_meta( $invoice_id, 'estate_invoice_amount_paid', $price_package );
				update_post_meta( $invoice_id, 'estate_invoice_item_price', $price_package );
				update_post_meta( $invoice_id, 'estate_invoice_id', $invoice_id );
				update_post_meta( $invoice_id, 'estate_date_invoice_created', $from_date );
				update_post_meta( $invoice_id, 'estate_invoice_payment_method', $method );
				update_post_meta( $invoice_id, 'estate_invoice_item_id', $plan_stripe_id );
				update_post_meta( $invoice_id, 'estate_invoice_item_title', $plan_title );
				update_post_meta( $invoice_id, 'estate_invoiced_user_id', $user_id );
				update_post_meta( $invoice_id, 'estate_if_invoice_paid', 1 );

				update_user_meta( $user_id, 'subscribed_package', $plan_stripe_id );
				update_user_meta( $user_id, 'user_package_activation_time', $time_now );
				update_user_meta( $user_id, 'subscribed_listing_remaining', $regular_listings );
				update_user_meta( $user_id, 'subscribed_featured_listing_remaining', $featured_listings );
				update_user_meta( $user_id, 'subscribed_package_default_id', $item_id );

			} else {

				$property_title = get_the_title( $item_id );
				$property_settings_amount = $realty_theme_option['paypal-amount'];
				$property_featured_amount = $realty_theme_option['paypal-featured-amount'];

				$property_extra_featured = get_post_meta( $item_id, 'estate_property_featured', true );
				if ( $property_extra_featured ) {
					$property_settings_amount = $property_settings_amount + $property_featured_amount;
				}
				$price_package = $property_settings_amount;
				$settings_currency_code = $realty_theme_option['paypal-currency-code'];
				$time_now = time();
				$from_date = date_i18n( get_option( 'date_format' ), $time_now );

				$invoice = array(
					'post_status'		=> 'publish',
					'post_type' 		=> 'invoice',
					'post_title'		=> $user_complete_name . ' | ' . $property_title,
				);

				$invoice_id = wp_insert_post( $invoice );
				update_post_meta( $invoice_id, 'estate_invoice_amount_paid', $price_package );
				update_post_meta( $invoice_id, 'estate_invoice_item_price', $price_package );
				update_post_meta( $invoice_id, 'estate_invoice_id', $invoice_id );
				update_post_meta( $invoice_id, 'estate_invoice_item_id', $item_id );
				update_post_meta( $invoice_id, 'estate_date_invoice_created', $from_date );
				update_post_meta( $invoice_id, 'estate_invoice_payment_method', $method );
				update_post_meta( $invoice_id, 'estate_invoice_item_title', $property_title );
				update_post_meta( $invoice_id, 'estate_invoiced_user_id', $user_id );
				update_post_meta( $invoice_id, 'estate_if_invoice_paid', 1 );

				// Update property payment details
				tt_update_property_payment_data( $invoice_id, $item_id,$first_name, $last_name, $email_user );

			}

		return $invoice_id;

		}

	}
}

/**
 * Per listings payments: update property payment details
 *
 */
if ( ! function_exists( 'tt_update_property_payment_data' ) ) {
	function tt_update_property_payment_data( $invoice_id, $property_id, $first_name, $last_name, $email_user ) {

		global $realty_theme_option;
		$property_settings_amount = $realty_theme_option['paypal-amount'];
		$property_featured_amount = $realty_theme_option['paypal-featured-amount'];

		$property_extra_featured = get_post_meta( $property_id, 'estate_property_featured', true );
		if ( $property_extra_featured ) {
			$property_settings_amount = $property_settings_amount + $property_featured_amount;
		}
		$price_package = $property_settings_amount;
		$settings_currency_code = $realty_theme_option['paypal-currency-code'];
		$time_now = time();
		update_post_meta( $property_id, 'property_payment_payment_date', $time_now );
		update_post_meta( $property_id, 'property_payment_first_name', $first_name );
		update_post_meta( $property_id, 'property_payment_last_name', $last_name );
		update_post_meta( $property_id, 'property_payment_payer_email', $email_user );
		update_post_meta( $property_id, 'property_payment_mc_currency', $settings_currency_code );
		update_post_meta( $property_id, 'property_payment_mc_gross', $property_settings_amount );
		update_post_meta( $property_id, 'property_payment_txn_id', $invoice_id );
		update_post_meta( $property_id, 'property_payment_status', esc_html__( 'Completed', 'realty' ) );

	}
}
add_action( 'init', 'tt_schedule_activation' );
add_action( 'tt_daily_system_check_properties', 'tt_daily_check_recurring_properties' );

if ( ! function_exists( 'tt_schedule_activation' ) ) {
	function tt_schedule_activation() {
		if ( ! wp_next_scheduled( 'tt_daily_system_check_properties' ) ) {
		    wp_schedule_event( time(), 'daily', 'tt_daily_system_check_properties' );
		}
	}
}

if ( ! function_exists( 'tt_daily_check_recurring_properties' ) ) {
	function tt_daily_check_recurring_properties() {

		global $realty_theme_option;
		$payment_enabled = $realty_theme_option['property-submission-type'];
		$enabled_subscriptions = $realty_theme_option['paypal-enable-subscription'];

		if ( $payment_enabled != 'per-listing' || ! $enabled_subscriptions ) {
			return;
		}

		$property_args = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$query_properties = new WP_Query( $property_args );

		if ( $query_properties->have_posts() ) :
			while ( $query_properties->have_posts() ) : $query_properties->the_post();

					$payment_date = get_post_meta( $post->ID, 'property_payment_payment_date', true );

					if ( ! empty( $payment_date ) ) {
						$is_valid = tt_is_listed_property_valid( $post->ID, $payment_date );

						if ( $is_valid == false ) {
							update_post_meta( $post->ID, 'property_payment_status', __( 'Expired', 'realty' ), __( 'Completed', 'realty' ) );
							$property = array(
								'ID'		  		=> $post->ID,
								'post_status' => 'pending',
							);
							wp_update_post( $property );

							tt_property_expiry_email($post->post_author,$post->ID);
						}
					}

			endwhile;
		endif;

		wp_reset_query();
	}
}


if ( ! function_exists( 'tt_is_listed_property_valid' ) ) {
	function tt_is_listed_property_valid( $property_id, $activation_date ) {

		global $realty_theme_option;
		$paypal_enable_subscription = $realty_theme_option['paypal-enable-subscription'];

		if ( $paypal_enable_subscription ) {

			$paypal_subscription_recurrence = $realty_theme_option['paypal-subscription-recurrence'];
			$paypal_subscription_period = $realty_theme_option['paypal-subscription-period'];

			if ( $property_id && $activation_date ) {

				$time_now = time();
				$seconds = 0;
				switch ( $paypal_subscription_period ) {
					case 'D':
						$seconds = 60*60*24;
						break;
					case 'W':
						$seconds = 60*60*24*7;
						break;
					case 'M':
						$seconds = 60*60*24*30;
						break;
					case 'Y':
						$seconds = 60*60*24*365;
						break;
				}

			   $time_period = $seconds * $paypal_subscription_recurrence;
				// Ff this time is more than actvation time + time period of package, then not valid
				if( $time_now > $activation_date + $time_period ) {
					return false;
				} else {
					return true;
				}

			 } else {
				return __( 'Expired', 'realty' );
			}

		}

	}
}
add_action( 'init', 'tt_user_schedule_activation' );
add_action( 'tt_daily_system_check_users', 'tt_daily_user_membership_check' );

if ( ! function_exists( 'tt_user_schedule_activation' ) ) {
	function tt_user_schedule_activation() {
		if ( ! wp_next_scheduled( 'tt_daily_system_check_users' ) ) {
			wp_schedule_event( time(), 'daily', 'tt_daily_system_check_users' );
		}
	}
}


if( ! function_exists('tt_daily_user_membership_check') ) {
	function tt_daily_user_membership_check() {

		global $realty_theme_option;
		$payment_enabled = $realty_theme_option['property-submission-type'];

		if ( $payment_enabled != 'membership' ) {
			return;
		}

		$users_query = new WP_User_Query( array( 'role' => 'subscriber' ) );

		if ( ! empty( $users_query->results ) ) {
			foreach ( $users_query->results as $user ) {
				$package_id = get_user_meta( $user->ID, 'subscribed_package_default_id', true );

				if ( ! empty( $package_id ) ) {
					$is_valid = tt_is_user_membership_valid( $user->ID );
					if ( $is_valid == 0 ) {
						tt_user_properties_scheduled_update( $user->ID );
						tt_package_expiry_email( $user->ID, $package_id );
					}
				}
			}
		}

		wp_reset_query();

	}
}

/**
 * Scheduled update
 *
 */
if ( ! function_exists( 'tt_user_properties_scheduled_update' ) ) {
	function tt_user_properties_scheduled_update( $user_id ) {

		$property_args = array(
			'post_type' 			=> 'property',
			'posts_per_page' 	=> -1,
			'author' 					=> $user_id,
			'post_status'			=> 'publish'
		);

		$query_properties = new WP_Query( $property_args );

		if ( $query_properties->have_posts() ) :
			while ( $query_properties->have_posts() ) : $query_properties->the_post();
				$property = array(
					'ID'          => $post->ID,
					'post_status' => 'pending',
				);
				wp_update_post( $property );
			endwhile;
		endif;

		wp_reset_query();

	}
}

/**
 * Publish properties on package update
 *
 */
if ( ! function_exists( 'tt_user_properties_publish_on_package_update' ) ) {
	function tt_user_properties_publish_on_package_update( $user_id ) {

		$property_args = array(
			'post_type'      => 'property',
			'posts_per_page' => -1,
			'author'         => $user_id,
			'post_status'    => 'pending'
		);

		$query_properties = new WP_Query( $property_args );

		if ( $query_properties->have_posts() ) :
			while ( $query_properties->have_posts() ) : $query_properties->the_post();
				$property = array(
					'ID'          => $post->ID,
					'post_status' => 'publish',
				);
				wp_update_post( $property );
			endwhile;
		endif;

		wp_reset_query();
	}

}

/**
 * Send email on package expire
 *
 */
if ( ! function_exists( 'tt_package_expiry_email' ) ) {
	function tt_package_expiry_email( $user_id, $package_id ) {

		$to = get_user_meta( $user_id, 'user_email', true );
		$subject = bloginfo( 'name' );
		$body = 'Dear User<br></br>';
		$body .= 'Your subscribed membership package <strong>' . get_the_title( $package_id ) . '</strong> is expired, status for all of your properties is changed from Published to "Pending". Please update your package so that your properties are published again.</br></br>';
		$body .= 'You can subscribe to same package here: '. get_pemralink( $package_id );
		$body .= 'Thank you';

		wp_mail( $to, $subject, $body );

		// Reset content-type to avoid conflicts: http://core.trac.wordpress.org/ticket/23578
		remove_filter( 'wp_mail_content_type', 'tt_set_html_content_type' );

	}
}

/**
 * Send email on property expire
 *
 */
if ( ! function_exists( 'tt_property_expiry_email' ) ) {
	function tt_property_expiry_email( $user_id, $property_id ) {

		$to = get_user_meta( $user_id, 'user_email', true );
		$subject = bloginfo( 'name' );
		$body  = 'Dear User<br></br>';
		$body .= 'Your Property <strong>'.get_the_title( $property_id ) . '</strong> with ID: ' . $property_id . ' is expired, status for your property is changed from Published to "Pending". Please make your due payment so that your property is published again.</br></br>';
		$body .= 'Thank you';
		add_filter( 'wp_mail_content_type', 'tt_set_html_content_type' );
		wp_mail( $to, $subject, $body );

		// Reset content-type to avoid conflicts: http://core.trac.wordpress.org/ticket/23578
		remove_filter( 'wp_mail_content_type', 'tt_set_html_content_type' );

	}
}
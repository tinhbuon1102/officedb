<?php
/**
 * Membership Package: PayPal Payment Button
 *
 */
if ( ! function_exists( 'tt_package_payment_button' ) ) {
	function tt_package_payment_button( $package_id ) {

		global $post, $realty_theme_option;

		if ( isset( $package_id ) ) {
			$package_id = $package_id;
		} else {
			$package_id = $post->ID;
		}

		$package = get_post( $package_id );
		$payment_title = $package->post_title;
		$paypal_subscription_period = get_post_meta($package_id,'estate_package_period_unit', true);
		$paypal_subscription_recurrence = get_post_meta($package_id,'estate_package_valid_renew', true);
		$paypal_settings_amount = get_post_meta($package_id,'estate_package_price', true);
		$paypal_settings_enable = true;

		$paypal_settings_currency_code = $realty_theme_option['paypal-currency-code'];
		$paypal_settings_merchant_id = $realty_theme_option['paypal-merchant-id'];
		$paypal_settings_sandbox = $realty_theme_option['paypal-sandbox'];
		$paypal_enable_subscription = true;

		/**
		 * Convert payment period values into PayPal conform values
		 * https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/
		 *
		 */
		$paypal_subscription_period_converted = ucfirst( $paypal_subscription_period[0] ); // i.e. Return first character of "months" (for example) and uppercase it.

		if ( $paypal_settings_enable && $paypal_settings_merchant_id && $paypal_settings_currency_code && $paypal_settings_amount ) {

			$paypal_output  = '<div title="' . $paypal_settings_currency_code . ' ' . $paypal_settings_amount .'/' . $paypal_subscription_period . '" data-toggle="tooltip" style="display:inline-block">';
			$paypal_output .= '<script async src= "' . plugin_dir_url( __FILE__ )  . 'lib/paypal/paypal-button.min.js?merchant=' . $paypal_settings_merchant_id . '"';

			if ( $paypal_enable_subscription ) {
				$paypal_output .= 'data-button="subscribe"';
				$paypal_output .= 'data-recurrence="' . $paypal_subscription_recurrence . '"';
				$paypal_output .= 'data-period="' . $paypal_subscription_period_converted . '"';
				$paypal_output .= 'data-src="1"'; // For Indefinite Cycles
			} else {
				$paypal_output .= 'data-button="subscribe"';
			}

			if ( $paypal_settings_sandbox ) {
				$paypal_output .= 'data-env="sandbox"';
			}

			$user_id = get_current_user_id();
			$paypal_output .= 'data-custom="' . $user_id . '"'; // Use to hold user_id. Without it impossible to retrieve it later, as payment processing is off-site

			$paypal_output .= 'data-name="' . $payment_title . '"';
			$paypal_output .= 'data-number="' . $package_id . '"';
			$paypal_output .= 'data-amount="' . $paypal_settings_amount . '"';
			$paypal_output .= 'data-currency="' . $paypal_settings_currency_code . '"';
			$paypal_output .= 'data-number="' . $package_id . '"';
			$paypal_output .= 'data-quantity="1"';
			$paypal_output .= 'data-shipping="0"';
			$paypal_output .= 'data-tax="0"';
			$paypal_output .= 'data-style="secondary"';
			$paypal_output .= 'data-size="small"';
			$paypal_output .= 'data-callback="' . plugin_dir_url( __FILE__ ) . 'lib/paypal/ipn.php' . '"';
			$paypal_output .= '></script>';
			$paypal_output .= '</div>';

			return $paypal_output;

		}

	}
}

/**
 * Pay Per Listing: PayPal Payment Button
 *
 */
if ( ! function_exists( 'tt_paypal_payment_button' ) ) {
	function tt_paypal_payment_button( $property_id ) {

		global $post, $realty_theme_option;

		if ( isset( $property_id ) ) {
			$property_id = $property_id;
		} else {
			$property_id = $post->ID;
		}

		$paypal_settings_enable = $realty_theme_option['paypal-enable'];
		$paypal_enable_subscription = $realty_theme_option['paypal-enable-subscription'];
		$paypal_subscription_recurrence = $realty_theme_option['paypal-subscription-recurrence'];
		$paypal_subscription_period = $realty_theme_option['paypal-subscription-period'];
		$paypal_settings_merchant_id = $realty_theme_option['paypal-merchant-id'];
		$paypal_settings_amount = $realty_theme_option['paypal-amount'];
		$paypal_featured_amount = $realty_theme_option['paypal-featured-amount'];

		$property_extra_featured = get_post_meta( $property_id, 'estate_property_featured', true );

		if ( $property_extra_featured ) {
			$paypal_settings_amount = $paypal_settings_amount + $paypal_featured_amount;
		}

		$paypal_settings_currency_code = $realty_theme_option['paypal-currency-code'];
		$paypal_settings_sandbox = $realty_theme_option['paypal-sandbox'];

		if ( $paypal_settings_enable && $paypal_settings_merchant_id && $paypal_settings_currency_code && $paypal_settings_amount ) {

			$paypal_output  = '<div title="' . $paypal_settings_currency_code . ' ' . $paypal_settings_amount .'" data-toggle="tooltip" style="display:inline-block">';

			$paypal_output .= '<script async src= "' . plugin_dir_url( __FILE__ ) . 'lib/paypal/paypal-button.min.js?merchant=' . $paypal_settings_merchant_id . '"';

			if ( $paypal_enable_subscription ) {
				$paypal_output .= 'data-button="subscribe"';
				$paypal_output .= 'data-recurrence="' . $paypal_subscription_recurrence . '"';
				$paypal_output .= 'data-period="' . $paypal_subscription_period . '"';
				$paypal_output .= 'data-src="1"'; // For Indefinite Cycles
			} else {
				$paypal_output .= 'data-button="buynow"';
			}

			if ( $paypal_settings_sandbox ) {
				$paypal_output .= 'data-env="sandbox"';
			}

			$user_id = get_current_user_id();
			$paypal_output .= 'data-custom="' . $user_id . '"'; // Use to hold user_id. Without it impossible to retrieve it later, as payment processing is off-site

			$paypal_output .= 'data-name="' . get_the_title() . '"';
			$paypal_output .= 'data-number="' . $property_id . '"';
			$paypal_output .= 'data-amount="' . $paypal_settings_amount . '"';
			$paypal_output .= 'data-currency="' . $paypal_settings_currency_code . '"';
			$paypal_output .= 'data-number="' . $property_id . '"';
			$paypal_output .= 'data-quantity="1"';
			$paypal_output .= 'data-shipping="0"';
			$paypal_output .= 'data-tax="0"';
			$paypal_output .= 'data-style="secondary"';
			$paypal_output .= 'data-size="small"';

			$paypal_output .= 'data-callback="' . plugin_dir_url( __FILE__ ) . 'lib/paypal/ipn.php' . '"';

			$paypal_output .= '></script>';
			$paypal_output .= '</div>';

			return $paypal_output;

		}

	}
}
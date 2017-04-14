<?php
/**
 * Contact Form - Single Property
 *
 */
if ( ! function_exists( 'submit_property_contact_form' ) ) {
	function submit_property_contact_form() {

		global $realty_theme_option;

		if ( wp_verify_nonce( $_POST['nonce'] ) && ! empty( $_POST['email'] ) && ! empty( $_POST['message'] ) ) {

			$property_title = $_POST['property_title'];
			$property_url = $_POST['property_url'];
			$recipient = array();

			if ( $realty_theme_option['property-contact-form-cc-admin'] ) {
				$recipient[] = $_POST['agent_email'];
				$recipient[] = $realty_theme_option['property-contact-form-cc-admin'];
			} else {
				$recipient[] = $_POST['agent_email'];
			}

			if ( empty( $property_title ) ) {
				$subject = esc_html__( 'Agent Profile Page Contact Form', 'realty' ) . ' | ' . get_bloginfo( 'name' );
			} else {
				$subject = esc_html__( 'Message for property', 'realty' ) . ': '. $property_title . ' | ' . get_bloginfo( 'name' );
			}

			$name = $_POST['name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];

			$message  = '<p>' . $_POST['message'] . '</p>';
			$message .= '<p>- - -</p>';

		  if ( $name ) {
		  	$message .= esc_html__( 'Name', 'realty' ) . ': ' . $name . '<br />';
		  }

		  $message .= esc_html__( 'Email', 'realty' ) . ': ' . $email . '<br />';

		  if ( $phone ) {
				$message .= esc_html__( 'Phone', 'realty' ) . ': ' . $phone . '<br />';
			}

			if ( $property_title ) {
		  	$message .= esc_html__( 'Property', 'realty' ) . ': ' . $property_title . '<br />';
		  }

		  if ( $property_url ) {
		  	$message .= esc_html__( 'Sent from', 'realty' ) . ': ' . $property_url;
			}

		  $headers[] = "From: $name <$email>";
		  $headers[] = "Content-Type: text/html; charset=UTF-8";

			wp_mail( $recipient, $subject, $message, $headers );

		}

		die;

	}
}
add_action( 'wp_ajax_nopriv_submit_property_contact_form', 'submit_property_contact_form' );
add_action( 'wp_ajax_submit_property_contact_form', 'submit_property_contact_form' );

/**
 * Manage email recipient in "Contact Form 7"
 *
 */
if ( ! function_exists( 'tt_wpcf7_agent_admin_email' ) ) {
  function tt_wpcf7_agent_admin_email ( $form ) {

	global $realty_theme_option;

	$mail = $form->properties;
	$properites = $form->get_properties();

	$post_url = $_SERVER["REQUEST_URI"];
	$postid = url_to_postid( $post_url );

	if ( get_post_type( $postid ) != 'property' ) {
		return ;
	}

	$post = get_post( $postid );
	$email = '';
	$agent = get_post_meta( $post->ID, 'estate_property_custom_agent', true );

	if ( ! $realty_theme_option['send-email-to-admin-only-cf7'] ) {

		if ( ! empty( $agent ) ) {
			$user_meta = get_userdata( $agent );
			$email = strip_tags( trim( $user_meta->user_email ) );
		} else {
			$author_id = $post->post_author;
			$email = get_the_author_meta( 'user_email', $author_id);
		}

		$recipients = $email;

		if ( $realty_theme_option['property-contact-form-cc-admin'] ) {
			$recipients = $email . ',' . $realty_theme_option['property-contact-form-cc-admin'];
		}

		$recipient_default = $properites['mail']['recipient'];
		$properites['mail']['recipient'] = $recipients;

	}

	$body_default = $properites['mail']['body'];
  $properites['mail']['body'] = $body_default . "\r\n\r\n" . esc_html__( 'Property: ', 'realty' ) . get_permalink( $postid ) . "\r\n\r\n";

	$form->set_properties( $properites );

  }
}
add_action( 'wpcf7_before_send_mail', 'tt_wpcf7_agent_admin_email' );
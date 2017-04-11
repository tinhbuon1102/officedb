<?php
/**
 * Email content type
 *
 */
if ( ! function_exists( 'tt_set_html_content_type' ) ) {
	function tt_set_html_content_type() {
		//return 'text/plain';
		return 'text/html';
	}
}

/**
 * Change the message/body of the email
 *
 */
if ( ! function_exists( 'wpse_retrieve_password_message' ) ) {
	function wpse_retrieve_password_message( $message, $key ) {

	  $user_data = '';

	  // If no value is posted, return false
	  if ( ! isset( $_POST['user_login'] )  ){
	    return '';
	  }

	  // Fetch user information from user_login
	  if ( strpos( $_POST['user_login'], '@' ) ) {
		  $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
	  } else {
		  $login = trim( $_POST['user_login'] );
		  $user_data = get_user_by( 'login', $login );
	  }

	  if ( ! $user_data ) {
		  return '';
	  }

		add_filter( 'wp_mail_content_type', 'tt_set_html_content_type' );

	  $user_login = $user_data->user_login;
	  $user_email = $user_data->user_email;

	  // Setting up message for retrieve password
	  $message  = '<p>' . esc_html__( 'Looks like you want to reset your password!', 'realty' ) . '</p>';
	  $message .= '<p>' . esc_html__( 'To do so please visit:', 'realty' ) . ' <a href="' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '">' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '</a></p>';
	  $message .= esc_html__( 'Kind regards!', 'realty' );

	  // Return completed message for retrieve password
	  return $message;

	}
}
add_filter( 'retrieve_password_message', 'wpse_retrieve_password_message', 10, 2 );

/**
 * Property Submit - Admin Email Notification
 *
 */
if ( ! function_exists( 'tt_property_submit_notification_admin_email' ) ) {
	function tt_property_submit_notification_admin_email( $post_id, $post, $update ) {

		global $realty_theme_option;

		if ( 'property' != $post->post_type || $update ) {
			return;
		}

		if ( $realty_theme_option['property-submit-notification-email-recipient'] ) {

			add_filter( 'wp_mail_content_type', 'tt_set_html_content_type' );

			$admin_email = $realty_theme_option['property-submit-notification-email-recipient'];
			$subject = sprintf( esc_html__( '[%1$s] New Property: %2$s', 'realty' ), get_option( 'blogname' ), $post->post_title );
			$admin_message  = sprintf( esc_html__( 'A new property %s has been submitted. To view/publish this property visit: %s', 'realty' ), '<strong>' . $post->post_title . '</strong>', get_option( 'siteurl' ) . '/wp-admin/post.php?action=edit&post=' . $post_id );

			@wp_mail( $admin_email, $subject, $admin_message );

		}

	}
}
add_action( 'save_post', 'tt_property_submit_notification_admin_email', 9 , 3 );

/**
 * Custom email to new users
 * Redefine user notification function
 *
 */
if ( ! function_exists( 'wp_new_user_notification' ) ) {
	function wp_new_user_notification( $user_id, $notify = '' ) {

    global $wpdb;
		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		$message  = sprintf( esc_html__( 'New user registration on %s:', 'realty' ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Username: %s', 'realty' ), $user_login ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'E-mail: %s', 'realty' ), $user_email ) . "\r\n";

		@wp_mail( get_option( 'admin_email' ), sprintf( esc_html__( '[%s] New User Registration', 'realty' ), get_option( 'blogname' ) ), $message );

		if ( 'admin' === $notify || empty( $notify ) ) {
			//return;
		}

		$key = wp_generate_password( 20, false );

		do_action( 'retrieve_password_key', $user->user_login, $key );

		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}

		add_filter( 'wp_mail_content_type', 'tt_set_html_content_type_plugin' );

		$hashed = $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_pass' => $hashed ), array( 'user_login' => $user->user_login ) );

		$message  = esc_html__( 'Hi there,', 'realty' ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Welcome to %s! Here\'s how to log in:', 'realty' ), get_option( 'blogname') ) . "\r\n\r\n";

		$message .= sprintf( esc_html__( 'Username: %s', 'realty' ), $user->user_login) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Password: %s', 'realty' ), $key ) . "\r\n\r\n";
		$message .= esc_html__( 'To login to your account, visit the following address:', 'realty' ) . "\r\n\r\n";
		$message .= network_site_url( '?login' ) . ' ';

		$message .= sprintf( esc_html__( 'If you have any problems, please contact me at %s.', 'realty' ), get_option( 'admin_email' ) ) . "\r\n\r\n";
		$message .= esc_html__( 'Thank you!', 'realty' );

		wp_mail( $user_email, sprintf( esc_html__( '[%s] Your username and password info', 'realty' ), get_option( 'blogname') ), $message );

		remove_filter( 'wp_mail_content_type', 'tt_set_html_content_type_plugin' );

	}
}

/**
 * Realty Core - Email content type
 *
 */
if ( ! function_exists( 'tt_set_html_content_type_plugin' ) ) {
	function tt_set_html_content_type_plugin() {
		return 'text/html';
	}
}

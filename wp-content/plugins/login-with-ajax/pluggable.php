<?php
//Replaces the user registration welcome email, this is an exact copy of the wp_new_user_notification function with one if statement added us to circumvent the default email.
//see comments starting //LWA for changes
if ( !function_exists('wp_new_user_notification') ) :
/**
 * Email login credentials to a newly-registered user.
 *
 * A new user registration notification is also sent to admin email.
 *
 * @since 2.0.0
 * @since 4.3.0 The `$plaintext_pass` parameter was changed to `$notify`.
 * @since 4.3.1 The `$plaintext_pass` parameter was deprecated. `$notify` added as a third parameter.
 *
 * @global wpdb         $wpdb      WordPress database object for queries.
 * @global PasswordHash $wp_hasher Portable PHP password hashing framework instance.
 *
 * @param int    $user_id    User ID.
 * @param null   $deprecated Not used (argument deprecated).
 * @param string $notify     Optional. Type of notification that should happen. Accepts 'admin' or an empty
 *                           string (admin only), or 'both' (admin and user). The empty string value was kept
 *                           for backward-compatibility purposes with the renamed parameter. Default empty.
 */
function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
	if ( $deprecated !== null ) {
		//_deprecated_argument( __FUNCTION__, '4.3.1' ); //LWA - <4.3 backwards compat, not executing this deprecated function
	}

	global $wpdb, $wp_hasher;
	$user = get_userdata( $user_id );

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$message = __("Thanks for signing up to our blog. 

You can login with the following credentials by visiting %BLOGURL%

Username: %USERNAME%
To set your password, visit the following address: %PASSWORD%

We look forward to your next visit!

The team at %BLOGNAME%", 'login-with-ajax');
	
	$user_name = @$_REQUEST['user_name'];
	$user_name_kana = @$_REQUEST['user_name_kana'];
	$user_company = @$_REQUEST['user_company'];
	$user_address = @$_REQUEST['user_address'];
	$user_phone = @$_REQUEST['user_phone'];
	
	$message = str_replace('%USERNAME%', $user->user_login, $message);
	$message = str_replace('%EMAIL%', $user->user_email, $message);
	
	$message = str_replace('%NAME%', $user_name, $message);
	$message = str_replace('%NAME KANA%', $user_name_kana, $message);
	$message = str_replace('%COMPANY%', $user_company, $message);
	$message = str_replace('%ADDRESS%', $user_address, $message);
	$message = str_replace('%PHONE%', $user_phone, $message);
	
	$message = str_replace('%PASSWORD%', $login_link, $message);
	$message = str_replace('%BLOGNAME%', $blogname, $message);
	$message = str_replace('%BLOGURL%', get_bloginfo('wpurl'), $message);
	
	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

	if ( 'admin' === $notify || (empty( $notify ) && empty($deprecated)) ) { //LWA - let this pass if there's a password to notify user with, <4.3 backwards compat
		return;
	}

	if ($_POST['user_password'] && $_POST['user_repeat_password'])
	{
		$user->user_pass = $_POST['user_password'];
		wp_set_password($user->user_pass, $user->ID);
		
		// Auto login
		$user_id = $user->ID;
		$user_login = $user->user_login;
		wp_set_current_user( $user_id, $user_login );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user_login );
		
		
		$message = sprintf(__('E-mail: %s'), $user->user_email) . "\r\n\r\n";
		$message .= sprintf(__('Password: %s'), $user->user_pass) . "\r\n\r\n";
		$message .= wp_login_url() . "\r\n";
		
	}
	else {
		// Generate something random for a password reset key.
		$key = wp_generate_password( 20, false );
		
		/** This action is documented in wp-login.php */
		do_action( 'retrieve_password_key', $user->user_login, $key );
		
		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
		
		//LWA Customizations START
		//generate password link like it's done further down
		$password_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');
		if( !empty($deprecated) ) $password_link = $deprecated; // for <4.3 compatability
		LoginWithAjax::new_user_notification($user->user_login, $password_link, $user->user_email, $blogname, $deprecated);
		return;
		//LWA Customizations END
		
		$message = sprintf(__('E-mail: %s'), $user->user_email) . "\r\n\r\n";
		$message .= __('To set your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
		
		$message .= wp_login_url() . "\r\n";
	}
	wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
}
endif;

?>
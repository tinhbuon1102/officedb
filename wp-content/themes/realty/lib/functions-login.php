<?php
/**
 * Login Form
 *
 */
if ( ! function_exists( 'tt_login_form' ) ) {
	function tt_login_form() {
		ob_start();
		global $realty_theme_option;
		login_with_ajax();
		return ob_get_clean();

	}
}
add_action( 'action_name', 'tt_login_form' );

/**
 * Login Logo Link
 *
 */
if ( ! function_exists( 'tt_wp_login_url' ) ) {
	function tt_wp_login_url() {
		return home_url();
	}
}
add_filter( 'login_headerurl', 'tt_wp_login_url' );

/**
 * Add "Lost Password" link to wp_login_form()
 *
 */
if ( ! function_exists( 'tt_add_lost_password_link' ) ) {
	function tt_add_lost_password_link() {
		return '<a href="' . wp_lostpassword_url( get_permalink() ) . '">' . esc_html__( 'Lost Password?', 'realty' ) . '</a>';
	}
}
add_action( 'login_form_bottom', 'tt_add_lost_password_link' );

if ( ! function_exists( 'tt_lost_password_redirect' ) ) {
	function tt_lost_password_redirect() {
		wp_redirect( home_url() );
		exit;
	}
}
add_action( 'password_reset', 'tt_lost_password_redirect' );

/**
 * Login Failed - Username & Password Entered, But Incorrect
 * http://www.paulund.co.uk/create-your-own-wordpress-login-page
 *
 */
if ( ! function_exists( 'tt_login_failed' ) ) {
	function tt_login_failed( $user ) {

		$referrer = $_SERVER['HTTP_REFERER'];

	  // Check that were not on the default login page
		if ( ! empty( $referrer ) && ! strstr( $referrer,'wp-login' ) && ! strstr( $referrer,'wp-admin' ) && $user != null ) {
			// Make sure we don't already have a failed login attempt
			if ( ! strstr($referrer, '?login=failed' ) ) {
				// Redirect to login page and append a querystring of login failed
		    wp_redirect( $referrer . '?login=failed' );
		  } else {
		  	wp_redirect( $referrer );
		  }
		  exit;
		}

	}
}
// add_action( 'wp_login_failed', 'tt_login_failed' );

/**
 * Login Failed - No Username & Password Entered
 * http://www.paulund.co.uk/create-your-own-wordpress-login-page
 *
 */
if ( ! function_exists( 'tt_login_blank' ) ) {
	function tt_login_blank( $user ) {

		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
	  	$referrer = $_SERVER['HTTP_REFERER'];
	  } else {
		 	$referrer = '';
	  }

	  $error = false;

		// Check Login
		if( isset( $_POST['log'] ) && $_POST['log'] == '' || isset( $_POST['pwd'] ) && $_POST['pwd'] == '' ) {
			$error = true;
		}

		// Check that were not on the default login page
		if ( ! empty( $referrer ) && !strstr( $referrer,'wp-login' ) && ! strstr( $referrer, 'wp-admin' ) && $error ) {
			// Make sure we don't already have a failed login attempt
	  	if ( ! strstr($referrer, '?login=failed') ) {
	  		// Redirect to the login page and append a querystring of login failed
	      wp_redirect( $referrer . '?login=failed' );
	    } else {
	      wp_redirect( $referrer );
	    }
	  exit;
		}

	}
}
// add_action( 'authenticate', 'tt_login_blank' );

/**
 * Login with Username OR Email Address
 * http://en.bainternet.info/wordpress-allow-login-with-email/
 *
 */
if ( ! function_exists( 'tt_allow_email_login' ) ) {
	function tt_allow_email_login( $user, $username, $password ) {
		if ( is_email( $username ) ) {
			$user = get_user_by_email( $username );
			if ( $user ) {
				$username = $user->user_login;
			}
		}
		return wp_authenticate_username_password( null, $username, $password );
	}
}
add_filter( 'authenticate', 'tt_allow_email_login', 20, 3 );

/**
 * Login Modal
 *
 */
if ( ! function_exists( 'tt_login_modal' ) ) {
	function tt_login_modal() {
		global $realty_theme_option;
		if ( ! is_user_logged_in() && ! $realty_theme_option['disable-header-login-register-bar'] ) {
			get_template_part( 'lib/inc/template/login-modal' );
		}
	}
}
// add_action( 'wp_footer', 'tt_login_modal', 20 );

/**
 * Disable WP Admin Bar For Non-Admins
 *
 */
if ( ! function_exists( 'tt_remove_admin_bar' ) ) {
	function tt_remove_admin_bar() {
		if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
		  show_admin_bar( false );
		}
	}
}
add_action( 'after_setup_theme', 'tt_remove_admin_bar' );

/**
 * Redirect user after successful login.
 *
 */
if ( ! function_exists( 'tt_login_redirect' ) ) {
	function tt_login_redirect( $redirect_to, $request, $user ) {

		// Is there a user to check?
		global $user;

		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return $redirect_to;
			} else {
				return $request . '?sign-user=successful';
			}
		} else {
			return $redirect_to;
		}

	}
}
add_filter( 'login_redirect', 'tt_login_redirect', 10, 3 );

/**
 * Custom login page (wp-login.php)
 *
 */
if ( ! function_exists( 'tt_custom_login' ) ) {
	function tt_custom_login() {

		// Login Page Logo
		global $realty_theme_option;
		$login_logo = $realty_theme_option['logo-login']['url'];
		$background_login = $realty_theme_option['background-login'];
		?>

		<style type="text/css">
			<?php if ( ! empty( $login_logo ) ) { ?>
				.login h1 a {
					background: url(<?php echo $login_logo; ?>) 50% 50% no-repeat !important;
					width: auto;
				}
			<?php } ?>

			<?php if ( $background_login ) { ?>
				.login {
					background-color: <?php echo $background_login; ?>;
				}
			<?php } else { ?>
				.login {
					background-color: #f8f8f8;
				}
			<?php } ?>

			.login form input[type="submit"] {
				border-radius: 0;
				border: none;
				-webkit-box-shadow: none;
				box-shadow: none;
			}

			.login form .input,
			.login .form input:focus {
				padding: 5px 10px;
				color: #666;
				-webkit-box-shadow: none;
				box-shadow: none;
			}

			input[type=checkbox]:focus,
			input[type=email]:focus,
			input[type=number]:focus,
			input[type=password]:focus,
			input[type=radio]:focus,
			input[type=search]:focus,
			input[type=tel]:focus,
			input[type=text]:focus,
			input[type=url]:focus,
			select:focus,
			textarea:focus {
				-webkit-box-shadow: none;
				box-shadow: none;
			}
		</style>

		<?php
		// Remove Login Shake
		remove_action( 'login_head', 'wp_shake_js', 12 );

	}
}
add_action( 'login_head', 'tt_custom_login' );
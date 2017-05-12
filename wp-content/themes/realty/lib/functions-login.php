<?php
/**
 * Login Form
 *
 */
if ( ! function_exists( 'tt_login_form' ) ) {
	function tt_login_form() {
		ob_start();
		global $realty_theme_option;
		?>

		<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 1em;">
			<li class="active"><a href="#tab-login" role="tab" data-toggle="tab"><?php esc_html_e( 'Login', 'realty' ); ?></a></li>
			<?php if ( get_option('users_can_register') ) { ?>
			<li><a href="#tab-registration" role="tab" data-toggle="tab"><?php esc_html_e( 'Register', 'realty' ); ?></a></li>
			<?php } ?>
  	</ul>

		<div class="tab-content">

			<div class="tab-pane active" id="tab-login">

				<?php if ( ! is_user_logged_in() ) { ?>
	        <p id="msg-login-to-add-favorites" class="alert alert-info hide"><small><?php esc_html_e( 'You have to be logged in to use this feature.', 'realty' ); ?></small></p>
        <?php } ?>

				<?php	if ( isset($_GET['login']) && $_GET['login'] == 'failed' ) { ?>
					<p id="login-error" class="text-danger"><small><?php esc_html_e( 'Incorrect login details. Please enter your username and password, and submit again.', 'realty' ); ?></small></p>
				<?php } ?>

				<?php
					wp_login_form( array(
						'id_submit' => 'wp-submit-login',
						'label_username' => null,
						'label_password' => null,
					) );
				?>

				<script>
				(function($) {
				  "use strict";
					$(document).ready(function() {
						$('#user_login').attr( 'placeholder', '<?php esc_html_e( 'Username or email', 'realty' ); ?>' );
						$('#user_pass').attr( 'placeholder', '<?php esc_html_e( 'Password', 'realty' ); ?>' );
					});
				})(jQuery);
				</script>

				<?php
					if ( ! is_user_logged_in() && is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {
						do_action( 'wordpress_social_login' );
					}
				?>

			</div>

			<?php if ( get_option ('users_can_register' ) ) { ?>
				<div class="tab-pane" id="tab-registration">
					<form name="registerform" id="registerform" action="<?php echo wp_registration_url(); ?>" method="post">
						<div class="form-group">
							<input type="text" name="user_login" id="user_login" placeholder="<?php echo esc_html_e( 'Username', 'realty' ); ?>">
						</div>
						<div class="form-group">
							<input type="text" name="user_email" id="user_email" placeholder="<?php echo esc_html_e( 'Email', 'realty' ); ?>">
						</div>
						<!--added kyoko-->
						<div class="form-group">
							<input class="extra_field_input " name="user_company" maxlength="70" type="text" id="user_company" placeholder="<?php echo esc_html_e( 'Company Name', 'realty' ); ?>" required="">
						</div>
						<div class="form-group">
							<input class="extra_field_input " name="user_name" maxlength="70" type="text" id="user_name" placeholder="<?php echo esc_html_e( 'Your name', 'realty' ); ?>" required="">
						</div>
						<div class="form-group">
							<input class="extra_field_input " name="user_name_kana" maxlength="70" type="text" id="user_name_kana" placeholder="<?php echo esc_html_e( 'Your name kana', 'realty' ); ?>" required="">
						</div>
						<div class="form-group">
							<input class="extra_field_input " name="user_address" maxlength="70" type="text" id="user_address" placeholder="<?php echo esc_html_e( 'Address', 'realty' ); ?>" required="">
						</div>
						<div class="form-group">
							<input class="extra_field_input " name="user_phone" maxlength="70" type="text" id="user_phone" placeholder="<?php echo esc_html_e( 'Phone', 'realty' ); ?>" required="">
						</div>
						<!--/added kyoko-->
						<?php if ( ! empty( $realty_theme_option['user-registration-terms-page'] ) ) { ?>
						<div class="form-group">
							<input type="checkbox" name="user_terms" id="user_terms" title="<?php esc_html_e( 'Please accept our terms and conditions to register.', 'realty' ); ?>">
							<label for="user_terms"><?php esc_html_e( 'I hereby agree to the', 'realty' ); ?> <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'realty' ); ?></a><?php esc_html_e( 'I do', 'realty' ); ?></label>
						</div>
						<?php } ?>
						<p id="reg_passmail">
							<?php esc_html_e( 'A password will be e-mailed to you.', 'realty' ); ?>
						</p>
						<input type="hidden" name="redirect_to" value="<?php echo site_url(); ?>?user-register=registered">
						<input type="submit" name="wp-submit-registration" id="wp-submit-registration" value="<?php esc_html_e( 'Register', 'realty' ); ?>">
					</form>
					<?php
						if ( ! is_user_logged_in() && is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {
							do_action( 'wordpress_social_login' );
						}
					?>
				</div>
			<?php }	?>

		</div>

		<?php
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
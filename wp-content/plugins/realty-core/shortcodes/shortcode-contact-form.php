<?php
/**
 * Shortcode: Contact Form
 *
 */
if ( ! function_exists( 'realty_contact_form' ) ) {
	function realty_contact_form( $atts ) {

		extract( shortcode_atts( array(
			'id'                 => rand(),
			'subject'            => esc_html__( 'Contact Form Request', 'realty' ),
			'show_name'          => true,
			'show_phone'         => true,
			'label_name'         => esc_html__( 'Your name', 'realty' ),
			'label_email'        => esc_html__( 'Your email address', 'realty' ),
			'label_phone'        => esc_html__( 'Your phone number', 'realty' ),
			'label_message'      => esc_html__( 'Your message', 'realty' ),
			'placeholders'       => false,
			'required_name'			 => true,
			'required_phone'  	 => false,
			'disable_recaptcha'  => false,
			'submit_text'        => esc_html__( 'Submit', 'realty' ),
		), $atts) );

		global $realty_theme_option;

		ob_start();
		?>
		<form id="<?php echo 'form-' . $id; ?>">
			<?php if ( $show_name ) { ?>
			<p>
				<?php if ( ! $placeholders ) { ?>
					<label><?php echo $label_name; ?></label>
				<?php } ?>
				<input type="text" name="name" placeholder="<?php if ( $placeholders ) { echo esc_attr( $label_name ); } ?>">
			</p>
			<?php }	?>
			<p>
				<?php if ( ! $placeholders ) { ?>
					<label><?php echo $label_email; ?></label>
				<?php }	?>
				<input type="email" name="email" placeholder="<?php if ( $placeholders ) { echo esc_attr( $label_email ); } ?>">
			</p>
			<?php if ( $show_phone ) { ?>
			<p>
				<?php if ( ! $placeholders ) { ?>
					<label><?php echo $label_phone;?></label>
				<?php }	?>
				<input type="text" name="phone" placeholder="<?php if ( $placeholders ) { echo esc_attr( $label_phone ); } ?>">
			</p>
			<?php } ?>
			<p>
				<?php if ( ! $placeholders ) { ?>
					<label><?php echo $label_message; ?></label>
				<?php }	?>
				<textarea name="message" rows="5" placeholder="<?php if ( $placeholders ) { echo esc_attr( $label_message ); } ?>"></textarea>
			</p>
			<?php if ( ! $disable_recaptcha ) { ?>
				<?php	if ( empty( $realty_theme_option['google-recaptcha-site-key'] ) ) { ?>
					<p class="alert alert-info"><?php esc_html_e( 'Please enter your Google reCaptcha site keys under "Appearance > Theme Options > General".', 'realty' ); ?></p>
				<?php } else { ?>
					<p id="recaptcha_contact_form-<?php echo $id; ?>"></p>
					<input type="hidden" name="recaptcha">
				<?php } ?>
			<?php } ?>
			<p>
				<input class="submit" type="submit" value="<?php echo esc_attr( $submit_text ); ?>">
			</p>

			<input type="hidden" name="subject" value="<?php echo $subject; ?>" />
			<input type="hidden" name="page_id" value="<?php the_id(); ?>" />
			<input type="hidden" name="action" value="realty_ajax_shortcode_contact_form" />
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(); ?>" />
			<?php if ( is_user_logged_in() ) { ?>
				<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
				<input type="hidden" name="current_time" value="<?php echo current_time( 'timestamp' ); ?>" />
			<?php } ?>

		</form>

		<p class="form-submit-success alert alert-success hide" id="form-submit-success-<?php echo $id; ?>"><?php esc_html_e( 'Your message was sent successfully.', 'realty' ); ?></p>
		<?php
			$required_fields = array(
				'id'                => $id,
				'name'              => $required_name,
				'email'             => true,
				'phone'             => $required_phone,
				'message'           => true,
			);
			realty_script_contact_form( $required_fields );
		?>

		<?php if ( ! $disable_recaptcha ) { ?>
			<script>
		  	var contactFormCaptcha;
		  	var getResponseContactForm;

		    var onloadCallback = function() {
		      contactFormCaptcha = grecaptcha.render('recaptcha_contact_form-<?php echo $id; ?>', {
		        'sitekey' : '<?php echo $realty_theme_option['google-recaptcha-site-key']; ?>',
		        'callback' : getResponseContactForm,
		      });
		    };
			</script>
			<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
		<?php } ?>

		<?php return ob_get_clean();
	}
}
add_shortcode( 'realty_contact_form', 'realty_contact_form' );

/**
 * Script - Contact form
 *
 */
if ( ! function_exists( 'realty_script_contact_form' ) ) {
	function realty_script_contact_form( $required_fields ) {
		?>
		<script>
		(function($) {
		  "use strict";

			$(document).ready(function() {

			  $('#form-<?php echo $required_fields['id']; ?>').validate({
				  ignore: [],
					rules: {
						<?php if ( isset ( $required_fields['name'] ) && ! empty( $required_fields['name'] ) ) { ?>
						name: {
							required: true
						},
						<?php } ?>
						email: {
							required: true,
							email: true
						},
						message: {
							required: true
						},
						<?php if ( isset ( $required_fields['phone'] ) && ! empty( $required_fields['phone'] ) ) { ?>
						phone: {
							required: true
						},
						<?php } ?>
						recaptcha: {
							required:
							function() {
								getResponseContactForm = grecaptcha.getResponse(contactFormCaptcha);
								if(getResponseContactForm.length == 0) {
									//reCaptcha not verified
									return true;
								}
								else {
									//reCaptcha verified
									return false;
								}
							}
						}
					},
					messages: {
						name: "<?php esc_html_e( 'Please enter your name.', 'realty' ); ?>",
						email: "<?php esc_html_e( 'Please enter your email address.', 'realty' ); ?>",
						email_friend: "<?php esc_html_e( 'Please enter your friend\'s email address.', 'realty' ); ?>",
						phone: "<?php esc_html_e( 'Please enter your phone number.', 'realty' ); ?>",
						subject: "<?php esc_html_e( 'Please enter a subject.', 'realty' ); ?>",
						message: "<?php esc_html_e( 'Please enter a message.', 'realty' ); ?>",
						recaptcha: "<?php esc_html_e( 'Please verify the reCaptcha.', 'realty' ); ?>",
					},
					submitHandler: function(form) {
						$(form).ajaxSubmit({
							error: function() {
								$('#form-submit-success-<?php echo $required_fields['id']; ?>').addClass('hide');
							},
							success: function() {
								var formData = $('<?php echo '#form-' . $required_fields['id']; ?>').serialize();
								$('#form-submit-success-<?php echo $required_fields['id']; ?>').removeClass('hide');
								$.ajax({
									type: 'GET',
									url: ajax_object.ajax_url,
									data: formData,
									success:function(){
										//console.log(formData);
										console.log("Message sent.");
					       	},
					       	error:function(){
						       	console.log("Error: "+formData);
					       	}
								});
							}
						});
					}
				});

			});

		})(jQuery);
		</script>
		<?php
	}
}

// Visual Composer Map
function realty_vc_map_contact_form() {
	vc_map( array(
		'name' => esc_html__( 'Contact Form', 'realty' ),
		'base' => 'realty_contact_form',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Email Subject', 'realty' ),
				'param_name' => 'subject',
				'value' => esc_html__( 'Contact Form Request', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Submit Button Text', 'realty' ),
				'param_name' => 'submit_text',
				'value' => esc_html__( 'Submit', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Label Email Address', 'realty' ),
				'param_name' => 'label_email',
				'value' => esc_html__( 'Your email address', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Label Message', 'realty' ),
				'param_name' => 'label_message',
				'value' => esc_html__( 'Your message', 'realty' ),
				'description' => '',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show placeholders instead of labels', 'realty' ),
				'param_name' => 'placeholders',
				'value' => array( '' => true ),
				'std' => false,
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show Name Field', 'realty' ),
				'param_name' => 'show_name',
				'value' => array( '' => true ),
				'std' => false,
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Require Name (optional by default)', 'realty' ),
				'param_name' => 'required_name',
				'value' => array( '' => true ),
				'std' => false,
				'dependency' => array(
					'element' => 'show_name',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Label Name', 'realty' ),
				'param_name' => 'label_name',
				'value' => esc_html__( 'Your name', 'realty' ),
				'description' => '',
				'dependency' => array(
					'element' => 'show_name',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show Phone Number Field', 'realty' ),
				'param_name' => 'show_phone',
				'value' => array( '' => true ),
				'std' => false,
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Require Phone (optional by default)', 'realty' ),
				'param_name' => 'required_phone',
				'value' => array( '' => true ),
				'std' => false,
				'dependency' => array(
					'element' => 'show_phone',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Label Phone Number', 'realty' ),
				'param_name' => 'label_phone',
				'value' => esc_html__( 'Your phone number', 'realty' ),
				'description' => '',
				'dependency' => array(
					'element' => 'show_phone',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Disable Google reCaptcha', 'realty' ),
				'param_name' => 'disable_recaptcha',
				'value' => array( '' => true ),
				'std' => false,
				'description' => esc_html__( 'If enabled, make sure to enter your reCaptcha site keys under "Appearance > Theme Options > General".', 'realty' ),
			),
		),
	) );
}
add_action( 'vc_before_init', 'realty_vc_map_contact_form' );

/**
 * AJAX - Shortcode "Contact Form"
 *
 */
if ( ! function_exists( 'realty_ajax_shortcode_contact_form' ) ) {
	function realty_ajax_shortcode_contact_form() {

		$admin_email = get_bloginfo( 'admin_email' );
		$page_url = get_the_permalink( $_GET['page_id'] );
		$email = $_GET['email'];
		$name = $_GET['name'];
		$phone = $_GET['phone'];
		$subject = $_GET['subject'];
		$message = '';

		if ( $name ) {
			$message .= esc_html__( 'Name', 'realty' ) . ': ' . $name . '<br />';
		}

		if ( $phone ) {
			$message .= esc_html__( 'Phone', 'realty' ) . ': ' . $phone . '<br />';
		}

		$message .= '<br />' . $_GET['message'] . '<br /><br />';

		if ( $page_url ) {
			$message .= esc_html__( 'Sent from', 'realty' ) . ': ' . $page_url;
		}

		$headers[] = "From:<$admin_email>";
		$headers[] = "Reply-To: $name <$email>";
		$headers[] = "Content-Type: text/html; charset=UTF-8";

		wp_mail( $admin_email, $subject, $message, $headers );
	}
}
add_action( 'wp_ajax_realty_ajax_shortcode_contact_form', 'realty_ajax_shortcode_contact_form' );
add_action( 'wp_ajax_nopriv_realty_ajax_shortcode_contact_form', 'realty_ajax_shortcode_contact_form' );
<?php
/*
 * This is the page users will see logged out.
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
 */
?>
<div class="lwa lwa-default "><?php //class must be here, and if this is a template, class name should be that of template directory ?>
	<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 1em;">
		<li class="active">
			<a href="#tab-login" role="tab" data-toggle="tab"><?php esc_html_e( 'Login', 'realty' ); ?></a>
		</li>
			<?php if ( get_option('users_can_register') ) { ?>
			<li>
			<a href="#tab-registration" role="tab" data-toggle="tab"><?php esc_html_e( 'Register', 'realty' ); ?></a>
		</li>
			<?php } ?>
  	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab-login">
			<form class="lwa-form login" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
				<div class="lwa-form-block">
					<span class="lwa-status"></span>
					<div class="row">
						<div class="col-xs-12">
							<label for="lwa_user_login"><?php _e( 'Username', 'realty' ); ?></label>
							<span class="user">
								<input type="text" name="log" class="input-text" id="lwa_user_login" placeholder="<?php _e( 'Username', 'realty' ); ?>" />
							</span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<label for="password"><?php _e( 'Password', 'realty' ); ?> <span class="required">*</span>
							</label>
							<span class="pass">
								<input type="password" id="password" name="pwd" class="input-text" placeholder="<?php _e( 'Password', 'realty' ); ?>" />
							</span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<input name="rememberme" type="checkbox" class="lwa-rememberme" value="forever" />
							<label class="visible"><?php esc_html_e( 'Remember Me','login-with-ajax' ) ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<input type="submit" name="wp-submit" id="lwa_wp-submit" class="button" value="<?php esc_attr_e('Log In', 'login-with-ajax'); ?>" tabindex="100" />
							<input type="hidden" name="lwa_profile_link" value="<?php echo esc_attr($lwa_data['profile_link']); ?>" />
							<input type="hidden" name="login-with-ajax" value="login" />
							<?php if( !empty($lwa_data['redirect']) ): ?>
							<input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="lost_password">
		           	<?php if( !empty($lwa_data['remember']) ): ?>
							<a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" title="<?php esc_attr_e('Password Lost and Found','login-with-ajax') ?>"><?php esc_attr_e('Lost your password?','login-with-ajax') ?></a>
							<?php endif; ?>
		           </div>
				</div>
			</form>
			<?php if( !empty($lwa_data['remember']) && $lwa_data['remember'] == 1 ): ?>
	        <form class="lwa-remember" action="<?php echo esc_attr(LoginWithAjax::$url_remember) ?>" method="post" style="display: none;">
				<div>
					<span class="lwa-status"></span>
					<table>
						<tr>
							<td>
								<strong><?php esc_html_e("Forgotten Password", 'login-with-ajax'); ?></strong>
							</td>
						</tr>
						<tr>
							<td class="lwa-remember-email">  
	                        <?php $msg = __("Enter username", 'login-with-ajax'); ?>
	                        <input type="text" name="user_login" class="lwa-user-remember" value="<?php echo esc_attr($msg); ?>" onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}" />
							</td>
						</tr>
						<tr>
							<td class="lwa-remember-buttons">
								<input type="submit" value="<?php esc_attr_e("Get New Password", 'login-with-ajax'); ?>" class="lwa-button-remember" />
								<a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel", 'login-with-ajax'); ?></a>
								<input type="hidden" name="login-with-ajax" value="remember" />
							</td>
						</tr>
					</table>
				</div>
			</form>
        <?php endif; ?>
		</div>
		
		<?php if( get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1 ): ?>
		<div class="tab-pane lwa-register lwa-register-default" id="tab-registration">
			<form class="lwa-register-form register" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post">
				<div class="lwa-status"></div>
				<?php do_action('lwa_register_form');?>
				<input type="hidden" name="login-with-ajax" value="register" />
			</form>
		</div>
	<?php endif; ?>
	</div>
</div>
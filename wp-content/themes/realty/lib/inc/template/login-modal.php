<?php
	global $realty_theme_option;
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
?>

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
  <div class="modal-dialog login-modal-content">
    <div class="modal-content">

      <div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="screen-reader-text"><?php esc_html_e( 'Close', 'realty' ); ?></span></button>
      </div>

      <div class="modal-body">
				<?php echo tt_login_form(); ?>
      </div>

    </div>
  </div>
</div>
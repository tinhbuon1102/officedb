<form class="coupon-form">

	<p>
		<?php echo esc_html__( 'One coupon, many benefits. Add your coupon code and enjoy special features.', 'tp-image-optimizer' ) ?> 
	</p>

	<div class="result_alert"></div>
	<input type="text" class="widefat" value="" name="coupon_code" placeholder="<?php echo esc_attr__( 'Coupon code', 'tp-image-optimizer' ) ?>"/>
	<button class="button button-secondary" type="button"><?php echo esc_html__( 'Apply coupon', 'tp-image-optimizer' ) ?></button>
	<input type="hidden" name="action" value="tpio_verify_coupon"/>
	<?php wp_nonce_field( 'tpio_verify_coupon', '_coupon_nonce' ) ?>
	
</form>
<div class='io-setting-api io-setting-wrapper'>
		<label><?php echo esc_html__( "Select compress quality: ", 'tp-image-optimizer' ); ?><span class='faq-i faq-quality'></span></label>
		
    <select id="io-compress-level" name="tp_image_optimizer_compress_level">
		<?php foreach ( $option as $key => $item ) {
			?>
			<option value="<?php echo esc_html( $key ); ?>" <?php if ( $compress == $key ) : ?> selected="selected" <?php endif; ?>><?php echo esc_html( $item ); ?></option>
		<?php };
		?>
    </select>
<div class="result_alert"></div>

<?php echo wp_nonce_field( "api_nonce_key", 'api-check-key' ) ?>

<?php submit_button( "Update", "button-secondary update-api-btn", "update-api", false, array( 'type'=>'submit' ) ); ?>
</div>
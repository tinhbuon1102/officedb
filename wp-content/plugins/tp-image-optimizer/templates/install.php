<div id='tp-image-optimizer' class="tp-image-optimizer io-detail-page wrap" data-total='<?php echo esc_html( $total_image ); ?>'>
    <h1 class="wp-heading-inline"></h1>


	<div class="tp-panel">

		<div class="tp-panel__heading">
			<div class="tp-panel__logo"></div>
			<h2 class="tp-panel__title"><?php echo esc_html( "Welcome to TP Image Optimizer! ", "tp-image-optimizer" ); ?></h2>
		</div>
		
		<div class="tp-panel__progress-bar">
			<div class="progress_wrap">
				<div class="progress">
					<div class="progress-bar">
						<span class="progress-percent">0%</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class='tp-panel__content io-install'>
			<div class='io-load-image-bar'>
				<div class='accept_panel'>
					
					<div class='feature'>
						<h4><?php echo esc_html( "During installation, the plugin will:", "tp-image-optimizer" ); ?></h4>
						<ul>
							<li><?php echo esc_html( "1. Get a free token key.", "tp-image-optimizer" ); ?></li>
							<li><?php echo esc_html( "2. Basic image optimizing options are auto-selected, you can change them after the installation is completed.", "tp-image-optimizer" ); ?></li>
							<li><?php echo esc_html( "3. Add all image data in the Media to the pending list for optimizing.", "tp-image-optimizer" ); ?></li>
						</ul>
					</div>
					
					<button type="submit" name="accept-install" id="accept-install" class="button-custom"><?php echo esc_html( "Get Started", "tp-image-optimizer" ); ?></button>
					<div class='install-required io_alert io_alert--error'>
						<?php echo esc_html( "Oops!!! Internet Connection is required to get the token key of our service.", "tp-image-optimizer" ); ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

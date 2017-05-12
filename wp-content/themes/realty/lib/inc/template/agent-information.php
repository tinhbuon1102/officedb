<section class="agent border-box content-box">
	<div class="row">

		<div class="col-md-5 col-lg-4">
		  <?php	if ( $profile_image ) { ?>
		  	<?php
				  $profile_image_id = tt_get_image_id( $profile_image );
		      $profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
		    ?>
		    <img src="<?php echo esc_url( $profile_image_array[0] ); ?>" />

	      <?php if ( $realty_theme_option['show-agent-social-networks'] ) { ?>
					<div class="social-transparent">
					<?php if ( $facebook ) { ?>
				      <a href="<?php echo esc_attr( $facebook ); ?>" target="_blank"><i class="icon-facebook"></i></a>
				   <?php } ?>
			     <?php if ( $twitter ) { ?>
				    	<a href="<?php echo esc_attr( $twitter ); ?>" target="_blank"><i class="icon-twitter"></i></a>
				   <?php } ?>
			     <?php if ( $google ) { ?>
				    	<a href="<?php echo esc_attr( $google ); ?>" target="_blank"><i class="icon-google-plus"></i></a>
				   <?php } ?>
			     <?php if ( $linkedin ) { ?>
				    	<a href="<?php echo esc_attr( $linkedin ); ?>" target="_blank"><i class="icon-linkedin"></i></a>
				   <?php } ?>
			      </div>
				<?php } ?>

		  </div>

	    <div class="col-md-7 col-lg-8">
			<?php } else { ?>
		  	<div class="col-sm-12">
		  <?php } ?>

				<div class="agent-details" style="padding:0">
				  <?php if ( $first_name && $last_name ) { ?>
						<h2 class="title"><?php echo $first_name . ' ' . $last_name; ?></h2>
						<?php if ( $company_name ) { ?>
							<p class="company-name"><?php echo $company_name; ?></p>
						<?php } ?>
					<?php } else if ( $company_name ) { ?>
						<h2 class="title"><?php echo $company_name; ?></h2>
					<?php } ?>

					<?php if ( $email && $realty_theme_option['show-agent-email'] ) { ?>
						<div class="contact">
							<i class="icon-email"></i><a href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a>
						</div>
					<?php } ?>
					<?php if ( $office && $realty_theme_option['show-agent-office'] ) { ?>
						<div class="contact">
							<i class="icon-phone"></i><?php echo $office; ?>
						</div>
					<?php } ?>
					<?php if ( $mobile && $realty_theme_option['show-agent-mobile'] ) { ?>
						<div class="contact">
							<i class="icon-mobile"></i><?php echo $mobile; ?>
						</div>
					<?php } ?>
					<?php if ( $fax && $realty_theme_option['show-agent-fax'] ) { ?>
						<div class="contact">
							<i class="icon-fax"></i><?php echo $fax; ?>
						</div>
					<?php } ?>
					<?php if ( $website && $realty_theme_option['show-agent-website'] ) { ?>
						<div class="contact">
							<i class="icon-globe"></i><a href="<?php echo $website; ?>" target="_blank"><?php echo $website_clean; ?></a>
						</div>
					<?php } ?>

				  <div class="description">
				  	<?php if ( $bio ) { ?>
							<?php if ( ! is_author() ) { ?>
						  	<?php $trim = wp_trim_words( $bio, 40, '..' ); ?>
						  	<p><?php echo $trim; ?></p>
						  <?php } else { ?>
						  	<p><?php echo $bio; ?></p>
						  <?php } ?>
						<?php } ?>
				  </div>

				  <?php if ( ! is_author() ) { ?>
				  	<div class="agent-more-link">
					  	<a href="<?php echo $author_profile_url; ?>" class="btn btn-primary" rel="author"><?php esc_html_e( 'My Account', 'realty' ); ?></a>
					  </div>
					<?php } ?>
				</div>

			</div>

	 </div>
</section>
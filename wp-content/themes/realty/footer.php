</div><!-- #content -->

<?php
	if ( is_page_template( 'template-map-vertical.php' ) ) {
		$show_footer = false;
	} else {
		$show_footer = true;
	}
	$hide_footer_widgets = get_post_meta( get_the_id(), 'estate_page_hide_footer_widgets', true );
?>

<?php if ( $show_footer && ! $hide_footer_widgets ) { ?>

	<footer class="site-footer" id="footer">

		<?php if ( is_active_sidebar( 'sidebar_footer_1' ) || is_active_sidebar( 'sidebar_footer_2' ) || is_active_sidebar( 'sidebar_footer_3' ) ) { ?>
      <div class="site-footer-top" id="footer-top">
        <div class="container">
          <div class="row">
          <?php
	          // Check for Footer Column 1
	          if ( is_active_sidebar( 'sidebar_footer_1' ) ) {
	            echo '<div class="col-sm-4"><ul class="list-unstyled">';
	            dynamic_sidebar( 'sidebar_footer_1' );
	            echo '</ul></div>';
	          }
	          // Check for Footer Column 2
	          if ( is_active_sidebar( 'sidebar_footer_2' ) ) {
	            echo '<div class="col-sm-4"><ul class="list-unstyled">';
	            dynamic_sidebar( 'sidebar_footer_2' );
	            echo '</ul></div>';
	          }
	          // Check for Footer Column 3
	          if ( is_active_sidebar( 'sidebar_footer_3' ) ) {
	            echo '<div class="col-sm-4"><ul class="list-unstyled">';
	            dynamic_sidebar( 'sidebar_footer_3' );
	            echo '</ul></div>';
	          }
          ?>
          </div>
        </div>
      </div>
		<?php } ?>

		<?php if ( is_active_sidebar( 'sidebar_footer_bottom_left' ) || is_active_sidebar( 'sidebar_footer_bottom_center' ) || is_active_sidebar( 'sidebar_footer_bottom_right' ) ) { ?>
			<div class="site-footer-bottom" id="footer-bottom">
				<div class="container">
					<div class="row">
						<?php
							$class_columns_left = 'col-sm-12';
							$class_columns_center = 'col-sm-12';
							$class_columns_right = 'col-sm-12';

							if ( ! is_active_sidebar( 'sidebar_footer_bottom_center' ) && ( is_active_sidebar( 'sidebar_footer_bottom_left' ) || is_active_sidebar( 'sidebar_footer_bottom_right' ) ) ) {
								$class_columns_left = 'col-sm-6';
								$class_columns_right = 'col-sm-6';
							}

							if ( is_active_sidebar( 'sidebar_footer_bottom_left' ) && is_active_sidebar( 'sidebar_footer_bottom_center' ) && is_active_sidebar( 'sidebar_footer_bottom_right' ) ) {
								$class_columns_left = 'col-sm-4';
								$class_columns_center = 'col-sm-4';
								$class_columns_right = 'col-sm-4';
							}
						?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_left' ) ) { ?>
							<div class="<?php echo $class_columns_left; ?> footer-bottom-left">
								<?php dynamic_sidebar( 'sidebar_footer_bottom_left' ); ?>
							</div>
						<?php } ?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_center' ) ) { ?>
							<div class="<?php echo $class_columns_center; ?> footer-bottom-center">
								<?php dynamic_sidebar( 'sidebar_footer_bottom_center' ); ?>
							</div>
						<?php } ?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_right' ) ) { ?>
							<div class="<?php echo $class_columns_right; ?> footer-bottom-right">
								<?php dynamic_sidebar( 'sidebar_footer_bottom_right' ); ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>

	</footer>

<?php } ?>

<?php wp_footer(); ?>

</body>
</html>
</div><!-- #content -->
<div id="links" class="common-links bg-ltgray">
	<div class="container">
		<div class="row link-list">
			<div class="col-sm-3"><div class="link-items"><a href="<?php echo esc_url( home_url( '/property-core-section-listing/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links01.jpg"><h4><?php esc_html_e( 'ハイグレードの小さな区画', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="<?php echo esc_url( home_url( '/smart-searching/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links02.jpg"><h4><?php esc_html_e( '条件絞込スマート検索', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="http://www.properties.co.jp/our-archievement/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links03.jpg"><h4><?php esc_html_e( 'オフィス仲介実績', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="http://www.properties.co.jp/category/office-latest-news/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links04.jpg"><h4><?php esc_html_e( 'オフィス情報最前線ブログ', 'realty' ); ?></h4></a></div></div>
		</div>
	</div>
</div>
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
	            echo '<div class="col-sm-3"><ul class="list-unstyled">';
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
	            echo '<div class="col-sm-5"><ul class="list-unstyled">';
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
					<div class="row sm-flex">
						<?php
							$class_columns_left = 'col-sm-12';
							$class_columns_center = 'col-sm-12';
							$class_columns_right = 'col-sm-12';

							if ( ! is_active_sidebar( 'sidebar_footer_bottom_center' ) && ( is_active_sidebar( 'sidebar_footer_bottom_left' ) || is_active_sidebar( 'sidebar_footer_bottom_right' ) ) ) {
								$class_columns_left = 'col-sm-6';
								$class_columns_right = 'col-sm-6';
							}

							if ( is_active_sidebar( 'sidebar_footer_bottom_left' ) && is_active_sidebar( 'sidebar_footer_bottom_center' ) && is_active_sidebar( 'sidebar_footer_bottom_right' ) ) {
								$class_columns_left = 'col-sm-3';
								$class_columns_center = 'col-sm-4';
								$class_columns_right = 'col-sm-5';
							}
						?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_left' ) ) { ?>
							<div class="<?php echo $class_columns_left; ?> footer-bottom-left order2">
								<?php dynamic_sidebar( 'sidebar_footer_bottom_left' ); ?>
							</div>
						<?php } ?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_center' ) ) { ?>
							<div class="<?php echo $class_columns_center; ?> footer-bottom-center">
								<?php dynamic_sidebar( 'sidebar_footer_bottom_center' ); ?>
							</div>
						<?php } ?>

						<?php if ( is_active_sidebar( 'sidebar_footer_bottom_right' ) ) { ?>
							<div class="<?php echo $class_columns_right; ?> footer-bottom-right order1">
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
<div class="sp-fixfooter">
	<div class="weekdays">
		<p class="msg"><?php esc_html_e( '物件の空室確認、内見、お問い合わせなどはお気軽に', 'realty' ); ?></p>
		<div class="btn-wrap">
		<?php
$time = intval(date('H'));
if (9 <= $time && $time <= 18) { // 9時～18時の時間帯のとき ?>
<a href="tel:0354117500" class="button tel-btn"><i class="topicon-icon-topnav03"></i><span>03-5411-7500</span></a>
<?php } else { // それ以外の時間帯のとき ?>
<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="button contact-btn"><i class="howcons-mail-envelope-closed"></i><span><?php esc_html_e( 'Contact us', 'realty' ); ?></span></a>
<?php } ?>
			<p class="openhour"><span class="jat"><?php esc_html_e( '平日', 'realty' ); ?></span>9:00~18:00</p>
		</div>
	</div>
</div>

<div class="modal fade modal-custom" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
				<span class="linericon-cross" aria-hidden="true">X</span>
			</button>
			<div class="modal-header">
				<h4 class="modal-title" ><?php echo __('Login / Register', 'realty')?></h4>
			</div>
			<div class="modal-body">
				<?php login_with_ajax();?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo get_site_url()?>/livechat/php/app.php?widget-init.js"></script>
</body>
</html>
</div><!-- #content -->
<?php if( is_front_page() || is_home() ) { ?>

<?php } else { ?>
<div id="links" class="common-links bg-ltgray">
	<div class="container">
		<div class="row link-list">
			<div class="col-sm-3"><div class="link-items"><a href="<?php echo isEnglish() ? esc_url( home_url( '/property-core-section-listing-en/' ) ) : esc_url( home_url( '/property-core-section-listing/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links01.jpg"><h4><?php esc_html_e( 'ハイグレードの小さな区画', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="<?php echo isEnglish() ? esc_url( home_url( '/smart-searching-en/' ) ) : esc_url( home_url( '/smart-searching/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links02.jpg"><h4><?php esc_html_e( '条件絞込スマート検索', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="http://www.properties.co.jp/our-archievement/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links03.jpg"><h4><?php esc_html_e( 'オフィス仲介実績', 'realty' ); ?></h4></a></div></div>
			<div class="col-sm-3"><div class="link-items"><a href="http://www.properties.co.jp/category/office-latest-news/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/links04.jpg"><h4><?php esc_html_e( 'オフィス情報最前線ブログ', 'realty' ); ?></h4></a></div></div>
		</div>
	</div>
</div>
<?php } ?>


<footer class="site-footer" id="footer">

	<?php if ( is_active_sidebar( 'sidebar_footer_1' ) || is_active_sidebar( 'sidebar_footer_2' ) || is_active_sidebar( 'sidebar_footer_3' ) ) { ?>
      <div class="site-footer-top" id="footer-top">
        <div class="container">
          <div class="row">
          <?php
	          // Check for Footer Column 1
	          if ( is_active_sidebar( 'sidebar_footer_1' ) ) {
	            echo '<div class="col-sm-7"><ul class="list-unstyled">';
	            dynamic_sidebar( 'sidebar_footer_1' );
	            echo '</ul></div>';
	          }
	          // Check for Footer Column 2
	          if ( is_active_sidebar( 'sidebar_footer_2' ) ) {
	            echo '<div class="col-sm-5"><ul class="list-unstyled">';
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
							$class_columns_left = 'col-sm-4';
							$class_columns_center = 'col-sm-4';
							$class_columns_right = 'col-sm-4';
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


<div class="modal fade modal-custom" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close abs-right" data-dismiss="modal" aria-label="Close">
				<span class="linericon-cross" aria-hidden="true">X</span>
			</button>
			<div class="modal-header">
				<h4 class="modal-title" ><?php echo __('Login / Register', 'realty')?></h4>
				<p class="alert alert-info" id="pdf_viewing_message" style="display:none;"><?php echo trans_text('You need to login for seeing PDF')?></p>
			</div>
			<div class="modal-body">
				<?php login_with_ajax();?>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
<div class="sp-fixfooter">
	<div class="weekdays">
		<p class="msg"><?php esc_html_e( '物件の空室確認、内見、お問い合わせなどはお気軽に', 'realty' ); ?></p>
		<div class="btn-wrap">
		<?php
$time = intval(date('H'));
if (9 <= $time && $time <= 18) { // 9時～18時の時間帯のとき ?>
<div class="btn-group">
<span class="btn-item"><a href="tel:0354117500" onclick="goog_report_conversion('tel:03-5411-7500');yahoo_report_conversion(undefined)" class="button tel-btn"><i class="topicon-icon-topnav03"></i><span><?php esc_html_e( 'Call Now', 'realty' ); ?></span></a></span>
<span class="btn-item"><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="button contact-btn"><i class="fa fa-envelope"></i><span><?php esc_html_e( 'Email Now', 'realty' ); ?></span></a></span>
</div>
<?php } else { // それ以外の時間帯のとき ?>
<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="button contact-btn"><i class="howcons-mail-envelope-closed"></i><span><?php esc_html_e( 'Contact us', 'realty' ); ?></span></a>
<?php } ?>
			<p class="openhour"><span class="jat"><?php esc_html_e( '平日', 'realty' ); ?></span>9:00~18:00</p>
		</div>
	</div>
</div>
</div><!--/viewwrap-->

<?php 
if (strpos($_SERVER['REQUEST_URI'], 'contact-us') !== false)
{
	$thankUrl = home_url() . (isEnglish() ? '/thank-you-2/' : '/thank-you/'); 
}
elseif (strpos($_SERVER['REQUEST_URI'], 'smart-searching') !== false)
{
	$thankUrl = home_url() . (isEnglish() ? '/thank-you-for-your-request/' : '/thankyou-smart/'); 
}
if (isset($thankUrl) && $thankUrl)
{
	?>
	<script>
	document.addEventListener( 'wpcf7mailsent', function( event ) {
	    location = '<?php echo $thankUrl?>';
	}, false );
	</script>
<?php 
}

?>

<script type="text/javascript">
function addingScript(url){
	var s = document.createElement("script");
	s.type = "text/javascript";
	s.src = url;
	jQuery("head").append(s);
}
setTimeout(function(){
	addingScript("https://use.typekit.net/sjj8vip.js");
	try{Typekit.load({ async: true });}catch(e){}
}, 6000)
</script>

<script src="https://use.fontawesome.com/d543855e1a.js"></script>
<link href="https://fonts.googleapis.com/css?family=Arapey|Old+Standard+TT" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=GFS+Didot" rel="stylesheet">

<script type="text/javascript" src="<?php echo get_site_url()?>/livechat/php/app.php?widget-init.js&lang=<?php echo pll_current_language()?>"></script>

<!--Start of Google_コールタップ-->
<script type="text/javascript">

  /* <![CDATA[ */

  goog_snippet_vars = function() {

    var w = window;

    w.google_conversion_id = 988279235;

    w.google_conversion_label = "QDNICOCQ-1kQw-Of1wM";

    w.google_remarketing_only = false;

  }

  // DO NOT CHANGE THE CODE BELOW.

  goog_report_conversion = function(url) {

    goog_snippet_vars();

    window.google_conversion_format = "3";

    var opt = new Object();

    opt.onload_callback = function() {

    if (typeof(url) != 'undefined') {

      window.location = url;

    }

  }

  var conv_handler = window['google_trackConversion'];

  if (typeof(conv_handler) == 'function') {

    conv_handler(opt);

  }

}

/* ]]> */

</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js"></script>
<!--end of Google_コールタップ-->

<!--Start of Yahooコールタップタグ-->
<script type="text/javascript">

    /* <![CDATA[ */

    yahoo_snippet_vars = function() {

        var w = window;

        w.yahoo_conversion_id = 1000085419;

        w.yahoo_conversion_label = "8Ch_CN6t-lkQ-Zbu1AM";

        w.yahoo_conversion_value = 0;

        w.yahoo_remarketing_only = false;

    }

    // IF YOU CHANGE THE CODE BELOW, THIS CONVERSION TAG MAY NOT WORK.

    yahoo_report_conversion = function(url) {

        yahoo_snippet_vars();

        window.yahoo_conversion_format = "3";

        window.yahoo_is_call = true;

        var opt = new Object();

        opt.onload_callback = function() {

            if (typeof(url) != 'undefined') {

                window.location = url;

            }

        }

        var conv_handler = window['yahoo_trackConversion'];

        if (typeof(conv_handler) == 'function') {

            conv_handler(opt);

        }

    }

    /* ]]> */

</script>
<script type="text/javascript" src="https://s.yimg.jp/images/listing/tool/cv/conversion_async.js"></script>
<!--end of Yahooコールタップタグ-->

<!--Start of Yahoo Common Retargeting tags リターゲティングタグ-->
<script type="text/javascript">

/* <![CDATA[ */

var yahoo_ss_retargeting_id = 1000085419;

var yahoo_sstag_custom_params = window.yahoo_sstag_params;

var yahoo_ss_retargeting = true;

/* ]]> */

</script>
<script type="text/javascript" src="https://s.yimg.jp/images/listing/tool/cv/conversion.js"></script>
<noscript>

<div style="display:inline;">

<img height="1" width="1" style="border-style:none;" alt="" src="https://b97.yahoo.co.jp/pagead/conversion/1000085419/?guid=ON&script=0&disvt=false"/>

</div>

</noscript>
<!--End of Yahoo Common Retargeting tags リターゲティングタグ-->

<!--if contact page お問い合わせ完了-->
<?php if(is_page( array( 5751 ) ) ): ?>
<!-- Start contact page tags Yahoo_問合せ完了タグ -->
<script type="text/javascript">

    /* <![CDATA[ */

    var yahoo_conversion_id = 1000085419;

    var yahoo_conversion_label = "RMKICL-8wwgQ-Zbu1AM";

    var yahoo_conversion_value = 0;

    /* ]]> */

</script>
<script type="text/javascript" src="https://s.yimg.jp/images/listing/tool/cv/conversion.js"></script>
<noscript>
<div style="display:inline;"><img height="1" width="1" style="border-style:none;" alt="" src="https://b91.yahoo.co.jp/pagead/conversion/1000085419/?value=0&label=RMKICL-8wwgQ-Zbu1AM&guid=ON&script=0&disvt=true"/></div>
</noscript>
<!-- End contact page tags Yahoo_問合せ完了タグ -->

<!--if thankyou page 会員登録手続き完了-->
<?php elseif(is_page( array( 22713 ) ) ): ?>
<!-- Start thankyou page tags 会員登録手続き完了タグ -->
<script type="text/javascript">

    /* <![CDATA[ */

    var yahoo_conversion_id = 1000085419;

    var yahoo_conversion_label = "Cv93CKrE9VkQ-Zbu1AM";

    var yahoo_conversion_value = 0;

    /* ]]> */

</script>
<script type="text/javascript" src="https://s.yimg.jp/images/listing/tool/cv/conversion.js"></script>
<noscript>

    <div style="display:inline;">

        <img height="1" width="1" style="border-style:none;" alt="" src="https://b91.yahoo.co.jp/pagead/conversion/1000085419/?value=0&label=Cv93CKrE9VkQ-Zbu1AM&guid=ON&script=0&disvt=true"/>

    </div>

</noscript>
<!-- End thankyou page tags 会員登録手続き完了タグ -->
<?php else: ?>
<?php endif; ?>
</body>
</html>
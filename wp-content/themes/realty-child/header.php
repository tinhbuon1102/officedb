<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K7HLD69');</script>
<!-- End Google Tag Manager -->

<?php 
	if (!is_user_logged_in() && is_plugin_active("wp-fastest-cache/wpFastestCache.php"))
	{
		get_template_part( 'init_style' );
	}
?>
<?php wp_head(); ?>

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/lib/js/html5.js"></script>
<![endif]-->
<script type="text/javascript">
	var message_no_result = '<?php echo trans_text('No result for "{{query}}"')?>';
	var global_lang = '<?php echo pll_current_language()?>';
</script>
  

  <script type="text/javascript">

    jQuery(document).ready(function ($) {
        default_options =
        { 
		    boundList: null,
            fillOpacity: 1,
			isDeselectable:false,
			isLink: true,
			isSelectable:false,
			 clickNavigate: true,
            render_highlight: {
                stroke: true,
				strokeColor: '000000',
                altImage: '<?php echo site_url()?>/wp-content/uploads/2015/10/map_on.png',		  
			},
		};
        $statelist = false;
        
        jQuery('#usa_image').mapster(default_options);
    });
</script>


<!-- Global site tag (gtag.js) - Google AdWords: 988279235 -->

<script async src="https://www.googletagmanager.com/gtag/js?id=AW-988279235"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());



  gtag('config', 'AW-988279235');

</script>
<!--if お問い合わせ完了-->
<?php if(is_page( array( 5751 ) ) ): ?>	
<!-- Event snippet for 問い合わせ(GTM) conversion page -->
<script>

  gtag('event', 'conversion', {'send_to': 'AW-988279235/fbObCI2hxQsQw-Of1wM'});

</script>
<?php elseif(is_page( array( 22713 ) ) ): ?>
<!-- Event snippet for 会員登録 conversion page -->

<script>

  gtag('event', 'conversion', {

      'send_to': 'AW-988279235/ZPIlCNWx2AgQw-Of1wM',

      'value': 1.0,

      'currency': 'JPY'

  });

</script>
<?php else: ?>
<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K7HLD69"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php global $realty_theme_option; ?>
<div class="viewwrap">
<header id="header"<?php if ( $realty_theme_option['header-layout'] == 'default' ) { echo esc_attr( ' class=nav-below' ); } ?>>

	<?php global $realty_theme_option; ?>

	<div class="top-header">
		<div class="container">
			<div class="top-header-sidebar site-desc">
					<?php esc_html_e( '東京都内の厳選された高級オフィス検索サイト', 'realty' ); ?>
				</div>
				<?php if ( is_active_sidebar( 'sidebar_header' ) ) { ?>
				<div class="top-header-sidebar lang-switch">
					<?php dynamic_sidebar( 'sidebar_header' ); ?>
				</div>
			<?php } ?>

			<?php if ( ! $realty_theme_option['disable-header-login-register-bar'] ) { ?>
				<div class="top-header-links primary-tooltips hidden-sm">
					<?php get_template_part( 'lib/inc/template/login-bar-header' ); ?>
				</div>
		  <?php } ?>
		  
		</div>
	</div>
  
<div class="header-navi-wrap">
  <div class="container">
  <div class="inline-container clearfix">
		<div class="site-branding">
			<?php
		  	if ( ! empty( $realty_theme_option['logo-url'] ) ) {
			  	$logo_url = $realty_theme_option['logo-url'];
			  } else {
					$logo_url = home_url( '/' );
				}
			?>

		  <?php if ( is_front_page() && is_home() ) { ?>
					<h1 class="site-title"><a href="<?php echo esc_url( $logo_url ); ?>" rel="home"><?php echo realty_get_the_logo(); ?></a></h1>
			<?php } else { ?>
					<p class="site-title"><a href="<?php echo esc_url( $logo_url ); ?>" rel="home"><?php echo realty_get_the_logo(); ?></a></p>
			<?php } ?>

	    <?php if ( get_bloginfo('description') && $realty_theme_option['header-tagline'] ) { ?>
	    <div class="tagline">
		    <?php echo get_bloginfo('description'); ?>
	    </div>
	    <?php } ?>
	    <?php if ( ! $realty_theme_option['disable-header-login-register-bar'] ) { ?>
				<div class="top-header-links primary-tooltips hidden-pc">
					<?php get_template_part( 'lib/inc/template/login-bar-header' ); ?>
				</div>
		  <?php } ?>
	    
	    <a id="toggle-navigation" class="navbar-togglex" href="#"><i></i></a>
			<div class="mobile-menu-overlay hide"></div>
    </div>

		<nav class="main-navigation" id="navigation">
		<div class="flex-column fill-height">
		<div class="flex-cell side-panel-top-row">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => 'ul',
					'menu_class' => 'primary-menu',
					'link_before' => '<span>',
					'link_after' => '</span>'
				) );
			?>
		</div>
		<div class="flex-cell-auto side-panel-bottom-row hidden-pc">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'sp_second_menu',
					'container' => 'ul',
					'menu_class' => 'sp-second-menu',
					'link_before' => '<span>',
					'link_after' => '</span>'
				) );
			?>
			<div class="ask-staff"><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="btn btn-primary btn-square"><?php esc_html_e( 'Contact us', 'realty' ); ?></a></div>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'sp_bottom_menu',
					'container' => 'ul',
					'menu_class' => 'sp-bottom-menu side-panel-legal-list text-overflow',
					'link_before' => '<span>',
					'link_after' => '</span>'
				) );
			?>
		</div>
		</div>
		</nav>

		
		<div class="calling-info">
			<div class="calling-content">
				<!--<i class="topicon-icon-topnav03"></i>-->
				<div class="calling-desc"><span class="jat"><?php esc_html_e( '平日', 'realty' ); ?></span>9:00~18:00<br/><span><a href="tel:03-5411-7500" >03-5411-7500</a></span></div>
			</div>
		</div>
	</div><!--/inline-container-->
  </div>
  </div>

</header>
   <div class="single-search">
  		<div class="container">
   		<?php 
   		if (is_singular('property'))
   		{
			echo '<div id="singlesearchform">';
   			echo do_shortcode('[property_search_form search_form_columns="3" search_type="mini"]');
			echo '</div>';
   		}
   		?>
   		</div>
   </div>
    <?php
	   if(function_exists('bcn_display') && !is_front_page()) {
		   echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
		   echo '<div class="container">';
		   bcn_display();
		   echo '</div>';
		   echo '</div>';
    }?>

<div id="content">
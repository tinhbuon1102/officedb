<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/lib/js/html5.js"></script>
<![endif]-->
<script src="https://use.fontawesome.com/d543855e1a.js"></script>
<script src="https://use.typekit.net/sjj8vip.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>
<link href="https://fonts.googleapis.com/css?family=Arapey|Old+Standard+TT" rel="stylesheet">
<link href="//db.onlinewebfonts.com/c/6272dc913454bb6ac46143176180c0fd?family=DidotW01-Italic" rel="stylesheet" type="text/css">
<script type="text/javascript">
	var message_no_result = '<?php echo trans_text('No result for "{{query}}"')?>';
</script>
</head>

<body <?php body_class(); ?>>

<?php global $realty_theme_option; ?>

<header id="header"<?php if ( $realty_theme_option['header-layout'] == 'default' ) { echo esc_attr( ' class=nav-below' ); } ?>>

	<?php global $realty_theme_option; ?>

	<div class="top-header">
		<div class="container">
			<?php if ( is_active_sidebar( 'sidebar_header' ) ) { ?>
				<div class="top-header-sidebar lang-switch">
					<?php dynamic_sidebar( 'sidebar_header' ); ?>
				</div>
			<?php } ?>

			<?php if ( ! $realty_theme_option['disable-header-login-register-bar'] ) { ?>
				<div class="top-header-links primary-tooltips">
					<?php get_template_part( 'lib/inc/template/login-bar-header' ); ?>
				</div>
		  <?php } ?>
		</div>
	</div>

  <div class="container">
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
	    <a id="toggle-navigation" class="navbar-togglex" href="#"><i></i></a>
			<div class="mobile-menu-overlay hide"></div>
    </div>

		<nav class="main-navigation" id="navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container' => 'ul',
					'menu_class' => 'primary-menu',
					'link_before' => '<span>',
					'link_after' => '</span>'
				) );
			?>
		</nav>
		
		<div class="calling-info">
			<div class="calling-content">
				<i class="topicon-icon-topnav03"></i>
				<div class="calling-desc"><span class="jat">平日</span>9:00~18:00<br/><span><a href="tel:03-5411-7500">03-5411-7500</a></span></div>
			</div>
		</div>
  </div>

</header>
    <?php
	   if(function_exists('bcn_display') && !is_front_page()) {
		   echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">';
		   echo '<div class="container">';
		   bcn_display();
		   echo '</div>';
		   echo '</div>';
    }?>

<div id="content">
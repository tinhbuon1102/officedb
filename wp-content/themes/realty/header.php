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

</head>

<body <?php body_class(); ?>>

<?php global $realty_theme_option; ?>

<header id="header"<?php if ( $realty_theme_option['header-layout'] == 'default' ) { echo esc_attr( ' class=nav-below' ); } ?>>

	<?php global $realty_theme_option; ?>

	<div class="top-header">
		<div class="container">
			<?php if ( is_active_sidebar( 'sidebar_header' ) ) { ?>
				<div class="top-header-sidebar">
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
				) );
			?>
		</nav>
  </div>

</header>

<div id="content">
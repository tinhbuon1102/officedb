<?php
if ( wp_basename( get_page_template() ) == 'template-user-profile.php' ) {
	$profile_active = ' class="active"';
} else {
	$profile_active = null;
}

if ( wp_basename( get_page_template() ) == 'template-property-submit-listing.php' ) {
	$property_submit_listing_active = ' class="active"';
} else {
	$property_submit_listing_active = null;
}

if ( wp_basename( get_page_template() ) == 'template-property-submit.php' ) {
	$property_submit_active = ' class="active"';
} else {
	$property_submit_active = null;
}

if ( wp_basename( get_page_template() ) == 'template-user-favorites.php' ) {
	$favorites_active = ' class="active"';
} else {
	$favorites_active = null;
}
?>

<ul class="widget-user-menu border-box list-unstyled">
	<li<?php echo $profile_active; ?>><a href="<?php echo get_permalink( tt_page_id_user_profile() ); ?>"><i class="icon-account"></i> <?php _e( 'My Account', 'realty' ); ?></a></li>
	<li<?php echo $favorites_active; ?>><a href="<?php echo get_permalink( tt_page_id_user_favorites() ); ?>"><i class="fa fa-star"></i> <?php _e( 'Favorites', 'realty' ); ?></a></li>
	<li><a href="<?php echo wp_logout_url( site_url('/') ); ?>"><i class="icon-logout"></i> <?php _e( 'Logout', 'realty' ); ?></a></li>
</ul>
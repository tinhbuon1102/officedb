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

if ( wp_basename( get_page_template() ) == 'template-user-follow.php' ) {
	$follow_active = ' class="active"';
} else {
	$follow_active = null;
}
$user_id = get_current_user_id();
global $number_of_favorites, $number_of_follows;
?>

<ul class="widget-user-menu border-box list-unstyled">
	<li<?php echo $profile_active; ?>><a href="<?php echo get_permalink( tt_page_id_user_profile() ); ?>"><i class="icon-account"></i> <?php _e( 'My Account', 'realty' ); ?></a></li>
	<li<?php echo $favorites_active; ?>><a href="<?php echo get_permalink( tt_page_id_user_favorites() ); ?>"><i class="fa fa-star"></i> <?php _e( 'Favorites', 'realty' ); ?> (<?php echo $number_of_favorites ?>)</a></li>
	<li<?php echo $follow_active; ?>><a href="<?php echo get_permalink( tt_page_id_user_follow() ); ?>"><i class="fa fa-star"></i> <?php _e( 'Subscribed properties', 'realty' ); ?> (<?php echo $number_of_follows ?>)</a></li>
	<li><a href="<?php echo wp_logout_url( site_url('/') ); ?>"><i class="icon-logout"></i> <?php _e( 'Logout', 'realty' ); ?></a></li>
</ul>


<?php 
$user = get_user_by('ID', $user_id);
if (in_array('customer', $user->roles))
{
?>
<a href="javascript:void(0)" id="delete_user" data-user-id="<?php echo $user_id?>"><i class="icon-close"></i> <?php _e( 'Unregister Membership', 'realty' ); ?></a>
<?php 
}?>
<?php
	global $realty_theme_option, $current_user;

	if ( $realty_theme_option['header-login-modal-layout'] == 'login-page' ) {
		$login_url = get_permalink( tt_page_id_user_login() );
	} else {
		$login_url = '#login-modal';
	}
	
	$tableFloors = get_contact_property_list();
?>

<?php if ( ! is_user_logged_in() ) { ?>

	<?php if ( $realty_theme_option['property-favorites-temporary'] && ! $realty_theme_option['property-favorites-disabled'] ) { ?>
		<a href="<?php echo get_permalink( tt_page_id_user_favorites() ); ?>">
			<span class="desktop"><i class="fa fa-star" aria-hidden="true"></i><?php esc_html_e( 'Favorites', 'realty' ); ?></span></span>
			<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr( 'Favorites', 'realty' ); ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
		</a>
	<?php } ?>

	<?php if ( ! $realty_theme_option['site-header-hide-property-submit-link'] ) { ?>
		<a href="<?php echo get_permalink( tt_page_id_property_submit() ); ?>" data-toggle="modal"><?php esc_html_e( 'Submit Property', 'realty' ); ?></a>
	<?php } ?>

	<a href="<?php echo esc_attr( $login_url ); ?>" data-toggle="modal"><?php if ( get_option('users_can_register') ) { esc_html_e( 'Login/Register', 'realty' ); } else { esc_html_e( 'Login', 'realty' ); } ?></a>

<?php } else { // Logged-In User ?>

	<?php
		$current_user = wp_get_current_user();
		$current_user_role = $current_user->roles[0];

		$user_id = get_current_user_id();

		$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false );

		$number_of_favorites = 0;

		if ( $get_user_meta_favorites ) {
			foreach ( $get_user_meta_favorites[0] as $favorite ) {
				if ( get_post_status( $favorite ) == 'publish' ) {
					$number_of_favorites++;
				}
			}
		}
	?>

	<?php if ( ! $realty_theme_option['property-favorites-disabled'] ) { ?>

	<a href="<?php echo isEnglish() ? site_url('favorite-properties') : site_url('favorites'); ?>">
		<span class="desktop"><i class="fa fa-star" aria-hidden="true"></i><?php esc_html_e( 'Favorites', 'realty' ); ?> (<span><?php echo $number_of_favorites; ?></span>)</span>
		<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Favorites', 'realty' ); ?>"><i class="fa fa-star" aria-hidden="true"></i></span>
	</a>
	<?php } ?>

	<?php if ( ! $realty_theme_option['site-header-hide-property-submit-link'] ) { ?>

		<?php if ( $current_user_role != 'subscriber' || ! $realty_theme_option['property-submit-disabled-for-subscriber'] ) { ?>
			<a href="<?php echo get_permalink( tt_page_id_property_submit() ); ?>">
				<span class="desktop"><?php echo esc_html_e( 'Submit Property', 'realty' ); ?></span>
				<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Submit Property', 'realty' ); ?>"><i class="icon-pen"></i></span>
			</a>
		<?php } ?>

	<?php } ?>

	<?php
		// Check if subscriber has any pending/published properties
		$user_published_properties_args = array(
			'post_type' 				=> 'property',
			'posts_per_page' 		=> 1,
			'author' 						=> $current_user->ID,
			'post_status'				=> array( 'publish', 'pending' )
		);
		$query_user_published_properties = new WP_Query( $user_published_properties_args );
	?>

	<?php if ( $current_user_role != 'subscriber' || $query_user_published_properties->have_posts() ) : ?>
		
	<?php endif; ?>

	<?php wp_reset_query();	?>

	<a class="contact-list-header" href="#contact-multiple-modal" data-toggle="modal">
		<span class="desktop"><i class="<?php echo CONTACT_ICON_EXIST?>"></i><?php esc_html_e( 'Contact List', 'realty' ); ?> (<span class="contact-list-count"><?php echo count($tableFloors); ?></span>)</span>
		<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Contact List', 'realty' ); ?>"><i class="<?php echo CONTACT_ICON_EXIST?>"></i></span>
	</a>
	
	<a href="<?php echo isEnglish() ? site_url('myaccount-en') : site_url('myaccount'); ?>" class="hidden-xs">
		<span class="desktop"><i class="fa fa-user-circle" aria-hidden="true"></i><?php esc_html_e( 'My Account', 'realty' ); ?></span>
		<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php //esc_html_e( 'My Account', 'realty' ); ?>"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
	</a>
	<a href="<?php echo wp_logout_url( site_url('/') ); ?>" class="hidden-xs">
		<span class="desktop"><i class="fa fa-sign-out" aria-hidden="true"></i><?php esc_html_e( 'Logout', 'realty' ); ?></span>
		<span class="mobile" data-toggle="tooltip" data-placement="bottom" title="<?php esc_html_e( 'Logout', 'realty' ); ?>"><i class="fa fa-sign-out" aria-hidden="true"></i></span>
	</a>

<?php } ?>
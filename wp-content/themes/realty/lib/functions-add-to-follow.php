<?php
/**
 * AJAX - Follow
 *
 */
if ( ! function_exists('tt_ajax_add_remove_follow') ) {
	function tt_ajax_add_remove_follow() {
		global $wpdb;

		$user_id = $_GET['user'];
		$property_id = $_GET['property'];

		// Get follow Meta Data
		$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()

		$property = get_post($property_id);
		
		$building_id = substr($property->pinged, strlen(FLOOOR_BUILDING_PARENT));
			
		// Get all same building floor with chosen language
		$querystr = "SELECT p.ID
		FROM $wpdb->posts p
		INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
		WHERE
		pm.meta_key = 'jpdb_floor_building_id_".pll_current_language()."'
			AND pm.meta_value=".(int)$building_id."
			AND p.post_type='property' AND p.pinged = ".(int)$property->pinged." AND p.ID != " . (int)$property_id;
			
		$aProperties = $wpdb->get_results($querystr, OBJECT);
			
		// Remove same building floor favorite before add or remove
		foreach ($aProperties as $oProperty){
			if ($oProperty->ID != $property_id && isset($get_user_meta_follow[0]))
			{
				$removeFavoriteFromPosition = array_search( $oProperty->ID, $get_user_meta_follow[0] );
				if ($removeFavoriteFromPosition !== false)
				{
					unset($get_user_meta_follow[0][$removeFavoriteFromPosition]);
				}
			}
		}
		array_unshift($aProperties, $property);
		
		// No User Meta Data follow Found -> Add Data
		// Add New Follow
		if ( !$get_user_meta_follow[0] || ! in_array( $property_id, $get_user_meta_follow[0] ) ) {
			foreach ($aProperties as $oProperty)
			{
				$property_id = $oProperty->ID;
				array_unshift( $get_user_meta_follow[0], $property_id ); // Add To Beginning Of follow Array
			}
		}
		// Remove Follow
		else {
			foreach ($aProperties as $oProperty)
			{
				$removeFollowFromPosition = array_search( $property_id, $get_user_meta_follow[0] );
				unset( $get_user_meta_follow[0][$removeFollowFromPosition] );
			}
		}
		
		update_user_meta( $user_id, 'realty_user_follow', $get_user_meta_follow[0] );
		
		die('processed');

	}
}
add_action('wp_ajax_tt_ajax_add_remove_follow', 'tt_ajax_add_remove_follow');

/**
 * Follow - Click
 *
 */
if ( ! function_exists( 'tt_add_remove_follow' ) ) {
	function tt_add_remove_follow( $property_id = 0 ) {

		global $realty_theme_option;

		if ( $realty_theme_option['property-follow-disabled'] )
		return;

		if ( ! $property_id ) {
			$property_id = get_the_ID();
		}

		// Logged-In User
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$get_user_meta_follow = get_user_meta( $user_id, 'realty_user_follow', false ); // false = array()

			if ( ! empty( $get_user_meta_follow ) && in_array( $property_id, $get_user_meta_follow[0] ) ) {
				// Follow: true
				$favicon = '<i class="add-to-follow icon-email-1" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Unsubscribe From Email Updates', 'realty' ) . '"></i>';
			} else {
				// Follow: false
				$favicon = '<i class="add-to-follow icon-email" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Subscribe To Email Updates', 'realty' ) . '"></i>';
			}
		} else {
			// Not Logged-In Visitor
			$favicon = '<i class="add-to-follow icon-email" data-fol-id="' . $property_id . '" data-toggle="tooltip" title="' . esc_html__( 'Subscribe To Email Updates', 'realty' ) . '"></i>';
		}
		return $favicon;

	}
}

/**
 * Follow - Script
 *
 */
if ( ! function_exists( 'tt_follow_script' ) ) {
	function tt_follow_script() {

		global $realty_theme_option;
		?>

		<script>
		jQuery('body').on("click",'.add-to-follow',function() {

			<?php if ( is_user_logged_in() ) { // Logged-In User ?>

					// Toggle Follow Tooltips
					if ( jQuery(this).hasClass('icon-email') ) {
						jQuery(this).attr('data-original-title', '<?php esc_html_e( 'Unsubscribe From Email Updates', 'realty' ); ?>');
					}

					if ( jQuery(this).hasClass('icon-email-1') ) {
						jQuery(this).attr('data-original-title', '<?php esc_html_e( 'Subscribe To Email Updates', 'realty' ); ?>');
					}

					if (jQuery('#single_subscribe_text').length)
						jQuery('#single_subscribe_text').text(jQuery(this).attr('data-original-title'));

					jQuery(this).find('i').toggleClass('icon-email icon-email-1');
					jQuery(this).closest('i').toggleClass('icon-email icon-email-1');

					<?php if ( is_user_logged_in() ) { ?>
						<?php $user_id = get_current_user_id();	?>
						jQuery.ajax({
						  type: 'GET',
						  url: ajax_object.ajax_url,
						  data: {
						    'action'        :   'tt_ajax_add_remove_follow', // WP Function
						    'user'					: 	<?php echo $user_id; ?>,
						    'property'			: 	jQuery(this).attr('data-fol-id')
						  },
						  success: function (response) { },
						  error: function () { }
						});
					<?php	} ?>

				<?php } else { // Not Logged-In Visitor - Show Modal ?>
					jQuery('a[href="#tab-login"]').tab('show');
					jQuery('#login-modal').modal();
					jQuery('#msg-login-to-add-follow').removeClass('hide');
					jQuery('#msg-login-to-add-follow').addClass('hide');
				<?php } ?>

			});
		</script>

	<?php
	}
}
add_action( 'wp_footer', 'tt_follow_script', 21 );


/**
 * Sent Message when property is updated.
 *
 */
if ( ! function_exists( 'tt_property_updated_send_email' ) ) {
	function tt_property_updated_send_email( $post_id ) {

		// If this is just a revision, don't send the email.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		global $wpdb, $post;
		$users = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_value LIKE '%\"". $post_id ."\"%' AND meta_key = 'realty_user_follow' GROUP BY user_id");
		
		foreach ( $users as $user ) {
			$user = get_user_by('ID', $user->user_id);
			$post = get_post($post_id);
			
			$post_title = get_the_title();
			$post_url = get_permalink();
			
			
			// Get Floor Content
			$building = getBuilding($post_id);
			$floor = getFloor($post_id);
			
			$styleTable = 'table-layout: fixed;width: 100%;word-break: break-all;border-collapse: collapse;border-left: 1px solid #e5e5e5;border-right: 1px solid #e5e5e5;';
			$styleTdTh = 'text-align: left;font-weight: normal;border-bottom: 1px solid #e5e5e5;padding: 8px 10px;font-size: 13px;-moz-osx-font-smoothing: greyscale;-webkit-font-smoothing: antialiased;';
			$styleTh = 'font-size: 16px;background: #f5f5f5;width: 30%';
			$styleTd = '';
			$floorContent = 
				'<table width="100%" border="0" style="'.$styleTable.'">
					<tr>
						<th style="'.$styleTdTh.$styleTh.';border-top: 1px solid #e5e5e5;">'. trans_text('Vacancy') .'</th>
						<td style="'.$styleTdTh.$styleTd.';border-top: 1px solid #e5e5e5;">'. translateBuildingValue('vacancy_info', $building, $floor, $post_id) .'</td>
					</tr>	
					<tr>
						<th style="'.$styleTdTh.$styleTh.'">'. trans_text('Area') .'</th>
						<td style="'.$styleTdTh.$styleTd.'">'. translateBuildingValue('area_ping', $building, $floor, $post_id) .'</td>
					</tr>
					<tr>
						<th style="'.$styleTdTh.$styleTh.'">'. trans_text('Rent') .'</th>
						<td style="'.$styleTdTh.$styleTd.'">'. translateBuildingValue('rent_unit_price_opt', $building, $floor, $post_id) .'</td>
					</tr>
					<tr>
						<th style="'.$styleTdTh.$styleTh.'">'. trans_text('Common service') .'</th>
						<td style="'.$styleTdTh.$styleTd.'">'. translateBuildingValue('unit_condo_fee_opt', $building, $floor, $post_id) .'</td>
					</tr>
					<tr>
						<th style="'.$styleTdTh.$styleTh.'">'. trans_text('Total deposit') .'</th>
						<td style="'.$styleTdTh.$styleTd.'">'. renderPrice($floor['total_deposit']) .'</td>
					</tr>
					<tr>
						<th style="'.$styleTdTh.$styleTh.'">'. trans_text('Date of occupancy') .'</th>
						<td style="'.$styleTdTh.$styleTd.'">'. translateBuildingValue('move_in_date', $building, $floor, $post_id) .'</td>
					</tr>
				</table>';
			
			$subject = esc_html__('A property that you follow has been updated | Premium Office Search', 'realty');
			$message = '<p>'. trans_text('Below floor has been updated.') .'</p>';
			
			$message .= '<h2 style="margin-bottom: 0.5em">' . $post_title . '</h2>';
			
			if ( has_post_thumbnail() )
			{
				$message .= '<a href="' . $post_url . '">' . wp_get_attachment_image(get_post_thumbnail_id(), 'thumbnail') . '</a>';
			}
			
			$message .= '<p>'. trans_text('Property Page:') .'<a href="' . $post_url . '">' . $post_url . '</a></p>';
			$message .= $floorContent;
			$message .= '<div style="height:1px; margin: 1em 0; background-color:#eee"></div>';
			$message .= '<p style="color: #999">' . esc_html__('To unsubscribe from update notifications about this property please follow the link above, then click the envelope icon next to the property title.', 'realty') . '</p>';
			
			$headers[] = "From: Premium Office Search | 高級オフィス検索 <".get_option('admin_email').">";
			$headers[] = "Content-Type: text/html; charset=UTF-8";
			add_filter('wp_mail_content_type', 'tt_set_html_content_type_plugin');
			
			// Send email to user.
			wp_mail( $user->user_email, $subject, $message, $headers );
			
			remove_filter('wp_mail_content_type', 'tt_set_html_content_type_plugin');
		}

	}
}
// add_action( 'save_post', 'tt_property_updated_send_email' );

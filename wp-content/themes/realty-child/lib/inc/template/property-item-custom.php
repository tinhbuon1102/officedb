<?php
global $realty_theme_option;

if ( ! isset( $property_id ) || empty( $property_id ) ) {
	$property_id = $post->ID;
}

$property_type = get_the_terms( $property_id, 'property-type' );
$property_status = get_the_terms( $property_id, 'property-status' );
$property_location = get_the_terms( $property_id, 'property-location' );
$property_featured = get_post_meta( $property_id, 'estate_property_featured', true );
$property_status_update = get_post_meta( $property_id, 'estate_property_status_update', true );
$estate_property_station = get_post_meta( $property_id, 'estate_property_station', true );
$estate_property_station = str_replace('Station', '', $estate_property_station);

$estate_property_prefecture = get_post_meta( $property_id, 'estate_property_prefecture', true );
$estate_property_district = get_post_meta( $property_id, 'estate_property_district', true );
$estate_property_town = get_post_meta( $property_id, 'estate_property_town', true );

$google_maps = get_post_meta( $property_id, 'estate_property_google_maps', true );
if ( ! tt_is_array_empty( $google_maps ) ) {
	$address = $google_maps['address'];
} else {
	$address = null;
}

$size = formatNumber(get_post_meta( $property_id, 'estate_property_size', true ));
$size_unit = get_post_meta( $property_id, 'estate_property_size_unit', true );
$size_unit = trans_text($size_unit == 'm2' ? AREA_M2 : $size_unit);
if ( ! empty( $size ) ) {
	$size_meta = get_field_object( 'estate_property_size', $property_id );
	if ( ! empty( $size_meta['label'] ) ) {
	  $size_label = $size_meta['label'];
	} else {
	  $size_label = null;
	}
}

// Get building info
$building = getBuilding($property_id);
$estate_property_station = isEnglish() ? $building['stations'][0]['name_en'] : $building['stations'][0]['name'];

// Get Floor info
$floor = getFloor($property_id);

$post_title = (isset($_GET['size']) && $_GET['size'] && $_GET['size'] != 'all') || (isset($show_floor_title) && $show_floor_title) ? get_the_title() : get_post_meta($property_id, 'post_title_building', true);
$post_title = strpos($_SERVER['REQUEST_URI'], 'search-properties') === false ? $post_title : get_post_meta($property_id, 'post_title_building', true);

$classes = array();
$classes[] = 'property-item';
$classes[] = 'border-box';
//$classes[] = 'primary-tooltips';

if ( $property_featured ) {
	$classes[] = ' featured';
}

$classes = join( ' ', $classes );

$last_updated_on = get_post_modified_time( get_option( 'date_format' ) );
?>

<div class="<?php echo $classes; ?>" data-buildingid="<?php echo $building['building_id'] ?>" <?php if ( isset ( $property_counter ) ) { echo ' data-sync-id="' . esc_attr( $property_counter ) . '"'; }?>>

	<a href="<?php echo get_permalink( $post ); ?>">
		<figure class="property-thumbnail fit-height">
			<?php
				if ( has_post_thumbnail() ) {
					$thumbnail_classes = array();
					if ( get_post_status( $property_id ) != 'publish' ) {
						$thumbnail_classes[] = 'grayscale';
					}
					$thumbnail_classes = join( ' ', $thumbnail_classes );

					the_post_thumbnail( 'square-300', array( 'class' => $thumbnail_classes ) );
				} else {
					if ( ! empty( $realty_theme_option['listing-sample-image']['url'] ) ) {
						echo '<img src ="' . $realty_theme_option['listing-sample-image']['url'] . '" />';
					} else {
						echo '<img src ="//placehold.it/600x300/eee/ccc/&text=.." />';
					}
				}
			?>
			<figcaption>
				<div class="property-excerpt">
					<h4 class="address">
					<?php echo $address; ?>
					<?php if (is_user_logged_in()) echo trans_text(sprintf('(Views : %s)', (int)get_post_meta(get_the_ID(), 'estate_property_views_count', true)));?>
					</h4>
					<?php the_excerpt(); ?>
					</div>
					<?php if ( $property_status_update ) { ?>
						<div class="property-tag tag-left">
							<?php echo $property_status_update; ?>
						</div>
				<?php } ?>
			</figcaption>
		</figure>
	</a>

	<div class="property-content content">
	<div class="same-height">
		<div class="property-title">
			<a href="<?php echo get_permalink( $post ); ?>"><h3 class="title"><?php echo $post_title; ?></h3></a>
		</div>
		<?php if ( $realty_theme_option['property-listing-type'] != 'custom' ) { // Default Listing Fields ?>
			<div class="property-meta custom-meta-list clearfix">
					<div>
						<div class="meta-title"><i class="iconmoon-location"></i></div>
						<div class="meta-data" data-toggle="tooltip" title="<?php echo __('location', 'realty')?>"><?php  echo isset($property_location[0]) ? $property_location[0]->name : ''?></div>
					</div>
					<div>
						<div class="meta-title"><i class="iconmoon-directions_transit"></i></div>
						<div class="meta-data" data-toggle="tooltip" title="<?php echo __('station', 'realty')?>"><?php echo $estate_property_station?></div>
					</div>
					<div>
						<div class="meta-title"><i class="iconmoon-crop_free"></i></div>
						<div class="meta-data" data-toggle="tooltip" title="<?php echo __('size', 'realty')?>"><?php echo $size . $size_unit?></div>
					</div>
			
			</div>
		<?php } ?>

		<?php if ( $realty_theme_option['property-listing-type'] == 'custom' ) { // Custom Listing Fields ?>
			<div class="property-meta clearfix">
				<?php
					$property_custom_listing_field = $realty_theme_option['property-custom-listing-field'];
					$property_custom_listing_icon_class = $realty_theme_option['property-custom-listing-icon-class'];
					$property_custom_listing_label = $realty_theme_option['property-custom-listing-label'];
					$property_custom_listing_label_plural = $realty_theme_option['property-custom-listing-label-plural'];
					$property_custom_listing_tooltip = $realty_theme_option['property-custom-listing-tooltip'];
				?>

				<?php $i = 0; ?>

				<?php foreach ( $property_custom_listing_field as $field_type ) { ?>

					<?php
						$field = get_post_meta( $property_id, $field_type, true );

						if ( $field_type == 'estate_property_available_from' ) {
							$field = date_i18n( get_option( 'date_format' ), strtotime( $field ) );
						}
						if ( $field_type == 'estate_property_updated' ) {
							$field = $last_updated_on;
						}
						if ( $field_type == 'estate_property_views' ) {
							$field = tt_get_property_views( $property_id );
						}
						if ( $field_type == 'estate_property_size' ) {
							$size_unit = get_post_meta( $property_id, 'estate_property_size_unit', true );
							if( ! empty ( $field ) ) {
								$field = $field . ' ' . $size_unit;
							}
						}
						if ( $field_type == 'estate_property_id' ) {
							if ( $realty_theme_option['property-id-type'] == 'post_id' ) {
								$field = $property_id;
							} else {
								$field = get_post_meta( $property_id, 'estate_property_id', true );
							}
						}
					?>

					<?php if ( ! empty( $field ) ) { ?>
						<div>
							<div class="meta-title"><i class="<?php echo $property_custom_listing_icon_class[$i]; ?>"></i></div>
							<div class="meta-data" data-toggle="tooltip" title="<?php echo _n( $property_custom_listing_label[$i], $property_custom_listing_label_plural[$i], $field, 'realty' ); ?>">
								<?php echo $field; ?>
								<?php
									if ( $property_custom_listing_tooltip[$i] == false ) {
										echo ' ' . _n( $property_custom_listing_label[$i], $property_custom_listing_label_plural[$i], $field, 'realty' );
									}
								?>
							</div>
						</div>
					<?php } ?>

					<?php $i++; ?>

				<?php }	?>
			</div>
		<?php }	?>
</div>
		<div class="property-price">

			<div class="price-tag">
				<?php echo renderPropertyPrice($property_id, $building, $floor) ?>
			</div>

			<div class="property-icons">
				<?php
					// Property icons
					if ( get_post_status( $property_id ) == 'publish' && $realty_theme_option['enable-social-on-listing'] ) {
						$encode_url = urlencode( get_permalink() );
						$encode_title = htmlspecialchars( urlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8') ), ENT_COMPAT, 'UTF-8' );
					?>
					<span style="position: relative">
						<div class="share-unit" style="display: none;">
							<a class="social-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $encode_url; ?>&amp;t=<?php echo $encode_title; ?>"><i class="icon-facebook"></i></a>
							<a target="_blank" class="social-twitter" href="http://twitter.com/home?status=<?php echo $encode_title; ?>+<?php echo $encode_url; ?>"><i class="icon-twitter"></i></a>
							<a class="social-google" target="_blank" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><i class="icon-google-plus"></i></a>
							<a class="social-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;description=<?php echo $encode_url; ?>"><i class="icon-pinterest"></i></a>
						</div>
						<i class="icon-share share-property" data-toggle="tooltip" data-original-title="<?php esc_html_e( 'Share', 'realty' ) ?>" title="<?php esc_html_e( 'Share', 'realty' ) ?>"></i>
					</span>
				<?php	} ?>

				<?php
					//echo tt_icon_property_featured();
					if ( get_post_status( $property_id ) == 'publish' ) {
						echo tt_icon_new_property();
						echo tt_add_remove_favorites();
						echo tt_add_remove_contact();
					}
					echo tt_icon_property_video();

					$disable_property_comparison = $realty_theme_option['property-comparison-disabled'];

					if ( get_post_status($property_id) == 'publish' && ! $disable_property_comparison ) {
						echo '<i class="icon-add compare-property" data-compare-id="' . get_the_ID() . '" data-toggle="tooltip" title="' . esc_html__( 'Compare', 'realty' ) . '"></i>';
					}
				?>

				<?php if ( is_user_logged_in() && is_page_template( 'template-property-submit-listing.php' ) ) { ?>

					<a href="<?php the_permalink(); ?>"><i class="icon-pen" data-toggle="tooltip" title="<?php esc_html_e( 'Edit Property', 'realty' ); ?>"></i></a>

					<?php if ( get_post_status( $property_id ) == 'publish' ) { ?>

						<a href="<?php echo get_the_permalink(); ?>" target="_blank"><i class="icon-check-2" data-toggle="tooltip" title="<?php esc_html_e( 'Published', 'realty' ); ?>"></i></a>
						<?php $paypal_payment_status = get_post_meta( $property_id, 'property_payment_status', true ); ?>
						<?php if ( isset( $paypal_payment_status ) && $paypal_payment_status == 'Completed' ) { ?>
							<i class="icon-bank-notes" data-toggle="tooltip" title="<?php esc_html_e( 'Paid', 'realty' ); ?>"></i>
						<?php } ?>
						<a href="#" class="delete-property" data-property-id="<?php echo $property_id; ?>"><i class="icon-trash" data-toggle="tooltip" title="<?php esc_html_e( 'Delete Property', 'realty' ); ?>"></i></a>

					<?php } else if ( get_post_status( $property_id ) == 'draft' ) { ?>

						<a href="<?php echo the_permalink(); ?>" target="_blank"><i class="icon-view" data-toggle="tooltip" title="<?php esc_html_e( 'Draft', 'realty' ); ?>"></i></a>
						<a href="#" class="delete-property" data-property-id="<?php echo $property_id; ?>"><i class="icon-trash" data-toggle="tooltip" title="<?php esc_html_e( 'Delete Property', 'realty' ); ?>"></i></a>

					<?php } else if ( get_post_status( $property_id ) == 'pending' ) { ?>

	          <a href="<?php echo the_permalink(); ?>" target="_blank"><i class="icon-clock" data-toggle="tooltip" title="<?php esc_html_e( 'Pending', 'realty' ); ?>"></i></a>
	          <a href="#" class="delete-property" data-property-id="<?php echo $property_id; ?>"><i class="icon-trash" data-toggle="tooltip" title="<?php esc_html_e( 'Delete Property', 'realty' ); ?>"></i></a>

					<?php }	?>

				<?php }	?>

			</div><!-- .property-icons -->
			<div class="clearfix"></div>

		</div><!-- .property-price -->

	</div><!-- .property-content -->

	<?php if ( is_user_logged_in() && is_page_template( 'template-property-submit-listing.php' ) && get_post_status( $property_id ) != 'publish' ) { ?>
		<div class="property-payment-buttons">
			<?php
        if ( $realty_theme_option['paypal-enable'] ) {
          echo tt_paypal_payment_button( $property_id );
        }
      ?>

      <?php
				if ( $realty_theme_option['enable-stripe-payments'] ) {
					tt_stripe_payment_form( $property_id );
				}
			?>
		</div>
	<?php } ?>

</div><!-- .property-item -->
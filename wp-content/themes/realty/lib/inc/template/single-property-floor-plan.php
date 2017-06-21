<?php $property_title_floor_plan = $realty_theme_option['property-title-floor-plan']; ?>
<?php if ( $property_title_floor_plan ) { ?>
	<h3 class="section-title"><span><?php echo $property_title_floor_plan; ?></span></h3>
<?php } ?>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

	<?php
		if ( $property_floor_plans  ) {

			$property_floor_plan_title = array();
			$property_floor_plan_price = array();
			$property_floor_plan_size = array();
			$property_floor_plan_rooms = array();
			$property_floor_plan_bedrooms = array();
			$property_floor_plan_bathrooms = array();
			$property_floor_plan_description = array();
			$property_floor_plan_image = array();

			while ( has_sub_field( 'estate_property_floor_plans', $single_property_id ) ) {
				$property_floor_plan_title[] = get_sub_field( 'acf_estate_floor_plan_title', $single_property_id );
				$property_floor_plan_price[] = get_sub_field( 'acf_estate_floor_plan_price', $single_property_id );
				$property_floor_plan_size[]= get_sub_field( 'acf_estate_floor_plan_size', $single_property_id );
				$property_floor_plan_rooms[] = get_sub_field( 'acf_estate_floor_plan_rooms', $single_property_id );
				$property_floor_plan_bedrooms[] = get_sub_field( 'acf_estate_floor_plan_bedrooms', $single_property_id );
				$property_floor_plan_bathrooms[] = get_sub_field( 'acf_estate_floor_plan_bathrooms', $single_property_id );
				$property_floor_plan_description[] = get_sub_field( 'acf_estate_floor_plan_description', $single_property_id );
				$property_floor_plan_image[] = get_sub_field( 'acf_estate_floor_plan_image', $single_property_id );
			}

		}

		$i = 0;
	?>

	<?php foreach ( $property_floor_plan_image as $image ) { ?>
	  <div class="panel panel-default">

			<div class="panel-heading" data-toggle="collapse" data-target="#floor-plan-<?php echo $i; ?>">
			  <h3 class="panel-title"><?php echo $property_floor_plan_title[$i]; ?></h3>
				<div class="details text-muted">
					<small>
					<?php
							$currency_sign = $realty_theme_option['currency-sign'];
							$currency_sign_position = $realty_theme_option['currency-sign-position'];

							if ( $realty_theme_option['price-decimals'] ) {
								$decimals = $realty_theme_option['price-decimals'];
							} else {
								$decimals = 0;
							}

							$decimal_point = '.';

							if ( $property_floor_plan_price[$i] ) {
								$formatted_price = number_format( $property_floor_plan_price[$i], $decimals, $decimal_point, $realty_theme_option['price-thousands-separator'] );
							} else {
								$formatted_price = 0;
							}

							if( $currency_sign_position == 'right' ) {
								$price = $formatted_price . $currency_sign;
							} else {
								$price = $currency_sign . $formatted_price;
							}

							if ( $property_floor_plan_price[$i] ) {
								echo '<span>' . esc_html__( 'Price', 'realty' ) . ': ' . $price . '</span>';
							}

							if ( $property_floor_plan_size[$i] ) {
								echo '<span>' . esc_html__( 'Size', 'realty' ) . ': ' . $property_floor_plan_size[$i] . ' ' . $size_unit . '</span>';
							}

							if ( $property_floor_plan_rooms[$i] ) { echo '<span>' . esc_html__( 'Rooms', 'realty' ) . ': ' . $property_floor_plan_rooms[$i] . '</span>'; }
							if ( $property_floor_plan_bedrooms[$i] ) { echo '<span>' . esc_html__( 'Bedrooms', 'realty' ) . ': ' . $property_floor_plan_bedrooms[$i] . '</span>'; }
							if ( $property_floor_plan_bathrooms[$i] ) { echo '<span>' . esc_html__( 'Bathrooms', 'realty' ) . ': ' . $property_floor_plan_bathrooms[$i] . '</span>'; }
							?>
					</small>
				</div>
			</div>

			<div id="floor-plan-<?php echo $i; ?>" class="panel-collapse collapse">
			  <img class="floor-plan" data-mfp-src="<?php echo $image; ?>" src="<?php echo $image; ?>" />
			  <?php if ( $property_floor_plan_description[$i] ) { ?>
			  <div class="panel-body"><?php echo $property_floor_plan_description[$i]; ?></div>
			  <?php } ?>
			</div>

	  </div>

		<?php $i++; ?>

	<?php }	?>

</div>
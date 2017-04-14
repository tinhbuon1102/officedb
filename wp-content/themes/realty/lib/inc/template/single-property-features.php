<?php
	$property_title_features = $realty_theme_option['property-title-features'];
	if ( $property_title_features	 ) {
?>
	<h3 class="section-title"><span><?php echo $property_title_features; ?></span></h3>
<?php } ?>

<ul class="list-unstyled row">
	<?php
		$property_features_all = get_terms( 'property-features', array( 'hide_empty' => false ) ); // Get All Property Features
		$property_features_slug = array();

		// Built Array With All Property Features
		foreach ( $property_features as $property_feature ) {
			$property_features_slug[] = $property_feature->slug;
		}
	?>

	<?php foreach ( $property_features_all as $property_feature_item ) { ?>

		<?php
			$property_feature_slug = $property_feature_item->slug;
			$description = $property_feature_item->description;
			$description = wp_trim_words( $description, 10, ' ..' );

			// Add Class "inactive" To Every Feature, That This Property Doesn't Have
			if ( ! in_array( $property_feature_slug, $property_features_slug ) ) {
				$inactive = ' class="inactive"';
			} else {
				$inactive = null;
			}
		?>

		<?php
			// Hide Non Applicable Features
			if ( $realty_theme_option['property-features-hide-non-applicable'] && $inactive ) {
				$show_empty_feature = false;
			} else {
				$show_empty_feature = true;
			}
		?>

		<?php if ( $show_empty_feature ) { ?>

			<?php if ( is_active_sidebar( 'sidebar_property' ) ) { ?>
				<li class="col-sm-6 col-md-4">
			<?php } else { ?>
				<li class="col-sm-4 col-md-3">
			<?php } ?>

				<a href="<?php echo home_url() . '/property-feature/'. $property_feature_item->slug; ?>"<?php echo $inactive; ?>>
					<?php if ( $inactive ) { ?>
						<i class="icon-subtract text-muted"></i>
					<?php } else { ?>
						<i class="icon-check-1 text-primary"></i>
					<?php } ?>

					<?php echo $property_feature_item->name; ?>

					<?php if ( $description ) { ?>
						<i class="icon-question-circle" data-toggle="tooltip" title="<?php echo esc_attr( $description ); ?>"></i>
					<?php } ?>
				</a>
			</li>

		<?php } ?>

	<?php }	?>
</ul>
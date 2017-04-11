<?php
	global $realty_theme_option;
	$disable_property_comparison = $realty_theme_option['property-comparison-disabled'];
?>

<?php if ( ! $disable_property_comparison ) { ?>
	<div id="compare-properties-popup">

		<h4><?php esc_html_e( 'Compare Properties', 'realty' ); ?></h4>

		<div id="compare-properties-thumbnails"></div>

		<?php
			// Get page that is using "Property Comparison" Page Template
			$template_page_property_comparison_array = get_pages( array (
				'meta_key' => '_wp_page_template',
				'meta_value' => 'template-property-comparison.php'
				)
			);
		?>

		<?php
			if ( $template_page_property_comparison_array ) {
				$comparison_page_id = $template_page_property_comparison_array[0]->ID;
			} else {
				$comparison_page_id = false;
			}
		?>

		<?php	if ( $comparison_page_id ) { ?>
			<p>
				<a href="<?php echo get_permalink( $comparison_page_id ); ?>" class="btn btn-primary btn-lg"><?php esc_html_e( 'Compare', 'realty' ); ?></a>
			</p>
		<?php } else { ?>
			<p class="alert alert-info">
				<?php esc_html_e( 'Please create a page that is using page template "Property Comparison".', 'realty' ); ?>
			</p>
		<?php }	?>

		<p class="alert alert-info hide">
			<small><?php esc_html_e( 'You have reached the maximum of four properties per comparison.', 'realty' ); ?></small>
			</p>

	</div>
<?php } ?>
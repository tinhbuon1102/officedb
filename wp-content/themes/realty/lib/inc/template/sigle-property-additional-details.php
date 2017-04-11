<?php
	$property_title_additional_details = $realty_theme_option['property-title-additional-details'];

	$hide_addtional_fields_selected =  array();

	if ( isset( $realty_theme_option['additional-area-hidden-fields'] ) ) {
		if ( ! tt_is_array_empty( $realty_theme_option['additional-area-hidden-fields'] ) ) {
			$hide_addtional_fields_selected =  $realty_theme_option['additional-area-hidden-fields'];
		}
	}
?>

<?php if ( $realty_theme_option['property-additional-details-layout'] == 'grid' ) { // Additional Details "Grid Layout" ?>

<?php
	$output_grid = '';

	foreach ( $acf_fields_name as $field_name ) {

		if ( in_array( $field_name , $hide_addtional_fields_selected ) == 1 ) {
			$field_name = '';
			$acf_fields_type[$i] = '';
			$acf_fields_label[$i] = '';
		} else {
			if ( $acf_fields_type[$i] == 'taxonomy' ) {
				$taxonomy_value = get_field_object( $field_name, $single_property_id );
				$field_terms = get_the_terms( $single_property_id, $taxonomy_value['taxonomy'] );
				$field = array();
				if ( $field_terms && ! is_wp_error( $field_terms ) ) {
					foreach( $field_terms as $term ) {
						$field[]= '<a href="'. get_term_link( $term->term_id, $term->taxonomy ).'">' . $term->name . '</a>';
					}
				}
			} else if ( $acf_fields_type[$i] == 'file' ) {
				$file = get_field( $field_name, $single_property_id );
				$field = tt_icon_attachment( $file['type'] ) . ' ' . '<a href="' . $file['url'] . '" target="_blank">' . $file['filename'] . '</a>';
			} else {
				$field = get_field( $field_name, $single_property_id );
			}

			// echo $field;

			if ( empty( $field ) ) {
				$field = '-';
				$empty_field = true;
			}

			if ( is_active_sidebar( 'sidebar_property' ) ) {
				$output_inner = '<li class="col-sm-6 col-md-4">';
			} else {
				$output_inner = '<li class="col-sm-4 col-md-3">';
			}

			$output_inner .= '<h5>'	. $acf_fields_label[$i] . ':</h5>';

			if ( is_array( $field ) ) {
				$output_inner .= join( ', ', $field );

			} else if ( $acf_fields_type[$i] == 'url' || $acf_fields_type[$i] == 'page_link' ) {
				$output_inner .= '<a href="'.$field.'">'.$field.'</a>';
			} else if( $acf_fields_type[$i] == 'oembed' ) {
				$output_inner .= '<div class="embed-container">Available in tab view only</div>';
			} else {
				$output_inner .= $field;
			}

			$output_inner .= '</li>';

			if ( $empty_field == true  && $realty_theme_option['property-additional-details-hide-empty'] ) {
				$output_inner = '';
			} else {
				$output_grid .= $output_inner;
			}
		}

		$i++;
		$empty_field = false;

	} // foreach ?>

	<?php if ( ! empty( $output_grid ) || ! $realty_theme_option['property-additional-details-hide-empty'] ) { ?>
		<section id="additional-details">
		<?php if ( $property_title_additional_details ) { ?>
			<h3 class="section-title"><span><?php echo $property_title_additional_details;; ?></span></h3>
		<?php } ?>
			<ul class="list-unstyled row"><?php echo $output_grid; ?></ul>
		</section>
	<?php } ?>

<?php }	else { // Additional Details "Tab Layout"

	$output = '<ul class="nav nav-tabs" role="tablist">';

	$tab_head = '';
	$tab_view = 0;
	$i = 0;

	foreach ( $acf_fields_name as $field_name ) {
		if ( in_array( $field_name , $hide_addtional_fields_selected ) == 1 ) {
			$field_name = '';
			$acf_fields_type[$i] = '';
			$acf_fields_label[$i] = '';
		} else {
			$field = get_field( $field_name, $single_property_id );
			if ( empty( $field ) ) {
				$field = '-';
				$empty_field = true;
			}
			$tab_head = '';
			if ( $realty_theme_option['property-additional-details-hide-empty'] && $empty_field == true ) {
				$tab_head = '';
			} else {
				if ( $tab_view == 0 ) {
					$tab_head .= '<li role="presentation" class="active">';
					$tab_view++;
				} else {
					$tab_head .= '<li role="presentation">';
				}
				$tab_head .= '<a href="#additional-' . $i . '" aria-controls="additional-' . $i . '" role="tab" data-toggle="tab">' . $acf_fields_label[$i] . '</a></li>';
			}
			$output .= $tab_head;
		}

		$empty_field = false;
		$i++;
	}

	$output .= '</ul>';

	// Tab content
	$output_tab = '<div class="tab-content">';
	$tab_content ='';
	$empty_field = false;
	$tab_show = false;
	$tab_view = 0;
	$i = 0;

	foreach ( $acf_fields_name as $field_name ) {
		if ( in_array( $field_name , $hide_addtional_fields_selected ) == 1 ) {
			$field_name = '';
			$acf_fields_type[$i] = '';
			$acf_fields_label[$i] = '';
		} else {
			if ( $acf_fields_type[$i] == 'taxonomy' ) {
				$taxonomy_value = get_field_object( $field_name, $single_property_id );
				$field_terms = get_the_terms( $single_property_id, $taxonomy_value['taxonomy'] );
				$field = array();
				if ( $field_terms && ! is_wp_error( $field_terms ) ) {
					foreach( $field_terms as $term ) {
						$field[]= '<a href="'. get_term_link( $term->term_id, $term->taxonomy ).'">' . $term->name . '</a>';
					}
				}
			} else if ( $acf_fields_type[$i] == 'file' ) {
				$file = get_field( $field_name, $single_property_id );
				$field = tt_icon_attachment( $file['type'] ) . ' ' . '<a href="' . $file['url'] . '" target="_blank">' . $file['filename'] . '</a>';
			} else {
				$field = get_field( $field_name, $single_property_id );
			}

			$tab_content = '';

			if ( empty( $field ) ) {
				$field = '-';
				$empty_field = true;
			} else {
				$tab_show = true;
			}

			if ( $realty_theme_option['property-additional-details-hide-empty'] && $empty_field == true ) {
				$tab_content = '-';
			} else {
				if ( $tab_view == 0) {
					$tab_content .= '<div role="tabpanel" class="tab-pane active" id="additional-' . $i . '">';
					$tab_view++;
				} else {
					$tab_content .= '<div role="tabpanel" class="tab-pane" id="additional-' . $i . '">';
				}
			}

			if ( is_array( $field ) ) {
				$tab_content .= join( ', ', $field );
			} else if ( $acf_fields_type[$i] == 'url' || $acf_fields_type[$i] == 'page_link' ) {
				$tab_content .= '<a href="' . $field . '">' . $field . '</a>';
			} else if ( $acf_fields_type[$i] == 'oembed' ) {
				$tab_content .= '<div class="embed-container">' . $field . '</div>';
			} else {
				$tab_content .= $field;
			}

			$tab_content .= '</div>';

			if ( $realty_theme_option['property-additional-details-hide-empty'] && $empty_field == true ) {
				$tab_content = '';
			} else {
				$output_tab .= $tab_content;
			}
		}

		$empty_field = false;
		$i++;
	} // foreach

	$output_tab .= '</div>';

	if ( $tab_show || ! $realty_theme_option['property-additional-details-hide-empty'] ) { ?>
		<section id="additional-details">
			<?php if ( $property_title_additional_details ) { ?>
				<h3 class="section-title"><span><?php echo $property_title_additional_details; ?></span></h3>
			<?php } ?>
			<?php echo $output . $output_tab;	?>
		</section>
	<?php }

} // Tab Layout
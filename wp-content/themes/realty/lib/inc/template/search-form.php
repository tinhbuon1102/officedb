<?php
	global $realty_theme_option, $wp_query;
?>

<form class="property-search-form border-box" action="<?php if ( tt_page_id_template_search() ) { echo get_permalink( tt_page_id_template_search() ); } ?>">

	<div class="row">

		<?php if ( isset( $realty_theme_option['property-search-results-page'] ) && empty( $realty_theme_option['property-search-results-page'] ) ) { ?>
			<div class="col-xs-12" style="margin-bottom: 1em">
				<p class="alert alert-info"><?php esc_html_e( 'Please go to "Appearance > Theme Options > Pages" and set the page you want to use as your property search results.', 'realty' ); ?></p>
			</div>
		<?php } ?>

		<?php
			// Form select classes
			$form_select_class = 'form-control';

			if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) {
				$form_select_class .= ' chosen-select chosen-rtl';
			} else {
				$form_select_class .= ' chosen-select';
			}

			$acf_field_array = array();

			if ( isset( $realty_theme_option['property-search-features'] ) && ! tt_is_array_empty( $realty_theme_option['property-search-features'] ) ) {
		    $property_search_features = $realty_theme_option['property-search-features'];
			} else {
				$property_search_features = null;
			}

			$raw_search_params = get_query_var( 'property_search_parameters' );
			if ( ! tt_is_array_empty( $raw_search_params ) ) {
				$search_parameters = $raw_search_params;
			} else if ( isset( $realty_theme_option['property-search-parameter'] ) && ! empty( $realty_theme_option['property-search-parameter'] ) ) {
				$search_parameters = $realty_theme_option['property-search-parameter'];
			} else {
				$search_parameters = null;
			}

			$raw_search_fields = get_query_var('property_search_fields');
			if ( ! tt_is_array_empty( $raw_search_fields ) ) {
				$search_fields = $raw_search_fields;
			} else if ( isset( $realty_theme_option['property-search-parameter'] ) && ! empty( $realty_theme_option['property-search-parameter'] ) ) {
				$search_fields = $realty_theme_option['property-search-field'];
			} else {
				$search_fields = null;
			}

			$raw_search_labels = get_query_var('property_search_labels');
			if ( ! tt_is_array_empty( $raw_search_labels ) ) {
				$search_labels = $raw_search_labels;
			} else if ( isset( $realty_theme_option['property-search-label'] ) && ! empty( $realty_theme_option['property-search-label'] ) ) {
				$search_labels = $realty_theme_option['property-search-label'];
			}  else {
				$search_labels = null;
			}

			$default_search_fields_array = array(
				'estate_search_by_keyword',
				'estate_property_id',
				'estate_property_location',
				'estate_property_type',
				'estate_property_status',
				'estate_property_price',
				'estate_property_price_min',
				'estate_property_price_max',
				'estate_property_pricerange',
				'estate_property_size',
				'estate_property_rooms',
				'estate_property_bedrooms',
				'estate_property_bathrooms',
				'estate_property_garages',
				'estate_property_available_from'
			);

			$i = 0;

			if ( isset( $search_form_columns ) && ! empty( $search_form_columns ) ) {
				// Use $columns parameter from shortcode [property_search_form]
				$count_search_fields = $search_form_columns;
			} else {
				// No shortcode $columns found, pick columns according to total field count
				$count_search_fields = count( $search_fields );
			}

			if ( $count_search_fields == 1 ) {
				$columns = 'col-xs-12';
			} else if ( $count_search_fields == 2 ) {
				$columns = 'col-xs-12 col-sm-6';
			} else if ( $count_search_fields == 3 ) {
				$columns = 'col-xs-12 col-sm-6 col-md-4';
			} else {
				$columns = 'col-xs-12 col-sm-4 col-md-3';
			}

			if ( is_page_template( 'template-map-vertical.php' ) ) {
				$columns = 'col-xs-12 col-sm-6';
			}

			// Do we have any search parameters defined?
			if ( isset( $search_parameters ) && ! empty( $search_parameters[0] ) ) {

				foreach ( $search_fields as $search_field ) {

					$search_parameter = $search_parameters[$i];

					// Check If Search Field Is Filled Out
					if ( ! empty( $search_field ) ) {

						// Default Property Field
						if ( in_array( $search_field, $default_search_fields_array ) ) {

							switch ( $search_field ) {

								case 'estate_search_by_keyword' :
								case 'estate_property_id' :
								?>
								<div class="<?php echo $columns; ?> form-group">
									<input type="text" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter]) ? $_GET[$search_parameter] : ''; ?>" placeholder="<?php echo $search_labels[$i]; ?>" class="form-control" />
								</div>
								<?php
								break;

								case 'estate_property_location' : ?>
								<div class="<?php echo $columns; ?> form-group select">
									<?php
										// http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658
										if ( ! empty( $search_labels[$i] ) ) {
											$search_label_location = $search_labels[$i];
										} else {
											$search_label_location = esc_html__( 'Any Location', 'realty' );
										}
									?>
									<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
										<option value="all"><?php echo $search_label_location; ?></option>
								    <?php
									    $location = get_terms('property-location', array(
									    	'orderby' => 'slug',
									    	'parent' => 0,
									    	'hide_empty' => false
									    ) );

									    if ( isset( $_GET[$search_parameter] ) ) {
												$get_location = $_GET[$search_parameter];
											} else {
												$get_location = null;
											}
										?>

								    <?php foreach ( $location as $key => $location ) : ?>
						        <option value="<?php echo $location->slug; ?>" <?php selected( $location->slug, $get_location ); ?>>
					            <?php
						            echo $location->name;

						            $location2 = get_terms( 'property-location', array(
						            	'orderby' => 'slug',
						            	'parent' => $location->term_id
						            ) );

						            if ( $location2 ) :
					            ?>
					            <optgroup>
					              <?php foreach( $location2 as $key => $location2 ) : ?>
					                  <option value="<?php echo $location2->slug; ?>" class="level2" <?php selected( $location2->slug, $get_location ); ?>>
					                  	<?php
						                  	echo $location2->name;

						                  	$location3 = get_terms( 'property-location', array(
						                  		'orderby' => 'slug',
						                  		'parent' => $location2->term_id
						                  	) );

						                  	if ( $location3 ) :
					                  	?>
					                  	<optgroup>
					                  		<?php foreach( $location3 as $key => $location3 ) : ?>
					                    		<option value="<?php echo $location3->slug; ?>" class="level3" <?php selected( $location3->slug, $get_location ); ?>>
					                    		<?php
					                    		echo $location3->name;
						                    	$location4 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location3->term_id ) );
						                    	if( $location4 ) :
					                    		?>
					                    		<optgroup>
					                    			<?php foreach( $location4 as $key => $location4 ) : ?>
					                    			<option value="<?php echo $location4->slug; ?>" class="level4" <?php selected( $location4->slug, $get_location ); ?>>
																		<?php echo $location4->name; ?>
					                    			</option>
					                    			<?php endforeach; ?>
					                    		</optgroup>
					                    		<?php endif; ?>
					                    		</option>
					                  		<?php endforeach; ?>
					                  	</optgroup>
					                  	<?php endif; ?>
					                  </option>
					              <?php endforeach; ?>
					            </optgroup>
					            <?php endif; ?>
						        </option>
								    <?php endforeach; ?>
									</select>
								</div>
								<?php
								break;

								case 'estate_property_status' : ?>
								<div class="<?php echo $columns; ?> form-group select">
									<?php
										// http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658
	                  if ( ! empty( $search_labels[$i] ) ) {
									    $search_label_status = $search_labels[$i];
									  } else {
											$search_label_status = esc_html__( 'Any Status', 'realty' );
										}
									?>
									<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
										<option value="all"><?php echo $search_label_status; ?></option>
								    <?php
									    $status = get_terms('property-status', array(
									    	'orderby' => 'slug',
									    	'parent' => 0
									    ) );

									    if ( isset( $_GET[$search_parameter] ) ) {
												$get_status = $_GET[$search_parameter];
											} else {
												$get_status = null;
											}
										?>
								    <?php foreach ( $status as $key => $status ) : ?>
						        <option value="<?php echo $status->slug; ?>" <?php selected( $status->slug, $get_status ); ?>>
					            <?php
						            echo $status->name;

						            $status2 = get_terms( 'property-status', array(
						            	'orderby' => 'slug',
						            	'parent' => $status->term_id
						            ) );

						            if ( $status2 ) :
					            ?>
					            <optgroup>
					              <?php foreach( $status2 as $key => $status2 ) : ?>
					                  <option value="<?php echo $status2->slug; ?>" class="level2" <?php selected( $status2->slug, $get_status ); ?>>
					                  	<?php
						                  	echo $status2->name;

						                  	$status3 = get_terms( 'property-status', array(
						                  		'orderby' => 'slug',
						                  		'parent' => $status2->term_id
						                  	) );

						                  	if ( $status3 ) :
					                  	?>
					                  	<optgroup>
					                  		<?php foreach( $status3 as $key => $status3 ) : ?>
					                    		<option value="<?php echo $status3->slug; ?>" class="level3" <?php selected( $status3->slug, $get_status ); ?>>
					                    		<?php
						                    		echo $status3->name;

							                    	$status4 = get_terms( 'property-status', array(
							                    		'orderby' => 'slug',
							                    		'parent' => $status3->term_id
							                    	) );

							                    	if ( $status4 ) :
					                    		?>
					                    		<optgroup>
					                    			<?php foreach( $status4 as $key => $status4 ) : ?>
					                    			<option value="<?php echo $status4->slug; ?>" class="level4" <?php selected( $status4->slug, $get_status ); ?>>
																		<?php echo $status4->name; ?>
					                    			</option>
					                    			<?php endforeach; ?>
					                    		</optgroup>
					                    		<?php endif; ?>
					                    		</option>
					                  		<?php endforeach; ?>
					                  	</optgroup>
					                  	<?php endif; ?>
					                  </option>
					              <?php endforeach; ?>
					            </optgroup>
					            <?php endif; ?>
						        </option>
								    <?php endforeach; ?>
									</select>
								</div>
								<?php
								break;

								case 'estate_property_type' : ?>
								<div class="<?php echo $columns; ?> form-group select">
									<?php
										// http://wordpress.stackexchange.com/questions/14652/how-to-show-a-hierarchical-terms-list#answer-14658
	                  if ( ! empty( $search_labels[$i] ) ) {
									    $search_label_type = $search_labels[$i];
									  } else {
											$search_label_type = esc_html__( 'Any Type', 'realty' );
										}
									?>
									<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
										<option value="all"><?php echo $search_label_type; ?></option>
								    <?php
									    $type = get_terms('property-type', array(
									    	'orderby' => 'slug',
									    	'parent' => 0
									    ) );

									    if ( isset( $_GET[$search_parameter] ) ) {
												$get_type = $_GET[$search_parameter];
											} else {
												$get_type = null;
											}
										?>
								    <?php foreach ( $type as $key => $type ) : ?>
						        <option value="<?php echo $type->slug; ?>" <?php selected( $type->slug, $get_type ); ?>>
					            <?php
						            echo $type->name;

						            $type2 = get_terms( 'property-type', array(
						            	'orderby' => 'slug',
						            	'parent' => $type->term_id
						            ) );

												if ( $type2 ) :
					            ?>
					            <optgroup>
					              <?php foreach( $type2 as $key => $type2 ) : ?>
					                  <option value="<?php echo $type2->slug; ?>" class="level2" <?php selected( $type2->slug, $get_type ); ?>>
					                  	<?php
						                  	echo $type2->name;

						                  	$type3 = get_terms( 'property-type', array(
						                  		'orderby' => 'slug',
						                  		'parent' => $type2->term_id
						                  	) );

						                  	if ( $type3 ) :
					                  	?>
					                  	<optgroup>
					                  		<?php foreach( $type3 as $key => $type3 ) : ?>
					                    		<option value="<?php echo $type3->slug; ?>" class="level3" <?php selected( $type3->slug, $get_type ); ?>>
					                    		<?php
						                    		echo $type3->name;

							                    	$type4 = get_terms( 'property-type', array(
							                    		'orderby' => 'slug',
							                    		'parent' => $type3->term_id
							                    	) );

							                    	if ( $type4 ) :
					                    		?>
					                    		<optgroup>
					                    			<?php foreach( $type4 as $key => $type4 ) : ?>
					                    			<option value="<?php echo $type4->slug; ?>" class="level4" <?php selected( $type4->slug, $get_type ); ?>>
																		<?php echo $type4->name; ?>
					                    			</option>
					                    			<?php endforeach; ?>
					                    		</optgroup>
					                    		<?php endif; ?>
					                    		</option>
					                  		<?php endforeach; ?>
					                  	</optgroup>
					                  	<?php endif; ?>
					                  </option>
					              <?php endforeach; ?>
					            </optgroup>
					            <?php endif; ?>
						        </option>
								    <?php endforeach; ?>
									</select>
								</div>
								<?php
								break;

								case 'estate_property_price' :
								?>
								<div class="<?php echo $columns; ?> form-group">

									<?php
										$pricerange_min = $realty_theme_option['property-search-price-range-min'];
										$pricerange_max = $realty_theme_option['property-search-price-range-max'];
										$pricerange_step = $realty_theme_option['property-search-price-range-step'];
									?>

									<?php if ( $realty_theme_option['property-search-price-dropdown'] == 'dropdown-range' ) { ?>

										<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
											<option value=""><?php echo $search_labels[$i]; ?></option>

											<?php $step_min = $pricerange_min; ?>

											<?php while ( $step_min <= $pricerange_max ) { ?>
												<?php
													if ( $step_min == $pricerange_min ) {
														// Min for first iteration
														$step_max = $pricerange_step - 1;
													} else {
														// Min for every other iteration
														$step_max = $step_min + $pricerange_step - 1;
													}
												?>
												<?php if ( $step_min == $pricerange_max ) { ?>
												<option value="<?php echo $step_min; ?>" <?php selected( isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : null, $step_min ); ?>><?php echo tt_get_formatted_price( $step_min ) . '+'; ?></option>
												<?php } else { ?>
												<option value="<?php echo $step_min . '-' . $step_max; ?>" <?php selected( isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : null, $step_min . '-' . $step_max ); ?>><?php echo tt_get_formatted_price( $step_min ) . ' - ' . tt_get_formatted_price( $step_max ); ?></option>
												<?php } ?>

												<?php $step_min = $step_min + $pricerange_step; ?>

											<?php } ?>
										</select>

									<?php } else if ( $realty_theme_option['property-search-price-dropdown'] == 'dropdown-steps' ) { ?>

										<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
											<option value=""><?php echo $search_labels[$i]; ?></option>
											<?php $i_option = $pricerange_min; ?>
											<?php while ( $i_option <= $pricerange_max ) { ?>
												<option value="<?php echo $i_option; ?>" <?php selected( isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : null, $i_option ); ?>><?php echo tt_get_formatted_price( $i_option ); ?></option>
												<?php $i_option = $i_option + $pricerange_step; ?>
											<?php } ?>
										</select>

									<?php } else { ?>
										<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : ''; ?>" placeholder="<?php echo $search_labels[$i]; ?>" min="0" class="form-control" />
									<?php } ?>

								</div>
								<?php
								break;

								case 'estate_property_price_min' :
								case 'estate_property_price_max' :
								case 'estate_property_size' :
								?>
								<div class="<?php echo $columns; ?> form-group">
									<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : ''; ?>" placeholder="<?php echo $search_labels[$i]; ?>" min="0" class="form-control" />
								</div>
								<?php
								break;

								// Dropdown options from 0 to 10
								case 'estate_property_rooms' :
								case 'estate_property_bedrooms' :
								case 'estate_property_bathrooms' :
								case 'estate_property_garages' :
								?>

								<div class="<?php echo $columns; ?> form-group">
									<?php if ( $realty_theme_option['property-search-field-dropdowns'] ) { ?>
										<select name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" class="<?php echo esc_attr( $form_select_class ); ?>">
											<option value=""><?php echo $search_labels[$i]; ?></option>
											<?php for ( $i_option = 0; $i_option <= 10; $i_option++ ) { ?>
											<option value="<?php echo $i_option; ?>" <?php selected( isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : null, $i_option ); ?>><?php echo $i_option; ?></option>
											<?php } ?>
										</select>
									<?php } else { ?>
											<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : ''; ?>" placeholder="<?php echo $search_labels[$i]; ?>" min="0" class="form-control" />
									<?php } ?>
								</div>
								<?php
								break;

								case 'estate_property_available_from' :
								?>
								<div class="<?php echo $columns; ?> form-group">
									<input type="number" name="<?php echo $search_parameter; ?>" id="<?php echo $search_parameter; ?>" value="<?php echo isset( $_GET[$search_parameter] ) ? $_GET[$search_parameter] : ''; ?>" placeholder="<?php echo $search_labels[$i]; ?>" min="0" class="form-control datepicker" />
								</div>
								<?php
								break;

								case 'estate_property_pricerange' :
								$pricerange_min = $realty_theme_option['property-search-price-range-min'];
								$pricerange_max = $realty_theme_option['property-search-price-range-max'];
								?>
								<div class="<?php echo $columns; ?> form-group price-range">
									<input type="number" name="price_range_min" class="property-search-price-range-min hide" value="<?php if ( isset( $_GET['price_range_min'] ) ) { echo $_GET['price_range_min']; } else { echo $pricerange_min; } ?>" />
									<input type="number" name="price_range_max" class="property-search-price-range-max hide" value="<?php if ( isset( $_GET['price_range_max'] ) ) { echo $_GET['price_range_max']; } else { echo $pricerange_max; } ?>" />
									<label><?php echo $search_labels[$i]; ?> <span class="price-range-min" id="price-range-min"></span> <?php esc_html_e( 'to', 'realty' ); ?> <span class="price-range-max" id="price-range-max"></span></label>
									<div class="price-range-slider" id="price-range-slider"></div>
								</div>
								<?php
								break;

							}

						} // Default Property Field

						// ACF: Custom Property Field
						else if ( tt_acf_active() ) {

							// Get ACF Field Type
							$acf_field_position = array_search( $search_field, tt_acf_fields_name( tt_acf_group_id_property() ) );
							$acf_field_type_key = tt_acf_fields_type( tt_acf_group_id_property() );
							$acf_field_type = $acf_field_type_key[$acf_field_position];

							// Single value based ACF fields, that appear next to default fields. Arrays such as checkboxes & radio buttons are shown under "more".
							// $acf_supported_field_types = array( 'text', 'number', 'email', 'date_picker', 'select' );

							//if ( in_array( $acf_field_type, $acf_supported_field_types ) ) {
								echo '<div class="' . $columns . ' form-group">';
							//}

								// Field Type: Select, Checkbox
								if ( $acf_field_type == 'select' || $acf_field_type == 'checkbox' || $acf_field_type == 'radio' ) {
									//echo $acf_field_type.$search_field;
									$field_group_ids = array();
									$acf_custom_keys = array();
									$field_group_ids = tt_acf_group_id_property();

									foreach ( $field_group_ids as $field_group ) {
										$field_group_type=get_post_type( $field_group );
										if ( $field_group_type == 'acf' || $field_group_type == 'acf-field-group' ) {
											$acf_custom_keys[] = get_post_custom_keys( $field_group );
										} else {
											$acf_field_key = get_post( $field_group );
											$acf_custom_keys[] = $acf_field_key->post_name;
										}
									}

										// ACF: Loop through field keys, as we can't output choices by name, but only by their key
										foreach ( $acf_custom_keys as $value ) {

											if ( ! is_array( $value ) ) {

											  if ( stristr( $value, 'field_' ) ) {

												  $acf_field = get_field_object( $value );

												  if ( $acf_field['name'] == $search_field ) {

														// Field Type: Select
														if ( $acf_field_type == 'select' ) {
															echo '<select name="' . $acf_field['name'] . '" data-placeholder="' . $search_labels[$i] . '" class="'.$form_select_class.'">';
															echo '<option value="all">' . $search_labels[$i] . '</option>';
															foreach( $acf_field['choices'] as $key => $value ) {
																// Default value
																if ( $acf_field['value'] == $key ) {
																	$selected = "selected";
																} else {
																	$selected = "";
																}
																echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
															}
															echo '</select>';
														}

														// Checkbox, Radio
														if ( $acf_field_type == 'checkbox' || $acf_field_type == 'radio' ) {
															$check_field_label = '';
															if ( isset( $search_labels[$i] ) && ! empty( $search_labels[$i] ) ) {
																$check_field_label = $search_labels[$i];
															} else {
																$check_field_label = $acf_field['label'];
															}
															$output  = '<h6>' . $check_field_label . '</h6>';
															$output .= '<div class="row">';

															foreach( $acf_field['choices'] as $key => $value ) {

																// Default value

																if ( $acf_field['value'] == $key ) {
																	$checked = "checked";
																}
																else {
																	$checked = "";
																}

																if ( !empty( $_GET[ $search_parameter ] ) ) {
																	if ( $_GET[ $search_parameter ] == $key ) {
																		$checked = "checked";
																	}
																}

																// Output under "more"
																$output .= '<div class="' . $columns . ' form-group">';
																$output .= '<input type="' . $acf_field_type . '" name="' . $search_field . '" id="' . $search_field . '-' . $key . '" value="' . $key . '" ' . $checked . ' />';
																$output .= '<label for="' . $search_field . '-' . $key . '">' . $value . '</label>';
																$output .= '</div>';

															}

															$output .= '</div>';

															$acf_field_array[] = $output;

														}

														} // ['name']

												 } // _field

											} // ! is_array

										} // foreach

									} // Field Type: Select, Checkbox, Radio

									// Field Type: Text, Number, Email, Date Picker
									else if ( $acf_field_type == 'text' || $acf_field_type == 'number' || $acf_field_type == 'email' || $acf_field_type == 'date_picker' ) {

										$datepicker_class = '';

										switch ( $acf_field_type ) {
											case 'text' : $acf_field_type_output = 'text'; break;
											case 'number' : $acf_field_type_output = 'number'; break;
											case 'email' : $acf_field_type_output = 'email'; break;
											case 'date_picker' : $acf_field_type_output = 'number'; $datepicker_class = 'datepicker'; break;
										}

										if ( $acf_field_type == 'date_picker' ) {
											echo '<div class="input-group">';
										}

										$value = '';

										if ( isset( $_GET[ $search_parameter ] ) ) {
											$value = $_GET[ $search_parameter ];
										}

										echo '<input type="' . $acf_field_type_output . '" name="' . $search_parameter . '" value="' . $value . '" placeholder="' . $search_labels[$i] . '" class="form-control ' . $datepicker_class . '" />';

										if ( $acf_field_type == 'date_picker' ) {
											echo '<span class="input-group-addon"><i class="icon-calendar"></i></span>';
											echo '</div>';
										}

									}


							//if ( in_array( $acf_field_type, $acf_supported_field_types ) ) {
								echo '</div>'; // .col-xx-x
							//}

							wp_reset_postdata();

						} // Custom Property Field tt_acf_active()

					}

					$i++;

				} // foreach()

			?>

			<div class="<?php echo $columns; ?> form-group">
				<input type="submit" value="<?php esc_html_e( 'Search', 'realty' ); ?>" class="btn btn-primary btn-block form-control" />
			</div>

			<?php } else { ?>

				<div class="col-xs-12" style="margin-bottom: 20px">
					<p class="alert alert-info">

						<?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) { ?>
							<a href="<?php echo admin_url( 'themes.php?page=theme-options' ); ?>" target="_blank">
						<?php } ?>

						<?php esc_html_e( 'Please setup "Property "Search" under "Appearance > Theme Options > Property Search".', 'realty' ); ?>

						<?php if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) { ?>
							</a>
						<?php } ?>

					</p>
				</div>

			<?php }	?>

	</div>

	<?php if ( $property_search_features || $acf_field_array ) { ?>

		<p>
			<a href="#" class="toggle-property-search-more">
				<span class="more"><?php esc_html_e( 'Show more search options', 'realty' ); ?></span>
				<span class="less hide"><?php esc_html_e( 'Hide additional search options', 'realty' ); ?></span>
			</a>
		</p>

		<?php if ( $acf_field_array ) { ?>
			<div class="property-search-more">
				<?php
					foreach ( $acf_field_array as $acf_field_single_array ) {
						echo $acf_field_single_array;
					}
				?>
			</div>
		<?php } ?>

		<?php if ( $property_search_features ) { ?>
			<div class="property-search-more">
				<h6><?php esc_html_e( 'Property features:', 'realty' ); ?></h6>
				<div class="row">
				<?php
				  $get_features = '';
					$get_feature = '';
				?>

				<?php
					foreach ( $property_search_features as $property_search_feature ) {

						$feature = get_term_by( 'id', $property_search_feature, 'property-features' );

						if ( isset( $_GET['feature'] ) ) {
							$get_features = $_GET['feature'];
							if ( is_array( $get_features ) && in_array( $feature->slug, $get_features ) ) {
								$get_feature = $feature->slug;
							}
						} else {
							$get_feature = '';
						}
					?>

					<div class="<?php echo $columns; ?> form-group">
						<input name="feature[]" id="property-search-feature-<?php echo $property_search_feature; ?>" class="property-search-feature" type="checkbox" value="<?php echo $feature->slug; ?>" <?php checked( $feature->slug, $get_feature ); ?> />
						<label for="property-search-feature-<?php echo $property_search_feature; ?>"><?php echo $feature->name; ?></label>
					</div>

				<?php } ?>

				</div>
			</div>
		<?php } ?>

	<?php } ?>

	<input type="hidden" name="pageid" value="<?php the_id(); ?>" />
  <?php if ( get_query_var( 'property_search_id' ) ) { ?>
    <input type="hidden" name="searchid" value="<?php echo get_query_var('property_search_id'); ?>" />
  <?php } ?>

	<?php wp_reset_query(); ?>

</form>

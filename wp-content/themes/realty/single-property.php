<?php get_header(); ?>

<?php
	global $realty_theme_option;

	if ( $realty_theme_option['property-show-login-users'] && ! is_user_logged_in() ) {
		$show_property = false;
	} else {
		$show_property = true;
	}

	// Check: Are we trying to retrieve page template "Single Property Home Page" or single-property.php
	if ( isset( $page_template_single_property ) && ! empty( $page_template_single_property ) ) {
		// "Single Property Home Page"
		$single_property_id = $page_template_single_property;
	} else {
		// Single Property
		$single_property_id = $post->ID;
	}
?>

<?php if ( $show_property ) { ?>

	<?php
		$property_location = get_the_terms( $single_property_id, 'property-location' );
		$property_status = get_the_terms( $single_property_id, 'property-status' );
		$property_type = get_the_terms( $single_property_id, 'property-type' );

		$date_format = get_option( 'date_format' );
		$today = current_time( $date_format );
		$last_updated_on = date_i18n( get_option( 'date_format' ), strtotime( $post->post_modified ) );

		$available_from = get_post_meta( $single_property_id, 'estate_property_available_from', true );
		$available_from_date = date_i18n(get_option( 'date_format' ), strtotime( $available_from ) );
		if ( ! empty( $available_from ) ) {
			$available_from_meta = get_field_object( 'estate_property_available_from', $single_property_id );
			$available_from_label = $available_from_meta['label'];
		}

		$single_property_layout = get_post_meta( $single_property_id, 'estate_property_layout', true );
		$property_status_update = get_post_meta( $single_property_id, 'estate_property_status_update', true );
		$property_video_provider = get_post_meta( $single_property_id, 'estate_property_video_provider', true );
		$property_video_id = get_post_meta( $single_property_id, 'estate_property_video_id', true );
		$property_images = get_post_meta( $single_property_id, 'estate_property_gallery', true );
		$featured = get_post_meta( $single_property_id, 'estate_property_featured', true );
		$property_id = get_post_meta( $single_property_id, 'estate_property_id', true );

		$price = intval( get_post_meta( $single_property_id, 'estate_property_price', true ) );

		$size = get_post_meta( $single_property_id, 'estate_property_size', true );
		$size_unit = get_post_meta( $single_property_id, 'estate_property_size_unit', true );
		if ( ! empty( $size ) ) {
			$size_meta = get_field_object( 'estate_property_size', $single_property_id );
			$size_label = $size_meta['label'];
		}

		$rooms = get_post_meta( $single_property_id, 'estate_property_rooms', true );
		if ( ! empty( $rooms ) ) {
			$rooms_meta = get_field_object('estate_property_rooms', $single_property_id);
			$rooms_label = $rooms_meta['label'];
		}

		$bedrooms = get_post_meta( $single_property_id, 'estate_property_bedrooms', true );
		if ( ! empty( $bedrooms ) ) {
			$bedrooms_meta = get_field_object( 'estate_property_bedrooms', $single_property_id );
			$bedrooms_label = $bedrooms_meta['label'];
		}

		$bathrooms = get_post_meta( $single_property_id, 'estate_property_bathrooms', true );
		if ( ! empty ( $bathrooms ) ) {
			$bathrooms_meta = get_field_object( 'estate_property_bathrooms', $single_property_id );
			$bathrooms_label = $bathrooms_meta['label'];
		}

		$garages = get_post_meta( $single_property_id, 'estate_property_garages', true );
		if ( ! empty( $garages ) ) {
			$garages_meta = get_field_object( 'estate_property_garages', $single_property_id );
			$garages_label = $garages_meta['label'];
		}

		$property_contact_information = get_post_meta( $single_property_id, 'estate_property_contact_information', true );
		$property_header_meta_type = '';
		$property_header_meta_status = '';
		if ( $property_type ) {
			foreach ( $property_type as $type ) {
				$property_header_meta_type =  $type->name;
				break;
			}
		}
		if ( $property_status ) {
			foreach ( $property_status as $status ) {
				$property_header_meta_status = ' &middot; ' . $status->name;
				break;
			}
		}

	?>

	<?php
		$property_layout = $realty_theme_option['property-layout'];
		$property_meta_data_type = $realty_theme_option['property-meta-data-type'];
		$social_sharing = $realty_theme_option['property-social-sharing'];
		$property_contact_form_default_email = $realty_theme_option['property-contact-form-default-email'];
		$property_image_height = $realty_theme_option['property-contact-form-default-email'];
		$property_image_width = $realty_theme_option['property-image-width'];

		if ( ! isset( $property_image_width ) ) {
			$property_image_width = "full";
		}

		if ( $realty_theme_option['property-lightbox'] != "none" ) {
			$property_zoom = ' zoom';
		} else {
			$property_zoom = null;
		}

		$fit_or_cut = null;

		if ( $single_property_layout == 'theme_option_setting' || $single_property_layout == '' ) {
			if ( $property_layout == 'layout-full-width' ) {
				$layout = 'full-width';
			} else {
				$layout = 'boxed';
			}
		} else {
			if ( $single_property_layout == 'full_width' ) {
				$layout = 'full-width';
			} else {
				$layout = 'boxed';
			}
		}

		function wp_get_attachment_meta_data_title() {
			$attachment = get_post( get_post_thumbnail_id() );
			return $attachment->post_title;
		}
	?>

	<?php if ( $layout == 'boxed' ) { ?>
		<div class="container">
	<?php } ?>

	<div id="property-layout-<?php echo $layout; ?>">

		<?php
			$image_slider_id = 'property_image_slider';
		  include get_template_directory() . '/lib/inc/template/single-property-slideshow.php';
		?>

		<div class="property-header-container">

			<?php if ( $layout == "full-width" ) { ?>
				<div class="container">
			<?php } ?>

				<div class="property-header">
					<h1 class="title">
						<span><?php echo get_the_title( $single_property_id ); ?></span>
						<div class="clearfix mobile"></div>
						<span><?php echo tt_add_remove_favorites( $single_property_id ); ?></span>
						<span><a href="#location"><i class="icon-pin-full" data-toggle="tooltip" title="<?php esc_html_e( 'Show Location', 'realty' );  ?>"></i></a></span>
						<span><?php echo tt_add_remove_follow( $single_property_id ); ?></span>
						<?php echo tt_icon_property_video( $single_property_id ); ?>
					</h1>
					<div class="clearfix"></div>

					<div class="meta"><?php echo $property_header_meta_type . $property_header_meta_status; ?></div>
					<div class="clearfix"></div>
					<div class="meta"><?php echo tt_property_price( $single_property_id ); ?></div>
					<div class="clearfix"></div>
					<?php if ( $property_status_update ) { ?>
						<div class="btn btn-dark btn-sm status-update"><?php echo $property_status_update; ?></div>
					<?php } ?>
				</div>

			<?php if ( $layout == "full-width" ) { ?>
				</div>
			<?php } ?>

		</div><!-- .property-header-container -->

	</div>

	<?php if ( $layout == 'boxed' ) { ?>
		</div><!-- .container -->
	<?php } ?>

	<?php if ( $realty_theme_option['property-slideshow-navigation-type'] == 'thumbnail' ) { ?>
		<div class="container">
			<?php include get_template_directory() . '/lib/inc/template/single-property-slideshow-thumbnails.php'; ?>
		</div>
	<?php } ?>

	<?php wp_reset_postdata(); ?>

	<div class="property-meta container">
		<div class="row">

			<?php if ( $property_meta_data_type == 'custom' ) { ?>

				<?php
					// Use Custom Meta Data
					$property_meta_data_field = $realty_theme_option['property-custom-meta-data-field'];
					$property_meta_data_icon_class = $realty_theme_option['property-custom-meta-data-icon-class'];
					$property_meta_data_label = $realty_theme_option['property-custom-meta-data-label'];
					$property_meta_data_label_plural = $realty_theme_option['property-custom-meta-data-label-plural'];
					$property_meta_data_tooltip = $realty_theme_option['property-custom-meta-data-tooltip'];

					$i = 0;
				?>

				<?php foreach ( $property_meta_data_field as $field_type ) { ?>

					<?php
						switch ( $field_type ) {

							case 'estate_property_id' :
								if ( $realty_theme_option['property-id-type'] == 'custom_id' ) {
									$field = $property_id;
								} else {
									$field = $single_property_id;
								}
							break;

							case 'estate_property_available_from' :
								if ( ! empty( $available_from_date ) ) {
									$field = $available_from_date;
								}
							break;

							case 'estate_property_updated' :
								$field = $last_updated_on;
							break;

							case 'estate_property_views' :
								$field = tt_get_property_views( $single_property_id );
							break;

							case 'estate_property_size' :
								$size_unit = get_post_meta( $single_property_id, 'estate_property_size_unit', true );
								$field = get_post_meta( $single_property_id, $field_type, true );
								if ( ! empty( $field ) ) {
								  $field = $field . ' ' . $size_unit;
								}
							break;

							default :
								$field = get_post_meta( $single_property_id, $field_type, true );
							break;

						}
					?>

					<?php if ( ! empty ( $field ) ) { ?>
						<div class="col-sm-4 col-md-3">
							<div class="meta-title">
								<i class="<?php echo $property_meta_data_icon_class[$i]; ?>"></i>
							</div>
							<div class="meta-data" data-toggle="tooltip" title="<?php echo _n( $property_meta_data_label[$i], $property_meta_data_label_plural[$i], $field, 'realty' ); ?>">
								<?php
									echo $field;

									if ( $property_meta_data_tooltip[$i] == false ) {
										echo ' ' . _n( $property_meta_data_label[$i], $property_meta_data_label_plural[$i], $field, 'realty' );
									}
								?>
							</div>
						</div>
					<?php } ?>

					<?php $i++; ?>

				<?php } // foreach ?>

			<?php } else { // Default Meta Data ?>

				<?php if ( $available_from ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-clock"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $available_from_label; ?>"><?php echo $available_from_date; ?></div>
				</div>
				<?php } ?>

				<?php if ( $size ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-size"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $size_label; ?>"><?php echo $size . ' ' . $size_unit; ?></div>
				</div>
				<?php } ?>

				<?php if ( $rooms ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-rooms"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $rooms_label; ?>"><?php echo $rooms . ' ' . _n( $rooms_label, $rooms_label, $rooms, 'realty' ); ?></div>
				</div>
				<?php } ?>

				<?php if ( $bedrooms ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-bedrooms"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $bedrooms_label; ?>"><?php echo $bedrooms . ' ' . _n( $bedrooms_label, $bedrooms_label, $bedrooms, 'realty' ); ?></div>
				</div>
				<?php } ?>

				<?php if ( $bathrooms ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-bathrooms"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $bathrooms_label; ?>"><?php echo $bathrooms . ' ' . _n( $bathrooms_label, $bathrooms_label, $bathrooms, 'realty' ); ?></div>
				</div>
				<?php } ?>

				<?php if ( $garages ) { ?>
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="icon-garage"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo $garages_label; ?>"><?php echo $garages . ' '. _n( $garages_label, $garages_label, $garages, 'realty' ); ?></div>
				</div>
				<?php } ?>

	      <?php
					$estate_property_id = '';

					if ( $realty_theme_option['property-id-type'] == "post_id" ) {
					  $estate_property_id = $single_property_id;
					} else {
					  $estate_property_id = $property_id;
					}
				?>

	      <?php if ( ! empty ( $estate_property_id ) ) { ?>
					<div class="col-sm-4 col-md-3">
						<div class="meta-title"><i class="icon-hash"></i></div>
						<div class="meta-data" data-toggle="tooltip" title="<?php esc_html_e( 'Property ID', 'realty' ); ?>">
							<?php echo $estate_property_id; ?>
						</div>
					</div>
			  <?php } ?>

			<?php } ?>

			<?php if ( ! $realty_theme_option['property-meta-data-hide-print'] ) { ?>
				<div class="col-sm-4 col-md-3">
					<a href="#" id="print">
						<div class="meta-title"><i class="icon-print"></i></div>
						<div class="meta-data"><?php esc_html_e( 'Print this page', 'realty' ); ?></div>
					</a>
				</div>
			<?php } ?>

		</div><!-- .row -->
	</div><!-- .property-meta -->

	<div class="container">
		<div class="row">

			<?php if ( is_active_sidebar( 'sidebar_property' ) ) { ?>
				<div class="col-sm-8 col-md-9">
			<?php } else { ?>
				<div class="col-sm-12">
			<?php }	?>

			<div id="main-content">

				<?php
					$post_content = get_post_field( 'post_content', $single_property_id );
					if ( $post_content ) {
				?>
					<section id="property-content">
						<?php
							$property_title_details = $realty_theme_option['property-title-details'];
							if ( $property_title_details ) {
								echo '<h3 class="section-title"><span>' . $property_title_details . '</span></h3>';
							}
						?>
						<?php do_action( 'tt_single_property_content_before' ); ?>
						<?php echo apply_filters( 'the_content', get_post_field( 'post_content', $single_property_id ) ); ?>
						<?php do_action( 'tt_single_property_content_after' ); ?>
					</section>
				<?php } ?>

				<?php $property_features = get_the_terms( $single_property_id, 'property-features' ); ?>
				<?php	if ( $property_features ) { ?>
					<section id="property-features" class="primary-tooltips">
						<?php include get_template_directory() . '/lib/inc/template/single-property-features.php'; ?>
					</section>
				<?php } ?>

				<?php if ( tt_acf_active() && tt_acf_group_id_property() ) : // Check if ACF plugin is active & for post type "property" field group ?>

					<?php
						$acf_fields_name = tt_acf_fields_name( tt_acf_group_id_property() );
						$acf_fields_label = tt_acf_fields_label( tt_acf_group_id_property() );
						$acf_fields_type = tt_acf_fields_type( tt_acf_group_id_property() );
						$acf_fields_count = count( $acf_fields_name );
						$empty_field = false;
						$i = 0;
					?>

					<?php
						if ( $acf_fields_count > 0 ) {
							include get_template_directory() . '/lib/inc/template/sigle-property-additional-details.php';
						}
					?>

					<?php
					wp_reset_postdata();
					endif;
				?>

				<?php
					/**
					 * Section: Floor Plan
					 *
					 */
					$property_floor_plan_disable = $realty_theme_option['property-floor-plan-disable'];
					$property_floor_plans = get_field( 'estate_property_floor_plans', $single_property_id );
					if ( ! $property_floor_plan_disable && $property_floor_plans ) {
				?>
				<section id="floor-plan" class="primary-tooltips">
					<?php include get_template_directory() . '/lib/inc/template/single-property-floor-plan.php'; ?>
				</section>
				<?php }	?>

				<?php
					/**
					 * Section: Map
					 *
					 */
					$google_maps = get_post_meta( $single_property_id, 'estate_property_google_maps', true );

					if ( ! tt_is_array_empty( $google_maps ) ) {
						$address = $google_maps['address'];
						if ( $google_maps['lat'] ) {
							$address_latitude = $google_maps['lat'];
					  } else {
							$address_latitude = null;
						}
						if ( $google_maps['lng'] ) {
							$address_longitude = $google_maps['lng'];
						} else {
							$address_longitude = null;
						}
					} else {
						$address = null;
					}
				?>

				<?php if ( ! empty ( $address ) && $realty_theme_option['show-single-property-map']  ) { ?>
					<section id="location">
						<?php include get_template_directory() . '/lib/inc/template/single-property-map.php'; ?>
					</section>
				<?php } ?>

				<?php
					/**
					 * Section: Attachments
					 *
					 */
					$property_attachments_acf = get_field( 'estate_property_attachments_repeater', $single_property_id );
					if ( $property_attachments_acf ) {
						$property_attachments = array();
						while ( has_sub_field( 'estate_property_attachments_repeater', $single_property_id ) ) {
							$property_attachment = get_sub_field( 'estate_property_attachment', $single_property_id );
							if ( isset( $property_attachment['id'] ) ) {
								$property_attachments[] = $property_attachment['id'];
							}
						}
					}
				?>

				<?php if ( ! empty( $property_attachments_acf ) ) { ?>
					<section id="attachments">
						<?php include get_template_directory() . '/lib/inc/template/single-property-attachments.php'; ?>
					</section>
				<?php }	?>

				<?php if ( $social_sharing ) { ?>
					<section class="primary-tooltips"><?php echo tt_social_sharing(); ?></section>
				<?php } ?>

				<?php
					/**
					 * Section: Agent
					 *
					 */
					if ( ! is_user_logged_in() && $realty_theme_option['property-show-agent-to-logged-in-users'] ) {
						$show_agent_info = false;
					} else {
						$show_agent_info = true;
					}

					$show_agent_information = $realty_theme_option['property-agent-information'];
				?>

				<?php if ( $show_agent_info == true && $show_agent_information ) { ?>
					<section id="agent">
						<?php
							// Property Settings: If "Assign Agent" selected, show his/her information, if not show post author.
							$agent = get_post_meta( $single_property_id, 'estate_property_custom_agent', true );

							if ( $agent ) {
								if ( is_array( $agent ) ) {
									// @since 3.0: If multiple agents assigned to property, pick first agent
									$agent_id = $agent[0];
								} else {
									$agent_id = $agent;
								}
							} else {
								$agent_id = get_the_author_meta( 'ID' );
							}

							$property_title_agent = $realty_theme_option['property-title-agent'];
						  if ( $property_title_agent ) {
								echo '<h3 class="section-title"><span>' . $property_title_agent . '</span></h3>';
							}

							$company_name = get_user_meta( $agent_id, 'company_name', true );
							$first_name = get_user_meta( $agent_id, 'first_name', true );
							$last_name = get_user_meta( $agent_id, 'last_name', true );
							$email = get_userdata( $agent_id );
							$email = $email->user_email;
							$office = get_user_meta( $agent_id, 'office_phone_number', true );
							$mobile = get_user_meta( $agent_id, 'mobile_phone_number', true );
							$fax = get_user_meta( $agent_id, 'fax_number', true );
							$website = get_userdata( $agent_id );
							$website = $website->user_url;
							$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
							$bio = get_user_meta( $agent_id, 'description', true );
							$profile_image = get_user_meta( $agent_id, 'user_image', true );
							$author_profile_url = get_author_posts_url( $agent_id );
							$facebook = get_user_meta( $agent_id, 'custom_facebook', true );
							$twitter = get_user_meta( $agent_id, 'custom_twitter', true );
							$google = get_user_meta( $agent_id, 'custom_google', true );
							$linkedin = get_user_meta( $agent_id, 'custom_linkedin', true );

							if ( $show_agent_information && ( $property_contact_information == 'all' || $property_contact_information == '' ) ) {
								if ( $show_agent_info == true ) {
								 include get_template_directory() . '/lib/inc/template/agent-information.php';
								} else {
								  echo '<p class="alert alert-danger">' . esc_html__( 'You have to be logged-in to view agent details. Click Login/Register in the top menu.', 'realty' ) . '</p>';
								}
							}
						?>
					</section>

				<?php } ?>

				<?php
					/**
					 * Section: Contact Form
					 * Check Theme Option + Property Settings For Author Contact Form
					 *
					 */
					$show_property_contact_form = $realty_theme_option['property-contact-form'];
					if ( $show_property_contact_form && $property_contact_information != 'none' ) {
						include get_template_directory() . '/lib/inc/template/contact-form.php';
					}
				?>

				<?php
					/**
					 * Section: Similar Properties
					 *
					 */
					if ( $realty_theme_option['property-show-similar-properties'] ) {
						include get_template_directory() . '/lib/inc/template/single-property-similar-properties.php';
					}
				?>

				<?php
					/**
					 * Section: Property Comments
					 *
					 */
					if ( $realty_theme_option['property-comments'] && ( comments_open() || get_comments_number() ) ) {
						comments_template();
					}
				?>

			</div><!-- #main-container -->

			<?php
				if ( $realty_theme_option['property-single-slideshow-autoplay'] ) {
					$autoplay = true;
				} else {
					$autoplay = false;
				}

				if ( $realty_theme_option['property-slideshow-navigation-type'] == 'thumbnail' ) {
					$as_nav_for = true;
					$show_dots = false;
				} else {
					$as_nav_for = false;
					$show_dots = true;
				}

				if ( $realty_theme_option['property-slideshow-animation-type'] == 'fade' ) {
					$fade = true;
				} else {
					$fade = false;
				}

				$slider_params = array(
					'id'                            => $image_slider_id,
					'images_to_show'                => 1,
					'images_to_show_lg'             => 1,
					'images_to_show_md'             => 1,
					'images_to_show_sm'             => 1,
					'autoplay'                      => $autoplay,
					'autoplay_speed'                => 5000,
					'fade'                          => $fade,
					'infinite'                      => false,
					'show_arrows'                   => false,
					'show_arrows_below'             => false,
					'show_dots'                     => true, //$show_dots,
					'show_dots_below'               => false,
					'property_slider_height'        => $realty_theme_option['property-image-height'],
					'property_slider_custom_height' => $realty_theme_option['property-image-custom-height'],
					'as_nav_for'                    => $as_nav_for,
				);

				tt_script_slick_slider( $slider_params );
			?>

			</div><!-- .col-sm-9 -->

			<?php if ( is_active_sidebar( 'sidebar_property' ) ) : ?>
				<div class="col-sm-4 col-md-3">
					<ul id="sidebar">
						<?php dynamic_sidebar( 'sidebar_property' ); ?>
					</ul>
				</div>
			<?php endif; ?>

		</div><!-- .row -->
	</div><!-- .container -->

<?php } else { // Show property only to logged in users ?>
	<div class="container">
		<p class="alert alert-info"><?php esc_html_e( 'You have to be logged-in to view property details. Click Login/Register in the top menu.', 'realty' ); ?></p>
	</div>
<?php } ?>

<?php get_footer(); ?>

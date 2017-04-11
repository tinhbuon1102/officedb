<?php
/**
 * Shortcode: Property Slider
 *
 */
if ( ! function_exists( 'tt_realty_property_slider' ) ) {
	function tt_realty_property_slider( $atts ) {

		extract( shortcode_atts( array(
			'id'                   => 'property_slider_' . rand(),
			'show_featured_only'   => false,
			'total'                => 3,
			'property_search_form' => null,
			'posts_in'             => null,
			'show_title'           => false,
			'show_excerpt'         => false,
			'show_price'           => false,
			'show_size'            => false,
			'show_rooms'           => false,
			'show_bedrooms'        => false,
			'show_bathrooms'       => false,
			'link'                 => 'link_post',
			'custom_links'         => '',
			'height'               => 'fullscreen',
			'custom_height'        => 600,
			'thumb_size'           => 'thumbnail-16-9',
			'orderby'              => 'date',
			'order'                => 'DESC',
			'autoplay'             => false,
			'autoplay_speed'       => 5000,
			'fade'                 => true,
			'infinite'             => true,
			'lazyload'             => false,
			'show_arrows'          => false,
			'show_arrows_below'    => true,
			'show_dots'            => true,
			'show_dots_below'      => false,
			'extra_class_names'    => ''
		), $atts ) );

		ob_start();

		$classes[] = 'property-slider';

		if ( $show_arrows && $show_arrows_below ) {
			$classes[] = 'arrows-below';
		}

		if ( $show_dots_below ) {
			$classes[] = 'dots-below';
		}

		if ( $height ) {
			$classes[] = $height;
		}

		if ( $extra_class_names ) {
			$classes[] = $extra_class_names;
		}

		$classes = join(' ', $classes );

		// Post/Page ID
		if ( isset( $posts_in ) && ! empty( $posts_in ) ) {
			$post_in = explode( ',', $posts_in );
		} else {
			$post_in = null;
		}

		$args_property_slider = array(
			'post_type'      => 'property',
			'post_status'    => 'publish',
			'posts_per_page' => $total,
			'orderby'        => $orderby,
			'order'          => $order,
			'post__in'       => $post_in,
		);

		if ( $show_featured_only ) {
			$meta_query[] = array(
				'key'	  	=> 'estate_property_featured',
				'value'	  	=> '1',
				'compare' => 'LIKE'
			);
			$args_property_slider['meta_query'] = $meta_query;
		}

		if ( $link == 'custom_link' ) {
			$custom_links = explode( ',', $custom_links );
		}

		$i = 0;

		$query_property_slider = new WP_Query( $args_property_slider );

		if ( $query_property_slider->have_posts() ) : ?>

			<div class="<?php echo esc_attr( $classes ); ?>">

				<?php if ( $property_search_form ) { ?>
					<div class="slideshow-search">
						<div class="container">
							<?php
								if ( $property_search_form == 'mini' ) {
									get_template_part( 'lib/inc/template/search-form-mini' );
								} else if ( $property_search_form == 'custom' ) {
									get_template_part( 'lib/inc/template/search-form' );
								}
							?>
						</div>
					</div>
				<?php }	?>

				<div class="hide-initially" id="<?php echo esc_attr( $id ); ?>">

					<?php while ( $query_property_slider->have_posts() ) : $query_property_slider->the_post(); ?>

						<?php
						global $realty_theme_option, $post;
						$current_item_post_type = get_post_type();
						$excerpt = get_the_excerpt();
						?>

						<?php if ( has_post_thumbnail() ) { ?>

							<?php
							$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
							$image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;

							if ( $lazyload ) {
								$image = get_the_post_thumbnail( get_the_ID(), $thumb_size, array(
									'title' => $image_title,
									'alt'   => $image_title,
									'src'   => null,
									'data-lazy' => get_the_post_thumbnail_url( get_the_ID(), $thumb_size ),
								) );
							} else {
								$image = get_the_post_thumbnail( get_the_ID(), $thumb_size, array(
									'title' => $image_title,
									'alt'   => $image_title,
									'src' => get_the_post_thumbnail_url( get_the_ID(), $thumb_size ),
								) );
							}

							$title = get_the_title();
							$url = get_the_permalink();

							if ( $link == 'custom_link' ) {
								if ( isset( $custom_links[$i] ) && ! empty( $custom_links[$i] ) ) {
									$url = $custom_links[$i];
								} else {
									$url = null;
								}
							}
							?>

							<div class="slide-item">

								<?php if ( $height != 'original' ) { ?>
								<style>
									.slider-image-<?php the_id(); ?> {
										background-image: url("<?php echo get_the_post_thumbnail_url( get_the_ID(), $thumb_size ); ?>");
										background-size: cover;
										background-position: center center;
									}
								</style>
								<?php } ?>

								<div class="property-image slider-image-<?php echo get_the_id(); ?>">
									<?php if ( $height == 'original' ) { echo $image; } ?>
								</div>

								<div class="wrapper-out">
									<div class="wrapper">
										<div class="inner<?php if ( $property_search_form ) { echo ' bottom'; } ?>">
											<div class="container">

												<div class="content">

													<?php if ( $show_title ) { ?>

														<h3 class="title">
															<?php if ( $link != 'link_no' ) { ?>
																<a href="<?php echo $url; ?>"><?php echo $title; ?></a>
															<?php } ?>

															<?php echo tt_icon_property_video(); ?>
														</h3>

													<?php } ?>

													<?php if ( ! $property_search_form ) {  // Hide when showing search form ?>

														<?php
															if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) {
																$arrow_class = 'arrow-left';
															} else {
																$arrow_class = 'arrow-right';
															}
														?>
														<div class="clearfix"></div>

														<div class="excerpt description">
															<?php if ( $show_excerpt == true && $excerpt ) { the_excerpt();	} ?>

															<?php if ( $show_title == true) { echo '<div class="' . $arrow_class . '"></div>'; } ?>

															<?php if ( $current_item_post_type == 'property' ) { ?>

																<?php
																	$size = get_post_meta( $post->ID, 'estate_property_size', true );
																	$size_unit = get_post_meta( $post->ID, 'estate_property_size_unit', true );
																	$bedrooms = get_post_meta( $post->ID, 'estate_property_bedrooms', true );
																	$bathrooms = get_post_meta( $post->ID, 'estate_property_bathrooms', true );
																?>

																<div class="property-data">
																	<?php if ( $show_price == true ) { ?><div class="property-price"><?php echo tt_property_price(); ?></div><?php } ?>
																	<div class="property-details">
																		<?php if ( $show_size == true ) { ?><div><i class="icon-size"></i><?php echo $size . $size_unit; ?></div><?php } ?>
																		<?php if ( $show_bedrooms == true ) { ?><div><i class="icon-bedrooms"></i><?php echo $bedrooms . ' ' . _n( esc_html__( 'Bedroom', 'realty' ), esc_html__( 'Bedrooms', 'realty' ), $bedrooms, 'realty' ); ?></div><?php } ?>
																		<?php if ( $show_bathrooms == true ) { ?><div><i class="icon-bathrooms"></i><?php echo $bathrooms . ' ' . _n( esc_html__( 'Bathroom', 'realty' ), esc_html__( 'Bathrooms', 'realty' ), $bathrooms, 'realty' ); ?></div><?php } ?>
																	</div>
																</div>

															<?php } ?>
														</div><!-- .description -->

													<?php } // $property_search_form == 'none' ?>

												</div><!-- .content -->

											</div>
										</div>
									</div>
								</div>

							</div><!-- .slide-item -->

						<?php } ?>

						<?php $i++; ?>

					<?php endwhile; ?>

				</div>

				<?php if ( $show_arrows_below ) { ?>
					<div class="arrow-container" id="arrow-container-<?php echo $id; ?>"></div>
				<?php } ?>

			</div>

			<?php wp_reset_postdata(); ?>

		<?php endif; ?>

		<?php
			$slider_params = array(
				'id'                            => $id,
				'images_to_show'                => 1,
				'images_to_show_lg'             => 1,
				'images_to_show_md'             => 1,
				'images_to_show_sm'             => 1,
				'autoplay'                      => $autoplay,
				'autoplay_speed'                => $autoplay_speed,
				'fade'                          => $fade,
				'infinite'                      => $infinite,
				'show_arrows'                   => $show_arrows,
				'show_arrows_below'             => $show_arrows_below,
				'show_dots'                     => $show_dots,
				'show_dots_below'               => $show_dots_below,
				'property_slider_height'        => $height,
				'property_slider_custom_height' => $custom_height,
			);

			tt_script_slick_slider( $slider_params );
		?>

		<?php  return ob_get_clean();
	}
}
add_shortcode( 'property_slider', 'tt_realty_property_slider' );

// Visual Composer Map
function tt_vc_map_realty_property_slider() {
	vc_map( array(
		'name' => esc_html__( 'Property Slider', 'realty' ),
		'base' => 'property_slider',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show only featured properties', 'realty' ),
				'param_name' => 'show_featured_only',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Slider count', 'realty' ),
				'param_name' => 'total',
				'value' => '3',
				'description' => esc_html__( 'Enter number of slides to display. Enter "-1" to list all vehicles. Ignored when "Show only featured properties" is selected.', 'realty' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Property IDs', 'realty' ),
				'param_name' => 'posts_in',
				'description' => esc_html__( 'Enter property IDs to display only those records. Separate by commas.', 'realty' ),
			),
			/*
			array(
				'type' => 'posttypes',
				'heading' => esc_html__( 'Post types', 'realty' ),
				'param_name' => 'posttypes',
				'description' => esc_html__( 'Select source for slider.', 'realty' ),
			),
			*/
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Property Search Form', 'realty' ),
				'param_name' => 'property_search_form',
				'value' => array(
					esc_html__( 'None', 'realty' )   => '',
					esc_html__( 'Custom', 'realty' ) => 'custom',
					esc_html__( 'Mini', 'realty' )   => 'mini',
				),
				'description' => esc_html__( 'Search forms have to be configured first under "Appearance > Theme Options > Property Search".', 'realty' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show title', 'realty' ),
				'param_name' => 'show_title',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show excerpt', 'realty' ),
				'param_name' => 'show_excerpt',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show price', 'realty' ),
				'param_name' => 'show_price',
				'value' => array( '' => true ),
				'std' => true,

			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show size', 'realty' ),
				'param_name' => 'show_size',
				'value' => array( '' => true ),
				'std' => true,

			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show rooms', 'realty' ),
				'param_name' => 'show_rooms',
				'value' => array( '' => true ),

			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show bedrooms', 'realty' ),
				'param_name' => 'show_bedrooms',
				'value' => array( '' => true ),
				'std' => true,

			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show bathrooms', 'realty' ),
				'param_name' => 'show_bathrooms',
				'value' => array( '' => true ),
				'std' => true,

			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Link type', 'realty' ),
				'param_name' => 'link',
				'value' => array(
					esc_html__( 'Link to property', 'realty' ) => 'link_post',
					esc_html__( 'Custom links', 'realty' )     => 'custom_link',
					esc_html__( 'No link', 'realty' )          => 'link_no',
				),
			),
			array(
				'type' => 'exploded_textarea',
				'heading' => esc_html__( 'Custom links', 'realty' ),
				'param_name' => 'custom_links',
				'value' => site_url() . '/',
				'dependency' => array(
					'element' => 'link',
					'value'   => 'custom_link',
				),
				'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'realty' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Slider height', 'realty' ),
				'param_name' => 'height',
				'value' => array(
					esc_html__( 'Fullscreen', 'realty' ) => 'fullscreen',
					esc_html__( 'Original', 'realty' )   => 'original',
					esc_html__( 'Custom', 'realty' )     => 'custom',
				),
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Custom height (in px)', 'realty' ),
				'param_name' => 'custom_height',
				'value' => 600,
				'dependency' => array(
					'element' => 'height',
					'value'   => 'custom',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Thumbnail size', 'realty' ),
				'param_name' => 'thumb_size',
				'value' => array(
					esc_html__( 'Thumbnail (150x150px)', 'realty' ) => 'thumbnail',
					esc_html__( 'Medium (300x300px)', 'realty' )    => 'medium',
					esc_html__( 'Large(1024px width)', 'realty' )   => 'large',
					esc_html__( 'Full', 'realty' )                  => 'full',
					esc_html__( '1600px width', 'realty' )          => 'thumbnail-1600',
					esc_html__( '1200px width', 'realty' )          => 'thumbnail-1200',
					esc_html__( '16:9 (1200x675px)', 'realty' )     => 'thumbnail-16-9',
					esc_html__( '1200x400', 'realty' )              => 'thumbnail-1200-400',
					esc_html__( '400x300', 'realty' )               => 'thumbnail-400-300',
					esc_html__( '600x300px', 'realty' )             => 'property-thumb',
					esc_html__( 'Square (400x400px)', 'realty' )    => 'square-400',
				),
				'std' => 'thumbnail-16-9'
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'realty' ),
				'param_name' => 'orderby',
				'value' => array(
					'date',
					'ID',
					'author',
					'title',
					'name',
					'type',
					'modified',
					'parent',
					'rand',
					'comment_count',
					'menu_order',
				),
				'description' => sprintf( esc_html__( 'Select how to sort retrieved posts. More at %s.', 'realty' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Sort order', 'realty' ),
				'param_name' => 'order',
				'value' => array(
					esc_html__( 'Descending', 'realty' ) => 'DESC',
					esc_html__( 'Ascending', 'realty' )  => 'ASC',
				),
				'description' => sprintf( esc_html__( 'Select ascending or descending order. More at %s.', 'realty' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Autoplay', 'realty' ),
				'param_name' => 'autoplay',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Autoplay Speed in ms', 'realty' ),
				'param_name' => 'autoplay_speed',
				'value' => '5000',
				'dependency' => array(
					'element' => 'autoplay',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Fade Effect', 'realty' ),
				'param_name' => 'fade',
				'value' => array( '' => true ),
				'std' => true,
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show navigation arrows', 'realty' ),
				'param_name' => 'show_arrows',
				'value' => array( '' => true ),
				'std' => false
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show arrows below', 'realty' ),
				'param_name' => 'show_arrows_below',
				'value' => array( '' => true ),
				'std' => true,
				'dependency' => array(
					'element' => 'show_arrows',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show navigation dots', 'realty' ),
				'param_name' => 'show_dots',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show dots below', 'realty' ),
				'param_name' => 'show_dots_below',
				'value' => array( '' => true ),
				'std' => false,
				'dependency' => array(
					'element' => 'show_dots',
					'not_empty' => true,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Infinite', 'realty' ),
				'param_name' => 'infinite',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Lazyload', 'realty' ),
				'param_name' => 'lazyload',
				'value' => array( '' => true ),
				'std' => true
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Extra Class Names', 'realty' ),
				'param_name' => 'extra_class_names',
				'value' => '',
				'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'realty' )
			),
		),
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_realty_property_slider' );

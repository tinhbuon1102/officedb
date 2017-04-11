<?php
/**
 * Shortcode: Latest Posts
 *
 */
if ( ! function_exists( 'tt_latest_posts' ) ) {
	function tt_latest_posts( $atts ) {

		extract( shortcode_atts( array(
			'id'                => 'latest_posts_' . rand(),
			'posts'             => 3,
			'thumb_size'        => 'thumbnail-400-300',
			'autoplay'          => false,
			'autoplay_speed'    => 5000,
			'fade'              => false,
			'images_to_show'    => 1,
			'images_to_show_lg' => 1,
			'images_to_show_sm' => 1,
			'images_to_show_md' => 1,
			'infinite'          => true,
			'show_arrows'       => false,
			'show_arrows_below' => false,
			'show_dots'         => true,
			'show_dots_below'   => true,
		), $atts ) );

		global $realty_theme_option;
		ob_start();

		// Backwards compatibility for Realty 2.4.3
		if ( isset( $columns ) && ! empty( $columns ) ) {
			$images_to_show = $columns;
		}

		$classes[] = 'latest-posts';

		if ( $show_arrows && $show_arrows_below ) {
			$classes[] = 'arrows-below';
		}

		if ( $show_dots && $show_dots_below ) {
			$classes[] = 'dots-below';
		}

		$classes = join(' ', $classes );
		?>

		<?php
			$custom_query_args = array(
				'post_type' => 'post',
				'posts_per_page' => $posts
			);

			$custom_query = new WP_Query( $custom_query_args );
		?>

		<?php if ( $custom_query->have_posts() ) : ?>

			<div class="<?php echo $classes; ?>">

				<div class="hide-initially" id="<?php echo esc_attr( $id ); ?>">

					<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>

						<div class="has-post-thumbnail">
							<div class="entry-header">
								<div class="entry-header-content">
									<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><h3 class="entry-title"><?php the_title(); ?></h3></a>
									<p class="post-date"><?php the_date(); ?></p>
								</div>
								<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( $thumb_size );
									}
								?>
								<div class="featured-image-overlay"></div>
							</div>
						</div>

					<?php endwhile; ?>

				</div>

				<?php if ( $show_arrows_below ) { ?>
					<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $id ); ?>"></div>
				<?php } ?>

			</div>

			<?php wp_reset_postdata(); ?>

		<?php endif; ?>

		<?php
			$slider_params = array(
				'id'                => $id,
				'images_to_show'    => $images_to_show,
				'images_to_show_lg' => $images_to_show_lg,
				'images_to_show_md' => $images_to_show_md,
				'images_to_show_sm' => $images_to_show_sm,
				'autoplay'          => $autoplay,
				'autoplay_speed'    => $autoplay_speed,
				'fade'              => $fade,
				'infinite'          => $infinite,
				'show_arrows'       => $show_arrows,
				'show_arrows_below' => $show_arrows_below,
				'show_dots'         => $show_dots,
				'show_dots_below'   => $show_dots_below,
			);

			tt_script_slick_slider( $slider_params );
		?>

		<?php return ob_get_clean();

	}
}
add_shortcode( 'latest_posts', 'tt_latest_posts' );

// Visual Composer Map
function tt_vc_map_latest_posts() {
	vc_map( array(
		'name' => esc_html__( 'Latest Posts', 'realty' ),
		'base' => 'latest_posts',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Total number of posts to display', 'realty' ),
				'param_name' => 'posts',
				'value' => array(
					esc_html__( 'All', 'realty' ) => -1,
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10,
				),
				'description' => '',
				'std' => 3
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
				'std' => 'thumbnail-400-300'
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
				'description' => esc_html__( 'Only works when you show one slide at once.', 'realty' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width from 1200px)', 'realty' ),
				'param_name' => 'images_to_show',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10
				),
				'description' => '',
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 1199px)', 'realty' ),
				'param_name' => 'images_to_show_lg',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10
				),
				'description' => '',
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 991px)', 'realty' ),
				'param_name' => 'images_to_show_md',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10
				),
				'description' => '',
				'std' => 1
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Images to show at once (browser width up to 767px)', 'realty' ),
				'param_name' => 'images_to_show_sm',
				'value' => array(
					1,
					2,
					3,
					4,
					5,
					6,
					7,
					8,
					9,
					10
				),
				'description' => '',
				'std' => 1
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Infinite', 'realty' ),
				'param_name' => 'infinite',
				'value' => array( '' => true ),
				'std' => true,
				'description' => '',
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
				'std' => false,
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
				'std' => true,
				'dependency' => array(
					'element' => 'show_dots',
					'not_empty' => true,
				),
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_latest_posts' );
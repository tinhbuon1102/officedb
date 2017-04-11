<?php
/**
 * Shortcode: Agents
 *
 */
if ( ! function_exists( 'tt_agents' ) ) {
	function tt_agents( $atts ) {

		extract( shortcode_atts( array(
			'columns'              => null,
			'id'                   => 'agents_carousel_' . rand(),
			'hide_social_networks' => false,
			'layout'			         => 'carousel',
			'orderby'		           => 'date',
			'order'			           => 'DESC',
			'autoplay'             => false,
			'autoplay_speed'       => 5000,
			'fade'                 => false,
			'images_to_show'       => 1,
			'images_to_show_lg'    => 1,
			'images_to_show_md'    => 1,
			'images_to_show_sm'    => 1,
			'infinite'             => true,
			'show_arrows'          => false,
			'show_arrows_below'    => false,
			'show_dots'            => true,
			'show_dots_below'      => true,
		), $atts ) );

		global $realty_theme_option;
		ob_start();

		// Realty 2.4.3
		if ( $columns ) {
			$images_to_show = $columns;
		}

		$classes[] = 'agents-carousel';

		$agents = get_users( array(
			'role'    => 'agent',
			'fields'  => 'ID',
			'orderby' => 'registered',
			'orderby' => $orderby,
			'order'   => $order,
		)	);

		if ( $show_dots_below ) {
			$classes[] = 'dots-below';
		}

		if ( $layout == 'carousel' ) {
			$class_hide = 'hide-initially';
		} else {
			$class_hide = null;
		}

		$classes = join( ' ', $classes );
		?>

		<div class="<?php echo esc_attr( $classes ); ?>">

			<div class="<?php echo esc_attr( $class_hide ); ?>" id="<?php echo esc_attr( $id ); ?>">

				<?php foreach ( $agents as $agent ) { ?>

					<?php
						$company_name = get_user_meta( $agent, 'company_name', true );
						$first_name = get_user_meta( $agent, 'first_name', true );
						$last_name = get_user_meta( $agent, 'last_name', true );
						$email = get_userdata( $agent );
						$email = $email->user_email;
						$office = get_user_meta( $agent, 'office_phone_number', true );
						$mobile = get_user_meta( $agent, 'mobile_phone_number', true );
						$fax = get_user_meta( $agent, 'fax_number', true );
						$website = get_userdata( $agent );
						$website = $website->user_url;
						$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
						$bio = get_user_meta( $agent, 'description', true );
						$profile_image = get_user_meta( $agent, 'user_image', true );
						$author_profile_url = get_author_posts_url( $agent );
						$facebook = get_user_meta( $agent, 'custom_facebook', true );
						$twitter = get_user_meta( $agent, 'custom_twitter', true );
						$google = get_user_meta( $agent, 'custom_google', true );
						$linkedin = get_user_meta( $agent, 'custom_linkedin', true );
					?>

					<?php if ( $layout == 'carousel' ) { ?>

					<div class="agent border-box">

						<a href="<?php echo $author_profile_url; ?>">
							<?php
								if ( $profile_image ) {
									$profile_image_id = tt_get_image_id( $profile_image );
									$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
									echo '<img src="' . $profile_image_array[0] . '" alt="" />';
								} else {
									echo '<img src="//placehold.it/400x400/eee/ccc/&text=.." alt="" />';
								}
							?>

							<?php if ( $realty_theme_option['show-agent-social-networks'] && ! $hide_social_networks ) { ?>
								<div class="social-transparent">
								<?php if ( $facebook ) { ?>
							      <a href="<?php echo esc_attr( $facebook ); ?>" target="_blank"><i class="icon-facebook"></i></a>
							   <?php } ?>
						     <?php if ( $twitter ) { ?>
							    	<a href="<?php echo esc_attr( $twitter ); ?>" target="_blank"><i class="icon-twitter"></i></a>
							   <?php } ?>
						     <?php if ( $google ) { ?>
							    	<a href="<?php echo esc_attr( $google ); ?>" target="_blank"><i class="icon-google-plus"></i></a>
							   <?php } ?>
						     <?php if ( $linkedin ) { ?>
							    	<a href="<?php echo esc_attr( $linkedin ); ?>" target="_blank"><i class="icon-linkedin"></i></a>
							   <?php } ?>
						      </div>
							<?php } ?>
						</a>

						<div class="agent-details content">
							<?php if ( $first_name && $last_name ) { ?>
								<h4 class="title"><?php echo $first_name . ' ' . $last_name; ?></h4>
							<?php } ?>
							<?php if ( $company_name ) { ?>
								<p class="company-name"><?php echo $company_name; ?></p>
							<?php } ?>
							<?php if ( $email && $realty_theme_option['show-agent-email'] ) { ?>
								<div class="contact">
									<i class="icon-email"></i><a href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a>
								</div>
							<?php } ?>
							<?php if ( $office && $realty_theme_option['show-agent-office'] ) { ?>
								<div class="contact">
									<i class="icon-phone"></i><?php echo $office; ?>
								</div>
							<?php } ?>
							<?php if ( $mobile && $realty_theme_option['show-agent-mobile'] ) { ?>
								<div class="contact">
									<i class="icon-mobile"></i><?php echo $mobile; ?>
								</div>
							<?php } ?>
							<?php if ( $fax && $realty_theme_option['show-agent-fax'] ) { ?>
								<div class="contact">
									<i class="icon-fax"></i><?php echo $fax; ?>
								</div>
							<?php } ?>
							<?php if ( $website && $realty_theme_option['show-agent-website'] ) { ?>
								<div class="contact">
									<i class="icon-globe"></i><a href="<?php echo $website; ?>" target="_blank"><?php echo $website_clean; ?></a>
								</div>
							<?php } ?>
						</div>

					</div>

					<?php } else { ?>
						<?php include( get_template_directory() . '/lib/inc/template/agent-information.php' ); ?>
					<?php } ?>

				<?php } // foreach ?>

			</div>

			<?php if ( $show_arrows_below ) { ?>
				<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $id ); ?>"></div>
			<?php } ?>

		</div>

		<?php if ( $layout == 'carousel' ) { ?>

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

		<?php } ?>

		<?php return ob_get_clean();

	}
}
add_shortcode( 'agents', 'tt_agents' );

// Visual Composer Map
function tt_vc_map_agents() {
	vc_map( array(
		'name' => esc_html__( 'Agents', 'realty' ),
		'base' => 'agents',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide social networks', 'realty' ),
				'param_name' => 'hide_social_networks',
				'value' => array( '' => true ),
				'std' => false,
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Layout', 'realty' ),
				'param_name' => 'layout',
				'value' => array(
					__( 'Carousel', 'realty' ) => 'carousel',
					__( 'List View', 'realty' )  => 'list-view',
				),
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
					__( 'Descending', 'realty' ) => 'DESC',
					__( 'Ascending', 'realty' )  => 'ASC',
				),
				'description' => sprintf( esc_html__( 'Select ascending or descending order. More at %s.', 'realty' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Autoplay', 'realty' ),
				'param_name' => 'autoplay',
				'value' => array( '' => true ),
				'std' => false,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => false,
				'description' => esc_html__( 'Only works when you show one slide at once.', 'realty' ),
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => 1,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => 1,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => 1,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => 1,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Infinite', 'realty' ),
				'param_name' => 'infinite',
				'value' => array( '' => true ),
				'std' => true,
				'description' => '',
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show navigation arrows', 'realty' ),
				'param_name' => 'show_arrows',
				'value' => array( '' => true ),
				'std' => false,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
				'std' => true,
				'dependency' => array(
					'element' => 'layout',
					'value' => 'carousel',
				),
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
add_action( 'vc_before_init', 'tt_vc_map_agents' );
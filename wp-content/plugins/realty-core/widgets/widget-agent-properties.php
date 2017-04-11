<?php
/**
 * Widget: Realty - Agent Properties
 *
 */
function widget_agent_properties() {
	register_widget( 'widget_agent_properties' );
}
add_action( 'widgets_init', 'widget_agent_properties' );

class widget_agent_properties extends WP_Widget {

	// Construct widget
	function widget_agent_properties() {
		$widget_ops = array(
			'classname' 	=> 'widget_agent_properties',
			'description' => __( 'Agent More Properties', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_agent_properties', __( 'Realty - Agent Properties', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) && isset ( $instance[ 'amount' ] ) ) {
			$title = $instance[ 'title' ];
			$amount = $instance[ 'amount' ];
			$random = $instance[ 'random' ];
		} else {
			$title = esc_html__( 'Agent More Properties', 'realty' );
			$amount = -1;
			$random = false;
		}

		if ( isset ( $instance[ 'random' ] ) ) {
			$random = $instance[ 'random' ];
		} else {
			$random = false;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'amount' ); ?>"><?php _e( 'Number of Properties to Display:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'amount' ); ?>" type="number" min="-1" value="<?php echo esc_attr( $amount );?>" class="widefat" />
			<small><?php _e( 'Enter "-1" to show all', 'realty' ); ?></small>
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'random' ); ?>" type="checkbox" <?php checked( $random, 'on' ); ?> />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Instead of Newest First, Order Randomly', 'realty' ); ?></label>
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['amount'] = ( ! empty( $new_instance['amount'] ) ) ? strip_tags( $new_instance['amount'] ) : '';
		$instance['random'] = ( ! empty( $new_instance['random'] ) ) ? strip_tags( $new_instance['random'] ) : '';

		return $instance;

  }

  // Display widget on frontend
  function widget( $args, $instance ) {

	  extract( $args );

		// Widget starts to print information
		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$amount = empty( $instance[ 'amount' ] ) ? '3' : $instance[ 'amount' ];
		$amount = intval( $amount );
		$random = $instance[ 'random' ] ? true : false;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		global $post;
		$agent = get_post_meta( $post->ID, 'estate_property_custom_agent', true );

		if ( ! $agent ) {
			$agent_author = $post->post_author;
			$args_agent_properties = array(
				'post_type' 				=> 'property',
				'posts_per_page' 		=> $amount,
				'author'						=> $agent_author,
			);
		} else {
			$args_agent_properties = array(
				'post_type' 				=> 'property',
				'posts_per_page' 		=> $amount,
				'meta_query' 				=> array(
					array(
						'key' 		=> 'estate_property_custom_agent',
						'value' 	=> $agent,
						'compare'	=> '='
					)
				)
			);
		}

		// Order By:
		if ( $random ) {
			$args_agent_properties[ 'orderby' ] = 'rand';
		}

		$query_agent = new WP_Query( $args_agent_properties );
		?>

		<?php if ( $query_agent->have_posts() ) : ?>

			<?php
				$property_carousel_id = 'agent_carousel_' . rand();
				$show_arrows_below = true;
			?>

			<div class="widget-agent-properties">

				<div class="hide-initially" id="<?php echo esc_attr( $property_carousel_id ); ?>">
					<?php while ( $query_agent->have_posts() ) : $query_agent->the_post(); ?>
						<?php global $post;	?>
						<div class="widget-container">
							<a href="<?php the_permalink(); ?>">
								<div class="widget-thumbnail">
									<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'thumbnail-400-300' );
										} else {
											echo '<img src ="//placehold.it/400x300/eee/ccc/&text=.." />';
										}
									?>
								</div>

								<div class="widget-text">
									<?php the_title( '<h5 class="title">', '</h5>' ); ?>
									<div class="sub-title"><?php echo tt_property_price(); ?></div>
								</div>
							</a>
						</div>
					<?php endwhile;	?>
					<?php wp_reset_postdata(); ?>
				</div>

				<?php if ( $show_arrows_below ) { ?>
					<div class="arrow-container" id="arrow-container-<?php echo esc_attr( $property_carousel_id ); ?>"></div>
				<?php } ?>

				<?php
					$slider_params = array(
						'id'                => $property_carousel_id,
						'images_to_show'    => 1,
						'images_to_show_lg' => 1,
						'images_to_show_md' => 1,
						'images_to_show_sm' => 1,
						'autoplay'          => false,
						'autoplay_speed'    => 5000,
						'fade'              => true,
						'infinite'          => true,
						'show_arrows'       => true,
						'show_arrows_below' => $show_arrows_below,
						'show_dots'         => false,
						'show_dots_below'   => false,
					);

					tt_script_slick_slider( $slider_params );
					?>

			</div>

		<?php endif; ?>

		<?php
		// Widget ends printing information
		echo $after_widget;

  }
}
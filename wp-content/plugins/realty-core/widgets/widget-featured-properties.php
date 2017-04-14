<?php
/**
 * Widget: Realty - Featured Properties
 *
 */
function widget_featured_properties() {
	register_widget( 'widget_featured_properties' );
}
add_action( 'widgets_init', 'widget_featured_properties' );

class widget_featured_properties extends WP_Widget {

	// Construct widget
	function widget_featured_properties() {
		$widget_ops = array(
			'classname' 	=> 'widget_featured_properties',
			'description' => esc_html__( 'Featured Properties', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_featured_properties', esc_html__( 'Realty - Featured Properties', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Featured Properties', 'realty' );
		}

		if ( isset ( $instance[ 'amount' ] ) ) {
			$amount = $instance[ 'amount' ];
		} else {
			$amount = -1;
		}

		if ( isset ( $instance[ 'random' ] ) ) {
			$random = $instance[ 'random' ];
		} else {
			$random = false;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'amount' ); ?>"><?php esc_html_e( 'Number of Properties to Display:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'amount' ); ?>" type="number" min="-1" value="<?php echo esc_attr( $amount );?>" class="widefat" />
			<small><?php esc_html_e( 'Enter "-1" to show all', 'realty' ); ?></small>
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'random' ); ?>" type="checkbox" <?php checked( $random, 'on' ); ?> />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php esc_html_e( 'Instead of Newest First, Order Randomly', 'realty' ); ?></label>
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
		$amount = empty( $instance[ 'amount' ] ) ? 3 : $instance[ 'amount' ];
		$amount = intval( $amount );
		$random = $instance[ 'random' ] ? true : false;

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// Query Featured Properties
		$args_featured_properties = array(
			'post_type' 				=> 'property',
			'posts_per_page' 		=> $amount,
			'meta_query' 				=> array(
				array(
					'key'	  	=> 'estate_property_featured',
					'value'	  	=> '1',
					'compare' => 'LIKE'
				)
			)
		);

		// Order By:
		if ( $random ) {
			$args_featured_properties[ 'orderby' ] = 'rand';
		}

		$query_featured = new WP_Query( $args_featured_properties );
		?>

		<?php if ( $query_featured->have_posts() ) : ?>

			<?php
				$property_carousel_id = 'agent_carousel_' . rand();
				$show_arrows_below = true;
			?>

			<div class="hide-initially" id="<?php echo esc_attr( $property_carousel_id ); ?>">
				<?php while ( $query_featured->have_posts() ) : $query_featured->the_post(); ?>
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
								<h5 class="title"><?php the_title(); ?></h5>
								<div class="sub-title">
									<?php echo tt_property_price(); ?>
								</div>
							</div>
						</a>
					</div>
				<?php endwhile; ?>
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

		<?php endif;

		// Widget ends printing information
		echo $after_widget;

  }

}
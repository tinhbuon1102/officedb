<?php
/**
 * Widget: Realty - Latest Posts
 *
 */
function widget_latest_posts() {
	register_widget( 'widget_latest_posts' );
}
add_action( 'widgets_init', 'widget_latest_posts' );

class widget_latest_posts extends WP_Widget {

	// Construct widget
	function widget_latest_posts() {
		$widget_ops = array(
			'classname' 	=> 'widget_latest_posts',
			'description' => esc_html__( 'Posts', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_latest_posts', esc_html__( 'Realty - Latest Posts', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) && isset ( $instance[ 'amount' ] ) ) {
			$title = $instance[ 'title' ];
			$amount = $instance[ 'amount' ];
		} else {
			$title = __( 'Latest Posts', 'realty' );
			$amount = -1;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'amount' ); ?>"><?php esc_html_e( 'Number of Posts to Display:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'amount' ); ?>" type="number" min="-1" value="<?php echo esc_attr( $amount );?>" class="widefat" />
			<small><?php esc_html_e( 'Enter "-1" to show all', 'realty' ); ?></small>
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['amount'] = ( ! empty( $new_instance['amount'] ) ) ? strip_tags( $new_instance['amount'] ) : '';

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

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// Query Featured Properties
		$args_latest_posts = array(
			'post_type' 				=> 'post',
			'posts_per_page' 		=> $amount
		);

		$query_latest_posts = new WP_Query( $args_latest_posts );
		?>

		<?php if ( $query_latest_posts->have_posts() ) : ?>

			<?php
				$property_carousel_id = 'agent_carousel_' . rand();
				$show_arrows_below = true;
			?>

			<div class="hide-initially" id="<?php echo esc_attr( $property_carousel_id ); ?>">
				<?php while ( $query_latest_posts->have_posts() ) : $query_latest_posts->the_post(); ?>
					<div class="widget-container">

						<?php if ( has_post_thumbnail() ) { ?>
							<a href="<?php the_permalink(); ?>">
								<div class="widget-thumbnail">
									<?php the_post_thumbnail( 'thumbnail-400-300', array( 'alt' => '' ) ); ?>
								</div>
							</a>
						<?php } ?>

						<div class="widget-text">
							<a href="<?php the_permalink(); ?>"><h5 class="title"><?php the_title(); ?></h5></a>
							<div class="text-muted"><?php	echo the_excerpt(); ?></div>
						</div>

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
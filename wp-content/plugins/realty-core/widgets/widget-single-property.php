<?php
/**
 * Widget: Realty - Single Property
 *
 */
function widget_single_property() {
	register_widget( 'widget_single_property' );
}
add_action( 'widgets_init', 'widget_single_property' );

class widget_single_property extends WP_Widget {

	// Construct widget
	function widget_single_property() {
		$widget_ops = array(
			'classname' 	=> 'widget_single_property',
			'description' => esc_html__( 'Single Property', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_single_property', esc_html__( 'Realty - Single Property', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) && isset ( $instance[ 'id' ] ) ) {
			$title = $instance[ 'title' ];
			$id = $instance[ 'id' ];
		} else {
			$title = esc_html__( 'Single Property', 'realty' );
			$id = esc_html__( '1', 'realty' );
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php esc_html_e( 'Property:', 'realty' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'id' ); ?>" id="<?php echo $this->get_field_id( 'id' ); ?>" class="widefat">
				<?php
					$query_property_ids_args = array(
						'post_type' 			=> 'property',
						'post_status' 		=> 'publish',
						'posts_per_page' 	=> -1
					);
				?>

				<?php $query_property_ids = new WP_Query( $query_property_ids_args ); ?>

				<?php while ( $query_property_ids->have_posts() ) : $query_property_ids->the_post(); ?>
					<option value="<?php the_ID(); ?>" <?php selected( $id, get_the_ID() ); ?>><?php echo get_the_title(); ?></option>
				<?php	endwhile; ?>
			</select>
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['id'] = ( ! empty( $new_instance['id'] ) ) ? strip_tags( $new_instance['id'] ) : '';

		return $instance;

  }

  // Display widget on frontend
  function widget( $args, $instance ) {

	  extract( $args );

		// Widget starts to print information
		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		$id = empty( $instance[ 'id' ] ) ? '1' : $instance[ 'id' ];
		$id = intval( $id );

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// Query Single Property
		$query_single_property_args = array(
			'post_type' 			=> 'property',
			'posts_per_page' 	=> 1,
			'page_id' 				=> $id
		);

		$query_single_property = new WP_Query( $query_single_property_args );
		?>

		<?php if ( $query_single_property->have_posts() ) : ?>
			<?php while ( $query_single_property->have_posts() ) : $query_single_property->the_post();?>

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
		<?php endif;

		// Widget ends printing information
		echo $after_widget;

  }

}
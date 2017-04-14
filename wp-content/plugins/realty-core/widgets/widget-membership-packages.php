<?php
/**
 * Widget: Realty - Membership Packages
 *
 */
function widget_membership_packages() {
	register_widget( 'widget_membership_packages' );
}
add_action( 'widgets_init', 'widget_membership_packages' );

class widget_membership_packages extends WP_Widget {

	// Construct widget
	function widget_membership_packages() {
		$widget_ops = array(
			'classname' 	=> 'widget_membership_packages',
			'description' => esc_html__( 'Membership Packages', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_membership_packages', esc_html__( 'Realty - Membership Packages', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Membership Packages', 'realty' );
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;

  }

  // Display widget on frontend
  function widget( $args, $instance ) {

	  extract( $args );

		// Widget starts to print information
		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance[ 'title' ] );

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		echo do_shortcode( '[membership_packages]' );

		// Widget ends printing information
		echo $after_widget;

  }

}
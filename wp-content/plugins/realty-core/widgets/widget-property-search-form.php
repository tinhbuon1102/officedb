<?php
/**
 * Widget: Realty - Property Search Form
 *
 */
function widget_property_search() {
	register_widget( 'widget_property_search' );
}
add_action( 'widgets_init', 'widget_property_search' );

class widget_property_search extends WP_Widget {

	// Construct widget
	function widget_property_search() {
		$widget_ops = array(
			'classname' 	=> 'widget_property_search',
			'description' => esc_html__( 'Property Search Form', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_property_search', esc_html__( 'Realty - Property Search', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Property Search', 'realty' );
			$agent = false;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<p>
			<?php esc_html_e( 'As defined under: Appearance > Theme Options > Property Search.', 'realty' ); ?>
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

		// Property Search Form
		get_template_part( 'lib/inc/template/search-form' );

		// Widget ends printing information
		echo $after_widget;

  }

}
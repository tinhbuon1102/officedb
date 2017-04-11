<?php
/**
 * Widget: Realty - Testimonial
 *
 */
function widget_testimonial() {
	register_widget( 'widget_testimonial' );
}
add_action( 'widgets_init', 'widget_testimonial' );

class widget_testimonial extends WP_Widget {

	// Construct widget
	function widget_testimonial() {
		$widget_ops = array(
			'classname' 	=> 'widget_testimonial',
			'description' => esc_html__( 'Testimonial', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_testimonial', esc_html__( 'Realty - Testimonial', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Testimonial', 'realty' );
		}

		if ( isset ( $instance[ 'media' ] ) ) {
			$media = $instance[ 'media' ];
		} else {
			$media = null;
		}

		if ( isset ( $instance[ 'testimonial' ] ) ) {
			$testimonial = $instance[ 'testimonial' ];
		} else {
			$testimonial = null;
		}

		if ( isset ( $instance[ 'author' ] ) ) {
			$author = $instance[ 'author' ];
		} else {
			$author = null;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'media' ); ?>"><?php esc_html_e( 'Media:', 'realty' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'media' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Select media file', 'realty' ); ?></option>
				<?php
					$query_images_args = array(
					    'post_type'      => 'attachment',
					    'post_mime_type' => 'image',
					    'post_status'    => 'inherit',
					    'posts_per_page' => - 1,
					);

					$query_images = new WP_Query( $query_images_args );
				?>
				<?php foreach ( $query_images->posts as $image ) { ?>
					<option value="<?php echo $image->ID; ?>" <?php selected( $media, $image->ID ); ?>><?php echo $image->post_title . ' (ID: ' . $image->ID . ')'; ?></option>
				<?php }	?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'testimonial' ); ?>"><?php esc_html_e( 'Testimonial:', 'realty' ); ?></label>
			<textarea name="<?php echo $this->get_field_name( 'testimonial' ); ?>" rows="5" class="widefat"><?php echo esc_attr( $testimonial ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php esc_html_e( 'Author:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'author' ); ?>" type="text" value="<?php echo esc_attr( $author ); ?>" class="widefat" placeholder="<?php esc_html_e( 'John Doe', 'realty' ); ?>" />
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['media'] = ( ! empty( $new_instance['media'] ) ) ? strip_tags( $new_instance['media'] ) : '';
		$instance['testimonial'] = ( ! empty( $new_instance['testimonial'] ) ) ? strip_tags( $new_instance['testimonial'] ) : '';
		$instance['author'] = ( ! empty( $new_instance['author'] ) ) ? strip_tags( $new_instance['author'] ) : '';

		return $instance;

  }

  // Display widget on frontend
  function widget( $args, $instance ) {

	  extract( $args );

		// Widget starts to print information
		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );
		$media = empty( $instance[ 'media' ] ) ? null : $instance['media'];
		$testimonial = empty( $instance[ 'testimonial' ] ) ? null : $instance['testimonial'];
		$author = empty( $instance[ 'author' ] ) ? null : $instance['author'];

		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		?>

		<div class="widget-container">
			<div class="property-thumbnail"><?php echo wp_get_attachment_image( $media, 'thumbnail-400-300' ); ?></div>
			<div class="widget-text">
				<blockquote style="margin: 0">
					<p style="margin-bottom: .5em; font-size: 1em">
						<?php echo $testimonial; ?>
					</p>
					<cite><?php echo $author; ?></cite>
				</blockquote>
			</div>
		</div>

		<?php
		// Widget ends printing information
		echo $after_widget;

  }

}
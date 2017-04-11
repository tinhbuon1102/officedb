<?php
/**
 * Widget: Realty - Property Map
 *
 */
function widget_property_map() {
	register_widget( 'widget_property_map' );
}
add_action( 'widgets_init', 'widget_property_map' );

class widget_property_map extends WP_Widget {

	// Construct widget
	function widget_property_map() {
		$widget_ops = array(
			'classname' 	=> 'widget_property_map',
			'description' => esc_html__( 'Property Map', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_property_map', esc_html__( 'Realty - Property Map', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form($instance) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Property Map', 'realty' );
		}

		if ( isset( $instance[ 'address' ] ) ) {
			$address = $instance[ 'address' ];
		} else {
			$address = null;
		}

		if ( isset( $instance[ 'location' ] ) ) {
			$location = $instance[ 'location' ];
		} else {
			$location = null;
		}

		if ( isset( $instance[ 'status' ] ) ) {
			$status = $instance[ 'status' ];
		} else {
			$status = null;
		}

		if ( isset( $instance[ 'type' ] ) ) {
			$type = $instance[ 'type' ];
		} else {
			$type = null;
		}

		if ( isset( $instance[ 'features' ] ) ) {
			$features = $instance[ 'features' ];
		} else {
			$features = null;
		}

		if ( isset( $instance[ 'latitude' ] ) ) {
			$latitude = $instance[ 'latitude' ];
		} else {
			$latitude = null;
		}

		if ( isset( $instance[ 'longitude' ] ) ) {
			$longitude = $instance[ 'longitude' ];
		} else {
			$longitude = null;
		}

		if ( isset( $instance[ 'zoomlevel' ] ) ) {
			$zoomlevel = $instance[ 'zoomlevel' ];
		} else {
			$zoomlevel = 14;
		}

		if ( isset( $instance[ 'height' ] ) ) {
			$height = $instance[ 'height' ];
		} else {
			$height = 400;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php esc_html_e( 'Address:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php echo esc_attr( $address ); ?>" class="widefat" placeholder="<?php esc_html_e( 'London, UK', 'realty' ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php esc_html_e( 'Property Location:', 'realty' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'location' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Any Location', 'realty' ); ?></option>
				<?php $locations = get_terms('property-location', array( 'orderby' => 'slug', 'hide_empty' => false ) ); ?>
				<?php foreach ( $locations as $key => $get_location ) {	?>
					<option value="<?php echo $get_location->slug; ?>" <?php selected( $location, $get_location->slug ); ?>><?php echo $get_location->name; ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'status' ); ?>"><?php esc_html_e( 'Property Status:', 'realty' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'status' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Any Status', 'realty' ); ?></option>
				<?php $statuss = get_terms('property-status', array( 'orderby' => 'slug', 'hide_empty' => false ) ); ?>
				<?php foreach ( $statuss as $key => $get_status ) {	?>
					<option value="<?php echo $get_status->slug; ?>" <?php selected( $status, $get_status->slug ); ?>><?php echo $get_status->name; ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( 'Property Type:', 'realty' ); ?></label>
		</p>

		<p>
			<select name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Any Type', 'realty' ); ?></option>
				<?php $types = get_terms('property-type', array( 'orderby' => 'slug', 'hide_empty' => false ) ); ?>
				<?php foreach ( $types as $key => $get_type ) {	?>
					<option value="<?php echo $get_type->slug; ?>" <?php selected( $type, $get_type->slug ); ?>><?php echo $get_type->name; ?></option>
				<?php } ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( 'Property Features:', 'realty' ); ?></label>
		</p>
		<p>
		<?php
			$features_array = explode( ',', $features );
			$get_features = get_terms('property-features', array( 'orderby' => 'slug', 'hide_empty' => false ) );
		?>
		<?php foreach ( $get_features as $key => $get_feature ) { ?>
			<input name="<?php echo $this->get_field_name( 'features' ); ?>[]" type="checkbox" value="<?php echo $get_feature->slug; ?>" class="widefat" <?php checked( in_array( $get_feature->slug, $features_array ), true ); ?> /><label><?php echo $get_feature->name; ?></label><br />
		<?php	}	?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'latitude' ); ?>"><?php esc_html_e( 'Latitude:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'latitude' ); ?>" type="text" value="<?php echo esc_attr( $latitude );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'longitude' ); ?>"><?php esc_html_e( 'Longitude:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'longitude' ); ?>" type="text" value="<?php echo esc_attr( $longitude );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'zoomlevel' ); ?>"><?php esc_html_e( 'Zoom Level (Default "14"):', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'zoomlevel' ); ?>" type="number" min="0" max="19" value="<?php echo esc_attr( $zoomlevel );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php esc_html_e( 'Height In Pixel (Default "400"):', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'height' ); ?>" type="number" value="<?php echo esc_attr( $height );?>" class="widefat" />
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? strip_tags( $new_instance['address'] ) : '';
		$instance['location'] = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : '';
		$instance['status'] = ( ! empty( $new_instance['status'] ) ) ? strip_tags( $new_instance['status'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		$instance['features'] = ( ! empty( $new_instance['features'] ) ) ? implode( ',', $new_instance['features'] ) : ''; // Convert array to string + save to db
		$instance['latitude'] = ( ! empty( $new_instance['latitude'] ) ) ? strip_tags( $new_instance['latitude'] ) : '';
		$instance['longitude'] = ( ! empty( $new_instance['longitude'] ) ) ? strip_tags( $new_instance['longitude'] ) : '';
		$instance['zoomlevel'] = ( ! empty( $new_instance['zoomlevel'] ) ) ? strip_tags( $new_instance['zoomlevel'] ) : '';
		$instance['height'] = ( ! empty( $new_instance['height'] ) ) ? strip_tags( $new_instance['height'] ) : '';

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

		// Parameters
		$parameters = array();

		$parameters[] = $instance[ 'address' ] ? 'address="' . $instance['address'] . '"' : '';
		$parameters[] = $instance[ 'location' ] ? 'location="' . $instance['location'] . '"' : '';
		$parameters[] = $instance[ 'status' ] ? 'status="' . $instance['status'] . '"' : '';
		$parameters[] = $instance[ 'type' ] ? 'type="' . $instance['type'] . '"' : '';
		$parameters[] = $instance[ 'features' ] ? 'features="' . $instance['features'] . '"' : '';
		$parameters[] = $instance[ 'latitude' ] ? 'latitude="' . $instance['latitude'] . '"' : '';
		$parameters[] = $instance[ 'longitude' ] ? 'longitude="' . $instance['longitude'] . '"' : '';
		$parameters[] = $instance[ 'zoomlevel' ] ? 'zoomlevel="' . $instance['zoomlevel'] . '"' : '';
		$parameters[] = $instance[ 'height' ] ? 'height="' . $instance['height'] . 'px"' : '';

		echo do_shortcode( '[map ' . implode( ' ', $parameters ) . ']' );

		// Widget ends printing information
		echo $after_widget;

  }

}
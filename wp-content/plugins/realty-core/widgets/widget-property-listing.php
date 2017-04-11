<?php
/**
 * Widget: Realty - Property Listing
 *
 */
function widget_property_listing() {
	register_widget( 'widget_property_listing' );
}
add_action( 'widgets_init', 'widget_property_listing' );

class widget_property_listing extends WP_Widget {

	// Construct widget
	function widget_property_listing() {
		$widget_ops = array(
			'classname' 	=> 'widget_property_listing',
			'description' => esc_html__( 'Property Listing', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_property_listing', esc_html__( 'Realty - Property Listing', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Property Listing', 'realty' );
		}

		if ( isset( $instance[ 'columns' ] ) ) {
			$columns = $instance[ 'columns' ];
		} else {
			$columns = 3;
		}

		if ( isset( $instance[ 'per_page' ] ) ) {
			$per_page = $instance[ 'per_page' ];
		} else {
			$per_page = 9;
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

		if ( isset( $instance[ 'max_price' ] ) ) {
			$max_price = $instance[ 'max_price' ];
		} else {
			$max_price = null;
		}

		if ( isset( $instance[ 'min_rooms' ] ) ) {
			$min_rooms = $instance[ 'min_rooms' ];
		} else {
			$min_rooms = null;
		}

		if ( isset( $instance[ 'available_from' ] ) ) {
			$available_from = $instance[ 'available_from' ];
		} else {
			$available_from = null;
		}

		if ( isset( $instance[ 'show_sorting_toggle_view' ] ) ) {
			$show_sorting_toggle_view = $instance[ 'show_sorting_toggle_view' ];
		} else {
			$show_sorting_toggle_view = null;
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php esc_html_e( 'Columns:', 'realty' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'columns' ); ?>" class="widefat">
				<option value="1" <?php selected( $columns, 2 ); ?>><?php esc_html_e( '1 Column', 'realty' ); ?></option>
				<option value="2" <?php selected( $columns, 2 ); ?>><?php esc_html_e( '2 Columns', 'realty' ); ?></option>
				<option value="3" <?php selected( $columns, 3 ); ?>><?php esc_html_e( '3 Columns', 'realty' ); ?></option>
				<option value="4" <?php selected( $columns, 4 ); ?>><?php esc_html_e( '4 Columns', 'realty' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'per_page' ); ?>"><?php esc_html_e( 'Properties Per Page:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'per_page' ); ?>" type="number" min="-1" value="<?php echo esc_attr( $per_page );?>" min="1" class="widefat" />
			<small><?php esc_html_e( 'Enter "-1" to show all', 'realty' ); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php esc_html_e( 'Property Location:', 'realty' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'location' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Any Location', 'realty' ); ?></option>
				<?php $locations = get_terms('property-location', array( 'orderby' => 'slug', 'hide_empty' => false ) ); ?>
				<?php foreach ( $locations as $key => $get_location ) { ?>
					<option value="<?php echo $get_location->slug; ?>" <?php selected( $location, $get_location->slug ); ?>><?php echo $get_location->name; ?></option>
				<?php	}	?>
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
				<?php	}	?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( 'Property Type:', 'realty' ); ?></label>
		</p>
		<p>
			<select name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat">
				<option value=""><?php esc_html_e( 'Any Type', 'realty' ); ?></option>
				<?php $types = get_terms('property-type', array( 'orderby' => 'slug', 'hide_empty' => false ) ); ?>
				<?php foreach ( $types as $key => $get_type ) { ?>
					<option value="<?php echo $get_type->slug; ?>" <?php selected( $type, $get_type->slug ); ?>><?php echo $get_type->name; ?></option>
				<?php	}	?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( 'Property Features:', 'realty' ); ?></label>
		</p>
		<p>
		<?php
			$features_array = explode( ',', $features );
			$get_features = get_terms('property-features', array(
				'orderby' => 'slug',
				'hide_empty' => false,
			) );
		?>
		<?php foreach ( $get_features as $key => $get_feature ) {	?>
			<input name="<?php echo $this->get_field_name( 'features' ); ?>[]" type="checkbox" value="<?php echo $get_feature->slug; ?>" class="widefat" <?php checked( in_array( $get_feature->slug, $features_array ), true ); ?> /><label><?php echo $get_feature->name; ?></label><br />
		<?php	}	?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max_price' ); ?>"><?php esc_html_e( 'Max. Price:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'max_price' ); ?>" type="number" step="0.01" value="<?php echo esc_attr( $max_price );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'min_rooms' ); ?>"><?php esc_html_e( 'Min. Rooms:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'min_rooms' ); ?>" type="number" step="0.01" value="<?php echo esc_attr( $min_rooms );?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'available_from' ); ?>"><?php esc_html_e( 'Available From (YYYYMMDD):', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'available_from' ); ?>" type="text" value="<?php echo esc_attr( $available_from );?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_sorting_toggle_view' ); ?>" type="checkbox" value="show" class="widefat" <?php checked( $show_sorting_toggle_view, 'show' ); ?> />
			<label for="<?php echo $this->get_field_id( 'show_sorting_toggle_view' ); ?>"><?php esc_html_e( 'Show Sorting Options', 'realty' ); ?></label>
		</p>

		<?php
  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['columns'] = ( ! empty( $new_instance['columns'] ) ) ? strip_tags( $new_instance['columns'] ) : '';
		$instance['per_page'] = ( ! empty( $new_instance['per_page'] ) ) ? strip_tags( $new_instance['per_page'] ) : '';
		$instance['location'] = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : '';
		$instance['status'] = ( ! empty( $new_instance['status'] ) ) ? strip_tags( $new_instance['status'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
		$instance['features'] = ( ! empty( $new_instance['features'] ) ) ? implode( ',', $new_instance['features'] ) : ''; // Convert array to string + save to db
		$instance['max_price'] = ( ! empty( $new_instance['max_price'] ) ) ? strip_tags( $new_instance['max_price'] ) : '';
		$instance['min_rooms'] = ( ! empty( $new_instance['min_rooms'] ) ) ? strip_tags( $new_instance['min_rooms'] ) : '';
		$instance['available_from'] = ( ! empty( $new_instance['available_from'] ) ) ? strip_tags( $new_instance['available_from'] ) : '';
		$instance['show_sorting_toggle_view'] = ( ! empty( $new_instance['show_sorting_toggle_view'] ) ) ? strip_tags( $new_instance['show_sorting_toggle_view'] ) : '';

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

		$parameters[] = $instance[ 'columns' ] ? 'columns="' . $instance['columns'] . '"' : '';
		$parameters[] = $instance[ 'per_page' ] ? 'per_page="' . $instance['per_page'] . '"' : '';
		$parameters[] = $instance[ 'location' ] ? 'location="' . $instance['location'] . '"' : '';
		$parameters[] = $instance[ 'status' ] ? 'status="' . $instance['status'] . '"' : '';
		$parameters[] = $instance[ 'type' ] ? 'type="' . $instance['type'] . '"' : '';
		$parameters[] = $instance[ 'features' ] ? 'features="' . $instance['features'] . '"' : '';
		$parameters[] = $instance[ 'max_price' ] ? 'max_price="' . $instance['max_price'] . '"' : '';
		$parameters[] = $instance[ 'min_rooms' ] ? 'min_rooms="' . $instance['min_rooms'] . '"' : '';
		$parameters[] = $instance[ 'available_from' ] ? 'available_from="' . $instance['available_from'] . '"' : '';
		$parameters[] = $instance[ 'show_sorting_toggle_view' ] ? 'show_sorting_toggle_view="' . $instance['show_sorting_toggle_view'] . '"' : 'hide';

		echo do_shortcode( '[property_listing ' . implode( ' ', $parameters ) . ']' );

		// Widget ends printing information
		echo $after_widget;

  }

}
<?php
/**
 * Widget: Realty - Custom Property Search Form
 *
 */
function widget_custom_property_search_form() {
	register_widget( 'widget_custom_property_search_form' );
}
add_action( 'widgets_init', 'widget_custom_property_search_form' );

class widget_custom_property_search_form extends WP_Widget {

	// Construct widget
	function widget_custom_property_search_form() {
		$widget_ops = array(
			'classname' 	=> 'widget_custom_property_search_form',
			'description' => esc_html__( 'Custom Property Search Form', 'realty' ),
			'panels_icon' => 'icon-themetrail',
		);
		parent::__construct( 'widget_custom_property_search_form', esc_html__( 'Realty - Custom Property Search', 'realty' ), $widget_ops );
	}

	// Create widget form (WordPress dashboard)
  function form( $instance ) {

	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Custom Property Search', 'realty' );
		}

		if ( isset( $instance['show_location'] ) ) {
			$show_location = $instance['show_location'];
		} else {
			$show_location = false;
		}

		if ( isset( $instance['label_location'] ) ) {
			$label_location = $instance['label_location'];
		} else {
			$label_location = esc_html__( 'Any Location', 'realty' );
		}

		if ( isset( $instance['show_status'] ) ) {
			$show_status = $instance['show_status'];
		} else {
			$show_status = false;
		}

		if ( isset( $instance['label_status'] ) ) {
			$label_status = $instance['label_status'];
		} else {
			$label_status = esc_html__( 'Any Status', 'realty' );
		}

		if ( isset( $instance['show_type'] ) ) {
			$show_type = $instance['show_type'];
		} else {
			$show_type = false;
		}

		if ( isset( $instance['label_type'] ) ) {
			$label_type = $instance['label_type'];
		} else {
			$label_type = esc_html__( 'Any Type', 'realty' );
		}

		if ( isset( $instance['show_features'] ) ) {
			$show_features = $instance['show_features'];
		} else {
			$show_features = false;
		}

		if ( isset( $instance['label_features'] ) ) {
			$label_features = $instance['label_features'];
		} else {
			$label_features = esc_html__( 'Show more search options', 'realty' );
		}

		if ( isset( $instance['show_id'] ) ) {
			$show_id = $instance['show_id'];
		} else {
			$show_id = false;
		}

		if ( isset( $instance['label_id'] ) ) {
			$label_id = $instance['label_id'];
		} else {
			$label_id = esc_html__( 'ID', 'realty' );
		}

		if ( isset( $instance['show_price'] ) ) {
			$show_price = $instance['show_price'];
		} else {
			$show_price = false;
		}

		if ( isset( $instance['label_price'] ) ) {
			$label_price = $instance['label_price'];
		} else {
			$label_price = esc_html__( 'Price', 'realty' );
		}

		if ( isset( $instance['show_size'] ) ) {
			$show_size = $instance['show_size'];
		} else {
			$show_size = false;
		}

		if ( isset( $instance['label_size'] ) ) {
			$label_size = $instance['label_size'];
		} else {
			$label_size = esc_html__( 'Size', 'realty' );
		}

		if ( isset( $instance['show_rooms'] ) ) {
			$show_rooms = $instance['show_rooms'];
		} else {
			$show_rooms = false;
		}

		if ( isset( $instance['label_rooms'] ) ) {
			$label_rooms = $instance['label_rooms'];
		} else {
			$label_rooms = esc_html__( 'Rooms', 'realty' );
		}

		if ( isset( $instance['show_bedrooms'] ) ) {
			$show_bedrooms = $instance['show_bedrooms'];
		} else {
			$show_bedrooms = false;
		}

		if ( isset( $instance['label_bedrooms'] ) ) {
			$label_bedrooms = $instance['label_bedrooms'];
		} else {
			$label_bedrooms = esc_html__( 'Bedrooms', 'realty' );
		}

		if ( isset( $instance['show_bathrooms'] ) ) {
			$show_bathrooms = $instance['show_bathrooms'];
		} else {
			$show_bathrooms = false;
		}

		if ( isset( $instance['label_bathrooms'] ) ) {
			$label_bathrooms = $instance['label_bathrooms'];
		} else {
			$label_bathrooms = esc_html__( 'Bathrooms', 'realty' );
		}

		if ( isset( $instance['show_garages'] ) ) {
			$show_garages = $instance['show_garages'];
		} else {
			$show_garages = false;
		}

		if ( isset( $instance['label_garages'] ) ) {
			$label_garages = $instance['label_garages'];
		} else {
			$label_garages = esc_html__( 'Garages', 'realty' );
		}

		if ( isset( $instance['show_keyword'] ) ) {
			$show_keyword = $instance['show_keyword'];
		} else {
			$show_keyword = false;
		}

		if ( isset( $instance['label_keyword'] ) ) {
			$label_keyword = $instance['label_keyword'];
		} else {
			$label_keyword = esc_html__( 'Keyword', 'realty' );
		}

		if ( isset( $instance['show_available_from'] ) ) {
			$show_available_from = $instance['show_available_from'];
		} else {
			$show_available_from = false;
		}

		if ( isset( $instance['label_available_from'] ) ) {
			$label_available_from = $instance['label_available_from'];
		} else {
			$label_available_from = esc_html__( 'Available From', 'realty' );
		}

		if ( isset( $instance['show_minprice'] ) ) {
			$show_minprice = $instance['show_minprice'];
		} else {
			$show_minprice = false;
		}

		if ( isset( $instance['label_minprice'] ) ) {
			$label_minprice = $instance['label_minprice'];
		} else {
			$label_minprice = esc_html__( 'Min. Price', 'realty' );
		}

		if ( isset( $instance['show_maxprice'] ) ) {
			$show_maxprice = $instance['show_maxprice'];
		} else {
			$show_maxprice = false;
		}

		if ( isset( $instance['label_maxprice'] ) ) {
			$label_maxprice = $instance['label_maxprice'];
		} else {
			$label_maxprice = esc_html__( 'Max. Price', 'realty' );
		}

		if ( isset( $instance['show_pricerange'] ) ) {
			$show_pricerange = $instance['show_pricerange'];
		} else {
			$show_pricerange = false;
		}

		if ( isset( $instance['label_pricerange'] ) ) {
			$label_pricerange = $instance['label_pricerange'];
		} else {
			$label_pricerange = esc_html__( 'From', 'realty' );
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'realty' ); ?></label>
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_location' ); ?>" type="checkbox" <?php checked( $show_location, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_location' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Location', 'realty' ); ?></label>
		</p>
		<p class="hide">
			<input name="<?php echo $this->get_field_name( 'label_location' ); ?>" type="text" value="<?php echo esc_attr( $label_location );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Any Location', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_status' ); ?>" type="checkbox" <?php checked( $show_status, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_status' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Status', 'realty' ); ?></label>
		</p>
		<p class="hide">
			<input name="<?php echo $this->get_field_name( 'label_status' ); ?>" type="text" value="<?php echo esc_attr( $label_status );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Any Status', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_type' ); ?>" type="checkbox" <?php checked( $show_type, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_type' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Type', 'realty' ); ?></label>
		</p>
		<p class="hide">
			<input name="<?php echo $this->get_field_name( 'label_type' ); ?>" type="text" value="<?php echo esc_attr( $label_type );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Any Type', 'realty' ); ?>" class="widefat" />
		</p>

		<p class="hide">
			<input name="<?php echo $this->get_field_name( 'show_features' ); ?>" type="checkbox" <?php checked( $show_features, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_features' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Features', 'realty' ); ?></label>
		</p>
		<p class="hide">
			<input name="<?php echo $this->get_field_name( 'label_features' ); ?>" type="text" value="<?php echo esc_attr( $label_features );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Show more search options', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_id' ); ?>" type="checkbox" <?php checked( $show_id, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_id' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'ID', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_id' ); ?>" type="text" value="<?php echo esc_attr( $label_id );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'ID', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_price' ); ?>" type="checkbox" <?php checked( $show_price, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_price' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Price', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_price' ); ?>" type="text" value="<?php echo esc_attr( $label_price );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Price', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_size' ); ?>" type="checkbox" <?php checked( $show_size, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_size' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Size', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_size' ); ?>" type="text" value="<?php echo esc_attr( $label_size );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Size', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_rooms' ); ?>" type="checkbox" <?php checked( $show_rooms, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_rooms' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Rooms', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_rooms' ); ?>" type="text" value="<?php echo esc_attr( $label_rooms );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Rooms', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_bedrooms' ); ?>" type="checkbox" <?php checked( $show_bedrooms, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_bedrooms' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Bedrooms', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_bedrooms' ); ?>" type="text" value="<?php echo esc_attr( $label_bedrooms );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Bedrooms', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_bathrooms' ); ?>" type="checkbox" <?php checked( $show_bathrooms, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_bathrooms' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Bathrooms', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_bathrooms' ); ?>" type="text" value="<?php echo esc_attr( $label_bathrooms );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Bathrooms', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_garages' ); ?>" type="checkbox" <?php checked( $show_garages, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_garages' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Garages', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_garages' ); ?>" type="text" value="<?php echo esc_attr( $label_garages );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Garages', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_keyword' ); ?>" type="checkbox" <?php checked( $show_keyword, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_keyword' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Keyword', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_keyword' ); ?>" type="text" value="<?php echo esc_attr( $label_keyword );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Keyword', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_available_from' ); ?>" type="checkbox" <?php checked( $show_available_from, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_available_from' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Available From', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_available_from' ); ?>" type="text" value="<?php echo esc_attr( $label_available_from );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Available From', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_minprice' ); ?>" type="checkbox" <?php checked( $show_minprice, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_minprice' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Min. Price', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_minprice' ); ?>" type="text" value="<?php echo esc_attr( $label_minprice );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Min. Price', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_maxprice' ); ?>" type="checkbox" <?php checked( $show_maxprice, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_maxprice' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Max. Price', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_maxprice' ); ?>" type="text" value="<?php echo esc_attr( $label_maxprice );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Max. Price', 'realty' ); ?>" class="widefat" />
		</p>

		<p>
			<input name="<?php echo $this->get_field_name( 'show_pricerange' ); ?>" type="checkbox" <?php checked( $show_pricerange, 'on' ); ?> />
			<label for="<?php echo $this->get_field_name( 'show_pricerange' ); ?>"><?php echo esc_html__( 'Show', 'realty' ) . ' ' . esc_html__( 'Price Range', 'realty' ); ?></label>
		</p>
		<p>
			<input name="<?php echo $this->get_field_name( 'label_pricerange' ); ?>" type="text" value="<?php echo esc_attr( $label_pricerange );?>" placeholder="<?php echo esc_html__( 'Label:', 'realty' ) . ' ' . esc_html__( 'Price Range', 'realty' ); ?>" class="widefat" />
		</p>

		<?php

  }

  // Update widget
  function update( $new_instance, $old_instance ) {

	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['show_location'] = ( ! empty( $new_instance['show_location'] ) ) ? $new_instance['show_location'] : '';
		$instance['label_location'] = ( ! empty( $new_instance['label_location'] ) ) ? strip_tags( $new_instance['label_location'] ) : esc_html__( 'Location', 'realty' );

		$instance['show_status'] = ( isset( $new_instance['show_status'] ) ) ? $new_instance['show_status'] : '';
		$instance['label_status'] = ( ! empty( $new_instance['label_status'] ) ) ? strip_tags( $new_instance['label_status'] ) : esc_html__( 'Status', 'realty' );

		$instance['show_type'] = ( isset( $new_instance['show_type'] ) ) ? $new_instance['show_type'] : '';
		$instance['label_type'] = ( ! empty( $new_instance['label_type'] ) ) ? strip_tags( $new_instance['label_type'] ) : esc_html__( 'Type', 'realty' );

		/*
		$instance['show_features'] = ( isset( $new_instance['show_features'] ) ) ? $new_instance['show_features'] : '';
		$instance['label_features'] = ( ! empty( $new_instance['label_features'] ) ) ? strip_tags( $new_instance['label_features'] ) : esc_html__( 'Show more search options', 'realty' );
		*/

		$instance['show_id'] = ( isset( $new_instance['show_id'] ) ) ? $new_instance['show_id'] : '';
		$instance['label_id'] = ( ! empty( $new_instance['label_id'] ) ) ? strip_tags( $new_instance['label_id'] ) : esc_html__( 'Property ID', 'realty' );

		$instance['show_price'] = ( isset( $new_instance['show_price'] ) ) ? $new_instance['show_price'] : '';
		$instance['label_price'] = ( ! empty( $new_instance['label_price'] ) ) ? strip_tags( $new_instance['label_price'] ) : esc_html__( 'Price', 'realty' );

		$instance['show_size'] = ( isset( $new_instance['show_size'] ) ) ? $new_instance['show_size'] : '';
		$instance['label_size'] = ( ! empty( $new_instance['label_size'] ) ) ? strip_tags( $new_instance['label_size'] ) : esc_html__( 'Size', 'realty' );

		$instance['show_rooms'] = ( isset( $new_instance['show_rooms'] ) ) ? $new_instance['show_rooms'] : '';
		$instance['label_rooms'] = ( ! empty( $new_instance['label_rooms'] ) ) ? strip_tags( $new_instance['label_rooms'] ) : esc_html__( 'Rooms', 'realty' );

		$instance['show_bedrooms'] = ( isset( $new_instance['show_bedrooms'] ) ) ? $new_instance['show_bedrooms'] : '';
		$instance['label_bedrooms'] = ( ! empty( $new_instance['label_bedrooms'] ) ) ? strip_tags( $new_instance['label_bedrooms'] ) : esc_html__( 'Bedrooms', 'realty' );

		$instance['show_bathrooms'] = ( isset( $new_instance['show_bathrooms'] ) ) ? $new_instance['show_bathrooms'] : '';
		$instance['label_bathrooms'] = ( ! empty( $new_instance['label_bathrooms'] ) ) ? strip_tags( $new_instance['label_bathrooms'] ) : esc_html__( 'Bathrooms', 'realty' );

		$instance['show_garages'] = ( isset( $new_instance['show_garages'] ) ) ? $new_instance['show_garages'] : '';
		$instance['label_garages'] = ( ! empty( $new_instance['label_garages'] ) ) ? strip_tags( $new_instance['label_garages'] ) : esc_html__( 'Garages', 'realty' );

		$instance['show_keyword'] = ( isset( $new_instance['show_keyword'] ) ) ? $new_instance['show_keyword'] : '';
		$instance['label_keyword'] = ( ! empty( $new_instance['label_keyword'] ) ) ? strip_tags( $new_instance['label_keyword'] ) : esc_html__( 'Keyword', 'realty' );

		$instance['show_available_from'] = ( isset( $new_instance['show_available_from'] ) ) ? $new_instance['show_available_from'] : '';
		$instance['label_available_from'] = ( ! empty( $new_instance['label_available_from'] ) ) ? strip_tags( $new_instance['label_available_from'] ) : esc_html__( 'Available From', 'realty' );

		$instance['show_minprice'] = ( isset( $new_instance['show_minprice'] ) ) ? $new_instance['show_minprice'] : '';
		$instance['label_minprice'] = ( ! empty( $new_instance['label_minprice'] ) ) ? strip_tags( $new_instance['label_minprice'] ) : esc_html__( 'Min. Price', 'realty' );

		$instance['show_maxprice'] = ( isset( $new_instance['show_maxprice'] ) ) ? $new_instance['show_maxprice'] : '';
		$instance['label_maxprice'] = ( ! empty( $new_instance['label_maxprice'] ) ) ? strip_tags( $new_instance['label_maxprice'] ) : esc_html__( 'Max. Price', 'realty' );

		$instance['show_pricerange'] = ( isset( $new_instance['show_pricerange'] ) ) ? $new_instance['show_pricerange'] : '';
		$instance['label_pricerange'] = ( ! empty( $new_instance['label_pricerange'] ) ) ? strip_tags( $new_instance['label_pricerange'] ) : esc_html__( 'From', 'realty' );

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

		$parameters[] = $instance[ 'show_location' ] ? 'location="' . $instance['label_location'] . '"' : '';
		$parameters[] = $instance[ 'show_status' ] ? 'status="' . $instance['label_status'] . '"' : '';
		$parameters[] = $instance[ 'show_type' ] ? 'type="' . $instance['label_type'] . '"' : '';
		//$parameters[] = $instance[ 'show_features' ] ? 'features="' . $instance['label_features'] . '"' : '';
		$parameters[] = $instance[ 'show_id' ] ? 'id="' . $instance['label_id'] . '"' : '';
		$parameters[] = $instance[ 'show_price' ] ? 'price="' . $instance['label_price'] . '"' : '';
		$parameters[] = $instance[ 'show_size' ] ? 'size="' . $instance['label_size'] . '"' : '';
		$parameters[] = $instance[ 'show_rooms' ] ? 'rooms="' . $instance['label_rooms'] . '"' : '';
		$parameters[] = $instance[ 'show_bedrooms' ] ? 'bedrooms="' . $instance['label_bedrooms'] . '"' : '';
		$parameters[] = $instance[ 'show_bathrooms' ] ? 'bathrooms="' . $instance['label_bathrooms'] . '"' : '';
		$parameters[] = $instance[ 'show_garages' ] ? 'garages="' . $instance['label_garages'] . '"' : '';
		$parameters[] = $instance[ 'show_keyword' ] ? 'keyword="' . $instance['label_keyword'] . '"' : '';
		$parameters[] = $instance[ 'show_available_from' ] ? 'available_from="' . $instance['label_available_from'] . '"' : '';
		$parameters[] = $instance[ 'show_minprice' ] ? 'minprice="' . $instance['label_minprice'] . '"' : '';
		$parameters[] = $instance[ 'show_maxprice' ] ? 'maxprice="' . $instance['label_maxprice'] . '"' : '';
		$parameters[] = $instance[ 'show_pricerange' ] ? 'pricerange="' . $instance['label_pricerange'] . '"' : '';

		echo do_shortcode( '[custom_property_search_form ' . implode( ' ', $parameters ) . ']' );

		// Widget ends printing information
		echo $after_widget;

  }

}
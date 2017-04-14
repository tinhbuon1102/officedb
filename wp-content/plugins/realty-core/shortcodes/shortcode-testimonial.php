<?php
/**
 * Shortcode: Testimonial
 *
 */
if ( ! function_exists( 'tt_testimonial' ) ) {
	function tt_testimonial( $atts ) {

		extract( shortcode_atts( array(
			'image'	     => null,
			'thumb_size' => 'large',
			'name'       => null,
			'text'       => null,
		), $atts ) );

		ob_start();
		?>
		<div class="testimonial testimonial-item">
			<div class="row">

				<div class="col-sm-6">
					<?php echo wp_get_attachment_image( $image, 'large', false, array( 'style' => 'width: 100%; height: <auto></auto>' ) ); ?>
				</div>

				<div class="col-sm-6">
					<div class="arrow-left"></div>
					<div class="content">
						<blockquote>
							<p>
								<?php	echo $text; ?>
							</p>
							<cite><?php echo $name; ?></cite>
						</blockquote>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode( 'testimonial', 'tt_testimonial' );

// Visual Composer Map
function tt_vc_map_testimonial() {
	vc_map( array(
		'name' => esc_html__( 'Testimonial', 'realty' ),
		'base' => 'testimonial',
		'class' => '',
		'category' => esc_html__( 'Realty Theme', 'realty' ),
		'icon' => 'themetrail-icon',
		'params' => array(
			array(
				'type' => 'attach_image',
				'heading' => __( 'Select image', 'realty' ),
				'param_name' => 'image',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Thumbnail size', 'realty' ),
				'param_name' => 'thumb_size',
				'value' => array(
					esc_html__( 'Thumbnail (150x150px)', 'realty' ) => 'thumbnail',
					esc_html__( 'Medium (300x300px)', 'realty' )    => 'medium',
					esc_html__( 'Large(1024px width)', 'realty' )   => 'large',
					esc_html__( 'Full', 'realty' )                  => 'full',
					esc_html__( '1600px width', 'realty' )          => 'thumbnail-1600',
					esc_html__( '1200px width', 'realty' )          => 'thumbnail-1200',
					esc_html__( '16:9 (1200x675px)', 'realty' )     => 'thumbnail-16-9',
					esc_html__( '1200x400', 'realty' )              => 'thumbnail-1200-400',
					esc_html__( '400x300', 'realty' )               => 'thumbnail-400-300',
					esc_html__( '600x300px', 'realty' )             => 'property-thumb',
					esc_html__( 'Square (400x400px)', 'realty' )    => 'square-400',
				),
				'std' => 'large'
			),
			array(
				'type' => 'textfield',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Name', 'realty' ),
				'param_name' => 'name',
				'value' => '',
				'description' => '',
			),
			array(
				'type' => 'textarea',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Text', 'realty' ),
				'param_name' => 'text',
				'value' => '',
				'description' => '',
			),
		)
	) );
}
add_action( 'vc_before_init', 'tt_vc_map_testimonial' );
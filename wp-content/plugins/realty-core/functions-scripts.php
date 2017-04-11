<?php
if ( ! function_exists( 'tt_script_slick_slider' ) ) {
	function tt_script_slick_slider( $slider_params ) {
		?>
		<script>
			(function($) {
			  "use strict";
				$(document).ready(function() {

					<?php global $realty_theme_option; ?>

					<?php if ( isset( $slider_params['property_slider_height'] ) ) { ?>
							var windowHeight = jQuery(window).height();
							var headerHeight = jQuery('#header').height();
						<?php if ( $slider_params['property_slider_height'] == 'fullscreen' ) { ?>
							var sliderHeight = windowHeight - headerHeight;
							if ( jQuery('.body').hasClass('header-fixed') ) {
								sliderHeight = sliderHeight - headerHeight;
							}
						<?php }	?>
						<?php if ( $slider_params['property_slider_height'] == 'custom' && $slider_params['property_slider_custom_height'] ) { ?>
							var sliderHeight = <?php echo $slider_params['property_slider_custom_height'] ?>;
							$('#<?php echo $slider_params['id']; ?> .property-image').css( 'height', sliderHeight );
						<?php } ?>
						<?php if ( $slider_params['property_slider_height'] != 'original' ) { ?>
							$('#<?php echo $slider_params['id']; ?> .property-image').css( 'height', sliderHeight );
						<?php } ?>
					<?php } ?>

					var slider_<?php echo $slider_params['id']; ?> = $('#<?php echo $slider_params['id']; ?>')
		       	.on('init', function(slick) {
							$('#<?php echo $slider_params['id']; ?>').removeClass('hide-initially');
							$('.loader-container').remove();
	        	})
	        	.slick({
				      <?php if ( is_rtl() || $realty_theme_option['enable-rtl-support'] ) { ?>
				      rtl: true,
				      <?php } ?>
				      <?php if ( $slider_params['autoplay'] ) { ?>
				      autoplay: true,
				      autoplaySpeed: <?php echo $slider_params['autoplay_speed']; ?>,
				      <?php } ?>
				      <?php if ( $slider_params['infinite'] ) { ?>
				      infinite: true,
				      <?php } else { ?>
				      infinite: false,
				      <?php } ?>
				      <?php if ( ( ! is_rtl() && ! $realty_theme_option['enable-rtl-support'] ) && $slider_params['fade'] && $slider_params['images_to_show'] == 1 ) { // RTL doesn't work ith fade ?>
				      fade: true,
				      <?php } ?>
			        slidesToShow: <?php echo $slider_params['images_to_show']; ?>,
			        slidesToScroll: 1,
			        focusOnSelect: true,
							adaptiveHeight: true,
			        <?php if ( $slider_params['show_arrows'] ) { ?>
			        arrows: true,
			        prevArrow: '<i class="icon-arrow-left"></i>',
			        nextArrow: '<i class="icon-arrow-right"></i>',
			        <?php if ( $slider_params['show_arrows_below'] ) { ?>
			        appendArrows: '#arrow-container-<?php echo $slider_params['id']; ?>',
			        <?php } ?>
			        <?php } else { ?>
			        arrows: false,
			        prevArrow: null,
			        nextArrow: null,
			        <?php } ?>
			        <?php if ( $slider_params['show_dots'] ) { ?>
			        dots: true,
			        dotsClass: 'slick-dots',
			        customPaging : function(slider, i) {
			          return '<div class="dot"></div>';
			        },
			        <?php } ?>
			        responsive: [
			          {
			            breakpoint: 768,
			            settings: {
			              slidesToShow: <?php echo $slider_params['images_to_show_sm']; ?>,
			            }
			          },
			          {
			            breakpoint: 992,
			            settings: {
			              slidesToShow: <?php echo $slider_params['images_to_show_md']; ?>,
			            }
			          },
			          {
			            breakpoint: 1200,
			            settings: {
			              slidesToShow: <?php echo $slider_params['images_to_show_lg']; ?>,
			            }
			          },
			        ],
							<?php if ( isset( $slider_params['as_nav_for'] ) && $slider_params['as_nav_for'] ) { ?>
			        asNavFor: '#property-thumbnails',
							<?php } ?>
		      });

		      <?php if ( isset( $slider_params['as_nav_for'] ) && $slider_params['as_nav_for'] ) { // Single Property Images - Thumbnail Navigation ?>
					$('#property-thumbnails a').click(function(e) {
						e.preventDefault();
					});

		      $('#property-thumbnails').slick({
				      <?php if( is_rtl() ) { ?>
				      rtl: true,
				      <?php } ?>
							asNavFor: '#<?php echo $slider_params['id']; ?>',
						  slidesToShow: 3,
						  slidesToScroll: 1,
						  //centerMode: true,
						  centerPadding: '0px',
							focusOnSelect: true,
							prevArrow: '<i class="icon-arrow-left"></i>',
							nextArrow: '<i class="icon-arrow-right"></i>',
			        responsive: [
			          {
			            breakpoint: 768,
			            settings: {
			              slidesToShow: 1,
			            }
			          },
			          {
			            breakpoint: 992,
			            settings: {
			              slidesToShow: 2,
			            }
			          },
			          {
			            breakpoint: 1200,
			            settings: {
			              slidesToShow: 3,
			            }
			          },
			        ],
		      });
		      <?php } ?>

		    });
		  })(jQuery);
	  </script>
		<?php
	}
}

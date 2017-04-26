<?php
/**
 * Theme Option: Logo & Retina Logo
 *
 */
if ( ! function_exists( 'realty_get_the_logo' ) ) {
	function realty_get_the_logo() {
		global $realty_theme_option;

		if ( ! empty( $realty_theme_option['logo']['id'] ) ) {
			$logo_attributes['class'] = 'site-logo';

			if ( ! empty( $realty_theme_option['logo-retina']['id'] ) ) {
				$logo_attributes['srcset'] = $realty_theme_option['logo-retina']['url'] . ' 2x'; // Retina Logo
			}

			$logo = wp_get_attachment_image(
				$realty_theme_option['logo']['id'],
				array(
					$realty_theme_option['logo']['width'],
					$realty_theme_option['logo']['height'] ),
				false,
				$logo_attributes
			);
		} else {
			$logo = get_bloginfo( 'name' );
		}

		if ( is_ssl() ) {
	    $logo = str_replace( 'http://', 'https://', $logo );
	  }

		return $logo;
	}
}

/**
 * Theme Option - Body Classes
 *
 */
if ( ! function_exists( 'tt_body_classes' ) ) {
	function tt_body_classes( $classes ) {

		global $realty_theme_option;

		if ( $realty_theme_option['site-layout'] == 'boxed' ) {
			$classes[] = 'site-layout-boxed';
		}

		$hide_site_header = get_post_meta( get_the_ID(), 'estate_page_hide_site_header', true );
		if ( $hide_site_header ) {
			$classes[] = 'hide-site-header';
		}
		if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) {
			$classes[] = 'rtl';
		}
		if ( $realty_theme_option['site-header-position-fixed'] || is_page_template('template-map-vertical.php' ) ) {
			$classes[] = 'header-fixed'; // obsolete: 'fixed-header'
		}

		return $classes;

	}
}
add_filter( 'body_class', 'tt_body_classes' );

/**
 * Theme Option - Accent Color, Custom Styles
 *
 */
if ( ! function_exists( 'tt_custom_styles' ) ) {
	function tt_custom_styles() {

		global $realty_theme_option;
		$color_accent = $realty_theme_option['color-accent'];
		$color_header = $realty_theme_option['color-header'];
		$site_layout = $realty_theme_option['site-layout'];
		$site_max_width = $realty_theme_option['site-max-width'];
		$property_image_custom_height = $realty_theme_option['property-image-custom-height'];
		$color_body_background = $realty_theme_option['color-body-background']['background-color'];
		?>
		<style>
			/* Theme Option: Color Accent */
			<?php if ( $site_layout == 'boxed' && ! empty( $site_max_width ) && ! is_page_template( 'template-map-vertical.php' ) ) {?>
				body, header {
					margin: 0 auto;
					width: <?php echo $site_max_width; ?>px;
				}
				#content {
					background-color: #fff;
					margin-right: auto;
					margin-bottom: 0;
					margin-left: auto;
					padding: 60px 30px;
				}
				#footer {
					margin-top: 0;
				}
				.section-title span {
					background-color: #fff;
				}

			<?php } ?>

			<?php if ( $site_layout == 'boxed' && ! empty( $site_max_width ) && $realty_theme_option['site-header-position-fixed'] ) { ?>
			.fixed-header {
				width: <?php echo $site_max_width; ?>px;
			}
			<?php } ?>

			<?php if ( $realty_theme_option['property-image-height'] == "custom" && $property_image_custom_height ) { ?>
			.property-image-container,
			.property-image-container .property-item,
			.property-image-container .loader-container {
				height: <?php echo $property_image_custom_height; ?>px;
			}

			.text-primary {
				color: <?php echo $color_accent; ?>;
			}

			<?php }	?>
			.btn-primary,
			.btn-primary:focus,
			input[type='submit'],
			.acf-button.blue,
			/*.primary-tooltips .tooltip-inner,*/
			.property-item .property-excerpt::after,
			.property-item.featured .property-title::after,
			#pagination .page-numbers li a:hover,
			#pagination .page-numbers li span:hover,
			#pagination .page-numbers li i:hover,
			#page-banner .banner-title:after,
			.map-wrapper .map-controls .control.active,
			.map-wrapper .map-controls .control:hover,
			.datepicker table tr td.active.active,
			.datepicker table tr td.active:hover.active,
			.noUi-connect {
				background-color: <?php echo $color_accent; ?>;
			}
			.page-template-template-property-submit #main-content{ padding: 25px; }
			.single-property #main-content{ padding: 25px; }
			@media(min-width:992px) {
				.sub-menu li.current-menu-item,
				.sub-menu li:hover {
					background-color: <?php echo $color_accent; ?>;
				}
			}

			input:focus,
			.form-control:focus,
			input:active,
			.form-control:active,
			ul#sidebar li.widget .wpcf7 textarea:focus,
			#footer li.widget .wpcf7 textarea:focus,
			ul#sidebar li.widget .wpcf7 input:not([type='submit']):focus,
			#footer li.widget .wpcf7 input:not([type='submit']):focus,
			.chosen-container.chosen-container-active .chosen-single, .chosen-container .chosen-drop {
				border-color: <?php echo $color_accent; ?>
			}

			/*
			.primary-tooltips .tooltip.top .tooltip-arrow,
			.arrow-down,
			.sticky .entry-header {
				border-top-color: <?php echo $color_accent; ?>;
			}

			.primary-tooltips .tooltip.right .tooltip-arrow,
			.arrow-left {
				border-right-color: <?php echo $color_accent; ?>;
			}

			.primary-tooltips .tooltip.bottom .tooltip-arrow,
			.arrow-up {
				border-bottom-color: <?php echo $color_accent; ?>;
			}

			.primary-tooltips .tooltip.left .tooltip-arrow,
			.arrow-right,
			.property-slider .description .arrow-right {
				border-left-color: <?php echo $color_accent; ?>;
			}
			*/

			.property-slider .title { background-color: <?php echo $color_accent; ?>; }
			.property-slider .description .arrow-right { border-left-color: <?php echo $color_accent; ?>; }
			.property-slider .description .arrow-left { border-right-color: <?php echo $color_accent; ?>; }
			.input--filled label::before, .form-control:focus + label::before { border-color: <?php echo $color_accent; ?> !important }

			.rtl .property-slider .description .arrow-right { border-right-color: <?php echo $color_accent; ?>; border-left-color: transparent !important; }

			/* Theme Option: Color Header */
			.top-header,
			.top-header a,
			.site-branding,
			.site-title a,
			.site-description a,
			.primary-menu a {
				color: <?php echo $color_header; ?>;
			}
			<?php
			// Theme Options: Custom Styles
			if ( ! empty( $realty_theme_option['custom-styles'] ) ) {
				echo $realty_theme_option['custom-styles'] . "\n";
			}
			?>

			<?php if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) { ?>
			/* xxx-any-rtl-stlyes-? */
			.owl-fearured-properties { direction: ltr; }
			.owl-latest-properties { direction: ltr; }
			<?php } ?>

		</style>
		<?php

	}
}
add_action( 'wp_head', 'tt_custom_styles', 151 ); // Fire after Redux

/**
 * Theme Scripts
 *
 */
if ( ! function_exists( 'tt_scripts' ) ) {
	function tt_scripts() {
	global $realty_theme_option;
	?>
	<script>
		jQuery(document).ready(function($) {

			// Social Sharing and video pop up
			video_and_social_share();

			jQuery('.search-results-view i').on('click',function() {
			  jQuery('.search-results-view i').removeClass('active');
			  jQuery(this).toggleClass('active');
			  jQuery('.property-items').fadeTo( 300 , 0, function() {
			  jQuery(this).fadeTo( 300, 1 );
			});

			setTimeout(function() {
				jQuery('.property-items').attr( 'data-view', jQuery('.search-results-view i.active').attr('data-view') );
			}, 300);

			});

			<?php if ( is_user_logged_in() ) { // Login Welcome Message ?>
				<?php if ( isset( $_GET['sign-user'] ) ) { ?>
					<?php if( $_GET['sign-user'] == 'successful') { ?>
					  jQuery.notifyBar({
						  cssClass: "alert-success",
						  html: "<?php echo esc_html__( 'Login successful', 'realty' ); ?>"
						});
					<?php } ?>
				<?php } ?>
			<?php } ?>

			<?php if ( isset( $_GET['user'] ) ) { ?>
				<?php if( $_GET['user'] == 'registered') { ?>
			    jQuery.notifyBar({
				    cssClass: "alert-success",
				    html: "<?php echo esc_html__( 'Your account has been created. Please check your email inbox.', 'realty' ); ?>"
				  });
				<?php } ?>
			<?php } ?>

		}); // END document.ready

		jQuery(window).load(function() {

			var heightWindow = jQuery(window).height();
			var windowWidth = jQuery(window).width();
			var heightHeader = jQuery('#header').height();
			var heightFullscreen = heightWindow - heightHeader;

			<?php // Property Image Height - Fullscreen ?>
			<?php if ( $realty_theme_option['property-image-height'] == 'fullscreen' ) { ?>
				if ( isMobile ) {
					var heightFullscreenBoxed = heightFullscreen - 15; // margin-top to header
				} else {
					var heightFullscreenBoxed = heightFullscreen - 50; // margin-top to header
				}

				if ( jQuery('#property-layout-boxed .property-image-container').hasClass('cut') ) {
					jQuery('#property-layout-boxed .property-image').css( 'height', heightFullscreenBoxed );
				} else {
					jQuery('#property-layout-boxed .property-image').css( 'height', heightFullscreenBoxed );
				}

				if ( jQuery('#property-layout-full-width .property-image-container').hasClass('cut') ) {
					jQuery('#property-layout-full-width .property-image').css( 'height', heightFullscreen );
				} else {
					jQuery('#property-layout-full-width .property-image').css( 'height', heightFullscreen );
				}
			<?php } ?>

			<?php
			if ( $realty_theme_option['property-image-width'] == "full" ) { ?>
				jQuery('#property-slideshow ul.slides li').css( 'width', windowWidth+'px' );
			<?php } ?>

			<?php // Property Image Height - Custom ?>
			<?php if ( $realty_theme_option['property-image-height'] == "custom" ) { ?>
				if ( jQuery('.property-image-container').hasClass('cut') ) {
					jQuery('.property-image-container .property-image, .property-image-container iframe').css( 'height', <?php echo $realty_theme_option['property-image-custom-height']; ?> );
				}
			<?php } ?>

			<?php // Property - Lightbox ?>
			<?php if ( $realty_theme_option['property-lightbox'] == "magnific-popup" ) { ?>
				jQuery('body.single-property .property-image').magnificPopup({
					type: 		'image',
					gallery: 	{
						enabled: 	true,
						tPrev: 		'',
						tNext: 		'',
						tCounter: '%curr% | %total%'
					}
				});
			<?php }	else if ( $realty_theme_option['property-lightbox'] == "intense-images" ) { ?>
				jQuery('body.single-property .property-image').each(function() {
					Intense( jQuery(this) );
				});
			<?php } ?>

			<?php
				// Datepicker
				$datepicker_language = $realty_theme_option['datepicker-language'];
				if ( function_exists( 'icl_object_id' ) ) {
					if ( ICL_LANGUAGE_CODE ) {
						$datepicker_language = ICL_LANGUAGE_CODE;
					}
				}
			?>

			<?php if ( isset( $datepicker_language ) && ! is_page_template( 'template-property-submit.php' ) ) { ?>
				jQuery('.datepicker').datepicker({
				language: '<?php echo $datepicker_language; ?>',
				autoclose: true,
				isRTL: <?php if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ){ echo "true"; } else { echo "false"; } ?>,
				format: 'yyyymmdd',
				});
			<?php } ?>

			<?php $price_thousands_separator = $realty_theme_option['price-thousands-separator'];	?>

			// Price Range
			if ( jQuery('.price-range-slider').length ) {

				var priceFormat;
				var priceSlider = document.getElementById('price-range-slider');
				noUiSlider.create(priceSlider, {

					start: [ <?php if ( isset( $_GET['price_range_min'] ) ) { echo $_GET['price_range_min']; } else if ( $realty_theme_option['property-search-price-range-min'] ) { echo $realty_theme_option['property-search-price-range-min']; } else { echo 0; } ?>, <?php if ( isset( $_GET['price_range_max'] ) ) { echo $_GET['price_range_max']; } else if ( $realty_theme_option['property-search-price-range-max'] ) { echo $realty_theme_option['property-search-price-range-max']; } else { echo 0; } ?> ],
					step: <?php if ( $realty_theme_option['property-search-price-range-step'] ) { echo $realty_theme_option['property-search-price-range-step']; } else {
						echo 0; } ?>,
					range: {
						'min': [  <?php if ( $realty_theme_option['property-search-price-range-min']) { echo $realty_theme_option['property-search-price-range-min']; } else { echo 0; } ?> ],
						'max': [  <?php if ( $realty_theme_option['property-search-price-range-max']) { echo $realty_theme_option['property-search-price-range-max']; } else { echo 0; } ?> ]
					},
					format: wNumb({
						decimals: 0,
						<?php
							if ( $price_thousands_separator ) {
								if ( $price_thousands_separator == "'" ) { echo "thousand: \"" . $price_thousands_separator . "\","; } else { if ( $price_thousands_separator ) { echo "thousand: '" . $price_thousands_separator . "',"; } }
							}
						?>
						<?php if ( $realty_theme_option['currency-sign-position'] == 'left' ) { echo "prefix: '"; } else { echo "postfix: '"; } echo $realty_theme_option['currency-sign'] . "',"; ?>
					}),

					connect: true,
					animate: true,
					<?php if ( $realty_theme_option['enable-rtl-support'] || is_rtl() ) { ?>
					direction: 'rtl'
					<?php } ?>


				});

				priceFormat = wNumb({
					decimals: 0,

					<?php
						if ( $price_thousands_separator ) {
							if ( $price_thousands_separator=="'" ) { echo "thousand: \"" . $price_thousands_separator . "\","; } else { if ( $price_thousands_separator ) { echo "thousand: '" . $price_thousands_separator . "',"; } }
						}
					?>
					<?php if ( $realty_theme_option['currency-sign-position'] == 'left' ) { echo "prefix: '"; } else { echo "postfix: '"; } echo $realty_theme_option['currency-sign'] . "',"; ?>
				});

				var priceValues = [
					document.getElementById('price-range-min'),
					document.getElementById('price-range-max')
				];

				var inputValues = [
					document.getElementById('price-range-min'),
					document.getElementById('price-range-max')
				];

				priceSlider.noUiSlider.on('update', function( values, handle ) {

					priceValues[handle].innerHTML = values[handle];
					var min_price = priceFormat.from( values[0] );
					var max_price = priceFormat.from( values[1] );
					jQuery('.property-search-price-range-min').val(min_price);
					jQuery('.property-search-price-range-max').val(max_price);

				});

				priceSlider.noUiSlider.on('change', function( values, handle ) {

					loader();

					priceValues[handle].innerHTML = values[handle];
					var min_price = priceFormat.from( values[0] );
					var max_price = priceFormat.from( values[1] );
					jQuery('.property-search-price-range-min').val(min_price);
					jQuery('.property-search-price-range-max').val(max_price);

					removeMarkers();
					tt_ajax_search_results();

				});

			} // END .price-range-slider

			var searching_xhr;
			// AJAX
			function tt_ajax_search_results() {
				if (searching_xhr)
				{
					searching_xhr.abort();
				}
				
				"use strict";
				if ( jQuery('.property-search-feature') ) {
					var feature = [];
					jQuery('.property-search-feature:checked').each(function() {
					  feature.push( jQuery(this).val() );
					});
				}
				var ajaxData = jQuery('.property-search-form').first().serialize() + "&action=tt_ajax_search&base=" + window.location.pathname;
				searching_xhr = jQuery.ajax({

				  type: 'GET',
				  url: ajax_object.ajax_url,
				  data: ajaxData,
				  success: function (response) {
					  jQuery('.loader-container').fadeOut();
				    jQuery('.property-items').html(response);
						video_and_social_share();
				  },
				  error: function () {
				  	console.log( 'failed' );
				  }

				});

			}

			// Remove Map Markers & Marker Cluster
			function removeMarkers() {
				// http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/examples/speed_test.js
			  if(typeof newMarkers != 'undefined'){
			    for( i = 0; i < newMarkers.length; i++ ) {
				  newMarkers[i].setMap(null);
					// Close Infoboxes
				  if ( newMarkers[i].infobox.getVisible() ) {
					newMarkers[i].infobox.hide();
				  }
			    }
			    if ( markerCluster ) {
				  markerCluster.clearMarkers();
			    }
			    markers = [];
			    newMarkers = [];
			    bounds = [];
			  }

			}

			// Fire Search Results Ajax On Search Field Change (Exclude Datepicker)
			jQuery('.property-search-form select, .property-search-form input').not('.datepicker').on('change',function() {
				loader();
				if ( jQuery('.google-map').length > 0 ) {
					removeMarkers();
				}
				tt_ajax_search_results();
			});

			// Fire Search Results Ajax On Search Field "Datepicker" Change
			jQuery('.property-search-form input.datepicker').on('changeDate', function() {
				loader();
				if ( jQuery('.google-map').length > 0 ) {
					removeMarkers();
				}
				tt_ajax_search_results();
			});

			function loader() {
				jQuery('.property-items').addClass('loading');
				jQuery('.property-items').html('<div class="loader-container"><div class="svg-loader"></div></div>');
			}

			// AJAX script for pagination
			jQuery(function($) {
				$('.pagination-ajax a').live('click',function(e){
					e.preventDefault();

					var link_page = $(this).attr('href');
					var page_number =  $(this).text();

					if($(this).hasClass( "next" )){
						var next_from = parseInt($('.pagination-ajax li span').text());
						page_number = next_from + 1;
					}

					if($(this).hasClass( "prev" )){
						var prev_from = parseInt($('.pagination-ajax li span').text());
						page_number = prev_from - 1;
					}

					$('.property-items').fadeOut(500);
					removeMarkers();

					var ajaxData = jQuery('.property-search-form').first().serialize() + "&action=tt_ajax_search&base=" + window.location.pathname + "&pagenumber=" + page_number;
					//console.log(ajaxData);
					$.ajax({
						type: 'GET',
						url: ajax_object.ajax_url,
						data: ajaxData,
						success: function (response) {
							$(".property-items").html(response);
							$(".property-items").fadeIn(500);
							window.history.pushState(".property-items", "Properties",link_page );
						},
						error: function () {
						console.log( 'failed' );
						}
					});

				});
			});

			// END AJAX script for pagination

		}); // END window.load

		<?php if ( is_rtl() || $realty_theme_option['enable-rtl-support'] ) { ?>

			jQuery('.icon-arrow-right').addClass('right-one').removeClass('icon-arrow-right');
			jQuery('.icon-arrow-left').addClass('left-one').removeClass('icon-arrow-left');
			jQuery('.right-one').addClass('icon-arrow-left').removeClass('right-one')
			jQuery('.left-one').addClass('icon-arrow-right').removeClass('left-one')

		<?php } ?>

		<?php if( ! $realty_theme_option['show-sub-menu-by-default-on-mobile'] ) { // Expand sub menu by default (on mobile) ?>
			jQuery('.menu-item-has-children, .menu-item-language').click(function() {
			  if ( jQuery('body').hasClass('show-nav') ) {
			    jQuery(this).find('.sub-menu').toggleClass('open');
			  }
			});
		<?php } else { ?>
			jQuery('#toggle-navigation').click(function() {
			  jQuery('.sub-menu').toggleClass('open');
			});

			jQuery('.menu-item-has-children, .menu-item-language').click(function() {
			  jQuery(this).find('.sub-menu').toggleClass('show');
			});
		<?php } ?>

		<?php
			// Theme Options: Custom Scripts
			echo $realty_theme_option['custom-scripts']."\n";
		?>
	</script>
	<?php
	}
}
add_action( 'wp_footer', 'tt_scripts', 20 );

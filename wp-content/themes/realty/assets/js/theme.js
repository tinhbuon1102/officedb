var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

function video_and_social_share() {
	jQuery('.property-video-popup').magnificPopup({
		type:"iframe"
	});
	jQuery('.property-price').on('click', '.share-property',function() {
		jQuery(this).prev('.share-unit').toggle();
	});
}

(function($) {
  "use strict";
	$(document).ready(function() {

		// Mobile Menu Toggle
		$('#toggle-navigation').click(function(e) {
			e.preventDefault();
			$(this).find('i').toggleClass('show');
			$('body').toggleClass('show-nav');
			$('.mobile-menu-overlay').toggleClass('hide');
		});

		// Mobile Menu Open Class
		$('.menu-item-has-children > a').click(function(e) {
			if($('body').hasClass('show-nav')) {
				e.preventDefault();
				$(this).toggleClass('open');
				//$(this).siblings('.sub-menu').toggleClass('open');
			}
		});

		// Set Main Navigation Line Height Equal To Header
		var siteBrandingHeight = $('.site-branding').height();
		siteBrandingHeight = siteBrandingHeight + 60; // 60 = .site-branding "padding"

		$('.primary-menu > li a').css('line-height',siteBrandingHeight+"px");
		$('.main-navigation').fadeTo(400, 1);

		// Fixed Header Content & Page Header Margins/Paddings
		//if(!isMobile && $('body').hasClass('header-fixed')) {
		if( $('body').hasClass('header-fixed') ) {
			var headerHeight = $('#header').height();
			headerHeight = headerHeight + 60; // 60 = #content "margin-top"
			$('#content').css('margin-top',headerHeight+"px");
		}

		/* Smooth Scroll Menu Links
		-------------------------*/
		$('#up i, .property-header a[href="#location_map"]').on('click', function(e) {
	    e.preventDefault();
	    $('html,body').animate({scrollTop:$(this.hash).offset().top-15}, 800);
	  });

	  /* Scroll To The Top - Button
		-------------------------*/
		$('#up').click(function(e) {
			e.preventDefault();
			$('html, body').animate({scrollTop: 0}, 800);
		});

		/* Bootstrap Datepicker
		// http://eternicode.github.io/bootstrap-datepicker/
		-------------------------*/
		/*$('.datepicker').datepicker({
	    language: 	'en',
	    autoclose: 	true,
	    format: "yyyymmdd"
		});*/

		/* FitVids v1.0 - Fluid Width Video Embeds
		https://github.com/davatron5000/FitVids.js/
		-------------------------*/
		$('#main-content, article, #intro-wrapper').fitVids();
		$('.fluid-width-video-wrapper').css('padding-top','56.25%'); // Always display videos 16:9 (100/16*9=56.25)

		/* Property Search Results
		-------------------------*/
		$('.search-results-view i').click(function() {

			$('.search-results-view i').removeClass('active');
			$(this).toggleClass('active');

			$('.property-items').fadeTo( 300 , 0, function() {
	    	$(this).fadeTo( 300, 1 );
			});

			setTimeout(function() {
				$('#property-search-results').attr( 'data-view', $('.search-results-view i.active').attr('data-view') );
			}, 300);

		});
		$('.toggle-property-search-more').click(function(e) {
			e.preventDefault();
			$(this).find('span').toggleClass('hide');
			$('.property-search-more').toggleClass('show');
		});

		$('#orderby').on('change', function() {

			var orderValue = $(this).val();
			var OrderKey = 'order-by';
			var windowLocationHref = window.location.href;

			// http://stackoverflow.com/questions/5999118/add-or-update-query-string-parameter
			function updateQueryStringParameter(uri, key, value) {
			  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
			  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
			  if (uri.match(re)) {
			    return uri.replace(re, '$1' + key + "=" + value + '$2');
			  }
			  else {
			    return uri + separator + key + "=" + value;
			  }
			}

			// Load new-built URI (Refresh With Orderby Update)
			document.location = updateQueryStringParameter( windowLocationHref, OrderKey, orderValue );

		});

		$('.search-results-header .fa-repeat').click(function() {
			location.reload();
		});

		/* Map - Button
		-------------------------*/
		$('.map-controls a').click(function(e) {
			e.preventDefault();
		});

		/* Template - Intro
		-------------------------*/
		$('#intro-wrapper .toggle').click(function(e) {
			e.preventDefault();
			$('.intro-search, .intro-map').toggleClass('transform');
			var introMapHeight = $('.intro-map').height();
			$('#intro-wrapper .intro-right').css( 'height', introMapHeight );
		});


		$('#toggle-intro-wrapper').click(function(e) {
			e.preventDefault();
			$('#intro-wrapper').find('.inner').fadeToggle();
			$(this).find('i').toggleClass('fa-expand, fa-compress');
		});

		/* Forms
		-------------------------*/
		if( $().validate && $().ajaxSubmit ) {

			// User Registration
			$('#registerform').validate({
		    submitHandler:  $(this).ajaxSubmit(),
				rules: {
			    user_login: {
			      required: true
			    },
			    user_email: {
			      required: true,
			      email: true
			    },
			    user_terms: {
			      required: true
			    }
			  }
			});

			// User Login
			$('#loginform').validate({
		    submitHandler:  $(this).ajaxSubmit(),
				rules: {
			    log: {
			      required: true
			    },
			    pwd: {
			      required: true
			    }
			  }
			});

		}

		/* Property Pagination
		-------------------------*/
		$('.property-items').fadeTo(0, 0);
		$('.property-items').fadeTo(400, 1);

		$('#pagination a').click(function() {
			$('.property-items').fadeTo(400, 0);
		});

		/* Window Scroll
		-------------------------*/
		$(window).scroll(function() {

			// // Page Header Scroll Effects
			var pixelsScrolledDown = $(document).scrollTop();
			var topHeaderHeight = $('.top-header').height();

			var pixelsScrolledDownBlur = pixelsScrolledDown / 100;
			$('.banner-title').css({ '-webkit-filter': 'blur('+pixelsScrolledDownBlur+'px)', 'filter': 'blur('+pixelsScrolledDownBlur+'px)' });

			var pixelsScrolledDownTop = pixelsScrolledDown / 5;
			$('.banner-title').css({ 'top': pixelsScrolledDownTop+'px' });

			var height = $(window).height();
			var pixelsScrolledOpacity = ( height - ( pixelsScrolledDown * 3 ) ) / height;
			$('.banner-title').css({ 'opacity': pixelsScrolledOpacity });

			// Scroll To Top Button
			var headerHeight = $('#header').height();
			var initialNavHeight = $('header.navbar').height();
			var loginBarHeight = $('#login-bar-header').height();

			if ( $(this).scrollTop() > headerHeight ) {
				$('#fixed-controls').addClass('show');
				// Fixed Header
				if ( !isMobile && $('body').hasClass('header-fixed') ) {

					$('.top-header').css('margin-top', -topHeaderHeight+'px');

					// Consider Admin Bar
					if ( $('body').hasClass('admin-bar') ) {
						$('header.navbar').addClass('mini');
						var loginBarHeightAdmin = loginBarHeight - 32; // 32 = admin bar height in px
						$('header.navbar').css( 'top', '-'+loginBarHeightAdmin+'px' );
					}
					else {
						$('header.navbar').addClass('mini');
						$('header.navbar').css( 'top', '-'+loginBarHeight+'px' );
					}
				}
			} else {
				$('#fixed-controls').removeClass('show');
				// Fixed Header
				if ( !isMobile && $('body').hasClass('header-fixed') ) {

					$('.top-header').css('margin-top', '0');

					// Consider Admin Bar
					if ( $('body').hasClass('admin-bar') ) {
						$('header.navbar').removeClass('mini');
						$('header.navbar').css( 'top', '32px' );
					}
					else {
						$('header.navbar').removeClass('mini');
						$('header.navbar').css( 'top', 0 );
					}
				}
			}

		});

		/* Bootstrap Plugins
		-------------------------*/
		$('[data-toggle="tooltip"]').tooltip();

	});

	$(window).load(function() {

		/* Chosen.js - Custom Select Boxes
		http://harvesthq.github.io/chosen/options.html
		-------------------------*/
		$('#dsidx select').chosen({
			width: "auto"
		});
		$('select').not('.acf-hidden').chosen({
			width: "100%",
			search_contains: true,
			disable_search_threshold: 5
		});
		$('.search-results-order select').chosen({
			disable_search: true,
			width: "100%"
		});

	  /* Latest Tweets Widget Plugin
	  https://wordpress.org/plugins/latest-tweets-widget/
		============================== */
		var latestTweets = $('.latest-tweets')
		var latestTweetsItems = $(latestTweets).find('ul').children('li');

		if ( latestTweetsItems.length > 1 ) {
	  	$(latestTweets).find('ul').addClass('owl-carousel-1'); // xxx-obsolete
	  }

		/* RTL results
		-------------------------*/
	  var rtlResult = false;

	  if ( $('body').hasClass('rtl') ) {
		  rtlResult = true;
	  }

	  /* Print Button
		-------------------------*/
		$('#print').click(function(e) {
			e.preventDefault();
			javascript:window.print();
		});

		/* Show Login Modal
		-------------------------*/
		if ( window.location.search.indexOf('login') > -1 ) {
			$('#login-modal').modal();
		}

		$('.slideshow-search').addClass('show');

		/* Delete Property
		-------------------------*/
		jQuery('.delete-property').click(function(e) {
			e.preventDefault();

		  jQuery.ajax({
		    type: 'GET',
		    url: ajax_object.ajax_url,
		    data: {
			    'action'          : 'tt_ajax_delete_property_function', // WP Function
			    'delete_property' : $(this).attr('data-property-id')
		    },
		    success: function (response) {
			    console.log('deleted');
		    },
		    error: function () {
		    	console.log('failed');
		    }
		  });

		  $(this).closest('li').fadeOut(400, function() {
				$(this).remove();
			});

		});

	});

})(jQuery);
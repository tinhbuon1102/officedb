jQuery(document).ready(function($){
	$('#header .top-header .lang-item.current-lang a').click(function(){
        return false;
    });
	$('#header .top-header .lang-item.current-lang a').on('click', function(){
        $("#header .top-header .lang-item").toggleClass('show-lang');
    });
	$(window).on('load resize', function(){
		var windowWidth = $(window).width();
	var headlogo =$('.header-navi-wrap .site-branding').innerWidth();
	var headnavi =$('.header-navi-wrap .main-navigation').innerWidth();
	var callinfo =$('.header-navi-wrap .calling-info').innerWidth();
	var tooltip =$('.top-header-links.primary-tooltips').width();
	if (windowWidth > 992) {
		$(".site-desc").css('min-width', ((headlogo + headnavi + callinfo) - tooltip - 20) + 'px');
	}
});
	
	$('.jktCD').jktCD();
	$('body').on('click' , 'input#keyword', function() {
		var formOffsetTop = $('#content form.property-search-form:visible:eq(0)').offset().top - 5;
		var windowWidth = $(window).width();
		var replaceWidth = 768;
		if (formOffsetTop > 97 && windowWidth > replaceWidth) {
			var formScrollTop = formOffsetTop - $('#header .header-navi-wrap').outerHeight();
		} else if (formOffsetTop > 430 && windowWidth < replaceWidth) {
			var formScrollTop = formOffsetTop - $('#header .header-navi-wrap').outerHeight();
		} else {
			var formScrollTop = formOffsetTop;
		}
		if (windowWidth <= 768)
		{
			$("html,body").animate({
				scrollTop: formScrollTop
			});
		}
	});
	function realty_debuging($data){
		console.log($data);
	}
	function getImgSize(img) {
	    var newImg = new Image();
	
	    newImg.onload = function() {
	      var height = newImg.height;
	      var width = newImg.width;
	      
	      if (width > height)
	    	  img.closest('.slick-slide').css('max-width', width + 'px');
	      else
	    	  img.closest('.slick-slide').css('max-height', height + 'px');
	      
	      img.css('max-height', height + 'px');
		  img.css('max-width', width + 'px');
	    };
	
	    newImg.src = img.attr('src'); 
	}
	$('#property_image_slider .property-image img').each(function(index, element){
		getImgSize($(this));
	});
	
	//scroll map
	$(window).bind("scroll", function() {
	// ドキュメントの高さ
	var scrollHeight = $(document).height();
	// ウィンドウの高さ+スクロールした高さ→ 現在のトップからの位置
	var scrollPosition = $(window).height() + $(window).scrollTop();
	// フッターの高さ
	var footHeight = $("footer").height();
	var linkHeight = $("#links").innerHeight();
	
	// スクロール位置がフッターまで来たら
	if ( scrollHeight - scrollPosition  <= footHeight && $(window).width() > 641 ) {
		// ページトップリンクをフッターに固定
		$(".page-template-template-map-vertical .map-container").addClass('static');
		$(".page-template-template-map-vertical .map-container").css('bottom', linkHeight + 'px');
	} else {
		// ページトップリンクを右下に固定
		$(".page-template-template-map-vertical .map-container").removeClass('static');
		$(".page-template-template-map-vertical .map-container").css('bottom', 'initial');
		}
	});
	//scroll map
	$('div#n2-ss-3 .n2-ss-layer > div').wrapInner('<div class="sword-wrap container"></div>');
	//$('div.sword').wrapAll('<div class="sword-wrap container"></div>');
	$('input').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
	});
	//$('.main-slider .property-search-form').wrap('<div class="col-sm-12 slider-col search-col"><div class="slider-searchbox"></div></div>');
	//$('.main-slider .map-select').wrap('<div class="col-sm-6 slider-col map-col"></div>');
	//$('.main-slider .search-col, .main-slider .map-col').wrapAll('<div class="slider-content"><div class="container"><div class="row"></div></div></div>');
	
	$('a img').hover(function(){
        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));
        if ($(this).attr('srcset'))
        {
        	$(this).attr('srcset', $(this).attr('srcset').replace('_off', '_on'));
        }
    }, function(){
    	if (!$(this).hasClass('currentPage')) {
    		$(this).attr('src', $(this).attr('src').replace('_on', '_off'));
    		if ($(this).attr('srcset'))
    		{
    			$(this).attr('srcset', $(this).attr('srcset').replace('_on', '_off'));
    		}
        }
	});
	
	if ($('#property-items li').length)
	{
		//latest office grid
		/*$('#latestoffice #property-items li').removeClass('col-lg-4');
		$('#latestoffice #property-items li').addClass('col-lg-6');
		$('#latestoffice #property-items li:first-child').removeClass('col-lg-6');
		$('#latestoffice #property-items li:first-child').addClass('col-lg-5 biglist');
		$('#latestoffice #property-items li.col-lg-6').wrapAll('<li class="col-lg-7"><ul class="row list-unstyled"></ul></li>');
		var lgHeight = $('div#property-items li.biglist .property-item').outerHeight();
		console.log('おおきめ高さ：' + lgHeight + 'px');
		var minConHeight =$('div#property-items li.col-lg-7 > ul > li > div > .property-content').height();
		console.log('小さめ白高さ：' + minConHeight + 'px');
		var windowWidth = $(window).width();
		var replaceWidth = 768;
		if(windowWidth > replaceWidth) {
		$('div#property-items li.col-lg-7 > ul > li > div > a > .property-thumbnail').css('height', (lgHeight / 2 - minConHeight - 7) + 'px');
		}*/
	}
	
	if ($('img.floor-plan').length)
	{
		$('img.floor-plan').magnificPopup({
		  type: 'image'
		  // other options
		});
	}
	
	if ($('.imgfit').length){
		setTimeout(function(){
			var featureHeight = [];
			$('.vc_figure.imgfit').each(function(){
				var img = $(this).find('img'); 
				if (img.height() > 0 && img.height() > img.width())
				{
					featureHeight.push(img.height());
				}
				else if (img.height() > 0 && img.height() < img.width()) {
					$(this).addClass('landscape');
				}
			});
			featureHeight.sort();
			$('.vc_figure.imgfit').css('height', featureHeight[0] + 'px');
			realty_debuging('minimum height：' + featureHeight[0] + 'px');
		}, 500);
		
	}
	if ($('.thum-element').length){
		setTimeout(function(){
			var rankingHeight = [];
			$('.thum-element').each(function(){
				var img = $(this).find('img'); 
				if (img.height() > 0)
				{
					rankingHeight.push(img.height());
				}
			});
			rankingHeight.sort();
			$('.thum-element').css('height', rankingHeight[0] + 'px');
			realty_debuging('minimum height2：' + rankingHeight[0] + 'px');
		}, 500);
		
	}
	if ($('.fit-height').length){
		setTimeout(function(){
			var fitHeight = [];
			$('.fit-height').each(function(){
				var img = $(this).find('img'); 
				if (img.height() > 0)
				{
					fitHeight.push(img.height());
				}
			});
			fitHeight.sort();
			$('.fit-height').css('height', fitHeight[0] + 'px');
			console.log('minimum height3：' + fitHeight[0] + 'px');
		}, 500);
		
	}
	var maxsameHeight = 0;
//もしdivがmaxHeightの値より大きい場合はdivの高さを全部合わせる
$("div.same-height").each(function(){
   if ($(this).height() > maxsameHeight) { maxsameHeight = $(this).height(); }
});
//divの高さを取得する
$("div.same-height").height(maxsameHeight);

var maxpickHeight = 0;
//もしdivがmaxHeightの値より大きい場合はdivの高さを全部合わせる
$("#property-items-featured .container.vc_row.wpb_row.vc_inner.vc_row-fluid.feature_row > div.order2 > .vc_column-inner > .table-wrap").each(function(){
   if ($(this).height() > maxpickHeight) { maxpickHeight = $(this).height(); }
});
//divの高さを取得する
$("#property-items-featured .container.vc_row.wpb_row.vc_inner.vc_row-fluid.feature_row > div.order2 > .vc_column-inner > .table-wrap").height(maxpickHeight);
	
	
		function initTypeHead() {
		$.typeahead({
			input : '[name="keyword"]',
			minLength : 1,
			maxItem : 15,
			order : "asc",
			accent: true,
			highlight: 'any',
//			display: ["name", "description"],
			maxItemPerGroup : 5,
//			backdropOnFocus: true,
//			backdrop: {
//		        "background-color": "#fff"
//		    },
			dropdownFilter : false,
			dynamic: true,
			templateValue: '{{title}}',
			emptyTemplate: message_no_result,
		    correlativeTemplate: true,
		    maxItemPerGroup: 10,
			group : {
				key : "group_name",
				template : function(item) {
					var group = item.group_name;
					group = group.toUpperCase();
					return group;
				}
			},

			source : {
				station : {
					display: 'name',
					href: '{{url}}',
					template: '<span>' +
					        '<span class="col-md-12 result-column"><span class="name">{{name}}</span>' +
					    '</span>',
					ajax : function(query){
						$('input[name="keyword"]').val(query);
						if (!$('.property-search-form:eq(0)').find('#search_type').length)
						{
							$('.property-search-form:eq(0)').append('<input type="hidden" name="search_type" id="search_type" />');
						}
						$('#search_type').val('station');
						return {
							type: 'GET',
							data: $('.property-search-form:eq(0)').serialize(),
							url : ajax_object.ajax_url + "?action=tt_ajax_search&response=json" ,
							path : "data.station"
						}
					}
				},
				address : {
					display: 'name',
					href: '{{url}}',
					template: '<span>' +
					        '<span class="col-md-12 result-column"><span class="name">{{name}}</span>' +
					    '</span>',
					ajax : function(query){
						$('input[name="keyword"]').val(query);
						if (!$('.property-search-form:eq(0)').find('#search_type').length)
						{
							$('.property-search-form:eq(0)').append('<input type="hidden" name="search_type" id="search_type" />');
						}
						$('#search_type').val('address');
						return {
							type: 'GET',
							data: $('.property-search-form:eq(0)').serialize(),
							url : ajax_object.ajax_url + "?action=tt_ajax_search&response=json" ,
							path : "data.address"
						}
					}
				},
				floor : {
					display: 'name',
					href: '{{url}}',
					template: '<span>' +
						'<span class="col-xs-4 col-sm-4 col-md-4 result-column"><span class="building-logo">' +
					            '<img src="{{image_url}}">' +
					        '</span></span>' +
					        '<span class="col-xs-8 col-sm-8 col-md-8 result-column"><span class="name">{{name}}</span><span class="address">{{address}}</span>' +
					    '</span>',
					ajax : function(query){
						$('input[name="keyword"]').val(query);
						if (!$('.property-search-form:eq(0)').find('#search_type').length)
						{
							$('.property-search-form:eq(0)').append('<input type="hidden" name="search_type" id="search_type" />');
						}
						$('#search_type').val('floor');
						return {
							type: 'GET',
							data: $('.property-search-form:eq(0)').serialize(),
							url : ajax_object.ajax_url + "?action=tt_ajax_search&response=json" ,
							path : "data.floor"
						}
					}
				}
			},
			callback : {
				done: function(){
					
				}
			},
			debug : false
		});
	}
	
	function initLoginRegisterForm() {
		if ($("form#registerform").length)
		{
			$("form#registerform input").each(function(){
				if ($(this).attr('required'))
				{
					$(this).addClass('validate[required]');
				}
			});
			$("form#registerform").validationEngine();
		}
		
		$('body').on('click', '.lwa-links-modal', function(){
        	$('.modal').modal('hide');
        });
        
        $('body').on('click', '.step_wraper a.showlogin', function(e){
        	e.preventDefault();
        	showLoginPopup();
        });
        
        $('body').on('click', '.lwa-links-login', function(e){
        	e.preventDefault();
        	$(".lwa-status").trigger("reveal:close");
        	setTimeout(function(){
        		showLoginPopup();
        	}, 300);
        });
        
        function actionLoginRegister(e, i, n){
        	if (i.result) {
        		location.reload();
        	}
        }
        
        $(document).on("lwa_register", function(e, i, n) {
        	actionLoginRegister(e, i, n);
        });
        
        $(document).on("lwa_login", function(e, i, n) {
        	actionLoginRegister(e, i, n);
        });
        
        $('#login-modal').on('hidden.bs.modal', function() {
            $("#pdf_viewing_message").hide();
        })
        
        $('body').on('click', '#pdf_viewing_disable', function(e){
        	e.preventDefault();
        	$("#pdf_viewing_message").show();
        	$('#login-modal').modal({
    			backdrop: 'static',
    		    keyboard: false
    		});
        });

        
        
        function showLoginPopup(popup){
        	$('body').LoadingOverlay("hide");
        	if (popup == 'register')
        	{
        		$('.lwa-links-modal').click();
        	}
        	else {
        		$('#custom_order_login_modal').modal({
        			backdrop: 'static',
        		    keyboard: false
        		});
        	}
        }
	}
	
	function initScrollBar(){
		if ($.fn.mCustomScrollbar != undefined && $(".scroll_bar_wraper").length)
		{
			$(".scroll_bar_wraper").mCustomScrollbar({
				scrollButtons:{
					enable:true
				}
			});
		}
	}
	
	function resizeMainSlider(){
		if ($('.main-slider').length)
		{
			var windowWidth = $(window).width();
			var windowHeight = $(window).height();
			var headerH = $('#header').height();
			var fixFooterHeight = $('.sp-fixfooter').height();
			var bannerHeight = $('#hometoplinks').height();
			
			if (windowWidth >= 768)
			{
				//var sliderHeight = (windowHeight - (headerH + bannerHeight)) + 'px';
				var sliderHeight = windowHeight + 'px';
			}
			else {
				//var sliderHeight = (windowHeight - (headerH + fixFooterHeight)) + 'px';
				var sliderHeight = windowHeight + 'px';
			}
			
			$('.main-slider').css('height', sliderHeight);
			$('#slidersec .crellyslider-slider').css('height', sliderHeight);
			$('.n2-ss-slider').css('height', sliderHeight);
			$('.n2-ss-slider-1').css('height', sliderHeight);
			$('.n2-ss-slide').css('height', sliderHeight);
			$('.n2-ss-layers-container').css('height', sliderHeight);
			
			var slconHeight = $(".slider-content").height();
			var mainSliderTop = $('.main-slider').offset().top;
			var mainSliderHeight = $('.main-slider').height();
			console.log((mainSliderHeight/2 - (slconHeight/2)));
			$('.main-slider .nextend-arrow').css('top', (mainSliderHeight/2 - (slconHeight/2)) + 'px');
			$('.main-slider .slider-content').css('top', (mainSliderHeight/2 - (slconHeight/2)) + 'px');
			$('.main-slider .slider-content').show();
		}
		
	}
	function initOneTime() {
		// Add keyword field for mobile in all page
		var searchElementWraper = $('#menu-item-1175');
		if(searchElementWraper.length)
		{
			searchElementWraper.prepend($('#temporary_search_block_wraper').html());
		}
		
		
		$(window).scroll(function() {
			var windowWidth = $(window).width();
			var replaceWidth = 768;
			if ($(window).scrollTop() > 97 && windowWidth > replaceWidth) {
				$('#header').addClass('sticky');
			} else if ($(window).scrollTop() > 430 && windowWidth < replaceWidth) {
				$('#header').addClass('sticky');
			} else {
				$('#header').removeClass('sticky');
			}
		});
		
		if ($('.main-slider').length)
		{
			var sliderInterval = setInterval(function(){
				if ($('.n2-ss-layers-container').attr('style') != '')
				{
					setTimeout(function(){
						resizeMainSlider();
					}, 1200)
					clearInterval(sliderInterval);
					sliderInterval = null;
				}
			}, 50);
		}
		
		$(window).on('resize', function(){
			setTimeout(function(){
				resizeMainSlider();
				
				$('#property_image_slider .property-image img').each(function(index, element){
					getImgSize($(this));
				});
				
			}, 500);
			
		});
		
		$('body').on('click', '.contact-list-header', function(){
			jQuery('#contact-multiple-modal').modal('toggle');
		});
		
		if ($('#building_detail_modal').length)
		{
			$('body').on('click', 'td.overlink', function(){
				location.href = $(this).find('a').attr('href');
			});
			
			$('body').on('click', '#property-search-results .property-item > a, #property-search-results .property-content .property-title > a', function(e){
				e.preventDefault();
				var clickElement = $(this);
				var building_id = clickElement.closest('.property-item').attr('data-buildingid');
				
				if ($('#building_detail_modal').attr('data-buildingid') != building_id)
				{
					$('body').LoadingOverlay("show");
					global_request.action = 'realty_get_floors';
					global_request.building_id = building_id;
					global_request.lang = global_lang;
					
					$.ajax({
						type: 'GET',
						url: ajax_object.ajax_url,
						data: global_request,
						dataType: 'json',
						success: function (response) {
							if (response.html)
							{
								$('#building_detail_modal .propertyTable tbody').html(response.html);
								
								var building_title = clickElement.closest('.property-item').find('.property-content .property-title h3').text();
								var building_image = clickElement.closest('.property-item').find('.property-thumbnail img').attr('src');
								
								$('#building_detail_modal .bld_name').text(building_title);
								$('#building_detail_modal .responsive-img').attr('src', building_image);
								
								$('#building_detail_modal .bld_sublocate .addr').text(response.address);
								$('#building_detail_modal .bld_sublocate .station').text(response.station);
								$('#building_detail_modal .details-summary').text(response.content);
								
								jQuery('#building_detail_modal').modal('show');
								$('#building_detail_modal').attr('data-buildingid', building_id);
								$('body').LoadingOverlay("hide");
							}
							else {
								// Redirect if building have only this floor
								location.href = clickElement.attr('href');
							}
						},
						error: function () {
							console.log( 'failed' );
							$('body').LoadingOverlay("hide");
						}
					});
				}
				else {
					jQuery('#building_detail_modal').modal('show');
				}
			});
		}
		
		if ($('form.lwa-register-form').length)
		{
			setTimeout(function(){
				if (!$('.wppb-recaptcha #recaptcha_pb_register0').html() && typeof wppbRecaptchaCallBack == 'function')
				{
					wppbRecaptchaCallBack();
				}
			}, 3000)
		}
		
	}
	
	initScrollBar();
	initTypeHead();
	initLoginRegisterForm();
	initOneTime();
	$.fn.autoKana('#user_name', '#user_name_kana', {katakana:true});
	
});



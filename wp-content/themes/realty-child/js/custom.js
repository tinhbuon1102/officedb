jQuery(document).ready(function($){
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
	$('div#n2-ss-3 .n2-ss-layer > div').wrapInner('<div class="sword-wrap"></div>');
	$('input').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
	});
	$('.main-slider .property-search-form').wrap('<div class="slider-searchbox"><div class="container"></div></div>');
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
	$(window).on('load resize', function(){
		var windowWidth = $(window).width();
		
		if ($(".entry-content .container").length)
		{
			var containerLeft = $(".entry-content .container").offset().left;
			//var ssearchHeight = $(".main-slider .slider-searchbox .property-search-form").outerHeight();
			$('.main-slider .slider-searchbox').css('margin-left', '-' + containerLeft + 'px');
			$('.main-slider .slider-searchbox').css('width', windowWidth + 'px');
			//$('.main-slider .slider-searchbox').css('margin-top', '-' + ssearchHeight + 'px');
		}
		var windowHeight = $(window).height();
		var headerH = $('#header').height();
		var replaceWidth = 768;
		if(windowWidth < replaceWidth) {
		$('.main-slider').css('height', (windowHeight - headerH) + 'px');
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
		var featureHeight = [];
		$('.vc_figure.imgfit').each(function(){
			var img = $(this).find('img'); 
			featureHeight.push(img.attr('height'));
		});
		featureHeight.sort();
		$('.vc_figure.imgfit').css('height', featureHeight[0] + 'px');
		console.log('minimum height：' + featureHeight[0] + 'px');
		
//		$('.vc_figure.imgfit').imagefit({
//	        mode: 'outside', //指定サイズの中に入れるかはみ出させるかの指定: 'inside' or 'outside'.
//		    halign : 'center', //指定サイズのブロックに対する画像の配置横位置: 'left', 'center' or 'right'
//		    valign : 'top' , //指定サイズのブロックに対する画像の配置縦位置: 'top', 'middle' or 'bottom'
//	        force : false, //画像のサイズが指定サイズより小さい場合もリサイズするかどうか:true or false 
//	        onStart: function(){}
//        });
	}
	
		function initTypeHead() {
		$.typeahead({
			input : '[name="keyword"]',
			minLength : 1,
			maxItem : 15,
			order : "asc",
			accent: true,
			highlight: true,
//			display: ["name", "description"],
			maxItemPerGroup : 5,
			backdropOnFocus: true,
			dropdownFilter : false,
			dynamic: true,
			templateValue: '{{name}}',
			emptyTemplate: message_no_result,
		    template: '<span>' +
				'<span class="col-xs-4 col-sm-4 col-md-4 result-column"><span class="building-logo">' +
		            '<img src="{{image_url}}">' +
		        '</span></span>' +
		        '<span class="col-xs-8 col-sm-8 col-md-8 result-column"><span class="name">{{name}}</span>' +
		    '</span>',
		    correlativeTemplate: true,
			source : {
				floor : {
					display: 'name',
					ajax : function(query){
						$('input[name="keyword"]').val(query);
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
	
	// Add keyword field for mobile in all page
	var searchElementWraper = $('#menu-item-1175');
	if(searchElementWraper.length)
	{
		searchElementWraper.prepend($('#temporary_search_block_wraper').html());
	}
	
	initTypeHead();
	initLoginRegisterForm();
	$.fn.autoKana('#user_name', '#user_name_kana', {katakana:true});
	
});



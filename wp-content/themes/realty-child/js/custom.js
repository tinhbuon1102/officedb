jQuery(document).ready(function($){
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
		if ($(window).scrollTop() > 97) {
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
	});
	
	$('body').on('click', '#contact_agent_button', function(){
		$("html, body").animate({ scrollTop: $('#contact-form').offset().top - 150 }, 1000);
	});
	
	if ($('#property-items li').length)
	{
		//latest office grid
		$('#latestoffice #property-items li').removeClass('col-lg-4');
		$('#latestoffice #property-items li').addClass('col-lg-6');
		$('#latestoffice #property-items li:first-child').removeClass('col-lg-6');
		$('#latestoffice #property-items li:first-child').addClass('col-lg-5 biglist');
		$('#latestoffice #property-items li.col-lg-6').wrapAll('<li class="col-lg-7"><ul class="row list-unstyled"></ul></li>');
		var lgHeight = $('div#property-items li.biglist .property-item').outerHeight();
		console.log('おおきめ高さ：' + lgHeight + 'px');
		var minConHeight =$('div#property-items li.col-lg-7 > ul > li > div > .property-content').height();
		console.log('小さめ白高さ：' + minConHeight + 'px');
		$('div#property-items li.col-lg-7 > ul > li > div > a > .property-thumbnail').css('height', (lgHeight / 2 - minConHeight - 7) + 'px');
	}
	
		function initTypeHead() {
		$.typeahead({
			input : '#keyword',
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
		        '<span class="description" style="display: block;">{{description}}</span></span>' +
		    '</span>',
		    correlativeTemplate: true,
			source : {
				floor : {
					display: 'name',
					ajax : function(query){
						$('#keyword').val($('#keyword').val().trim());
						return {
							type: 'GET',
							data: $('.property-search-form').serialize(),
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
			debug : true
		});
	}
	
	initTypeHead();
	
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
	
	
//	var validate = $("form#omOrder").validationEngine('validate');
});



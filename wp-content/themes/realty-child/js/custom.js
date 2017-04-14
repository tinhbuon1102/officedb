jQuery(document).ready(function($){
	$('input').iCheck({
    checkboxClass: 'icheckbox_square',
    radioClass: 'iradio_square',
    increaseArea: '20%' // optional
	});
	$('.main-slider .property-search-form').wrap('<div class="slider-searchbox"><div class="container"></div></div>');
	$('a img').hover(function(){
        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));
		$(this).attr('srcset', $(this).attr('srcset').replace('_off', '_on'));
          }, function(){
             if (!$(this).hasClass('currentPage')) {
             $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
			 $(this).attr('srcset', $(this).attr('srcset').replace('_on', '_off'));
        }
	});
	$(window).on('load resize', function(){
		var windowWidth = $(window).width();
		var containerLeft = $(".entry-content .container").offset().left;
		//var ssearchHeight = $(".main-slider .slider-searchbox .property-search-form").outerHeight();
		$('.main-slider .slider-searchbox').css('margin-left', '-' + containerLeft + 'px');
		$('.main-slider .slider-searchbox').css('width', windowWidth + 'px');
		//$('.main-slider .slider-searchbox').css('margin-top', '-' + ssearchHeight + 'px');
	});
});

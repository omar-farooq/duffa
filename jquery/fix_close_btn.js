//this is to set the close button to the top of the page when viewing the about page

$(document).ready(function() {

	$(window).bind('scroll', function(){
	var closeBtnOffset = $('#close-about-btn-marker').offset().top;
		var closeTop = $('.close-about-btn');
		var scroll = $(window).scrollTop();


		if (scroll > closeBtnOffset + 54) { closeTop.addClass('fixed_close'); }
		//will add a slightly higher point to remove the fixed close button to avoid conflict

			if(closeTop.hasClass('fixed_close') == true) {
			if(scroll < closeBtnOffset) { closeTop.removeClass('fixed_close'); }
		}

	});
});

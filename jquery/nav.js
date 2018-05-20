//this is to toggle the menu when clicking the hamburger icon
$(document).ready(function(){
	$('.menu').hide();

	$('.hamburger').click(function(e) {
		e.stopPropagation();
		$('.menu').slideToggle('fast', function() {
		});
	});

	if($('.menu').height() > 1) {
		$(document).on('click', function(event) {
			if(!$(event.target).closest('.menu').length) {
				$('.menu').slideUp('fast', function() {

				});
			}
		});
	}
});

//this is for a person's profile. If you want to send them a message then click on the 'send message' link and it will toggle a message box

$(document).ready(function() {
	$('#expand-send-message-form').click(function() {
		$('#send-message').slideToggle(150, function() {
			$(window).scrollTop($('#send-message').offset().top);
		});
	});
});

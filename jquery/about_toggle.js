//this is obviously for the first page when you click the 'about' button to display the about section. It also defines what pressing the close button does

$(document).ready(function(){
	$('#about').hide();


	$('.about-btn').click(function(){
		$('#about').slideDown(400);
		$('html, body').animate({
      		scrollTop: $("#about").offset().top 
		}, 1000);

	
	});	

	$('.close-about-btn').click(function(){
		$('#about').hide();
		$('html, body').animate({ scrollTop: 0 }, 0);

		
	
	});

});

$(document).ready(function() {
	$('.click-for-pw').on('click', function() {
			$('#gmap_canvas').attr('src', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2378.359015745444!2d-2.2192582840333546!3d53.40840517776186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487bb2f984c91013%3A0xdb33cc4f28cdbbaa!2sParrs+Wood+High+School!5e0!3m2!1sen!2suk!4v1525873152292');
			$('.click-for-fog-lane').show();
			$('.click-for-pw').hide();

	});

	$('.click-for-fog-lane').on('click', function() {
			$('#gmap_canvas').attr('src', 'https://maps.google.com/maps?q=fog lane park&t=&z=13&ie=UTF8&iwloc=&output=embed');
			$('.click-for-fog-lane').hide();
			$('.click-for-pw').show();

	});


	
});

//this is if you want to make a slideshow out of the banner on the top of the page
//this is useful for adding things like sponsor information in the form of a banner
//it can also be used to alert important information
$(document).ready(function() {

	$('.dot').each(function() {
		var dotValue = $(this).attr('dotValue');
		$(this).on('click',function() {
			showSlides(slideIndex = dotValue);
		});
	});

	var slideIndex = 1;
	showSlides(slideIndex);

	function currentSlide(n) {
	  showSlides(slideIndex = n);
	}

	function showSlides(n) {
	  var i;
	  var slides = $(".banner-slides");
	  var dots = $(".dot");
	  if (n > slides.length) {slideIndex = 1}
	  if (n < 1) {slideIndex = 2}
	  for (i = 0; i < slides.length; i++) {
	      slides[i].style.display = "none";
	  }
	  for (i = 0; i < dots.length; i++) {
	      dots[i].className = dots[i].className.replace(" banner-active", "");
	  }
	  slides[slideIndex-1].style.display = "block";
	  dots[slideIndex-1].className += " banner-active";
	}

	$('#slideshow-container').on('swiperight', function() {
		showSlides(slideIndex = slideIndex + 1);
	});

	$('#slideshow-container').on('swipeleft', function() {
		showSlides(slideIndex = slideIndex - 1);
	});
});

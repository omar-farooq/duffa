$(document).ready(function() {

	var altImage = function () {
		var $this = $(this);
		var newSource = $this.data('alt-src');
		$this.data('alt-src', $this.attr('src'));
		$this.attr('src', newSource);
	}

	$(function () {
		$('.fb').hover(altImage, altImage);
		$('.twitter').hover(altImage, altImage);
	});

	function preload(arrayOfImages) {
	    $(arrayOfImages).each(function(){
		(new Image()).src = this;
	    });
	}

	preload([
	    './icons/fb.png',
	    './icons/twitter.png',
	]);

});

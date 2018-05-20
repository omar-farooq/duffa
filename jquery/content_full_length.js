//this is to make the content div full length on bigger screens so that it touches the footer
$(window).load(function() {

	function makeContentFullLength() {
		var CH = $("#content").height();
		var WH = $(window).height();
		var WW = $(window).width();
		var FH = $("#footer").height();
		var HH = $("#header").height();
		var BH = $("#banner").height();
		var RH = ($("#related").outerHeight());
		var NH = WH - (FH + HH + BH + 8);

		if(CH < RH && (WW > 1000)) {

			if ((CH + FH + HH + BH < WH) && (RH + FH + HH + BH < WH)) {
				$("#content").height(NH);
			} else {
				$("#content").height(RH);
			}

		} else if(CH > RH && (WW > 1000)) {

			if (CH + FH + HH + BH < WH && (WW > 1000)) {

				$("#content").height(NH);
			}

		} else if(CH == RH) {

			return false;
			
		} else {
			$("#content").css({'height':'auto'});
		}
	}

	makeContentFullLength();

	$(window).resize(function() {
		makeContentFullLength();
	});

});

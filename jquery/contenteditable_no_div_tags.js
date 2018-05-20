//The code below is for if line breaks add <div> tags. 
//It doesn't do this on localhost but on a server it may so uncomment if it does this
$(document).ready(function() {
	$('div[contenteditable]').keydown(function(e) {
	// trap the return key being pressed
		if (e.keyCode === 13) {
	  	// insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
	  	document.execCommand('insertHTML', false, '<br><br>');
	  	// prevent the default behaviour of return key pressed
	  	return false;
	    	}
	});

});

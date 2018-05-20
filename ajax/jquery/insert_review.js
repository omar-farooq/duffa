$(document).ready(function(){

	$('#review-btn').click(function(){

		review_post_btn_click();

	});

});

function review_post_btn_click() {

	var _text = $('#review-text').html();
	var _userid = $('#userid').val();
	var _sessionid = $('#sessionid').val();

	if(_text.length>0) {
		$('#review-text').css('border', 'solid black');
		$('#review-text').css('padding', '0 5 0 5');
		$('#review-text').val("");
		$.post("./ajax/php/insert_review.php" , 
		{
			task: "insert_review",
			userid: _userid,
			sessionid: _sessionid,
			text: _text
		})
		.success(
		 $('#review-text').html(""),
		function(data) {
		review_insert( jQuery.parseJSON( data ) );
		});
	}

	else {
		$('#review-text').css('border', 'solid #ff0000');
		$('#review-text').css('padding', '0 5 0 5');
		console.log('no text entered');
	}

}


//inserting a new review function

function review_insert(data) {

	var t='';
	t += '<li class="review-holder" id="_'+data.reviewid+'">';
	t += '<div class="user-image">';
	t += '<img src="'+data.profilepic+'" class="user-image-pic">';
	t += '</div>';
	t += '<div class="review-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.reviewid+'" class="delete-btn">X</li>';
	t += '<li id="'+data.reviewid+'" class="edit-btn">edit</li>';
//	t += '<li id="'+data.reviewid+'" class="ajax-reply-btn">reply</li>';
	t += '</ul>';
	t += '</div>';
	t += '<div class="review-body">';
	t += '<input type="hidden" id="userid'+data.reviewid+'" value="'+data.userid+'">';
	t += '<h3 class="username-field">'+data.username+'</h3>';
	t += '<div class="review-text">'+data.review+'</div>';
	t += '</div>';
	t += '</li>';

	$('.reviews-holder-ul').prepend( t );
	add_delete_handlers();
	add_edit_handlers();
//	add_ajax_reply_handlers();


  function parseHTML()
  {
			$.get('./php/models/bbParser.php',
			{
				bbcode: data.review
			},
			function(txt){
				var reviewText = $("#_"+data.reviewid).find($(".review-text"));
				reviewText.html(txt);
				})
			
  
  }

	parseHTML();

}

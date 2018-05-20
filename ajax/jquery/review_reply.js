$(document).ready(function(){
	add_reply_handlers();
});

function add_reply_handlers(){
	$('.reply-btn').each(function(){
		var reply_btn = this;
		var review_body = $(this).parent().parent().parent();

		$(reply_btn).click(function() {
			reply_btn_fn(reply_btn);

		});		
			

	});
}



function reply_btn_fn(reply_btn){

	var review_body = $(reply_btn).parent().parent().parent();
	$('.review-buttons-holder').css('visibility','hidden');
	$('.comment-buttons-holder').css('visibility','hidden');
	review_body.append("<div id='replydiv'><textarea class='reviewreplytext'></textarea>" + "<br><div id='new-btn-holder'><span class='cancelreply' name='cancel'>cancel </span> <span class='makereply' name='makereply'>reply</span></div></div>");

	$(document).off('click');
	$(document).on('click', '.cancelreply', function(){
		$('.review-buttons-holder').css('visibility','');
		$('.comment-buttons-holder').css('visibility','');
		$('#replydiv').remove();
	});

	$(document).on('click', '.makereply', function(){
		$('.review-buttons-holder').css('visibility','');
		$('.comment-buttons-holder').css('visibility','');
		var _review_id = reply_btn.id;
		var _text = review_body.find('.reviewreplytext').val();

		if(_text.length > 0) {
			$('#replydiv').remove();
			$.post("./ajax/php/review_reply.php", {
			task: "review_reply",
			review_id: _review_id,
			text: _text
			})

			.success(function(data) {
				replied( jQuery.parseJSON( data ) );

			});

		} else {
			$('.review-buttons-holder').css('visibility','hidden');
			$('.comment-buttons-holder').css('visibility','hidden');
			$('.reviewreplytext').css('border', 'solid #ff0000');
			$('.reviewreplytext').css('padding', '0 5 0 5');
			console.log('no text entered');
		}
		
			

	});
}

function replied(data){
	var t='';
	t += '<li class="comment-holder" id="com_'+data.commentid+'">';
	t += '<div class="comment-top-wrapper">'
	t += '<div class="user-image">';
	t += '<img src="'+data.profilepic+'" class="user-image-pic">';
	t += '</div>';
	t += '<div class="comment-body">';
	t += '<input type="hidden" id="comments-userid'+data.commentid+'" value="'+data.userid+'">';
	t += '<h3 class="username-field">'+data.username+' replied</h3>';
	t += '<div class="comment-text">'+data.comment+'</div>';
	t += '</div></div>';
	t += '<div class="comment-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.commentid+'" class="comment-delete-btn">X</li>';
	t += '<li id="'+data.commentid+'" class="comment-edit-btn">edit</li>';
	t += '</ul>';
	t += '</div>';
	t += '</li>';

	$('#_' +data.reviewid).after( t );
	add_comment_delete_handlers();
	add_comment_edit_handlers();

  function parsereviewreplyHTML()
  {
			$.get('./php/models/bbParser.php',
			{
				bbcode: data.comment
			},
			function(txt){
				var commentText = $("#com_"+data.commentid).find($(".comment-text"));
				commentText.html(txt);
				})
			
  
  }
	parsereviewreplyHTML()();

}

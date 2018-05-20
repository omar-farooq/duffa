$(document).ready(function(){
	add_comment_reply_handlers();
});

function add_comment_reply_handlers(){
	$('.comment-reply-btn').each(function(){
		var comment_reply_btn = this;
		var comment_body = $(this).parent().parent().parent();

		$(comment_reply_btn).click(function() {
			comment_reply_btn_fn(comment_reply_btn);
		});		
			

	});
}


function comment_reply_btn_fn(comment_reply_btn){

	var comment_body = $(comment_reply_btn).parent().parent().parent();
	$('.comment-buttons-holder').css('display','none');
	$('.review-buttons-holder').css('visibility','hidden');
	comment_body.append("<div id='replydiv2'><textarea class='comment-reply-text2'></textarea>" + "<br><div id='new-btn-holder2'><span class='cancelreply2' name='cancel'>cancel </span> <span class='makereply2' name='makereply'>reply</span></div></div>");

	$(document).off('click');
	$(document).on('click', '.cancelreply2', function(){
		$('.comment-buttons-holder').css('display','inline');	
		$('.review-buttons-holder').css('visibility','');
		$('#replydiv2').remove();
	});

	$(document).on('click', '.makereply2', function(){
		$('.comment-buttons-holder').css('display','inline');
		$('.review-buttons-holder').css('visibility','');
		var _parent_comment_id = comment_reply_btn.id;
		var _parent_user_id = $('#comments-userid' + _parent_comment_id).val();
		var _text = comment_body.find('.comment-reply-text2').val();

		if(_text.length > 0) {
			$('#replydiv2').remove();
			$.post("./ajax/php/comment_reply.php", {
			task: "comment_reply",
			parent_comment_id: _parent_comment_id,
			parent_user_id: _parent_user_id,
			text: _text
			})

			.success(function(data) {
				comment_replied( jQuery.parseJSON( data ) );

			});

		} else {
			$('.comment-buttons-holder').css('display','none');
			$('.review-buttons-holder').css('visibility','hidden');
			$('.comment-reply-text2').css('border', 'solid #ff0000');
			$('.comment-reply-text2').css('padding', '0 5 0 5');
			console.log('no text entered');
		}
		
			

	});
}

function comment_replied(data){
	var t='';
	t += '<li class="comment-holder" id="com_'+data.commentid+'">';
	t += '<div class="comment-top-wrapper">'
	t += '<div class="user-image">';
	t += '<img src="'+data.profilepic+'" class="user-image-pic">';
	t += '</div>';
	t += '<div class="comment-body">';
	t += '<input type="hidden" id="comments-userid'+data.commentid+'" value="'+data.userid+'">';
	t += '<h3 class="username-field">'+data.firstname + '</h3><h3 class="in-reply-to"> ► ' + data.parentname + '</h3>';
	t += '<div class="comment-text">'+data.comment+'</div>';
	t += '</div></div>';
	t += '<div class="comment-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.commentid+'" class="comment-delete-btn">delete</li> ';
	t += '<li id="'+data.commentid+'" class="comment-edit-btn"> edit</li>';
	t += '</ul>';
	t += '</div>';
	t += '</li>';

	$('#_' +data.parentreviewid).after( t );
	add_comment_delete_handlers();
	add_comment_edit_handlers();

  function parsecommentHTML()
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

	parsecommentHTML();

}

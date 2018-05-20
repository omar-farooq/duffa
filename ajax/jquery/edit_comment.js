$(document).ready(function(){
	add_comment_edit_handlers();

});

function add_comment_edit_handlers() {
	$('.comment-edit-btn').each(function(){
		var comment_edit_btn = this;
		var comment_body = $(this).parent().parent().parent();
		var comment_content = comment_body.find(".comment-text").html();
		var commentId = comment_body.attr('id');
		var c = comment_body.find(".comment-text");

		$(comment_edit_btn).click(function() {

			//get the original comment from the database
			$.get('./ajax/php/edit_comment.php',
			{
				fromCommentHolderid: commentId
			},
			function(commentFromDB){



				//insert the unedited comment from the database into the edit box.


				$('.comment-buttons-holder').css('display','none');
				$('.review-buttons-holder').css('visibility','hidden');
				comment_body.find(".comment-text").html("<textarea class='edit-com'>" + commentFromDB + "</textarea>" + "<br><div id='new-btn-holder'><span class='cancel-edit-com' name='cancel'>cancel </span> <span class='make-edit-com' name='make-edit-com'>edit</span></div>");

			})

			$(document).off('click');
				$(document).on('click','.cancel-edit-com',function(){
					comment_body.find(".comment-text").html(comment_content);
					$('.comment-buttons-holder').css('display','inline-block');
					$('.review-buttons-holder').css('visibility','');
				});
			
				$(document).on('click','.make-edit-com',function(e){
					e.preventDefault();
					$('.comment-buttons-holder').css('display','inline-block');
					$('.review-buttons-holder').css('visibility','');
					var _comment_id = comment_edit_btn.id;
					var _text = comment_body.find(".edit-com").val();
					var _user_id = $('#comments-userid' + _comment_id).val();

					$.post("./ajax/php/edit_comment.php", {
					task: "comment_edit",
					comment_id: _comment_id,
					text: _text,
					user_id: _user_id
					})

					.success(function() {
						comment_edited(c, _text);
				
					
					});				
				});


		});
	});

}

function comment_edited(c, _text){
$(c).fadeOut(200,function(){
//_text=_text.replace(/\n+/g,"<br>");

	$.get('./php/models/bbParser.php',
	{
		bbcode: _text
	},
	function(txt){
		$(c).html(txt).fadeIn();
		add_comment_edit_handlers();
		})

});
}

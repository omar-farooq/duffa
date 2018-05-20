$(document).ready(function(){

	$('#comment-btn').click(function(){

		comment_post_btn_click();

	});

});

function comment_post_btn_click() {

	var _text = $('#comment-text').html();
	var _name = $('#commentor-name').val();
	var _articleid = $('#articleid').val();

	if(_name.trim() == "") { var _name = "anonymous";}

	if(_text.length>0) {
		$('#comment-text').css('border', 'solid black');
		$('#comment-text').css('padding', '0 5 0 5');
		$('#comment-text').val("");
		$.post("./ajax/php/news_comments.php" , 
		{
			task: "insert_comment",
			articleid: _articleid,
			text: _text,
			name: _name
		})
		.success(
		 $('#comment-text').html(""),
		function(data) {
		comment_insert( jQuery.parseJSON( data ) );
		});
	}

	else {
		$('#comment-text').css('border', 'solid #ff0000');
		$('#comment-text').css('padding', '0 5 0 5');
		console.log('no text entered');
	}

}


//inserting a new review function

function comment_insert(data) {

	var t='';
	t += '<li class="comment-holder" id="_'+data.commentid+'">';
	t += '<div class="comment-top-wrapper">';
	t += '<div class="user-image">';
	t += '<img src="'+data.profilepic+'" class="user-image-pic">';
	t += '</div>';
	t += '<div class="comment-body">';
	t += '<input type="hidden" id="comments-userid'+data.commentid+'" value="'+data.userid+'">';
	t += '<h3 class="username-field">'+data.username+'</h3>';
	t += '<div class="comment-text">'+data.comment+'</div>';
	t += '</div>';
	t += '</div>';
	t += '<div class="comment-buttons-holder">';
	t += '<ul>';
	t += '<li id="'+data.commentid+'" class="delete-btn">delete </li>';
	t += '<li id="'+data.commentid+'" class="edit-btn">edit</li>';
	t += '</ul>';
	t += '</div>';
	t += '</li>';

	$('.comments-holder-ul').prepend( t );
	add_comment_delete_handlers();
	add_comment_edit_handlers();

  function parsecommentHTML()
  {
			//parse the HTML into BB Code
			$.get('./php/models/bbParser.php',
			{
				bbcode: data.comment
			},
			function(txt){
				var commentText = $("#_"+data.commentid).find($(".comment-text"));
				commentText.html(txt);
				})
			
  
  }

	parsecommentHTML();
}

//the next part is for deleting a comment

$(document).ready(function(){

	add_comment_delete_handlers();

});

function add_comment_delete_handlers(){

	$('.delete-btn').each(function(){

		var btn = this;
		$(btn).click(function(){

			comment_delete(btn.id);			
		});
	});

}

function comment_delete(_comment_id){
	var _user_id = $('#comments-userid' + _comment_id).val();

	$.post( "./ajax/php/delete_comment.php" , {
		task: "comment_delete",
		comment_id: _comment_id,
		user_id: _user_id
	})
	.success( function() {
 		$('#_' + _comment_id).detach();
	});

}


//the next part is for editing a comment
$(document).ready(function(){
	add_comment_edit_handlers();

});

function add_comment_edit_handlers() {
	$('.edit-btn').each(function(){
		var comment_edit_btn = this;
		var comment_body = $(this).parent().parent().parent();
		var comment_content = comment_body.find(".comment-text").html();
		var commentId = comment_body.attr('id');
		var c = comment_body.find(".comment-text");

		$(comment_edit_btn).click(function() {



			//get the original comment from the database
			$.get('./ajax/php/edit_comment.php',
			{
				fromCommentid: commentId
			},
			function(commentFromDB){



				//insert the unedited comment from the database into the edit box.
				$('.comment-buttons-holder').css('visibility','hidden');
				comment_body.find(".comment-text").html("<textarea class='edit-com'>" + commentFromDB + "</textarea>" + "<br><div id='new-btn-holder'><span class='cancel-edit-com' name='cancel'>cancel </span> <span class='make-edit-com' name='make-edit-com'>edit</span></div>");

			})

				//what happens when the cancel button is clicked
				$(document).off('click');
					$(document).on('click','.cancel-edit-com',function(){
						comment_body.find(".comment-text").html(comment_content);
				
						$('.comment-buttons-holder').css('visibility','');
					});




			
					$(document).on('click','.make-edit-com',function(e){
						e.preventDefault();
						$('.comment-buttons-holder').css('visibility','');	
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
//the below that is commented out is to remove additional line breaks if we want/need to go that route
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

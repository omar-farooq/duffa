$(document).ready(function(){

	add_comment_delete_handlers();

});

function add_comment_delete_handlers(){

	$('.comment-delete-btn').each(function(){

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
 		$('#com_' + _comment_id).detach();
	});

}

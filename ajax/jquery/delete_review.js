$(document).ready(function(){

	add_delete_handlers();

});

function add_delete_handlers(){

	$('.delete-btn').each(function(){

		var btn = this;
		$(btn).click(function(){

			review_delete(btn.id);			
		});
	});

}

function review_delete(_review_id){
	var _user_id = $('#userid' + _review_id).val();

	$.post( "./ajax/php/delete_review.php" , {
		task: "review_delete",
		review_id: _review_id,
		user_id: _user_id
	})
	.success( function() {
 		$('#_' + _review_id).detach();
	});

}

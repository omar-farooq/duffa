$(document).ready(function(){
	add_edit_handlers();

});

function add_edit_handlers() {
	$('.edit-btn').each(function(){
		var edit_btn = this;
		var review_body = $(this).parent().parent().parent();
		var review_content = review_body.find(".review-text").html();
		var reviewId = review_body.attr('id');
		var r = review_body.find(".review-text");

		$(edit_btn).click(function() {


			//get the original review from the database
			$.get('./ajax/php/edit_review.php',
			{
				fromReviewHolderid: reviewId
			},
			function(reviewFromDB){


			//insert the unedited review from the database into the edit box.

			$('.review-buttons-holder').css('visibility','hidden');
			$('.comment-buttons-holder').css('visibility','hidden');
			review_body.find(".review-text").html("<textarea class='edittext'>" + reviewFromDB + "</textarea>" + "<br><div id='new-btn-holder'><span class='canceledit' name='cancel'>cancel </span> <span class='makeedit' name='makeedit'>edit</span></div>");


			})

			$(document).off('click');


				$(document).on('click','.canceledit',function(){
					review_body.find(".review-text").html(review_content);
					$('.review-buttons-holder').css('visibility','');
					$('.comment-buttons-holder').css('visibility','');
				});
			
				$(document).on('click','.makeedit',function(e){
					e.preventDefault();
					$('.review-buttons-holder').css('visibility','');
					$('.comment-buttons-holder').css('visibility','');
					var _review_id = edit_btn.id;
					var _text = review_body.find(".edittext").val();
					var _user_id = $('#userid' + _review_id).val();

					$.post("./ajax/php/edit_review.php", {
					task: "review_edit",
					review_id: _review_id,
					text: _text,
					user_id: _user_id
					})

					.success(function() {
						edited(r, _text);
				
					
					});				
				});


		});
	});

}

function edited(r, _text){
$(r).fadeOut(200,function(){

	$.get('./php/models/bbParser.php',
		{
			bbcode: _text
		},
		function(txt){
			$(r).html(txt).fadeIn();
			add_edit_handlers();
		})


});
}

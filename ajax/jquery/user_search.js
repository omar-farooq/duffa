$(document).ready(function() {

		$(document).on('keyup', 'input', function(e) {
			var searchBoxVal = $(this).val();
			$.post("./ajax/php/user_search.php", {
				task: "search_user",
				cache: false,
				string: searchBoxVal
			})

			.success(function(data){
				searchfor(  data );
			});
		})
});

function searchfor(data) {
	$('.user_list_container').html("<ul class='users_list_ul'>" +data+ "</ul>");
}

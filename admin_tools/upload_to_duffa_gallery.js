$(document).ready(function() {
	$('.file_drag_area').on('dragover', function() {
		$(this).addClass('file_drag_over');	
		$(this).text('ready. Unclick your mouse')
		return false;
	});

	$('.file_drag_area').on('dragleave', function() {
		$(this).removeClass('file_drag_over');
		$(this).html('Drop Files Here<br> wait for the ready signal before dropping')
		return false;
	});

	$('.file_drag_area').on('drop', function(e) {
		e.preventDefault();
		$(this).removeClass('file_drag_over');
		$(this).text('uploading');
		var formData = new FormData();
		var files_list = e.originalEvent.dataTransfer.files;
		
		for(var i=0; i<files_list.length; i++) {
			formData.append('file[]', files_list[i]);
		}


		$.ajax({
			url:'./admin_tools/image_to_db.php',
			method:'POST',
			data:formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data) {

				var parsedData = jQuery.parseJSON(data);
				if(parsedData.error.length > 0){ $('#upload-errors').css('display', 'block');}
				$('.additions-text').css('display', 'inline-block');
				$('#uploaded_file').prepend(parsedData.image);
				$('#upload-errors').append(parsedData.error);
				$('.file_drag_area').text("Done! upload some more");
			}
		})

		
	});


		    // Open file selector on div click
		    $("#upload-area").click(function(){
			$("#file").click();
		    });

		    // file selected
		    $("#file").change(function(){
			var formData = new FormData();

			var browseImage = $('#file')[0].files;

			for(var i=0; i<browseImage.length; i++) {

			formData.append('file[]', browseImage[i]);
			}

			$.ajax({
				url:'./admin_tools/image_to_db.php',
				method:'POST',
				data:formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					var parsedData = jQuery.parseJSON(data);
					if(parsedData.error.length > 0){ $('#upload-errors').css('display', 'block');}
					$('.additions-text').css('display', 'inline-block');
					$('#uploaded_file').prepend(parsedData.image);
					$('#upload-errors').append(parsedData.error);
				}
			})

		    });
	
});

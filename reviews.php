<?php

$banner_text = 'Reviews';
$banner_image = './layout/banner_images/random10.jpg';

include_once "./layout/layout-top2.php"; ?>

<script src="./jquery/jquery.bbcode.js"></script>
<style>
#banner_text {
right: 60px;
left: initial;
}

.banner-slides {
	background-position: 50% 40%;
}
</style>

<?php 

//create a model of the session and get its properties
$session_id = $_GET['id'];
$session = new Session($session_id);
$session_id = $session->SessionID;
$session_date = $session->SessionDate;
$session_date = strtotime($session_date);
$session_date = date("d-m-y H:i", $session_date);
$session_location = $session->Location;



//if session is defined

if($session_id){

	
	echo $session_date . $session_location . "<br>";
	?><div id="reviewbox"><?php

	//if logged in
	if(!empty($_SESSION['userid']) && !empty($_SESSION['LoggedIn'])){

	//write a review
?>
	
		
		<div class="post-review-container">
			<a href="#image-modal-popup"><img src='./icons/insert_image.png' id="insert-image-icon"></a>
			<a href="#video-modal-popup"><img src='./icons/youtube.png' id="insert-video-icon"></a>
		
		<div name="review-text" id="review-text" contentEditable="true"></div>
		<input type="hidden" id="userid" value="<?php echo $_SESSION['userid']?>">
		<input type="hidden" id="sessionid" value="<?php echo $session_id?>">
		
		
			<div id="review-btn">review</div>
		
		</div>

	<!-- modal popup for inserting images -->

		<link rel="stylesheet" type="text/css" href="./css/modal.css">

		<div id="image-modal-popup" class="modaltext">
			<div>
			<a href="#close" class="close" title="Close">X</a>

				You can upload images and embed them. Once you click 'upload and insert' then it's in the gallery whether you post your comment or not.<br><br>

				<script type="text/javascript">
					//the javascript for previewing an image before uploading and inserting it
					function readURL(input) {
					    if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function (e) {
						    $('#image-upload-preview').attr('src', e.target.result) .width(350);
						    $('#submitImage').css('opacity', '1');
						}

						reader.readAsDataURL(input.files[0]);
					    }
					}
				    </script>
				<form method = "POST" action = "./ajax/php/insert_image.php" name="insertImage" id="insertImage" enctype="multipart/form-data">
					Select image:
					<input type="file" name="image" id="file" onchange="readURL(this);">
					<img id="image-upload-preview" src="#" alt="">
					<input type="submit" value="Upload and Insert Image" name="submitImage" id="submitImage" class="modal-button">

				</form>
			</div>
		</div>


		<script>
		//ajax for image upload 
			$(document).ready(function() {
				$('#insertImage').on('submit', function(e) {
				
					var formData = new FormData(this);

					$.ajax({
					    url: "./ajax/php/insert_image.php",
					    type: "POST",
					    data: formData,
					    success: function (data) {
						$("#review-text").append(data);
						window.location ='#close';
					    },
					    cache: false,
					    contentType: false,
					    processData: false
					});

					e.preventDefault();

				});

				/*The code below is for if line breaks add <div> tags. 
				It doesn't do this on localhost but on a server it may so uncomment if it does this
		    		$('div[contenteditable]').keydown(function(e) {
				// trap the return key being pressed
					if (e.keyCode === 13) {
				  	// insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
				  	document.execCommand('insertHTML', false, '<br><br>');
				  	// prevent the default behaviour of return key pressed
				  	return false;
				    	}
				});*/
			});
		

		</script>


	<!-- end modal popup -->

	
	<!-- modal popup for videos -->

	<div id="video-modal-popup" class="modaltext">
			<div>
			<a href="#close" class="close" title="Close">X</a>
					You can embed YOUTUBE videos into your post<br><br>
				
					<input type="text" id="youtube-link">
					<!--<input type="submit" class="modal-button" value="insert video" id="insert-video-button">-->
					<div id="insert-video-button" class="modal-button">insert video</div>
				

					<script>
						$(document).ready(function() {
							

							$('#insert-video-button').on('click', function() {	
								var youtubeLink = $('#youtube-link').val();
								//regular expression for youtube link:
								var youtubeLinkRegex = new RegExp("(((https|http):\/\/)|^)(www\.)?youtube\.com\/[\s\S]*");
								//check that the link inserted matches the regular expression for a youtube link
								if(youtubeLink.match(youtubeLinkRegex)) {
									//remove any part of the link excluding the video id
									var youtubeLink = youtubeLink.replace('https://','');
									var youtubeLink = youtubeLink.replace('http://','');
									var youtubeLink = youtubeLink.replace('www.','');
									var youtubeLink = youtubeLink.replace('youtube.com/','');
									var youtubeLink = youtubeLink.replace('watch?v=','');

									//embed the youtube video using the video id
									/*$("#review-text").append('<iframe width="280" height="155" src="https://www.youtube.com/embed/' + youtubeLink + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>');*/
									//BB Code friendly below:
									$("#review-text").append('[youtube]' + youtubeLink + '[/youtube]');
									window.location ='#close';

								};
					
							});
							
						});

					</script>
			
			</div>
		</div>


	<!-- end modal popup -->


<?php
	//not logged in
	} else { echo "<a href='./login'>log in</a> or <a href='./register'>register</a> to write a review<br>";
	}

?>		<div class="reviews-list">
			<ul class="reviews-holder-ul">
				<?php 
					//get the reviews
					$session->getReviews(); 
				?>
			</ul>

		</div>

	</div>

<?php

} else {
echo "this session doesn't exist";

}
//these are the scripts for the ajax involved in the reviews and comments
echo "<script type='text/javascript' src='./ajax/jquery/insert_review.js'></script>
<script type='text/javascript' src='./ajax/jquery/delete_review.js'></script>
<script type='text/javascript' src='./ajax/jquery/review_reply.js'></script>
<script type='text/javascript' src='./ajax/jquery/edit_review.js'></script>
<script type='text/javascript' src='./ajax/jquery/delete_comment.js'></script>
<script type='text/javascript' src='./ajax/jquery/edit_comment.js'></script>
<script type='text/javascript' src='./ajax/jquery/comment_reply.js'></script>
<link rel='stylesheet' href='./css/reviews.css' type='text/css'>";
?>

</div>


<div id="related">
<h4>write a review.</h4>

<p>If logged in, you can make comments, add pictures and embed youtube videos.</p>

<p>This feature is only available for each session for a maximum time of 2 weeks. After that everything is archived.</p>

<p>You can upload and insert pictures but once a picture is uploaded, it is inserted into the gallery. Only duffa pictures from this date are allowed.</p>

<p>Your posts and pictures can be deleted at any time from your account settings</p>

<p>Anything deemed unacceptable (porn, racism, bullying, sexism, homophobic, etc.) will be deleted. You may be issued with a warning and repeat offenders will be banned.</p>

<?php
include "./layout/layout-bottom.php";
?>




<?php 

$banner_text = 'Gallery';
$banner_image = './layout/banner_images/random9.jpg';

include "./layout/layout-top2.php"; ?>

<link rel="stylesheet" href="./css/gallery.css" type="text/css">

<?php
//if the event is indicated in the url then we will get all images from that event

if(isset($_GET['event'])) {

?>
	<style>
		#banner, #desktop-features {
			display: none;
		}


		#content-wrapper {
				margin-top: 60px;

		}

		#pagination a, #pagination a:visited{
			 
			color: black !important;
			
		}

		#pagination {
			padding-top: 10px;
			font-size: 25px;
			text-align: center;
		}
		
	</style>
<?php

//get the event
$gallery_event = $_GET['event'];

//get the page number and offset the images based on the page number
isset($_GET['page']) ? $current_page = $_GET['page'] : $current_page = 1;

$prev_page = $current_page - 1;
$next_page = $current_page + 1;

//limit 18 images per page
$limit = 18;

$offset = ($limit * $prev_page);

	//Get the total number of pictures in a particular gallery
	$db = new Database();
	$stmt = $db->prepare("SELECT * FROM gallery where event=?");
	$stmt->bindParam(1,$gallery_event);
	$stmt->execute();
	$total_images = $stmt->rowCount();

	//Select 18 images from that gallery from the particular selected page
	$db = new Database();
	$stmt = $db->prepare("SELECT * FROM gallery where event=? ORDER BY imageID DESC LIMIT 18 OFFSET ?");
	$stmt->bindParam(1,$gallery_event);
	$stmt->bindParam(2,$offset);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
		echo "<div id='event-gallery-container'>";
		foreach($result as $image) {
			echo "<img class = 'galleryImage click-for-larger' id='" . $image->ImageID . "' src='" . $image->image . "'>";
		}

		echo "</div>";

		//display the page number and the options for prev/next pages

		echo "<div id='pagination'>";
			if($prev_page != 0) {
				echo "<a href=?event=" . $_GET['event'] . "&page=" . $prev_page . ">prev </a>"; 
			}

			echo "Page " . $current_page;

			if($total_images > ($limit * $current_page)) {
				echo "<a href=?event=" . $_GET['event'] . "&page=" . $next_page . "> next </a>";
			}

		echo "</div>";

} else {

//if the url contains no event then we will get a sample from each event

?>
<div>
	


		<?php


	$db = new Database();
	$stmt = $db->prepare("SELECT * FROM gallery WHERE event='duffa' ORDER BY imageID DESC LIMIT 1");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
	echo "<div id='duffa-sample-container' class='sample-gallery-container'>";
		echo "<div class='event-header'>Duffa Gallery</div>";
		echo "<div class='sample-image-text-container'>";
			foreach($result as $image) {
				echo "<div class='sample-image-container'><a href='?event=" . $image->event . "'><div class = 'sample' id='sample" . $image->ImageID . "' style='background:url(" . $image->image . "); background-repeat: no-repeat; 
	background-size: cover; background-position: center center;'></div></a></div>";
			}

			echo "<div class='duffa-gallery-summary'>A collection of galleries<br><span class='click-for-more-duffa'><a href='?event=duffa'>click to view</a></span></div>
		</div>
	</div> ";
	
	
	$db = new Database();
	$stmt = $db->prepare("SELECT * FROM gallery WHERE event='user' ORDER BY imageID DESC LIMIT 1");
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_OBJ); 
	echo "<div id='user-sample-container' class='sample-gallery-container'>";
		echo "<div class='event-header' style='margin-top:-10px'>User Submissions</div>";
		echo "<div class='sample-image-text-container'>";
			foreach($result as $image) {
				echo "<div class='sample-image-container'><a href='?event=" . $image->event . "'><div class = 'sample' id='sample" . $image->ImageID . "' style='background:url(" . $image->image . "); background-repeat: no-repeat; 
	background-size: cover; background-position: center center;'></div></a></div>";
			}

			echo "<div class='user-gallery-summary'>your uploaded pictures<br><span class='click-for-more-user'><a href='?event=user'>click to view</a></span></div> 
		</div>
	</div> 


</div> ";   

}




include "./layout/layout-bottom.php";
?>

<!-- modal popup -->
<div id="imageModal" class="modal">
<span class="close">&times;</span>
	<div id="modal-content-wrapper">
	<a class="prev">&#10094;</a>
	<img class="modal-content" id="img01">

	<a class="next">&#10095;</a>
	</div>
</div>


<script>
//Jquery for the selecting images and then selecting the next/prev within the modal
$(document).ready(function(){

	var modalImg = $('#img01');
	
	$('.click-for-larger').each(function() {

		var imgSrc = $(this).attr('src');
		var imgID = $(this).attr('id');
		$(this).on('click', function(e){
			$('.modal').css('display','block');
			modalImg.attr('src', imgSrc);
			modalImg.attr('imgID', imgID);
			e.stopPropagation();
		});
	});

	$('body').on('click', function(e) {
			$('.modal').css('display','none');

	});

	$('.modal-content, .prev, .next').on('click', function(e) {
		e.stopPropagation();
	});


	$('.close').on('click', function() {
		$('.modal').css('display','none');
	});

	
	$('.next').on('click', function() {
		var imgID = $('.modal-content').attr('imgID');
		var nextSlide = $('#'+imgID).next();
		var nextImgSrc = nextSlide.attr('src');
		var nextImgID = nextSlide.attr('id');
		$('.modal-content').attr('src', nextImgSrc);
		$('.modal-content').attr('imgID', nextImgID);
	});

	$('.prev').on('click', function() {
		var imgID = $('.modal-content').attr('imgID');
		var prevSlide = $('#'+imgID).prev();
		var prevImgSrc = prevSlide.attr('src');
		var prevImgID = prevSlide.attr('id');
		$('.modal-content').attr('src', prevImgSrc);
		$('.modal-content').attr('imgID', prevImgID);
	});



	$('.modal-content').on('swiperight', function() {
		var imgID = $(this).attr('imgID');
		var prevSlide = $('#'+imgID).prev();
		var prevImgSrc = prevSlide.attr('src');
		var prevImgID = prevSlide.attr('id');
		$(this).attr('src', prevImgSrc);
		$(this).attr('imgID', prevImgID);

	});

	$('.modal-content').on('swipeleft', function() {
		var imgID = $(this).attr('imgID');
		var nextSlide = $('#'+imgID).next();
		var nextImgSrc = nextSlide.attr('src');
		var nextImgID = nextSlide.attr('id');
		$(this).attr('src', nextImgSrc);
		$(this).attr('imgID', nextImgID);

	});

//prevent image from dragging

$('img').on('dragstart', function(event) { event.preventDefault(); });


//change the height of the sample image in proportion to its width

	function sampleHeight() {
		$('.sample').each(function() {
			var w = $(this).width();
			$(this).height(w*0.5);
			sampleHeightContainer();
		});

	}

	function sampleHeightContainer() {

		$('.sample-image-container').each(function() {
			var w = $(this).width();
			$(this).height(w*0.5);
		});

		
	}

	sampleHeight();

	$(window).resize(function() {
		sampleHeight();
	});


});
</script>

<?php 
$banner_text = 'News';
$banner_image = './layout/banner_images/random.jpg';
include './layout/layout-top2.php';


?> 
 <link href="https://fonts.googleapis.com/css?family=Catamaran:400" rel="stylesheet"> 
<style>
/*style a red theme*/
#title, #desktop-nav a, .hamburger, .menu li {
	color: #c52c2c !important;
}

</style>

<!-- script to make the 'user icon' a red colour to fit the nav theme-->
<script>
	$(document).ready(function() {
		$('.usericon-desktop').attr('src','./icons/user-icon-desktop-red.png');

	});
</script>


<!-- facebook plugin script -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.11';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<!-- content goes here -->


<?php

//Section for viewing articles

if(isset($_GET['article_id'])) {

	$articleid = $_GET['article_id'];

	$news = new News($articleid);
	$SubmitterID = $news->SubmitterID;
	$submitter = new Users();
	$submitter->userID($SubmitterID);
	$submitter_name = $submitter->FirstName . " " . $submitter->LastName;
	$submitter_username = $submitter->Username;
	echo "<h1 class='news-header'>" . $news->description . "</h1> written by " . $submitter_name . " <a href=\"./user_profile?username=" . $submitter_username . "\">(@" . $submitter_username . ")</a><br><br>";
	echo "<div id='news-article-body' style='width:80%; margin: 0 auto'>";
	echo $news->article;
	echo "</div>";

?>
	<link rel="stylesheet" href="./css/news_article.css" type="text/css">

	<style>
		<?php if($_SESSION['LoggedIn'] != 1) {
			echo " .delete-btn, .edit-btn { visibility: hidden; } ";
		}
		?>
	</style>


		</div>
		<div id="related">
			<h2 class="news-headers">comments</h2>

			<div class="post-comment-container">
				<?php if ($_SESSION['LoggedIn'] != 1){ echo "Name <input type=\"text\" id=\"commentor-name\" class=\"box1\"><br>";}
					else { echo "<input type='hidden' id='commentor-name' value='null'>";} ?>
			<div name="comment-text" id="comment-text" contentEditable="true"></div>
			<input type="hidden" id="userid" value="<?php echo $_SESSION['userid']?>">
			<input type="hidden" id="articleid" value="<?php echo $articleid?>">
		
		
				<div id="comment-btn" class="black-button">make comment</div>
		
			</div>

			<div class="comments-list">
				<ul class="comments-holder-ul">
					<?php 
						//get the comments
						$news->getComments(); 
					?>
				</ul>

			</div>
		<script src="./ajax/jquery/news_comment.js"></script>
		<script src="./jquery/contenteditable_no_div_tags.js"></script>

		<script>

			$(window).load(function() {

				relatedHeightToggle();

				$(window).on('resize', function() {

					relatedHeightToggle();

				});

				function relatedHeightToggle() {

					if($(window).width() > 1001) {
						$("#related").height($("#content").height());
					} else {
						$("#related").css('height', '');
					}
				
				}
			});
		</script>

<?php

} else {

//section for viewing the front news page

?>

<script>
$(document).ready(function() {

	//jquery for calculating the latest news image sizes

	imageHeight();

	$(window).on('resize', function() {

		imageHeight();

	});

	function imageHeight() {
		var contwidth = $('#content').width();
		var idealheight = (contwidth / 100) * 75;
		$('.image1, .image2, .image3').css('height', idealheight);
		$('#content').height(idealheight + 40);

	}


});

</script>


	<link rel="stylesheet" href="./css/news_frontpage.css" type="text/css">

	<?php
	//get latest news
	$db = new Database();
	$stmt = $db->prepare("SELECT ArticleID FROM news ORDER BY ArticleID DESC LIMIT 1");
	$stmt->execute();
	$row = $stmt->fetch();
	$latest = $row['ArticleID'];

	$latest_article = new News($latest);
	$latest_article_title = $latest_article->title;
	$latest_article_description = $latest_article->description;
	$latest_article_image = $latest_article->image;

	$second_latest_article = new News($latest - 1);
	$second_latest_article_title = $second_latest_article->title;
	$second_latest_article_description = $second_latest_article->description;
	$second_latest_article_image = $second_latest_article->image;

	$third_latest_article = new News($latest - 2);
	$third_latest_article_title = $third_latest_article->title;
	$third_latest_article_description = $third_latest_article->description;
	$third_latest_article_image = $third_latest_article->image;
	?>

	<div id="recent-news-titles">
	<span class="news-title1">Latest • </span>
	<span class="news-title2"><?php echo $second_latest_article_title; ?> • </span>
	<span class="news-title3"><?php echo $third_latest_article_title; ?></span>
	</div>

	<div id="slideshow">
		<div class="active image1 start-news">
		    <img src="<?php echo $latest_article_image; ?>">
			<div class="news-text"><a href="./news?article_id=<?php echo ($latest)?>"> <?php echo $latest_article_description; ?></a></div>
		</div>

		<div class="image2">
		    <img src="<?php echo $second_latest_article_image; ?>">
			<div class="news-text"><a href="./news?article_id=<?php echo ($latest - 1)?>"> <?php echo $second_latest_article_description; ?></a></div>
		</div>

		<div class="image3 end-news">
		    <img src="<?php echo $third_latest_article_image; ?>">
			<div class="news-text"><a href="./news?article_id=<?php echo ($latest - 2)?>"> <?php echo $third_latest_article_description; ?></a></div>
		</div>
	</div>

	<script>
	$(document).ready(function() {


		var $active = $('div#slideshow div');
		var $next = $active.next();
		var $prev = $active.prev();

		$('.news-title1').click(function() {
			$active.removeClass('active');
			$('.image1').addClass('active');
			$('.image1').addClass('fadeInLeft');
		});

		$('.news-title2').on('click', function() {
			$active.removeClass('active');
			$('.image2').addClass('active');
			$('.image2').addClass('fadeInLeft');
		});

		$('.news-title3').on('click', function() {
			$active.removeClass('active');
			$('.image3').addClass('active');
			$('.image3').addClass('fadeInLeft');
		});

		$('#slideshow').on('swipeleft', function(e) {
			if( $('.active').hasClass('end-news')) {
				return false;
			} else {
			var $next = $('.active').next();
			$('.active').removeClass('active')
			$next.addClass('active');
			$next.addClass('fadeInLeft');
			}

		});

		$('#slideshow').on('swiperight', function(e) {
			if( $('.active').hasClass('start-news')) {
				return false;
			} else {
			var $prev = $('.active').prev();
			$('.active').removeClass('active')
			$prev.addClass('active');
			$prev.addClass('fadeInLeft');
			}

		});

		//prevent image from dragging

		$('img').on('dragstart', function(event) { event.preventDefault(); });

	});

	</script>


				</div>
				<div id="related">
					<div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="timeline" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/2981635593" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/2981635593">Tracking protection is enabled. Click here for the Duffa Facebook Page</a></blockquote>

</div>

				</div>

<?php


}


?>


<?php include './layout/layout-bottom.php'; ?>

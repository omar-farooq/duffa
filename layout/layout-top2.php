<?php include_once "./php/config.php"; ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Ultimate Frisbee in Manchester for everyone; Free sports in Manchester, everyone is welcome">
	<meta name="keywords" content="Ultimate, Frisbee, Didsbury, Manchester, Fog Lane Park, Free, Amateurs, Beginner, Intermediate, Expert, Fun, Sports, Running, Pickup, Outdoors, Indoors, clubs, activities, men, women, boys, girls, M20">
	<title>Duffa &bull; <?php echo $banner_text ?> &bull; Didsbury Ultimate Frisbee for Amateurs</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="./jquery/nav.js"></script>
	<link rel="stylesheet" href="./css/desktop_layout.css" type="text/css">
	<link rel="stylesheet" href="./css/mobile_layout.css" type="text/css">
	<link rel="stylesheet" href="./css/content.css" type="text/css">
	<link rel="stylesheet" href="./css/fonts.css" type="text/css">

	<link href="./icons/favicon.ico" rel="shortcut icon" type="image/x-icon">
	
	<script type='text/javascript' src='./jquery/icon_hover.js'></script>
	<script type='text/javascript' src='./jquery/swipe.js'></script>
	<script type='text/javascript' src='./jquery/content_full_length.js'></script>

<?php if (isset($banner_image2)) { ?>
	<script type='text/javascript' src='./jquery/banner_slideshow.js'></script>

<?php } ?>

	<script type='text/javascript'>
		//jquery to toggle the desktop user icon
		$(document).ready(function() {
			$('.usericon-desktop').click(function() {
				$('#user-nav').slideToggle();
			});

		//keep the content above the footer always
			function contentOverFooter() {
				var FH = $('#footer').outerHeight();
				$("#content-wrapper").css('margin-bottom', FH - 5);
			}

			contentOverFooter();
			$(window).resize(function() {
				contentOverFooter();
			});
		});
	</script>

</head>


<body>
	<div id="wrapper">

		<div id="header">

			<!--for desktop computers and larger devices -->

			<a href="./"><span id='title'>
			Duffa.org
			</span></a>
			<span id='desktop-nav'>
			<a href="./timetable">schedule</a>
			<a href="./news">news</a>
			<a href="./shop">shop</a>
			<?php if($_SESSION['LoggedIn'] == '1') { echo "<a href=\"./account_settings\">account</a>"; } else { echo "<a href=\"./register\">join</a>"; }  ?> 
			<span class="hamburger">&#9776;</span>
			<img src="./icons/user-icon-desktop.png" class="usericon-desktop">

			</span>
			



			<!-- for mobiles -->
			
			<span class="mobile-hamburger hamburger mobile">&#9776</span>
	
			<!-- end mobile -->

		</div>

		<!-- drop down menu -->

		<div class="menu">
			<ul class="second-list mobile">
			<a href="./timetable"><li>schedule</li></a>
			<a href="./news"><li>news</li></a>
			<a href="./shop"><li>shop</li></a>
			<?php if($_SESSION['LoggedIn'] == '1') { echo "<a href=\"./account_settings\"><li>account</li></a>"; } else { echo "<a href=\"./register\"><li>join</li></a>"; }  ?> 
			<?php if($_SESSION['LoggedIn'] == '1') { echo "<a href=\"./logout\"><li>logout</li></a>"; } else { echo "<a href=\"./login\"><li>login</li></a>"; }  ?> 
			</ul>
			<ul class="first-list">
			<a href="./image_gallery"><li>gallery</li></a>
			<a href="./users_list"><li>users</li></a>
			<a href="#"><li>hat</li></a>
			<a href="#"><li>AGM</li></a>
			<a href="#"><li>social media</li></a>

			
			<?php 
			//offer a chance to log in on a desktop menu
			//this doesn't appear on this list when on a mobile layout but appears on the above list instead
			if($_SESSION['LoggedIn'] == '1') { echo "<a href=\"./logout\" class=\"desktop\"><li>logout</li></a>"; } else { echo "<a href=\"./login\" class=\"desktop\"><li>login</li></a>"; }  
			?> 
			</ul>



		</div>


		<!-- the banner and logged in status for desktop computers -->

		<div id="desktop-features">

			<!--banner slideshow -->
			<div id="slideshow-container">

				<div id="banner" class="banner-slides fade"><h1 id="banner_text"><?php echo $banner_text; ?></h1>
				</div>

				<?php if (isset($banner_image2)) {
					echo "<div id=\"banner2\" class=\"banner-slides fade\"><div id=\"banner_text2\">" . $banner_text2 . "</div></div>";
				} ?>

				<?php if (isset($banner_image2)) {?>
				<div id="dot-holder" style="text-align:center">
				  <span class="dot" dotValue='1'></span>
				  <span class="dot" dotValue='2'></span> <?php 
				  if (isset($banner_image3)) {?><span class="dot" dotValue='3'></span> <?php } ?>
				</div>
				<?php } ?>
			</div>

			<!-- end slideshow -->

			
			<div id="user-nav">
				<!--login form or login details -->
				<?php if($_SESSION['LoggedIn'] == 1) {
						echo "logged in as " . $_SESSION['Username']  . " <a href='./logout'> | logout | </a>";
						echo "<a href=\"./user_profile?userid=" . $_SESSION['userid'] . "\"> My Profile</a> | ";
						$messages = new Messages();
						$numberUnseenMessages = $messages->numberOfUnseen();
						$anyUnseenMessages = ($numberUnseenMessages == '0' ? 'messages' : 'messages-unread');
						echo "<a href='./messages'><img src = './icons/" . $anyUnseenMessages . ".png' class='messages-icon'></a>";
					} else { ?>
						<form method="post" action="" name="login" id="login">
						username<input type='text' class='header-log-user' id='username' name='username'> password<input type='password' class='header-log-pass' id='password' name='password'> <input type="submit" name="login" id="login" value="Login"> <input type="checkbox" id="remember" name="remember">  remember me </form>
				<?php	}

					//get info from the form and run the login function
					if(!empty($_POST['username']) && !empty($_POST['password'])) {
						$username = $_POST['username'];
						$password = $_POST['password'];
						$remember = $_POST['remember'];


						$account = new Account();

						if($remember == 'on') {
							$account->loginAndRemember($username, $password);
						} else {
							$account->login($username, $password);
						}
					}
				 ?>
			</div>

		</div>


		<div id="content-wrapper">
			
			<div id="content">

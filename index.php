<?php include_once "./php/config.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Ultimate Frisbee in Manchester for everyone; Free sports in Manchester, everyone is welcome">
	<meta name="keywords" content="Ultimate, Frisbee, Didsbury, Manchester, Fog Lane Park, Free, Amateurs, Beginner, Intermediate, Expert, Fun, Sports, Running, Pickup, Outdoors, clubs, activities, men, women, boys, girls, M20">
	<meta name="author" content="Omar Farooq">
	<title>DUFFA &bull; Didsbury Ultimate Frisbee for Amateurs</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<link rel="stylesheet" href="./css/fonts.css" type="text/css">
	<link href="./icons/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<script type='text/javascript' src='./jquery/about_toggle.js'></script>
	<script type='text/javascript' src='./jquery/nav.js'></script>
	<script type='text/javascript' src='./jquery/icon_hover.js'></script>
	<link rel="stylesheet" href="./css/index.css" id="index-css">
<style>


</style>
</head>

<body>

<div id='main'>
	<div id='main-container'>
		<div id='header'>
			<span id='title'>
			Duffa.org
			</span>
			<span id='nav'>
			<a href="./timetable">schedule</a>
			<a href="./news">news</a>
			<a href="./shop">shop</a>
			<?php if($_SESSION['LoggedIn'] == '1') { echo "<a href=\"./account_settings\">account</a>"; } else { echo "<a href=\"./register\">join</a>"; }  ?> 
			<span class="hamburger">&#9776</span>
			</span>
			<span class="mobile-hamburger hamburger mobile">&#9776</span>


		</div>

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
			<a href="#"><li>links</li></a>
			</ul>

		</div>

		<div id='content-container'>
			<div id='duffa'>
				<span class="d">DIDSBURY</span> <br>
				<span class="u"> ULTIMATE</span><span class="f"> FRISBEE</span> <br>
				<span class="for"> FOR</span> <span class="a"> AMATEURS</span>
			</div>

			<div id='info'>
			<h1>Amateur Frisbee</h1><br>
			Sundays and Thursdays<br>
			<br>
			Important News: populate with text<br>
			texttexttexttexttexttexttexttext<br>
			<br>
			Duffa is powered by virgin etc. to get your ad here, sponsor us. for perks, click here.


			</div>

			<div class ="about-btn">
			about
			</div>

		</div>
	
		<div id='additional-info'>
			<img src="./icons/contact.png" class="index-icon">
			<img src="./icons/fb2-grey.png" class="index-icon fb" data-alt-src="./icons/fb2.png">
			<img src="./icons/twitter-grey.png" class="index-icon twitter" data-alt-src="./icons/twitter.png">
		</div>

		<div id='about'>
			<?php include "./about.php"; ?>
		</div>

	</div>
</div>


</body>
</html>
<!-- Omar Farooq 2018 -->

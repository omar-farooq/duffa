<?php
$banner_image = './layout/banner_images/duffa-birthday-10.jpeg'; $banner_text = 'Schedule';
$banner_image2 = './layout/banner_images/random11.jpg';

include "./layout/layout-top2.php";
?>

<link rel="stylesheet" href="./css/timetable.css" type="text/css">
 <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> 

	<!-- table for the upcoming schedule -->
	<center><table class="timetable_table"><tr><td>Date</td><td>Time</td><td>Info</td></tr> <?php 
	$schedule = new Schedule();
	$schedule->currentTimetable();
	echo "</table></center>";

	echo "<br><br> <a href='session_archive.php'>archive</a>";

	//to cancel or uncancel a session
	if($_GET['action'] == (cancel || uncancel) && $_SESSION['user_level'] > 0) {
		$sid = $_GET['sessionid'];

		$session = new Session($sid);
		$session->cancelSession();
	} ?>

	
		<br><br>We play no matter the weather!<br>
		<img src="./layout/sunshine.jpeg" class="all-weather-pic">
	

</div>
<div id="related">
	<h3>Notes:</h3>

	Want to play pickup frisbee for free with other people of similar skill?<br> Click here for more info about trying<br><br>

	<b>Indoors not currently on</b><br>

	<!-- Google map for fog lane park -->
	<div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="330" id="gmap_canvas" src="https://maps.google.com/maps?q=fog lane park&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></div></div>

	<br><div id="click-for-map">
		<span class='click-for-pw'>click here for a map to Parrs Wood</span>
		<span class='click-for-fog-lane' style='display:none'>click for a map of fog lane</span>
	</div><br>

	Outdoors is free<br>
	remember to bring £4 for indoors<br>
	Outdoors location is Fog Lane Park<br>
	Indoors is held at Parrs Wood High School<br>

</div>

<!-- script to toggle the fog lane park/parrs wood maps -->
<script type='text/javascript' src='./jquery/select_map.js'></script>


<?php
include "./layout/layout-bottom.php";
?>

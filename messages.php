<?php 
$banner_text = "Messages";
$banner_image = './layout/banner_images/post.jpg';
include "./layout/layout-top2.php";
?>
<link rel="stylesheet" href="./css/messages.css" type="text/css">
<title>Messages</title>

<?php
if($_SESSION['LoggedIn'] != 1) {
	echo "no account detected";
	include "./layout/layout-bottom.php";
	exit;
	
}


echo "<h1>Your Messages</h1>";
$messages = new Messages();
$messages->getAll();


include "./layout/layout-bottom.php";
?>

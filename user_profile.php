<?php
$banner_text = "Profiles";
include "./layout/layout-top2.php";
?>

<link rel="stylesheet" href="./css/user_profile.css" type="text/css">

<?php

if(isset($_GET['userid'])){
	$user = $_GET['userid'];

} 

if(isset($_GET['username'])) {
	$user = $_GET['username'];
} 

$profile = new Users($user);


$profile_pic = $profile->Profile_Pic;
$datereg = strtotime($profile->DateRegistered);
$datereg = date("d-m-y", $datereg);
$userid = $profile->UserID;
$username = $profile->Username;
$firstname = $profile->FirstName;
$lastname = $profile->LastName;
$about = $profile->about;
$about = htmlspecialchars($about);
$bbparse = new bbParser();
$about = $bbparse->getHtml($about);

echo "<br><img src='$profile_pic' id='profile-photo'> <br><br>
<div id='user-profile'>username: " . $username . 
"<br> Date Registered: "  . $datereg  . 
"<br> Name: " . $firstname . " " . $lastname . 
"<br><br> About: <br> " . $about . "</div><br><br>" ; 


//if logged in then allow to send a message
if ($_SESSION['LoggedIn'] == 1) {
	echo "<div id='expand-send-message-form'><b>Send a message</b></div>";

	//form to send a message
	echo "<div id='send-message'>
			<form method='post' action='' id='message-form' name='message-form'>
			<textarea name='comment-text' id='comment-text' rows='10' required minlength='2' maxlength='10000'></textarea>
			<br><input type='submit' value='message' name='message-btn' id='message-btn' class='red-button'>
			</form> 
		</div>";
} else {
	//if not logged in then offer links to log in/register
	echo "<a href='./login'>Log in</a> or <a href='./register'>Register</a> to send this person a message";
}

//once the 'send comment' button has been sent, send the message
if(isset($_POST['message-btn'])) {
	$message_body= $_POST['comment-text'];
	$sender = $_SESSION['Username'];
	$senderID = $_SESSION['userid'];
	$message = new Messages();
	$message->create($username, $message_body);

}
?>

</div>

<div id="related">
	<h4>Achievements</h4>

	<p>posts: <?php echo $profile->postsCount; ?></p>

	<p>reviews: <?php echo $profile->reviewsCount; ?></p>

	<p>photos: <?php echo $profile->imagesCount; ?></p>

	<p>awards:</p>

<!-- Script to toggle the send message form -->
<script type='text/javascript' src='./jquery/send_message_toggle.js'></script>

<?php

include "./layout/layout-bottom.php";
?>

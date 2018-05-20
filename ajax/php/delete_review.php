<?php
include "../../php/config.php";
if ($_POST['task'] == 'review_delete') {
$userid = $_POST['user_id'];
$reviewid = $_POST['review_id'];

	if($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0){
	$review = new Review($reviewid);
	$review->delete();
	}
}
?>

<?php
include "../../php/config.php";
if ($_POST['task'] == 'review_edit') {
$userid = $_POST['user_id'];
$reviewid = $_POST['review_id'];
$text = $_POST['text'];

	if($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0){
		$review = new Review($reviewid);
		$review->edit($text);
	}
}

if(isset($_GET['fromReviewHolderid'])) {
	$reviewid = substr($_GET['fromReviewHolderid'], 1);
	$thisReview = new Review($reviewid);
	echo $thisReview->Review;

}
?>

<?php
include "../../php/config.php";
if ($_POST['task'] == 'comment_edit') {
$userid = $_POST['user_id'];
$commentid = $_POST['comment_id'];
$text = $_POST['text'];

	if($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0){
		$comment = new Comment($commentid);
		$comment->edit($text);
	}
}

if(isset($_GET['fromCommentid'])) {
	$commentid = substr($_GET['fromCommentid'], 1);
	$thisComment = new Comment($commentid);
	echo $thisComment->Comment;
}

if(isset($_GET['fromCommentHolderid'])) {
	$commentid = substr($_GET['fromCommentHolderid'], 4);
	$thisComment = new Comment($commentid);
	echo $thisComment->Comment;
}
?>

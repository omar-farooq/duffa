<?php
include "../../php/config.php";
if ($_POST['task'] == 'comment_delete') {
$userid = $_POST['user_id'];
$commentid = $_POST['comment_id'];

	if($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0){
	$comment = new Comment($commentid);
	$comment->delete();
	}
}
?>

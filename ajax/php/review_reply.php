<?php
include "../../php/config.php";
if ($_POST['task'] == 'review_reply') {

$userid = (int)$_SESSION['userid'];
$reviewid = (int)$_POST['review_id'];
$comment = str_replace("<br>" , "\n", $_POST['text']);
$account = new Account();
$account->info();
$user_name = $account->FirstName;
$profilepic = $account->Profile_Pic;
$username = $account->Username;

//for "@tweeting"people
/*$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '<a href=./users/$1>@$1</a>',
                      $comment);*/

//for bb code:
$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '[url href=./users/$1]@$1[/url]',
                      $comment);

$tweetcontent = addslashes( str_replace("<br>", "\n", $_POST['text']));
$account->tweet($tweetcontent, $username);

//end tweet script

//insert everything into the database and return the results to the page via jquery

$review = new Review($reviewid);
$commentid = $review->reply($userid, $comment);

$std = new stdClass();
$std->userid = $userid;
$std->comment = $comment;
$std->commentid = $commentid;
$std->reviewid = $reviewid;
$std->username = $user_name;
$std->profilepic = $profilepic;

echo json_encode($std);
}

?>

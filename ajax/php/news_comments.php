<?php
include "../../php/config.php";
if ($_POST['task'] == 'insert_comment') {

$articleid = (int)$_POST['articleid'];

$comment = str_replace("<br>" , "\n", $_POST['text']);


/*$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '<a href=./users/$1>@$1</a>',
                      $comment);*/

//for bb code:
$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '[url href=./users/$1]@$1[/url]',
                      $comment);


if($_SESSION['LoggedIn'] == 1) {

$account = new Account();
$account->info();
$user_name = $account->Username;
$profilepic = $account->Profile_Pic;
$userid = $account->UserID;

//for "@tweeting"people
$tweetcontent = addslashes( str_replace("<br>", "\n", $_POST['text']));
$account->tweet($tweetcontent, $user_name);

//end tweet script
} else {
$user_name = $_POST['name'];
$profilepic = "./user/profile_pics/default.jpg";
$userid = 0;
}



//insert everything into the database and return the results to the page via jquery

$news = new News($articleid);
$commentid = $news->insertComment($comment, $user_name);

$std = new stdClass();
$std->userid = $userid;
$std->comment = $comment;
$std->commentid = $commentid;
$std->username = $user_name;
$std->profilepic = $profilepic;

echo json_encode($std);
}

?>

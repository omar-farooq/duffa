<?php
include "../../php/config.php";
if ($_POST['task'] == 'comment_reply') {

$userid = (int)$_SESSION['userid'];
$parent_commentid = (int)$_POST['parent_comment_id'];
$parent_userid = (int)$_POST['parent_user_id'];
$users = new Users();
$users->userID($parent_userid);

$parent_name = $users->FirstName;
//$jqcomment = str_replace( "\n", "<br>", $_POST['text']);
$comment = str_replace("<br>" , "\n", $_POST['text']);


$account = new Account();
$account->info();
$firstname = $account->FirstName;
$profilepic = $account->Profile_Pic;
$username = $account->Username;

$parent_comment = new Comment($parent_commentid);
$parent_reviewid = $parent_comment->ReviewID;

//for "@tweeting"people

/*
$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '<a href=./users/$1>@$1</a>',
                      $comment);*/
//for bb code:
$comment = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '[url href=./users/$1]@$1[/url]',
                      $comment);


//$tweetcontent = addslashes( str_replace( "\n", "<br>", $_POST['text']));
$tweetcontent = addslashes( str_replace("<br>", "\n", $_POST['text']));
$account->tweet($tweetcontent, $username);

//end tweet script

//insert everything into the database and return the results to the page via jquery

$commentid = $parent_comment->reply($userid, $comment, $parent_reviewid);

$std = new stdClass();
$std->userid = $userid;
$std->comment = $comment;
$std->commentid = $commentid;
$std->parentcommentid = $parent_commentid;
$std->parentreviewid = $parent_reviewid;
$std->parentname = $parent_name;
$std->firstname = $firstname;
$std->profilepic = $profilepic;

echo json_encode($std);
}

?>

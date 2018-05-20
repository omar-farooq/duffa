<?php
include "../../php/config.php";
if ($_POST['task'] == 'insert_review') {

$userid = (int)$_POST['userid'];
$sessionid = (int)$_POST['sessionid'];

$review = str_replace( "\n", "<br>", $_POST['text']);
//$review = str_replace( "<br>", "\n", $_POST['text']);

$account = new Account();
$account->info();
$user_name = $account->FirstName;
$profilepic = $account->Profile_Pic;
$username = $account->Username;

//for "@tweeting"people
/*
$review = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '<a href=./users/$1>@$1</a>',
                      $review);*/
//the above made friendly for BB Code
$review = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i',
                      '[url href=./users/$1]@$1[/url]',
                      $review);

$tweetcontent = str_replace( "<br>", "\n", $_POST['text']);
$account->tweet($tweetcontent, $username);

//end tweet script

//insert everything into the database and return the results to the page via jquery

$session = new Session($sessionid);
$reviewid = $session->insertReview($userid, $review);

$std = new stdClass();
$std->userid = $userid;
$std->review = $review;
$std->reviewid = $reviewid;
$std->username = $user_name;
$std->profilepic = $profilepic;

echo json_encode($std);
}

?>

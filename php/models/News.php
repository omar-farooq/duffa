<?php
Class News {
	public $ArticleID, $SubmitterID, $title, $description, $image, $article, $upload_time;
	private $db;

	public function __construct($ArticleID) {
		$this->db = new Database();

		$stmt=$this->db->prepare ("SELECT * FROM news WHERE ArticleID = ?");
		$stmt->bindParam(1, $ArticleID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'News');
		$result = $stmt->fetch(PDO::FETCH_CLASS);

		foreach($result as $key => $value) {
			$this->$key = $value;
		}

	}

	public function insertComment($comment, $name) {
		$db = $this->db;
		$ArticleID = $this->ArticleID;
		if(isset($_SESSION['userid'])) {
			$userid = $_SESSION['userid'];

			$stmt=$db->prepare("INSERT INTO comments (UserID, ArticleID, Comment, CommentTime) VALUES (?,?,?,CURRENT_TIMESTAMP)");
			$stmt->bindParam(1,$userid);
			$stmt->bindParam(2,$ArticleID);
			$stmt->bindParam(3,$comment);
			$stmt->execute();

			
		} else {
			$stmt=$db->prepare("INSERT INTO comments (ArticleID, Comment, CommentorName, CommentTime) VALUES (?,?,?,CURRENT_TIMESTAMP)");
			$stmt->bindParam(1,$ArticleID);
			$stmt->bindParam(2,$comment);
			$stmt->bindParam(3,$name);
			$stmt->execute();
		}

		return $lastid = $db->lastInsertId();
		

	}

	public function getComments() {
		$db = $this->db;
		$ArticleID = $this->ArticleID;

		$stmt=$db->prepare("SELECT * FROM comments WHERE ArticleID = ?");
		$stmt->bindParam(1, $ArticleID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
		$comments = $stmt->fetchAll(PDO::FETCH_CLASS);
		foreach($comments as $comment) {
			$userid = $comment->UserID;

			if($userid > 0) {
				$profile = new Users();
				$profile->userID($userid);
				$user_name = $profile->FirstName;
				$profilepic = $profile->Profile_Pic; 
			} else {
				$userid = "0";
				$user_name = $comment->CommentorName;
				$profilepic = "./user/profile_pics/default.jpg";
			}
			$commentid = $comment->CommentID;
			$commenttext = htmlspecialchars($comment->Comment);
			$bbparse = new bbParser();
			$commenttext = $bbparse->getHtml($commenttext);
			//$commenttext = nl2br($comment->Comment);

			echo "<li class=\"comment-holder\" id=\"_" . $commentid . "\">
				<div class=\"comment-top-wrapper\">
				<div class=\"user-image\">
				<img src=\"" . $profilepic . "\" class=\"user-image-pic\">
				</div>
				<div class=\"comment-body\">
				<input type=\"hidden\" id=\"comments-userid" . $commentid . "\" value=\"" . $userid . "\">
				<h3 class=\"username-field\">" . $user_name . "</h3>
				<div class=\"comment-text\">" . $commenttext . "</div>
				</div>
				</div>
				<div class=\"comment-buttons-holder\">
				<ul>
				<li id=\"" . $commentid . "\" class=\"delete-btn\"";
				if(!($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0)) { echo " style=\"visibility:hidden\" ";}
				echo ">delete </li>
				<li id=\"" . $commentid . "\" class=\"edit-btn\"";
				if(!($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0)) { echo " style=\"visibility:hidden\" ";}
				echo ">edit</li>
				</ul>
				</div>
				</li>";


		}
	
	}

}

?>

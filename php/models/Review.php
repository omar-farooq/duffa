<?php
Class Review {

	public $ReviewID, $SessionID, $UserID, $Review, $ReviewTime;
	protected $db;

	public function __construct($ReviewID) {
		$this->db = new Database();
		$stmt = $this->db->prepare("SELECT * FROM reviews WHERE ReviewID = ?");
		$stmt->bindParam(1, $ReviewID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Review');
		$result = $stmt->fetch(PDO::FETCH_CLASS); 
		foreach($result as $key => $value) {
			$this->$key = $value;
		}
	}

	public function edit($text) { 
			$db = $this->db;
			$reviewid = $this->ReviewID;
			$stmt=$db->prepare("UPDATE reviews SET Review = ? WHERE ReviewID = ?");
			$stmt->bindParam(1,$text);
			$stmt->bindParam(2,$reviewid);
			$stmt->execute();
	}

	public function delete() { 
			$db = $this->db;
			$reviewid = $this->ReviewID;
			$stmt=$db->prepare("DELETE FROM reviews WHERE ReviewID = ?");
			$stmt->bindParam(1,$reviewid);
			$stmt->execute();
	}

	public function reply($userid, $comment) { 
		$reviewid = $this->ReviewID;
		$db = $this->db;
		$stmt=$db->prepare("INSERT INTO comments (UserID, ReviewID, Comment, CommentTime) VALUES (?,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1,$userid);
		$stmt->bindParam(2,$reviewid);
		$stmt->bindParam(3,$comment);
		$stmt->execute();
		return $lastid = $db->lastInsertId();
	}

	public function addComments() {
			$db = $this->db;
			$reviewid = $this->ReviewID;
			$stmt=$db->prepare("SELECT * FROM comments WHERE ReviewID = ?");
			$stmt->bindParam(1,$reviewid);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
			$result = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach($result as $comment){
				$userid = $comment->UserID;
				$user = new Users();
				$user->userID($userid);
				$firstname = $user->FirstName;
				$profilepic = $user->Profile_Pic;
				$commentid = $comment->CommentID;
				//$commenttext = nl2br($comment->Comment);
				$commenttext = htmlspecialchars($comment->Comment);
				$bbparse = new bbParser();
				$commenttext = $bbparse->getHtml($commenttext);
				$parentid = $comment->ParentID;
				
				echo "<li class='comment-holder' id='com_$commentid'>
					<div class='comment-top-wrapper'>
					<div class='user-image'>
					<img src='$profilepic' class='user-image-pic'>
					</div>
					<div class='comment-body'>
					<input type='hidden' id='comments-userid$commentid' value='$userid'>
					<h3 class='username-field'>$firstname"; 

					if($parentid > 0) {
						echo "</h3><h3 class='in-reply-to'>";
						$stmt=$db->prepare("SELECT * FROM comments WHERE CommentID = ?");
						$stmt->bindParam(1,$parentid);
						$stmt->execute();
						$rowcnt = $stmt->rowCount();
						if($rowcnt > 0) {
							$parent_user = new Users();
							$parent_comment = new Comment($parentid);
							$parent_user_id = $parent_comment->UserID;
							$parent_user->userID($parent_user_id);
							$parent_firstname = $parent_user->FirstName;
							echo " ► " . $parent_firstname;
						} else {
							echo " ► [Dead Comment]";
						}

					} else {					

					echo " replied: ";
					}


				echo	"</h3>
					<div class='comment-text'>$commenttext</div>
					</div></div>";
				if($_SESSION['LoggedIn'] == 1) {
					echo	"<div class='comment-buttons-holder'>
						<ul>";
					if($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0) {
						echo "<li id='$commentid' class='comment-delete-btn'>delete</li>
						<li id='$commentid' class='comment-edit-btn'>edit</li>" . " ";
					}
					echo	"<li id='$commentid' class='comment-reply-btn'>reply</li>
						</ul>
						</div>";
				}

				else {
					echo "<div class='comment-spacer'></div>";
				}
				echo	"</li>";
				
			}


	}

}
?>

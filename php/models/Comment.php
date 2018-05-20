<?php
Class Comment {

	public $CommentID, $UserID, $ReviewID, $ArticleID, $ParentID, $Comment, $CommentTime;
	protected $db;

	public function __construct($CommentID) {
		$this->db = new Database();
		$stmt = $this->db->prepare("SELECT * FROM comments WHERE CommentID = ?");
		$stmt->bindParam(1, $CommentID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
		$result = $stmt->fetch(PDO::FETCH_CLASS); 
		foreach($result as $key => $value) {
			$this->$key = $value;
		}
	}


	public function edit($text) { 
		$commentid = $this->CommentID;
		$db = $this->db;
		$stmt=$db->prepare("UPDATE comments SET Comment = ? WHERE CommentID = ?");
		$stmt->bindParam(1,$text);
		$stmt->bindParam(2,$commentid);
		$stmt->execute();
	}

	public function delete() { 
		$commentid = $this->CommentID;
		$db = $this->db;
		$stmt=$db->prepare("DELETE FROM comments WHERE CommentID = ?");
		$stmt->bindParam(1,$commentid);
		$stmt->execute();
	}

	public function reply($userid, $comment, $reviewid) { 
		$parentid = $this->CommentID;
		$db = $this->db;
		$stmt=$db->prepare("INSERT INTO comments (UserID, ParentID, Comment, ReviewID, CommentTime) VALUES (?,?,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1,$userid);
		$stmt->bindParam(2,$parentid);
		$stmt->bindParam(3,$comment);
		$stmt->bindParam(4,$reviewid);
		$stmt->execute();
		return $lastid = $db->lastInsertId();
	}

	public function replyToNewsComment($userid, $comment, $articleid) { 
		$parentid = $this->CommentID;
		$db = $this->db;
		$stmt=$db->prepare("INSERT INTO comments (UserID, ParentID, Comment, ArticleID, CommentTime) VALUES (?,?,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1,$userid);
		$stmt->bindParam(2,$parentid);
		$stmt->bindParam(3,$comment);
		$stmt->bindParam(4,$reviewid);
		$stmt->execute();
		return $lastid = $db->lastInsertId();
	}
}
?>

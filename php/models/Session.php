<?php
Class Session extends Schedule {

	public $SessionID, $SessionDate, $Cancelled, $Location;
	protected $db;

	public function __construct($SessionID) {
		$this->db = new Database();
		$stmt = $this->db->prepare("SELECT * FROM session WHERE SessionID = ?");
		$stmt->bindParam(1, $SessionID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Session');
		$result = $stmt->fetch(PDO::FETCH_CLASS); 
		foreach($result as $key => $value) {
			$this->$key = $value;
		}
	

	}

	public function cancelSession() {
		$db = $this->db;
			$sid = $this->SessionID;
			$datetime = $this->SessionDate;
			$datetime = strtotime($datetime);
			$cancel_status = $this->Cancelled;
			if($datetime > strtotime('now') && $cancel_status == 0) {

				$stmt=$db->prepare("UPDATE session SET Cancelled = 1 WHERE SessionID = ?;");
				$stmt->bindParam(1,$sid);
				$stmt->execute();
				echo "<meta http-equiv='refresh' content='0; url=./timetable.php'>";

			}elseif($datetime > strtotime('now') && $cancel_status == 1){
				$stmt=$db->prepare("UPDATE session SET Cancelled = 0 WHERE SessionID = ?;");
				$stmt->bindParam(1,$sid);
				$stmt->execute();
				echo "<meta http-equiv='refresh' content='0; url=./timetable.php'>";
			} else {
			echo "<meta http-equiv='refresh' content='0; url=./timetable.php'>";
			}			
	}

	public function getReviews() {
			$db = $this->db;
			$sessionid = $this->SessionID;
			$stmt=$db->prepare("SELECT * FROM reviews WHERE SessionID = ? ORDER BY ReviewID ASC");
			$stmt->bindParam(1,$sessionid);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Review');
			$result = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach ($result as $review) {
				$userid = $review->UserID;
				$profile = new Users();
				$profile->userID($userid);
				$username = $profile->FirstName;
				$profilepic = $profile->Profile_Pic;
				$reviewid = $review->ReviewID;
				$reviewtext = htmlspecialchars($review->Review);
				$bbparse = new bbParser();
				$reviewtext = $bbparse->getHtml($reviewtext);


				echo "<li class='review-holder' id='_$reviewid'>";

					if ($_SESSION['LoggedIn'] == 1){ echo "<div class='user-image'>"; } else { echo "<div class='user-image-nologin'>"; }
				echo	"<img src='$profilepic' class='user-image-pic'>
					</div>";

				if ($_SESSION['LoggedIn'] == 1) {
					echo	"<div class='review-buttons-holder'>
						<ul>";

					if ($_SESSION['userid'] == $userid || $_SESSION['user_level'] > 0){
					echo	"<li id='$reviewid' class='delete-btn'>X</li>
						<li id='$reviewid' class='edit-btn'>edit</li>";
					}
					echo	"<li id='$reviewid' class='reply-btn'><img class='reply-btn-img' src='./icons/reply.png'></li>
						</ul>
						</div>";
				}

				echo	"<div class='review-body'>
					<input type='hidden' id='userid$reviewid' value='$userid'>
					<h3 class='username-field'>$username</h3>
					<div class='review-text'>$reviewtext</div>
					</div>";

				echo	"</li>";
				$review = new Review($reviewid);
				$review->addComments();
			}

	}

	public function insertReview($userid, $review) { 
			$db = $this->db;
			$sessionid = $this->SessionID;
			$review = str_replace("<br>", "\n", $review);
			$stmt=$db->prepare("INSERT INTO reviews (UserID, SessionID, Review, ReviewTime) VALUES (?,?,?,CURRENT_TIMESTAMP)");
			$stmt->bindParam(1,$userid);
			$stmt->bindParam(2,$sessionid);
			$stmt->bindParam(3,$review);
			$stmt->execute();
			return $lastid = $db->lastInsertId();
	}



}

?>

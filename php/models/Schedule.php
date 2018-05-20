<?php
Class Schedule {

	protected $db;

	public function __construct() {
		$this->db = new Database();
	}

//----SECTION 1-----
//get and display the current timetable

	public function currentTimetable() {
		$db = $this->db;
			$stmt=$db->prepare ("SELECT * FROM (SELECT * FROM session ORDER BY SessionDate DESC LIMIT 8) session ORDER BY SessionDate ASC");
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Schedule');

			$result = $stmt->fetchALL(PDO::FETCH_CLASS);
			foreach($result as $session){ 
				$datetime = $session->SessionDate;
				$datetime = strtotime($datetime);
				$oneweek = (time() + 7*24*60*60);
				$now = time();
				$day = date("N", $datetime);
				$date = date("D d-m-Y", $datetime);
				$time = date("H:i", $datetime);
				$info = $session->Info;
				$cancelled = $session->Cancelled;
				$id = $session->SessionID;

				//highlight the upcoming thurs and sun sessions
				if($datetime < $oneweek && $datetime > $now && $day == 7) {
					echo "<tr style='background-color:#db962f'>";

				} elseif($datetime < $oneweek && $datetime > $now && $day == 4) {
					echo "<tr style='background-color:#4eaeba'>";
				} else {
					echo "<tr>";
				}
				echo "<td>";
				if($datetime < strtotime('now')){
					echo "<a href='reviews.php?id=$id'>".$date." </a>";
				}

				else{
					echo $date . " ";
				}
				echo "</td><td>" . $time . "</td><td> ";
				if($cancelled == 1) {
					echo "<span style='color:red'>CANCELLED</span> " . $info . "</td><td>";
				} else {
					echo $info . "</td><td>";
				}


				//for people with admin privelages to cancel
				if($cancelled == 0 && $_SESSION['user_level'] > 0 && $datetime > strtotime('now')) {
					echo "<a href='timetable.php?action=cancel&sessionid=$id'> cancel session </a>";
				}

				if($cancelled == 1 && $_SESSION['user_level'] > 0 && $datetime > strtotime('now')) {
					echo "<a href='timetable.php?action=uncancel&sessionid=$id'> uncancel session</a>";
				}
				echo "</td></tr>";
			}
			

	}

//----SECTION 2----
//getting and displaying the archive

	public function archive() {
		$db = $this->db;
			$stmt=$db->prepare ("SELECT * FROM session");
			$stmt->execute();
			$numrows = $stmt->rowCount();
			$n = $numrows - 8;
			$stmt=$db->prepare ("SELECT * FROM (SELECT * FROM session ORDER BY SessionDate LIMIT $n) session ORDER BY SessionDate DESC");
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Schedule');

			$result = $stmt->fetchALL(PDO::FETCH_CLASS);

			foreach($result as $session) {
				$sid = $session->SessionID;
				$datetime = $session->SessionDate;
				$datetime = strtotime($datetime);
				$date = date("D d-m-Y", $datetime);
				$time = date("H:i", $datetime);
				$info = $session->Info;
				$cancelled = $session->Cancelled;
				if($cancelled == 0) {
					$stmt=$db->prepare ("SELECT * FROM reviews where SessionID = $sid");
					$stmt->execute();
					$numreviews = $stmt->rowCount();
					if ($numreviews > 0) {
						echo "<a href = 'reviews.php?id=$sid'>" . $date . " " . $time . "</a><br>";
					} else {
						echo $date . " " . $time. "<br>";
					}
				}
			}
	}

}
?>

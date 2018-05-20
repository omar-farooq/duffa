<?php
Class Gallery {

	public function __construct() {
		$this->db = new Database();
	}

	public function userSubmittedImage($image_up) { 
		$db = $this->db;
		$stmt = $db->prepare("INSERT INTO gallery (SubmitterID, image, event, upload_time) VALUES (?, ?, 'user', CURRENT_TIMESTAMP)");
		$stmt->bindParam(1, $_SESSION['userid']);
		$stmt->bindParam(2, $image_up);
		$stmt->execute();

	}

	public function insertDuffaImage($image_up) {
		$db = $this->db;
		$stmt = $db->prepare("INSERT INTO gallery (SubmitterID, image, event, upload_time) VALUES (?, ?, 'duffa', CURRENT_TIMESTAMP)");
		$stmt->bindParam(1, $_SESSION['userid']);
		$stmt->bindParam(2, $image_up);
		$stmt->execute();
	}
}
?>

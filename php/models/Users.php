<?php
Class Users {

	public $UserID, $Username, $FirstName, $LastName, $emailaddress, $phone_number, $Profile_Pic, $DateRegistered, $user_level, $email_list, $sms_list, $about;
	public $postsCount, $reviewsCount, $imagesCount;
	private $db, $password;

	public function __construct($user) {
		$this->db = new Database();
		is_numeric($user) ? $query = "SELECT * FROM users WHERE UserID=?" : $query = "SELECT * FROM users WHERE Username=?";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(1,$user);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Users');
		$result = $stmt->fetch(PDO::FETCH_CLASS);
		foreach($result as $key => $value) {
			$this->$key = $value;
		}

		$this->postsCount();
		$this->reviewsCount();
		$this->imagesCount();
	}

//individual functions to build a profile of a single user from their user id or username

	public function userID($UserID) {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM users WHERE UserID=?");
		$stmt->bindParam(1,$UserID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Users');
		$result = $stmt->fetch(PDO::FETCH_CLASS);
		foreach($result as $key => $value) {
			$this->$key = $value;
		}

		$this->postsCount();
		$this->reviewsCount();
		$this->imagesCount();
		

	}

	public function username($Username) {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM users WHERE Username=?");
		$stmt->bindParam(1,$Username);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Users');
		$result = $stmt->fetch(PDO::FETCH_CLASS);
		foreach($result as $key => $value) {
			$this->$key = $value;
		}

		$this->postsCount();
		$this->reviewsCount();
		$this->imagesCount();
		

	}

//get the number of posts, reviews, images etc. from the user

	private function postsCount() {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM comments WHERE UserID = ?");
		$stmt->bindParam(1,$this->UserID);
		$stmt->execute();
		$postsCount = $stmt->rowCount();
		$this->postsCount = $postsCount;

	}

	private function reviewsCount() {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM reviews WHERE UserID = ?");
		$stmt->bindParam(1,$this->UserID);
		$stmt->execute();
		$reviewsCount = $stmt->rowCount();
		$this->reviewsCount = $reviewsCount;

	}

	private function imagesCount() {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM gallery WHERE SubmitterID = ?");
		$stmt->bindParam(1,$this->UserID);
		$stmt->execute();
		$imagesCount = $stmt->rowCount();
		$this->imagesCount = $imagesCount;

	}


//get a list of all users 

	public function listAll() {
		$db = $this->db;
		$stmt=$db->prepare ("SELECT * FROM users ORDER BY FirstName");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Users');

		$result = $stmt->fetchAll(PDO::FETCH_CLASS);
		foreach ($result as $user) {
			echo "<a href='./user_profile?userid=$user->UserID'><li class='users_list_li'>";
			echo "<img src='$user->Profile_Pic' class='user_list_profile_pic'>" . "<br>" . $user->FirstName . " " . $user->LastName;
			echo "</li></a>";
		}

	}

//search for users
	public function searchFor($fname, $lname) {
		$db = $this->db;
		$fname = "%$fname%";
		$lname = "%$lname%";

	

			$stmt=$db->prepare ("SELECT * FROM users WHERE FirstName LIKE ? AND LastName LIKE ? OR LastName LIKE ? ORDER BY FirstName");
			$stmt->bindParam(1,$fname);
			$stmt->bindParam(2,$lname);
			$stmt->bindParam(3,$fname);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Users');

			$result = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach ($result as $user) {
				echo "<a href='./user_profile?userid=$user->UserID'><li class='users_list_li'>";
				echo "<img src='$user->Profile_Pic' class='user_list_profile_pic'>" . "<br>" . $user->FirstName . " " . $user->LastName;
				echo "</li></a>";

				
			}


	}



}

?>

<?php
Class FBUser extends Account{
	public $UserID, $Username, $FirstName, $LastName, $EmailAddress, $PhoneNumber, $Profile_Pic, $DateRegistered, $user_level, $email_list, $sms_list, $about, $Password, $FacebookID;
	protected $db;

	public function __construct($arg) {
		parent::__construct($arg);

	}

//Register with a Basefuck account
	public function registerWithFB($firstname, $lastname, $email, $fbid, $profilepic) {

		//if they have an email address set then verify it
		empty($email) ? $verified = 0 : $verified = 1;


		//set a username of firstnamelastname and check if it exists
		$username = strtolower($firstname . $lastname);		

		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
		$stmt->bindParam(1, $username);
		$stmt->execute();
		$row = $stmt->rowCount();
		if($row > 0) {

			//if the username exists then try firstnamelastname1 and iterate until a solution is found
			$exists = 1;
			$i = 1;
			while($exists == 1) {
				$username = $username . $i;
				$stmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
				$stmt->bindParam(1, $username);
				$stmt->execute();
				$exists = $stmt->rowCount();
				$i++;
			}
		}

		//upload the profile pic
		$uploadedpic = "./user/profile_pics/" . $username . ".jpg";
		file_put_contents($uploadedpic, file_get_contents($profilepic));

		/*On my website I had to resort to an alternative way to transfer the profile pic using cURL:
			$uploadedpic = "./user/profile_pics/" . $username . ".jpg";
	
			$fp = fopen($uploadedpic, 'w+');

			   $ch = curl_init($profilepic);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_exec($ch);
			curl_close($ch);

		this is because file_get_contents must have been disabled*/

		//insert variables into database
		
		$stmt2=$db->prepare ("INSERT INTO users (Username, FirstName, LastName, EmailAddress, FacebookID, Profile_Pic, verified, email_list, sms_list, DateRegistered) VALUES (?,?,?,?,?,?,?,1,0, CURRENT_TIMESTAMP)");
		$stmt2->bindParam(1, $username);
		$stmt2->bindParam(2, $firstname);
		$stmt2->bindParam(3, $lastname);
		$stmt2->bindParam(4, $email);
		$stmt2->bindParam(5, $fbid);
		$stmt2->bindParam(6, $uploadedpic);
		$stmt2->bindParam(7, $verified);
		


		if($stmt2->execute()) {
			//echo "<meta http-equiv=\"refresh\" content=\"0; url=account_settings.php\">";
			//if successfully registered then we can log them in:

			$this->loginWithFB($email, $fbid);
		} else {
			echo "error, not registered. <a href='register.php'>try again</a>";
			die();
		}
	
	}

//login with your FB account
	public function loginWithFB($email, $fbid) {
		$db = $this->db;

		//attempt to log in with a verified email first (doesn't have to have registered on the site with FB)
		$stmt = $db->prepare("SELECT * FROM users WHERE EmailAddress = ? AND verified = 1");
		$stmt->bindParam(1, $email);
		$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Account');
		$stmt->execute();
		$exist = $stmt->rowCount();

		if($exist === 1) {


			$acct = $stmt->fetch(); 
				$remember = 'true';
				$acct->createSession($remember);

			
		} else {

			//If they created a FB account with a mobile phone then try logging in with their Facebook ID (has to have registered using Facebook for this)

			$stmt = $db->prepare("SELECT * FROM users WHERE FacebookID = ?");
			$stmt->bindParam(1, $fbid);
			$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Account');
			$stmt->execute();
			$exist = $stmt->rowCount();

			if($exist === 1) {

				$acct = $stmt->fetch(); 
				$remember = 'true';
				$acct->createSession($remember);

			} else {
			
				echo "<br><br>You need to register first";
				

			}
		}
	}


}

?>

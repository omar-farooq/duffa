<?php
Class Account {
	public $UserID, $Username, $FirstName, $LastName, $EmailAddress, $PhoneNumber, $Profile_Pic, $DateRegistered, $user_level, $email_list, $sms_list, $about, $Password, $FacebookID;
	protected $db;

	public function __construct() {
		$this->db = new Database();

	}

//register a new user account

	public function register($username, $firstname, $lastname, $password, $email, $phone, $email_list, $phone_list) {
		$db = $this->db;
		$stmt=$db->prepare ("INSERT INTO users (Username, FirstName, LastName, Password, EmailAddress, PhoneNumber, email_list, sms_list, DateRegistered) VALUES (?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1, $username);
		$stmt->bindParam(2, $firstname);
		$stmt->bindParam(3, $lastname);
		$stmt->bindParam(4, $password);
		$stmt->bindParam(5, $email);
		$stmt->bindParam(6, $phone);
		$stmt->bindParam(7, $email_list);
		$stmt->bindParam(8, $phone_list);

		if($stmt->execute()) {

			//If they've entered an email address then send an email to verify their email address
			if(!empty($email)) {
				$userid = $db->lastInsertId();
				Account::sendVerificationEmail($email, $userid);
			}
			echo "Registered <a href='login.php'>click here to log in</a>";
		} else {
			echo "error, not registered. <a href='register.php'>try again</a>";
		}
	}


//login to your account

	public function login($username, $password) {
		$db = $this->db;

		$stmt=$db->prepare ("SELECT * FROM users WHERE Username = ?");
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$num = $stmt->rowCount();
		if ($num == 1) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Account');

			$login_account = $stmt->fetch(PDO::FETCH_CLASS); 

			//If they managed to bypass the login page post requirements somehow then tell them that they can't login this way without a password
			if(empty($login_account->Password)) {
				echo "Social Media (Facebook) login required<br>";
				return false;
			}

			//If they logged in then create a session
			elseif(password_verify($password, $login_account->Password)) {

				foreach($login_account as $key=>$value) {
					$this->$key = $value;
				}

				$login_account->createSession();		

			} else {
				echo "invalid login details";
			}
		
		}
		else {
			echo "that username doesn't exist";
		}

	}

//login with the 'remember me' checkbox ticked

	public function loginAndRemember($username, $password) {
		$db = $this->db;

		$stmt=$db->prepare ("SELECT * FROM users WHERE Username = ?");
		$stmt->bindParam(1,$username);
		$stmt->execute();
		$num = $stmt->rowCount();
		if ($num == 1) {
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Account');

			$login_account = $stmt->fetch(PDO::FETCH_CLASS); 

			//If they managed to bypass the login page post requirements somehow then tell them that they can't login this way without a password
			if(empty($login_account->Password)) {
				echo "Social Media (Facebook) login required<br>";
				return false;
			}

			//If they logged in then create a session
			elseif(password_verify($password, $login_account->Password)) {

				foreach($login_account as $key=>$value) {
					$this->$key = $value;
				}

				//we'll create a session but pass the value 'true' in for when it refreshes and wants to know whether to remember (i.e. set a cookie)
				$remember = 'true';
				$login_account->createSession($remember);

			} else {
				echo "invalid login details";
			}
		
		}
		else {
			echo "that username doesn't exist";
		}

	}


//create session variables after you've logged in

	public function createSession($remember){
		if($remember != 'true') $remember = 'false';

		$_SESSION['userid'] = "{$this->UserID}";
		$_SESSION['Username'] = "{$this->Username}";
		$_SESSION['EmailAddress'] = "{$this->EmailAddress}";
		$_SESSION['LoggedIn'] = 1;
		$_SESSION['user_level'] = "{$this->user_level}";
		echo "<meta http-equiv=\"refresh\" content=\"0 ?remember=" . $remember . "\">";
	}


//set a cookie if you've checked the 'remember me' box

	public function getCookie(){
		$db = $this->db;
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$userid = $_SESSION['userid'];

		$stmt = $db->prepare ("INSERT INTO auth_tokens (token, UserID) VALUES (?, ?)");
		$stmt->bindParam(1,$token);
		$stmt->bindParam(2,$userid);
		$stmt->execute();

		
		$cookie = $userid . ':' . $token;
		$mac = hash_hmac('sha256', $cookie, ALAN_KEY);
		$cookie .= ':' . $mac;
		setcookie('remembermeduffa', $cookie, time()+60*60*24*365);

	}

//login to account automatically if the cookie is in place and the tokens match.

	public function rememberMe() {
		$db = $this->db;
			
		$cookie = isset($_COOKIE['remembermeduffa']) ? $_COOKIE['remembermeduffa'] : '';

			if ($cookie) {
				list($userid, $token, $mac) = explode(':', $cookie);
				if ($mac !== hash_hmac('sha256', $userid . ':' . $token, ALAN_KEY)) {
					return false;
					
				} 
				$stmt = $db->prepare ("SELECT token FROM auth_tokens WHERE UserID = ?");
				$stmt->bindParam(1,$userid);
				$stmt->execute();
				$usertoken = $stmt->fetch();

				if (Account::timingSafeCompare($usertoken['token'], $token)) {
					$stmt = $db->prepare("SELECT * FROM users WHERE UserID = ?");
					$stmt->bindParam(1,$userid);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_CLASS, 'Account');

					$loggedin = $stmt->fetchAll(PDO::FETCH_CLASS); 
					
					foreach ($loggedin as $sessionvars) {
						$_SESSION['userid'] = "{$sessionvars->UserID}";
						$_SESSION['Username'] = "{$sessionvars->Username}";
						$_SESSION['EmailAddress'] = "{$sessionvars->EmailAddress}";
						$_SESSION['LoggedIn'] = 1;
						$_SESSION['user_level'] = "{$sessionvars->user_level}"; 
					}
					
				}

			}

	} 


	function timingSafeCompare($safe, $user) {
	    // Prevent issues if string length is 0
	    $safe .= chr(0);
	    $user .= chr(0);

	    $safeLen = strlen($safe);
	    $userLen = strlen($user);

	    // Set the result to the difference between the lengths
	    $result = $safeLen - $userLen;

	    // Note that we ALWAYS iterate over the user-supplied length
	    // This is to prevent leaking length information
	    for ($i = 0; $i < $userLen; $i++) {
		// Using % here is a trick to prevent notices
		// It's safe, since if the lengths are different
		// $result is already non-0
		$result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
	    }

	    // They are only identical strings if $result is exactly 0...
	    return $result === 0;
	}


//delete user token after logging out

	public function deleteToken() {
		$db = $this->db;
		$userid = $_SESSION['userid'];
		$stmt=$db->prepare ("DELETE FROM auth_tokens WHERE UserID = ?");
		$stmt->bindParam(1,$userid);
		$stmt->execute();	

	}


//get user info for the logged in user

	public function info() {

		$db = $this->db;
		$stmt=$db->prepare ("SELECT * FROM users WHERE UserID = ?");
		$stmt->bindParam(1,$_SESSION['userid']);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Account');

		$result = $stmt->fetch(PDO::FETCH_CLASS);

		foreach($result as $key => $value) {
			$this->$key = $value;
		}

	}

//send verification email

	public static function sendVerificationEmail($email, $userid) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));
		$db = new Database();
		$stmt = $db->prepare("SELECT * FROM email_verification WHERE UserID = ?");
		$stmt->bindParam(1, $userid);
		$stmt->execute();
		$num = $stmt->rowCount();

		if($num > 0) {
			//If a verification email has previously been sent then don't waste space in the database by creating a new entry for it, just update the current entry with a new token
			$stmt2 = $db->prepare("UPDATE email_verification SET token = ?, EmailAddress = ? expires = NOW() + INTERVAL 3 DAY WHERE UserID = ?");	
			$stmt2->bindParam(1, $token);
			$stmt2->bindParam(2, $email);
			$stmt2->bindParam(3, $userid);
			
		} else {
			//send a verification email for the first time
			$stmt2 = $db->prepare("INSERT INTO email_verification(token, UserID, EmailAddress, expires) values (?, ?, ?, NOW() + INTERVAL 3 DAY)");
			$stmt2->bindParam(1, $token);
			$stmt2->bindParam(2, $userid);
			$stmt2->bindParam(3, $email);
		}


		$stmt2->execute();

		$subj = "Verify your Duffa account";
		$msg = "Hello, <br><br>

			Click the link below to verify your email address<br>
			https://www.duffa.org/login?vemail=" . $email . "&vtoken="  . $token . "&uid=" . $userid;
		$headers = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/html; charset=iso-8859-1' . "\n" . 'From: registrations@duffa.org' . "\r\n";
		mail($email, $subj, $msg, $headers);
	}

//verify the email address
	public static function verifyEmail($email, $token, $userid) {
		$db = new Database();
		$stmt = $db->prepare("SELECT * FROM email_verification WHERE UserID = ? AND EmailAddress = ? AND token = ? AND expires > NOW()");
		$stmt->bindParam(1, $userid);
		$stmt->bindParam(2, $email);
		$stmt->bindParam(3, $token);
		$stmt->execute();
		$num = $stmt->rowCount();

		if($num != 1) {
			return "<b>Unable to verify credentials</b><br><br>";	
		} else {

		$stmt = $db->prepare("Update users SET verified = 1 WHERE UserID = ?");
		$stmt->bindParam(1, $userid);
		$stmt->execute();
			
			return "<b>Your email address has been verified</b><br><br>";
		}
	}


//update your account details

	public function update($setting,$inputval) {

		$db = $this->db;

		$stmt=$db->prepare ("UPDATE users SET $setting = ? WHERE UserID = ?");
		$stmt->bindParam(1,$inputval);
		$stmt->bindParam(2,$_SESSION['userid']);
		$stmt->execute();

	}

//tweet people private messages

	public function tweet($pmcontent, $sender) {

	//Get the User ID of the sender
	$createSender = new Users($sender);
	$senderId = $createSender->UserID;
	$db = $this->db;

	//check if there's a match for @user in the message
	$pmsend = preg_match_all('/(?<=^|\s)@([a-z0-9_]+)/i',$pmcontent, $matches);
		if($pmsend !== false) {
			$matches_length = count($matches[0]);
			
			//iterate through the matches and replace @ with a blank to get the recipient's username in the database
			for ($y=0;$y<$matches_length;$y++){
				$string = $matches[0][$y];
				$string = str_replace('@','',$string);
				//$newpmcontent = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i','<a href="./users/$1">@$1</a>',$pmcontent);
				//the content is adjusted for BB Code

				//replace @user in the message with a link to @user's profile
				$newpmcontent = preg_replace('/(?<=^|\s)@([a-z0-9_]+)/i','[url href=./users/$1]@$1[/url]',$pmcontent);

				//check if the user actually exists before sending the message
				$stmt=$db->prepare ("SELECT * FROM users WHERE Username = ?");
				$stmt->bindParam(1,$string);
				$stmt->execute();
				$does_user_exist = $stmt->rowCount();
				if($does_user_exist > 0){

					//Get the user ID of @user:
					$createRecipient = new Users($string);
					$recipientId = $createRecipient->UserID; 

					$stmt=$db->prepare ("INSERT INTO pms (sender, recipient, message, senderID, recipientID, pmtime) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
					$stmt->bindParam(1,$sender);
					$stmt->bindParam(2,$string);
					$stmt->bindParam(3,$newpmcontent);
					$stmt->bindParam(4,$senderId);
					$stmt->bindParam(5,$recipientId);
					$stmt->execute();
				}



			}
		}
	}
	

}
//Omar Farooq
?>

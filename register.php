<?php
$banner_text = 'Register';
$banner_image = './layout/banner_images/random4.jpg';

include "./layout/layout-top2.php"; ?>

<style>

@media only screen and (max-width: 1000px) {
	.facebook-reg-img {
		height:48px; 
		width:300px;
	}
}

@media only screen and (min-width: 1001px) {
	#content {
		width: 90%;
		font-size: 22px;
		padding-bottom: 10px;
	}

	input[type=checkbox] {
		padding: 10px;
		transform: scale(1.5);
	}
}


</style>

<?php
//already logged in

if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])) {echo "logged in as " . $_SESSION['Username'] . " <a href='?action=logout'>click to logout</a>";
	if(@$_GET['action'] == "logout") {
	session_destroy();
	echo "<meta http-equiv=\"refresh\" content=\"0\">";
	}
include "./layout/layout-bottom.php";
exit;
}

//register button has been hit
if ($_POST['reg']){

	//create temp session variable from entered data so that the fields populate if going back
	$_SESSION['regform']['tempregusername'] = $_POST['newUsername'];
	$_SESSION['regform']['tempregfname'] = $_POST['firstname'];
	$_SESSION['regform']['tempreglname'] = $_POST['lastname'];
	$_SESSION['regform']['tempregemail'] = $_POST['email'];
	$_SESSION['regform']['tempregphone'] = $_POST['phone'];
	if($_POST['mail_list'] == 'on') $_SESSION['regform']['tempregmailop'] = 'checked';
	if($_POST['sms_list'] == 'on') $_SESSION['regform']['tempregsmsop'] = 'checked';

//if empty fields
	foreach ($_POST as $field => $value) {
		if ($field != "email" && $field != "lastname" && $field != "phone") {
			if (empty($value))
			{
			$blanks[] = $field;
			}
			else {
			$good_data[$field] = strip_tags(trim($value));
			}
		}
	}

	if (isset($blanks)){
	$message3 = "Please insert info into the blank fields <a href='register.php'>back</a>";

	echo $message3;
	extract($good_data);
	include "./layout/layout-bottom.php";
	exit();
	}

//prevent injection by limiting characters
	foreach($_POST as $field => $value) {
		if(!empty($value)) {
			if(preg_match("/newUsername/i",$field)) {
				if(!preg_match("/^\_*\d*[a-zA-Z][a-zA-Z0-9_]*$/i",$value))
				{
				$errors[] = "$value is not a valid username";
				}
			}

			if(preg_match("/firstname/i",$field)) {
				if(!preg_match("/^[A-Za-z' -]{2,50}$/",$value)) {
				$errors[] = "$value is not a valid name";
				}
			}

			if(preg_match("/lastname/i",$field)) {
				if(!preg_match("/^[A-Za-z' -]{1,50}$/",$value)) {
				$errors[] = "$value is not a valid name";
				}
			}

			if(preg_match("/phone/i",$field)) {
				if(!preg_match("/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/",$value)) {
				$errors[] = "$value is not a valid phone number";
				}
			}

			if(preg_match("/email/i",$field)) {
				if(!preg_match("/^.+@.+\\..+$/",$value))
				{
				$errors[] = "$value is not a valid email";
				}

			}

			if(preg_match("/usrpass/i",$field)) {

				if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{6,}$/", $value))
				{
				$errors[] = "$value does not meet the password complexity requirements";
				}

			}

		}
	}
//strip html tags and show errors
	foreach($_POST as $field => $value){
	$$field = strip_tags(trim($value));
	}
	if(@is_array($errors)){
	$message2 = "";
		foreach($errors as $value){
		$value = htmlspecialchars($value);
		$message2 .= $value." Please try again <br>";
		}
	echo $message2 . "<a href='register.php'>back</a>";

	include "./layout/layout-bottom.php";

	exit();
	}

//check if email and username are filled iff email list and sms list are checked

	if(empty($_POST['email'])) {

		if(!empty($_POST['mail_list'])) {
			echo "<br>you can't be on the email list without an email <a href='register.php'>back</a>";
			include "./layout/layout-bottom.php";
			exit;
		}
	}

	if(empty($_POST['phone'])) {
		if(!empty($_POST['sms_list'])) {
			echo "<br><br>you can't receive phone updates without inserting a phone number <a href='register.php'>back</a>";
			include "./layout/layout-bottom.php";
			exit;
		}

	}

//encrypt the password for the database

$password = password_hash($_POST['usrpass'], PASSWORD_DEFAULT);

//set variables for email list and phone list

	if($_POST['mail_list']) {
	$email_list = 1;
	}

	else {
	$email_list = 0;
	}

	if($_POST['sms_list']) {
	$phone_list = 1;
	}

	else {		
	$phone_list = 0;
	}



//get the number of rows that are duplicates of the Username input

		$db = new Database();
		$stmt = $db->prepare("SELECT Username from users WHERE Username=?");
		$stmt->bindParam(1, $newUsername);
		$stmt->execute();
		$num = $stmt->rowCount();


//check if passwords match or if username exists
	if (isset($_POST['usrpass'])) {
		if ($_POST['usrpass'] != $_POST['pchck']) {
		echo "passwords don't match <a href='register.php'>back</a>";
		include "./layout/layout-bottom.php";
		exit;
		}
	}
	if ($num > 0) {
	echo "sorry username taken <a href='register.php'>back</a>";
	include "./layout/layout-bottom.php";	
	exit;
	}




// remove temporary field variables and enter data into database
	else {

		unset($_SESSION['regform']);

		$account = new Account();
		$account->register($newUsername, $firstname, $lastname, $password, $email, $phone, $email_list, $phone_list);
	}
}

else {

//offer a chance to register with Facemuck
	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email'];
	$loginURL = $helper->getLoginUrl('https://localhost/duffa/register.php?method=facebook', $permissions);

	echo '<a href="' . htmlspecialchars($loginURL) . '"><img src="./icons/sign-up-with-facebook.png" alt="Register with Facebook" class="facebook-reg-img"></img></a><br>with two clicks or fill in the form below<br><br>';

	if(isset($_GET['method']) && $_GET['method'] == 'facebook') {
		$helper = $fb->getRedirectLoginHelper();
 
		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit();
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit();
		}
		 
		if (! isset($accessToken)) {
		  if ($helper->getError()) {
		    header('HTTP/1.0 401 Unauthorized');
		    echo "Error: " . $helper->getError() . "\n";
		    echo "Error Code: " . $helper->getErrorCode() . "\n";
		    echo "Error Reason: " . $helper->getErrorReason() . "\n";
		    echo "Error Description: " . $helper->getErrorDescription() . "\n";
		  } else {
		    header('HTTP/1.0 400 Bad Request');
		    echo 'Bad request';
		  }
		  exit();
		}
		 
		// Logged in
		// The OAuth 2.0 client handler helps us manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();
		 
		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);


		$tokenMetadata->validateAppId($fb_app_id);

		$tokenMetadata->validateExpiration();


		if (! $accessToken->isLongLived()) {
		  // Exchanges a short-lived access token for a long-lived one
		  try {
		    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
		    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
		    exit;
		  }

		  //echo '<h3>Long-lived</h3>';
		  //var_dump($accessToken->getValue());
		}

		$response = $fb->get('/me?fields=id,first_name,last_name,email,picture.width(480).height(480)', $accessToken);

		$fbreg = $response->getGraphNode()->asArray();
		

		//check they don't already exist
		$db = new Database();
		$stmt = $db->prepare("SELECT * FROM users WHERE EmailAddress = ? AND verified = 1");
		$stmt->bindParam(1, $fbreg['email']);
		$stmt->execute();
		$exist = $stmt->rowCount();

		if($exist === 1) {
			//if they exist then exit and redirect the user to another page

				echo "This email address is already registered";
				
				echo '<meta http-equiv="refresh" content="0; url=./login?registered=true">';
				exit();
			
		} else {
			//now check if they don't have a verified email but do have a koobecaf id and therefore account		

			$stmt = $db->prepare("SELECT * FROM users WHERE FacebookID = ?");
			$stmt->bindParam(1, $fbreg['id']);
			$stmt->execute();
			$exist = $stmt->rowCount();

			if($exist === 1) {
				echo "This user is already registered";
				echo '<meta http-equiv="refresh" content="0; url=./login?registered=true">';
				exit();
			} else {

				//if no registered account is found and they've agreed to all terms then register the user
				$fbfirstname = $fbreg['first_name'];
				$fblastname = $fbreg['last_name'];
				$fbemail = $fbreg['email'];
				$fbid = $fbreg['id'];
				$fbprofilepic = $fbreg['picture']['url'];
				$fbuser = new FBUser();
				$fbuser->registerWithFB($fbfirstname, $fblastname, $fbemail, $fbid, $fbprofilepic);

			}

		}
	}


?>
<!-- registration form  -->
<span id="reg-header">Registration Form</span>

<br>
<br>

Make sure that you fill in the fields marked with a *

<form method="post" action="" name="regform" id="regform">


<label for>*Username</label><input type="text" name="newUsername" id="newUsername" class="box1" required minlength="3" maxlength="20" value="<?php echo $_SESSION['regform']['tempregusername'] ?>">
<br />
<label for>*First Name</label><input type="text" name="firstname" id="firstname" class="box1" required minlength="2" maxlength="20" value="<?php echo $_SESSION['regform']['tempregfname'] ?>">
<br />
<label for>*Last Name</label><input type="text" name="lastname" id="lastname" class="box1" required minlength="1" maxlength="20" value="<?php echo $_SESSION['regform']['tempreglname'] ?>">
<br />
<label for>*Password</label><input type="password" name="usrpass" id="usrpass" class="box1" required minlength="6" maxlength="20">
<br />
<label for>*Confirm Password</label><input type="password" name="pchck" id="pchck" class="box1" required minlength="6" maxlength="20">
<br />
<label for>email</label><input type="email" name="email" id="email" class="box2" maxlength="45" value="<?php echo $_SESSION['regform']['tempregemail'] ?>">
<br />
<label for>Mobile Number</label><input type="text" name="phone" id="phone" class="box1" maxlength="30" value="<?php echo $_SESSION['regform']['tempregphone'] ?>">
<br />
<br />Receive email updates  <input type="checkbox" name="mail_list" id="mail_list" class="checkbox1" <?php echo $_SESSION['regform']['tempregmailop'] ?>>

<br />Receive phone updates  <input type="checkbox" name="sms_list" id="sms_list" class="checkbox1" <?php echo $_SESSION['regform']['tempregsmsop'] ?>>
<br /><br />
<input type="submit" name="reg" id="reg" class="button" value="Register">

</form>

<br>
Do note that your information will not be shared with any third parties.
<br>
Also note that you will not receive spam mail/texts and that you can change your preferences of anything other than your username in account settings.

<!-- script to make the content div full length on bigger screens -->
<script type='text/javascript' src='./jquery/content_full_length.js'></script>

<?php };
include "./layout/layout-bottom.php"; 
//Omar Farooq?>

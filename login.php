<?php 

$banner_text = 'Log in';
$banner_image = './layout/banner_images/random2.jpg';

include "./layout/layout-top2.php";

//this is a link from the registration page for people who are already registered with bookface and attempt to register again
if(isset($_GET['registered']) && $_GET['registered'] == true) {
	echo "You're already registered with facebook. Click the facebook button below to continue.<br><br>";
}

if(isset($_GET['registered']) && $_GET['registered'] == false) {
	echo "You need to register first.<br><br>";
}

//If the email verification link has been clicked
//this requires the three variables passed which are the verification email address, the token and the user requesting verification
if(isset($_GET['vemail'], $_GET['vtoken'], $_GET['uid'])) {
	$vemail = $_GET['vemail'];
	$vtoken = $_GET['vtoken'];
	$uid = $_GET['uid'];

	echo Account::verifyEmail($vemail,$vtoken,$uid);
}

//if the user is logged in, inform them and offer them a way to log out
if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])) {
	echo $_SESSION['Username'] . " <a href='?action=logout'> logout</a>";
	include "./layout/layout-bottom.php";
	if(@$_GET['action'] == "logout") {
		$account = new Account();
		$account->deleteToken();
		session_destroy();
		echo "<meta http-equiv=\"refresh\" content=\"0\">";
		}

	exit;
}

else 
//if the user is not logged in, offer them to log in via this form
{ ?>
	<div id="logindiv">
	Log In:
	<br>
	<form method="post" action="" name="login" id="login">
	<label for>username</label><input type="text" name="username" id="username" class="box1" maxlength="20"/>
	<br />
	<label for>password</label><input type="password" name="password" id="password" class="box1" maxlength="20" />
	<br />
	<input type="checkbox" id="remember" name="remember">  remember me
	<br />
	<input type="submit" name="login" id="login" class="button" value="Login">
	
	</form>
	</div>

<?php 
//get info from the form and run the login function
	if(!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = $_POST['username'];
		$password = ($_POST['password']);
		$remember = $_POST['remember'];

		$account = new Account();

		if($remember == 'on') {
			$account->loginAndRemember($username, $password);
		} else {
			$account->login($username, $password);
		}
	}

//


//Offer an opportunity to login with zuckbook

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email'];
	$loginURL = $helper->getLoginUrl('https://localhost/duffa/login.php?method=facebook', $permissions);

	echo 'or<br><br><a href="' . htmlspecialchars($loginURL) . '"><img src="./icons/facebook-login-button.png" alt="Log in with Facebook" height="40px" width="250px"></img></a>';

	if(isset($_GET['method']) && $_GET['method'] == 'facebook') {
		$helper = $fb->getRedirectLoginHelper();
 
		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
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
		  exit;
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


		$response = $fb->get('/me?fields=id,name,email', $accessToken);

		$fbuser = $response->getGraphNode()->asArray();

		$fbrequester = new FBUser();
		$fbrequester->loginWithFB($fbuser['email'], $fbuser['id']);


	}

} 
?>
<!-- script to make the content div full length on bigger screens -->
<script type='text/javascript' src='./jquery/content_full_length.js'></script>
<?php
//Omar Farooq
include "./layout/layout-bottom.php"; ?>

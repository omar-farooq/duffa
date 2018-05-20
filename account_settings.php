<?php 
$banner_text = 'Account';
$banner_image = './layout/banner_images/random4.jpg';
include "./layout/layout-top2.php";
?>

<style>
	#related {
		top:60px;
		padding-bottom: 20px;
	}

	#related:after {
		box-shadow: 0px 0px 0px 0px;
	}

	#related img {
		max-width: 100%;
	}

	.headline-red {
		color: #d14924;
	}

	@media (max-height:1001px) {
		#related {
			display: block;
		}
	}
</style>

<?php

/************************************
	sections:
 1 - change user password
 2 - change name
 3 - update contact info
 4 - update your personal about section
 5 - change your profile pic
 6 - view your orders
	by Omar Farooq
*************************************/


if($_SESSION['LoggedIn'] == 1){ 

	$account = new Account();
	$account->info();

	$user_id = $_SESSION['userid'];
	$username = $_SESSION['Username'];

	echo "<br><br><a href='?action=changepassword'>change password</a> <br>

	<a href = '?action=changename'>change name</a><br>

	<a href = '?action=changepref'>change contact preferences</a><br>

	<a href = '?action=changeabout'> change your about section </a><br>

	<a href = '?action=profilepic'> change your profile picture </a><br>

	<a href = '?action=orders'> view your orders </a><br>";

	if($_SESSION['user_level'] > 0) {
		echo "<a href='./admin'>admin page</a><br>";
	} else {
		echo "<br>";
	}

	$_GET['action'] == "";

	switch ($_GET['action']) {
//----SECTION 1----
//code to change user password
		case changepassword:
		echo "change password"; 

		//check if a password has been set yet. With bacefook registration this isn't always set off the bat
		$pwrd = $account->Password;
		if(empty($pwrd)) {

	?>

			<form method = "post" action = "" name="setpwrd" id="setpwrd">
			<br>new password <input required minlength="6" title="min 6 characters" type = "password" name="newpw" id="newpw">
			<br>repeat new password <input type = "password" name="pwchck" id="pwchck">
			<br><input type="submit" name="setpw" id="setpw" value="set password">
			</form>


	<?php
			if(isset($_POST['setpw'])) {

				$newpw = $_POST['newpw'];
				$pwchck = $_POST['pwchck'];
				$insertpw = password_hash($_POST['newpw'], PASSWORD_DEFAULT);

				if($newpw != $pwchck) {	echo "passwords do not match";

				} elseif(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{6,}$/",$newpw)) {
					echo "Password does not meet the complexity requirements of at least one letter and number";
				}else {
					$account->update(Password,$insertpw);
					echo "password successfully changed";

				}

			}
			

		} else {
	?>
			<form method = "post" action = "" name="changepwrd" id="changepwrd">
			old password <input type = "password" name="oldpw" id="oldpw">
			<br>new password <input required minlength="6" title="min 6 characters" type = "password" name="newpw" id="newpw">
			<br>repeat new password <input type = "password" name="pwchck" id="pwchck">
			<br><input type="submit" name="changepw" id="changepw" value="change password">
			</form>

	<?php 
			if(isset($_POST['changepw'])) {

				$oldpw = $_POST['oldpw'];
				$newpw = $_POST['newpw'];
				$pwchck = $_POST['pwchck'];
				$insertpw = password_hash($_POST['newpw'], PASSWORD_DEFAULT);

				$pwrd = $account->Password;

				if(password_verify($oldpw, $pwrd)) {

					if($newpw != $pwchck) {	echo "passwords do not match";	}

					elseif(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{6,}$/",$newpw)) {
						echo "Password does not meet the complexity requirements of at least one letter and number";
					} elseif($newpw == $oldpw){echo "choose a different password this time";}

					else{
						$account->update(Password,$insertpw);
						echo "password successfully changed";
					
					}
	


				} else {
					echo "current password incorrect";
				}

			}

		}

		break;

//----SECTION 2----
//code to change user's real name
		case changename:
		$fname = $account->FirstName;
		$lname = $account->LastName;
		echo "change name"; ?>
		<form method = "post" action = "">
		First name: <input type= "text" name="fname" id="fname" required minlength="2" value="<?php echo $fname; ?>">
		<br>Last name: <input type= "text" name="lname" id="lname" value="<?php echo $lname; ?>">
		<br><input type="submit" name="changename" id="changename" value="change name">

		</form>


<?php

		if(isset($_POST['changename'])){
			foreach($_POST as $field => $value){
			$$field = strip_tags(trim($value));
			}
		$newfname = $_POST['fname'];
		$newlname = $_POST['lname'];

			if(empty($_POST['fname'])){
			echo "must provide a first name at least";
			include "./layout/layout-bottom.php";
			exit;
			} 
			else {
				if(!preg_match("/^[A-Za-z' -]{1,50}$/" ,$newfname))
					{ 
					echo "invalid name";
					include "./layout/layout-bottom.php";
					exit;
					}
				}

			if(!empty($_POST['lname'])) {
				if(!preg_match("/^[A-Za-z' -]{1,50}$/" ,$newlname))
				{ 
				echo "invalid name";
				include "./layout/layout-bottom.php";
				exit;
				}
			}

			$account->update(FirstName,$newfname); 
			$account->update(LastName,$newlname);
			echo 'changing';
			echo '<meta http-equiv="refresh" content="1; url=./account_settings?action=changename">';

		}
			
		break;

//----SECTION 3----
//change contact info
		case changepref:
		
		//send a verification email if the link was clicked
		if(isset($_GET['required']) && $_GET['required'] == 'verificationemail') {
			$email = $account->EmailAddress;
			Account::sendVerificationEmail($email, $user_id);
			echo "<b>Verification email sent</b><br><br>";
		}

		echo "update contact info"; 
		$email = $account->EmailAddress;
		$phone = $account->PhoneNumber;
		$email_list = $account->email_list;
		$sms_list = $account->sms_list;
		$verified = $account->verified;



?>
		<form method = "post" action ="" name="contactinfo" id="contactinfo">
		<br>email: <input type= "text" name="email" id="email" value="<?php echo $email; ?>">

		<br>Phone Number: <input type= "text" name="phone" id="phone" value="<?php echo $phone; ?>">

		<br><br>Receive email updates<input type="checkbox" name="email_list" id="email_list" <?php if($email_list == 1) {
		echo "checked";
		} ?>>

		<br><br>Receive phone updates<input type="checkbox" name="sms_list" id="sms_list" <?php if($sms_list == 1) {
		echo "checked";
		} ?>>

		<br><input type="submit" name="updatecontact" id="updatecontact" value="update contact info">

<?php

		if(isset($email) && $verified != 1) {
			echo "<br>verify your email address. <a href='account_settings.php?action=changepref&required=verificationemail'>Click here for another verification email</a><br>";
		}

		if(isset($_POST['updatecontact'])){
			foreach($_POST as $field => $value){
			$$field = strip_tags(trim($value));
			}
		$newemail = $_POST['email'];
		$newphone = $_POST['phone'];
		$email = $account->EmailAddress;
	
			if(!empty($_POST['email'])) {

		
				if(!preg_match("/^.+@.+\\..+$/",$_POST['email']))
				{
				echo "not a valid email";
				include "./layout/layout-bottom.php";
				exit;
				}


			} else {

				if(!empty($_POST['email_list'])) {
				echo "<br>you can't be on the email list without an email";
				include "./layout/layout-bottom.php";
				exit;
				}

			}


			if(!empty($_POST['phone'])) {

				if(!preg_match("/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/",$_POST['phone'])) {
				echo "<br><br>not a valid phone number";
				include "./layout/layout-bottom.php";
				exit;
				}



			} else {

				if(!empty($_POST['sms_list'])) {
				echo "<br><br>you can't receive phone updates without inserting a phone number";
				include "./layout/layout-bottom.php";
				exit;
				}

			}


	
			if($_POST['email_list']) {
			$email_list_update = 1;

			}

			else {
			$email_list_update = 0;

			}

			if($_POST['sms_list']) {
	
			$phone_list_update = 1;

			}

			else {
	
			$phone_list_update = 0;
			}

			if ($newemail != $email) {
				$account->update(verified, 0);
				$account->update(EmailAddress,$newemail);
				Account::sendVerificationEmail($newemail, $user_id);
				echo "Please wait while we send a verification email<br>";
			}
			$account->update(PhoneNumber,$newphone);
			$account->update(email_list,$email_list_update);
			$account->update(sms_list,$phone_list_update);
			//header('Location: account_settings');
			echo "<br>record updating";
			echo '<meta http-equiv="refresh" content="1; url=./account_settings?action=changepref">';

		}


		break;	

//----SECTION 4----
//change your 'about me'

		case changeabout:

		//if the record has been changed then we'll put a note to say that it was successfully changed
		if(isset($_GET['change']) && $_GET['change'] == 'success') {
			echo "<b>your profile has been successfully changed</b><br><br>";
		}

		$about = $account->about;
		echo "write about yourself"; ?>
		<form method = "post" action = "" name="about" id="about">
		<textarea name = "abouttxt" id="abouttxt" rows="10" cols="80"><?php echo $about; ?></textarea>


		<br><input type="submit" name="changeabout" id="changeabout" value="Update!">

<?php

			if(isset($_POST['changeabout'])){
				$aboutme = $_POST['abouttxt'];
				$account->update(about,$aboutme);
				echo "<br>updating...<br><br>";
				echo '<meta http-equiv="refresh" content="0; url=./account_settings?action=changeabout&change=success">';
			
			}

		break;

//----SECTION 5----
//Update your profile pic

		case profilepic:
		$profile_pic = $account->Profile_Pic;
		echo "change your profile pic"; ?>

		<form method = "POST" action = "" name="updateimage" id="updateimage" enctype="multipart/form-data">
		<input type="file" name="image"><br>
		<input type="submit" name="profilepic" id="profilepic" value="update your profile picture">
		</form>

<?php

			if(isset($_POST['profilepic'])) {



			$errors = array();
			$allowed_e = array('png', 'jpg', 'jpeg', 'gif');

			$file_name = $_FILES['image']['name'];
			$file_e = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_s = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];

				if(in_array($file_e, $allowed_e) === false) {
				$errors[] = 'This file extension is not allowed';


				}

				if($file_s > 10485760) {
				$errors[] = 'File must be under 10mb';

				}


				if(empty($errors)){

					$username = $_SESSION['Username'];
	
					move_uploaded_file($file_tmp, './user/profile_pics/'.$username);

					$image_up = './user/profile_pics/'.$username;
					unlink($profile_pic);
					$account->update(Profile_pic,$image_up);
					
					echo "successfully updated your profile pic";
					
					


				} else {
					foreach($errors as $error) {
						echo $error, '<br>';
					}	
				}

			}
		break;

//----SECTION 6----
//View your orders
		

		case orders:
			$db = new Database();
			$stmt = $db->prepare("SELECT OrderNumber FROM orders WHERE UserID = ?");
			$stmt->bindParam(1,$user_id);
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $orders) {
				$order = new Order($orders['OrderNumber']);
				$items = $order->getOrderItems();
					echo "<center><table><tr style='text-align: center'><td>order number</td><td>name</td><td>price</td><td>quantity</td><td>posted?</td>";

					foreach($items as $item) {
					$prodid = $item->ProdID;
					$quantity = $item->quantity;
					$posted = $item->posted;
					$posted == 0 ? $posted = 'no' : $posted = 'yes';
						$item_details = new Shop();
						$item_details->getInfo($prodid);
						$item_name = $item_details->name;
						$item_price = $item_details->price;
					echo "<tr style='text-align: center'><td>" . $orders['OrderNumber'] . "</td><td>" . $item_name . "</td><td>" . $item_price . "</td><td>" . $quantity . "</td><td>" . $posted . "</td>";
				}
				echo "</table></center>"; 
				
			}

	}

?>

</div>
<div id="related">
<!-- We will display the number of new messages here -->
<h2<?php $messages = new Messages();
	//here I am displaying the number of unread messages. I will add a class to display the number in red if it's positive
	if($messages->numberOfUnseen() == 0) {
	echo ">Messages: (" . $messages->numberOfUnseen() .") New</h2>"; 
	} else {
	echo " class='headline-red'>Messages: (" . $messages->numberOfUnseen() .") New</h2>";
	}

	//this is pretty straight forward. Getting the last message and displaying it safely
	$messages->getLast();
	if(isset($messages->message)) {
			$pmBody = htmlspecialchars($messages->message);
			$bbparse = new bbParser();
			$pmBody = $bbparse->getHtml($pmBody);
		echo "<i>" . $pmBody . "</i><br>from <a href='./user_profile?username=" . $messages->sender . "'>@" . $messages->sender . "</a>";
	} else {
		echo "no new messages";
	}

?>
	<br><br><a href="./messages"><span class="button">view all messages</span></a>

</div>

<?php

} else {
echo "no account detected";
}

include "./layout/layout-bottom.php";
?>
<!-- script to make the content div full length on bigger screens -->
<script type='text/javascript' src='./jquery/content_full_length.js'></script>

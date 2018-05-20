<?php
	if(isset($_POST['contact-submit'])) {


//form validation

		foreach($_POST as $field => $value) {
			if($field != 'contact-address2' && $field != 'contact-collect') {

				if(empty($value)) {
					$blanks[] = $field;
				} else {
					$good_data = htmlspecialchars($value);
				}

			}

		}	

//if blanks have been left where they shouldn't be. This is how it is sorted server side (client side in javascript)	

		if (isset($blanks)){
			$fill_in_blanks_message = "Please insert info into the blank fields <a href='./checkout?page=contact'>back</a>";

			echo $fill_in_blanks_message;
			extract($good_data);
			include "./layout/layout-bottom.php";
			exit();
		}

//server side checking the fields contain the right characters

		foreach($_POST as $field => $value) {

			if(!empty($value)) {

				if(preg_match("/contact-phone/i",$field)) {
					if(!preg_match("/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/",$value)) {
					$errors[] = "$value is not a valid phone number";
					}
				}

				if(preg_match("/contact-email/i",$field)) {
					if(!preg_match("/^.+@.+\\..+$/",$value)){
						$errors[] = "$value is not a valid email";
					}

				}

			}

		}

//error message for incorrect fields
		foreach($_POST as $field => $value){
		$$field = htmlspecialchars($value);
		}
		if(@is_array($errors)){
		$message2 = "";
			foreach($errors as $value){
			$message2 .= $value." Please try again <br>";
			}
		echo $message2 . " <a href='./checkout?page=contact'>back</a>";
		include "./layout/layout-bottom.php";
		exit();
		}

//no errors or blanks means that the form can be put into an array and then a cookie while the page redirects :)

		$contact_details = array(
			name => $_POST['contact-name'],
			email => $_POST['contact-email'],
			phone => $_POST['contact-phone'],
			address_line_1 => $_POST['contact-address1'],
			address_line_2 => $_POST['contact-address2'],
			town => $_POST['contact-town'],
			city => $_POST['contact-city'],
			post_code => $_POST['contact-postcode'],
			collect => $_POST['contact-collect']);

	setcookie('duffa_shop_contact_details', json_encode($contact_details), time()+3600*24*3, "/duffa/");

	header('Location: ./checkout?page=confirmandpay');

	//alternatively use 	echo '<meta http-equiv="refresh" content="0; url=./checkout?page=confirmandpay">'; if header redirects aren't working
	}

?>

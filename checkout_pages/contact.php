<?php

//first check if the correct cookies exist to continue

	if(!isset($_COOKIE['duffa_shop'])) { header('location: ./shop'); }

	if($_SESSION['LoggedIn'] != 1) {
		echo " Please Login or Register to proceed";
	} else {

//if the form has been filled in before but needs to be changed, populate the previously entered values into the fields

		if(isset($_COOKIE['duffa_shop_contact_details'])) {
			$contact_info = json_decode($_COOKIE['duffa_shop_contact_details'], true);
			$contact_info['collect'] == 'on' ? $collect = "checked" : $collect = "";
		}

//the form itself


		echo "Username: " . $_SESSION['Username'];
		?> <br><form name="contact_details" id="contact_details" method="post" action="checkout?page=contact_form">
			Name: <input type="text" id="contact-name" name="contact-name" value="<?php echo $contact_info['name']; ?>"> <br>
			Email: <input type="text" id="contact-email" name="contact-email" value="<?php echo $contact_info['email']; ?>"> <br>
			Phone:  <input type="text" id="contact-phone" name="contact-phone" value="<?php echo $contact_info['phone']; ?>"> <br>
			Address Line 1:  <input type="text" id="contact-address1" name="contact-address1" value="<?php echo $contact_info['address_line_1']; ?>"> <br>
			Address Line 2: <input type="text" id="contact-address2" name="contact-address2" value="<?php echo $contact_info['address_line_2']; ?>"> <br>
			Town:  <input type="text" id="contact-town" name="contact-town" value="<?php echo $contact_info['town']; ?>"> <br>
			City:  <input type="text" id="contact-city" name="contact-city" value="<?php echo $contact_info['city']; ?>"> <br>
			Post Code:  <input type="text" id="contact-postcode" name="contact-postcode" value="<?php echo $contact_info['post_code']; ?>"> <br><br>

			Collect in person <input type="checkbox" id="contact-collect" name="contact-collect" <?php echo $collect ?>><br>

			<br><input type="submit" id="contact-submit" name="contact-submit" value="submit">

			</form>


<script>
//javascript for client side form validation

$(document).ready(function() {

	function validatePhone(phone) {

		var phoneRegex = /^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/;
		return phoneRegex.test(phone);

	}

	function validateEmail(email) {

		var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return emailRegex.test(email);

	}


	function validate() {

	validated = true;

		if($("#contact-name").val() == "") {

			$("#contact-name").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-name").css("border","");

		}

		if($("#contact-email").val() == "") {

			$("#contact-email").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-email").css("border","");

		}

		if($("#contact-phone").val() == "") {

			$("#contact-phone").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-phone").css("border","");

		}

		if($("#contact-address1").val() == "") {

			$("#contact-address1").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-address1").css("border","");

		}

		if($("#contact-town").val() == "") {

			$("#contact-town").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-town").css("border","");

		}

		if($("#contact-city").val() == "") {

			$("#contact-city").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-city").css("border","");

		}

		if($("#contact-postcode").val() == "") {

			$("#contact-postcode").css("border","2px solid red");
			validated = false;

		} else {
			$("#contact-postcode").css("border","");

		}



		var phone = $("#contact-phone").val();
		var email = $("#contact-email").val();

		if(!validateEmail(email)) {
			$("#contact-email").css("border","2px solid red");
			return false;

		} else {
			$("#contact-email").css("border","");

		}

		if(!validatePhone(phone)) {
			$("#contact-phone").css("border","2px solid red");
			return false;

		} else {
			$("#contact-phone").css("border","");

		}
	if(validated == false) return false;


	}

$("form").bind("submit", validate);

});

</script>



<?php
	}

?>
</div>

<div id="related">
<?php

	if (empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') {
		$_SESSION['cart'] = $_COOKIE['duffa_shop'];
	}

	$cart = json_decode($_SESSION['cart'], true);

	echo "your cart consists of: <br><br>";

	$total = 0;

	for($i=0;$i<count($cart);$i++) {

		echo $cart[$i]['pname'] . " (x" . $cart[$i]['quantity'] . ") : £" .  money_format('%i', ($cart[$i]['price'] * $cart[$i]['quantity'])) . "<br>";
		$total += ($cart[$i]['price'] * $cart[$i]['quantity']);
		//setlocale(LC_MONETARY, 'en_GB');
		//setlocale can add GBP which screws with iterations on some servers.
		$total = money_format('%i', $total);

	}
	echo "<br>your subtotal is: £" . $total . "<br><br><a href='./shop'>Click to change your order</a>";

?>

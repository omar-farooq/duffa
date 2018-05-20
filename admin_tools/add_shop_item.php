<?php
include "../php/config.php";

//this file is about processing the form for adding items to the shop in the admin page. 
//The script checks that the image has the correct extension, is under 10mb and doesn't already exist.

//create temporary session variables in case the person submitting fails the checks and needs to go back.
//This will make sure that the form fields are filled in if you need to go back.

$_SESSION['addToShopForm']['name'] = $_POST['new-prod-name'];
$_SESSION['addToShopForm']['price'] = $_POST['new-prod-price'];
$_SESSION['addToShopForm']['postage'] = $_POST['new-prod-shipping'];
$_SESSION['addToShopForm']['quantity'] = $_POST['new-prod-quantity'];
$_SESSION['addToShopForm']['description'] = $_POST['new-prod-description'];



//check if logged in first
if(!isset($_SESSION['LoggedIn'])) { echo "you need to log in again as your session has expired"; die(); }

//check admin credentials
if($_SESSION['user_level'] != '1') { echo "unauthorized access"; die(); }


$submitter = $_SESSION['userid'];


//make variables of the posted form items
$name = strip_tags(trim($_POST['new-prod-name']));
$price = $_POST['new-prod-price'];
$shipping = $_POST['new-prod-shipping'];
$quantity = $_POST['new-prod-quantity'];
$description = strip_tags(trim($_POST['new-prod-description']));



			//get all errors where the submitted data doesn't match
			$errors = array();
			$allowed_e = array('png', 'jpg', 'jpeg', 'gif');

			$file_name = $_FILES['image']['name'];
			$file_e = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_s = $_FILES['image']['size'];
			$file_tmp = $_FILES['image']['tmp_name'];

				if(empty($name) || empty($description)) {
					$errors[] = 'You must have a name and a brief description';
				}

				if(!preg_match("/^\d+(\.\d{1,2})?$/",$price)) {
					$errors[] = 'This format is not right for the price';
				}

				if(!preg_match("/^\d+(\.\d{1,2})?$/",$shipping)) {
					$errors[] = 'This format is not right for the shipping';
				}

				if(!preg_match("/^\d+$/",$quantity)) {
					$errors[] = 'Enter an integer for the quantity';
				}

				if(in_array($file_e, $allowed_e) === false) {
					$errors[] = 'This file extension is not allowed';


				}

				if($file_s > 10485760) {
					$errors[] = 'File must be under 10mb';

				}

				if(file_exists('../news_images/'.$file_name)){
					$errors[] = 'File name already exists';
				}


				if(empty($errors)){
					//there are no errors in this case so we will upload the image and add the submitted data to the database
	
					move_uploaded_file($file_tmp, '../shop_images/'.$file_name);

					$image_up = './shop_images/'.$file_name;		
					
					$db = new Database();
					$stmt = $db->prepare("INSERT INTO shop(name, price, postage, submitterID, quantity, image, description) VALUES (?,?,?,?,?,?,?)");
					$stmt->bindParam(1,$name);
					$stmt->bindParam(2,$price);
					$stmt->bindParam(3,$shipping);
					$stmt->bindParam(4,$submitter);
					$stmt->bindParam(5,$quantity);
					$stmt->bindParam(6,$image_up);
					$stmt->bindParam(7,$description);
					
					$stmt->execute();  

					unset($_SESSION['addToShopForm']);

					echo "Added to the shop. <a href='../shop'>click here to go to the shop</a><br>
						<a href='../admin'>click here to go back to the admin page</a>";


				} else {
				foreach($errors as $error) {
					//We can list all error messages here so you can go back and correct them
					echo $error . "<br>"; }
					echo "Go back and try again";
				} 

?>

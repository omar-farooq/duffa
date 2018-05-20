<?php
//this file is about processing the news form in the admin page. 
//The script checks that the image has the correct extension, is under 10mb and doesn't already exist.

include "../php/config.php";

//create temporary session variables in case the person submitting fails the checks and needs to go back.
//This will make sure that the form fields are filled in if you need to go back.

$_SESSION['addArticleForm']['title'] = $_POST['article_name'];
$_SESSION['addArticleForm']['description'] = $_POST['article_description'];
$_SESSION['addArticleForm']['article'] = $_POST['article'];

//check if logged in first
if(!isset($_SESSION['LoggedIn'])) { echo "you need to log in again as your session has expired"; die(); }

//check admin credentials
if($_SESSION['user_level'] != '1') { echo "unauthorized access"; die(); }

$submitter = $_SESSION['userid'];
$title = $_POST['article_name'];
$description = $_POST['article_description'];
$article = $_POST['article'];


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

				//this error was initially in place but I decided that too many conflicting generic names can come up.
				//I've kept this in case a change is desired.
				/*if(file_exists('../news_images/'.$file_name)){
				$errors[] = 'File name already exists';
				}*/


				if(empty($errors)){

					//if the file exists then we must create a new name for it. I will try adding a [0] on the end first.

					if(file_exists('../news_images/'.$file_name)){

						$i = 0;

						$file = explode('.',$file_name);
						$file_name = $file[0]. '[' . $i . ']' . '.' .$file[1];

						//if the file still exists with a 0 then the thing can loop until it finds a suitable number
						while(file_exists('../news_images/'.$file_name)) {

						$file = explode('.',$file_name);
						$file_prefix = explode('[' . $i . ']',$file[0]);
						$file_name = $file_prefix[0]. '[' . ($i + 1) . ']' . '.' .$file[1];

						$i++;
						}
					} 
	
					move_uploaded_file($file_tmp, '../news_images/'.$file_name);

					$image_up = './news_images/'.$file_name;
					
					$db = new Database();
					$stmt = $db->prepare("INSERT INTO news(SubmitterID, title, description, image, article, upload_time) VALUES (?,?,?,?,?,NOW())");
					$stmt->bindParam(1,$submitter);
					$stmt->bindParam(2,$title);
					$stmt->bindParam(3,$description);
					$stmt->bindParam(4,$image_up);
					$stmt->bindParam(5,$article);
					$stmt->execute(); 
			
					unset($_SESSION['addArticleForm']);
					header("Location: ../news");

				} else {
				foreach($errors as $error) {
					//We can list all error messages here so you can go back and correct them
					echo $error . "<br>"; }
					echo "Go back and try again";
				}

//Omar Farooq

?>

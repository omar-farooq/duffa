<?php
//this file is about processing the ajax image upload form in the reviews.php page. 
//The script first checks that the image has the correct extension, is under 10mb and doesn't already exist.
//On success, the file is added to the user submitted gallery folder and a link is added to the database and sent back to the original request.

include "../../php/config.php";

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

				/*if(file_exists('../../gallery/user_submitted/'.$file_name)){
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
	
					move_uploaded_file($file_tmp, '../../gallery/user_submitted/'.$file_name);

					$image_up = './gallery/user_submitted/'.$file_name;
					$gallery = new Gallery();
					$gallery->userSubmittedImage($image_up);
					
					//echo "<img src='" . $image_up . "'>";
					//we are using the below as I have gone with BB Code to parse to HTML
					echo "[img]" . $image_up . "[/img]";


				} else {
				foreach($errors as $error) {
				echo $error;}
				}

?>

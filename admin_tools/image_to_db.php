<?php
//this file is to get the image via the ajax post from upload_to_duffa_gallery.js, check it's an image, rename it if necessary, post it to the database and pass details of the errors and images back.

include "../php/config.php";

$output = '';  
$error = '';
if(isset($_FILES['file']['name'][0]))  {

	foreach($_FILES['file']['name'] as $keys => $iname)  {

		$errors = array();
		$allowed_e = array('png', 'jpg', 'jpeg', 'gif');

		$file_e = strtolower(pathinfo($iname, PATHINFO_EXTENSION));

		if(in_array($file_e, $allowed_e) === false) {
			$error .= $iname . " is not a . png, jpg, jpeg or gif <br>";
		}

		else   {

			if(file_exists('../gallery/duffa/'.$iname)){

				$i = 0;

				$file = explode('.',$iname);
				$iname = $file[0]. '[' . $i . ']' . '.' .$file[1];

				//if the file still exists with a 0 then the thing can loop until it finds a suitable number
				while(file_exists('../gallery/duffa/'.$iname)) {

				$file = explode('.',$iname);
				$file_prefix = explode('[' . $i . ']',$file[0]);
				$iname = $file_prefix[0]. '[' . ($i + 1) . ']' . '.' .$file[1];

				$i++;
				}
			} 

			move_uploaded_file($_FILES['file']['tmp_name'][$keys], '../gallery/duffa/' . $iname);
			$image_for_db = './gallery/duffa/' . $iname;
			$gallery = new Gallery();
			$gallery->insertDuffaImage($image_for_db);

			$output .= '<div><img src="./gallery/duffa/'.$iname.'" class="img-responsive" /></div>';  
		}  
	}  
}  




$std = new stdClass();
$std->image = $output;
$std->error = $error;
echo json_encode($std);
?>  

<?php
include "../../php/config.php";
if ($_POST['task'] == 'search_user') {
	$string = $_POST['string'];
	$string = explode(" ", $string);

	$users = new Users();
	$users->searchFor($string[0], $string[1]);

}

?>

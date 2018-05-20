<?php

//The shop.js file in /ajax/jquery posts to here and the updated information is relayed back.

include "../../php/config.php";

if(isset($_POST['task']) && $_POST['task'] == 'add') {

	$id = $_POST['id'];
	$quantity = $_POST['quantity'];

	$shop = new Shop();

	$shop->getInfo($id);
	$name = $shop->name;
	$price = $shop->price;
	$image = $shop->image;
	$postage = $shop->postage;
	$max_quantity = $shop->quantity;

	if($_SESSION['cart'] != "") {

		$cart = json_decode($_SESSION['cart'], true);
		$found = false;


		for($i=0; $i<count($cart); $i++) {

			if($cart[$i]['id'] == $id) {
				($cart[$i]['quantity'] + $quantity > $max_quantity) ? $cart[$i]['quantity'] = $max_quantity : $cart[$i]['quantity'] = $cart[$i]['quantity'] + $quantity;
				$found = true;
				break;
			}

		}	
	
		if(!$found) {

			if ($quantity > $max_quantity) $quantity = $max_quantity;

			$line = new stdClass;
			$line->id = $id;
			$line->pname = $name;
			$line->quantity = $quantity;
			$line->price = $price;	
			$line->postage = $postage;	
			$line->picture = $image;
			$cart[] = $line;
	

		}	
		$_SESSION['cart'] = json_encode($cart);

	} else {

		

		$line = new stdClass();
			$line->id = $id;
			$line->pname = $name;
			$line->quantity = $quantity;
			$line->price = $price;	
			$line->postage = $postage;	
			$line->picture = $image;
			$cart[] = $line;

		$_SESSION['cart'] = json_encode($cart);

	}

setcookie('duffa_shop', json_encode($cart), time()+3600*3*24, "/duffa/");
echo json_encode($cart);
	
}



if(isset($_POST['task']) && $_POST['task'] == 'remove') {

	$remove_quantity = $_POST['remove_quantity'];
	$remove_id = $_POST['remove_id'];

	$cart = json_decode($_SESSION['cart'], true);

	foreach($cart as $item => $property) {

		if($property['id'] == $remove_id) {

			if($cart[$item]['quantity'] - $remove_quantity < 1) {
				unset($cart[$item]);
				$cart = array_values($cart);
			} else {
				$cart[$item]['quantity'] = $cart[$item]['quantity'] - $remove_quantity;
			}

		}

	}	
	$_SESSION['cart'] = json_encode($cart);

setcookie('duffa_shop', json_encode($cart), time()+3600*24*3, "/duffa/");
echo json_encode($cart);

}

?>

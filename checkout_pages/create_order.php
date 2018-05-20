<?php

//check first if there are any irregularities between the requested quantity and the shop's stock levels

$items = $_SESSION['order']['items'];

$quantityMatch = 'true';

foreach ($items as $key => $value) {
	$ProdID = $items[$key]['product_id'];
	$quantity = $items[$key]['product_quantity'];

	$shop = new Shop($ProdID);
	$quantityDifference = $shop->quantity - $quantity;
	
	if($quantityDifference < 0) {
		$quantityMatch = 'false';
	}
}

//If the requested quantity doesn't match available stock then end the script
if($quantityMatch == 'false') {
	echo "The quantities you have requested do no match the stock levels we have in the shop, therefore we are currently unable to process your payment.<br><br> No payment has been made";
	die();
}


//if the person isn't logged in then end the script until they log in.
if($_SESSION['LoggedIn'] != 1) {
	echo 'Please log in to complete the order. No payment has been made yet.';
	die();
} else {

	if(!isset($_COOKIE['duffa_shop_contact_details'], $_SESSION['order'])) {
		echo "your shopping session has expired. Please make sure that cookies are enabled and try again. Payment has not yet been made.";
	} else {

		require './PayPal-PHP-SDK/autoload.php';

		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
			'',
			''
			)
		);

		if (!isset($_GET['success'], $_GET['paymentId'], $_GET['PayerID'])) {
			echo "There was an error. This page has not been accessed correctly. Payment has not been made";
			die();
		}

		if ((bool)$_GET['success'] === false) {
			echo "There was an error. Payment has not been made";
			die();
		}

		$paymentId = $_GET['paymentId'];
		$PayerID = $_GET['PayerID'];

		$payment = \PayPal\Api\Payment::get($paymentId, $apiContext);

		$execute = new \PayPal\Api\PaymentExecution();
		$execute->setPayerId($PayerID);

		try {
			$result = $payment->execute($execute, $apiContext);
			echo 'Payment made, thanks';

			//once payment is made then everything can be inserted into the database
			$contact_details = json_decode($_COOKIE['duffa_shop_contact_details'], true);
			$items = $_SESSION['order']['items'];

			$name = $contact_details['name'];
			$email = $contact_details['email'];
			$phone = $contact_details['phone'];
			$address1 = $contact_details['address_line_1'];
			$address2 = $contact_details['address_line_2'];
			$town = $contact_details['town'];
			$city = $contact_details['city'];
			$postcode = $contact_details['postcode'];
			$contact_details['collect'] == 'on' ? $collect = '1' : $collect = '0';
			$postage = $_GET['shp'];

			$orders = new Order();
			$OrderNumber = $orders->createOrder($name, $email, $phone, $address1, $address2, $town, $city, $postcode, $collect, $postage);

			$Payment = new Payment();
			$Payment->paid($OrderNumber, $paymentId, $PayerID);

			foreach ($items as $key => $value) {
				$ProdID = $items[$key]['product_id'];
				$quantity = $items[$key]['product_quantity'];
				$orders->orderItems($OrderNumber, $ProdID, $quantity);

				$shop = new Shop($ProdID);
				$newShopQuantity = $shop->quantity - $quantity;
				//update the database to reflect the new quantity after each product has been purchased
				if($newShopQuantity < 1) {
					$shop->deleteItem($ProdID, $shop->image);
				} else {
					$shop->updateQuantity($ProdID, $newShopQuantity);
				}

			}

			echo "Your order has been placed. Your order number is: " . $OrderNumber . ". You can view your order <a href='./account_settings?unset=true'>here</a> and you will be redirected there in a moment";

			//take the user to account settings and unset the sessions/shop cookie
			echo '<meta http-equiv="refresh" content="5; url=./account_settings?unset=true">';


		} catch (Exception $e) {
			$data = json_decode($e->getData());
			echo $data->message;
		}
	}
}

?>

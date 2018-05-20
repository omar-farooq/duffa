<?php

//retrieve information about the user's contact details and their cart from the cookies
$contact_info = json_decode($_COOKIE['duffa_shop_contact_details'], true);

$shop = json_decode($_COOKIE['duffa_shop'], true);

//confirm their contact details first
echo "Confirmation of details: <br><br>

	name: " . $contact_info['name'] . "<br> email: " . $contact_info['email'] . "<br> address: " . $contact_info['address_line_1'] . "<br>" . $contact_info['address_line_2'] . "<br>" . $contact_info['town'] . "<br>" . $contact_info['city'] . "<br>" . $contact_info['post_code'] . "<br><br> <a href='./checkout?page=contact'>Click here to go back and change your contact details</a><br><br>";

//create an array with the final selection to be sent to Paypal
$_SESSION['order']['items'] = array();

//get the cart items from the 'duffa_shop' cookie and total it up.
$subtotal = 0;
$postage = 0;
foreach ($shop as $key => $value) {

	//we will get the price of the item server side
	$shop_items = new Shop($shop[$key]['id']);
	if($shop_items->ProdID == "") {
		return false;
	} else {


		$item_id = $shop[$key]['id'];
		$item_price = $shop_items->price;
		$item_postage = $shop_items->postage;
		$product_name = $shop[$key]['pname'];
		$quantity = $shop[$key]['quantity'];

		//display the information to the user about what they will be purchasing
		echo $shop[$key]['pname'] .  " (x" . $shop[$key]['quantity'] . ") <br>£" . money_format('%i',($item_price * $shop[$key]['quantity']))  . "<br><br>";
		$_SESSION['order']['items'][] = array(
			'product_id' => $item_id,
			'product_name' => $product_name,
			'product_price' => $item_price,
			'product_quantity' => $quantity
		);

		$subtotal += money_format('%i',($item_price * $shop[$key]['quantity']));
		$postage += money_format('%i',($item_postage * $shop[$key]['quantity']));

	}
	
}

$subtotal = money_format('%i',($subtotal));

if($contact_info['collect'] == "on") $postage = 0;

$total = money_format('%i',($subtotal + $postage));

echo "subtotal is £" . $subtotal . "<br>
postage is £" . $postage . "<br> 
Total is £" . $total . "<br> <br>";

$_SESSION['order']['subtotal'] = $subtotal;
$_SESSION['order']['postage'] = $postage;

echo "<a href='./checkout_pages/checkoutwithpaypal.php'>Click to pay with Paypal</a>";

?>

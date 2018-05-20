<?php 
require "../php/config.php";
require './PayPal-PHP-SDK/autoload.php';

$apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
	'',
	''
	)
);

if(!isset($_COOKIE['duffa_shop_contact_details'], $_COOKIE['duffa_shop'], $_SESSION['order'])) {
	echo "your session has expired. Please make sure that cookies are enabled and try again";
	die();
}

$finalSelection = $_SESSION['order']['items'];
$subtotal = $_SESSION['order']['subtotal'];
$shipping = $_SESSION['order']['postage'];

$total = $subtotal + $shipping;

$payer = new \PayPal\Api\Payer();
$payer->setPaymentMethod('paypal');

$allItems = array();

foreach($finalSelection as $key => $value) {

	$product = $finalSelection[$key]['product_name'];
	$price = $finalSelection[$key]['product_price']; 
	$quantity = (int) ($finalSelection[$key]['product_quantity']);

	$item = new \PayPal\Api\Item();
	$item->setName($product)
		->setCurrency('GBP')
		->setQuantity($quantity)
		->setPrice($price);

		$allItems[] = $item;	
}

$itemList = new \PayPal\Api\ItemList();
$itemList->setItems($allItems);

$details = new \PayPal\Api\Details();
$details->setShipping($shipping)
	->setSubtotal($subtotal);

$amount = new \PayPal\Api\Amount();
$amount->setCurrency('GBP')
	->setTotal($total)
	->setDetails($details);

$transaction = new \PayPal\Api\Transaction();
$transaction->setAmount($amount)
	->setItemList($itemList)
	->setDescription('PayForSomething Payment')
	->setInvoiceNumber(uniqid());

$redirectUrls = new \PayPal\Api\RedirectUrls();
$redirectUrls->setReturnUrl("https://omar.earth/duffa/checkout.php?page=create_order&success=true&shp=" . $shipping)
    ->setCancelUrl("https://omar.earth/duffa/checkout.php?page=confirmandpay&success=false");

$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale')
	->setPayer($payer)
	->setRedirectUrls($redirectUrls)
	->setTransactions([$transaction]);

try {
    $payment->create($apiContext);
    //echo $payment;

    $approvalUrl = $payment->getApprovalLink();
	header("Location: {$approvalUrl}");
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception.
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}

?>

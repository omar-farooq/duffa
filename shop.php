<?php 
$banner_text = 'Shop';
$banner_image = './layout/random.jpg';
include './layout/layout-top2.php';
?> 

<style>
@media (min-width: 1001px) {
	body, #related {
		background-color: #f9f9f9;
	}

	#related:after {
		box-shadow: none;
	}

	#related {
		padding-top: 20px;
	}

	#footertop {
		background-color: #fffc;
		color: #484848;
	}
}

@media (max-width: 1000px) {
	#related {
		display: block;
	}	

}
</style>

<!-- content goes here -->

	<?php $shop = new Shop();
		$shop->open();

	?>



			</div>

<!-- SIDEBAR CONTENT -->
			<div id="related">
				<h3 class="desktop">Cart</h3>

					<div id="cart" class="desktop">

						<?php  //new customer
							 if ((empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') && !isset($_COOKIE['duffa_shop'])) {

								echo "Your cart is currently empty!";
								$number_of_items = 0;

							} else {
								//has put stuff in their cart but went away from the site and came back
								if ((empty($_SESSION['cart']) || $_SESSION['cart'] == '[]' || $_SESSION['cart'] == 'null') && isset($_COOKIE['duffa_shop']) && $_COOKIE['duffa_shop'] != '[]') {
									$_SESSION['cart'] = $_COOKIE['duffa_shop'];
									$savedCart = json_decode($_COOKIE['duffa_shop'], true);


									echo "your cart consists of: <br><br>";

									$total = 0;
									$postage = 0;
									$number_of_items = 0;

									for($i=0;$i<count($savedCart);$i++) {

										echo $savedCart[$i]['pname'] . " (x" . $savedCart[$i]['quantity'] . ") : £" .  money_format('%i', ($savedCart[$i]['price'] * $savedCart[$i]['quantity'])) . " <span class='remove_wrapper'><input type='number' name='remove_quantity' class='remove_quantity' min='1' max='" . $savedCart[$i]['quantity'] . "'>" . "<span remove_id='" . $savedCart[$i]['id'] . "' class='remove-item'><img src='./icons/bin.png' class='bin-icon'></span> </span><br>";
										$total += ($savedCart[$i]['price'] * $savedCart[$i]['quantity']);
										
										$total = money_format('%i', $total);

										$postage += ($savedCart[$i]['postage'] * $savedCart[$i]['quantity']);
										
										$postage = money_format('%i', $postage);


										$number_of_items += $savedCart[$i]['quantity'];

									}
									echo "<br>your subtotal is: £" . $total . "<br>postage (optional) is " . $postage . "<br><br><a href='./checkout'>Proceed to Checkout</a>";
									


									


								}
									//cart has been emptied
								elseif((empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') && $_COOKIE['duffa_shop'] == '[]') {
									echo "Your cart is currently empty!";
									$number_of_items = 0;
								} else {
									//has been shopping continuously
				 					$cart = json_decode($_SESSION['cart'], true);

									echo "your cart consists of: <br><br>";

									$total = 0;
									$postage = 0;
									$number_of_items = 0;

									for($i=0;$i<count($cart);$i++) {

										echo $cart[$i]['pname'] . " (x" . $cart[$i]['quantity'] . ") : £" .  money_format('%i', ($cart[$i]['price'] * $cart[$i]['quantity'])) . " <span class='remove_wrapper'><input type='number' name='remove_quantity' class='remove_quantity' min='1' max='" . $cart[$i]['quantity'] . "'>" . "<span remove_id='" . $cart[$i]['id'] . "' class='remove-item'><img src='./icons/bin.png' class='bin-icon'></span> </span><br>";
										$total += ($cart[$i]['price'] * $cart[$i]['quantity']);
										
										$total = money_format('%i', $total);

										$postage += ($cart[$i]['postage'] * $cart[$i]['quantity']);
										
										$postage = money_format('%i', $postage);


										$number_of_items += $cart[$i]['quantity'];

									}
									echo "<br>your subtotal is: £" . $total . "<br>postage (optional) is " . $postage . "<br><br><a href='./checkout'>Proceed to Checkout</a>";
								}


							} 

						 ?>

					</div>

					<div id="accepted-payment-methods-related" class="desktop">
						<br>We currently accept:<br>
						<img src="./icons/paypal.png" width="40%">
					</div>

				<!-- FOR THE MOBILE VERSION OF THE SITE -->

					<div id="mobile-cart" class="mobile">

						<img src="./icons/cart.png" height="80px" width="80px">
						<span class="number-of-items-large"><?php echo $number_of_items; ?></span>

						<br>

						<a href="./cart"><button class="button">Click to view your cart</button></a>

					</div>
			</div>








<script src="./ajax/jquery/shop.js"></script>
<?php include './layout/layout-bottom.php'; ?>

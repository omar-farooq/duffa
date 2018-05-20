<?php 
$banner_text = 'Your Cart';
$banner_image = './layout/random.jpg';
include './layout/layout-top2.php';
?> 

<!-- content goes here -->

<?php   if ((empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') && !isset($_COOKIE['duffa_shop'])) {

		echo "<br>Your cart is currently empty!";
		echo "<br><br><a href='./shop' class='back-to-shop-link'>< go back to the shop</a><br><br>";

	} else {

		if (empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') {
			$_SESSION['cart'] = $_COOKIE['duffa_shop'];
		}

		if(empty($_SESSION['cart']) || $_SESSION['cart'] == '[]') {
			echo "<br>Your cart is currently empty!";
			echo "<br><br><a href='./shop' class='back-to-shop-link'>< go back to the shop</a><br><br>";
		} else {

			$cart = json_decode($_SESSION['cart'], true);

			echo "<br><br><a href='./shop' class='back-to-shop-link'>< go back to the shop</a><br><br>";

			$subtotal = 0;
			$postage = 0;

			echo "<ul id='mobile-cart' class='flex-list'>";

			for($i=0;$i<count($cart);$i++) {

				$item_info = new Shop();
				$item_info->getInfo($cart[$i]['id']);
				$item_image = $item_info->image;
				$item_postage = $item_info->postage;
				echo "<li><div class='product-container'>";
				echo"<div><img class='product-image' src='" . $item_image . "' width=250px height=250px></div>";

				echo $cart[$i]['pname'] . " (x" . $cart[$i]['quantity'] . ") : £" .  money_format('%i', ($cart[$i]['price'] * $cart[$i]['quantity'])) . " <br><span class='remove_wrapper'><input type='number' name='remove_quantity' class='remove_quantity' value='1' min='1' max='" . $cart[$i]['quantity'] . "'>" . "<span remove_id='" . $cart[$i]['id'] . "' class='remove-item'><img src='./icons/bin.png' class='bin-icon'></span> </span><br>";

				$subtotal += ($cart[$i]['price'] * $cart[$i]['quantity']);
				//setlocale(LC_MONETARY, 'en_GB');
				//note that setlocale works on some servers and can act screwy on others
				$subtotal = money_format('%i', $subtotal);

				$postage += ($item_postage * $cart[$i]['quantity']);
				//setlocale(LC_MONETARY, 'en_GB');
				$postage = money_format('%i', $postage);

				echo "</div></li>";


			}
			echo "</ul><br><span class='cart-total'>Subtotal : £" . $subtotal . "</span><br>
				<span class='cart-postage'>Postage (optional) : £" . $postage . "</span><br>";
			echo "<a href='./checkout'><button class='button'>Proceed to Checkout</button></a>";

			echo "<br><br>We currently accept:<br>
				<img src='./icons/paypal.png' width='25%'>";
		}


	}

 ?>
<script src='./ajax/jquery/cart.js'></script>
<?php include './layout/layout-bottom.php'; ?>

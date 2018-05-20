 <?php $page = $_GET['page'];

	$include_page = "./checkout_pages/" . $page . ".php";



	if($page != "") { 

		if(!file_exists($include_page)) {

			header('Location: ./shop.php');
		
		} else {
			$banner_text = 'Shop';
			$banner_image = './layout/random.jpg';
			include './layout/layout-top2.php';
			include($include_page);
		}

	} else {

		if(isset($_COOKIE['duffa_shop']) && isset($_COOKIE['duffa_shop_contact_details'])) {

			header('Location: ./checkout.php?page=confirmandpay');

		} 
		elseif(isset($_COOKIE['duffa_shop'])) {
			header('Location: ./checkout.php?page=contact');

		}

		else {

			header('Location: ./shop.php');

		}


	}


?>


<!-- content goes here -->



			</div>

<script src="./ajax/jquery/shop.js"></script>
<?php include './layout/layout-bottom.php'; ?>

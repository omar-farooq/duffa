<?php 
$banner_text = 'Admin';
$banner_image = './layout/banner_images/random4.jpg';
include "./layout/layout-top2.php"; 

if(!isset($_SESSION['LoggedIn'])) {
	echo 'access denied';
	include "./layout/layout-bottom.php";
	die();
}


if($_SESSION['user_level'] == 0) {
	echo 'access denied';
	include "./layout/layout-bottom.php";
	die();
}

?>

<link rel="stylesheet" href="./css/admin.css" type="text/css">

<?php

$_GET['action'] == "";

switch($_GET['action']) {

	case email:
		echo "send an email";
		?>
		<form method="post" action="" name="mailing-list-form" id="mailing-list-form">
		subject: <input type="text" name="subj" id="subj"><br>
		message: Hello [name] ...<br>
		<textarea name="email-message" id="email-message">edit message here</textarea><br>
		<input type="submit" id="send-mass-mail" value="send email" name="send-mass-mail">
		</form>

		<?php

		//note that this is just an example. For actually sending mass mails use PEAR

		if(isset($_POST['send-mass-mail'])) {
			$subj = $_POST['subj'];
			$mssg = $_POST['email-message'];
			$db = new Database();
			$stmt = $db->prepare("SELECT * FROM users WHERE email_list = '1' AND verified = '1'");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $row) {
				$email = $row->EmailAddress;
				$name = $row->FirstName . $row->LastName;
				$to = $email;
				$tailored_mssg = "Hello " . $name . "! " . $mssg;
				$headers = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n" . 'From: updates@duffa.org' . "\r\n";
				mail($to, $subj, $tailored_mssg, $headers);
			}

		}


	break;

	case update_schedule:

		//update sunday times first
		echo "update the schedule <br><br>

		<form method=\"post\" action=\"\" name=\"alterDateForm\" id=\"alterDateForm\">
		<select name=\"alterSunday\" id=\"alterSunday\">
		<option value=\"\">Choose a Sunday date</option>";

		$db = new Database();

			$stmt = $db->prepare("SELECT * FROM session WHERE SessionDate > NOW() AND DAYOFWEEK(SessionDate) = 1");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $upcoming) {
				$date = strtotime($upcoming->SessionDate);
				echo "<option value=\"" . $upcoming->SessionDate . "\">" . date( 'D d-m-Y ga', $date ) . "</option>";
			}
		echo "</select>

			<select name=\"sundayTime\" id=\"sundayTime\">
			<option value=\"\">Choose a time</option>
			<option value=\"13\">1pm</option>
			<option value=\"15\">3pm</option>
			<option value=\"17\">5pm</option>
			</select>
			<input type=\"submit\" value=\"change\" id=\"changeSun\" name=\"changeSun\" class=\"\">
			</form>
			 <br><br>";

		//choose a Thursday time
		echo "<form method=\"post\" action=\"\" name=\"alterDateForm2\" id=\"alterDateForm2\">
		<select name=\"alterThursday\" id=\"alterThursday\">
		<option value=\"\">Choose upcoming Thursday</option>";

		$db = new Database();

			$stmt = $db->prepare("SELECT * FROM session WHERE SessionDate > NOW() AND DAYOFWEEK(SessionDate) = 5");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach($result as $upcoming) {
				$date = strtotime($upcoming->SessionDate);
				echo "<option value=\"" . $upcoming->SessionDate . "\">" . date( 'D d-m-Y ga', $date ) . "</option>";
			}
		echo "</select>
			<select name=\"thursdayTime\" id=\"thursdayTime\">
			<option value=\"\">Choose a time</option>
			<option value=\"18\">6pm</option>
			<option value=\"21\">9pm</option>
			</select>
			<input type=\"submit\" value=\"change\" id=\"changeThu\" name=\"changeThu\" class=\"\">
			</form><br><br>";

		// When the first submit button has been hit
		if(isset($_POST['changeSun'])) {
			if($_POST['alterSunday'] != "" && $_POST['sundayTime'] != "") {
				$oldSunTime = $_POST['alterSunday']; //The old time that Sunday was at
				$formatOldTime = strtotime($oldSunTime); //The above old time formatted for PHP
				$oldHour = date( 'G', $formatOldTime); //The hour of the above old time
				$newSunTime = $_POST['sundayTime']; //The hour of the new time to be set
				$newSunTimeFormatted = date( 'Y-m-d', $formatOldTime) . " " . $newSunTime . ":00:00";
				$db = new Database();

				$stmt = $db->prepare ("SELECT * FROM session WHERE SessionDate >= ? AND DAYOFWEEK(SessionDate) = 1");
				$stmt->bindParam(1,$oldSunTime);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_OBJ);
					foreach($result as $session) {
						$dateTime = $session->SessionDate; //each date after selected date
						$sunDateTimeStrings = strtotime($dateTime); 
						$newFormattedDate = date( 'Y-m-d', $sunDateTimeStrings) . " " . $newSunTime . ":00:00";
							$stmt = $db->prepare ("UPDATE session SET SessionDate = ? WHERE SessionDate = ?");
							$stmt->bindParam(1,$newFormattedDate);
							$stmt->bindParam(2,$dateTime);
							$stmt->execute();
				
					}

				//next update the auto mysql schedule
				$stmt2 = $db->exec("ALTER EVENT duffa_session_sunday ON SCHEDULE EVERY 1 WEEK STARTS '2018-04-22 $newSunTime:00:00'");

				echo "every date on or after " . date( 'd/m', $formatOldTime) . " has now been set from " . $oldHour . ":00 to " . $newSunTime . ":00. Go to the schedule to confirm this";
			}
		}

		if(isset($_POST['changeThu'])) {
			if($_POST['alterThursday'] != "" && $_POST['thursdayTime'] != "") {
				
				$oldThuTime = $_POST['alterThursday']; //The old time that Thursday was at
				$formatOldTime = strtotime($oldThuTime); //The above old time formatted for PHP
				$oldHour = date( 'G', $formatOldTime); //The hour of the above old time
				$newThuTime = $_POST['thursdayTime']; //The hour of the new time to be set
				$newThuTimeFormatted = date( 'Y-m-d', $formatOldTime) . " " . $newThuTime . ":00:00";
				$db = new Database();

				$stmt = $db->prepare ("SELECT * FROM session WHERE SessionDate >= ? AND DAYOFWEEK(SessionDate) = 5");
				$stmt->bindParam(1,$oldThuTime);
				$stmt->execute();
				$result = $stmt->fetchAll(PDO::FETCH_OBJ);
					foreach($result as $session) {
						$dateTime = $session->SessionDate; //each date after selected date
						$thuDateTimeStrings = strtotime($dateTime); 
						$newFormattedDate = date( 'Y-m-d', $thuDateTimeStrings) . " " . $newThuTime . ":00:00";
							$stmt = $db->prepare ("UPDATE session SET SessionDate = ? WHERE SessionDate = ?");
							$stmt->bindParam(1,$newFormattedDate);
							$stmt->bindParam(2,$dateTime);
							$stmt->execute();
				
					}

				//next update the auto mysql schedule
				$stmt2 = $db->exec("ALTER EVENT duffa_session_sunday ON SCHEDULE EVERY 1 WEEK STARTS '2018-04-26 $newThuTime:00:00'");

				echo "every date on or after " . date( 'd/m', $formatOldTime) . " has now been set from " . $oldHour . ":00 to " . $newThuTime . ":00. Go to the schedule to confirm this";


			}
		}


	break;

	case add_news_article:
		echo "add a news article<br><br>

		Instructions as follow: <br>
		Fill in all fields. The article field accepts html and so to add content you must use html.<br>
		Once you hit preview, click on the image/description to preview the actual article itself.<br>
		Once satisfied hit submit<br>";?>

		<form method="post" action="./admin_tools/insert_news_article.php" id="create_article_form" enctype="multipart/form-data"><br>
		artcle tab title: <input type="text" id="article_name" name="article_name" required maxlength="10" value="<?php echo $_SESSION['addArticleForm']['title'] ?>"><br>
		brief description: <input type="text" id="article_description" name="article_description" value="<?php echo $_SESSION['addArticleForm']['description'] ?>"><br>
		relevant image:
		<input type="file" name="image" id="article_file" ><br>
		article:<br> <textarea id="article" name="article" style="width:300px; height:300px"><?php echo $_SESSION['addArticleForm']['article']?></textarea><br>
		<button type="button" value="preview" id="preview-article">preview</button>
		<input type="submit" value="submit" id="submit-article">
		</form>

		<div id="article-preview-pane">
			<div id="preview-news-holder">
				<img id="image-upload-preview" src="#" alt=""><br>
				<div id="preview-article-description"></div>
			</div>
			<div id="preview-article-title"></div>
			<div id="preview-article-text"></div>
		</div>

		<script>
			$(document).ready(function() {

					function imagePreview(input) {

					    if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function (e) {
						    $('#image-upload-preview').attr('src', e.target.result) .width(350);
						}

						reader.readAsDataURL(input.files[0]);
					    }
					}



				$('#preview-article').on('click',function() {

					$('#preview-news-holder').css('display','block');

					$('#preview-article-title').html("");
					$('#preview-article-text').html("");

					 $('#article_file').each(function() {
						imagePreview(this);
					});



					var previewD = $('#article_description').val();
					$('#preview-article-description').html( previewD );


					$('#preview-news-holder').on('click',function() {

						$('#preview-news-holder').css('display','none');

						//I messed up with the naming convention here a little bit (which you'll see). The title is actually the little tab on the news image on the news page. The 'description' is also the actual 'title' of the article.

						var previewTitle = $('#article_name').val();
						var previewArticle = $('#article').val();
					
						$('#preview-article-title').html("<h1>" + previewD + "</h1>");
						$('#preview-article-text').html( previewArticle );

					});
				});

			});

		</script>

		<?php
	break;

	case add_shop_item:
		echo "add items to the shop:<br><br>

			Select an existing item and the quantity to add (on top of what is already available)<br>"; ?>

		<div class='update-quantity'>

			Item:<br>

			<form method='post' action='' name='update-shop-quantity-form' id='update-shop-quantity-form'>
				<select name='update-shop-quantity-list' id='update-shop-quantity-list'>

			<?php
			//above is a drop down menu and below is a loop that will catch each shop element and add it as an option in the drop down

			$db = new Database();
			$stmt = $db->prepare ("SELECT * FROM shop");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach ($result as $item) {
				$ProdID = $item->ProdID;
				$name = $item->name;
				$price = $item->price;
				$quantity = $item->quantity;
				$description = $item->description;
				$image = $item->image;
				echo "<option value='" . $ProdID . "' quantity='" . $quantity ."'>" . $name . "</option>";
			
			}

			?>

				</select>


		</div>
		<!-- below is a span that will be updated when the drop down menu option changes. The script below the span is the jquery code to change the quantity when the drop down changes-->
		<div class='update-quantity'>
			quantity<br>

			<span class='current-quantity' id='current-quantity'>

			<script>
				$(document).ready(function() {

					var quantity = $(this).find(':selected').attr('quantity');
					$('#current-quantity').html(quantity);

					$('#update-shop-quantity-list').on('change', function() {

						var quantity = $(this).find(':selected').attr('quantity');
						$('#current-quantity').html(quantity);
		
					});
				});

			</script>

		</div>

		<div class="update-quantity">

			Add <br>

			<input id="additional-quantity" name="additional-quantity" type="number">

		</div>

		<input type="submit" value="add" id="submit-new-quantity" name="submit-new-quantity">
		</form>

		<?php

		if(isset($_POST['submit-new-quantity'])) {
			$itemID = $_POST['update-shop-quantity-list'];
			$Warehouse = new Shop();
			$Warehouse->getInfo($itemID);
			$currentQuantity = $Warehouse->quantity;
			$additionalQuantity = $_POST['additional-quantity'];
			$newQuantity = ($currentQuantity + $additionalQuantity);
			$Warehouse::updateQuantity($itemID, $newQuantity);
			echo "<meta http-equiv=\"refresh\" content=\"0\">";
		}

		?>

		<br><br>

		Insert a new item into the shop: <br><br>

		<form method="post" action="./admin_tools/add_shop_item.php" name="add-new-shop-item-form" id="add-new-shop-item-form" enctype="multipart/form-data">

			Name: <input type="text" name="new-prod-name" id="new-prod-name" required minlength="4" value="<?php echo $_SESSION['addToShopForm']['name']; ?>"> <br>

			Price: £ <input type="number" name="new-prod-price" id="new-prod-price" step="0.01" required minlength="0.01" value="<?php echo $_SESSION['addToShopForm']['price']; ?>"> <br>

			Postage (in UK): £ <input type="number" name="new-prod-shipping" id="new-prod-shipping" step="0.01" required minlength="0.01" value="<?php echo $_SESSION['addToShopForm']['postage']; ?>"> <br>

			Quantity: <input type="number" name="new-prod-quantity" id="new-prod-quantity" required minlength="1" value="<?php echo $_SESSION['addToShopForm']['quantity']; ?>"> <br>

			Description: <input type="text" name="new-prod-description" id="new-prod-description" required minlength="4" value="<?php echo $_SESSION['addToShopForm']['description']; ?>"> <br>

			Image:<input type="file" name="image" id="shopfile" onchange="readURL(this);"><br>


			<input type="submit" value="submit item" name="submit-new-shopt-item" id="submit-new-shopt-item">

		</form><br><br>

		<img id="image-upload-preview" src="#" alt="">

			<script type="text/javascript">
			//the javascript for previewing an image before uploading and inserting it
				function readURL(input) {
				    if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function (e) {
					    $('#image-upload-preview').attr('src', e.target.result) .width(350);
					}

					reader.readAsDataURL(input.files[0]);
				    }
				}
		   	 </script>

		<?php		


	break;


	case remove_shop_item:
		echo "remove items from the shop:<br><br>

			Select an existing item and the quantity to remove<br>

			Note: As of now, if all stock is removed then the items is completely removed from the site and you will have to readd it if you get more stock<br><br>"; ?>

		<div class='update-quantity'>

			Item:<br>

			<form method='post' action='' name='update-shop-quantity-form2' id='update-shop-quantity-form2'>
				<select name='update-shop-quantity-list2' id='update-shop-quantity-list2'>

			<?php
			//above is a drop down menu and below is a loop that will catch each shop element and add it as an option in the drop down

			$db = new Database();
			$stmt = $db->prepare ("SELECT * FROM shop");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach ($result as $item) {
				$ProdID = $item->ProdID;
				$name = $item->name;
				$price = $item->price;
				$quantity = $item->quantity;
				$description = $item->description;
				$image = $item->image;
				echo "<option value='" . $ProdID . "' quantity='" . $quantity ."'>" . $name . "</option>";
			
			}

			?>

				</select>


		</div>
		<!-- below is a span that will be updated when the drop down menu option changes. The script below the span is the jquery code to change the quantity when the drop down changes-->
		<div class='update-quantity'>
			quantity<br>

			<span class='current-quantity2' id='current-quantity2'>

			<script>
				$(document).ready(function() {

					var quantity = $(this).find(':selected').attr('quantity');
					$('#current-quantity2').html(quantity);
					$('#remove-quantity-amount').attr('max',quantity);

					$('#update-shop-quantity-list2').on('change', function() {

						var quantity = $(this).find(':selected').attr('quantity');
						$('#current-quantity2').html(quantity);
						$('#remove-quantity-amount').attr('max',quantity);
		
					});
				});

			</script>

		</div>

		<div class="update-quantity">

			Remove <br>

			<input id="remove-quantity-amount" name="remove-quantity-amount" type="number" max="">

		</div>

		<input type="submit" value="remove" id="submit-new-quantity2" name="submit-new-quantity2">
		</form>

		<?php

		if(isset($_POST['submit-new-quantity2'])) {
			$itemID = $_POST['update-shop-quantity-list2'];
			$Warehouse = new Shop();
			$Warehouse->getInfo($itemID);
			$itemImage = $Warehouse->image;
			$currentQuantity = $Warehouse->quantity;
			$removeQuantity = $_POST['remove-quantity-amount'];
			$newQuantity = ($currentQuantity - $removeQuantity);

			if ($newQuantity > 0) {
				$Warehouse::updateQuantity($itemID, $newQuantity);
				echo "<meta http-equiv=\"refresh\" content=\"0\">";
			} else {
				$Warehouse::updateQuantity($itemID, 0);
				$Warehouse::deleteItem($itemID, $itemImage);
				echo "<meta http-equiv=\"refresh\" content=\"0\">";
			}
		}
	break;


	case upload_images:
		?>
		<div class="image-upload-container" align="center">
			<input type="file" name="file[]" id="file" multiple>
			<h3 class="text-center">Drag and Drop images</h3>

			<div class="file_drag_area" id="upload-area">
				Click<br>or<br>
				Drop Files Here
				<br>wait for the ready signal before dropping
			</div>
			<div id="upload-errors">Errors:<br></div>
			<span class="additions-text">Latest Additions <br></span>
			<div id="uploaded_file"></div>	
		</div>

		<script src="./admin_tools/upload_to_duffa_gallery.js"></script>

	<?php

	break;

	case orders:

		//for view the details of an order

		if(isset($_GET['orderid'])) {
			$orderid = $_GET['orderid'];
			$order = new Order($orderid);
			$result = $order->getOrderItems();
			echo "<center><table><tr style='text-align: center'><td>item number</td><td>name</td><td>price</td><td>quantity</td><td>posted?</td>";
			foreach($result as $item) {
				$prodid = $item->ProdID;
				$quantity = $item->quantity;
				$posted = $item->posted;
				$item_id = $item->ItemNumber;
				$posted == 0 ? $posted = 'no' : $posted = 'yes';
					$item_details = new Shop();
					$item_details->getInfo($prodid);
					$item_name = $item_details->name;
					$item_price = $item_details->price;
				echo "<tr style='text-align: center'><td>" . $item_id . "</td><td>" . $item_name . "</td><td>" . $item_price . "</td><td>" . $quantity . "</td><td>" . $posted . "</td>";
			}
			echo "</table></center>"; 


			
			
		} else {

			//for viewing the basic list of all orders and people who purchased them


			echo "<table><tr><td>Order ID</td><td>Purchaser ID</td><td>Purchase Date</td></tr>";
			$db = new Database();
			$stmt = $db->query("SELECT * FROM orders ORDER BY OrderNumber DESC");
			$stmt->execute();
			$order_results = $stmt->fetchAll(PDO::FETCH_OBJ);
			foreach ($order_results as $order) {
				echo "<tr><td>" . 
				"<a href='./admin?action=orders&orderid=" . $order->OrderNumber . "'>" . $order->OrderNumber . "</a>" . 
				"</td><td>" .
				"<a href='./user_profile?userid=" . $order->UserID . "'>" . $order->UserID . "</a>" .
				"</td><td>" .
				$order->OrderTime;
			
			
			}
			echo "</table>";
		}		

	break;


}


?>

</div>

<div id="related">
<h3>Menu</h3>
<a href="./admin?action=add_news_article">create news article</a><br>
<a href="./admin?action=email">Send a newsletter</a><br>
<a href="./admin?action=update_schedule">Update the schedule</a><br>
<a href="./admin?action=add_shop_item">Add to the shop</a><br>
<a href="./admin?action=remove_shop_item">Remove from the shop</a><br>
<a href="./admin?action=upload_images">Upload images to gallery</a><br>
<a href="./admin?action=orders">Ordered shop items</a>

<?php
include "./layout/layout-bottom.php";
//page created by Omar Farooq
?>

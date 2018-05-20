<?php
Class Shop {

	public $ProdID, $name, $price, $quantity, $description, $image;
	protected $db;

	public function __construct($item) {
		$this->db = new Database();
		$stmt = $this->db->prepare("SELECT * FROM shop WHERE ProdID = ?");
		$stmt->bindParam(1, $item);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Shop');
		$obj = $stmt->fetch(PDO::FETCH_CLASS);

		foreach($obj as $key => $value) {
			$this->$key = $value;
		}
	}
	
	//get all items in the shop and display them
	public function open() {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM shop WHERE deleted = 0");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

			echo "<ul class='flex-list'>";

			foreach ($result as $item) {
				$prod_id = $item->ProdID;
				$name = $item->name;
				$price = $item->price;
				$quantity = $item->quantity;
				$description = $item->description;
				$image = $item->image;

			echo "<li><div class='product-container' style='position:relative'><div><img class='product-image' src='" . $image . "' width=250px height='250px'></div><div style='clear:both; position:relative'>" . $description . "</div>";
			echo "<div style='clear:both; position: relative'>£" . $price . "<input type=\"number\" value=\"1\" min=\"1\" max=\"$quantity\" class=\"quantity\"></div>";
			echo "<span style=\"clear:both\" class=\"addtocart\" prod_id=\"$prod_id\">add to cart</span>";

			echo "</div></li>";


			}

			echo "</ul>";

	}

	//get information about a product in the store. This is particularly useful when you need to get the price. Don't get the price client side.
	public function getInfo($id) {
		$db = $this->db;
		$stmt = $db->prepare("SELECT * FROM shop WHERE ProdID = ?");
		$stmt->bindParam(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Shop');
		$obj = $stmt->fetch();

		foreach($obj as $key => $value) {
			$this->$key = $value;
		}

	}
	
	
	//this is from the admin page to update the stock levels
	public static function updateQuantity($itemID, $quantity) {
		$db = new Database();
		$stmt = $db->prepare("UPDATE shop SET quantity = ? WHERE ProdID = ?");
		$stmt->bindParam(1,$quantity);
		$stmt->bindParam(2,$itemID);
		$stmt->execute();
	}

	public static function deleteItem($itemID, $removeImage) {

		$db = new Database();
		$stmt = $db->prepare("UPDATE shop set deleted = 1 WHERE ProdID = ?");
		$stmt->bindParam(1,$itemID);
		$stmt->execute();

	}
		

}

?>

<?php
Class Order {

	public $OrderNumber, $OrderTime, $UserID, $name, $email, $phone, $address1, $address2, $town, $city, $postcode, $collect, $postage, $paid, $cancelled;
	public $ItemNumber, $ProdID, $quantity, $posted, $ref;
	protected $db;

	public function __construct($OrderNumber) {
		$this->db = new Database();
		$stmt = $this->db->prepare("SELECT * FROM orders WHERE OrderNumber = ?");
		$stmt->bindParam(1,$OrderNumber);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
		$result = $stmt->fetch(PDO::FETCH_CLASS); 
		foreach($result as $key => $value) {
			$this->$key = $value;
		}
		
	}

	//create the order and return an order number

	public function createOrder($name, $email, $phone, $address1, $address2, $town, $city, $postcode, $collect, $postage) {
		$db = $this->db;
			$stmt = $db->prepare("INSERT INTO orders (OrderTime, UserID, name, email, phone, address1, address2, town, city, postcode, collect, postage, paid) VALUES (NOW(), :userid, :name, :email, :phone, :address1, :address2, :town, :city, :postcode, :collect, :postage, 'true')");
			$stmt->bindParam(':userid', $_SESSION['userid']);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':phone', $phone);
			$stmt->bindParam(':address1', $address1);
			$stmt->bindParam(':address2', $address2);
			$stmt->bindParam(':town', $town);
			$stmt->bindParam(':city', $city);
			$stmt->bindParam(':postcode', $postcode);
			$stmt->bindParam(':collect', $collect);
			$stmt->bindParam(':postage', $postage);
			$stmt->execute();
			$lastid = $db->lastInsertId();

			return $lastid;


	}

	//with the order number, create a table entry for each item separately, each referencing this order number
	public function orderItems($OrderNumber, $ProdID, $quantity) {
		$db = $this->db;
		$stmt = $db->prepare("INSERT INTO order_items (OrderNumber, ProdID, quantity) VALUES (:OrderNumber, :ProdID, :quantity)");
		$stmt->bindParam(':OrderNumber',$OrderNumber);
		$stmt->bindParam(':ProdID',$ProdID);
		$stmt->bindParam(':quantity',$quantity);
		$stmt->execute();



	}

	public function getOrderItems() {
		$db = $this->db;
		$OrderNumber = $this->OrderNumber;
		$stmt = $db->prepare("SELECT * FROM order_items WHERE OrderNumber = ?");
		$stmt->bindParam(1, $OrderNumber);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		return $result;
	}
	
}

?>

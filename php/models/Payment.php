<?php

Class Payment extends Order {

	protected $db;
	private $paymentId, $PayerID, $PaymentTime;

	private function paid($OrderID, $paymentId, $PayerID) {

		$db = $this->db;
		$stmt = $db->prepare("INSERT INTO payments(OrderNumber, paymentId, PayerID, PaymentTime) VALUES (?, ?, ?, NOW())");
		$stmt->bindParam(1, $OrderID);
		$stmt->bindParam(2, $paymentId);
		$stmt->bindParam(3, $PayerID);
		$stmt->execute();
		
	}
	
}

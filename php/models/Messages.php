<?php
Class Messages {

	public $pmID, $sender, $recipient, $message, $seen, $pmtime, $senderID, $recipientID;
	protected $db;

	public function __construct() {
		$this->db = new Database();
		$this->recipient = $_SESSION['Username'];
		$this->recipientID = $_SESSION['userid'];
	}

	public function getLast() {
		$db = $this->db;
		$recipientID = $this->recipientID;
		$seen = '0';
		$stmt = $db->prepare("SELECT * FROM pms WHERE recipientID = ? AND seen = ? ORDER BY pmID DESC LIMIT 1");
		$stmt->bindParam(1, $recipientID);
		$stmt->bindParam(2, $seen);
		$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'Messages');
			$messages = $stmt->fetch(PDO::FETCH_CLASS); 
			foreach($messages as $key => $value) {
				$this->$key = $value;
			}
		
	}

	public function getAll() {
		$db = $this->db;
		$recipientID = $this->recipientID;
		$stmt = $this->db->prepare("SELECT * FROM pms WHERE recipientID = ? ORDER BY pmtime DESC");
		$stmt->bindParam(1, $recipientID);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Messages');
		$messages = $stmt->fetchAll(PDO::FETCH_CLASS); 
		foreach($messages as $message) {
			$id = $message->pmID;
			$seen = $message->seen;
			$pmBody = htmlspecialchars($message->message);
			$bbparse = new bbParser();
			$pmBody = $bbparse->getHtml($pmBody);
			echo "<div id='message_" . $id . "' class='message-holder";
				$v = ($seen == '0' ? " unread'>" : "'>");
			echo $v;
			echo $message->pmtime . "<br>" . $message->sender . " says<br>";
			echo "&ldquo;" . $pmBody . "&rdquo;";
			echo "</div><br>";

			if($seen == '0') {
				$stmt = $this->db->prepare("UPDATE pms SET seen = 1 WHERE pmID = ?");
				$stmt->bindParam(1, $id);
				$stmt->execute();
			}
		}
		

	}

	public function numberOfUnseen() {
		$db = $this->db;
		$recipientID = $this->recipientID;
		$stmt = $this->db->prepare("SELECT * FROM pms WHERE recipientID = ? AND seen = ?");
		$stmt->bindValue(1, $recipientID);
		$stmt->bindValue(2, 0, PDO::PARAM_INT);
		$stmt->execute();
		$numUnseen = $stmt->rowCount();
		return $numUnseen;

	}

	public function create($recipient, $message) {
		$createRecipient = new Users($recipient);
		$recipientID = $createRecipient->UserID;
		$senderID = $_SESSION['userid'];
		$sender = $_SESSION['Username'];
		$db = $this->db;
		$stmt = $db->prepare("INSERT INTO pms (sender, recipient, message, senderID, recipientID, pmtime) VALUES (?,?,?,?,?,CURRENT_TIMESTAMP)");
		$stmt->bindParam(1,$sender);
		$stmt->bindParam(2,$recipient);
		$stmt->bindParam(3,$message);
		$stmt->bindParam(4,$senderID);
		$stmt->bindParam(5,$recipientID);
		$stmt->execute();
		echo "message sent";
	}

}

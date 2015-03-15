<?php

if (isset($_POST)) {
	// Variables
	$MessageSid = $_REQUEST["MessageSid"];
	$ErrorCode = $_REQUEST["ErrorCode"];
	$MessageStatus = $_REQUEST["MessageStatus"];
	// $authKey = $_POST["authKey"];

	$servername = "web404.webfaction.com:3306";
	$username = "yinanfang";
	$password = "123456Chai";
	$dbname = "chai_protect";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// echo "Connected successfully";
		// new data
		$MessageSid = 'PHP Security';
		$author = 'Jack Hijack';

		// query
		$sql = "INSERT INTO Message (MessageSid, ErrorCode, MessageStatus) VALUES (:MessageSid, :ErrorCode, :MessageStatus)";
		$q = $conn->prepare($sql);
		$q->execute(array(
			':MessageSid' => $MessageSid,
			':ErrorCode' => $ErrorCode,
			':MessageStatus' => $MessageStatus,
		));
	} catch (PDOException $e) {
		// echo "Connection failed: " . $e->getMessage();
	}
}

?>
<?php

ini_set("log_errors", 1);
ini_set("error_log", "./error.log");
error_log("Received call back...");

try {
	$response = array();
	$request = file_get_contents('php://input');
	error_log("the whole thing: " . $request);
	$parts = explode("&", $request);
	foreach ($parts as $part) {
		$items = explode("=", $part);
		$response[$items[0]] = $items[1];
	}
	foreach ($response as $key => &$value) {
		error_log("json: " . $key . " -> " . $value);
	}

} catch (Exception $e) {
	error_log("Connection failed: " . $e->getMessage());
}

// $input = json_decode($request);
// error_log("item: " . $input["SmsSid"]);
// $data = json_decode(file_get_contents('php://input'));
// error_log($data["SmsSid"]);

// $data = json_decode(file_get_contents('php://input'), TRUE);
// $text = print_r($data, true);
// error_log($text);

if (!empty($data["SmsSid"])) {
	error_log("data formed in json");
} else {
	error_log("is empty");
}

// error_log(file_get_contents('php://input'));

// $data = json_decode(file_get_contents('php://input'), true);
// error_log($data);

// Variables
// $MessageSid = $_REQUEST["MessageSid"];
//  $ErrorCode = $_REQUEST["ErrorCode"];
//  $MessageStatus = $_REQUEST["MessageStatus"];
$MessageSid = "sdfasd";
$ErrorCode = "asdfasd";
$MessageStatus = "asdfasdf";
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
	error_log("Connection failed: " . $e->getMessage());
}

?>
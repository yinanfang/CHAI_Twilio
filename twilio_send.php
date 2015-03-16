<?php

ini_set("log_errors", 1);
ini_set("error_log", "./error.log");
error_log("Received sent request...");

if (isset($_POST["name"])) {
	// Variables
	$clientName = $_POST["name"];
	$numberTo = $_POST["name"];
	$message = $_POST["message"];
	$authKey = $_POST["authKey"];

	header("Status: 200 OK");

	$servername = "web404.webfaction.com:3306";
	$username = "yinanfang";
	$password = "123456Chai";
	$dbname = "chai_protect";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected successfully";
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
}

// Get the PHP helper library from twilio.com/docs/php/install
require_once __DIR__ . '/twilio-php/Services/Twilio.php'; // Loads the library
// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC424a6be19a10b589c1908ae43e48a736";
$token = "abcf6bd45a21149a9f21825875bf6937";
$client = new Services_Twilio($sid, $token);
$numFrom = "+19195900174";
$numTo = "+19194484206";
// $numTo = "+19192654757";

// $client->account->messages->sendMessage($numFrom, $numTo, "The call girl", "http://upload.wikimedia.org/wikipedia/commons/e/e5/Vienna_Skyline.jpg");

$client->account->messages->create(array(
	'From' => $numFrom,
	'To' => $numTo,
	'Body' => "test",
	'MediaUrl' => "http://upload.wikimedia.org/wikipedia/commons/e/e5/Vienna_Skyline.jpg",
	'StatusCallback' => "http://chai.yinanfang.webfactional.com/Twilio/twilio_callback.php",
	// 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
));

?>

<!--
200 OK
500 FAIL -->
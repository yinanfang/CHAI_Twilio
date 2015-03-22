<?php
require_once __DIR__ . "/log/Log.class.php";
require_once __DIR__ . "/PDO/Db.class.php";

$AUTH_KEY = "3pKtr0P1p9";

$errorMessage = "";
$log = new Log();
$log->write("Received sent request in " . basename(__FILE__));

if (!isset($_POST["From"]) || !isset($_POST["To"]) || !isset($_POST["Body"]) || !isset($_POST["AuthKey"])) {
	$errorMessage = "Incomplete POST request; From:" . $_POST["From"] . "To: " . $_POST["To"];
	$log->write($errorMessage);
	header("Status: 500 FAIL");
	echo $errorMessage;
} else {
	// Variables
	$clientName = $_POST["From"];
	$numberTo = $_POST["To"];
	$authKey = $_POST["AuthKey"];

	if (strcmp($authKey, $AUTH_KEY) !== 0) {
		$errorMessage = "Incorrect AuthKey";
		$log->write($errorMessage);
		header("Status: 500 FAIL");
		echo $errorMessage;
	} else {
		// Twilio Service
		try {
			require_once __DIR__ . '/twilio-php/Services/Twilio.php'; // Loads the library
			$settings = parse_ini_file("twilio_settings.ini.php");
			// Your Account Sid and Auth Token from twilio.com/user/account
			$sid = $settings["sid"];
			$token = $settings["token"];
			$client = new Services_Twilio($sid, $token);
			$numTo = $numberTo;
			// $numTo = "+19192654757";
			// $numFrom = "+19195900174";

			// Creates the instance
			$db = new Db();
			$numFromArray = $db->query(
				"SELECT Number FROM Client WHERE ClientID = :clientID",
				array(
					"clientID" => $clientName,
				));
			$numFrom = reset($numFromArray[0]);

			$client->account->messages->create(array(
				'From' => $numFrom,
				'To' => $numTo,
				'Body' => $_POST["Body"],
				'MediaUrl' => $_POST["MediaUrl"],
				'StatusCallback' => "http://chai.yinanfang.webfactional.com/Twilio/twilio/twilio_callback.php",
				// 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
			));

			// Get response
			$response = $client->last_response;
			$insert = $db->query("
				INSERT INTO Message(
					MessageSid,
					MessageStatus,
					ErrorCode,
					ErrorMessage,
					ClientName,
					NumFrom,
					NumTo,
					Body,
					NumMedia,
					AccountSid,
					ApiVersion
					)
				VALUES(
					:sid,
					:status,
					:error_code,
					:error_message,
					:clientName,
					:from,
					:to,
					:body,
					:num_media,
					:account_sid,
					:api_version
					)",
				array(
					"sid" => $response->sid,
					"status" => $response->status,
					"error_code" => $response->error_code,
					"error_message" => $response->error_message,
					"clientName" => $clientName,
					"from" => $response->from,
					"to" => $response->to,
					"body" => $response->body,
					"num_media" => $response->num_media,
					"account_sid" => $response->account_sid,
					"api_version" => $response->api_version,
				)
			);
			header("Status: 200 OK");
			$log->write("Queued and recorded message #" . $response->sid);

		} catch (Exception $e) {
			$errorMessage = $e->getMessage();
			$log->write($errorMessage);
			header("Status: 500 FAIL");
			echo $errorMessage;
		}
	}
}

?>
;
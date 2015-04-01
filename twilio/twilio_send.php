<?php
require_once __DIR__ . "/log/Log.class.php";
require_once __DIR__ . "/PDO/Db.class.php";

$settings = parse_ini_file(__DIR__ . "/twilio_settings.ini.php");
$AUTH_KEY = $settings["AUTH_KEY"];

$logMessage = "";
$log = new Log();
$log->write("Received sent request in " . basename(__FILE__));

if (!isset($_POST["From"]) || !isset($_POST["To"]) || !isset($_POST["Body"]) || !isset($_POST["AuthKey"]) || !isset($_POST["Type"])) {
	$logMessage = "Incomplete POST request; From:" . $_POST["From"] . "To: " . $_POST["To"];
	$log->write($logMessage);
	header("Status: 500 FAIL");
	echo $logMessage;
} else {
	// Variables
	$clientName = $_POST["From"];
	$numberTo = $_POST["To"];
	$authKey = $_POST["AuthKey"];

	if (strcmp($authKey, $AUTH_KEY) !== 0) {
		$logMessage = "Incorrect AuthKey";
		$log->write($logMessage);
		header("Status: 500 FAIL");
		echo $logMessage;
	} else {

		// if ($_POST["Type"] == "message") {
		// 	echo "crap!";
		// }

		// Twilio Service
		try {
			// Creates the instance
			$db = new Db();
			$response = $db->query(
				"SELECT Number, SID, Token FROM Client WHERE ClientName = :clientName",
				array(
					"clientName" => $clientName,
				));
			// echo (var_dump($response));
			$numFrom = $response[0]["Number"];
			$sid = $response[0]["SID"];
			$token = $response[0]["Token"];
			// echo "I got: ";
			// echo ($numFrom . " - ");
			// echo ($sid . " - ");
			// echo ($token . " - ");

			require_once __DIR__ . '/twilio-php/Services/Twilio.php'; // Loads the library
			// Your Account Sid and Auth Token from twilio.com/user/account
			$client = new Services_Twilio($sid, $token);
			$numTo = $numberTo;
			// $numTo = "+19192654757";
			// $numFrom = "+19195900174";

			$data = array(
				'From' => $numFrom,
				'To' => $numTo,
				'Body' => $_POST["Body"],
				'StatusCallback' => "http://chai.yinanfang.webfactional.com/Twilio/twilio/twilio_callback.php",
				// 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
			);
			// Add mediaUrl if there's one
			if (isset($_POST["MediaUrl"])) {
				$data["MediaUrl"] = $_POST["MediaUrl"];
			}
			$client->account->messages->create($data);

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
			$logMessage = $e->getMessage();
			$log->write($logMessage);
			header("Status: 500 FAIL");
			echo $logMessage;
		}
	}
}

?>
;
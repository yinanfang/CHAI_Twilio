<?php
// Log
require_once __DIR__ . "/log/Log.class.php";
$logMessage = "";
$log = new Log();
$log->write("Received request in " . basename(__FILE__));

// Twilio Request
require_once __DIR__ . "/TwilioRequest.class.php";

try {
	if ($_POST["AuthKey"] != parse_ini_file(__DIR__ . "/twilio_settings.ini.php")["AUTH_KEY"]) {
		$logMessage = "Twilio API service authorization failed. This incident will be reported.";
	} else if (isset($_POST["Request"]) && isset($_POST["From"]) && isset($_POST["To"])) {
		// Init Twilio API
		// , $_POST["To"]
		$twilioRequest = TwilioRequest::withClientName($_POST["From"]);
		if ($_POST["Request"] == "message" && isset($_POST["Body"])) {
			// $log->write("Requested to send message!");
			$twilioRequest->sendAMessageTo($_POST["To"], $_POST["Body"], $_POST["MediaUrl"]);
		} else if ($_POST["Request"] == "call" && isset($_POST["MediaUrl"])) {
			// $log->write("Request make a call!");
			$twilioRequest->makeACallTo($_POST["To"], $_POST["MediaUrl"]);
		} else {
			$logMessage = "Missing request parameters or unknown request type.";
		}
	} else if ($_POST["Request"] == "query_logging") {
		$twilioRequest = new TwilioRequest();
		$twilioRequest->getAllLogging();
	} else if ($_POST["Request"] == "query_billing") {
		# code...
	} else {
		$logMessage = "Missing request parameters or unknown request type.";
	}

} catch (Exception $e) {
	$logMessage = $e->getMessage();
} finally {
	if (strlen($logMessage) == 0) {
		$logMessage = "The request is successfully processed.";
		header("Status: 200 OK");
	} else {
		header("Status: 500 FAIL");
	}
	$log->write($logMessage . "\r\n");
	echo $logMessage;
}

?>
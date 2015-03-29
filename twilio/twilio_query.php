<?php
require_once __DIR__ . "/log/Log.class.php";
require_once __DIR__ . "/PDO/Db.class.php";
require_once __DIR__ . '/twilio-php/Services/Twilio.php'; // Loads the library

$settings = parse_ini_file(__DIR__ . "/twilio_settings.ini.php");
$AUTH_KEY = $settings["AUTH_KEY"];

$logMessage = "";
$log = new Log();
$log->write("Received query request in " . basename(__FILE__));
if (isset($_POST["AuthKey"]) && strcmp($authKey, $AUTH_KEY) !== 0) {
	// Creates the instance
	$db = new Db();
	if (isset($_POST["Type"])) {
		if ($_POST["Type"] == "billing") {
			$log->write("Received query request for billing");
			if (isset($_POST["Range"])) {
				// Querying for something
				# code...
			} else {
				// General query. Send everyting back
				$accountInfo = $db->query(
					"SELECT ClientName, Number, SID, Token FROM Client GROUP BY ClientName"
				);
				// echo (var_dump($accountInfo));
				// echo "start looping \n";
				$response = array();
				$response['columns'] = '["Client Name","Total Price"]';
				$response['data'] = array();
				for ($i = 0; $i < sizeof($accountInfo); $i++) {
					$row = array();
					// $row["clientName"] = $accountInfo[$i]["ClientName"];
					array_push($row, $accountInfo[$i]["ClientName"]);
					$sid = $accountInfo[$i]["SID"];
					$token = $accountInfo[$i]["Token"];
					// $client = new Services_Twilio($sid, $token);
					$requestURL = "https://" . $sid . ":" . $token . "@api.twilio.com/2010-04-01/Accounts/" . $sid . "/Usage/Records/Monthly.json?Category=totalprice";
					$json = json_decode(file_get_contents($requestURL));
					// echo (var_dump($json));
					// $row["totalPrice"] = $json->usage_records[0]->price;
					array_push($row, $json->usage_records[0]->price);
					array_push($response['data'], $row);
				}
				// echo (var_dump($response));
				echo json_encode($response);
				die();
			}
		} else if ($_POST["Type"] == "logging") {
			// General query. Send everyting back
			$messageLogs = $db->query(
				"SELECT id, ClientName, MessageStatus, ErrorCode, TimeStamp FROM Message"
			);
			// echo (var_dump($messageLogs));
			// echo "start looping \n";
			$response = array();
			$response['columns'] = '["id","Client", "Status", "Error", "Time"]';
			$response['data'] = array();
			for ($i = 0; $i < sizeof($messageLogs); $i++) {
				$row = array();
				array_push($row, $messageLogs[$i]["id"]);
				array_push($row, $messageLogs[$i]["ClientName"]);
				array_push($row, $messageLogs[$i]["MessageStatus"]);
				array_push($row, $messageLogs[$i]["ErrorCode"]);
				array_push($row, $messageLogs[$i]["TimeStamp"]);
				array_push($response['data'], $row);
			}
			// echo (var_dump($response));
			echo json_encode($response);
			die();
		} else {
			$logMessage = "Uknown Type";
		}
	} else {
		$logMessage = "Incomplete POST request";
	}
} else {
	$logMessage = "Incorrect Authentication Key";
}

$log->write($logMessage);
header("Status: 500 FAIL");
echo $logMessage;
?>
;
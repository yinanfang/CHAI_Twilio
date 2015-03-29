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
	if (isset($_POST["Type"])) {
		if ($_POST["Type"] == "billing") {
			$log->write("Received query request for billing");
			if (isset($_POST["Range"])) {
				// Querying for something
				# code...
			} else {
				// General query. Send everyting back
				// Creates the instance
				$db = new Db();
				$response = $db->query(
					"SELECT ClientName, Number, SID, Token FROM Client GROUP BY ClientName"
				);
				echo (var_dump($response));
				echo "start looping \n";
				for ($i = 0; $i < sizeof($response); $i++) {
					$sid = $response[$i]["SID"];
					$token = $response[$i]["Token"];
					$client = new Services_Twilio($sid, $token);
					// Loop over the list of records and echo a property for each one
					foreach ($client->account->usage_records->monthly as $record) {
						echo $record->price . "; ";
					}
					echo "\n";
					// echo (var_dump($client->account->usage_records));

				}

				// Your Account Sid and Auth Token from twilio.com/user/account
				// $client = new Services_Twilio($sid, $token);
				// $numTo = $numberTo;
				// $numTo = "+19192654757";
				// $numFrom = "+19195900174";

				// $data = array(
				// 	'From' => $numFrom,
				// 	'To' => $numTo,
				// 	'Body' => $_POST["Body"],
				// 	'StatusCallback' => "http://chai.yinanfang.webfactional.com/Twilio/twilio/twilio_callback.php",
				// 	// 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
				// );
				// // Add mediaUrl if there's one
				// if (isset($_POST["MediaUrl"])) {
				// 	$data["MediaUrl"] = $_POST["MediaUrl"];
				// }
				// $client->account->messages->create($data);

			}

			// Query for billing
			// $db = new Db();
			// $numFromArray = $db->query(
			// 	"SELECT Number FROM Client WHERE 1",
			// 	array(
			// 		"clientID" => $clientName,
			// 	));
			// $response["data"] = $numFromArray;
			// header("Status: 200 OK");
			// echo json_encode($response);
			return;
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

// 			$data = array(
// 				'From' => $numFrom,
// 				'To' => $numTo,
// 				'Body' => $_POST["Body"],
// 				'StatusCallback' => "http://chai.yinanfang.webfactional.com/Twilio/twilio/twilio_callback.php",
// 				// 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
// 			);
// 			// Add mediaUrl if there's one
// 			if (isset($_POST["MediaUrl"])) {
// 				$data["MediaUrl"] = $_POST["MediaUrl"];
// 			}
// 			$client->account->messages->create($data);

// 			// Get response
// 			$response = $client->last_response;
// 			$insert = $db->query("
// 				INSERT INTO Message(
// 					MessageSid,
// 					MessageStatus,
// 					ErrorCode,
// 					ErrorMessage,
// 					ClientName,
// 					NumFrom,
// 					NumTo,
// 					Body,
// 					NumMedia,
// 					AccountSid,
// 					ApiVersion
// 					)
// 				VALUES(
// 					:sid,
// 					:status,
// 					:error_code,
// 					:error_message,
// 					:clientName,
// 					:from,
// 					:to,
// 					:body,
// 					:num_media,
// 					:account_sid,
// 					:api_version
// 					)",
// 				array(
// 					"sid" => $response->sid,
// 					"status" => $response->status,
// 					"error_code" => $response->error_code,
// 					"error_message" => $response->error_message,
// 					"clientName" => $clientName,
// 					"from" => $response->from,
// 					"to" => $response->to,
// 					"body" => $response->body,
// 					"num_media" => $response->num_media,
// 					"account_sid" => $response->account_sid,
// 					"api_version" => $response->api_version,
// 				)
// 			);
// 			header("Status: 200 OK");
// 			$log->write("Queued and recorded message #" . $response->sid);

// 		} catch (Exception $e) {
// 			$logMessage = $e->getMessage();
// 			$log->write($logMessage);
// 			header("Status: 500 FAIL");
// 			echo $logMessage;
// 		}
// 	}
// }

?>
;
<?php
require_once __DIR__ . "/log/Log.class.php";
require_once __DIR__ . "/PDO/Db.class.php";

$logMessage = "";
$log = new Log();
$log->write("Received Callback in " . basename(__FILE__));

try {
	$response = array();
	$request = file_get_contents('php://input');
	// $log->write("the whole thing: " . $request);
	$parts = explode("&", $request);
	foreach ($parts as $part) {
		$items = explode("=", $part);
		$response[$items[0]] = $items[1];
	}
	// Logging response data
	// foreach ($response as $key => &$value) {
	// 	error_log("json: " . $key . " -> " . $value);
	// }

	// Get variables
	$MessageSid = $response["MessageSid"];
	$ErrorCode = $response["ErrorCode"];
	$MessageStatus = $response["MessageStatus"];
	$log->write("MessageSid: " . $response["MessageSid"] . " MessageStatus: " . $MessageStatus);

	// Update status
	$db = new Db();
	$update = $db->query(
		"	UPDATE Message
			SET MessageStatus = :MessageStatus, ErrorCode = :ErrorCode
			WHERE MessageSid = :MessageSid",
		array(
			"MessageStatus" => $MessageStatus,
			"ErrorCode" => $ErrorCode,
			"MessageSid" => $MessageSid,
		)
	);

} catch (Exception $e) {
	$logMessage = $e->getMessage();
	$log->write($logMessage);
}

?>

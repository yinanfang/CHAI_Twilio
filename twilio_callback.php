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
	// Logging response data
	foreach ($response as $key => &$value) {
		error_log("json: " . $key . " -> " . $value);
	}

	// connecting to database
	$servername = "web404.webfaction.com:3306";
	$username = "yinanfang";
	$password = "123456Chai";
	$dbname = "chai_protect";
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	error_log("Connectted succeessfully!");

	// Update status
	$MessageSid = $response["MessageSid"];
	$AccountSid = $response["AccountSid"];
	$ErrorCode = $response["ErrorCode"];
	$MessageStatus = $response["MessageStatus"];
	$NumFrom = intval(substr($response["From"], 3));
	// error_log("NumFrom: " . $response["From"]);
	// error_log("NumFrom: " . substr($response["From"], 3));
	// error_log("NumFrom: " . $NumFrom);

	$NumTo = intval(substr($response["To"], 2));
	$Body = $response["Body"];
	$NumMedia = $response["NumMedia"];
	$SmsSid = $response["SmsSid"];
	$SmsStatus = $response["SmsStatus"];

	$sql = "INSERT INTO Message (MessageSid, AccountSid, ErrorCode, MessageStatus, NumFrom, NumTo, Body, NumMedia, SmsSid, SmsStatus) VALUES (:MessageSid, :AccountSid, :ErrorCode, :MessageStatus, :NumFrom, :NumTo, :Body, :NumMedia, :SmsSid, :SmsStatus)";
	$q = $conn->prepare($sql);
	$q->execute(array(
		':MessageSid' => $MessageSid,
		':AccountSid' => $AccountSid,
		':ErrorCode' => $ErrorCode,
		':MessageStatus' => $MessageStatus,
		':NumFrom' => $NumFrom,
		':NumTo' => $NumTo,
		':Body' => $Body,
		':NumMedia' => $NumMedia,
		':SmsSid' => $SmsSid,
		':SmsStatus' => $SmsStatus,
	));

} catch (Exception $e) {
	error_log("Connection failed: " . $e->getMessage());
}

?>
<!-- Example response
SmsSid=MM2e20406a36cd4d179ac7958ae3a4d5e2&SmsStatus=sent&MessageStatus=sent&To=%2B19194484206&MessageSid=MM2e20406a36cd4d179ac7958ae3a4d5e2&AccountSid=AC424a6be19a10b589c1908ae43e48a736&From=%2B19195900174&ApiVersion=2010-04-01
Formatted:
SmsSid -> MM2e20406a36cd4d179ac7958ae3a4d5e2
SmsStatus -> sent
MessageStatus -> sent
To -> %2B19194484206
MessageSid -> MM2e20406a36cd4d179ac7958ae3a4d5e2
AccountSid -> AC424a6be19a10b589c1908ae43e48a736
From -> %2B19195900174
ApiVersion -> 2010-04-01 -->
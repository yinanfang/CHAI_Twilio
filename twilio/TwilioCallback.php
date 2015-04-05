<?php
// Log
require_once __DIR__ . "/log/Log.class.php";
$log = new Log();
$log->write("Received callback request in " . basename(__FILE__));

// Twilio Request
require_once __DIR__ . "/TwilioRequest.class.php";

try {
  $callback = array();
  foreach ($_REQUEST as $key => $value) {
    // $request = file_get_contents('php://input');
    // $log->write("the whole thing: " . $request . $_REQUEST["From"] . $rrr);
    $log->write("request: " . $key . "; " . $value);
    $callback[$key] = $value;
  }

  $twilioRequest = new TwilioRequest();
  if (array_key_exists("MessageStatus", $callback)) {
    $twilioRequest->updateLogMessageEntryWith($callback);
    $logMessage .= "Updated status of message: " . $callback["MessageSid"];
  } else if (array_key_exists("CallStatus", $callback)) {
    $twilioRequest->updateLogCallEntryWith($callback);
    $logMessage .= "Updated status of call: " . $callback["CallSid"];
  }

} catch (Exception $e) {
  $logMessage = $e->getMessage();
  $log->write("exception!" . "\r\n");
} finally {
  if (strlen($logMessage) == 0) {
    $logMessage = "The callback is successfully processed.";
  }
  $log->write($logMessage . "\r\n");
  header("Status: 500 FAIL");
  echo $logMessage;
}

?>


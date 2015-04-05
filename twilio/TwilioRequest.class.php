<?php
/**
 *  Twilio Request - Interact with Twilio Service
 */
// Log
require_once __DIR__ . "/log/Log.class.php";
// PDO
require_once __DIR__ . "/PDO/Db.class.php";
// Twilio Library
require_once __DIR__ . '/twilio-php/Services/Twilio.php';
// Settings

class TwilioRequest {
	// General
	private $log;
	private $db;
	private $settings;
	private $client;
	private $callbackURL;
	// For both message and call
	private $clientName;
	private $numFrom;
	private $sid;
	private $token;
	// For call only
	private $mediaUrl;

	public function __construct($clientName) {
		$this->log = new Log();
		$this->db = new DB();
		$this->settings = parse_ini_file(__DIR__ . "/twilio_settings.ini.php");
		$this->callbackURL = $this->settings["ServerURL"] . "/twilio/twilio_callback.php";
		$this->clientName = $clientName;
		$this->client = $this->initTwilioClientWith($clientName);
	}

	private function initTwilioClientWith($clientName) {
		// Get Clients' numbers
		$response = $this->db->query(
			"SELECT Number, SID, Token FROM Client WHERE ClientName = :clientName",
			array(
				"clientName" => $clientName,
			));
		// echo (var_dump($response));
		$this->numFrom = $response[0]["Number"];
		$this->sid = $response[0]["SID"];
		$this->token = $response[0]["Token"];
		// echo ("I got: " . $numFrom . "; " . $sid . "; " . $token);
		// Twilio client
		$client = new Services_Twilio($this->sid, $this->token);
		return $client;
	}

	// Message
	public function sendAMessageTo($numberTo, $messageBody, $mediaUrl) {
		$data = array(
			'From' => $this->numFrom,
			'To' => $numberTo,
			'Body' => $messageBody,
			'StatusCallback' => $this->callbackURL,
			                         // 'StatusCallback' => "http://protectthem.152.23.4.176.xip.io/twilio_callback.php",
		);
		// Add mediaUrl if there's one
		if (isset($mediaUrl)) {
			$data["MediaUrl"] = $mediaUrl;
		}
		$this->client->account->messages->create($data);
		$this->createLogEntryForMessage();
	}

	private function createLogEntryForMessage() {
		$response = $this->client->last_response;
		$insert = $this->db->query("
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
				"clientName" => $this->clientName,
				"from" => $response->from,
				"to" => $response->to,
				"body" => $response->body,
				"num_media" => $response->num_media,
				"account_sid" => $response->account_sid,
				"api_version" => $response->api_version,
			)
		);
	}

	// Call
	public function makeACallTo($numberTo, $mediaUrl) {
		$this->mediaURL = $mediaUrl;
		$this->client->account->calls->create(
			$this->numFrom,
			$numberTo,
			$mediaUrl,
			array(
				'Method' => 'GET',
				'FallbackMethod' => 'GET',
				'StatusCallback' => $this->callbackURL,
				'StatusCallbackMethod' => 'GET',
				'IfMachine' => 'Hangup',
				'Record' => 'false',
			)
		);
		$this->createLogEntryForCall();
	}

	private function createLogEntryForCall() {
		$response = $this->client->last_response;
		$insert = $this->db->query("
	    INSERT INTO Call(
	      CallSid,
	      CallStatus,
	      ClientName,
	      NumFrom,
	      NumTo,
	      MediaURL,
	      AccountSid,
	      ApiVersion
	      )
	    VALUES(
	      :sid,
	      :status,
	      :clientName,
	      :from,
	      :to,
	      :mediaURL,
	      :account_sid,
	      :api_version
	      )",
			array(
				"sid" => $response->sid,
				"status" => $response->status,
				"clientName" => $this->clientName,
				"from" => $response->from,
				"to" => $response->to,
				"mediaURL" => $this->mediaURL,
				"account_sid" => $response->account_sid,
				"api_version" => $response->api_version,
			)
		);
	}
}
?>

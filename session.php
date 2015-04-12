<?php
// Log
require_once __DIR__ . "/twilio/log/Log.class.php";
// PDO
require_once __DIR__ . "/twilio/PDO/Db.class.php";

session_start(); // Starting Session
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$db = new DB();
$response = $db->query(
	"SELECT username FROM Logins WHERE username = :username",
	array(
		"username" => $_SESSION['login_user'],
	));
$login_session = $response[0]['username'];
if (!isset($login_session)) {
	header('Location: index.php'); // Redirecting To Home Page
}
?>
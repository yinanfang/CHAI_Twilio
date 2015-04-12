<?php
// Log
require_once __DIR__ . "/twilio/log/Log.class.php";
// PDO
require_once __DIR__ . "/twilio/PDO/Db.class.php";

session_start(); // Starting Session
$error = ''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = "Username or Password is invalid";
	} else {
// Define $username and $password
		$username = stripslashes($_POST['username']);
		$password = stripslashes($_POST['password']);
		// Establishing Connection with Server by passing server_name, user_id and password as a parameter
		$db = new DB();
		$response = $db->query(
			"SELECT * FROM Logins WHERE username = :username AND password= :password",
			array(
				"username" => $username,
				"password" => $password,
			));
		// echo (var_dump($response));
		if (count($response) == 1) {
			$_SESSION['login_user'] = $username; // Initializing Session
			header("location: admin_vul.php"); // Redirecting To Other Page
		} else {
			$error = "Username or Password is invalid";
		}
	}
}
?>
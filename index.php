<?php
include 'login.php'; // Includes Login Script

// if (session_destroy()) // Destroying All Sessions
// {
//  header("Location: index.php"); // Redirecting To Home Page
// }

if (isset($_SESSION['login_user'])) {
	header("location: admin_vul.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Form in PHP with Session</title>
  <link href="./css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div id="main">
    <h1>PHP Login Session Example</h1>
    <div id="login">
      <h2>Login Form</h2>
      <form action="" method="post">
        <label>UserName :</label>
        <input id="name" name="username" placeholder="username" type="text">
        <label>Password :</label>
        <input id="password" name="password" placeholder="**********" type="password">
        <input name="submit" type="submit" value=" Login ">
        <span><?php echo $error;?></span>
      </form>
    </div>
  </div>
</body>
</html>
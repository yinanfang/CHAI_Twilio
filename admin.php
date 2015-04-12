<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en" ng-app="adminApp">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Pragma" content="no-cache">
  <title>Twilio Demo</title>

  <!-- Normalize.css -->
  <link rel="stylesheet" href="./css/normalize.css">

  <!-- Loading Bootstrap -->
  <link href="./FlatUI/css/vendor/bootstrap.min.css" rel="stylesheet">
  <!-- Loading Flat UI -->
  <link href="./FlatUI/css/flat-ui.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="img/favicon.ico">
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
  <!--[if lt IE 9]>
    <script src="js/vendor/html5shiv.js"></script>
    <script src="js/vendor/respond.min.js"></script>
  <![endif]-->

  <!-- Customized CSS -->
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="stylesheet" href="./css/loading.css">

  <!-- AngularJS -->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>

  <!-- Polymer -->
  <!-- 1. Load platform support before any code that touches the DOM. -->
  <script src="./components/webcomponentsjs/webcomponents.js"></script>
  <!-- 2. Load the component using an HTML Import -->
  <link rel="import" href="./sampler-scaffold.html">
  <link rel="import" href="./components/core-item/core-item.html">
  <link rel="import" href="./components/paper-fab/paper-fab.html">
  <link rel="import" href="./components/paper-button/paper-button.html">
</head>
<body>

  <div id="loader-wrapper">
    <div id="loader"></div>
    <div id="loaderText">Loading ...</div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
  </div>

  <div>So nothing shows up again!!!</div>

  <sampler-scaffold label="Twilio Admin" fit>

    <!-- <paper-icon-button class="menuButton" icon="menu" ></paper-icon-button>-->
    <paper-fab class="sourceButton bottom" icon="launch"></paper-fab>

    <core-item label="Control" tag="control" url="./control_vul.html"></core-item>

    <core-item label="Logging" tag="logging" url="./logging_vul.html"></core-item>

    <core-item label="Billing" tag="billing" url="./billing_vul.html"></core-item>

    <core-item label="Account" tag="account" url="./account_vul.php"></core-item>

  </sampler-scaffold>

  <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
  <script src="./FlatUI/js/vendor/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="./FlatUI/js/vendor/video.js"></script>
  <script src="./FlatUI/js/flat-ui.min.js"></script>

  <!-- Customized JS -->
  <script src="./js/javascript.js"></script>
</body>
</html>
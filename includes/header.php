<?php
require 'config/config.php';

if(isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username']; //sets the logged in user with the session
} else {
  //if a user is not logged in...
  header('Location: register.php'); //redirects back to register page
}
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Social</title>

    <!-- BOOTSTRAP CSS v3.3.7-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- BOOTSTRAP JS v3.3.7-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="top_bar">
      <div class="logo">
        <a href="index.php">Social</a>
      </div>
    </div>

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
  </head>
  <body>

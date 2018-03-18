<?php
require 'config/config.php';

if(isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username']; //sets the logged in user with the session
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query); //Access User Details
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
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Sevillana" rel="stylesheet">
    <!-- FONT AWESOME v4.7.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- JQUERY -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous"></script>
    <!-- BOOTSTRAP JS v3.3.7-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


  </head>
  <body>
    <div class="top_bar">
      <div class="logo">
        <a href="index.php">Social</a>
      </div>
      <nav>
        <a id="user-fn" href="<?php echo $userLoggedIn; ?>"><?php echo 'Hi, ' . $user['first_name'] . '!'; ?></a>
        <a href="#"><i class="fa fa-home" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-bell-o" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-users" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-cogs" aria-hidden="true"></i></a>
      </nav>
    </div>
    <div class="wrapper">
      <!-- .wrapper CLOSING DIV TAG IN index.php -->

<?php
//Turns on Output Buffering
ob_start();

//Session Holding
session_start();
$timezone = date_default_timezone_set('America/New_York');

//Connection Variable
$con = mysqli_connect("localhost", "root", "", "social");

//If error connecting to DB:
if(mysqli_connect_errno()) {
  echo "Failed to connect: " . mysqli_connect_errno();
}

 ?>

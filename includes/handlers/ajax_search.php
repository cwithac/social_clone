<?php

include('../../config/config.php');
include('../../includes/classes/User.php');

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query); //query split into array at ' '

 ?>

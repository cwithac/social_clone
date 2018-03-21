<?php
include('includes/header.php');

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u'])) {
  $user_to = $GET_['u']; //Set in the URL params
} else {
  $user_to = $message_obj->getMostRecentUser(); //Most recent user messaged
  if($user_to == false) {
    $user_to = 'new'; //No conversations yet, start a new 
  }
}

?>

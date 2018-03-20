<?php

require '../../config/config.php';

if(isset($_GET['post_id'])) {
  //If post id has been provided;
  $post_id = $_GET['post_id'];
}

if(isset($_POST['result'])) {
  //Delete yes or no answered
  if($_POST[result] == true) {
    $query - mysqli_query($con, "UPDATE posts SET deleted='yes' WHERE id='$post_id'"); //Hides, does not remove from DB
  }
}

?>

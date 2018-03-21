<?php

class Message {
  private $user;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user);
  }

  public function getMostRecentUser() {
    $userLoggedIn = $this->user_obj->getUsername();

    $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1"); //Single most recent result, message to or from logged in user

    if(mysqli_num_rows($query) == 0) {
      return false;
    }

    $row = mysqli_fetch_array($query);
    $user_to = $row['user_to'];
    $user_from = $row['user_from'];

    if($user_to != $userLoggedIn) {
      return $user_to;
    } else {
      return $user_from;
    }

  }

  public function sendMessage($user_to, $body, $date) {
    if($body != '') {
      //Body is not empty
      $userLoggedIn = $this->user_obj->getUsername();
      //Messages Table: id, user_to, user_from, body, date, opened, viewed, deleted
      $query = mysqli_query($this->con, "INSERT INTO messages VALUES('', '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
    }
  }

}// End  Class



 ?>

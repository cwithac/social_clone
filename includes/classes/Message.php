<?php

class Message {
  private $user;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user); 
  }


}// End  Class



 ?>

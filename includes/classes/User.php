<?php

class User {
  private $user;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user'"); //All information from table on user
    $this->user = mysqli_fetch_array($user_details_query); //Query reuslts as array
  }

  public function getFirstAndLastName() {
    $username = $this->user['username'];
    $query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username'");
    $row = mysqli_fetch_array($query);
    return $row['first_name'] . ' ' . $row['last_name'];
  }

  public function getUsername() {
    return $this->user['username'];
  }

  public function getNumPosts() {
    $username = $this->user['username'];
    $query = mysqli_query($this->con, "SELECT num_posts FROM users WHERE username='$username'");
    $row = mysqli_fetch_array($query);
    return $row['num_posts'];
  }
}

 ?>

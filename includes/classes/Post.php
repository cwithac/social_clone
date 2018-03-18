<?php

class Post {
  private $user_obj;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user); //New instance of User class
  }

  public function submitPost($body, $user_to) {
    $body = strip_tags($body); //Removes html
    $body = mysqli_real_escape_string($this->con, $body); //Protects DB from non conforming chars
    $check_empty = preg_replace('/\s+/', '', $body); //Delete all spaces

    //Confirms there is text within the post
      if($check_empty != '') {
        $date_added = date("Y-m-d H:i:s"); //Current date and time
        $added_by = $this->user_obj->getUsername(); //Get username from User.php

          //If user is on own profile, user_to is 'none'
          if($user_to == $added_by) {
            $user_to = 'none';
          }

          //Insert Post (id, vody, added_by, user_to, date_added, user_closed, deleted, likes)
          $query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')"); //Insert into DB
          $returned_id = mysqli_insert_id($this->con); //Returns the id of the post just submitted

          //Update Post Count for User
          $num_posts = $this->user_obj->getNumPosts(); //Get number of posts from User.php
          $num_posts++;
          $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
      }
  }
}

 ?>

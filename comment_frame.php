<!DOCTYPE html>
<html>
  <head>
    <title>Social</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <?php
    require 'config/config.php';
    include('includes/classes/User.php');
    include('includes/classes/Post.php');

    if(isset($_SESSION['username'])) {
      $userLoggedIn = $_SESSION['username']; //sets the logged in user with the session
      $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
      $user = mysqli_fetch_array($user_details_query); //Access User Details
    } else {
      //if a user is not logged in...
      header('Location: register.php'); //redirects back to register page
    }
    ?>

    <script>
      function toggle() {
        //Toggle show and hide for comments
        var element = document.getElementById('comment_section');
        if(element.style.display = 'block') {
          element.style.display = 'none';
        } else {
          element.style.display = 'block';
        }
      }
    </script>

    <?php

    //Get id of Post
    if(isset($_GET['post_id'])){
      $post_id = $_GET['post_id'];
    }

    $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id=$post_id");
    $row = mysqli_fetch_array($user_query);
    $posted_to = $row['added_by'];

    if(isset($_POST['postComment' . $post_id])) {
      //If the associated post is pressed for the comment ...
      $post_body = $_POST['post_body'];
      $post_body = mysqli_escape_string($con, $post_body);
      $date_time_now = date("Y-m-d H:i:s");
      //Comments Table: id, post_body, posted_by, posted_to, date_added, removed, post_id
      $insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id')");
  		echo "<p>Comment Posted! </p>";
    }

     ?>

     <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
     		<textarea name="post_body"></textarea>
     		<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post">
   	</form>


  </body>
</html>

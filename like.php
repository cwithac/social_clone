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

    //Get id of Post
    if(isset($_GET['post_id'])){
      $post_id = $_GET['post_id'];
    }

    //Get information about likes and associated users
    $get_likes = mysqli_query($con, "SELECT likes, added_by FROM posts WHERE id='$post_id'");
  	$row = mysqli_fetch_array($get_likes);
  	$total_likes = $row['likes'];
  	$user_liked = $row['added_by'];

    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $total_user_likes = $row['num_likes']; //Total for user

    //Like Button & Unlike Buttons
    if(isset($_POST['like_button'])) {
      //Like button is pressed
        $total_likes++;
        $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
        $total_user_likes++;
        $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        //Likes Table: id, username, post_id
        $insert_user = mysqli_query($con, "INSERT INTO likes VALUES('', '$userLoggedIn', '$post_id')");
    }

    if(isset($_POST['unlike_button'])) {
      //UnLike button is pressed
        $total_likes--;
        $query = mysqli_query($con, "UPDATE posts SET likes='$total_likes' WHERE id='$post_id'");
        $total_user_likes--;
        $user_likes = mysqli_query($con, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        //Likes Table: id, username, post_id
        $insert_user = mysqli_query($con, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
    }


      //Check for previous likes
      $check_query = mysqli_query($con, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
      $num_rows = mysqli_num_rows($check_query);

        if($num_rows > 0) {
          //UNLIKE Options
          echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
                  <input type="submit" class="comment_like" name="unlike_button" value="Unlike">
                  <div class="like_value">
                    ' . $total_likes . ' Likes
                  </div>
                </form>
                ';
        } else {
          //LIKE Options
          echo '<form action="like.php?post_id=' . $post_id . '" method="POST">
                  <input type="submit" class="comment_like" name="like_button" value="Like">
                  <div class="like_value">
                    ' . $total_likes . ' Likes
                  </div>
                </form>
                ';
        }

    ?>
  </body>
</html>

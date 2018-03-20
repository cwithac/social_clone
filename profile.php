<?php
include('includes/header.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');

if(isset($_GET['profile_username'])) {
  //Username from .htaccess URL parameter
  $username = $_GET['profile_username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
  $user_array = mysqli_fetch_array($user_details_query); //Array results of user info

    $num_friends = (substr_count($user_array['friend_array'], ',') - 1); //Splice friends array, excluding initial ',' for 0
}
 ?>

<style>
  .wrapper {
    margin-left: 0px;
    padding-left: 0px;
  }
</style>
 <div class="profile_left">
   <img src="<?php echo $user_array['profile_pic']; ?> " alt="user_profile_pic">
   <div class="profile_info">
     <p><?php echo "<strong>Posts:</strong> " . $user_array['num_posts'];?></p>
     <p><?php echo "<strong>Likes:</strong> " . $user_array['num_likes'];?></p>
     <p><?php echo "<strong>Friends:</strong> " . $num_friends;?></p>
   </div>
    <form action=<?php echo $username ?>>
      <?php
        $profile_user_obj = new User($con, $username);
        if($profile_user_obj->isClosed()) {
          //if user's profile is closed...
          header('Location: user_closed.php');
        }
       ?>
    </form>
 </div>

 <div class="main_column column">
   <?php echo $username ?>
 </div>
 <!-- WRAPPER BELOW CLOSE FROM header.php -->
  </div>
  </body>
</html>

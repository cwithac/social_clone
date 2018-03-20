<?php
include('includes/header.php');

if(isset($_GET['profile_username'])) {
  //Username from .htaccess URL parameter
  $username = $_GET['profile_username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
  $user_array = mysqli_fetch_array($user_details_query); //Array results of user info

    $num_friends = (substr_count($user_array['friend_array'], ',') - 1); //Splice friends array, excluding initial ',' for 0
}

  if(isset($_POST['remove_friend'])) {
    //Remove friend button is pressed
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
  }

  if(isset($_POST['add_friend'])) {
    //Add friend button is pressed
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
  }

  if(isset($_POST['respond_request'])) {
    //Respond to request redirect
    header('Location: requests.php');
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
    <form action=<?php echo $username ?> method="POST">
      <?php
        $profile_user_obj = new User($con, $username);
        if($profile_user_obj->isClosed()) {
          //if user's profile is closed...
          header('Location: user_closed.php');
        }
        $logged_in_user_obj = new User($con, $userLoggedIn);
        if($userLoggedIn != $username) {
          //If the logged in user is not the profile in view ...
          if($logged_in_user_obj->isFriend($username)) {
            //If accounts are friends...
            echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
          } else if ($logged_in_user_obj->didRecieveRequest($username)){
            //Response to friend request option
            echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
          } else if ($logged_in_user_obj->didSendRequest($username)){
            //Friend request sent from userLoggedIn to profile user
            echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
          } else {
            //userLoggedIn and profile user are not friends
            echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
        }
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

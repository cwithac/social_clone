<?php
include('includes/header.php');

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u'])) {
  $user_to = ($_GET['u']); //Set in the URL params
} else {
  $user_to = $message_obj->getMostRecentUser(); //Most recent user messaged
  if($user_to == false) {
    $user_to = 'new'; //No conversations yet, start a new
  }
}

if($user_to != 'new') {
  $user_to_obj = new User($con, $user_to);
}

?>

<div class="user_details column">
  <a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>
  <div class="user_details_left_right">
    <a href="<?php echo $userLoggedIn; ?>">
    <?php echo $user['first_name'] . " " . $user['last_name'];?>
    </a>
    <br>
    <?php echo "Posts: " . $user['num_posts']. "<br>";
    echo "Likes: " . $user['num_likes']; ?>
  </div>
</div>

<div class="main_column column" id="main_column">
  <?php
    if($user_to != 'new') {
      echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>";
    }
   ?>
  <div class="loaded_messages">
    <form action="" method="POST">
      <?php
        if($user_to == 'new') {
          echo 'Select the friend you would like to message! <br><br>';
          echo "To: <input type='text'>";
          echo "<div class='results'></div>";
        }  else {
          echo "<textarea name='message_body' id='message_textarea' placeholder='Write something ...'></textarea>";
          echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
        }
       ?>
    </form>
  </div>
</div>

<?php
include('includes/header.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');

if(isset($_POST['post'])) {
  //if post button has been pressed ...
  $post = new Post($con, $userLoggedIn); //New instance of Post class
  $post->submitPost($_POST['post_text'], 'none'); //SubmitPost from Post.php, post_text from form
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
 <div class="main_column column">
   <form class="post_form" action="index.php" method="POST">
     <textarea name="post_text" id="post_text" placeholder="Thoughts?"></textarea>
     <input type="submit" name="post" id="post_button" value="POST">
     <hr>
   </form>
 </div>
 <!-- WRAPPER BELOW CLOSE FROM header.php -->
  </div>
  </body>
</html>

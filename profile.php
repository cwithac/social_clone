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
    <input type="submit" class="default" data-toggle="modal" data-target="#post_form" value="Say something...">
 </div>

 <div class="profile_main_column column">
   <div class="posts_area">

   </div>
   <img src="assets/images/icons/loading.gif" id="loading" alt="loading">
 </div>

 <!-- Modal -->
 <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel">
   <div class="modal-dialog" role="document">
     <div class="modal-content">

       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title" id="myModalLabel">Say something...</h4>
       </div>

       <div class="modal-body">
         <p>Post to another user's profile page and their newsfeed for your friends to see!</p>
         <form class="profile_post" action="" method="POST">
           <div class="form-group">
             <textarea class="form-control" name="post_body"></textarea>
             <input type="hidden" name="user_from" value="<?php echo $userLoggedIn ?>">
             <input type="hidden" name="user_to" value="<?php echo $username ?>">
           </div>
         </form>
       </div>

       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         <button type="button" name="post_button" id="submit_profile_post" class="btn btn-primary">Post</button>
       </div>
     </div>
   </div>
 </div>

<!-- INIFINTE LOADING -->
 <script>
 //Loading Icon
    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    var profileUsername = '<?php echo $username; ?>';
    $(document).ready(function() {
      $('#loading').show();

      //Ajax Request
      $.ajax({
        url: 'includes/handlers/ajax_load_profile_posts.php',
        type: 'POST',
        data: 'page=1&userLoggedIn=' + userLoggedIn + '&profileUsername=' + profileUsername,
        cache: false,
        success: function(data) {
          $('#loading').hide();
          $('.posts_area').html(data); //Returned data from AJAX
        }
      });

      $(window).scroll(function() {
        var height = $('.posts_area').height; //Height of posts container div
        var scroll_top = $(this).scrollTop(); //Top of page at any time
        var page = $('.posts_area').find('.nextPage').val();
        var noMorePosts = $('.posts_area').find('.noMorePosts').val();

          if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
            //If height scrolled is top of window plus the height of the window and more posts available
            $('#loading').show();
            //Ajax Request
            var ajaxReq = $.ajax({
              url: 'includes/handlers/ajax_load_profile_posts.php',
              type: 'POST',
              data: 'page=' + page + '&userLoggedIn=' + userLoggedIn + '&profileUsername=' + profileUsername,
              cache: false,
              success: function(data) {
                $('.posts_area').find('.nextPage').remove(); //Removes current next page
                $('.posts_area').find('.noMorePosts').remove();
                $('#loading').hide();
                $('.posts_area').append(data); //Returned data from AJAX
              }
            });
          } //End if statement
          return false;
      }); //End $(window).scroll(function()
    }); //Document Ready Close


 </script>

 <!-- WRAPPER BELOW CLOSE FROM header.php -->
  </div>
  </body>
</html>

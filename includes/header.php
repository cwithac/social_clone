<?php
require 'config/config.php';
include('includes/classes/User.php');
include('includes/classes/Post.php');
include('includes/classes/Message.php');
include('includes/classes/Notification.php');

if(isset($_SESSION['username'])) {
  $userLoggedIn = $_SESSION['username']; //sets the logged in user with the session
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query); //Access User Details
} else {
  //if a user is not logged in...
  header('Location: register.php'); //redirects back to register page
}
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Social</title>

    <!-- BOOTSTRAP CSS v3.3.7-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Sevillana" rel="stylesheet">
    <!-- FONT AWESOME v4.7.0 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- JCROP -->
    <link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />


    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- JCROP -->
    <script src="assets/js/jquery.Jcrop.js"></script>
    <script src="assets/js/jcrop_bits.js"></script>
    <!-- BOOTSTRAP JS v3.3.7-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- BOOTBOX 4.4.0 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js" charset="utf-8"></script>
    <!-- SCRIPT JS -->
    <script src="assets/js/script.js" charset="utf-8"></script>




  </head>
  <body>
    <div class="top_bar">
      <div class="logo">
        <a href="index.php">Social</a>
      </div>
      <div class="search">
        <form action="search.php" method="GET" name="search_form">
          <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
          <div class="button_holder">
            <img src="assets/images/icons/magnifying_glass.png" alt="magnify">
          </div>
        </form>
        <div class="search_results">

        </div>
        <div class="search_results_footer_empty">

        </div>
      </div>
      <nav>
        <?php

          //Unread messages
          $messages = new Message($con, $userLoggedIn);
          $num_messages = $messages->getUnreadNumber();

          //Unread notifications
          $notifications = new Notification($con, $userLoggedIn);
          $num_notifications = $notifications->getUnreadNumber();

          //Friend request notifications
          $user_obj = new User($con, $userLoggedIn);
          $num_requests = $user_obj->getNumberOfFriendRequests();

         ?>
         <!-- NAVIGATION LINKS -->
        <a id="user-fn" href="<?php echo $userLoggedIn; ?>"><?php echo 'Hi, ' . $user['first_name'] . '!'; ?></a>
        <a href="index.php" style="text-decoration: none"><i class="fa fa-home" aria-hidden="true"></i></a>
        <a href="javascript:void(0);" style="text-decoration: none" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')"><i class="fa fa-envelope" aria-hidden="true"></i>
          <?php
            if($num_messages > 0) {
              echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
            }
          ?>
        </a>
        <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')" style="text-decoration: none"><i class="fa fa-bell" aria-hidden="true"></i>
          <?php
            if($num_notifications > 0) {
              echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
            }
          ?>
        </a>
        <a href="requests.php" style="text-decoration: none"><i class="fa fa-users" aria-hidden="true"></i>
          <?php
            if($num_requests > 0) {
              echo '<span class="notification_badge" id="unread_request">' . $num_requests . '</span>';
            }
          ?>
        </a>
        <a href="settings.php" style="text-decoration: none"><i class="fa fa-cogs" aria-hidden="true"></i></a>
        <a href="includes/handlers/logout.php" style="text-decoration: none"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
      </nav>
      <div class="dropdown_data_window" style="height:0px; border:none;"></div>
        <input type="hidden" id="dropdown_data_type" value="">
      </div>
    </div>

    <!-- INFINITE LOADING -->
     <script>

        var userLoggedIn = '<?php echo $userLoggedIn; ?>';
        $(document).ready(function() {

          $('.dropdown_data_window').scroll(function() {
            var inner_height = $('.dropdown_data_window').innerHeight;
            var scroll_top = $('.dropdown_data_window').scrollTop(); //Top of page at any time
            var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
            var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

              if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {
                var pageName;
                var type = $('#dropdown_data_type').val();
                  if(type == 'notification') {
                    pageName = "ajax_load_notifications.php";
                  } else if (type == 'message') {
                    pageName = "ajax_load_messages.php";
                  }
                //Ajax Request
                var ajaxReq = $.ajax({
                  url: 'includes/handlers/' + pageName,
                  type: 'POST',
                  data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
                  cache: false,
                  success: function(data) {
                    $('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current next page
                    $('.dropdown_data_window').find('.noMoreDropdownData').remove();
                    $('.dropdown_data_window').append(data); //Returned data from AJAX
                  }
                });
              } //End if statement
              return false;
          }); //End $(window).scroll(function()
        }); //Document Ready Close


     </script>

    <div class="wrapper">
      <!-- .wrapper CLOSING DIV TAG IN index.php -->

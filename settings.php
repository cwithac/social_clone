<?php

include('includes/header.php');
include('includes/form_handlers/settings_handler.php');

 ?>

<div class="main_column column">
  <h4>Account Settings</h4>
  <hr>
  <?php
    echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pics'>";
   ?>
   <br>
   <a href="upload.php">Upload New Profile Picture</a> <br><br><br><hr>

   <?php

   //Get updated details refresh
   $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
   $row = mysqli_fetch_array($user_data_query);
   $first_name = $row['first_name'];
   $last_name = $row['last_name'];
   $email = $row['email'];

    ?>
   <h6 class='settings_header'>Update Details</h6>
   <form action="settings.php" method="POST">
     First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" class="settings_input"><br>
     Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" class="settings_input"><br>
     E-Mail: <input type="text" name="email" value="<?php echo $email; ?>" class="settings_input"><br>
     <?php echo $message; ?>
     <input type="submit" name="update_details" class="save_details warning" value="Update Details">
   </form>
<hr>
   <h6 class='settings_header'>Change Password</h6>
   <form action="settings.php" method="POST">
     Old Password: <input type="password" name="old_password" class="settings_input"><br>
     New Password: <input type="password" name="new_password_1" class="settings_input"><br>
     Confirm New Password: <input type="password" name="new_password_2" class="settings_input"><br>
     <?php echo $password_message; ?>
     <input type="submit" name="update_password" class="save_details warning" value="Change Password">
   </form>
<hr>
   <form action="settings.php" method="POST">
     <input class='danger' type="submit" name="close_account" id="close_account" value="Close Account">
   </form>

</div>

<?php

include('includes/header.php');
include('includes/settings_handler.php');

 ?>

<div class="main_column column">
  <h4>Account Settings</h4>
  <?php
    echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pics'>";
   ?>
   <br>
   <a href="upload.php">Upload New Profile Picture</a> <br><br>

   <h6>Update Details</h6>
   <form action="settings.php" method="POST">
     First Name: <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>"><br>
     Last Name: <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>"><br>
     E-Mail: <input type="text" name="email" value="<?php echo $user['email']; ?>"><br>
   </form>

   <h6>Change Password</h6>
   <form action="settings.php" method="POST">
     Old Password: <input type="password" name="old_password"><br>
     New Password: <input type="password" name="new_password_1"><br>
     Confirm New Password: <input type="password" name="new_password_2"><br>
   </form>

   <h6>Close Account</h6>
   <form action="settings.php">
     <input type="submit" name="close_account" id="close_account" value="Close Account">
   </form>

</div>

<?php

include('includes/header.php');

if(isset($_POST['cancel'])) {
  header('Location: settings.php');
}

if(isset($_POST['close_account'])) {
  $close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
  session_destroy();
  header('Location: register.php');
}

 ?>

 <div class="main_column column">
   <h2>Close Account</h2>
   <h4>Are you sure you want to close your account?</h4>
   <p>Closing your account will hide your profile and all activity from other users.</p>
   <p>You can re-open your account at any time by simply loggin in to the site.</p>

   <form action="close_account.php" method="POST">
     <input class='danger' type="submit" name="close_account" id="close_account" value="Yes, close my account">
     <input class='success' type="submit" name="cancel" id="update_details" value="No, keep my account open">
   </form>
 </div>

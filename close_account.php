<?php

include('includes/header.php');

 ?>

 <div class="main_column column">
   <h2>Close Account</h2>
   <h4>Are you sure you want to close your account?</h4>
   <p>Closing your account will hide your profile and all activity from other users.</p>
   <p>You can re-open your account at any time by simply loggin in to the site.</p>

   <form action="close_account.php" method="POST">
     <input type="submit" name="close_account" id="close_account" value="Yes, close my account.">
     <input type="submit" name="cancel" id="update_details" value="No, keep my account open.">
   </form>
 </div>

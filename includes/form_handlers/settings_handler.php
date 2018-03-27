<?php

if(isset($_POST['update_details'])) {
  //Update profile Details
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];

  $email_check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
  $row = mysqli_fetch_array($email_check);
  $matched_user = $row['username'];

  if($matched_user == '' || $matched_user == $userLoggedIn) {
    //No user with email found or email is user logged in
    $message = 'Details updated!<br>';

    $query = mysqli_query($con, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$userLoggedIn'");

  } else {
    $message = 'That e-mail address is already in use.<br>';
  }

} else {
  $message = '';
}

 ?>

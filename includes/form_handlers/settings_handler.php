<?php
//UPDATE DETAILS
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

//UPDATE PASSWORD

if(isset($_POST['update_password'])) {
  $old_password = strip_tags($_POST['old_password']);
  $new_password_1 = strip_tags($_POST['new_password_1']);
  $new_password_2 = strip_tags($_POST['new_password_2']);

  $password_query = mysqli_query($con, "SELECT password FROM users WHERE username='$userLoggedIn'");
  $row = mysqli_fetch_array($password_query);
  $db_password = $row['password'];

  if(md5($old_password) == $db_password) {
    if($new_password_1 == $new_password_2) {
      if(strlen($new_password_1) <= 4) {
        $password_message = 'Password must be at least four characters.';
      } else {
        $new_password_md5 = md5($new_password_1);
        $password_query = mysqli_query($con, "UPDATE users SET password='$new_password_md5' WHERE username='$userLoggedIn'");
        $password_message = 'Password updated!<br>';
      }
    } else {
      $password_message = 'New passwords do not match.<br>';
    }
  } else {
    $password_message = 'Old password not found.<br>';
  }
} else {
  $password_message = '';
}
 ?>

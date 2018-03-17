<?php

if(isset($_POST['login_button'])) {
  //Login Button has been pressed ...
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL); //Confirms email format
    $_SESSION['log_email'] = $email; //Form retain upon error
    $password = md5($_POST['log_password']); //Password encryption confirmation through DB

    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query); //1 = true, 0 = false
    if($check_login_query == 1) {
      //Upon successful login ...
      $row = mysqli_fetch_array($check_database_query); //Results returned from query in array
      $username = $row['username']; //Access username row of succcessful query

        //If user has closed account, reopen upon login ...
          $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
          if(mysqli_num_rows($user_closed_query) == 1) {
            $reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
          }

      $_SESSION['username'] = $username; //New session variable
      header('Location: index.php'); //Redirect upon successful login
      exit();
    } else {
      array_push($error_array, "Email or password was incorrect.<br>");
    }
}

 ?>

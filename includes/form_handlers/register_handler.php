<?php

//Variable Declaration
$fname = ''; //First Name
$lname = ''; //Last Name
$em = ''; //Email
$em2 = ''; //Email Confirm
$password = ''; //Password
$password2 = ''; //Password Confirm
$date = ''; //Registration Date
$error_array = array(); //Error Message Holding

//Form Handling
if(isset($_POST['register_button'])) {
  //Registration button has been clicked ...
    //First Name
    $fname = strip_tags($_POST['reg_fname']); //strip_tags removes HTML tags
    $fname = str_replace(' ', '', $fname); //remove spaces
    $fname = ucfirst(strtolower($fname)); //capitalize first letter after complete lowercase
    $_SESSION['reg_fname'] = $fname; //Stores variable into session variable

    //Last Name
    $lname = strip_tags($_POST['reg_lname']); //strip_tags removes HTML tags
    $lname = str_replace(' ', '', $lname); //remove spaces
    $lname = ucfirst(strtolower($lname)); //capitalize first letter after complete lowercase
    $_SESSION['reg_lname'] = $lname; //Stores variable into session variable

    //Email
    $em = strip_tags($_POST['reg_email']); //strip_tags removes HTML tags
    $em = str_replace(' ', '', $em); //remove spaces
    $em = strtolower($em); //complete lowercase
    $_SESSION['reg_email'] = $em; //Stores variable into session variable

    //Email Confirmation
    $em2 = strip_tags($_POST['reg_email2']); //strip_tags removes HTML tags
    $em2 = str_replace(' ', '', $em2); //remove spaces
    $em2 = strtolower($em); //complete lowercase
    $_SESSION['reg_email2'] = $em2; //Stores variable into session variable

    //Password & Confirmation
    $password = strip_tags($_POST['reg_password']); //strip_tags removes HTML tags
    $password2 = strip_tags($_POST['reg_password2']); //strip_tags removes HTML tags

    //Date
    $date = date("Y-m-d"); //Date Formatting of current date

    // Validation of Email
    if($em == $em2) {
      if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
        $em = filter_var($em, FILTER_VALIDATE_EMAIL);
        //Query for email in database
        $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'"); //If false, account can be created
        $num_rows = mysqli_num_rows($e_check); //If zero, false
          if ($num_rows > 0) {
            array_push($error_array, "Email already in use.<br>");
          }
      } else {
        array_push($error_array, "Invalid e-mail format.<br>");
      }
    } else {
      array_push($error_array, "Emails do not match.<br>");
    }

    //Additional Form Validation
    if(strlen($fname) > 25 || strlen($fname) < 2) {
      array_push($error_array, "Your first name must be between 2 and 25 characters.<br>");
    }
    if(strlen($lname) > 25 || strlen($lname) < 2) {
      array_push($error_array, "Your last name must be between 2 and 25 characters.<br>");
    }
    if($password != $password2) {
      array_push($error_array, "Passwords do not match.<br>");
    } else {
      if(preg_match('/[^A-Za-z0-9]/', $password)) { //Regex Validation
        array_push($error_array, "Your password can only contain letter or numbers.  Special characters are not allowed.<br>");
      }
    }
    if((strlen($password > 30)) || (strlen($password) < 5)) {
      array_push($error_array, "Your password must be between 5 and 30 characters.<br>");
    }

    //Database Insert
    //Final Validation
    if(empty($error_array)) {
      //If no errors...
      $password = md5($password); //MD5 password encryption
      //Generate Username
      $username = strtolower($fname . '_' . $lname);
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'"); //Validate against database
        $i = 0;
        //Add number to username if pre-existing in DB
        while(mysqli_num_rows($check_username_query) !=0) {
          $i++;
          $username = $username . + '_' . $i;
          $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'"); //Confirm against DB with number
        }

        //Generate Profile Pic (Random)
        $rand = rand(1, 2); //Random number between 1-2
        //**TO DO - Update to switch statement for all default images
        if($rand == 1) {
          $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        } else {
          $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        }

        //INSERT
          //id, first_name, last_name, username, email, password, signup_date, profile_pic, num_posts, num_likes, user_closed, friend_array
        $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: blue;'>Registration complete.  Please log in.</span><br>"); //Confirmation Message
        //Clear Forms Upon INSERT
        $_SESSION['reg_fname'] = '';
        $_SESSION['reg_lname'] = '';
        $_SESSION['reg_email'] = '';
        $_SESSION['reg_email2'] = '';
    }
}

 ?>

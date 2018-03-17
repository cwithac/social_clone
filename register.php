<?php
//Session Holding
session_start();

//Connection Variable
$con = mysqli_connect("localhost", "root", "", "social");

//If error connecting to DB:
if(mysqli_connect_errno()) {
  echo "Failed to connect: " . mysqli_connect_errno();
}

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
    $em = ucfirst(strtolower($em)); //capitalize first letter after complete lowercase
    $_SESSION['reg_email'] = $em; //Stores variable into session variable

    //Email Confirmation
    $em2 = strip_tags($_POST['reg_email2']); //strip_tags removes HTML tags
    $em2 = str_replace(' ', '', $em2); //remove spaces
    $em2 = ucfirst(strtolower($em2)); //capitalize first letter after complete lowercase
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
    }
}

 ?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Social</title>
  </head>
  <body>
    <form action="register.php" method="POST">
      <input type="text" name="reg_fname" placeholder="First Name" value="<?php
        if(isset($_SESSION['reg_fname'])) {
          echo $_SESSION['reg_fname'];
        }
      ?>" required>
      <br>
      <?php if(in_array("Your first name must be between 2 and 25 characters.<br>", $error_array)) {
        echo "Your first name must be between 2 and 25 characters.<br>";
      } ?>
      <input type="text" name="reg_lname" placeholder="Last Name" value="<?php
        if(isset($_SESSION['reg_lname'])) {
          echo $_SESSION['reg_lname'];
        }
      ?>" required>
      <br>
      <?php if(in_array("Your last name must be between 2 and 25 characters.<br>", $error_array)) {
        echo "Your last name must be between 2 and 25 characters.<br>";
      } ?>
      <input type="email" name="reg_email" placeholder="Email" value="<?php
        if(isset($_SESSION['reg_email'])) {
          echo $_SESSION['reg_email'];
        }
      ?>" required>
      <br>

      <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
        if(isset($_SESSION['reg_email2'])) {
          echo $_SESSION['reg_email2'];
        }
      ?>" required>
      <br>
      <?php if(in_array("Email already in use.<br>", $error_array)) {
        echo "Email already in use.<br>";
      } else if(in_array("Invalid e-mail format.<br>", $error_array)) {
        echo "Invalid e-mail format.<br>";
      } else if(in_array("Emails do not match.<br>", $error_array)) {
        echo "Emails do not match.<br>";
      } ?>
      <input type="password" name="reg_password" placeholder="Password" required>
      <br>
      <input type="password" name="reg_password2" placeholder="Confirm Password" required>
      <br>
      <?php if(in_array("Passwords do not match.<br>", $error_array)) {
        echo "Passwords do not match.<br>";
      } else if(in_array("Your password can only contain letter or numbers.  Special characters are not allowed.<br>", $error_array)) {
        echo "Your password can only contain letter or numbers.  Special characters are not allowed.<br>";
      } else if(in_array("Your password must be between 5 and 30 characters.<br>", $error_array)) {
        echo "Your password must be between 5 and 30 characters.<br>";
      } ?>
      <input type="submit" name="register_button" value="Register">
    </form>
  </body>
</html>

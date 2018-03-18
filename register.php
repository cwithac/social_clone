<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Social</title>
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Sevillana" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/register.css">
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- JS -->
    <script src="assets/js/register.js" charset="utf-8"></script>
  </head>
  <body>

    <?php
    //Registration page load cleanup for error handling.
    if(isset($_POST['register_button'])) {
      echo '
        <script>
          $(document).ready(function(){
            $("#first").hide();
            $("#second").show();
          });
        </script>
      ';
    };

     ?>

    <div class="wrapper">
      <div class="login_box">
        <div class="login_header">
          <h1>Social</h1>
        </div>
        <div id="first">
          <form action="register.php" method="POST">
            <input type="email" name="log_email" placeholder="Email Address" value="<?php
              if(isset($_SESSION['log_email'])) {
                echo $_SESSION['log_email'];
              }
            ?>" required>
            <br>
            <input type="password" name="log_password" placeholder="Password">
            <br>
            <input type="submit" name="login_button" value="Log In">
            <br>
            <?php if(in_array("Email or password was incorrect.<br>", $error_array)) {
              echo "Email or password was incorrect.<br>";
            } ?>
            <br>
            <a href="#" id="signup" class="signup">Need an account?  Register here.</a>
          </form>
        </div>
        <div id="second">
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
            <br>
            <?php if(in_array("<span style='color: #27ae60;'>Registration complete.  Please log in.</span><br>", $error_array)) {
              echo "<span style='color: #27ae60;'>Registration complete.  Please log in.</span><br>";
            } ?>
            <br>
            <a href="#" id="signin" class="signin">Already have an account?  Log in here.</a>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>

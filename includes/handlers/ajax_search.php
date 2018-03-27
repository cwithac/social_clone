<?php

include('../../config/config.php');
include('../../includes/classes/User.php');

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query); //query split into array at ' '

if(strpos($query, '_') !== false) {
  //If query contains an _ , assume user is searching for username (first_last)
  $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
} else if (count($names) == 2) {
  //If there are two words, assume they are first and last names respectively
  $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
} else {
  //If query has one word only, search all first names and last names
  $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query != '') {
  //if query is not blank
  while($row = mysqli_fetch_array($usersReturnedQuery)) {
    $user = new User($con, $userLoggedIn);
    if($row['username'] != $userLoggedIn) {
      //get mutual friends
      $mutual_friends = $user->getMutualFriends($row['username']) . ' friends in common.';
    } else {
      $mutual_friends = '';
    }

    echo "<div class='resultsDisplay'>
            <a href='" . $row['username'] . "' style='color: #000>
              <div class='liveSearchProfilePic'>
                <img src='" . $row['profile_pic'] . "'>
              </div>
              <div class='liveSearchText'>
                " . $row['first_name'] . ' ' . $row['last_name'] . "
                <p id='grey'>" . $mutual_friends . " </p>
              </div>
            </a>'
          </div>"
  }
}

 ?>

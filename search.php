<?php

include('includes/header.php');

if(isset($_GET['q'])) {
  //query parameter exists
  $query = $_GET['q'];
} else {
  $query = '';
}

//type = username, name
if(isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = 'name';
}

 ?>

 <div class="main_column column" id="main_column">

 	<?php
 	if($query == "")
 		echo "You must enter something in the search box.";
 	else {
 		if($type == "username") {
      $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
    } else {
 			$names = explode(" ", $query);
 			if(count($names) == 3) {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') AND user_closed='no'");
      } else if(count($names) == 2) {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no'");
      } else  {
        $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no'");
      }
 		}

 		//Check if results were found
 		if(mysqli_num_rows($usersReturnedQuery) == 0)
 			echo "No results found with a " . $type . " like: " .$query;
 		else
 			echo mysqli_num_rows($usersReturnedQuery) . " results found: <br> <br>";

 		while($row = mysqli_fetch_array($usersReturnedQuery)) {
 			$user_obj = new User($con, $user['username']);

 			$button = "";
 			$mutual_friends = "";

 			if($user['username'] != $row['username']) {
 				//Generate button depending on friendship status
 				if($user_obj->isFriend($row['username']))
 					$button = "<input type='submit' name='" . $row['username'] . "' class='danger' value='Remove Friend'>";
 				else if($user_obj->didRecieveRequest($row['username']))
 					$button = "<input type='submit' name='" . $row['username'] . "' class='warning' value='Respond to request'>";
 				else if($user_obj->didSendRequest($row['username']))
 					$button = "<input type='submit' class='default' value='Request Sent'>";
 				else
 					$button = "<input type='submit' name='" . $row['username'] . "' class='success' value='Add Friend'>";

 				$mutual_friends = $user_obj->getMutualFriends($row['username']) . " friends in common";


 				//Button forms
 				if(isset($_POST[$row['username']])) {

 					if($user_obj->isFriend($row['username'])) {
 						$user_obj->removeFriend($row['username']);
 						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
 					}
 					else if($user_obj->didReceiveRequest($row['username'])) {
 						header("Location: requests.php");
 					}
 					else if($user_obj->didSendRequest($row['username'])) {

 					}
 					else {
 						$user_obj->sendRequest($row['username']);
 						header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
 					}
 				}
 			} //end if($user['username'] != $row['username'])

 			echo "<div class='search_result'>
 					<div class='searchPageFriendButtons'>
 						<form action='' method='POST'>
 							" . $button . "
 							<br>
 						</form>
 					</div>


 					<div class='result_profile_pic'>
 						<a href='" . $row['username'] ."'><img src='". $row['profile_pic'] ."' style='height: 100px;'></a>
 					</div>

 						<a href='" . $row['username'] ."'> " . $row['first_name'] . " " . $row['last_name'] . "
 						<p id='grey'> " . $row['username'] ."</p>
 						</a>
 						<br>
 						" . $mutual_friends ."<br>

 				</div>
 				<hr id='search_hr'>";

 		} //End while
 	} //End else 	if($query == "")

   ?>

</div>

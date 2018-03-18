<?php

class Post {
  private $user_obj;
  private $con;

  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user); //New instance of User class
  }

  public function submitPost($body, $user_to) {
    $body = strip_tags($body); //Removes html
    $body = mysqli_real_escape_string($this->con, $body); //Protects DB from non conforming chars
    $check_empty = preg_replace('/\s+/', '', $body); //Delete all spaces

    //Confirms there is text within the post
      if($check_empty != '') {
        $date_added = date("Y-m-d H:i:s"); //Current date and time
        $added_by = $this->user_obj->getUsername(); //Get username from User.php

          //If user is on own profile, user_to is 'none'
          if($user_to == $added_by) {
            $user_to = 'none';
          }

          //Insert Post (id, vody, added_by, user_to, date_added, user_closed, deleted, likes)
          $query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')"); //Insert into DB
          $returned_id = mysqli_insert_id($this->con); //Returns the id of the post just submitted

          //Update Post Count for User
          $num_posts = $this->user_obj->getNumPosts(); //Get number of posts from User.php
          $num_posts++;
          $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
      }
  }

  public function loadPostsFriends($data, $limit) {
    $page = $data['page'];
    $userLoggedIn = $this->user_obj->getUsername();

    if($page == 1) {
      $start = 0;
    } else {
      $start = ($page - 1) + $limit;
    };

    $str = ''; //String to return, initialize
    $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

    if(mysqli_num_rows($data_query) > 0) {
      //There are results...
      $num_iterations = 0; //Number of results checked (not necs posted)
      $count = 1;

        while($row = mysqli_fetch_array($data_query)) {
          $id = $row['id'];
          $body = $row['body'];
          $added_by = $row['added_by'];
          $date_time = $row['date_added'];

          //Prepare user_to string to be included if necessary
          if($row['user_to'] == 'none') {
            $user_to  = '';
          } else {
            $user_to_obj = new User($con, $row['user_to']); //New instance of user class
            $user_to_name = $user_to_obj->getFirstAndLastName(); //Gets first and last name of user
            $user_to = "<a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>"; //Return link to the profile page
          }

          //Check if user who posted has a closed accounts (posts will not show)
          $added_by_obj = new User($this->con, $added_by);
          if($added_by_obj->isClosed()) {
            continue; //Returns to start of while loop
          }

          //Friends of check, shows posts only from logged in user and friends of
          $user_logged_obj = new User($this->con, $userLoggedIn);
          if($user_logged_obj->isFriend($added_by)) {

          if($num_iterations++ < $start){ //Gets to all posts to be loaded before ending
            continue;
          }

          if($count > $limit) {
            break; //Once limit posts have been loaded, break;
          } else {
            $count++;
          };

          $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
          $user_row = mysqli_fetch_array($user_details_query);
          $first_name = $user_row['first_name'];
          $last_name = $user_row['last_name'];
          $profile_pic = $user_row['profile_pic'];

          ?>

              <script>
                function toggle<?php echo $id; ?>() {
                  //Toggle show and hide for comments based on $id
                  var element = document.getElementById('toggleComment<?php echo $id; ?>');
                  if(element.style.display == 'block') {
                    element.style.display = 'none';
                  } else {
                    element.style.display = 'block';
                  }
                }
              </script>

          <?php
                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_time); //Time of post
                $end_date = new DateTime($date_time_now); //Current time
                $interval = $start_date->diff($end_date); //Difference between dates

                if($interval->y >= 1) {
                    if($interval == 1) {
                      $time_message = $interval->y . " year ago"; //1 year ago
                    } else {
                      $time_message = $interval->y . " years ago"; //1+ year ago
                    };
                } else if ($interval-> m >= 1) {
                    if($interval->d == 0) {
                      $days = " ago";
                    } else if($interval->d == 1) {
                      $days = $interval->d . " day ago";
                    } else {
                      $days = $interval->d . " days ago";
                    };
                    if($interval->m == 1) {
                      $time_message = $interval->m . " month". $days;
                    } else {
                      $time_message = $interval->m . " months". $days;
                    };
                } else if($interval->d >= 1) {
                    if($interval->d == 1) {
                      $time_message = "Yesterday";
                    } else {
                      $time_message = $interval->d . " days ago";
                    }
                } else if($interval->h >= 1) {
                  if($interval->h == 1) {
                    $time_message = $interval->h . " hour ago";
                  } else {
                    $time_message = $interval->h . " hours ago";
                  }
                } else if($interval->i >= 1) {
                  if($interval->i == 1) {
                    $time_message = $interval->i . " minute ago";
                  } else {
                    $time_message = $interval->i . " minutes ago";
                  }
                } else {
                    if($interval->s < 30) {
                      $time_message = "Just now";
                    } else {
                      $time_message = $interval->s . " seconds ago";
                    }
                }; //END TIME FRAME

    //Create post content html
            $str .= "<div class='status_post' onClick='javascript:toggle$id()'>
                      <div class='post_profile_pic'>
                        <img src='$profile_pic' width='50'>
                      </div>
                      <div class='posted_by' style='color:#bdc3c7;'>
                        <a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;<span class='time_message'>$time_message</span>
                      </div>
                      <div id='post_body'>
                        $body
                        <br>
                      </div>
                    </div>
                    <div class='post_comment' id='toggleComment$id' style='display: none;'>
                      <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder=0></iframe>
                    </div>
                    <hr>";
          } //End of friends of check
      } //End while loop

      if($count > $limit) {
        $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) > "'>
                <input type='hidden' class='noMorePosts' value='false'>";
      } else {
        $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>No more posts to show!</p>";
      }
    } //End if(mysqli_num_rows($data_query))
      echo $str;
  }
} //End Class


 ?>

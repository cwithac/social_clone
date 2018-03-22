<?php
class Notification {
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

  public function getUnreadNumber() {
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->con, "SELECT * FROM notifications WHERE viewed='no' AND user_to='$userLoggedIn'");
    return mysqli_num_rows($query);
  }

  public function insertNotification($post_id, $user_to, $type) {
    $userLoggedIn = $this->user_obj->getUsername();
    $userLoggedInName = $this->user_obj->getFirstAndLastName();
    $date_time = date("Y-m-d H:i:s");

    switch($type) {
      case 'comment':
        $message = $userLoggedInName . " commented on your post.";
        break;
      case 'like':
        $message = $userLoggedInName . " liked your post.";
        break;
      case 'profile':
        $message = $userLoggedInName . " posted on your post.";
        break;
      case 'comment_non_owner':
        $message = $userLoggedInName . " commented on a post you commented on.";
        break;
      case 'profile_comment':
        $message = $userLoggedInName . " commented on your profile post.";
        break;
    }

      $link = "post.php?id=" . $post_id;
      //Notification Table: id, user_to, user_from, message, link, datetime, opened, viewed
      $insert_query = mysqli_query($this->con, "INSERT INTO notifications VALUES('', '$user_to', '$userLoggedIn', '$message', '$link', '$date_time', 'no', 'no')");

  }

} //End Class

  ?>

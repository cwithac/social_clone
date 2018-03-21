$(document).ready(function() {
  //Button for profile peer to peer post
  $('#submit_profile_post').on('click', function() {
    $.ajax({
      type: "POST",
      url: "includes/handlers/ajax_submit_profile_post.php",
      data: $('form.profile_post').serialize(),
      success: function(msg) {
        $("#post_form").modal('hide');
        location.reload();
      },
      error: function() {
        console.log('Failure');
      }
    });
  });
}); //End Document Ready

function getUsers(value, user) {
  $.post('includes/handlers/ajax_friend_search.php', {query:value, userLoggedIn:user}, function(data) {
    $('.results').html(data);
  });
};

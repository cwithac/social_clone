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

//Search clickable functionality
  //Expand searchbar
  $('#search_text_input').focus(function() {
    if(window.matchMedia("(min-width: 800px)").matches) {
      //If the window width > 800px
      $(this).animate({
        width: '250px'
      }, 500);
    }
  });

  $('.button_holder').on('click', function() {
    document.search_form.submit();
  });

}); //End Document Ready

function getUsers(value, user) {
  $.post('includes/handlers/ajax_friend_search.php', {query:value, userLoggedIn:user}, function(data) {
    $('.results').html(data);
  });
};

//NAV BAR dropdown menu
function getDropdownData(user, type) {
  if($(".dropdown_data_window").css("height") == "0px") {
    var pageName;
    if (type == "notification") {
      pageName = "ajax_load_notification.php";
      $("span").remove("#unread_notification");
    } else if (type == "message") {
      pageName = "ajax_load_messages.php";
      $("span").remove("#unread_message");
    }

    var ajaxreq = $.ajax({
      url: "includes/handlers/" + pageName,
      type: "POST",
      data: "page=1&userLoggedIn=" + user,
      cache: false,
      success: function(response) {
        $(".dropdown_data_window").html(response);
        $(".dropdown_data_window").css({
            "padding" : "0px",
            "height": "280px",
            "border-left": "1px solid #95a5a6",
            "border-right": "1px solid #95a5a6",
            "border-bottom": "1px solid #95a5a6"
          });
        $(".dropdown_data_type").val(type);
      }
    });
  } else {
    $(".dropdown_data_window").html("");
    $(".dropdown_data_window").css({"padding":"0px", "height": "0px", "border": "none", "box-shadow": "0 0 0 0"});
  }
};

//Live Search
function getLiveSearchUsers(value, user) {
  $.post("includes/handlers/ajax_search.php", {query: value, userLoggedIn: user}, function(data) {
    if($(".search_results_footer_empty")[0]) {
			$(".search_results_footer_empty").toggleClass("search_results_footer");
			$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
		}

    $('.search_results').html(data);
    $('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");
    if(data == '') {
      $('.search_results_footer').html('');
      $('.search_results_footer').toggleClass('search_results_footer_empty');
      $('.search_results_footer').toggleClass('search_results_footer');
    }
  });
};

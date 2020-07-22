$(document).ready(function() {
$("#search").click(function(popup){
  $(".navbar_box").animate({
    width: "toggle"
  });
$(".searchPopup").css("display", "flex");
$(".searchPopup").html("<h2>Player search</h2>");
$(".searchPopup").append('<form class="user_search" method="post"><input type=text name=username class="username_input" placeholder="Enter players username"><button type=submit name=submit_search_player class=submit_search_player>Search</button </form>')
$(".searchPopup").append('<div class="search_result_box"></div>');
$(".searchPopup").append("<img src='../image/graphics/closeMsg.png' class='close_player_search'>");
});
$(document).on("submit", ".user_search", function(searchplayer) {
  searchplayer.preventDefault();
  let username = $(".username_input").val();
  if (username !== "") {
  $.ajax({
  type: "POST", //execute ajax
    url: './include/player-search.php',
    data: {username: username},
  success: function(getSearchData) {
    let decodedSearch = $.parseJSON(getSearchData);
    $(".search_result_box").html("");
    if (decodedSearch[0] != "empty" && decodedSearch[0] != "You haven't entered any username!") {
    for (var i = 0; i < decodedSearch.length; i++) {
      $(".search_result_box").append("<div class='user_result_wrapper' id='result"+i+"'></div>");
      $("#result"+i+"").append("<div class='profile"+i+" profile_img'></div><div class='info"+i+" profile_info'></div>");
      $(".info"+i+"").html("<p>Username: "+decodedSearch[i][0]+"</p><p>Location: "+decodedSearch[i][3]+":"+decodedSearch[i][1]+":"+decodedSearch[i][2]+"</p><p>Position in leaderboard: "+decodedSearch[i][4]+"</p><p>Rank: <img width='20px' src='../image/ranks/rank"+decodedSearch[i][5]+".png'></p>");
      $("#result"+i+"").append("<div class='social_buttons'><a href='internalMessages.php?username="+decodedSearch[i][0]+"'>Send message!</a></div>");
    }
  } else if (decodedSearch[0] == "empty") {
    $(".search_result_box").append("<p>There were no results matching your search</p>");
  } else if (decodedSearch[0] == "You haven't entered any username!") {
    $(".search_result_box").append("<p>You haven't entered username to search for!</p>");
  }
  }
});//end ajax call
} else {
  $(".search_result_box").append("<p>You haven't entered username to search for!</p>");
}
});
$(document).on("click", ".close_player_search", function(closeSearch) {
  $(".searchPopup").css("display", "none");
  $(".searchPopup").html("");
})
});

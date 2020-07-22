$(document).ready(function(){
  $(".button_leaderboard").click(function(newPage){
    let index = newPage.target.id;
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/leaderboard.php',
      data: {page: index},
    success: function(parsePage) {
    $(".leaderboardAll").html(parsePage);
  }});//end ajax call
});
});

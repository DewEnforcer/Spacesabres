$(document).ready(function(){
  $(".authors").click(function(gameinfo){
    $.getJSON( "../js/authors.json", function( json ) {
      let author = json.people["authors"].leader;
      let specialthanks = json.people["specialthanks"];
      $(".forum_info").html("");
      $(".gameinfo").css("display", "flex");
      $(".game_leader").html("<img src='../image/graphics/closeMsg.png' class='close_info'>");
      $(".game_leader").append("<img src='../image/graphics/logoNew.png' class='logo_info'>");
      $(".game_leader").append("<div class='game_leader_wrapper'><h3 class='h2_author'>Author:<br> "+author+"</h3>");
      $(".game_leader_wrapper").append("<h3>Game developer:<br> "+author+"</h3>");
      $(".game_leader_wrapper").append("<h3>Game designer:<br> "+author+"</h3>");
      $(".game_leader_wrapper").append("<h3>Graphics:<br> "+author+"</h3></div>");
      $(".special_thanks").html("<h2>Special thanks to: </h2>");
      $(".special_thanks").append("<h4>"+specialthanks[0]+"</h4>");
      $(".special_thanks").append("<h4>"+specialthanks[1]+"</h4>");
     });
  });
//  $(".forum").click(function(foruminfo){
  //  $(".gameinfo").css("display", "block");
  //  $(".game_leader").html("<img src='../image/graphics/closeMsg.png' class='close_info'>");
  //  $(".special_thanks").html("");
  //  $(".forum_info").html("<p>Unfortunately there is no forum at the moment, but we are working on it as hard as we can:)!</p>");
  // });
  $(document).on("click", ".close_info", function(){
    $(".gameinfo").css("display","none");
    $(".game_leader").html("");
    $(".special_thanks").html("");
  });
  $(".support").click(function(helpWindow) {
    $("main").append("<div class='help_window'></div>");
    $(".help_window").html("<img src='../image/graphics/closeMsg.png' class='close_help_window'>");
    $(".help_window").append("<h2>Got lost in the galaxy commander?</h2>");
    $(".help_window").append("<h3>Don't worry , it happens to all of us</h3>");
    $(".help_window").append("<p>Have you just started your journey in the huge galaxy of spacesabres and want to know how stuff works around here? Check out our technical guide for new commanders <a href='https://forum.spacesabres.com/thread-1.html'>here</a>!</p>"); // TODO: add new faq link
    $(".help_window").append("<p>Or do you need a help from some of our most experienced commanders? In that case how about visiting our forum? You can visit it by clicking this <a href='../forum/index.php'>link</a>.</p>");
    $(".help_window").append("<p>Not what you were looking for? Are your ships stranded in space or perhaps something broke in yours commander HUD? Please contact our galactic support by following this <a href='../support/index.php'>link</a>!</p>");
    $(".help_window").fadeIn(1000, function() {
    $(".help_window").css("display", "flex");
  });
});
  $(document).on("click", ".close_help_window", (function(closeHelpWindow) {
    $(".help_window").fadeOut(1000, function() {
      $(".help_window").css("display", "none");
      $(".help_window").remove();
    });
  }));
});

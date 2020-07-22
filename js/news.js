$(document).ready(function() {
  function animationNews(newImg, newTitle, newDesc) {
    $(".img_news_wrapper").fadeOut(500, function() {
      $(".img_news_wrapper").css("display", "none");
    });
    setTimeout(function () {
      $(".img_news_wrapper").html("<img src='../image/news/news"+newImg+".png'>");
      $(".img_news_wrapper").fadeIn(500, function() {
        $(".img_news_wrapper").css("display", "block");
      });
    }, 500);
    $(".news_description").html("<h2>"+newTitle+"</h2><p style='text-align: center'>"+newDesc+"</p>");
  }
  $.ajax({
  type: "POST", //execute ajax
    url: '../include/ajaxnews.php',
    data: {index: "all"},
  success: function(getNews) {
    let jsonNews = $.parseJSON(getNews);
    var i = 1;
    let stop = jsonNews["newsImage"].length;
    if (stop > 1) {
      var timeoutNews = setInterval(function() {
      animationNews(jsonNews["newsImage"][i], jsonNews["newsTitle"][i], jsonNews["newsDesc"][i]);
      $("#radio"+i-1+"").prop("checked", false);
      $("#radio"+i+"").prop("checked", true);
      i++;
      if (i >= stop) {
        i = 0;
      }
    }, 15000);
  } else {
    "";
  }
  }
});//end ajax call

});

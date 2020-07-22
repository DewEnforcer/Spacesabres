$(document).ready(function(){
  function countDown (param1, offset) {
    var deadline = new Date(param1).getTime();
    var x = setInterval(function() {
    var now = new Date().getTime()+offset;
    var t = deadline - now;
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.getElementById("research_info_time").innerHTML ="<p>Time remaining: "+ days + "d "
    + hours + "h " + minutes + "m " + seconds + "s </p>";
        if (t < 0) {
            clearInterval(x);
            document.getElementById("research_info_time").innerHTML = "Finished";
        }
    }, 1000);
    }

    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getResearch.php',
        data: {index: "get_res"},
      success: function(getTime) {
        let timeOffset = new Date();
        let offset = (timeOffset.getTimezoneOffset()*60)*1000;
      if (getTime != "1970-01-01 1:00:00" && getTime != "1970-01-01 0:00:00") {
      countDown(getTime, offset);
      }
  }
  });//end ajax call
});

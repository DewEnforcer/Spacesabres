$(document).ready(function(){
  function countDown (param1) {
    var deadline = new Date(param1).getTime();
    var x = setInterval(function() {
    var now = new Date().getTime();
    var t = deadline - now;
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.getElementById("mission_countdown").innerHTML ="<p>Time remaining: "+ days + "d "
    + hours + "h " + minutes + "m " + seconds + "s </p>";
        if (t < 0) {
            clearInterval(x);
            document.getElementById("mission_countdown").innerHTML = "";
        }
    }, 1000);
    }
    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getMissions.php',
        data: {index: "get_mis"},
      success: function(getTime) {
        if (getTime !== "empty") {
          let timeOffset = new Date();
          let offset = (timeOffset.getTimezoneOffset()*60)*1000;
          countDown(getTime, offset);
        } else {
          "";
        }
  }
  });//end ajax call
});

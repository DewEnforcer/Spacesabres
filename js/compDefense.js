$(document).ready(function() {
  let timeOffset = new Date();
  let offset = (timeOffset.getTimezoneOffset()*60)*1000;
  countDown(time, offset);
  function countDown (param1, offset) {
    var deadline = new Date(param1).getTime();
    var x = setInterval(function() {
    var now = new Date().getTime()+offset;
    var t = deadline - now;
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.querySelector(".countdown_def").innerHTML ="<p>"
    + hours + "h " + minutes + "m " + seconds + "s </p>";
        if (t < 0) {
            clearInterval(x);
            document.querySelector(".countdown_def").innerHTML = "Finished";
        }
    }, 1000);
    }

  $("#cancel_defense").click(function(cancelDef) {
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/compDefense.php',
      data: {cancel: "cancel"},
    success: function(defCancel) {
      $("#result_compdef").css("display", "block");
      $('#result_compdef').html(defCancel);
  }
  });//end ajax call
});
});

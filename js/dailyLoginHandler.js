$(document).ready(function() {
  $(".claim_login_reward").click(function(claimBonus) {
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/dailybonusHandler.php',
      data: {action: "claimLogin"},
    success: function(claimResult) {
      if (claimResult == "success") {
        $.ajax({
        type: "POST", //execute ajax
          url: '../include/dailybonusHandler.php',
          data: {action: "getNewVal"},
        success: function(updateValute) {
          let decodedNewVal = $.parseJSON(updateValute);
          if (decodedNewVal[0] != "error") {
            $(".header_center_res").html("<p>Credits: "+decodedNewVal[0]+"</p><p>Hyperid: "+decodedNewVal[1]+"</p><p>Natium: "+decodedNewVal[2]+"</p><p>Your userID: "+decodedNewVal[3]+"</p>")
            $(".daily_login").fadeOut(1000, function() {
            $(".daily_login").css("display", "none");
            })
          } else {
            $(".daily_login").fadeOut(1000, function() {
            $(".daily_login").css("display", "none");
            })
          }
      }
      });//end ajax call
    } else {
      $(".daily_login").fadeOut(1000, function() {
      $(".daily_login").css("display", "none");
      })
    }
  }
  });//end ajax call
  });
});

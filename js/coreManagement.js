$(document).ready(function() {
  var prices = {
    hp: 2,
    shd: 3
  }
  var amounts = {
    hp: 0,
    shd: 0
  }
  function numberFormated(corePoints) {
    return corePoints.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  }
  $(document).on("click", ".btn_repair" , function(shdRep) {
    let type = this.value;
    let amount = amounts[type];
    if (amount == 0) {
    } else {
    $.ajax({
      type: "POST",
      url: "../include/coreManage.php",
      data: {index: type, amount: amount},
      success: function (result) {
        if (result != "error") {
          $("."+type+"_val").animate({
            width: "100%"
          }, 10000);
          let valueOverTime = Math.ceil(amount/100);
          let maxVal = Number($(".base_"+type+"_txt").attr("points"))+Number(amount);
          console.log(maxVal);
          let timeCheck = 0;
          let x = setInterval(function() {
            let previousValue = $(".base_"+type+"_txt").attr("points");
            let newVal = Number(previousValue) + valueOverTime;
            if (newVal > maxVal) {
              newVal = maxVal;
            }
            timeCheck += 100;
            if (timeCheck > 10000) {
              clearInterval(x);
            } else {
              document.querySelector(".base_"+type+"_txt").setAttribute("points", newVal);
              $(".base_"+type+"_txt").html(numberFormated(newVal));
            }
          }, 100);
          $(".amount_"+type+"").html("");
          $(".cost_"+type+"").html("");
          $("main").append('<div class="popup_result"><p>Success!</p><button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>');
        } else if (result == "error") {
          $("main").append('<div class="popup_result"><p>Unfortunately an error has occured.</p><button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>');
        } else if (result == "notenougres") {
          $("main").append('<div class="popup_result"><p>You don\'t have enough resources to start this action!</p><button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>');
        } else if (result == "attack") {
          $("main").append('<div class="popup_result"><p>Cannot start repairs while under attack!</p><button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>');
        }
      },
    });
  }
  });
  $(document).on("click", ".button_confirm_result", function(hideResult){
    $(".popup_result").html("");
    $(".popup_result").css("display", "none");
  });
  $(".slider").on("input",function() {
    let type = this.id
    let amount = this.value;
    amounts[type] = amount;
    $(".amount_"+type+"").html(numberFormated(amount)+" Points");
    $(".cost_"+type+"").html(numberFormated(amount*prices[type]));
  });
});

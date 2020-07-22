$(document).ready(function(){
  function countDown (param1, param3, param4, param5) {
    console.log(param3);
    var deadline = new Date(param1).getTime();
    var x = setInterval(function() {
    var now = new Date().getTime()+param5;
    var t = deadline - now;
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.getElementById(param3).innerHTML ="<p class='last_p'>"+param4+": "+ days + "d "
    + hours + "h " + minutes + "m " + seconds + "s </p>";
        if (t < 0) {
            clearInterval(x);
            document.getElementById(param3).innerHTML = "";

        }
    }, 1000);
    }
    function travelMovement(param1, param2, param3, param4) {
      var deadline = new Date(param1).getTime();
      var now = new Date().getTime()+param4;
      let seconds = Math.floor(deadline - now);
      if (param3 == "left") {
        if (seconds > 0) {
          $(param2).animate({ "left": "+=85%" }, seconds );
        } else {
          $(param2).css("left","85%");
        }
      } else if (param3 == "right") {
        if (seconds > 0) {
          $(param2).animate({ "right": "+=85%" }, seconds );
        } else {
          $(param2).css("right","85%");
        }
      }
    }

    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getAllMov.php',
        data: {index: "get_all_mov"},
      success: function(getMovs) {
     let decodMovs = $.parseJSON(getMovs);
     let decodMovsLen = decodMovs.length;
     let index = 0;
     let time = new Date();
     let offset = (time.getTimezoneOffset()*60)*1000;
     if (decodMovs[0][0] != "empty") {
       while (index <= decodMovs[0].length-1) {
         countDown(decodMovs[0][index], "user_travel_time"+index+"", "Travel time", offset);
         travelMovement(decodMovs[0][index], ".arrow"+index+"", "left", offset);
         index++;
       }
     }
     if (decodMovs[1][0] != "empty") {
     let index = 0;
       while (index <= decodMovs[1].length-1) {
         countDown(decodMovs[1][index], "user_return_time"+index+"", "Travel time", offset);
         travelMovement(decodMovs[1][index], ".arrowReturn"+index+"", "left", offset);
         index++;
       }
     }
     if (decodMovs[2][0] != "empty") {
     let index = 0;
       while (index <= decodMovs[2].length-1) {
         countDown(decodMovs[2][index], "enemy_attack_time"+index+"", "Time till enemy fleet arrives", offset);
         travelMovement(decodMovs[2][index], ".arrowAttack"+index+"", "right", offset);
         index++;
       }
     }
  }
  });//end ajax call
  $(".button_details").click(function(showMovDetails) {
    let index = showMovDetails.target.id;
    let type = $(this).val();
    let classs;
    if (type == "attack") {
      classs = "user_travel_info";
    } else if(type == "return") {
      classs = "user_return_info";
    } else if(type == "enemy_attack") {
      classs = "enemy_attack_info";
    } else {
      return;
    }
    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getAllMov.php',
        data: {index: "getspecificdetails", id: index, type: type},
      success: function(getMovParams) {
      if (getMovParams != "error") {
          $("."+classs+index+"params").html("<img src=../image/graphics/closeMsg.png class='close_mov_params' id='."+classs+index+"params'>");
          $("."+classs+index+"params").append("<h3>Ship(s) detected in this fleet movement: </h3>");
          let decodedParams = $.parseJSON(getMovParams);
          let a = 1;
          while (a <= 6) {
            let attack = "attack"+a;
            let varCheck = decodedParams[attack].split(" ");
            if (Number(varCheck[0]) > 0) {
              $("."+classs+index+"params").append("<p>"+decodedParams[attack]+"</p>");
            }
            a++;
          }
          $("."+classs+index+"params").slideDown("slow", function() {
            $("."+classs+index+"params").css("display", "flex");
        });
        };
  }
  });//end ajax call
  });
  $(document).on("click", ".close_mov_params", function(closeParams) {
    let classs = closeParams.target.id
    $(classs).slideUp(1000 , function() {
      $(classs).css("display", "none");
      $(classs).html("");
  });
  })
})

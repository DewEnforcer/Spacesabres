$(document).ready(function() {
  var formationArr = ["", "Phalanx", "Pincer", "Arrow", "Turtle", "Lance", "Line", "Column", "Echelon", "Wedge"];
  function updateH (id, type) {
    setInterval(function() {
      $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {type: type, id: id, action: "update"},
      });//end ajax call
    }, 20000);
  }

  var formationDesc = $.getJSON( "../js/formationDesc.json", function() {});
  // ↓ regex
  // ↓ enter the lobby
  var id;
  var type;
  $(".btn_join").click(function(joinLobby){
    var formationArr = ["", "Phalanx", "Pincer", "Arrow", "Turtle", "Lance", "Line", "Column", "Echelon", "Wedge"];
    id = $(this).val();
    type = joinLobby.target.id;
    $(".opacity_box").fadeIn(function() {
      $(".opacity_box").css("display", "block");
    })
    $("body").append("<div class='battle_window'></div>");
    $(".battle_window").fadeIn(function() {
      $(".battle_window").css("display", "flex");
      $(".battle_window").html("<div class='spacemap_header'></div><div class='spacemap_main'></div><span id='time'></span>");
      $(".spacemap_header").html("<div class='headers_wrapper'><h2 style='color: white' class='attacker_h3'></h2><h3 style='color: white' class='battle_tag'></h3><h2 style='color: white' class='defender_h3'></h2></div><div class='spacemap_header_wrapper'></div>");
      $(".spacemap_header_wrapper").append(`<div class="wrapper_header_left"></div><div class="wrapper_header_right"></div>`);
      $(".wrapper_header_left").append(`<div class="attacker_fleet param_divs_header"></div><div class="attacker_research param_divs_header"><span>Research</span></div>`);
      $(".wrapper_header_right").append(`<div class="defender_fleet param_divs_header"></div><div class="defender_research param_divs_header"><span>Research</span></div>`);
      $(".attacker_fleet").append(`<div class="attacker_fleet_light fleets_classes"><span>Starfighters</span></div><div class="attacker_fleet_heavy fleets_classes"><span>Battleships</span></div>`);
      $(".defender_fleet").append(`<div class="defender_fleet_light fleets_classes"><span>Starfighters</span></div><div class="defender_fleet_heavy fleets_classes"><span>Battleships</span></div>`);
      $(".spacemap_main").html("<img class='main_planet' src='../image/travelimg/planetright.png'><div class='battle_status_main'></div>");
      $(".battle_status_main").html("<h3 class='text_round'></h3><span class='text_countdown'></span>");
      $(".spacemap_main").append("<div class='main_tools'></div><div class='action_bar_main'></div>");
      for (var i = 1; i <= 10; i++) {
        $(".action_bar_main").append(`<div class="slot_actionbar actionbar${i}" id="${i}"><img src="../image/travelimg/formation${i}.png" class="formation_img" position="${i}" id="${formationArr[i]}"></div>`);
      }
      $(".spacemap_main").append("<div class='hyperspace_box'></div><img src='../image/travelimg/userincoming.gif' class='hyperspace_lane_left'>");
      $(".main_tools").html("<img src='../image/graphics/closeMsg.png' class='close_battle'><div class='display_heavy'><img src='../image/travelimg/hideHeavy.png' class='show_heavy'></div><div class='display_light'><img src='../image/travelimg/hideLight.png' class='show_fighter'></div>");
      $(".battle_window").append("<img src='../image/graphics/loading.gif' class='loading'>");
    });
    $( ".battle_window" ).promise().done(function() {
      //↓ get all the required info
      generateObj(id, type);
      updateH(id, type);
    });
  });
  var cooldown = false;
  $(document).on("click" , ".formation_img", function() {
    if (cooldown == false) {
      let side;
      let file;
      let ship;
        if (type == "user") {
          side = "left";
          file = "formationsAttacker";
        } else if (type == "enemy") {
          side = "right";
          file = "formationsDefender";
        }
      let formation = this.id;
      $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {action: "formationSwitch", battleid: id, type: type, newFormation: formation},
      success: function(parsePage) {
        if (parsePage == "success") {
          cooldown = true;
          $(".formation_img").css({
              "cursor": "auto",
              "opacity": "0.7"
          });
          setTimeout(function () {
            $(".formation_img").css({
                "cursor": "pointer",
                "opacity": "1"
            });
          cooldown = false;
          }, 3000);
          if (formationArr.indexOf(formation) > 5) {
            ship = "heavy";
          } else {
            ship = "fighter";
          }
          $.getJSON( "../js/"+file+".json", function(jsonAttacker) {
            let amount = $("."+ship+"_"+side+"").length;
                for (var i = 1; i <= amount; i++) {
                  $("."+ship+"_"+side+"_"+i+"").animate({
                              left: jsonAttacker.formations[formation][i]["left"],
                              top: jsonAttacker.formations[formation][i]["top"]
                            }, 1000);
                }
          });
        }
    }
    });//end ajax call
    }
  });
  var heavyDisplay = true;
  var lightDisplay = true;
  $(document).on("click", ".close_battle", function(closeBattle) {
    window.location.href = "internalBattlelobbies.php";
  });
  $(document).on("click", ".basecore_right", function() {
    if (baseStats != "") {
      $(".spacemap_main").append("<div class='base_info'></div>");
      $(".base_info").html("<h3>Core status</h3><img src='../image/graphics/closeMsg.png' class='close_info'><span>Hullpoints: "+baseStats["coreHealth"]+"</span><span>Shields: "+baseStats["coreShields"]+"</span>");
    }
  });
  $(document).on("click", ".close_info", function() {
    $(".base_info").remove();
  });
  $(document).on("click", ".show_fighter", function(hideFighters) {
    if (lightDisplay == true) {
      $(".fighter_left").css("display", "none");
      $(".fighter_right").css("display", "none");
      $(".display_light").html("<img src='../image/travelimg/showLight.png' class='show_fighter'>");
      lightDisplay = false;
    } else {
      $(".fighter_right").css("display", "block");
      $(".fighter_left").css("display", "block");
      $(".display_light").html("<img src='../image/travelimg/hideLight.png' class='show_fighter'>");
      lightDisplay = true;
    }
  })
  $(document).on("click", ".show_heavy", function(hideFighters) {
        if (heavyDisplay == true) {
          $(".heavy_left").css("display", "none");
          $(".heavy_right").css("display", "none");
          $(".display_heavy").html("<img src='../image/travelimg/showHeavy.png' class='show_heavy'>");
          heavyDisplay = false;
        } else {
          $(".heavy_right").css("display", "block");
          $(".heavy_left").css("display", "block");
          $(".display_heavy").html("<img src='../image/travelimg/hideHeavy.png' class='show_heavy'>");
          heavyDisplay = true;
        }
  });
  $(document).on("mouseover", ".formation_img", function() {
    if (formationDesc) {
      $(".formation_info").remove();
      const formName = this.id;
      const formDesc = formationDesc.responseJSON.formations[formName].desc;
      let position = $(this).attr("position");
      $(".actionbar"+position+"").append("<div class='formation_info'></div>");
      $(".formation_info").html("<h3 align='center'>"+formName+"</h3>");
      $(".formation_info").append(`<span>${formDesc}</span>`);
    }
  })
  $(document).on("mouseout", ".formation_img", function() {
    $(".formation_info").remove();
  })
});

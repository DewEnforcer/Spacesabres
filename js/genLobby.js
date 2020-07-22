function numberFormated(corePoints) {
  return corePoints.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
// ↓ generates the fleet icons
function generateFleets(param2, arrayFetched) {
let number = [1,1] //fighter num , heavy num;
var allLightAm = arrayFetched[0] + arrayFetched[1] + arrayFetched[2];
var allHeavyAm = arrayFetched[3] + arrayFetched[4] + arrayFetched[5];
let indexArr = [0,0,0,1,1,1];
let shipAmmount = [allLightAm, allHeavyAm];
let i = 0;
let usedIcons = [12,12];
let typeArr = ["fighter", "heavy"];
arrayFetched.forEach(function(shipType) {
    let index = indexArr[i];
    let percentageInFleet = parseFloat(shipType/shipAmmount[index]).toFixed(1);
    let generate = Math.ceil(12*percentageInFleet);
    if (usedIcons[index] - generate < 0) { // prevents to generate more than 12 ships
      generate = usedIcons[index];
    }
    if (generate > arrayFetched[i]) { //prevents to generate more icons than present real ships
      generate = arrayFetched[i];
    }
    for (var k = 1; k <= generate; k++) {
      $(".spacemap_main").append('<img src="../image/travelimg/icon'+typeArr[index]+''+param2+'.png" class="shipicon '+typeArr[index]+'_'+param2+' '+typeArr[index]+'_'+param2+'_'+number[index]+'">');
      number[index]++;
      usedIcons[index]--;
    }
    i++;
});

}
    var formationArr = ["", "Phalanx", "Pincer", "Arrow", "Turtle", "Lance", "Line", "Column", "Echelon", "Wedge"];
function checkEnemyForm(battleid, type) {
  let side;
  let file;
    if (type == "user") {
      side = "right";
      file = "formationsDefender";
    } else if (type == "enemy") {
      side = "left";
      file = "formationsAttacker";
    }
    $.getJSON( "../js/"+file+".json", function(jsonAttacker) {
      let checkEnemyForm = setInterval(function() {
        $.ajax({
          type: "POST", //execute ajax
            url: '../include/getBattle.php',
            data: {battleid: battleid, type: type, action: "formationCheck"},
          success: function(getStatus) {
            if (getStatus != "error") {
              let parseJson = $.parseJSON(getStatus);
                let amountLight = $(".fighter_"+side+"").length;
                let amountHeavy = $(".heavy_"+side+"").length;
                    for (var i = 1; i <= amountLight; i++) {
                      $(".fighter_"+side+"_"+i+"").animate({
                                  left: jsonAttacker.formations[parseJson[0]][i]["left"],
                                  top: jsonAttacker.formations[parseJson[0]][i]["top"]
                                }, 500);
                                i++;
                    }
                    for (var i = 1; i <= amountHeavy; i++) {
                      $(".heavy_"+side+"_"+i+"").animate({
                                  left: jsonAttacker.formations[parseJson[1]][i]["left"],
                                  top: jsonAttacker.formations[parseJson[1]][i]["top"]
                                }, 1500);
                                i++;
                    }
            }
      }
      });//end ajax call
    }, 10000);
      });
}

function hyperspaceBoom (sound, time, arrayFleetAttacker, arrayFleetDefender, formationsAttacker, formationsDefender, type) {
    sound.play();
    $(".hyperspace_box").fadeIn(700, function() {
      $(".hyperspace_box").css("display", "block");
      $(".hyperspace_lane_left").remove();
      $(".target_fleet_un").remove();
    });
    $(".hyperspace_box").fadeOut(250, function() {
      $(".hyperspace_box").css("display", "none");
      $(".hyperspace_lane_left").remove();
    });
    setTimeout(function() {
      if (type == "user") {
        generateFleets("left", arrayFleetAttacker);
        generateFleets("right", arrayFleetDefender);
        generateEnemyFleet("user", formationsAttacker);
        generateEnemyFleet("enemy", formationsDefender);
      } else if (type == "enemy") {
        generateFleets("left", arrayFleetAttacker);
        generateEnemyFleet("user", formationsAttacker);
      }
  }, 600);
}


//↓ creates the hyperspace jump sound effect
var sound = document.createElement("audio");
sound.src = "../sounds/hyperspacejump.mp3";
sound.volume = 0.4;
sound.preLoad = true;
//↓ countdown function , used only when the user is attacking
function countDownAttack (param1, id , type) {
  let timeOffset = new Date();
  let offset = (timeOffset.getTimezoneOffset()*60)*1000;
  var deadline = new Date(param1).getTime();
  var x = setInterval(function() {
  var now = new Date().getTime();
  var t = deadline - now;
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
  var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((t % (1000 * 60)) / 1000);
  $(".text_countdown").html("Time remaining: "+ days + "d "
  + hours + "h " + minutes + "m " + seconds + "s ");
  $("#time").html(t); // <-- required to trigger the hyperspace sound
      if (t < 0) {
          $(".countdown_p").html("The battle is now taking place");
          setTimeout(function() {
          window.location.href= "./include/redirect.php?&id="+id+"&type="+type+"";
          }, 1000);
          clearInterval(x);
      }
  }, 1000);
  }

// ↓ handles the fleet icon locations according to the user formation
function generateEnemyFleet(type, formation) {
  let file;
  let side;
  if (type == "user") {
    file = "formationsAttacker";
    side = "left";
  } else {
    file = "formationsDefender";
    side = "right";
  }
  $.getJSON( "../js/"+file+".json", function(jsonFormation) {
    if (formation[0] != "") {
      let fighterAmount = $(".fighter_"+side+"").length;
      for (var i = 1; i <= fighterAmount; i++) {
        $(".fighter_"+side+"_"+i+"").css({"left": jsonFormation.formations[formation[0]][i].left, "top": jsonFormation.formations[formation[0]][i].top});
      }
    }
    if (formation[1] != "") {
      let heavyAmount = $(".heavy_"+side+"").length;
      for (var i = 1; i <= heavyAmount; i++) {
        $(".heavy_"+side+"_"+i+"").css({"left": jsonFormation.formations[formation[1]][i].left, "top": jsonFormation.formations[formation[1]][i].top})
      }
    }
});
}
  var selected = ["", ""];
  var baseStats = [];
function generateObj(id, type) {
      var status = "start";
  $.ajax({
    type: "POST", //execute ajax
      url: '../include/getBattle.php',
      data: {battleid: id, type: type, action: "start"},
    success: function(getBattleParams) {
      if (getBattleParams != "error" && getBattleParams != "end") {
        let battleParams = $.parseJSON(getBattleParams);
        baseStats = battleParams["battlestationCore"];
        if (battleParams["attackerFleet"].length == 0 || battleParams["defenderFleet"].length == 0) {
          status = "waiting";
          setTimeout(function () {
            $.ajax({
              url: "../include/getBattle.php",
              type: 'POST',
              data: {
                battleid: id,
                type: type,
                action: "additionalData"
              },
              success: function(data) {
                if (data != "error") {
                  data = $.parseJSON(data);
                  if (type == "user") {
                    battleParams["defenderFleet"] = data[0];
                    battleParams["defenderFormations"] = data[1];
                    $(".defender_fleet_light").html(`<span>Starfighters</span><hr><span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
                    $(".defender_fleet_heavy").html(`<span>Battleships</span><hr><span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
                  } else if (type == "enemy") {
                    battleParams["attackerFleet"] = data[0];
                    battleParams["attackerFormations"] = data[1];
                    $(".attacker_fleet_light").html(`<span>Starfighters</span><hr><span>Hornet(s): ${battleParams["attackerFleet"][0]}</span><span>Spacefire(s): ${battleParams["attackerFleet"][1]}</span><span>Starhawk(s): ${battleParams["attackerFleet"][2]}</span>`);
                    $(".attacker_fleet_heavy").html(`<span>Battleships</span><hr><span>Peacemaker(s): ${battleParams["attackerFleet"][3]}</span><span>Centurion(s): ${battleParams["attackerFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["attackerFleet"][5]}</span>`);
                  }
                }
              }
            });
          }, battleParams["end"] * 1000);
        }
        $(".loading").remove();
      $(".battle_tag").html("Battle: #"+battleParams["lobbyID"]+"");
      $(".attacker_h3").html(battleParams["attackerNick"]);
      $(".defender_h3").html(battleParams["targetNick"]);
      $(".text_round").html("Round: "+battleParams["round"]+"");
      if (type == "user") {
        if (status == "start") {
          if (battleParams["round"]>1) {
            $(".defender_research").append(`<hr><span>Weapon systems: ${battleParams["targetResearch"]["researchDmg"]} lvl</span>`);
            $(".defender_research").append(`<span>Hull durability: ${battleParams["targetResearch"]["researchHp"]} lvl</span>`);
            $(".defender_research").append(`<span>Shield energy capacity: ${battleParams["targetResearch"]["researchShd"]} lvl</span>`);
          } else {
            $(".defender_research").append("<hr><span>Weapon systems: ? lvl</span>");
            $(".defender_research").append("<span>Hull durability: ? lvl</span>");
            $(".defender_research").append("<span>Shield energy capacity: ? lvl</span>");
          }

          $(".defender_fleet_light").append(`<hr><span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
          $(".defender_fleet_heavy").append(`<hr><span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
        } else {
          $(".defender_research").append("<hr><span>Weapon systems: ? lvl</span>");
          $(".defender_research").append("<span>Hull durability: ? lvl</span>");
          $(".defender_research").append("<span>Shield energy capacity: ? lvl</span>");

          $(".defender_fleet_light").append(`<hr><span>Hornet(s): ?</span><span>Spacefire(s): ?</span><span>Starhawk(s): ?</span>`);
          $(".defender_fleet_heavy").append(`<hr><span>Peacemaker(s): ?</span><span>Centurion(s): ?</span><span>Na-Thalis destroyer(s): ?</span>`);
        }
        $(".attacker_fleet_light").append(`<hr><span>Hornet(s): ${battleParams["attackerFleet"][0]}</span><span>Spacefire(s): ${battleParams["attackerFleet"][1]}</span><span>Starhawk(s): ${battleParams["attackerFleet"][2]}</span>`);
        $(".attacker_fleet_heavy").append(`<hr><span>Peacemaker(s): ${battleParams["attackerFleet"][3]}</span><span>Centurion(s): ${battleParams["attackerFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["attackerFleet"][5]}</span>`);

        $(".attacker_research").append("<hr><span>Weapon systems: "+battleParams["attackerResearch"]["researchDmg"]+" lvl</span>");
        $(".attacker_research").append("<span>Hull durability: "+battleParams["attackerResearch"]["researchHp"]+" lvl</span>");
        $(".attacker_research").append("<span>Shield energy capacity: "+battleParams["attackerResearch"]["researchShd"]+" lvl</span>");


      } else if (type == "enemy") {
        if (status == "start") {
          if (battleParams["round"] > 1) {
            $(".attacker_research").append(`<span>Weapon systems: ${battleParams["attackerResearch"]["researchDmg"]} lvl</span>`);
            $(".attacker_research").append(`<span>Hull durability: ${battleParams["attackerResearch"]["researchHp"]} lvl</span>`);
            $(".attacker_research").append(`<span>Shield energy capacity: ${battleParams["attackerResearch"]["researchShd"]} lvl</span>`);
          } else {
            $(".attacker_research").append("<span>Weapon systems: ? lvl</span>");
            $(".attacker_research").append("<span>Hull durability: ? lvl</span>");
            $(".attacker_research").append("<span>Shield energy capacity: ? lvl</span>");
          }

          $(".attacker_fleet_light").append(`<hr><span>Hornet(s): ${battleParams["attackerFleet"][0]}</span><span>Spacefire(s): ${battleParams["attackerFleet"][1]}</span><span>Starhawk(s): ${battleParams["attackerFleet"][2]}</span>`);
          $(".attacker_fleet_heavy").append(`<hr><span>Peacemaker(s): ${battleParams["attackerFleet"][3]}</span><span>Centurion(s): ${battleParams["attackerFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["attackerFleet"][5]}</span>`);
        } else {
          $(".attacker_research").append("<span>Weapon systems: ? lvl</span>");
          $(".attacker_research").append("<span>Hull durability: ? lvl</span>");
          $(".attacker_research").append("<span>Shield energy capacity: ? lvl</span>");

          $(".attacker_fleet_light").append(`<hr><span>Hornet(s): ?</span><span>Spacefire(s): ?</span><span>Starhawk(s): ?</span>`);
          $(".attacker_fleet_heavy").append(`<hr><span>Peacemaker(s): ?</span><span>Centurion(s): ?</span><span>Na-Thalis destroyer(s): ?</span>`);
        }

        $(".defender_fleet_light").append(`<hr><span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
        $(".defender_fleet_heavy").append(`<hr><span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
        $(".defender_research").append("<span>Weapon systems: "+battleParams["targetResearch"]["researchDmg"]+" lvl</span>");
        $(".defender_research").append("<span>Hull durability: "+battleParams["targetResearch"]["researchHp"]+" lvl</span>");
        $(".defender_research").append("<span>Shield energy capacity: "+battleParams["targetResearch"]["researchShd"]+" lvl</span>");
      }

        if (battleParams["battlestationInfo"]["slot1"] != "npc") {
          $(".spacemap_main").append("<img src='../image/travelimg/basetravel.png' class='basecore_right'>");
          let i = 1;
          while (i <= 10 ) {
            let moduleType = battleParams["battlestationImg"]["slot"+i+""];
            $(".spacemap_main").append("<img src='../image/travelimg/module"+moduleType+".png' class='module"+i+"_right'>");
            i++;
          }
        }
        countDownAttack((battleParams["time"]*1000), id, type);
        //↓ controls time and triggers the hyperspace event, same rules applied as in the countdown function
        if (battleParams["round"] == 1) {
        let offset = (new Date().getTimezoneOffset()*60)*1000;
        let hyperspaceWhen = ((battleParams["time"]*1000) - (new Date().getTime()))-35000;
        if (hyperspaceWhen < 0) {
          hyperspaceWhen = 1500;
        }
        let async = setTimeout(function() {
          let time = $("#time").html();
          if (time > 30000) {
            let hyperspace = setTimeout(function(){
              $(".target_fleet_un").remove();
              hyperspaceBoom(sound, time, battleParams["attackerFleet"], battleParams["defenderFleet"], battleParams["attackerFormations"], battleParams["defenderFormations"], type);
              if (battleParams["npc"] != "npc") {
                checkEnemyForm(id, type);
              }
              let x = 0;
            }, 5000);
          }
           else if (time < 30000 && time > 0) {
            $(".hyperspace_lane_left").remove();
            generateFleets("left", battleParams["attackerFleet"]);
            generateFleets("right", battleParams["defenderFleet"]);
            generateEnemyFleet("user", battleParams["attackerFormations"]);
            generateEnemyFleet("enemy", battleParams["defenderFormations"]);
            if (battleParams["npc"] != "npc") {
              checkEnemyForm(id, type);
            }
          }
        }, hyperspaceWhen);
      } else if (battleParams["round"] == 2 || battleParams["round"] == 3) {
      let async = setTimeout(function() {
        let time = $("#time").html();
        if (time > 1000) {
          $(".hyperspace_lane_left").remove();
          generateFleets("left", battleParams["attackerFleet"]);
          generateFleets("right", battleParams["defenderFleet"]);
          generateEnemyFleet("user", battleParams["attackerFormations"]);
          generateEnemyFleet("enemy", battleParams["defenderFormations"]);
        }
      }, 2000);
    if (battleParams["npc"] != "npc") {
      checkEnemyForm(id, type);
    }
    }
} else if (battleParams == "end") {
  window.location.href= "./internalInbox.php";
}
}
});//end ajax call
}

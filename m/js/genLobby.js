function numberFormated(corePoints) {
  return corePoints.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
// ↓ generates the fleet icons

function checkEnemyForm(battleid, type, formationLight, formationHeavy) {
  let checkEnemyForm = setInterval(function() {
    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {battleid: battleid, type: type, action: "formationCheck", formationLight: formationLight, formationHeavy: formationHeavy},
      success: function(getStatus) {
        if (getStatus != "error") {
          let parseJson = $.parseJSON(getStatus);
        }
        }
  });//end ajax call
}, 10000);
}

function checkEnemyForm(battleid, type, formationLight, formationHeavy) {
  let checkEnemyForm = setInterval(function() {
    $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {battleid: battleid, type: type, action: "formationCheck", formationLight: formationLight, formationHeavy: formationHeavy},
      success: function(getStatus) {
        if (getStatus != "error") {
          let parseJson = $.parseJSON(getStatus);
            if (type == "user") {
              $(".formation_defender").html("<h2>Current enemy formations:</h2><span>Heavy ships formation: "+parseJSON[1][1]+"</span><span>Spacefighters formation: "+parseJSON[1][0]+"</span>");
            } else if (type == "enemy") {
              $(".formation_attacker").html("<h2>Current enemy formations:</h2><span>Heavy ships formation: "+parseJSON[1][1]+"</span><span>Spacefighters formation: "+parseJSON[1][0]+"</span>");
            }
        }
  }
  });//end ajax call
}, 10000);
}

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
  $(".battle_countdown").html(""+ days + "d "
  + hours + "h " + minutes + "m " + seconds + "s ");
      if (t < 0) {
        $(".battle_countdown").html("The battle is now taking place");
        setTimeout(function () {
          window.location.href= "./include/redirect.php?&id="+id+"&type="+type+"";
        }, 1000);
        clearInterval(x);
      }
  }, 1000);
  }

// ↓ handles the fleet icon locations according to the user formation

function generateObj(id, type) {
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
                    $(".defender_fleet").html(`<span>Starfighters</span><span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
                    $(".defender_fleet").html(`<span>Battleships</span><span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
                  } else if (type == "enemy") {
                    battleParams["attackerFleet"] = data[0];
                    battleParams["attackerFormations"] = data[1];
                    $(".attacker_fleet").html(`<span>Starfighters</span><span>Hornet(s): ${battleParams["attackerFleet"][0]}</span><span>Spacefire(s): ${battleParams["attackerFleet"][1]}</span><span>Starhawk(s): ${battleParams["attackerFleet"][2]}</span>`);
                    $(".attacker_fleet").html(`<span>Battleships</span><span>Peacemaker(s): ${battleParams["attackerFleet"][3]}</span><span>Centurion(s): ${battleParams["attackerFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["attackerFleet"][5]}</span>`);
                  }
                }
              }
            });
          }, battleParams["end"] * 1000);
        }
        $(".loading").remove();
        $(".attacker_nick").html(battleParams["attackerNick"]);
        $(".defender_nick").html(battleParams["targetNick"]);
        $(".round_txt").html("Round: "+battleParams["round"]+"");

        if (type == "user") {
          if (battleParams["round"]>1) {
            $(".defender_research").append(`<span>Weapon systems: ${battleParams["targetResearch"]["researchDmg"]} lvl</span>`);
            $(".defender_research").append(`<span>Hull durability: ${battleParams["targetResearch"]["researchHp"]} lvl</span>`);
            $(".defender_research").append(`<span>Shield energy capacity: ${battleParams["targetResearch"]["researchShd"]} lvl</span>`);
          } else {
            $(".defender_research").append("<span>Weapon systems: ? lvl</span>");
            $(".defender_research").append("<span>Hull durability: ? lvl</span>");
            $(".defender_research").append("<span>Shield energy capacity: ? lvl</span>");
          }

          if (status == "start" || battleParams["round"] > 1) {
            $(".defender_fleet").append(`<span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
            $(".defender_fleet").append(`<span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
          } else {
            $(".defender_fleet").append(`<span>Hornet(s): ?</span><span>Spacefire(s): ?</span><span>Starhawk(s): ?</span>`);
            $(".defender_fleet").append(`<span>Peacemaker(s): ?</span><span>Centurion(s): ?</span><span>Na-Thalis destroyer(s): ?</span>`);
          }

        } else if (type == "enemy") {
          if (battleParams["round"] > 1) {
            $(".attacker_research").append(`<span>Weapon systems: ${battleParams["attackerResearch"]["researchDmg"]} lvl</span>`);
            $(".attacker_research").append(`<span>Hull durability: ${battleParams["attackerResearch"]["researchHp"]} lvl</span>`);
            $(".attacker_research").append(`<span>Shield energy capacity: ${battleParams["attackerResearch"]["researchShd"]} lvl</span>`);
          } else {
            $(".attacker_research").append("<span>Weapon systems: ? lvl</span>");
            $(".attacker_research").append("<span>Hull durability: ? lvl</span>");
            $(".attacker_research").append("<span>Shield energy capacity: ? lvl</span>");
          }
          if (status == "start" || battleParams["round"] > 1) {
            $(".attacker_fleet").append(`<span>Hornet(s): ${battleParams["attackerFleet"][0]}</span><span>Spacefire(s): ${battleParams["attackerFleet"][1]}</span><span>Starhawk(s): ${battleParams["attackerFleet"][2]}</span>`);
            $(".attacker_fleet").append(`<span>Peacemaker(s): ${battleParams["attackerFleet"][3]}</span><span>Centurion(s): ${battleParams["attackerFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["attackerFleet"][5]}</span>`);
          } else {
            $(".attacker_fleet").append(`<span>Hornet(s): ?</span><span>Spacefire(s): ?</span><span>Starhawk(s): ?</span>`);
            $(".attacker_fleet").append(`<span>Peacemaker(s): ?</span><span>Centurion(s): ?</span><span>Na-Thalis destroyer(s): ?</span>`);
          }

          $(".defender_fleet").append(`<span>Hornet(s): ${battleParams["defenderFleet"][0]}</span><span>Spacefire(s): ${battleParams["defenderFleet"][1]}</span><span>Starhawk(s): ${battleParams["defenderFleet"][2]}</span>`);
          $(".defender_fleet").append(`<span>Peacemaker(s): ${battleParams["defenderFleet"][3]}</span><span>Centurion(s): ${battleParams["defenderFleet"][4]}</span><span>Na-Thalis destroyer(s): ${battleParams["defenderFleet"][5]}</span>`);
          $(".defender_research").append("<span>Weapon systems: "+battleParams["targetResearch"]["researchDmg"]+" lvl</span>");
          $(".defender_research").append("<span>Hull durability: "+battleParams["targetResearch"]["researchHp"]+" lvl</span>");
          $(".defender_research").append("<span>Shield energy capacity: "+battleParams["targetResearch"]["researchShd"]+" lvl</span>");
        }

          countDownAttack((battleParams["time"]*1000), id, type);
          if (battleParams["battlestationInfo"]["slot1"] != "npc") {
            let i = 1;
            i = 1;
            if (type == "user") {
              if (battleParams["round"] > 1) {
                $(".defender_base").html("<span>Core Hullpoints: "+numberFormated(battleParams["battlestationCore"][0])+"</span>");
                $(".defender_base").append("<span>Core Shields: "+numberFormated(battleParams["battlestationCore"][1])+" </span>");
              } else {
                $(".defender_base").html("<span>Core Hullpoints: ?</span>");
                $(".defender_base").append("<span>Core Shields: ?</span>");
              }
            } else if (type == "enemy") {
              $(".defender_base").html("<span>Core Hullpoints: "+numberFormated(battleParams["battlestationCore"][0])+"</span>");
              $(".defender_base").append("<span>Core Shields: "+numberFormated(battleParams["battlestationCore"][1])+"</span>");
            }
            // ↓ shows the user targets battlestation parameters
            while (i <= 10 ) {
              let moduleName = battleParams["battlestationInfo"]["slot"+i+""];
              $(".defender_base").append("<span>Slot "+i+": "+moduleName+"</span>");
              i++;
            }
          }

          //checkEnemyForm(id, type);
      } else if (battleParams == "end") {
          window.location.href= "./internalInbox.php";
      } else {
          window.location.href= "./internalBattlelobbies.php";
      }
}
});//end ajax call
}

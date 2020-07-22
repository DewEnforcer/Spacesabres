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

  // ↓ enter the lobby
  var id;
  var type;
  $(".btn_join").click(function(joinLobby){
     id = $(this).val();
     type = joinLobby.target.id;
    $(".opacity_box").fadeIn(function() {
      $(".opacity_box").css("display", "block");
    })
    $("body").append("<div class='battle_window'></div>");
    $(".battle_window").fadeIn(function() {
      $(".battle_window").css("display", "flex");
      $(".battle_window").html("<h2 style='color: white' class='battle_tag'></h2><img src='../image/graphics/closeMsg.png' class='close_battle'><div class='spacemap'></div>");
      $(".spacemap").html("<div class='spacemap_header'></div><div class='spacemap_attacker'></div><div class='spacemap_defender'></div>");
      $(".spacemap_header").html(`<h2>Battle: #${id}</h2><p><span class="attacker_nick"></span> vs <span class="defender_nick"></span></p><span class="round_txt"></span><span>Time remaining: <span class="battle_countdown"></span></span>`);
      $(".spacemap_attacker").html(`<div class="attacker_fleet"></div><div class="attacker_research"></div><div class="attacker_formations"></div>`);
      $(".spacemap_defender").html(`<div class="defender_fleet"></div><div class="defender_research"></div><div class="defender_formations"></div><div class="defender_base"></div>`);
    });
    $( ".battle_window" ).promise().done(function() {
      //↓ get all the required info
      generateObj(id, type);
      updateH(id, type);
    });
  });
  $(document).on("click", ".close_battle", function(closeBattle) {
    window.location.href = "internalBattlelobbies.php";
  });
  $(document).on("click", ".btn_submit_form_heavy", function(changeFormation) {
    changeFormation.preventDefault();
    let time = $("#time").html();
    let id = $(".cruisers_formation").attr("id");
    if (id == undefined) {
      id = $(".fighters_formation").attr("id");
    }
    let type = $(".form_select_heavy").attr("id");
    if (type == "") {
      type = $(".form_select_light").attr("id");
    }
    let formation = $(".form_select_heavy").val();
    if (time > 10000) {
      $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {battleid: id, type: type, action: "switchFormationHeavy", switchFormationHeavy: formation},
      success: function(parsePage) {
      if (parsePage == "success") {
        $("#heavy_p").html(formation);
      }
    }});//end ajax call
    }
  });
  $(document).on("click", ".btn_submit_form_light", function(changeFormation) {
    changeFormation.preventDefault();
    let time = $("#time").html();
    let id = $(".cruisers_formation").attr("id")
    if (id == undefined) {
      id = $(".fighters_formation").attr("id");
    }
    let type = $(".form_select_heavy").attr("id");
    if (type == undefined) {
      type = $(".form_select_light").attr("id");
    }
    let formation = $(".form_select_light").val();
    if (time > 10000) {
      $.ajax({
      type: "POST", //execute ajax
        url: '../include/getBattle.php',
        data: {battleid: id, type: type, action: "switchFormationLight", switchFormationLight: formation},
      success: function(parsePage) {
      if (parsePage == "success") {
        $("#light_p").html(formation);
      }
    }});//end ajax call
    }
  });

});

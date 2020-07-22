$(document).ready(function(){
  $(document).on("click", ".base_wrapper", function(planet){
    let mapID = $(".map_input").val();
    let elementInfo = $(this).prop("id");
    console.log(elementInfo);
    let split = elementInfo.split("type");
    let splitCoords = split[1].split("y");
    let type = split[0];
    console.log(type);
    //let planetID = planet.target.id;
    $.ajax({
    type: "POST", //execute ajax
      url: 'include/getPlanetInfo.php',
      data: {dataX: splitCoords[0],dataY: splitCoords[1], map: mapID, type:type},
    success: function(planetParams) {
    let decodedParams = $.parseJSON(planetParams);
    $(".galaxy_main_map_planet_params").css("display", "flex");
    if (decodedParams["owner"] != "Unknown") {
    $(".galaxy_main_map_planet_params").css("display", "flex");
    $(".galaxy_main_map_planet_params").html("<img src='../image/graphics/closeMsg.png' class='close_planet_info' style='max-width:30px; cursor:pointer; margin-left: calc(100% - 30px);'><p>Target: "+decodedParams["owner"]+"</p>");
    if (decodedParams["attack"] == 0 && type != "Passive" && type != "Ally") {
      $(".galaxy_main_map_planet_params").append("<a href='./include/redirect.php?action=attack&&coordsX="+decodedParams["coordsx"]+"&&coordsY="+decodedParams["coordsy"]+"&&map="+decodedParams["mapLocation"]+"'>Attack this station!</a>");
      if (decodedParams["hackingStatus"] == 1) {
        $(".galaxy_main_map_planet_params").append("<button type='button' class='btn_galaxy_hack' id='"+elementInfo+"'>Attempt to access targets subspace network!</button>");
      } else {
        "";
      }
    } else {
      if (type == "Passive") {
        $(".galaxy_main_map_planet_params").append("<p style='text-align: center;'>This commander has significantly weaker fleet than you!</p>");
      } else if (type == "Ally") {
        $(".galaxy_main_map_planet_params").append("<p style='text-align: center;'>This commander is a member of your alliance</p>");
      }
    }
  } else {
    $(".galaxy_main_map_planet_params").html("<img src='../image/graphics/closeMsg.png' class='close_planet_info' style='max-width:30px; cursor:pointer; margin-left: calc(100% - 30px);'><span align='center'>Target: "+decodedParams["npc"]+"</span><span align='center'>Coordinates X: "+decodedParams["coordsx"]+"</span><span align='center'>Coordinates Y: "+decodedParams["coordsy"]+"</span><span align='center'>Map: "+decodedParams["mapLocation"]+"</span><a href='./include/redirect.php?action=attack&&coordsX="+decodedParams["coordsx"]+"&&coordsY="+decodedParams["coordsy"]+"&&map="+decodedParams["mapLocation"]+"'>Attack this invader!</a>");
  }
  }
  });//end ajax call
  });
  $(".backwards").click(function(mapbackwards){
    let currentmap = $(".map_input").val();
    if (Number(currentmap) == 1) {
      var newmap = 999;
    } else {
      var newmap = Number(currentmap)-1;
    }
    $.ajax({
    type: "POST", //execute ajax
      url: 'include/getNewMap.php',
      data: {newmap: newmap},
    success: function(parsemap) {
    $(".galaxy_main_map_planet_params").html("");
    $(".galaxy_main_map_planet_params").css("display", "none");
    $(".navigation_info").html("Current system displayed in the navigation:<br> "+newmap+"");
    $(".map_input").val(newmap);
    $(".map_mobile").html(parsemap);
  }
  });//end ajax call
  })
  $(".forward").click(function(mapforwards){
    let currentmap = $(".map_input").val();
    if (Number(currentmap) == 999) {
      var newmap = 1;
    } else {
      var newmap = Number(currentmap)+1;
    }
    $.ajax({
    type: "POST", //execute ajax
      url: 'include/getNewMap.php',
      data: {newmap: newmap},
    success: function(parsemap) {
    $(".galaxy_main_map_planet_params").html("");
    $(".galaxy_main_map_planet_params").css("display", "none");
        $(".navigation_info").html("Current system displayed in the navigation:<br> "+newmap+"");
    $(".map_input").val(newmap);
    $(".map_mobile").html(parsemap);
  }
  });//end ajax call
});
$('.map_input').keypress(function(mapEnter){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
      let currentmap = $(".map_input").val();
      if (Number(currentmap) > 999) {
        var newmap = 999;
      } else if (currentmap === ""){
        var newmap = 1;
      }
        else {
        var newmap = Number(currentmap);
      }
      $.ajax({
      type: "POST", //execute ajax
        url: 'include/getNewMap.php',
        data: {newmap: newmap},
      success: function(parsemap) {
      $(".galaxy_main_map_planet_params").html("");
      $(".galaxy_main_map_planet_params").css("display", "none");
      $(".navigation_info").html("Current system displayed in the navigation:<br> "+newmap+"");
      $(".map_input").val(newmap);
      $(".map_mobile").html(parsemap);
    }
    });//end ajax call
    } else {
      "";
    }
});

$(document).on("click", ".close_planet_info", function() {
  $(".galaxy_main_map_planet_params").html("");
  $(".galaxy_main_map_planet_params").css("display", "none");
});

$(document).on("click", ".btn_galaxy_hack", function(attemptHack) {
  let mapID = $(".map_input").val();
  let targetInfo = attemptHack.target.id;
  let split = targetInfo.split("type");
  let splitCoords = split[1].split("y");
  let type = split[0];
  let targetNick = $(".targetNick").attr('id');
  $(".galaxy_main_map_hack_attempt").fadeIn(1000, function() {
      $(".galaxy_main_map_hack_attempt").css("display", "flex");
      $(".galaxy_main_map_hack_attempt").html("<p>Attempting to hack subspace network communications of: <br> "+targetNick+"</p><p class='time_hack_countdown'></p>");
      $(".galaxy_main_map_hack_attempt").append("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
  })
  let cd = 15;
  let attemptHackTime = setInterval(function(){
    $(".time_hack_countdown").html("Time remaining: " + cd + "s");
    cd -= 1;
    if(cd <= 0){
      clearInterval(attemptHackTime);
      $.ajax({
      type: "POST", //execute ajax
        url: './include/hacking.php',
        data: {index: "attemptHack", map: mapID, dataX: splitCoords[0], dataY: splitCoords[1], type: type},
      success: function(hackResult) {
      console.log(hackResult);
      let decodedHackInfo = $.parseJSON(hackResult);
      console.log(decodedHackInfo);
      if (decodedHackInfo[0] == "inputerror") {
        $(".galaxy_main_map_hack_attempt").html("<h2>Unfortunately an error has occured</h2>");
        $(".galaxy_main_map_hack_attempt").append("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
      } else if(decodedHackInfo[0] == "researcherror") {
        $(".galaxy_main_map_hack_attempt").html("<h2>You haven't researched the subsonic network hacking research yet!</h2>");
        $(".galaxy_main_map_hack_attempt").append("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
      } else if(decodedHackInfo[0] == "nofleetsdetected") {
        $(".galaxy_main_map_hack_attempt").html("<h2>No fleet movement was detected</h2>");
        $(".galaxy_main_map_hack_attempt").append("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
      } else if(decodedHackInfo[0] == "attemptfailed") {
        $(".galaxy_main_map_hack_attempt").html("<h2>Unfortunately we weren't capable of breaking through targets subsonic network hashes</h2>");
        $(".galaxy_main_map_hack_attempt").append("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
      } else {
        let i = 0;
        let ammount = decodedHackInfo.length;
        $(".galaxy_main_map_hack_attempt").html("<img src='../image/graphics/closeMsg.png' class='close_galaxy_hack_info'>")
        $(".galaxy_main_map_hack_attempt").append("<h2>Detected current fleet movement of: "+targetNick+"</h2>");
        while (i <= ammount) {
          $(".galaxy_main_map_hack_attempt").css("justify-content", "flex-start");
          $(".galaxy_main_map_hack_attempt").append("<div class='movement_hack_params_wrapper movement_hack_params"+i+"'></div>");
          $(".movement_hack_params"+i+"").html("<h3>Fleet traveling to/from coordinates: "+decodedHackInfo[i]["attackedUserX"]+":"+decodedHackInfo[i]["attackedUserY"]+":"+decodedHackInfo[i]["targetMapLocation"]+" consisting of</h3>");
          $(".movement_hack_params"+i+"").append("<p>"+decodedHackInfo[i]["attack1"]+" Hornet(s)</p><p>"+decodedHackInfo[i]["attack2"]+" Spacefire(s)</p><p>"+decodedHackInfo[i]["attack3"]+" Starhawk(s)</p><p>"+decodedHackInfo[i]["attack4"]+" Peacemaker(s)</p><p>"+decodedHackInfo[i]["attack5"]+" Centurion(s)</p><p>"+decodedHackInfo[i]["attack6"]+" Na-Thalis destroyer(s)</p>");
          $(".movement_hack_params"+i+"").append("<p>This fleet will reach it's target on: "+decodedHackInfo[i]["travelTime"]+" Galaxy central time</p>");
          $(".movement_hack_params"+i+"").fadeIn(1000, function() {
            $(".movement_hack_params"+i+"").css("display", "flex");
          });
          i++
        }
      }
    }
    });//end ajax call
    }
  }, 1000);
})
$(document).on("click", ".close_galaxy_hack_info", function(closeHackAttempt) {
  $(".galaxy_main_map_hack_attempt").fadeOut(1000, function() {
    $(".galaxy_main_map_hack_attempt").css("display", "none");
    $(".galaxy_main_map_hack_attempt").css("justify-content", "center");
    $(".galaxy_main_map_hack_attempt").html("");
  })
});
});

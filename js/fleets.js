$(document).ready(function(){
  $(".button_see_fleet").click(function(showfleet){
    $(".mov_wrapper").css("display", "none");
    $(".fleet_show_fleet_box").css("display", "flex");
    $(".fleet_show_fleet_details").css("display", "block");
    $(".fleet_show_stats_box").css("display", "none");
    $(".fleet_buy_fuel_box").css("display", "none");
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/getUserFleet.php',
      data: {index: "index"},
    success: function(getships) {
      $(".fleet_show_fleet_imgs").html('');
      $(".fleet_show_fleet_details").html('');
    let decodedShips = $.parseJSON(getships);
    let index = 1
    let arrIndex = 0;
    decodedShips.forEach(foreach);
    function foreach(ship) {
      $(".fleet_show_fleet_imgs").append("<div class='img"+index+" fleet_img_divs'><img src='../image/shopImg/ship"+index+".png' class='fleet_ship' id='"+index+"'><span align='center' id='span"+index+"'>"+decodedShips[arrIndex]+"</span></div>")
      index++;
      arrIndex++;
    }
  }
  });//end ajax call
  });
  $(document).on("click", ".fleet_ship", function(showDetails){
    let index = showDetails.target.id;
    let ammount = $("#span"+index+"").text();
    $.getJSON( "../js/shop.json", function(jsonShips) {
      let name = jsonShips.ships[index].name;
      let classs= jsonShips.ships[index].class;
      let description = jsonShips.ships[index].description;
      $(".fleet_show_fleet_details").html("<div class='fleet_show_fleet_details_wrapper'></div>");
      $(".fleet_show_fleet_details_wrapper").html("<h2 class='ship_name'>"+name+"</h2><h3>"+classs+"</h3><p>"+description+"</p><span>You currently own: "+ammount+" of these ships</span><img src='../image/graphics/closeMsg.png' class='close_fleet_details'><button type='button' name='fleet_button_params' class='fleet_button_params' id='"+index+"'>Technical Parameters</button><a href='internalShipyard.php'>Buy more!</a>");
     }); //end json
  });
  $(document).on("click", ".fleet_button_params", function(showParams){
    let index = showParams.target.id;
    let name = $(".ship_name").text();
    $.getJSON( "../js/techparams.json", function(jsonShips) {
      let hull = jsonShips.techparams[index].hull;
      let shd= jsonShips.techparams[index].shields;
      let dmg = jsonShips.techparams[index].damage;
      let speed = jsonShips.techparams[index].speed;
      let bonustohornet = jsonShips.techparams[index].rapidfiretohornet;
      let bonustospacefire = jsonShips.techparams[index].rapidfiretospacefire;
      let bonustostarhawk = jsonShips.techparams[index].rapidfiretostarhawk;
      let bonustopeacemaker = jsonShips.techparams[index].rapidfiretopeacemaker;
      let bonustocenturion = jsonShips.techparams[index].rapidfiretocenturion;
      let bonustonathalis = jsonShips.techparams[index].rapidfiretonathalis;
      $(".fleet_show_fleet_details").append("<div class='fleet_show_fleet_techparams'></div>");
      $(".fleet_show_fleet_techparams").css("display", "flex");
      $(".fleet_show_fleet_techparams").html("<h2>Technical parameters for "+name+"</h2><span>Hull points: "+hull+"</span><span>Shield points: "+shd+"</span><span>Damage: "+dmg+"</span><span>Travel speed: "+speed+"</span><span>Rapid fire on hornets: "+bonustohornet+"</span><span>Rapid fire on spacefires: "+bonustospacefire+"</span><span>Rapid fire on starhawks: "+bonustostarhawk+"</span><span>Rapid fire on peacemakers: "+bonustopeacemaker+"</span><span>Rapid fire on centurions: "+bonustocenturion+"</span><span>Rapid fire on nathalis destroyers: "+bonustonathalis+"</span><img src='../image/graphics/closeMsg.png' class='close_fleet_techparams'>");
     }); //end json
  });
  $(document).on("click", ".close_fleet_details", function(closeDetails) {
    $(".fleet_show_fleet_details").html("");
  })
  $(document).on("click", ".close_fleet_techparams", function(closeDetails) {
    $(".fleet_show_fleet_techparams").html("");
    $(".fleet_show_fleet_techparams").css("display", "none");
  });
  $(".button_see_movement").click(function(showMovement) {
    $(".mov_wrapper").css("display", "flex");
    $(".fleet_show_fleet_box").css("display", "none");
    $(".fleet_show_fleet_details").css("display", "none");
    $(".fleet_show_stats_box").css("display", "none");
    $(".fleet_buy_fuel_box").css("display", "none");
  });
  $(".button_see_stats").click(function(showStats) {
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/getUserFleet.php',
      data: {get: "stats"},
    success: function(getStats) {
      console.log(getStats);
      let decodStats = $.parseJSON(getStats);
      $(".mov_wrapper").css("display", "none");
      $(".fleet_show_fleet_box").css("display", "none");
      $(".fleet_show_stats_box").css("display", "flex");
      $(".fleet_buy_fuel_box").css("display", "none");
      $(".fleet_show_stats_box").html("<h2 align='center' style='margin-top: 10px;'>Your all battle statistics</h2>")
      $(".fleet_show_stats_box").append("<div class='fleet_show_stats_wrapper'></div>");
      $(".fleet_show_stats_wrapper").html("<div class='fleet_show_stats_destroyed'></div><div class='fleet_show_stats_battles'></div>");
      $(".fleet_show_stats_destroyed").html("<h3 align='center'>Destruction statistics:</h3>");
      let index = 0;
      while (index <= 7 ) {
        $(".fleet_show_stats_destroyed").append("<span>"+decodStats[1][index]+" "+decodStats[0][index]+"</span>");
        index++;
      }
      $(".fleet_show_stats_battles").html("<h3 align='center'>Battle statistics:</h3>");
      while (index <= 11 ) {
        $(".fleet_show_stats_battles").append("<span>"+decodStats[1][index]+" "+decodStats[0][index]+"</span>");
        index++;
      }
      }
  });//end ajax call
});
  $(".button_buy_fuel").click(function(buyFuel){
    $(".mov_wrapper").css("display", "none");
    $(".fleet_show_fleet_box").css("display", "none");
    $(".fleet_show_stats_box").css("display", "none");
    $(".fleet_buy_fuel_box").css("display", "block");
    $.ajax({
    type: "POST", //execute ajax
      url: '../include/getUserFleet.php',
      data: {get: "stats"},
    success: function(getFuel) {
      $(".fleet_buy_fuel_box").html('<form class="fuel" action="include/buyFuel.php" method="post">Enter the ammount of fuel you wish to purchase: <input type="number" min="0" name="fuel" placeholder="Enter ammount of fuel" ><input type="hidden" name="fuel_fleet"><button type="submit" name="fuelSubmit">Purchase</button></form>')
      }
  });//end ajax call
  })
});

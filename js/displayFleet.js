$(document).on("click", ".fleet_ship", function(showDetails){
  let index = showDetails.target.id;
  let ammount = $("#span"+index+"").text();
  $.getJSON( "../js/shop.json", function(jsonShips) {
    let name = jsonShips.ships[index].name;
    let classs= jsonShips.ships[index].class;
    let description = jsonShips.ships[index].description;
    $(".fleet_show_fleet_details").html("<div class='fleet_show_fleet_details_wrapper'></div>");
    $(".fleet_show_fleet_details_wrapper").html("<h2 class='ship_name'>"+name+"</h2><h3 class='ship_class'>"+classs+"</h3><p class='ship_desc'>"+description+"</p><span class='ship_ammount_own'>You currently own: "+ammount+" of these ships</span><img src='../image/graphics/closeMsg.png' class='close_fleet_details'><button type='button' name='fleet_button_params' class='fleet_button_params' id='"+index+"'>Technical Parameters</button><a class='btn_buy_more' href='internalShipyard.php'>Buy more!</a>");
   }); //end json
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
});

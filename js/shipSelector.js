$(document).ready(function() {
  function numberFormated(resultCost) {
    return resultCost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
  $(".ship").click(function(ship) {
    $(".confirmpopup").css("display", "none");
    $(".confirmpopup").html("");
  let index = ship.target.id;
  $.getJSON( "../js/shop.json", function(jsonShips) {
    let name = jsonShips.ships[index].name;
    let classs= jsonShips.ships[index].class;
    let description = jsonShips.ships[index].description;
    let price = jsonShips.ships[index].cost;
    $(".iteminfo_container").html("<image src='../image/graphics/closeMsg.png' class='closeitems'>");
    $(".iteminfo_container").css("display", "grid");
    $(".iteminfo_container").css("background-image", 'url("")');
    $(".iteminfo_container").css("background-color", 'rgb(64,64,64, 0.8)');
      $(".iteminfo_container").append("<h3 class='title_name'>"+name+"</h3>");
      $(".iteminfo_container").append("<image class='img_ship' src='../image/shopImg/ship"+index+".png'>");
      $(".iteminfo_container").append("<h4 class='title_class'>"+classs+"</h4>");
      $(".iteminfo_container").append("<p class='shipyard_p_desc'>"+description+"</p>");
      $(".iteminfo_container").append("<p class='shipyard_p_price'>"+price+"</p>");
    $(".iteminfo_container").append("<button type='button' name='techParams' class='techParams' id='"+index+ "'>Technical Parameters</button>");
      $(".iteminfo_container").append("<input type='number' class='ammount' name='ammount' value='1'></input>");
      $(".iteminfo_container").append("<button type='button' name='confirm' class='confirm' id='"+index+ "'>Buy</button>");
   });
 });
 $(".module-shop").click(function(modules) {
   $(".confirmpopup").css("display", "none");
   $(".confirmpopup").html("");
 let index = modules.target.id;
 $.getJSON( "../js/shop.json", function(jsonModules) {
   let name = jsonModules.ships[index].name;
   let description = jsonModules.ships[index].description;
   let price = jsonModules.ships[index].cost;
   $(".iteminfo_container").css("display", "grid");
     $(".iteminfo_container").html("<h3 class='title_name'>"+name+"</h3>");
    $(".iteminfo_container").append("<image src='../image/shopImg/module"+index+".png' class='img_ship'>");
     $(".iteminfo_container").append("<p class='shipyard_p_desc'>"+description+"</p>");
     $(".iteminfo_container").append("<p class='shipyard_p_price'>"+price+"</p>");
         $(".iteminfo_container").append("<button type='button' name='techParams' class='techParams' id='"+index+ "'>Technical Parameters</button>");
     $(".iteminfo_container").append("<image src='../image/graphics/closeMsg.png' class='closeitems'>");
     $(".iteminfo_container").css("background-image", 'url("")');
     $(".iteminfo_container").css("background-color", 'rgb(64,64,64, 0.8)');
     $(".iteminfo_container").append("<input type='number' class='ammount' name='ammount' value='1'></input>");
     $(".iteminfo_container").append("<button type='button' name='confirm' class='confirm' id='"+index+ "'>Buy</button>");
  });
});
$(document).on("click", ".closeitems", function(closeItems) {
  $(".iteminfo_container").css("display", "block");
  $(".iteminfo_container").html('<div class="shop_top_title_container"><h2>Shipyard</h2></div>');
  $(".iteminfo_container").css("background-image", 'url("../image/bg/shipyardcont.webp")');
  $(".iteminfo_container").css("background-color", '');
  $(".confirmpopup").css("display", "none");
});
$(document).on("click", ".confirm", function(confirmPurch) {
    let index=confirmPurch.target.id;
    // creds, hyperid ,natium
    let costCreds = [0,25000, 40000, 50000, 125000, 150000, 500000,100000,150000,150000,100000];
    let costHyperids = [0,0,0,5000,25000,27000,60000,0,0,0,0];
    let costNatiums = [0,0,0,0,0,0,1000,0,0,100,0];
    let ammount = $(".ammount").val();
    let totalCostCreds = numberFormated(ammount*costCreds[index]);
    let totalCostHyperid = numberFormated(ammount*costHyperids[index]);
    let totalCostNatium = numberFormated(ammount*costNatiums[index]);
    $.getJSON( "../js/shop.json", function(jsonItem) {
      let name = jsonItem.ships[index].name;;
      $(".confirmpopup").css("display", "block");
      $(".confirmpopup").html("<p class='shipyard_confirm_question' >Are you sure you wan't to purchase "+ammount+" "+name+"(s) for "+totalCostCreds+" Credits, "+totalCostHyperid+" Hyperid and "+totalCostNatium+" Natiums?</p>");
      $(".confirmpopup").append("<div class='purchase_buttons_wrapper'></div>")
      $(".purchase_buttons_wrapper").append("<a href='include/shipyardhandler.php?&send=shipconstruction&&shipID="+index+"&&ammount="+ammount+"' name='purchaseItem' class='purchaseItem' id='"+index+ "'>Confirm</a>");
        $(".purchase_buttons_wrapper").append("<button type='button' name='cancelPurch' class='cancelPurch' id='"+index+ "'>Cancel</button>");
     });
});
$(document).on("click", ".cancelPurch", function(confirmPurch) {
      $(".confirmpopup").css("display", "none");
      $(".confirmpopup").html("");
});
$(document).on("click", ".techParams", function(showParams) {
  let index = showParams.target.id;
  let name = $(".title_name").text();
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
    $(".iteminfo_container").append("<div class='fleet_show_fleet_techparams'></div>");
    $(".fleet_show_fleet_techparams").css("display", "flex");
    $(".fleet_show_fleet_techparams").css("left", "270px");
    $(".fleet_show_fleet_techparams").html("<h2>Technical parameters for "+name+"</h2><span>Hull points: "+hull+"</span><span>Shield points: "+shd+"</span><span>Damage: "+dmg+"</span><span>Travel speed: "+speed+"</span><span>Rapid fire on hornets: "+bonustohornet+"</span><span>Rapid fire on spacefires: "+bonustospacefire+"</span><span>Rapid fire on starhawks: "+bonustostarhawk+"</span><span>Rapid fire on peacemakers: "+bonustopeacemaker+"</span><span>Rapid fire on centurions: "+bonustocenturion+"</span><span>Rapid fire on nathalis destroyers: "+bonustonathalis+"</span><img src='../image/graphics/closeMsg.png' class='close_fleet_techparams'>");
   }); //end json
});
$(document).on("click", ".close_fleet_techparams", function(closeDetails) {
  $(".fleet_show_fleet_techparams").html("");
  $(".fleet_show_fleet_techparams").css("display", "none");
});
});

<?php
require "./include/accessSecurity.php";
if (isset($_GET["type"]) && isset($_GET["action"])) {
"";
} else {
  header("location: internalDisplayfleet.php");
  exit();
}
 ?>
<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
      <meta name="description" content="">
      <meta name=viewport content="width=device-width, initial-scale=1">
      <?php include "include/font.php"; ?>
      <link rel="stylesheet" href="../css/stylegame.css">
      <link rel="stylesheet" href="../css/styleEquipment.css">
      <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
      <script src="../js/equipment.js"></script>
      <script src="../js/search-player.js"></script>
      <script src="../js/gameinfo.js" charset="utf-8"></script>
      <script src="../js/backgroundmanager.js" charset="utf-8"></script>
      <style>
        .equipment_main_wrapper {
            background-image: url("../image/equipment/<?php echo $_SESSION["shipEquipmentType"]; ?>top.png"), linear-gradient(rgb(10,10,10), rgb(35,35,35), rgb(10,10,10));
            background-repeat: no-repeat;
            background-position: top left;
            background-size:  auto;

        }
      </style>
      <title>SpaceSabres||Templates</title>
    </head>
  <body>
    <header>
      <?php require "include/header.php"; ?>
</header>

    <main>
      <section class="gameinfo">
        <div class="game_leader">

        </div>
        <div class="special_thanks">

        </div>
        <div class="forum_info">

        </div>
      </section>
      <section class="searchPopup">

      </section>
      <?php
      $shipType = $_GET["type"];

        $arrayParams = ["empty", "lasers"=>[0,2,4,10,15,25], "rockets"=>[4,2,0,0,0,0], "energy"=>[1,1,1,2,2,3], "hyperspace"=>[1,1,1,1,1,1]];
       ?>
       <section class="equipment_main_wrapper">
         <div class="equipment_header">
           <h2>Equipment</h2>
           <button type="button" name="button" class="btn_save_setup">Save this configuration</button>
         </div>
         <section class="equipment_main_container">
           <div class="ship_equipment_slots">
             <div class="equipment_slots_wrapper">
               <div class="laser_slots_wrapper ship_wrappers">
                 <div class="laser_text text_wrp">
                   <span>L<br>A<br>S<br>E<br>R<br>S</span>
                 </div>
                 <div class="laser_slots_equip slots_eq" id="laser_ship">
                   <?php
                   $s = 0;
                   for ($i=0; $i < $arrayParams["lasers"][$i]; $i++) {
                     // code...
                   }
                     foreach ($userDock[$type][$i]["lasers"] as $ship) {
                       echo "<div class='laser_slot slot_eq' id='laser_ship_$s' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       echo "</div>";
                       $s++;
                     }
                   ?>
                 </div>
               </div>
               <div class="rocket_slots_wrapper ship_wrappers">
                 <div class="laser_text text_wrp">
                   <span>R<br>O<br>C<br>K<br>E<br>T<br>S</span>
                 </div>
                 <div class="rocket_slots_equip slots_eq" id="rocket_ship">
                   <?php
                     foreach ($userDock[$type][$i]["rockets"] as $ship) {
                       echo "<div class='rocket_slot slot_eq' id='rocket_ship_$s' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       if ($ship > 0) {
                         echo "<img src='../image/equipment/item$ship.jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='rocket_img item_img' id='rocket_eq_".$ship."_$s'>";
                       }
                       echo "</div>";
                       $s++;
                     }
                   ?>
                 </div>
               </div>
               <div class="energy_slots_wrapper ship_wrappers">
                 <div class="laser_text text_wrp">
                   <span>E<br>N<br>E<br>R<br>G<br>Y</span>
                 </div>
                 <div class="energy_slots_equip slots_eq" id="energy_ship">
                   <?php
                     foreach ($userDock[$type][$i]["energy"] as $ship) {
                       echo "<div class='energy_slot slot_eq' id='energy_ship_$s' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       if ($ship > 0) {
                         echo "<img src='../image/equipment/item$ship.jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='energy_img item_img' id='energy_eq_".$ship."_$s'>";
                       }
                       echo "</div>";
                       $s++;
                     }
                   ?>
                 </div>
               </div>
               <div class="hyperspace_slots_wrapper ship_wrappers">
                 <div class="laser_text text_wrp">
                   <span>H<br>Y<br>P<br>E<br>R</span>
                   <span style="margin-left: 5px;">S<br>P<br>A<br>C<br>E</span>
                 </div>
                 <div class="hyperspace_slots_equip slots_eq" id="hyperspace_ship">
                   <?php
                     foreach ($userDock[$type][$i]["hyperspace"] as $ship) {
                       echo "<div class='hyperspace_slot slot_eq' id='hyperspace_ship_$s' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       if ($ship > 0) {
                         echo "<img src='../image/equipment/item$ship.jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='hyperspace_img item_img' id='hyperspace_eq_".$ship."_$s'>";
                       }
                       echo "</div>";
                       $s++;
                     }
                   ?>
                 </div>
               </div>
             </div>
             <div class="equipment_slots_ship_info">
               <h2 style="text-transform: capitalize;"><?php
                 echo $type;
                ?></h2>
                <hr>
               <div class="info_wrapper">
                 <div class="static_params">

                 </div>
                 <?php
                 echo '<img src="../image/equipment/'.$type.'.png" class="img_ship_model">';
                  ?>
                 <div class="equip_params">

                 </div>
               </div>
             </div>
           </div>
           <div class="ship_equipment_items">
             <div class="laser_inv_wrapper inv_wrappers">
               <div class="laser_slots_items slots_items" id="laser_inv">
                 <?php
                 $itemNumbersArray = [[19,20,21], [26], [22,23,24], [25]];
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["lasers"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='laser_item slot_eq' id='laser_inv_$l' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[0][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='laser_img item_img' id='laser_aval_".$itemNumbersArray[0][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }

                  ?>
               </div>
             </div>
             <div class="rocket_inv_wrapper inv_wrappers">
               <div class="rocket_slots_items slots_items" id="rocket_inv">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["rockets"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='rocket_item slot_eq' id='rocket_inv_$l' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[1][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='rocket_img item_img' id='rocket_aval_".$itemNumbersArray[1][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }
                  ?>
               </div>
             </div>
             <div class="energy_inv_wrapper inv_wrappers">
               <div class="energy_slots_items slots_items" id="energy_inv">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["energy"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='energy_item slot_eq' id='energy_inv_$l' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[3][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='energy_img item_img' id='energy_aval_".$itemNumbersArray[3][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }
                  ?>
               </div>
             </div>
             <div class="hyperspace_inv_wrapper inv_wrappers">
               <div class="hyperspace_slots_items slots_items" id="hyperspace_inv">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["hyperspace"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='hyperspace_item slot_eq' id='hyperspace_inv_$l' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[2][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='hyperspace_img item_img' id='hyperspace_aval_".$itemNumbersArray[2][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }
                  ?>
               </div>
             </div>
           </div>

         </section>
       </section>

    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
  </body>
</html>

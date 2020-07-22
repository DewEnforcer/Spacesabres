<?php
  require "./include/accessSecurity.php";
if (isset($_SESSION["shipEquipmentID"]) && isset($_SESSION["shipEquipmentType"]) && isset($_SESSION["dockNumber"])) {
        $dockNumber = $_SESSION["dockNumber"];
        if (is_numeric($dockNumber) === false || $dockNumber > 10 && $dockNumber < 1) {
          header("location: internalDisplayfleet.php");
          exit();
        }
} else {
  header("location: internalDisplayfleet.php");
  exit();
}
function countEquipped (&$ship, $equipment) {
  $amount = 0;
  for ($l=0; $l < count($ship[$equipment]); $l++) {
    if ($ship[$equipment][$l] > 0) {
      $amount++;
    }
  }
  return $amount;
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
            background-image: linear-gradient(rgb(10,10,10), rgb(35,35,35), rgb(10,10,10));
            background-repeat: no-repeat;
            background-position: top left;
            background-size:  auto;

        }
        .ship_equipment_slots {
          background-image: url("../image/equipment/ship<?php echo $_SESSION["shipEquipmentType"]; ?>top.png");
          background-position: center top;
          background-repeat: no-repeat;
        }
      </style>
      <title>SpaceSabres|FleetOverview</title>
    </head>
  <body>
    <header>
      <?php require "include/header.php"; ?>
</header>

    <main>
      <section class="searchPopup">

      </section>
      <?php
      $dock = "dock$dockNumber";
      $sql = mysqli_query($conn, "SELECT $dock, equipment, templates FROM userfleet WHERE userID=$userInfo[userID]");
      $userDock = mysqli_fetch_assoc($sql);
      $templates = unserialize(gzdecode($userDock["templates"]));
      $equipment = unserialize($userDock["equipment"]);
      $userDock = unserialize(gzdecode($userDock[$dock]));

      $type = $_SESSION["shipEquipmentType"];
      $shipID = $_SESSION["shipEquipmentID"];
      $i = 0;
      $status;
      foreach ($userDock as $key) {
        if (is_array($key) == false) {
          $i++;
          continue;
        }
        if ($key["number"] == $shipID) {
          $status = true;
          break;
        }
        $i++;
      }
      if ($status == true) {
        "";
      } else {
        echo "<p>No ship with this number matches your ships in your dock</p>";
        echo "</main>";
        echo "<footer>";
        require "include/footer.php";
        echo "</footer>";
        exit();
      }
       ?>
       <script>
         const templates = <?php echo json_encode($templates[$type]).";"; ?>;
         const shipType = <?php echo $_SESSION["shipEquipmentType"]; ?>;
       </script>
       <section class="equipment_main_wrapper">
         <div class="equipment_header">
           <h2>Equipment</h2>
           <button type="button" name="button" class="btn_save_setup">Save this configuration</button>
         </div>
         <div class="equipment_subheader">
            <select class="template_select">
              <option value="none">Manual</option>
              <?php
              if (empty($templates[$type]) !== true) {
                echo '<option value="template" id="template_'.$type.'">Template</option>';
              }
               ?>
            </select>
            <button type="button" class="btn_tmpl" value="save">Save this configuration as template</button>
         </div>
         <section class="equipment_main_container">
           <div class="ship_equipment_slots">
             <div class="equipment_slots_wrapper">
               <div class="laser_slots_wrapper ship_wrappers">
                 <div class="laser_text text_wrp">
                   <span>L<br>A<br>S<br>E<br>R<br>S</span>
                 </div>
                 <div class="laser_slots_equip slots_eq" id="laser_ship" oncontextmenu="return false;">
                   <?php
                   $s = 0;
                     foreach ($userDock[$i]["lasers"] as $ship) {
                       echo "<div class='laser_slot slot_eq' id='laser_ship_$s' type='$ship' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       if ($ship > 0) {
                         echo "<img src='../image/equipment/item$ship.jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='laser_img item_img' id='laser_eq_".$ship."_$s'>";
                       }
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
                 <div class="rocket_slots_equip slots_eq" id="rocket_ship" oncontextmenu="return false;">
                   <?php
                     foreach ($userDock[$i]["rockets"] as $ship) {
                       echo "<div class='rocket_slot slot_eq' id='rocket_ship_$s' type='$ship' ondrop='drop(event)' ondragover='allowDrop(event)'>";
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
                   <span>S<br>H<br>I<br>E<br>L<br>D<br>S</span>
                 </div>
                 <div class="shields_slots_equip slots_eq" id="shields_ship" oncontextmenu="return false;">
                   <?php
                     foreach ($userDock[$i]["shields"] as $ship) {
                       echo "<div class='shields_slot slot_eq' id='shields_ship_$s' type='$ship' ondrop='drop(event)' ondragover='allowDrop(event)'>";
                       if ($ship > 0) {
                         echo "<img src='../image/equipment/item$ship.jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='shields_img item_img' id='shields_eq_".$ship."_$s'>";
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
                 <div class="hyperspace_slots_equip slots_eq" id="hyperspace_ship" oncontextmenu="return false;">
                   <?php
                     foreach ($userDock[$i]["hyperspace"] as $ship) {
                       echo "<div class='hyperspace_slot slot_eq' id='hyperspace_ship_$s' type='$ship' ondrop='drop(event)' ondragover='allowDrop(event)'>";
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
               $arrayNames = ["Hornet", "Spacefire", "Starhawk", "Peacemaker", "Centurion","Na-Thalis destroyer"];
                 echo $arrayNames[$type];
                ?></h2>
                <hr>
               <div class="info_wrapper">
                 <div class="static_params">
                   <h2>Equipment</h2>
                   <hr>
                   <?php
                   $ship = $userDock[$i];
                   $paramShipSlots = [[0,4,0,1],[2,2,0,1],[4,0,1,1],[12,0,6,1],[15,0,7,1],[25,0,15,1]];
                   echo "<span>".countEquipped($ship, "lasers")."/".$paramShipSlots[$ship["type"]][0]." Lasers</span>";
                   echo "<span>".countEquipped($ship, "rockets")."/".$paramShipSlots[$ship["type"]][1]." Missile platforms</span>";
                   echo "<span>".countEquipped($ship, "shields")."/".$paramShipSlots[$ship["type"]][2]." Shield generators</span>";
                   echo "<span>".countEquipped($ship, "hyperspace")."/".$paramShipSlots[$ship["type"]][3]." Hyperspace engines</span>";
                    ?>
                 </div>
                 <?php
                 echo '<img src="../image/equipment/ship'.$type.'.png" class="img_ship_model">';
                  ?>
                 <div class="equip_params">
                   <h2>Parameters</h2>
                   <hr>
                   <?php
                   echo "<span>Hullpoints: ".number_format($ship["stats"][2], '0', '.',' ')."</span>";
                   echo "<span>Shields: ".number_format($ship["stats"][1], '0', '.',' ')."</span>";
                   echo "<span>Maximal damage: ".number_format($ship["stats"][0], '0', '.',' ')."</span>";
                    ?>
                 </div>
               </div>
             </div>
           </div>
           <div class="ship_equipment_items">
             <div class="laser_inv_wrapper inv_wrappers">
               <div class="laser_slots_items slots_items" id="laser_inv" oncontextmenu="return false;">
                 <?php
                 $itemNumbersArray = [[19,20,21], [26], [22,23,24], [25]];
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["lasers"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='laser_item slot_eq laser_slot_inv_".$itemNumbersArray[0][$k]."' id='laser_inv_$l' ondrop='drop(event)' type='".$itemNumbersArray[0][$k]."' ondragover='allowDrop(event)'>";
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
               <div class="rocket_slots_items slots_items" id="rocket_inv" oncontextmenu="return false;">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["rockets"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='rocket_item slot_eq rocket_slot_inv_".$itemNumbersArray[1][$k]."' id='rocket_inv_$l' ondrop='drop(event)' type='".$itemNumbersArray[1][$k]."' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[1][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='rocket_img item_img' id='rocket_aval_".$itemNumbersArray[1][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }
                  ?>
               </div>
             </div>
             <div class="shields_inv_wrapper inv_wrappers">
               <div class="shields_slots_items slots_items" id="shields_inv" oncontextmenu="return false;">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["shields"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='shields_item slot_eq shields_slot_inv_".$itemNumbersArray[3][$k]."' id='shields_inv_$l' ondrop='drop(event)' type='".$itemNumbersArray[3][$k]."' ondragover='allowDrop(event)'>";
                     echo "<img src='../image/equipment/item".$itemNumbersArray[3][$k].".jpg' oncontextmenu='return false;' draggable='true' ondragstart='drag(event)' class='shields_img item_img' id='shields_aval_".$itemNumbersArray[3][$k]."_$l'>";
                     echo "</div>";
                     $l++;
                   }
                   $k++;
                 }
                  ?>
               </div>
             </div>
             <div class="hyperspace_inv_wrapper inv_wrappers">
               <div class="hyperspace_slots_items slots_items" id="hyperspace_inv" oncontextmenu="return false;">
                 <?php
                 $k = 0;
                 $l = 0;
                 foreach ($equipment["hyperspace"] as $key) {
                   for ($i=0; $i < $key; $i++) {
                     echo "<div class='hyperspace_item slot_eq hyperspace_slot_inv_".$itemNumbersArray[2][$k]."' id='hyperspace_inv_$l' ondrop='drop(event)' type='".$itemNumbersArray[2][$k]."' ondragover='allowDrop(event)'>";
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

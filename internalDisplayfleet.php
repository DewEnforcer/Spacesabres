<?php
  require "./include/accessSecurity.php";

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
      <link rel="stylesheet" href="../css/styleDisplayfleet.css">
      <script src="../js/jquery-3.3.1.min.js" charset="utf-8"></script>
      <script src="../js/dock.js"></script>
      <script src="../js/search-player.js"></script>
      <script src="../js/gameinfo.js" charset="utf-8"></script>
      <script src="../js/backgroundmanager.js" charset="utf-8"></script>
      <title>SpaceSabres||Docks</title>
    </head>
  <body>
    <header>
      <?php require "include/header.php"; ?>
</header>

    <main>
      <section class="searchPopup">

      </section>

      <section class="all_fleets_box">
          <h1>Docks</h1>
          <div class="navbar_fleet_over">
            <a href="internalFleets.php" class="button_see_movement">Fleet movement</a>
            <a href="internalBattlelobbies.php" class="button_see_movement">Commander HUD</a>
            <a href="internalDisplayfleet.php" class="button_see_fleet">Docks</a>
            <a href="internalDisplaystats.php" class="button_see_stats">Battle statistics</a>
          </div>
          <div class="fleet_show_fleet_box">
            <table class="wrapper_squadrons">
              <tr class="header_tr">
                <th>Ship ID</th>
                <th>Ship type</th>
                <th>Equipment</th>
              </tr>
              <?php
                $sql = mysqli_query($conn, "SELECT dock1, dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
                $userSquadron = mysqli_fetch_assoc($sql);

                $userDock = unserialize(gzdecode($userSquadron["dock1"]));
                if (empty($userDock) === TRUE) {
                  echo "<tr><td colspan=\"3\">Currently there are no ships present in the dock</tr>";
                } else {
                  $arrayParams = ["lasers"=>[0,2,4,12,15,25], "rockets"=>[4,2,0,0,0,0], "shields"=>[0,0,1,6,7,15], "hyperspace"=>[1,1,1,1,1,1]];
                  $arrayNames = ["Hornet","Spacefire","Starhawk","Peacemaker","Centurion","Na-Thalis Destroyer"];
                  foreach ($userDock as $ship) {
                      echo "<tr class=\"ship_tab\" id=\"$ship[number]\" type=\"$ship[type]\">";
                      echo "<td>$ship[number]</td>";
                      echo "<td>".$arrayNames[$ship["type"]]."</td>";
                      $lasers = countEquipped($ship, "lasers");
                      $rockets = countEquipped($ship, "rockets");
                      $energy =  countEquipped($ship, "shields");
                      $hyperspace = countEquipped($ship, "hyperspace");
                      echo "<td><div class='equipment_wrapper'>Lasers: $lasers/".$arrayParams["lasers"][$ship["type"]]." | Missile platforms: $rockets/".$arrayParams["rockets"][$ship["type"]]." | Shield generators: $energy/".$arrayParams["shields"][$ship["type"]]." | Hyperspace engines: $hyperspace/".$arrayParams["hyperspace"][$ship["type"]]." </div></td>";
                      echo "</tr>";
                  }
                }

              ?>
            </table>
            <div class="menu_squadrons">
              <h2>Tools</h2>
              <button type="button" name="button" class="btn_help_squadron">Help</button>
              <hr>
              <div class="menu_docks_wrapper menu_dock">
                <h3>Current dock: <span class="dock_curr_sp">1</span></h3>
                <?php
                for ($i=1; $i <= $userSquadron["dockAmount"]; $i++) {
                  echo '<button type="button" name="button" class="btn_dock_show" id="'.$i.'">Switch to Dock-'.$i.'</button>';
                }
                 ?>
              </div>
              <div class="menu_equipment_wrapper menu_dock">
                <h3>Equipment</h3>
                <button type="button" name="button" class="btn_dock_manage">Equip selected ship</button>
                <button type="button" name="button" class="btn_dock_scrap">Scrap selected ship</button>
                <button type="button" name="button" class="btn_dock_scrap_all">Scrap all ships</button>
              </div>
              <div class="menu_equipment_wrapper menu_dock">
                <h3>Filters</h3>
                <button type="button" name="button" class="btn_filter" id="0">Show only Hornets</button>
                <button type="button" name="button" class="btn_filter" id="1">Show only Spacefires</button>
                <button type="button" name="button" class="btn_filter" id="2">Show only Starhawks</button>
                <button type="button" name="button" class="btn_filter" id="3">Show only Peacemakers</button>
                <button type="button" name="button" class="btn_filter" id="4">Show only Centurions</button>
                <button type="button" name="button" class="btn_filter" id="5">Show only Na-Thalis destroyers</button>
                <button type="button" name="button" class="btn_filter_all">Show all ships</button>
              </div>
            </div>
          </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
  </body>
</html>

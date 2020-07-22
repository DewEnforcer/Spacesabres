<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
  } else {
    session_unset();
    session_destroy();
    header("location: ../index.php?error=10");
    exit();
  }
} else {
  header("location: ../index.php?error=10");
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
      <meta name=viewport content="width=device-width, initial-scale=1">
      <?php include "include/font.php"; ?>
      <link rel="stylesheet" href="css/game.css">
      <link rel="stylesheet" href="css/docks.css">
      <?php
      require 'include/head.php';
       ?>
      <script src="./js/dock.js"></script>
      <title>SpaceSabres||Docks</title>
    </head>
  <body>
    <?php
    require 'include/nav.php';
     ?>
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

      <section class="all_fleets_box">
          <h1>Fleet Overview</h1>
          <div class="docks_header">
            <div class="dock_wrapper">
              <h3>Current dock: <span class="curr_dock">1</span></h3>
      <select class="dock_select">
        <?php
        $sql = mysqli_query($conn, "SELECT dock1, dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
        $userSquadron = mysqli_fetch_assoc($sql);
        for ($i=1; $i <= $userSquadron["dockAmount"]; $i++) {
          echo "<option value='$i'>Dock-$i</option>";
        }
         ?>
      </select>
            </div>
            <div class="filter_wrapper">
              <span>Show only:</span>
              <select class="select_filter">
                <option value="all">All</option>
                <option value="0">Hornets</option>
                <option value="1">Spacefires</option>
                <option value="2">Starhawks</option>
                <option value="3">Peacemakers</option>
                <option value="4">Centurions</option>
                <option value="5">Na-Thalis destroyers</option>
              </select>
            </div>
            <button type="button" name="button" class="btn_dock_scrap_all">Scrap all ships</button>
          </div>
          <div class="fleet_show_fleet_box">
            <table class="wrapper_squadrons">
              <tr class="header_tr">
                <th>Number</th>
                <th>Ship type</th>
                <th>Equipment</th>
              </tr>
              <?php

                $userDock = unserialize(gzdecode($userSquadron["dock1"]));
                if (empty($userDock) === TRUE) {
                  echo "<tr><td colspan=\"3\">Currently there are no ships present in the dock</tr>";
                } else {
                  $arrayParams = ["lasers"=>[0,2,4,10,15,25], "rockets"=>[4,2,0,0,0,0], "shields"=>[0,0,1,6,7,15], "hyperspace"=>[1,1,1,1,1,1]];
                  $arrayNames = ["Hornet","Spacefire","Starhawk","Peacemaker","Centurion","Na-Thalis Destroyer"];
                  foreach ($userDock as $ship) {
                      echo "<tr class=\"ship_tab\" id=\"$ship[number]\" type=\"$ship[type]\">";
                      echo "<td>$ship[number]</td>";
                      echo "<td>".$arrayNames[$ship["type"]]."</td>";
                      $lasers = countEquipped($ship, "lasers");
                      $rockets = countEquipped($ship, "rockets");
                      $energy =  countEquipped($ship, "shields");
                      $hyperspace = countEquipped($ship, "hyperspace");
                      echo "<td><div class='equipment_wrapper'>Lasers: $lasers/".$arrayParams["lasers"][$ship["type"]]." <br> Missile platforms: $rockets/".$arrayParams["rockets"][$ship["type"]]." <br> Shield generators: $energy/".$arrayParams["shields"][$ship["type"]]." <br> Hyperspace engines: $hyperspace/".$arrayParams["hyperspace"][$ship["type"]]." </div></td>";
                      echo "</tr>";
                  }
                }

              ?>

            </table>
          </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
  </body>
</html>

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
$sql = mysqli_query($conn, "SELECT dock1, dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
$ships = mysqli_fetch_assoc($sql);
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleBriefing.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <?php require "include/scripts.php" ?>
    <script src="../js/briefing.js"></script>
    <script>
      var docks = <?php echo $ships["dockAmount"]; ?>;
      var available = <?php if ($userInfo["defenseCooldown"] <= date("U")) {
        if (mysqli_num_rows($sqlMovement) > 0) {
          echo "\"onway\"";
        } else {
            echo "true";
        }
      } else {
        echo "false";
      } ?>;
      var time = "<?php echo date("Y-m-d G:i:s", $userInfo["defenseCooldown"]); ?>";
    </script>
    <title>SpaceSabres||Briefing</title>
  </head>
  <body>
        <?php require "include/header.php"; ?>
    <main>
      <?php
      require "include/bars.php";
      if (isset($_GET["error"])) {
        if ($_GET["error"]== "hyperid") {
          echo ' <div class="popup_result">
              <p>You do not have enough hyperid!.</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"]== "cantattackyourself") {
          echo ' <div class="popup_result">
              <p>You cannot attack yourself!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif ($_GET["error"]== "notfound") {
          echo ' <div class="popup_result">
              <p>The target hasn´t been found</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        elseif ($_GET["error"]== "fuel") {
          echo ' <div class="popup_result">
              <p>To initiate this attack you are missing '.$_GET["ammount"].' fuel!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        elseif ($_GET["error"]== "noshipsselected") {
          echo ' <div class="popup_result">
              <p>You haven´t selected any ships!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        elseif ($_GET["error"]== "noships") {
          echo ' <div class="popup_result">
              <p>You don´t have this many ships to send!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        elseif ($_GET["error"]== "emtpycoords") {
          echo ' <div class="popup_result">
              <p>You haven´t filled out all the coordinates!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        elseif ($_GET["error"]== "core0") {
          echo ' <div class="popup_result">
              <p>Your fleet center has been destroyed!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      if (isset($_GET["success"]) && $_GET["success"] == 1) {
        echo ' <div class="popup_result">
            <p>The fuel has been successfully purchased!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
      } elseif (isset($_GET["success"]) && $_GET["success"] == "attacksent") {
        echo '<div class="popup_result">
            <p>The fleet has been successfully deployed!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
      }
       ?>
      <section class="searchPopup">

      </section>

      <?php
      if (isset($_SESSION["coordsX"]) && isset($_SESSION["coordsY"]) && isset($_SESSION["map"]) && $_SESSION["coordsX"] != "" && $_SESSION["coordsY"] != "" && $_SESSION["map"] != "") {
        $X = "value=\"$_SESSION[coordsX]\"";
        $Y = "value=\"$_SESSION[coordsY]\"";
        $map = "value=\"$_SESSION[map]\"";
      } else {
        $X = "placeholder=\"Enter X coordinates\"";
        $Y = "placeholder=\"Enter Y coordinates\"";
        $map = "placeholder=\"Enter target system\"";
      }
      $dock = unserialize(gzdecode($ships["dock1"]));
       ?>
      <section class="briefing_main_container">
        <h2>Briefing</h2>
        <hr style="width: calc(100% - 4px);">
        <h3>Current dock: <span class="curr_dock">1</span></h3>
        <div class="briefing_wrapper">
          <div class="briefing_list_ships">
            <div class="briefing_header_docks">
              <?php
              for ($i=1; $i <= $ships["dockAmount"]; $i++) {
                echo "<button type='button' class='btn_dock' id='$i'>Dock-$i</button>";
              }
               ?>
            </div>
            <table class="ship_list">
              <tr class="tr_header">
                <th>Selected</th>
                <th>Ship ID</th>
                <th>Ship type</th>
                <th>Equipment</th>
              </tr>
                <?php
                $arrayParams = ["lasers"=>[0,2,4,10,15,25], "rockets"=>[4,2,0,0,0,0], "shields"=>[0,0,1,6,7,15], "hyperspace"=>[1,1,1,1,1,1]];
                $arrayNames = ["Hornet","Spacefire","Starhawk","Peacemaker","Centurion","Na-Thalis Destroyer"];
                $arrayData = ["hornet","spacefire","starhawk","peacemaker","centurion","nathalis"];
                    foreach ($dock as $ship) {
                        echo "<tr class=\"ship_tab\" id=\"$ship[number]\" type='$ship[type]'>";
                        echo "<td><input type='checkbox' name='ship_checkbox' class='ship_checkbox' ship=\"$ship[type]\" id='ship_$ship[number]'></td>";
                        echo "<td>$ship[number]</td>";
                        echo "<td>".$arrayNames[$ship["type"]]."</td>";
                        $lasers = countEquipped($ship, "lasers");
                        $rockets = countEquipped($ship, "rockets");
                        $energy =  countEquipped($ship, "shields");
                        $hyperspace = countEquipped($ship, "hyperspace");
                        echo "<td><div class='equipment_wrapper'>Lasers: $lasers/".$arrayParams["lasers"][$ship["type"]]." | Missile platforms: $rockets/".$arrayParams["rockets"][$ship["type"]]." | Shield generators: $energy/".$arrayParams["shields"][$ship["type"]]." | Hyperspace engines: $hyperspace/".$arrayParams["hyperspace"][$ship["type"]]." </div></td>";
                        echo "</tr>";
                    }
                 ?>
            </table>
          </div>
          <div class="briefing_tools">
            <div class="mission_type section_tool">
              <h2>Mission</h2>
              <span>Select mission type</span>
              <button type="button" name="button" class="btn_mission_type" id="mission_1" value="1" style="border-color: white;">Standard attack</button><button type="button" name="button" value="2" id="mission_2" class="btn_mission_type">Company defense</button>
            </div>
            <div class="coordinates section_tool">
              <h2>Coordinates</h2>
              <form class="form_brief_coord" action="index.html" method="post">
                <div>
                  <span>Coordinates X:</span><input type="number" class="coords_x" <?php echo $X ?>>
                </div>
                <div>
                  <span>Coordinates Y:</span><input type="number" class="coords_y" <?php echo $Y ?>>
                </div>
                <div>
                  <span>System:</span><input type="number" class="map" <?php echo $map ?>>
                </div>
                <button type="button" name="button" class="btn_submit_attack">Deploy fleet!</button>
              </form>
            </div>
            <div class="fleet_tools section_tool">
              <h2>Fleet</h2>
              <div>
                <button type="button" name="button" class="btn_select" value="0" id="select">Select all Hornets</button>
                <button type="button" name="button" class="btn_select" value="0" id="unselect">Unselect all Hornets</button>
                <button type="button" name="button" class="btn_select" value="1" id="select">Select all Spacefires</button>
                <button type="button" name="button" class="btn_select" value="1" id="unselect">Unselect all Spacefires</button>
                <button type="button" name="button" class="btn_select" value="2" id="select">Select all Starhawks</button>
                <button type="button" name="button" class="btn_select" value="2" id="unselect">Unselect all Starhawks</button>
                <button type="button" name="button" class="btn_select" value="3" id="select">Select all Peacemakers</button>
                <button type="button" name="button" class="btn_select" value="3" id="unselect">Unselect all Peacemakers</button>
                <button type="button" name="button" class="btn_select" value="4" id="select">Select all Centurions</button>
                <button type="button" name="button" class="btn_select" value="4" id="unselect">Unselect all Centurions</button>
                <button type="button" name="button" class="btn_select" value="5" id="select">Select all Na-Thalis destroyers</button>
                <button type="button" name="button" class="btn_select" value="5" id="unselect">Unselect all Na-Thalis destroyers</button>
              </div>
            </div>
            <div class="menu_equipment_wrapper section_tool">
              <h3>Filters</h3>
              <button type="button" name="button" class="btn_filter" value="0">Show only Hornets</button>
              <button type="button" name="button" class="btn_filter" value="1">Show only Spacefires</button>
              <button type="button" name="button" class="btn_filter" value="2">Show only Starhawks</button>
              <button type="button" name="button" class="btn_filter" value="3">Show only Peacemakers</button>
              <button type="button" name="button" class="btn_filter" value="4">Show only Centurions</button>
              <button type="button" name="button" class="btn_filter" value="5">Show only Na-Thalis destroyers</button>
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

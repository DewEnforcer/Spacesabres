<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, defenseCooldown, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    $sqlMovement = mysqli_query($conn, "SELECT userID FROM usermovement WHERE userID=$userInfo[userID] AND missionType=3 AND returnWay=1");
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
$sql = mysqli_query($conn, "SELECT dock1, dockAmount FROM userfleet WHERE userID=$userInfo[userID]");
$ships = mysqli_fetch_assoc($sql);

  if (isset($_SESSION["coordsX"]) && isset($_SESSION["coordsY"]) && isset($_SESSION["map"])) {
    $coordX= "value='".$_SESSION["coordsX"]."'";
    $coordY = "value='".$_SESSION["coordsY"]."'";
    $map = "value='".$_SESSION["map"]."'";
  } else {
    $coordX= "";
    $coordY = "";
    $map = "";
  }

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/briefing.css">
    <script>
    var coordsX = "<?php echo $coordX; ?>";
    var coordsY = "<?php echo $coordY; ?>";
    var coordsMap = "<?php echo $map; ?>";
    </script>
  <?php
  require 'include/head.php';
   ?>
    <script src="./js/briefing.js"></script>
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
    <?php
    require 'include/nav.php';
     ?>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <?php
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
      <section class="gameinfo">
        <div class="game_leader">

        </div>
        <div class="special_thanks">

        </div>
        <div class="forum_info">

        </div>
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
        <div class="briefing_wrapper">
          <div class="briefing_list_ships">
            <div class="briefing_header_docks">
                      <h3>Current dock: <span class="curr_dock">1</span></h3>
              <select class="dock_select">
                <?php
                for ($i=1; $i <= $ships["dockAmount"]; $i++) {
                  echo "<option value='$i'>Dock-$i</option>";
                }
                 ?>
              </select>
              <button type="button" class="mission_params">Select<br>mission</button>
              <div class="filter_wrapper">
                <span>Show only:</span>
                <select class="select_filter">
                  <option value="placeholder">-------</option>
                  <option value="0">Hornets</option>
                  <option value="1">Spacefires</option>
                  <option value="2">Starhawks</option>
                  <option value="3">Peacemakers</option>
                  <option value="4">Centurions</option>
                  <option value="5">Na-Thalis destroyers</option>
                </select>
              </div>
              <div class="select_ships_wrapper">
                <span>Select all: </span>
                <select class="select_ships">
                  <option value="placeholder">-------</option>
                  <option value="0">Hornets</option>
                  <option value="1">Spacefires</option>
                  <option value="2">Starhawks</option>
                  <option value="3">Peacemakers</option>
                  <option value="4">Centurions</option>
                  <option value="5">Na-Thalis destroyers</option>
                </select>
              </div>
              <div class="select_ships_wrapper">
                <span>Unselect all: </span>
                <select class="unselect_ships">
                  <option value="placeholder">-------</option>
                  <option value="0">Hornets</option>
                  <option value="1">Spacefires</option>
                  <option value="2">Starhawks</option>
                  <option value="3">Peacemakers</option>
                  <option value="4">Centurions</option>
                  <option value="5">Na-Thalis destroyers</option>
                </select>
              </div>
            </div>
            <table class="ship_list">
              <tr class="tr_header">
                <th>Deploy</th>
                <th>Number</th>
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
                        echo "<td><div class='equipment_wrapper'><span>Lasers: $lasers/".$arrayParams["lasers"][$ship["type"]]."</span>  <span>Missiles: $rockets/".$arrayParams["rockets"][$ship["type"]]."</span>  <span>Shields: $energy/".$arrayParams["shields"][$ship["type"]]."</span>  <span>Engines: $hyperspace/".$arrayParams["hyperspace"][$ship["type"]]."</span> </div></td>";
                        echo "</tr>";
                    }
                 ?>
            </table>
          </div>
        </div>
      </section>

    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

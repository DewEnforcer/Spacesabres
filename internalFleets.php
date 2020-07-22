<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleFleets.css">
      <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
    <script src="../js/search-player.js"></script>
    <script src="../js/gameinfo.js"></script>
    <script src="../js/allmov.js"></script>
    <script src="../js/backgroundmanager.js"></script>
    <title>SpaceSabres||Movement</title>
    <script>
      $(document).ready(function() {
        $(".btn_help_fleets").click(function(openTips) {
          $("main").append("<div class='help_box_content'></div>");
          let section = $(".btn_help_fleets").attr("id");
          $.getJSON("../js/game_text_en.json", function(jsonLang) {
        //    $("base_help_box_content").fadeIn(function() {
            $(".help_box_content").css("display", "flex");
            $(".help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_tips"><h2 style="color: white;">Fleet overview tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
          //  }, 1000);
        });
        });
        $(document).on("click", ".close_tips", function() {
            $(".help_box_content").remove();
            $(".help_box_content").css("display", "none");
        });
      });
    </script>
  </head>
  <body>

      <header>
        <?php 
        require "include/header.php"; 
        handleObjectives($conn, $show["userID"], 16);
         ?>
  </header>

    <main>

      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"]== "cannotcancel") {
          echo ' <div class="popup_result">
              <p>The attack has already taken place!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]=="wrongparams") {
          echo ' <div class="popup_result">
              <p>You haven´t filled out all the parameters!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
      }
      if (isset($_GET["success"]) && $_GET["success"] == 1) {
        echo ' <div class="popup_result">
            <p>The attack has been successfully cancelled!</p>
            <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
          </div>';
      }
       ?>
      <section class="searchPopup">

      </section>
      <section class="all_fleets_box">
          <button type="button" class="btn_help_fleets" id="fleetoverw">Help</button>
          <h1>Fleet Overview</h1>
          <h2 align="center">Your fleet movement</h2>
          <div class="navbar_fleet_over">
            <a href="internalFleets.php" class="button_see_movement">Fleet movement</a>
            <a href="internalBattlelobbies.php" class="button_see_movement">Commander HUD</a>
            <a href="internalDisplayfleet.php" class="button_see_fleet">Docks</a>
            <a href="internalDisplaystats.php" class="button_see_stats">Battle statistics</a>
          </div>
        <div class="mov_wrapper">
        <div class="user_mov">
        <?php
        $sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY , mapLocation FROM userfleet WHERE userID=$show[userID]");
        $userCoords = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE userID=$show[userID] AND travelWay=1 AND rounds=1 ORDER BY setAttack ASC");
        $fleetsUserMove = 0;
        if (mysqli_num_rows($sql)>0) {
        $index = 0;
        while($getUserMov = mysqli_fetch_assoc($sql)) {
          $getIDEnemy = mysqli_query($conn, "SELECT userID FROM userfleet WHERE pageCoordsX=$getUserMov[attackedUserX] AND pageCoordsY=$getUserMov[attackedUserY] AND mapLocation=$getUserMov[targetMapLocation]");
          $IDEnemy = mysqli_fetch_assoc($getIDEnemy);
          if ($IDEnemy["userID"] > 100000) {
            $getUsernameEnemy = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$IDEnemy[userID]");
            $usernameEnemy = mysqli_fetch_assoc($getUsernameEnemy);
            $username = $usernameEnemy["ingameNick"];
            $img = '<img class="targetImg" src="image/travelimg/base.png">';
          } else {
            $username = "Non company member";
            $img = '<img class="targetNpcImg" src="image/travelimg/npc.png">';
          }
          echo "<div class='user_travel_info".$index." user_travel_info'>";
          echo "<div class='travel_info_left_wrapper'>";
          if ($getUserMov["missionType"] == 1) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span >Mission type: <label style='color:red;'>Attack</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          elseif ($getUserMov["missionType"] == 2) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span >Mission type: <label style='color:green;'>Return</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          echo "<hr class='travel_line'>";
          echo $img;
          echo '<img class="arrow arrow'.$index.'" src="image/travelimg/travelway.png">';
            echo "<span>Target: ".$username."</span>";
          echo "</div>";
          echo "<div class='travel_info_right_wrapper'>";
          echo "<button type='button name='travel_button_details' class='button_details' value='attack' id=".$index.">Show details</button>";
          echo "<div class='time'id='user_travel_time".$index."'>";
          echo "</div>";
          if ($getUserMov["travelTime"] > date("U") && $getUserMov["travelTime"]>0 && $getUserMov["travelWay"] = 1) {
            echo '<a class="button_cancel_attack" href="include/cancelAttack.php?cancel=attack&&id='.$getUserMov["id"].'">Cancel the attack</a>';
          }
          echo "</div>";
          echo '<div class="movementShipParams user_travel_info'.$index.'params">';
          echo "</div>";
          echo "</div>";
          $index++;
        }
      }  else {
        $fleetsUserMove += 1;
      }
      $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE userID=$show[userID] AND returnWay=1 AND missionType=2 ORDER BY travelTime ASC");
      if (mysqli_num_rows($sql) > 0) {
        $index = 0;
        while($getUserMov = mysqli_fetch_assoc($sql)) {
          echo "<div class='user_return_info".$index." user_travel_info'>";
          echo "<div class='travel_info_left_wrapper'>";
          if ($getUserMov["missionType"] == 1) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span>Mission type: <label style='color:red;'>Attack</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          elseif ($getUserMov["missionType"] == 2) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span >Mission type: <label style='color:green;'>Return</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          echo "<hr class='travel_line'>";
          echo '<img class="targetImg" src="image/travelimg/base.png">';
          echo '<img class="arrow arrowReturn'.$index.'" src="image/travelimg/travelway.png">';
            echo "<span>Target: Homebase</span>";
          echo "</div>";
          echo "<div class='travel_info_right_wrapper'>";
          echo "<button type='button name='travel_button_details' class='button_details' value='return' id=".$index.">Show details</button>";
          echo "<div class='time'id='user_return_time".$index."'>";
          echo "</div>";
          if ($getUserMov["travelTime"] > date("U") && $getUserMov["travelTime"]>0 && $getUserMov["travelWay"] = 1) {
            echo '<a class="button_cancel_attack" href="include/cancelAttack.php?cancel=attack&&id='.$getUserMov["id"].'">Cancel the attack</a>';
          }
          echo "</div>";
          echo '<div class="movementShipParams user_return_info'.$index.'params">';
          echo "</div>";
          echo "</div>";
          $index++;
        }
      } else {
        $fleetsUserMove += 1;
      }
      if ($fleetsUserMove == 2) {
        echo "<p align='center'>No movement of your fleets has been detected</p>";
      }
      echo "</div>";
      $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE attackedUserX=$userCoords[pageCoordsX] AND attackedUserY=$userCoords[pageCoordsY] AND targetMapLocation=$userCoords[mapLocation] AND travelWay=1 AND rounds=1 ORDER BY travelTime ASC");
      echo "<div class='enemy_mov'>";
      if (mysqli_num_rows($sql) > 0) {
        $index = 0;
        while($getUserAttack = mysqli_fetch_assoc($sql)) {
          $getEnemyNick = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$getUserAttack[userID]");
          $enemyNick = mysqli_fetch_assoc($getEnemyNick);
          echo "<div class='enemy_attack_info".$index." enemy_attack_info'>";
          echo "<div class='travel_info_left_wrapper'>";
          if ($getUserAttack["missionType"] == 1) {
            echo "<span >Mission type: <label style='color:red;'>Attack</label></span>";
          }
          echo "<hr class='travel_line enemy_travel_line'>";
          echo '<img src="../image/travelimg/base.png" class="userbaseImg">';
          echo '<img src="../image/travelimg/returnway.png" class="arrowAttack arrowAttack'.$index.'">';
            echo "<span>Attacker: ".$enemyNick["ingameNick"]."</span>";
          echo "</div>";
          echo "<div class='travel_info_right_wrapper'>";
          echo "<button type='button name='travel_button_details' class='button_details' value='enemy_attack' id=".$index.">Show details</button>";
          echo "<div class='time'id='enemy_attack_time".$index."'>";
          echo "</div>";
          echo "</div>";
          echo '<div class="movementShipParams enemy_attack_info'.$index.'params">';
          echo "</div>";
          echo "</div>";
          $index++;
        }
      } else {
        echo "<p align='center'>There are no enemy fleets heading towards your base</p>";
      }
         ?>
       </div>
     </div>
      </section>

    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

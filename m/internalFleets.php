<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_array($sql);
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
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="css/game.css">
    <link rel="stylesheet" href="css/movement.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="js/allmov.js"></script>
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
    <?php
    require 'include/nav.php';
     ?>
      <header>
        <?php require "include/header.php"; ?>
        <?php
        function handleObjectives($conn, $userID, $index) {
          $sql = mysqli_query($conn, "SELECT currentQuest, userObjectives FROM userquests WHERE userID=$userID");
          $currentQuest = mysqli_fetch_assoc($sql);

          $unserializeUser = unserialize($currentQuest["userObjectives"]);

          if ($currentQuest["currentQuest"] == 0) {
            return;
          }


          $sql = mysqli_query($conn, "SELECT objectives FROM quests WHERE questID=$currentQuest[currentQuest]");
          $objective = mysqli_fetch_assoc($sql);
          $unserializeTemplate = unserialize($objective["objectives"]);

          if ($unserializeTemplate[$index]>0) {
            $unserializeUser[$index] += 1;
            $serialize = serialize($unserializeUser);
            $sql = mysqli_query($conn, "UPDATE userquests SET userObjectives='$serialize' WHERE userID=$userID");
            return;
          } else {
            return;
          }
        }

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
          <button type="button" class="btn_help_fleets" id="fleetoverw">Help</button>
          <h2 align="center">Your fleet movement</h2>
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
          } else {
            $username = "Non company member"; // placeholder // TODO:
          }
          echo "<div class='user_movement_info".$index." user_travel_info'>";
          echo "<div class='travel_info_left_wrapper'>";
          if ($getUserMov["missionType"] == 1) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span >Mission type: <label style='color:red;'>Attack</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          elseif ($getUserMov["missionType"] == 2) {
            echo "<div style='display:flex; flex-flow: row nowrap; justify-content: space-evenly; width: 100%;' ><span >Mission type: <label style='color:green;'>Return</label></span><span>LobbyID: #".$getUserMov["lobbyID"]."</span></div>";
          }
          echo "<hr class='travel_line'>";
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
      echo "</div>";
      $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE userID=$show[userID] AND returnWay=1 ORDER BY travelTime ASC");
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

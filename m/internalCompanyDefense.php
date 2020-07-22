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
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
<?php include "include/font.php"; ?>
<link rel="stylesheet" href="css/game.css">
<link rel="stylesheet" href="css/compdef.css">
<?php
require 'include/head.php';
 ?>
        <script src="../js/compDefense.js"></script>
    <title>SpaceSabres||CompanyDefense</title>
    <script>
      $(document).ready(function() {
        $(".btn_help_compdef").click(function(openTips) {
          $("main").append("<div class='help_box_content'></div>");
          let section = $(".btn_help_compdef").attr("id");
          $.getJSON("../js/game_text_en.json", function(jsonLang) {
        //    $("base_help_box_content").fadeIn(function() {
            $(".help_box_content").css("display", "flex");
            $(".help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_tips"><h2 style="color: white;">Company defense tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
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
    require './include/nav.php';
     ?>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <section class="searchPopup">

      </section>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"]=="sql") {
          echo ' <div class="popup_result">
              <p>An error occurred ID#11</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        } elseif($_GET["error"]== "notenoughmodules") {
          echo ' <div class="popup_result">
              <p>You don´t have enough modules in your inventory to set this configuration of module placement!</p>
              <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
            </div>';
        }
        }
        if (isset($_GET["success"])) {
          if ($_GET["success"]=="moduleset") {
            echo ' <div class="popup_result">
                <p>The modules have been successfully set!</p>
                <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
              </div>';
          }
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
      <section class="sendFleet">
        <button type="button" class="btn_help_compdef" id="compdef">Help</button>
        <div class="top_container_base_title">
          <h2 align="center">Company defense</h2>
        </div>
        <?php
        $sql = mysqli_query($conn, "SELECT defenseCooldown FROM users WHERE userID=$show[userID]");
        $getCD = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT * FROM userfleet WHERE userID=$show[userID]");
        $getShips = mysqli_fetch_assoc($sql);
        $sql = mysqli_query($conn, "SELECT * FROM companydefense WHERE userID=$show[userID]");
        if (mysqli_num_rows($sql) > 0) {
          $compDef = mysqli_fetch_assoc($sql);
          echo "<div class='fleetaway_box'><p>Your fleet will be done helping out your faction on ".date('Y-m-d G:i:s', $compDef["timeFinish"])." (Server time)</p>";
          echo "<h3>Fleet participating in this operation</h3>";
          if ($compDef["hornet"] > 0) {
            echo "<p>$compDef[hornet] Hornet(s)</p>";
          }
          if ($compDef["spacefire"] > 0) {
            echo "<p>$compDef[spacefire] Spacefire(s)</p>";
          }
          if ($compDef["starhawk"] > 0) {
            echo "<p>$compDef[starhawk] Starhawk(s)</p>";
          }
          if ($compDef["peacemaker"] > 0) {
            echo "<p>$compDef[peacemaker] Peacemaker(s)</p>";
          }
          if ($compDef["centurion"] > 0) {
            echo "<p>$compDef[centurion] Centurion(s)</p>";
          }
          if ($compDef["nathalis"] > 0) {
            echo "<p>$compDef[nathalis] Na-Thalis Destroyer(s)</p>";
          }
          echo '<button type="button" name="buttonCancDef" id="cancel_defense">Cancel the defense</button>';
          echo '<p>Upon success of this operation you will recieve '.$compDef["reward"].' Hyperid(s)</p>';
          echo "<h3>NOTE: You will not recieve any rewards if you cancel this operation prematurely.</h3>";
          echo "</div>";
        } elseif ($getCD["defenseCooldown"] > date("U")) {
          echo "<div class='fleetaway_box'><p>The company doesn't currently need your help till ".date('Y-m-d G:i:s', $getCD["defenseCooldown"])." (Server time)</p></div>";
        }
         else {
          echo '<form class="Selection" method="post">
            <label class="timeLabel" for="Time">Serving time (min. 1 hour, max. 10 hours):</label> <input type="number" name="Time" id="time" value="1" min="1" max="10" class="timeInput">
            <label for="hornet" >Hornet/s:</label> <input type="number" name="hornet" id="Hornet" placeholder="Hornets" value="'.$getShips["hornet"].'">
            <label for="Spacefire" >Spacefire/s:</label> <input type="number" name="Spacefire" id="Spacefire" placeholder="Spacefires" value="'.$getShips["spacefire"].'">
            <label for="Starhawk" >Starhawk/s:</label> <input type="number" name="Starhawk" id="Starhawk" placeholder="Starhawks" value="'.$getShips["starhawk"].'">
            <label for="Peacemaker" >Peacemaker/s:</label> <input type="number" name="Peacemaker" id="Peacemaker" placeholder="Peacemakers" value="'.$getShips["peacemaker"].'">
            <label for="Centurion" >Centurion/s:</label> <input type="number" name="Centurion" id="Centurion" placeholder="Centurions" value="'.$getShips["centurion"].'">
            <label for="Na-Thalis" >Na-Thalis Destroyer/s:</label> <input type="number" name="Na-Thalis" id="Nathalis" placeholder="Na-Thalis Destroyers" value="'.$getShips["nathalis"].'">
            <button type="submit" name="defense-send">Send your fleet now!</button>
          </form>';
        }
         ?>

      </section>
    </main>

    <footer class="footerShipyard">
      <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

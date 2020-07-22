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
    <link rel="stylesheet" href="css/missions.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="../js/quest-selector.js"></script>
    <script src="../js/missionCountdown.js"></script>
    <title>SpaceSabres||Missions</title>
  </head>
  <body>
    <?php
    require './include/nav.php';
     ?>
      <header>
      <?php require "include/header.php"; ?>

  </header>

    <main>
    <?php
    if (isset($_SESSION["mission_result"]) && $_SESSION["mission_result"] != "") {
      echo ' <div class="popup_result">';
      if ($_SESSION["mission_result"] == "sql") {
        echo '<p>Unfortunately an internal server error has occured, please try again or report this issue on forums!</p>';
      } elseif($_SESSION["mission_result"] == "alreadyon") {
        echo '<p>You cannot accept more than one mission at a time!</p>';
      } elseif ($_SESSION["mission_result"] == "successCancel") {
        echo '<p>The mission has been successfully cancelled!</p>';
      } elseif ($_SESSION["mission_result"] == "successAccepted") {
        echo '<p>The mission has been successfully accepted!</p>';
      } elseif ($_SESSION["mission_result"] == "nomiss") {
        echo '<p>You dont\' have any missions to cancel!</p>';
      }
      echo '<button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>';
        $_SESSION["mission_result"] = "";
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
      <section class="mission_container_main">
        <div class="mission_top_title_container">
          <h1 align="center">Missions</h1>
          <hr>
        </div>
        <div class="mission_container_main_wrapper">
        <div class="mission_list_wrapper">
                    <h2 class="active">List of missions</h2>
        <div class="mision_container_list">
          <?php

          $sql = mysqli_query($conn, "SELECT userObjectives FROM userquests WHERE userID=$show[userID]");
          $userObjectives = mysqli_fetch_assoc($sql);

          $decompiledObjectivesObject = unserialize($userObjectives["userObjectives"]);

          $sql = mysqli_query($conn, "SELECT questsCompleted, currentQuest, timeLeft FROM userquests WHERE userID=$show[userID]");
          $userMissions = mysqli_fetch_assoc($sql);
          $index=$userMissions["questsCompleted"] + 1;
          while ($index <= 20) {
            if ($index % 10 == 0) {
              if ($index == $userMissions["currentQuest"]) {
                echo '<button  type="button" name="quest" href="#" value="true" class="quest quest_current" id="'.$index.'" ><b>Mission '.$index.'</b></button>';
              } else {
                echo '<button type="button" name="quest" href="#" value="false" class="quest" id="'.$index.'" ><b>Mission '.$index.'</b></button>';
              }
            } else {
              if ($index == $userMissions["currentQuest"]) {
                echo '<button s type="button" name="quest" href="#" value="true" class="quest quest_current" id="'.$index.'" >Mission '.$index.'</button>';
            } else {
                echo '<button type="button" name="quest" href="#" value="false" class="quest" id="'.$index.'" >Mission '.$index.'</button>';
            }
          }
            $index++;
          }
           ?>

        </div>
      </div>
        <?php
        function checkTime($conn, $userID, $time) {
          if ($time > 0) {
            if ($time > date("U")) {
              $return = true;
            } else {
              $newArray = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
              $serialized = serialize($newArray);
              $sql = mysqli_query($conn, "UPDATE userquests SET currentQuest=0, userObjectives='$newArray' WHERE userID=$userID");
              $return = false;
            }
          } else {
            $return = true;
          }
          return $return;
        }
         ?>
        <div class="mission_container_details">
            <div class="mission_container_details_wrapper">
            <div class="mission_container_details_mission_objectives">
            </div>
              <div class="mission_container_details_mission_reward">
            </div>
          </div>
          <div class="btn_mission_wrapper">
          </div>
        </div>
      </div>
      </section>
      <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] == 1) {



       ?>
      <div class="error_box" <?php echo "style='display:block'"; ?>>
        <?php
        echo "You cannot accept more than 1 mission at a time!";
        }
        }
         ?>
     </div>
    </main>

    <footer>
  <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

<?php
  require "./include/accessSecurity.php";
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <?php include "include/font.php"; ?>
    <link rel="stylesheet" href="../css/stylegame.css">
    <link rel="stylesheet" href="../css/styleMissions.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="../js/quest-selector.js"></script>
    <script src="../js/gameinfo.js"></script>
    <script src="../js/backgroundmanager.js" charset="utf-8"></script>
    <script src="../js/search-player.js"></script>
    <script src="../js/missionCountdown.js"></script>
    <title>SpaceSabres||Missions</title>
  </head>
  <body>

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
      } elseif ($_SESSION["mission_result"] == "notcomplete") {
        echo '<p>You haven\'t finished this mission yet!</p>';
      } elseif ($_SESSION["mission_result"] == "completed") {
        echo '<p>You have successfully claimed well earned your rewards!</p>';
      }
      echo '<button type="button" name="button_confirm_result" class="button_confirm_result">OK</button></div>';
        $_SESSION["mission_result"] = "";
    }
     ?>
    <section class="searchPopup">

    </section>
      <section class="mission_container_main">
        <div class="mission_top_title_container">
          <h1 align="center">Missions</h1>
          <hr>
        </div>
        <div class="mission_container_main_wrapper">
        <div class="mission_list_wrapper">
        <div class="mision_container_list_wrapper">
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

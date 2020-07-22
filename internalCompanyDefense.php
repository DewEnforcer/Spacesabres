<?php
  require "./include/accessSecurity.php";
$foundCF = false;
$sql = mysqli_query($conn, "SELECT reward, defenseHours, travelTime, fleetNumbers FROM usermovement WHERE userID=$userInfo[userID] AND missionType=3");
if (mysqli_num_rows($sql) > 0) {
  $foundCF = true;
}
$userCompDefense = mysqli_fetch_assoc($sql);
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results">
    <meta name=viewport content="width=device-width, initial-scale=1">
<?php include "include/font.php"; ?>
<link rel="stylesheet" href="../css/stylegame.css">
<link rel="stylesheet" href="../css/styleDefense.css">
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
        <script src="../js/compDefense.js"></script>
        <script src="../js/countDownPage.js" charset="utf-8"></script>
        <script src="../js/gameinfo.js" charset="utf-8"></script>
        <script src="../js/search-player.js"></script>
        <script src="../js/backgroundmanager.js" charset="utf-8"></script>
    <title>SpaceSabres||CompanyDefense</title>
    <script>
    var time = "<?php if ($foundCF && $userCompDefense["travelTime"] > 0) {
      echo date("Y-m-d G:i:s", $userCompDefense["travelTime"]);
    } ?>";
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
      <section class="sendFleet">
        <button type="button" class="btn_help_compdef" id="compdef">Help</button>
          <h2 align="center">Company defense</h2>
          <div class="defense_wrapper">
            <?php
              if ($foundCF && $userCompDefense["travelTime"] > 0) {
                $shipArr = ["Hornet", "Spacefire", "Starhawk", "Peacemaker", "Centurion", "Na-Thalis destroyer"];
                $fleetNumbers = unserialize($userCompDefense["fleetNumbers"]);
                echo "<h3>Current company defense overview</h3>";
                echo "<span>$userCompDefense[defenseHours] Hours of defense time</span>";
                echo "<span>Reward: $userCompDefense[reward] Natiums</span>";
                echo "<hr><span>Fleet participating in this operation:</span>";
                $i = 0;
                foreach ($fleetNumbers as $ship) {
                  if ($ship > 0) {
                    echo "<span>$ship ".$shipArr[$i]."(s)</span>";
                  }
                  $i++;
                }
                echo "<hr><span>Time left: <span class='countdown_def'></span></span>";
                if ($userCompDefense["travelTime"] > date("U")+660) {
                  echo "<a href='./include/compDefense.php?action=cancel' id='cancel_defense'>Call the fleet back</a>";
                }
              } else {
                echo "<h3>Currently you aren't participating in company defense.</h3>";
              }
             ?>
          </div>
      </section>
    </main>

    <footer>
      <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

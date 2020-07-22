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
    <link rel="stylesheet" href="css/galaxy.css">
    <?php
    require 'include/head.php';
     ?>
    <script src="./js/galaxy.js"></script>
    <script>
      $(document).ready(function() {
        $(".btn_help_galaxy").click(function(openTips) {
          $("main").append("<div class='help_box_content'></div>");
          let section = $(".btn_help_galaxy").attr("id");
          $.getJSON("../js/game_text_en.json", function(jsonLang) {
        //    $("base_help_box_content").fadeIn(function() {
            $(".help_box_content").css("display", "flex");
            $(".help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_tips"><h2 style="color: white;">Galaxy tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
          //  }, 1000);
        });
        });
        $(document).on("click", ".close_tips", function() {
            $(".help_box_content").remove();
            $(".help_box_content").css("display", "none");
        });
      });
    </script>
    <title>SpaceSabres||Galaxy</title>
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

        handleObjectives($conn, $show["userID"], 14);
         ?>
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
      <section class="galaxy_main_container">
        <button type="button" class="btn_help_galaxy" id="galaxy">Help</button>
        <div class="top_container_galaxy_title">
          <h2>Galaxy</h2>
        </div>
        <div class="galaxy_main_container_navbar">
          <div class="galaxy_main_container_navbar_map">
            <?php
            $sql = mysqli_query($conn, "SELECT * FROM userfleet WHERE userID=$show[userID]");
            $getMapParams = mysqli_fetch_assoc($sql);
            echo "<h4 class='navigation_info'>Current system displayed in the navigation:<br> ".$getMapParams["mapLocation"]."</h4>";
             ?>
          </div>
          <div class="galaxy_main_container_navbar_selector">
            <button type="button" name="button_backwards" class="backwards">&#8249</button>
            <input type="number" name="mapnumber" value="<?php echo $getMapParams["mapLocation"]; ?>" class="map_input">
            <button type="button" name="button_forward" class="forward">&#8250</button>
          </div>
        </div>
        <div class="galaxy_main_map_wrapper">
          <div class="galaxy_main_map_planet_params">

          </div>
          <div class="galaxy_main_map_hack_attempt">

          </div>
        <div class="galaxy_main_map">
          <table class="map_mobile">
            <tr>
              <th>Type</th>
              <th>Commander</th>
              <th>Coordinates</th>
              <th>Alliance</th>
            </tr>
          <?php
          $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$show[userID]");
          $userClan = mysqli_fetch_assoc($sql);
          $sql = mysqli_query($conn, "SELECT fleetPoints FROM userfleet WHERE userID=$show[userID]");
          $userPoints = mysqli_fetch_assoc($sql);
          if ($userPoints["fleetPoints"] < 1) {
            $userPoints["fleetPoints"] = 1;
          }
          $sqlLoop = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, userID, fleetPoints FROM userfleet WHERE mapLocation=$getMapParams[mapLocation]");
          while ($planets = mysqli_fetch_assoc($sqlLoop)) {
            $sql = mysqli_query($conn, "SELECT userclan, ingameNick FROM users WHERE userID=$planets[userID]");
            $planetClan = mysqli_fetch_assoc($sql);
            if ($planets["fleetPoints"] < 1) {
              $planets["fleetPoints"] = 1;
            }
            if ($planets["userID"] == $show["userID"]) {
              $type = "User";
            } elseif ($planetClan["userclan"] == $userClan["userclan"] && $userClan["userclan"] != "none") {
              $type = "Ally";
            } else {
              if ($userPoints["fleetPoints"] < $planets["fleetPoints"]*10) {
                $type = "Enemy";
              } else {
                $type = "Passive";
              }
            }
            echo '<tr class="base_wrapper" id='.$type.'type'.$planets["pageCoordsX"].'y'.$planets["pageCoordsY"].'>';
            echo '<td><img src="../image/graphics/galaxybase'.$type.'.png" alt="planet" class="planet"></td>';
            echo "<td class='targetNick' id='$planetClan[ingameNick]'>$planetClan[ingameNick]</td>";
            echo '<td>X: '.$planets["pageCoordsX"].' Y: '.$planets["pageCoordsY"].'</td>';
            echo '<td>'.$planetClan["userclan"].'</td>';
            echo "</tr>";
          }
          $system = "systems".ceil($getMapParams["mapLocation"]/100);
          $sql = mysqli_query($conn, "SELECT coordsX, coordsY, npcType FROM $system WHERE map=$getMapParams[mapLocation] AND status=1");
          while ($npcs = mysqli_fetch_assoc($sql)) {
            echo '<tr class="base_wrapper" id=npctype'.$npcs["coordsX"].'y'.$npcs["coordsY"].'>';
            echo '<td><img src="../image/graphics/npc.png" alt="planet" class="npc"></td>';
            echo '<td><p class="targetNick" id='.$npcs["npcType"].'>'.$npcs["npcType"].'</p></td>';
            echo '<td>X: '.$npcs["coordsX"].' Y: '.$npcs["coordsY"].'</td>';
            echo '<td>Xamon</td>';
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

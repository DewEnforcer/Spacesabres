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
    <link rel="stylesheet" href="../css/styleGalaxy.css">
    <?php require "include/scripts.php" ?>
    <script src="../js/galaxy.js"></script>
    <script src="../js/hack-handler.js"></script>
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
        require "include/header.php"; 
        handleObjectives($conn, $userInfo["userID"], 14);
         ?>

    <main>
      <?php require "include/bars.php"; ?>
      <section class="galaxy_main_container">
        <button type="button" class="btn_help_galaxy" id="galaxy">Help</button>
        <div class="top_container_galaxy_title">
          <h2>Galaxy</h2>
        </div>
        <div class="galaxy_main_container_navbar">
          <div class="galaxy_main_container_navbar_map">
            <?php
            $sql = mysqli_query($conn, "SELECT * FROM userfleet WHERE userID=$userInfo[userID]");
            $getMapParams = mysqli_fetch_assoc($sql);
            echo "<h4 class='navigation_info'>Current system displayed in the navigation: ".$getMapParams["mapLocation"]."</h4>";
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

          <?php
          $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$userInfo[userID]");
          $userClan = mysqli_fetch_assoc($sql);
          $sql = mysqli_query($conn, "SELECT fleetPoints FROM userfleet WHERE userID=$userInfo[userID]");
          $userPoints = mysqli_fetch_assoc($sql);
          if ($userPoints["fleetPoints"] < 1) {
            $userPoints["fleetPoints"] = 1;
          }
          $sqlLoop = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, userID, fleetPoints FROM userfleet WHERE mapLocation=$getMapParams[mapLocation]");
          while ($planets = mysqli_fetch_assoc($sqlLoop)) {
            $sql = mysqli_query($conn, "SELECT userclan FROM users WHERE userID=$planets[userID]");
            $planetClan = mysqli_fetch_assoc($sql);
            if ($planets["fleetPoints"] < 1) {
              $planets["fleetPoints"] = 1;
            }
            if ($planets["userID"] == $userInfo["userID"]) {
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


              echo '<img src="../image/graphics/galaxybase'.$type.'.png" alt="planet" class="planet" style="cursor:pointer;position:absolute; left:'.$planets["pageCoordsX"].'px; top:'.$planets["pageCoordsY"].'px" id='.$type.'type'.$planets["pageCoordsX"].'y'.$planets["pageCoordsY"].'>';
          }
          $system = "systems".ceil($getMapParams["mapLocation"]/100);
          $sql = mysqli_query($conn, "SELECT coordsX, coordsY FROM $system WHERE map=$getMapParams[mapLocation] AND status=1");
          while ($npcs = mysqli_fetch_assoc($sql)) {
              echo '<img src="../image/graphics/npc.png" alt="planet" class="planet" style="cursor:pointer;position:absolute; left:'.$npcs["coordsX"].'px; top:'.$npcs["coordsY"].'px" id=npctype'.$npcs["coordsX"].'y'.$npcs["coordsY"].'>';
          }
           ?>

        <img src="../image/graphics/sungalaxy.png" alt="sun" style="position:absolute; left:372.5px;top:177.5px;">

      </div>
    </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

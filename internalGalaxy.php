<?php
  require "./include/accessSecurity.php";
  $sql = mysqli_query($conn, "SELECT mapLocation FROM userfleet WHERE userID=$userInfo[userID]");
  $map = mysqli_fetch_assoc($sql)["mapLocation"];
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
      let currentSystem = <?php echo $map; ?>;
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
          </div>
          <div class="galaxy_main_container_navbar_selector">
            <button type="button" name="button_backwards" class="backwards">&#8249</button>
            <input type="number" name="mapnumber" value="<?php echo $getMapParams["mapLocation"]; ?>" class="map_input">
            <button type="button" name="button_forward" class="forward">&#8250</button>
          </div>
        </div>
        <div class="galaxy_main_map">
        </div>
      </div>
      </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

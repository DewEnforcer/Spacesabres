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
    <?php require "include/scripts.php" ?>
    <script src="../js/allmov.js"></script>
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
        require "include/header.php"; 
        handleObjectives($conn, $userInfo["userID"], 16);
         ?>
    <main>
      <?php
      require "include/bars.php";
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
       <section class="main_fleet_box">
        
       </section>
    </main>

    <footer>
    <?php require "include/footer.php"; ?>
    </footer>
</body>
</html>

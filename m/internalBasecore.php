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
     <link rel="stylesheet" href="css/base.css">
     <?php
     require 'include/head.php';
      ?>
     <script src="../js/coreManagement.js" charset="utf-8"></script>
     <title>SpaceSabres||BattleStation</title>
     <script>
       $(document).ready(function() {
         $(".button_help_base").click(function(openTips) {
           $("main").append("<div class='base_help_box_content'></div>");
           let section = $(".button_help_base").attr("id");
           $.getJSON("../js/game_text_en.json", function(jsonLang) {
         //    $("base_help_box_content").fadeIn(function() {
             $(".base_help_box_content").css("display", "flex");
             $(".base_help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_base_tips"><h2 style="color: white;">BattleStation tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
           //  }, 1000);
         });
         });
         $(document).on("click", ".close_base_tips", function() {
             $(".base_help_box_content").remove();
             $(".base_help_box_content").css("display", "none");
         });
       });
     </script>
   </head>
   <body>
     <?php
     require './include/nav.php';
      ?>
       <header>
 <?php  require "include/header.php";
 $getModules = mysqli_query($conn, "SELECT * FROM userbase WHERE userID=$show[userID]");
 $modules = mysqli_fetch_assoc($getModules);
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
       <?php
       if (isset($_GET["error"])) {
         if ($_GET["error"]=="sql") {
           echo ' <div class="popup_result">
               <p>An error occurred ID#11</p>
               <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
             </div>';
         } elseif($_GET["error"]== "notenoughmodules") {
           echo ' <div class="popup_result">
               <p>You don´t have enough modules in your inventory to set all the selected modules!</p>
               <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
             </div>';
         }
       }
       if (isset($_GET["success"])) {
         if ($_GET["success"] == "moduleset") {
         echo '<div class="popup_result">
             <p>The modules have been successfully set!</p>
             <button type="button" name="button_confirm_result" class="button_confirm_result">OK</button>
           </div>';
         }
       }
        ?>
       <section class="searchPopup">

       </section>
       <section class="base_container">
         <div class="top_container_base_title">
           <h2 align="center">Battlestation core center</h2>
         </div>
         <div class="base_core_main">
           <div class="wrapper_graphics">
             <div class="box_core_hull">
               <h2>Core hull</h2>
               <?php
               $sql = mysqli_query($conn, "SELECT coreHealth, coreShields FROM userbase WHERE userID=$userInfo[userID]");
              $userBase = mysqli_fetch_assoc($sql);
              $sql = mysqli_query($conn, "SELECT researchHp, researchShd FROM userresearch WHERE userID=$userInfo[userID]");
              $userResearch = mysqli_fetch_assoc($sql);
               if ($userBase["coreShields"] > 0) {
                 $statusShd = "<label style='color: lightblue'>Up</label>";
               } else {
                 $statusShd = "<label style='color: red'>Down</label>";
               }
               if ($userBase["coreHealth"] > 0) {
                 $statusHp = "<label style='color: green'>Operational</label>";
               } else {
                 $statusHp = "<label style='color: red'>Destroyed</label>";
               }
                ?>
               <p>Hull points: <div style="position: relative; width: 100%; height: 30px; border: 1px solid black; text-align: center; background-color: rgb(240,240,240)"> <?php echo "<div class='hp_bar' style='display: flex; align-items: center; min-height: 100%; width: ". ((($userBase["coreHealth"]))/(1000000 * ($userResearch["researchHp"]*0.1+1))*100)."%; background-color: rgb(80,220,100);'>";?><?php echo "<p id='hull_val' style='position: absolute; left: 35%; color: green;'>".$userBase["coreHealth"]."</p>"; ?></div></div></p>
               <p class='hull_status'>Core status:<br>  <?php echo $statusHp.""; ?></p>
             </div>
               <?php
               $calc = (($userBase["coreShields"])/(500000 * ($userResearch["researchShd"]*0.1+1))*100);
                ?>
             <div class="box_core_shield">
               <h2>Core shields</h2>
               <p>Shield points: <div style="position: relative; width: 100%; height: 30px; border: 1px solid black; text-align: center; background-color: rgb(240,240,240)"> <?php echo "<div class='shd_bar' style='display: flex; align-items: center; min-height: 100%; width: ". ((($userBase["coreShields"]))/(500000 * ($userResearch["researchShd"]*0.1+1))*100)."%; background-color: #318CE7;'>";?><?php echo "<p id='shd_val' style='position: absolute; left: 35%; color: #002FA7;'>".$userBase["coreShields"]."</p>"; ?></div></div></p>
               <p class="shd_status">Shields status:<br> <?php echo $statusShd.""; ?></p>
             </div>
           </div>
           <div class="wrapper_tech">
             <div class="repair_hull">
               <?php
               $cost = round(((1000000 * ($userResearch["researchHp"]*0.1+1))-($userBase["coreHealth"] ))/2);
                ?>
               <h2 align="center">Hull repair</h2>
               <span>Current cost (1 Credit for 2 points repaired): <?php echo number_format($cost, '0', '.',' '); ?> Credit(s)</span>
               <?php
                if ($cost > 0) {
                  echo "<button type='button' class='btn_repair_hull'>Start the repairs</button>";
                } else {
                  echo "<p>Your core has currently maximal hull points!</p>";
                }
                ?>
             </div>
             <div class="recharge_shield">
               <?php
               $cost = round(((500000*($userResearch["researchShd"]*0.1+1))-($userBase["coreShields"] ))/3);
                ?>
               <h2 align="center">Shield recharge</h2>
               <span>Current cost (1 Hyperid for 3 points recharged): <?php echo number_format($cost, '0', '.',' '); ?> Hyperid(s)</span>
               <?php
                if ($cost > 0 && $userBase["coreHealth"] > 0) {
                  echo "<button type='button' class='btn_repair_shd'>Start the shield recharge</button>";
                } elseif ($userBase["coreHealth"] == 0) {
                  echo "<p class='button_shd_stop'>Cannot recharge shields without present core!</p>";
                } else {
                  echo "<p>Your cores shields have already reached maximum capacity</p>";
                }
                ?>
             </div>
           </div>
         </div>


       </section>

     </main>

     <footer>
       <?php require "include/footer.php"; ?>
     </footer>
 </body>
 </html>

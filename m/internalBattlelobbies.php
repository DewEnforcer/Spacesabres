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
     <link rel="stylesheet" href="css/lobby.css">
     <?php
     require 'include/head.php';
      ?>
     <script src="js/genLobby.js"></script>
     <script src="js/lobby.js"></script>
     <?php
     if (isset($_SESSION["lobbyID"]) && isset($_SESSION["typeLobby"]) && strlen($_SESSION["lobbyID"]) == 20 && $_SESSION["typeLobby"] != "" ) {
       echo '<script>
       $(document).ready(function() {
         $(".opacity_box").fadeIn(function() {
           $(".opacity_box").css("display", "block");
         })
         $("body").append("<div class="+"battle_window"+"></div>");
         $(".battle_window").fadeIn(function() {
           $(".battle_window").css("display", "flex");
           $(".battle_window").html(`<h2 style="color: white;" class="battle_tag"></h2><img src="../image/graphics/closeMsg.png" class="close_battle"><div class="spacemap_battle"></div>`);
           $(".spacemap_battle").append("<div class="+"spacemap_attacker"+"></div><div class="+"spacemap_middle_upper"+"></div><div class="+"spacemap_defender spacemap_right"+"></div><div class="+"spacemap_middle_lower"+"></div><div class="+"spacemap_middle_middle"+"></div>")
           $("spacemap_middle_lower").html("<div class="+"box_lower_left"+"></div><div class="+"box_lower_right"+"></div>");
           $(".battle_window").append("<img src="+"../image/graphics/loading.gif"+" class="+"loading"+">");
         });
         $( ".battle_window" ).promise().done(function() {
           //↓ get all the required info
           setTimeout(function() {
             generateObj("'.$_SESSION["lobbyID"].'", "'.$_SESSION["typeLobby"].'");
           },1000);
         });
       });
       </script>';
       $_SESSION["lobbyID"] = "";
       $_SESSION["typeLobby"] = "";
     } else {
       $_SESSION["lobbyID"] = "";
       $_SESSION["typeLobby"] = "";
     }
      ?>
     <script>
       $(document).ready(function() {
         $(".btn_help_fleets").click(function(openTips) {
           $("main").append("<div class='help_box_content'></div>");
           let section = $(".btn_help_fleets").attr("id");
           $.getJSON("../js/game_text_en.json", function(jsonLang) {
         //    $("base_help_box_content").fadeIn(function() {
             $(".help_box_content").css("display", "flex");
             $(".help_box_content").html(`<img src="../image/graphics/closeMsg.png" class="close_tips"><h2 style="color: white;">Commander's HUD tips</h2><p>${jsonLang[section]["help_text"]}</p><h3>${jsonLang[section]["remember"]}</h3>`);
           //  }, 1000);
         });
         });
         $(document).on("click", ".close_tips", function() {
             $(".help_box_content").remove();
             $(".help_box_content").css("display", "none");
         });
       });
     </script>
     <title>SpaceSabres||Commander HUD</title>
   </head>
   <body>
     <?php
     require 'include/nav.php';
      ?>
       <header>
         <?php require "include/header.php"; ?>
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

       <section class="lobby_main_container">
        <button type="button" class="btn_help_fleets" id="commanderhud">Help</button>
         <h1>List of all battles you participate in</h1>
         <hr>
         <div class="lobbies_wrapper">
           <?php
           // ↓ get users coordinates
            $sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation FROM userfleet WHERE userID=$userInfo[userID]");
            $userFleet = mysqli_fetch_assoc($sql);
            // ↓ get all users movements, order by traveltime
            echo "<div class='user_attacks'>";
            $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE userID=$userInfo[userID] AND travelWay=1 AND missionType=1 ORDER BY travelTime ASC");
            if (mysqli_num_rows($sql) > 0) {
            $index = 0;
            while ($userTravel = mysqli_fetch_assoc($sql)) {
              //↓ gets username if the target isnt npc
              if ($userTravel["type"] != "npc") {
                $getTargetID = mysqli_query($conn, "SELECT userID FROM userfleet WHERE pageCoordsX=$userTravel[attackedUserX] AND pageCoordsY=$userTravel[attackedUserY] AND mapLocation=$userTravel[targetMapLocation]");
                $targetID = mysqli_fetch_assoc($getTargetID);
                $getTargetNick = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$targetID[userID]");
                $targetNick = mysqli_fetch_assoc($getTargetNick);
              } else { //↓ gets username if the target is npc
                $systemNum = ceil($userTravel["targetMapLocation"]/100);
                $system = "systems".$systemNum.""; //<-- decides from which table to select npc
                $getTargetNick = mysqli_query($conn, "SELECT npcType FROM $system WHERE coordsX=$userTravel[attackedUserX] AND coordsY=$userTravel[attackedUserY] AND map=$userTravel[targetMapLocation]");
                $targetNick = mysqli_fetch_assoc($getTargetNick);
                $targetNick["ingameNick"] = $targetNick["npcType"];
              }
              // ↓ create the actual container
              echo "<div class='lobby_wrapper lobby_wrapper_user".$index."'>";
              echo "<div class='lobby_left'><p>Mission type: <b style='color: red'>Attack</b></p>";
              echo "<span>Coordinates: ".$userTravel["targetMapLocation"].":".$userTravel["attackedUserX"].":".$userTravel["attackedUserY"]."</span></div>";
              echo "<div class='lobby_middle'><span>Target: ".$targetNick["ingameNick"]."</span>";
              echo "<span>LobbyID: #".$userTravel["lobbyID"]."</span>";
              echo "</div>";
              echo "<div class='lobby_right'><button type='button' class='btn_join' id='user' value='".$userTravel["lobbyID"]."'>&#9989; Join lobby</button>";
              echo "</div>";
              echo "</div>";
              $index++;
            }
          } else {
            echo "<p>None of your fleets are currently involved in any attacks</p>";
          }
            echo "</div>";
            // ↓ get all attacks heading towards user
            echo "<div class='enemy_attacks'>";
            $index = 0;
            $sql = mysqli_query($conn, "SELECT * FROM usermovement WHERE attackedUserX = $userFleet[pageCoordsX] AND attackedUserY = $userFleet[pageCoordsY] AND targetMapLocation = $userFleet[mapLocation] AND travelWay=1 AND missionType=1");
            if (mysqli_num_rows($sql) > 0) {
              while ($userIncAttacks = mysqli_fetch_assoc($sql)) {
                //↓ gets username if the target isnt npc
                $getAttackerNick = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$userIncAttacks[userID]");
                $attackerNick = mysqli_fetch_assoc($getAttackerNick);
                // ↓ create the actual container
                echo "<div class='lobby_wrapper lobby_wrapper_enemy".$index."'>";
                echo "<div class='lobby_left'><p>Mission type: <b style='color: red'>Incoming Attack</b></p>";
                echo "<span>Coordinates: ".$userIncAttacks["targetMapLocation"].":".$userIncAttacks["attackedUserX"].":".$userIncAttacks["attackedUserY"]."</span></div>";
                echo "<div class='lobby_middle'><span>Target: ".$attackerNick["ingameNick"]."</span>";
                echo "<span>LobbyID: #".$userIncAttacks["lobbyID"]."</span>";
                echo "</div>";
                echo "<div class='lobby_right'>";
                echo "<button type='button' class='btn_join' id='enemy' value='".$userIncAttacks["lobbyID"]."'>&#9989; Join lobby</button>";
                echo "</div>";
                echo "</div>";
                $index++;
              }
              echo "</div>";
            } else {
              echo "<p>No enemy fleet movement</p>";
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

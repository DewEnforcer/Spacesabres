<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
      $userInfo = mysqli_fetch_assoc($sql);
      $show = $userInfo;
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
 <html>
     <head>
       <meta charset="utf-8">
       <meta name=viewport content="width=device-width, initial-scale=1">
       <?php include "include/font.php"; ?>
       <link rel="stylesheet" href="css/game.css">
       <link rel="stylesheet" href="css/stats.css">
       <?php
       require 'include/head.php';
        ?>
       <title>SpaceSabres|FleetOverview</title>
     </head>
   <body>
     <?php
     require './include/nav.php';
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

       <section class="all_fleets_box">
           <?php
            $sql = mysqli_query($conn, "SELECT destroyedPoints, destroyedShips, destroyedHornets, destroyedSpacefires, destroyedStarhawks, destroyedPeacemakers, destroyedCenturions, destroyedNathalis, battlesWon, battlesLost, battlesDraw, battlesTotal FROM userfleet WHERE userID=$show[userID]");
            $userStats = mysqli_fetch_assoc($sql);
            ?>
             <section class="fleet_show_stats_box">
               <h2 align="center">Your all battle statistics</h2>
               <div class="fleet_show_stats_wrapper">
                 <div class="fleet_show_stats_destroyed">
                 <h3 align="center">Destruction statistics:</h3>
                 <span>Points of destruction in total: <?php echo $userStats["destroyedPoints"]; ?></span>
                 <span>Destroyed ships in total: <?php echo $userStats["destroyedShips"]; ?></span>
                 <span>Destroyed hornets in total: <?php echo $userStats["destroyedHornets"]; ?></span>
                 <span>Destroyed spacefires in total: <?php echo $userStats["destroyedSpacefires"]; ?></span>
                 <span>Destroyed starhawks in total: <?php echo $userStats["destroyedStarhawks"]; ?></span>
                 <span>Destroyed peacemakers in total: <?php echo $userStats["destroyedPeacemakers"]; ?></span>
                 <span>Destroyed centurions in total: <?php echo $userStats["destroyedCenturions"]; ?></span>
                 <span>Destroyed nathalis destroyers in total: <?php echo $userStats["destroyedNathalis"]; ?></span>
               </div>
               <div class="fleet_show_stats_battles">
                 <h3 align="center">Battle statistics:</h3>
                 <span>Battles won in total: <?php echo $userStats["battlesWon"]; ?></span>
                 <span>Battles lost in total: <?php echo $userStats["battlesLost"]; ?></span>
                 <span>Draw battles in total: <?php echo $userStats["battlesDraw"]; ?></span>
                 <span>Battles in total: <?php echo $userStats["battlesTotal"]; ?></span>
               </div>
              </div>
             </section>
       </section>
     </main>

     <footer>
     <?php require "include/footer.php"; ?>
     </footer>
   </body>
 </html>

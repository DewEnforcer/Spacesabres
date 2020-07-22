<?php
  require "./include/accessSecurity.php";
 ?>
 <html>
     <head>
       <meta charset="utf-8">
       <meta name="description" content="">
       <meta name=viewport content="width=device-width, initial-scale=1">
       <?php include "include/font.php"; ?>
       <link rel="stylesheet" href="../css/stylegame.css">
       <link rel="stylesheet" href="../css/styleDisplaystats.css">
       <script
     src="https://code.jquery.com/jquery-3.4.1.min.js"
     integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
     crossorigin="anonymous"></script>
       <script src="../js/search-player.js"></script>
       <script src="../js/gameinfo.js" charset="utf-8"></script>
       <script src="../js/backgroundmanager.js" charset="utf-8"></script>
       <title>SpaceSabres|FleetOverview</title>
     </head>
   <body>

     <header>
       <?php require "include/header.php"; ?>
 </header>

     <main>
       <section class="searchPopup">

       </section>

       <section class="all_fleets_box">
           <h1>Statistics</h1>
           <div class="navbar_fleet_over">
             <a href="internalFleets.php" class="button_see_movement">Fleet movement</a>
             <a href="internalBattlelobbies.php" class="button_see_movement">Commander HUD</a>
             <a href="internalDisplayfleet.php" class="button_see_fleet">Docks</a>
             <a href="internalDisplaystats.php" class="button_see_stats">Battle statistics</a>
           </div>
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

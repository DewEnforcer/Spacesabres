<?php
session_start();
if (isset($_SESSION["sid"])) {
  require "include/dbh.inc.php";
  $session = $_SESSION["sid"];
  $sql = mysqli_query($conn, "SELECT userID, userclan FROM users WHERE sessionID='$session'");
  if (mysqli_num_rows($sql)>0) {
    $userInfo = mysqli_fetch_assoc($sql);
    "";
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
      <link rel="stylesheet" href="css/alliancestart.css">
      <?php
      require 'include/head.php';
       ?>
    <script src="js/alliance.js"></script>
    <title>SpaceSabres||Alliances</title>
  </head>
  <body>
    <?php
      require 'include/nav.php';
      ?>
    <div class="opacity_box">

    </div>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>
      <?php
      require '../include/allianceResults.php';
       ?>

      <section class="searchPopup">

      </section>
      <section class="gameinfo">
        <div class="game_leader">

        </div>
        <div class="special_thanks">

        </div>
        <div class="forum_info">

        </div>
      </section>

      <section class="ally_main_box">
        <h2>List of interplanetary alliances</h2>
        <div class="ally_wrapper_box">
          <div class="ally_navbar">
            <ul class="ally_nav">
              <?php
                if ($userInfo["userclan"] == "none")  {
                  echo "<a href=\"internalAlliances.php\">Find alliance</a>";
                  echo "<a href=\"internalJoinally.php\">Join alliance</a>";
                  echo "<a href=\"internalCreateally.php\">Create alliance</a>";
                } else {
                  echo "<a href=\"internalAlliance.php\">Alliance</a>";
                  echo "<a href=\"internalAllianceMembers.php\">Alliance members</a>";
                  echo "<a href=\"internalAllianceMsg.php\">Send alliance message</a>";
                  echo "<a href=\"internalAllianceMsg.php\">Alliance diplomacy</a>";
                }
               ?>
            </ul>
          </div>
          <?php
            if ($userInfo["userclan"] == "none") {
              echo "<div class=\"ally_search_box\" >";
              $i = 0;
              $sql = mysqli_query($conn, "SELECT * FROM userclans ORDER BY clanName");
              if (mysqli_num_rows($sql) > 0) {
                echo "<table class=\"ally_search_table\">";
                echo "<tr class='first_row'>";
                echo "<th>Alliance name</th>";
                echo "<th>Alliance tag</th>";
                echo "<th>Alliance total members</th>";
                echo "<th>Alliance total points</th>";
                echo "</tr>";
                while ($clans = mysqli_fetch_assoc($sql)) {
                  echo "<tr class=\"ally_row\" id=$i>";
                  echo "<td>$clans[clanName]</td>";
                  echo "<td>$clans[clanTag]</td>";
                  echo "<td>$clans[totalMembers]</td>";
                  echo "<td>$clans[totalPoints]</td>";
                  echo "</tr>";
                  $i++;
                }
                echo "</table>";
              } else {
                echo "<span style=\"text-align: center\">There are no alliances available!</span>";
              }
              echo "</div>";
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

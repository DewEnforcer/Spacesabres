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
    <link rel="stylesheet" href="../css/styleAlly.css">
    <?php require "include/scripts.php"; ?>
    <script src="../js/alliance.js"></script>
    <title>SpaceSabres||Alliances</title>
  </head>
  <body>
    <div class="opacity_box">

    </div>
        <?php require "include/header.php"; ?>
    <main>
      <?php
        require "include/bars.php";
        require './include/allianceResults.php';
       ?>
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

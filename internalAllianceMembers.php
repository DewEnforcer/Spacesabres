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
    <link rel="stylesheet" href="../css/styleAllyIn.css">
    <?php require "include/scripts.php"; ?>
    <script src="../js/alliance.js"></script>
    <script>
    var allMembers = [<?php
    $sql = mysqli_query($conn, "SELECT clanMembers FROM userclans WHERE clanTag='$userInfo[userclan]'");
    $clanMembers = mysqli_fetch_assoc($sql);
    $arrayMembers = unserialize($clanMembers["clanMembers"]);
    $ammount = count($arrayMembers);
    $i = 1;
    foreach ($arrayMembers as $key) {
      $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$key");
      $fetch = mysqli_fetch_assoc($sql);
      if ($i == $ammount) {
        echo "\"$fetch[ingameNick]\"";
      } else {
        echo "\"$fetch[ingameNick]\",";
      }

      $i++;
    }
     ?>]
    </script>
    <title>SpaceSabres||Members</title>
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
        <h2>Interplanetary alliance</h2>
        <div class="ally_wrapper_box">
          <div class="ally_navbar">
            <ul class="ally_nav">
              <a href="internalAlliance.php">Alliance</a>
              <a href="internalAllianceMembers.php">Alliance members</a>
              <a href="internalAllianceMsg.php">Send alliance message</a>
              <a href="internalAllianceDiplo.php">Alliance diplomacy</a>
            </ul>
          </div>
        <div class="ally_overview">
          <h3>List of all members</h3>
          <div class="members_list">
            <?php
              $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanApps FROM userclans WHERE clanTag='$userInfo[userclan]'");
              $clanInfo = mysqli_fetch_assoc($sql);
              $membersList = unserialize($clanInfo["clanMembers"]);
              $applications = unserialize($clanInfo["clanApps"]);
              $permissions = unserialize($clanInfo["clanMembersPerms"]);
              $getPermissionUser = array_search($userInfo["userID"], $membersList);
              $permissionUser = $permissions[$getPermissionUser];
              $i = 0;
              $rankArray = ["empty", "Ensign", "Basic Lieutenant", "Lieutenant", "Lieutenant Commander", "Commander", "Captain", "Rear Admiral", "Vice Admiral", "Admiral", "Fleet Admiral"];
              foreach ($membersList as $key) {
                $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$key");
                $nick = mysqli_fetch_assoc($sql);

                $sql = mysqli_query($conn, "SELECT pageCoordsX, pageCoordsY, mapLocation, rank FROM userfleet WHERE userID=$key");
                $fleet = mysqli_fetch_assoc($sql);

                $memberPermissions = $permissions[$i];
                $rankAlly = $memberPermissions[8];

                switch ($fleet["rank"]) {
                  case '1':
                    $rankName = "Ensign";
                  break;
                  case '2':
                    $rankName = "Basic Lieutenant";
                  break;
                  case '3':
                    $rankName = "Lieutenant";
                  break;
                  case '4':
                    $rankName = "Lieutenant Commander";
                  break;
                  case '5':
                    $rankName = "Commander";
                  break;
                  case '6':
                    $rankName = "Captain";
                  break;
                  case '7':
                    $rankName = "Rear Admiral";
                  break;
                  case '8':
                    $rankName = "Vice Admiral";
                  break;
                  case '9':
                    $rankName = "Admiral";
                  break;
                  case '10':
                    $rankName = "Fleet Admiral";
                  break;
                  case '11':
                    $rankName = "Administrator";
                  break;
                }

                switch ($rankAlly) {
                  case '1':
                    $rankAllyName = "Ensign";
                  break;
                  case '2':
                    $rankAllyName = "Lieutenant";
                  break;
                  case '3':
                    $rankAllyName = "Captain";
                  break;
                  case '4':
                    $rankAllyName = "Admiral";
                  break;
                  case '5':
                    $rankAllyName = "Leader";
                  break;
                }

                echo "<div class='ally_member'>";
                echo "<div class=\"ally_member_left\">";
                echo "<span class=\"nickname_popup_$i\" id=\"$nick[ingameNick]\">Username: $nick[ingameNick]</span>";
                echo "<span>Position: $fleet[mapLocation]:$fleet[pageCoordsX]:$fleet[pageCoordsY]</span>";
                echo "<span>Rank: $rankName</span>";
                echo "<span>Alliance rank: $rankAllyName</span>";
                echo "</div>";
                echo "<div class=\"ally_member_right\">";
                if ($permissionUser[0] == 1 && $permissionUser[8] > $memberPermissions[8] && $key != $userInfo["userID"]) {
                  echo "<a href=\"./include/allianceHandler.php?action=kick&&index=$i\">Kick commander from alliance</a>";
                }
                if ($permissionUser[8] == 5 && $key != $userInfo["userID"]) {
                  echo "<button type=\"btn_manage_rank\" class=\"btn_manage_rank\" id=\"$i\">Change commanders alliance rank</button>";
                }
                if ($permissionUser[8] != 5 && $key == $userInfo["userID"]) {
                  echo "<a href=\"./include/allianceHandler.php?action=leave\">Leave alliance</a>";
                } elseif ($permissionUser[8] == 5 && $userInfo["userID"] == $key) {
                  echo "<button type=\"btn_give_leader\" class=\"btn_give_leader\">Resign on alliance leader</button>";
                  echo "<button type=\"btn_disband_ally\" class=\"btn_disband_ally\">Disband alliance</button>";
                }
                echo "</div>";
                echo "</div>";
                $i++;
              }
             ?>
          </div>
          <div class="apps_list">
            <?php
            if (empty($applications) === FALSE) {
              foreach ($applications as $key) {
                $sql = mysqli_query($conn, "SELECT ingameNick FROM users WHERE userID=$key");
                $nick = mysqli_fetch_assoc($sql);
                $i = 0;
                $sql = mysqli_query($conn, "SELECT rank FROM userfleet WHERE userID=$key");
                $fleet = mysqli_fetch_assoc($sql);

                switch ($fleet["rank"]) {
                  case '1':
                    $rankName = "Ensign";
                  break;
                  case '2':
                    $rankName = "Basic Lieutenant";
                  break;
                  case '3':
                    $rankName = "Lieutenant";
                  break;
                  case '4':
                    $rankName = "Lieutenant Commander";
                  break;
                  case '5':
                    $rankName = "Commander";
                  break;
                  case '6':
                    $rankName = "Captain";
                  break;
                  case '7':
                    $rankName = "Rear Admiral";
                  break;
                  case '8':
                    $rankName = "Vice Admiral";
                  break;
                  case '9':
                    $rankName = "Admiral";
                  break;
                  case '10':
                    $rankName = "Fleet Admiral";
                  break;
                }

                echo "<div class='ally_app'>";
                echo "<div class=\"ally_app_left\">";
                echo "<span>Username: $nick[ingameNick]</span>";
                echo "<span>Rank: $rankName</span>";
                echo "</div>";
                echo "<div class=\"ally_app_right\">";
                if ($permissionUser[5] == 1) {
                  echo "<button type=\"btn_read_app\" class=\"btn_read_app\" id=\"$i\">Read commanders application</button>";
                  echo "<a href=\"./include/allianceHandler.php?action=accept&&index=$i\">Accept this commander</a>";
                  echo "<a href=\"./include/allianceHandler.php?action=decline&&index=$i\">Decline this application</a>";
                }
                echo "</div>";
                echo "</div>";
                $i++;
              }
            } else {
              echo "<span>No applications.</span>";
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

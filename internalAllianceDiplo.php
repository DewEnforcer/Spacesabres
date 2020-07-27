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
    <title>SpaceSabres||Diplomacy</title>
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
            <?php require "include/allyNav.php" ?>
            </ul>
          </div>
          <?php
          $sql = mysqli_query($conn, "SELECT clanMembers, clanMembersPerms, clanDiplo, clanDiploReq FROM userclans WHERE clanTag='$userInfo[userclan]'");
          $clanInfo = mysqli_fetch_assoc($sql);

          $clanDiplomacy = unserialize($clanInfo["clanDiplo"]);
          $clanDiplomacyRequest = unserialize($clanInfo["clanDiploReq"]);
          $membersList = unserialize($clanInfo["clanMembers"]);
          $permissions = unserialize($clanInfo["clanMembersPerms"]);
          $getPermissionUser = array_search($userInfo["userID"], $permissions);
          $permissionUser = $permissions[$getPermissionUser];
          $i = 0;
           ?>
        <div class="ally_overview_diplo">
          <h3>Alliance diplomacy</h3>
          <div class="diplo_tools_box">
            </form>
            <?php
              if ($permissionUser[1] == 1 || $permissionUser[2] == 1) {
                echo "<button type=\"button\" class=\"btn_send_diplo\">Send diplomacy requests</button>";
                echo "<button type=\"button\" class=\"btn_show_diplo\">Show all pending requests</button>";
              } else {
                echo "<span>You don't have permissions to access these tools.</span>";
              }
             ?>
          </div>
          <div class="diplo_current_list">
            <h2>List of all relations</h2>
            <?php
            if (empty($clanDiplomacy) == FALSE) {
              echo '<table class="diplo_table">
                <tr>
                  <th>Tag | Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>';
            foreach ($clanDiplomacy as $key) {
              $sql = mysqli_query($conn, "SELECT clanName, clanTag FROM userclans WHERE clanID=$key[0]");
              if (mysqli_num_rows($sql) > 0) {
                $diploClanInfo = mysqli_fetch_assoc($sql);
                switch ($key[1]) {
                  case '1':
                  $status = "<span style=\"color: rgb(80,220,100)\">Alliance</span>";
                  $buttonType = "Alliance";
                  break;
                  case '2':
                  $status = "<span style=\"color: rgb(252,209,42)\">Non-agression pact</span>";
                  $buttonType = "NAP";
                  break;
                  case '3':
                  $status = "<span style=\"color: red\">War</span>";
                  $buttonType = "War";
                  break;
                }
                echo "<tr class=\"ally_diplo\">";
                echo "<td>[$diploClanInfo[clanTag]] $diploClanInfo[clanName]</td>";
                echo "<td>$status</td>";
                if ($key[1] == 1 || $key[1] == 2) {
                  if ($permissionUser[3] == 1) {
                    echo "<td><a href=\"./include/allianceHandler.php?action=cancelNormal&index=$i\">End $buttonType</td>";
                  }
                } else if ($status == 3) {
                  if ($permissionUser[4] == 1) {
                    echo "<td><a href=\"./include/allianceHandler.php?action=cancelWar&index=$i\">End $buttonType</td>";
                  }
                }
                echo "</tr>";
              } else {
                array_splice($clanDiplomacy, $i, 1);
              }
              $i++;
            }
          } else {
            echo "<span>No relations with other alliances have been started.</span>";
          }
          echo "</table>";
             ?>
        </div>
          <div class="diplo_request_list">
            <div class="ally_diplo_req_header">
              <h2>List of all requested diplomacy acts</h2>
            </div>
            <?php
            $i = 0;
            if (empty($clanDiplomacyRequest)=== FALSE) {
            foreach ($clanDiplomacyRequest as $key) {
              $sql = mysqli_query($conn, "SELECT clanName, clanTag FROM userclans WHERE clanID=$key[0]");
              if (mysqli_num_rows($sql) > 0) {
                $diploClanInfo = mysqli_fetch_assoc($sql);
                switch ($key[1]) {
                  case '1':
                  $status = "<span style=\"color: rgb(80,220,100)\">Alliance</span>";
                  $buttonType = "Alliance";
                  break;
                  case '2':
                  $status = "<span style=\"color: rgb(252,209,42)\">Non-agression pact</span>";
                  $buttonType = "NAP";
                  break;
                }
                echo "<div class=\"ally_diplo_req\">";
                echo "<span>[$diploClanInfo[clanTag]] $diploClanInfo[clanName]</span>";
                echo "$status";
                  if ($permissionUser[1] == 1) {
                    echo "<a style=\"color: green \" href=\"./include/allianceHandler.php?action=acceptDiplo&index=$i\">Accept $buttonType</a>";
                    echo "<a style=\"color: red\" href=\"./include/allianceHandler.php?action=declineDiplo&index=$i\">Decline $buttonType</a>";
                  }
                echo "</div>";
              } else {
                array_splice($clanDiplomacyRequest, $i, 1);
                $serializedDiplo = serialize($clanDiplomacyRequest);
                $sql = mysqli_query($conn, "UPDATE userclans SET clanDiplo='$serializedDiplo' WHERE clanTag='$userInfo[userclan]'");
              }
              $i++;
            }
          } else {
            echo "<span>No requests.</span>";
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

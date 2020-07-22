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
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143336464-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-143336464-1');
  </script>
    <script src="../js/search-player.js"></script>
    <script src="../js/gameinfo.js"></script>
    <script src="../js/backgroundmanager.js"></script>
    <script src="../js/alliance.js"></script>
    <title>SpaceSabres||Alliance</title>
  </head>
  <body>
    <div class="opacity_box">

    </div>
      <header>
        <?php require "include/header.php"; ?>

  </header>

    <main>

      <?php
      require './include/allianceResults.php';
       ?>
      <section class="searchPopup">

      </section>
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
        <div class="ally_overview_home">
          <h2>Alliance info</h2>
          <div class="overview_header">
            <div class="overview_img_div">
              <h2>Alliance Logo</h2>
            <?php
            $sql = mysqli_query($conn, "SELECT createDate, clanID, clanImgID, clanImgStatus, clanDetail, clanName, clanTag, clanLeader, clanContact, totalMembers, totalPoints, clanMembers, clanMembersPerms, clanLog FROM userclans WHERE clanTag='$userInfo[userclan]'");
            $userClan = mysqli_fetch_assoc($sql);

            $arrayMembers = unserialize($userClan["clanMembers"]);
            $findKeyArray = array_search($userInfo["userID"], $arrayMembers);

            $arrayPerms = unserialize($userClan["clanMembersPerms"]);
            $userPermissions = $arrayPerms[$findKeyArray];

            if ($userPermissions[7] == 1) {
              $edit = true;
            } else {
              $edit = false;
            }

            if ($userPermissions[8] == 5) {
              $leader = true;
            } else {
              $leader = false;
            }
            if ($userClan['clanImgStatus'] == 1) {
              $filename = "./uploads/clanavtr/clanavtr".$userClan["clanID"]."*";

              $fileinfo = glob($filename);
              $fileExt = explode(".", $fileinfo[0]);
              $fileActext = $fileExt[2];
              echo "<img class='ally_avatar' src='./uploads/clanavtr/clanavtr".$userClan["clanID"].".".$fileActext."?".mt_rand()."'>";
            } else {
              echo "<img class='ally_avatar' src='./uploads/clanavtr/clanavtr0.png'>";
            }
            if ($edit == true) {
              echo "<button type='button' class='btn_edit_avatar'>Change logo</button>";
            }
            ?>
            </div>
            <div class="header_info">
              <div class="header_info_wrapper">
              <?php
              echo "<span class=\"ally_name_span\" id=\"$userClan[clanName]\">Alliance name: $userClan[clanName]</span>";
              echo "<span class=\"ally_tag_span\" id=\"$userClan[clanTag]\">Alliance tag: $userClan[clanTag]</span>";
              echo "<span>Alliance leader: $userClan[clanLeader]</span>";
              echo "<span>Creation date: ".date("d.m.Y", $userClan["createDate"])."</span>";
              echo "<span>Total members: $userClan[totalMembers]</span>";
              echo "<span>Alliance points: $userClan[totalPoints]</span>";
              $position = 1;
              $sql = mysqli_query($conn, "SELECT clanID FROM userclans ORDER BY totalPoints DESC");
              while ($row = mysqli_fetch_assoc($sql)) {
                if ($row["clanID"] == $userClan["clanID"]) {
                  break;
                } else {
                  $position++;
                }
              }
              echo "<span>Alliance position: $position</span>";
              if ($leader == true) {
                echo "<button type='button' class='btn_edit_name'>&#10000Change alliance name</button>";
              }
               ?>
             </div>
            </div>
          </div>
          <div class="overview_main">
            <div class="overview_desc">
              <?php
              if ($edit == true) {
                echo "<button type='button' class='btn_edit_desc'>&#10000;Edit</button>";
              }
              echo "<p class=\"ally_desc_p\">$userClan[clanDetail]</p>";
               ?>
            </div>
            <div class="overview_logs">
              <?php
                $logsArray = unserialize($userClan["clanLog"]);
                if (!empty($logsArray)) {
                  foreach ($logsArray as $key) {
                  echo "<span>$key</span>";
                  }
                } else {
                  echo "<div>No events have occured yet.</div>";
                }
               ?>
            </div>
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
